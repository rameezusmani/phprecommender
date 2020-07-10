<?php

namespace UglyRecommender;

use UglyRecommender\DataHelper\MatrixHelper;

class RecommenderSystem {
    private $dataMatrix=array();
    private $distanceMethod="cosine";
    private $unknownValue=0;

    public function setDataMatrix($dMatrix){
        $this->dataMatrix=$dMatrix;
    }

    public function getDataMatrix(){
        return $this->dataMatrix;
    }

    public function setDistanceMethod($m){
        $this->distanceMethod=$m;
    }

    public function setUnknownValue($v){
        $this->unknownValue=$v;
    }

    private function isVectorEmpty($vector){
        foreach ($vector as $v){
            if ($v!=$this->unknownValue){
                return false;
            }
        }
        return true;
    }    

    private function buildVectorFromDataMatrix($key){
        return $this->dataMatrix->get_row($key);
    }

    private function buildNeighbor($key,$distance){
        $neighbor=array("key"=>$key,"distance"=>$distance);
        return $neighbor;
    }

    private function getVectorsDistance($vectorA,$vectorB,$method){
        $distance=0;
        if ($method=="cosine"){
            $distance=(1-DistanceCalculator::cosineOfAngle($vectorA,$vectorB));
        }else if ($method=="manhattan"){
            $distance=DistanceCalculator::manhattanDistance($vectorA,$vectorB);
        }else if ($method=="euclidean"){
            $distance=DistanceCalculator::euclideanDistance($vectorA,$vectorB);
        }
        return $distance;
    }

    private function _getDistanceFromNeighbors($y_label,$x_label="",$max_count=-1){
        if ($max_count!=-1 && $max_count<=0){
            throw new Exception("max_count must be -1 or > 0");
        }
        $subjectVector=$this->buildVectorFromDataMatrix($y_label);
        if ($subjectVector==FALSE){
            throw new SubjectNotFoundException("No row with label ".$y_label." in matrix");
        }
        $method=$this->distanceMethod;
        $neighbors=array();
        foreach ($this->dataMatrix->y_labels as $y){
            if ($max_count!=-1 && count($neighbors)==$max_count)
                break;
            if (($y==$y_label)
                || ($x_label!="" && $this->dataMatrix->get_value($y,$x_label)==$this->unknownValue)){
                continue;
            }
            $otherVector=$this->buildVectorFromDataMatrix($y);
            if ($this->isVectorEmpty($otherVector)){
                unset($otherVector);
                continue;
            }
            $distance=$this->getVectorsDistance($subjectVector,$otherVector,$method);
            $neighbors[]=$this->buildNeighbor($y,$distance);
            unset($otherVector);
        }
        unset($subjectVector);
        return $neighbors;
    }

    public function getNearestNeighbors($y_label,$x_label="",$count=5){
        if ($count<=0){
            throw new Exception("count must be > 0");
        }
        $neighbors=$this->_getDistanceFromNeighbors($y_label,$x_label,-1);
        usort($neighbors,function($a,$b){
            if($a['distance']==$b['distance']){ return 0 ; } 
            return ($a['distance'] < $b['distance']) ? -1 : 1;
        });
        array_splice($neighbors,$count);
        return $neighbors;
    }

    public function predict($y_label,$x_label,$max_neighbors=5,$weights=[]){
        $neighbors_count=$max_neighbors;
        $use_weights=count($weights)>0;
        $neighbors=$this->getNearestNeighbors($y_label,$x_label,$max_neighbors);
        $value=0;
        if (count($neighbors)<=0){
            throw new NeighborsNotFoundException("No neighbors found");
        }
        $total_weights=1;
        $n_index=0;
        foreach ($neighbors as $n){
            $nval=$this->dataMatrix->get_value($n["key"],$x_label);
            $nweight=1;
            if ($use_weights){
                if (isset($weights[$n_index])){
                    $nweight=$weights[$n_index];
                }
            }
            $value+=floatval($nval)*$nweight;
            $total_weights+=$nweight;
            $n_index++;
        }
        $value=$value/$total_weights;
        unset($neighbors);
        return $value;
    }

    public function getRecommendations($y_label,$max_neighbors=100,$max_count=5){
        $neighbors_count=$max_neighbors;
        $neighbors=$this->getNearestNeighbors($y_label,"",$max_neighbors);
        if (count($neighbors)<=0){
            throw new NeighborsNotFoundException();
        }
        if (count($neighbors)<$neighbors_count){
            $neighbors_count=count($neighbors);
        }
        $recommendations=array();
        $rated_movies=array();
        $y_row=$this->dataMatrix->get_row($y_label);
        foreach ($neighbors as $n){
            $k=$n["key"];
            $items=$this->dataMatrix->get_row($k);
            //remove items that $subjectKey has already rated
            $keys=array_keys($items);
            foreach($keys as $k){
                if ($this->dataMatrix->get_value_from_row($y_row,$k)!=$this->unknownValue){
                    unset($items[$k]);
                }
            }
            $x_index=0;
            foreach ($items as $i){
                if ($i!=$this->unknownValue){
                    $x_key=$this->dataMatrix->x_labels[$x_index];
                    if (!isset($rated_movies[$x_key])){
                        $rated_movies[$x_key]=array("value"=>0,"count"=>0,"key"=>$x_key,"x_index"=>$x_index);
                    }
                    $rated_movies[$x_key]["value"]+=$i;
                    $rated_movies[$x_key]["count"]+=1;
                }
                $x_index++;
            }
        }
        usort($rated_movies,function($a,$b){
            if($a['value']==$b['value']){ return 0 ; } 
            return ($a['value'] > $b['value']) ? -1 : 1;
        });
        for ($a=0;$a<count($rated_movies);$a++){
            $r=$rated_movies[$a];
            $r['value']=floatval($r['value']/$r['count']);
            $rated_movies[$a]=$r;
        }
        array_splice($rated_movies,$max_count);
        return $rated_movies;
    }
}


?>