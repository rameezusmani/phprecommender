<?php

namespace UglyRecommender;

class RecommenderSystem {
    //main matrix to hold data
    //first column contains subject key
    //starting from second column, first row contains object key
    //starting from second column and second row are value for subject+object
    private $dataMatrix=array();
    private $useWeightedDistance=false;
    private $distanceMethod="cosine";

    public function setDataMatrix($dMatrix){
        $this->dataMatrix=$dMatrix;
    }

    public function setWeightedDistance($wd){
        $this->useWeightedDistance=$wd;
    }

    public function setDistanceMethod($m){
        $this->distanceMethod=$m;
    }

    private function buildVectorFromDataMatrix($key){
        $vector=array();
        foreach ($this->dataMatrix[$key] as $sval){
            $vector[]=$sval;
        }
        return $vector;
    }

    private function buildNeighbor($key,$distance){
        $neighbor=array("key"=>$key,"distance"=>$distance);
        return $neighbor;
    }

    public function getNeighborsWithDistance($subjectKey,$max_count=-1){
        if (!isset($this->dataMatrix[$subjectKey])){
            //subject is not in matrix
            throw new SubjectNotFoundException("Subject with key ".$subjectKey." not in dataMatrix");
        }
        $method=$this->distanceMethod;
        $subjectVector=$this->buildVectorFromDataMatrix($subjectKey);
        $neighbors=array();
        $keys=array_keys($this->dataMatrix);
        foreach ($keys as $k){
            if ($max_count!=-1 && count($neighbors)==$max_count)
                break;
            if ($k==$subjectKey){
                continue;
            }
            $distance=0;
            $otherVector=$this->buildVectorFromDataMatrix($k);
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

    public function getOrderedNeighbors($subjectKey,$count=5,$order_sort="asc"){
        if ($count<=0){
            throw new Exception("count must be > 0");
        }
        $neighbors=$this->getNeighborsWithDistance($subjectKey);
        if ($order_sort=="asc"){
            usort($neighbors,function($a,$b){
                if($a['distance']==$b['distance']){ return 0 ; } 
                return ($a['distance'] < $b['distance']) ? -1 : 1;
            });
        }else{
            usort($neighbors,function($a,$b){
                if($a['distance']==$b['distance']){ return 0 ; } 
                return ($a['distance'] > $b['distance']) ? -1 : 1;
            });
        }
        array_splice($neighbors,$count);
        return $neighbors;
    }

    public function predictValue($subjectKey,$itemKey,$max_count=5){
        $neighbors_count=$max_count;
        $neighbors=$this->getOrderedNeighbors($subjectKey,$max_count);
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
    
}


?>