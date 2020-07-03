<?php

namespace UglyRecommender;

use UglyRecommender\DataHelper\MatrixHelper;

class RecommenderSystem {
    //main matrix to hold data
    //first column contains subject key
    //starting from second column, first row contains object key
    //starting from second column and second row are value for subject+object
    private $dataMatrix=array();
    private $useWeightedDistance=false;
    private $distanceMethod="cosine";
    private $unknownValue=0;

    public function setDataMatrix($dMatrix){
        $this->dataMatrix=$dMatrix;
    }

    public function getDataMatrix(){
        return $this->dataMatrix;
    }

    public function setUseWeightedDistance($wd){
        $this->useWeightedDistance=$wd;
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

    public function getNeighborsWithDistance($y_label,$x_label="",$max_count=-1){
        $subjectVector=$this->buildVectorFromDataMatrix($y_label);
        if ($subjectVector==FALSE){
            //subject is not in matrix
            throw new SubjectNotFoundException("No row with label ".$y_label." in matrix");
        }
        $method=$this->distanceMethod;
        $neighbors=array();
        foreach ($this->dataMatrix->y_labels as $y){
            if ($max_count!=-1 && count($neighbors)==$max_count)
                break;
            if ($y==$y_label){
                continue;
            }
            if ($x_label!="" && $this->dataMatrix[$k][$itemKey]==$this->unknownValue){
                continue;
            }
            $otherVector=$this->buildVectorFromDataMatrix($k);
            if ($this->isVectorEmpty($otherVector)){
                unset($otherVector);
                continue;
            }
            $distance=0;
            if ($method=="cosine"){
                $distance=(1-DistanceCalculator::cosineOfAngle($subjectVector,$otherVector));
            }else if ($method=="manhattan"){
                $distance=DistanceCalculator::manhattanDistance($subjectVector,$otherVector);
            }else if ($method=="euclidean"){
                $distance=DistanceCalculator::euclideanDistance($subjectVector,$otherVector);
            }
            $neighbors[]=$this->buildNeighbor($k,$distance);
            unset($otherVector);
        }
        unset($keys);
        unset($subjectVector);
        return $neighbors;
    }

    /*public function getOrderedNeighbors($subjectKey,$itemKey="",$count=5){
        if ($count<=0){
            throw new Exception("count must be > 0");
        }
        $neighbors=$this->getNeighborsWithDistance($subjectKey,$itemKey);
        usort($neighbors,function($a,$b){
            if($a['distance']==$b['distance']){ return 0 ; } 
            return ($a['distance'] < $b['distance']) ? -1 : 1;
        });
        if ($count>0){
            array_splice($neighbors,$count);
        }
        return $neighbors;
    }

    public function predictValue($subjectKey,$itemKey,$max_neighbors=5){
        $neighbors_count=$max_neighbors;
        $neighbors=$this->getOrderedNeighbors($subjectKey,$itemKey,$max_neighbors);
        $value=0;
        if (count($neighbors)<=0){
            throw new NeighborsNotFoundException("No neighbors found");
        }
        if (count($neighbors)<$neighbors_count){
            $neighbors_count=count($neighbors);
        }
        $total_weights=1;
        for($a=0;$a<$neighbors_count;$a++){
            $nval=$this->dataMatrix[$neighbors[$a]["key"]][$itemKey];
            $nweight=1;
            $value+=floatval($nval)*$nweight;
            $total_weights+=$nweight;
        }
        $value=$value/$total_weights;
        unset($neighbors);
        return $value;
    }

    public function getRecommendations($subjectKey,$max_neighbors=5,$max_count=5){
        $neighbors_count=$max_neighbors;
        $neighbors=$this->getOrderedNeighbors($subjectKey,"",$max_neighbors);
        if (count($neighbors)<=0){
            throw new NeighborsNotFoundException();
        }
        if (count($neighbors)<$neighbors_count){
            $neighbors_count=count($neighbors);
        }
        $recommendations=array();
        $rated_movies=array();
        foreach ($neighbors as $n){
            $items=$this->dataMatrix[$n["key"]];
            //remove items that $subjectKey has already rated
            $keys=array_keys($items);
            foreach($keys as $k){
                if ($this->dataMatrix[$subjectKey][$k]!=$this->unknownValue){
                    unset($items[$k]);
                }
            }
            $keys=array_keys($items);
            foreach($keys as $k){
                if (floatval($items[$k])!=$this->unknownValue){
                    if (!isset($rated_movies[$k])){
                        $rated_movies[$k]=array("value"=>0,"count"=>0,"key"=>$k);
                    }
                    $rated_movies[$k]["value"]+=floatval($items[$k]);
                    $rated_movies[$k]["count"]+=1;
                }
            }
        }
        usort($rated_movies,function($a,$b){
            if($a['value']==$b['value']){ return 0 ; } 
            return ($a['value'] > $b['value']) ? -1 : 1;
        });
        foreach ($rated_movies as $r){
            $r['value']=floatval($r['value']/$r['count']);
            $recommendations[]=$r;
        }
        unset($rated_movies);
        array_splice($recommendations,$max_count);
        return $recommendations;
    }*/
}


?>