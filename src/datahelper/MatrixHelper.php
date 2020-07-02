<?php

namespace UglyRecommender\DataHelper;

class MatrixHelper {
    
    public function __construct(){
    }

    public function fillEmptyValues(&$dataMatrix,&$all_unique_keys,$empty_value=0,$axis="x"){
        if ($axis=="x"){
            $aucount=count($all_unique_keys);
            $keys=array_keys($dataMatrix);
            foreach ($keys as $k){
                for($x=0;$x<$aucount;$x++){
                    $sk=$all_unique_keys[$x];
                    if (!isset($dataMatrix[$k][$sk])){
                        $dataMatrix[$k][$sk]=$empty_value;
                    }
                }
            }
        }
    }

    public function pivotDataMatrix(&$dataMatrix){
        $newMatrix=array();
        $keys=array_keys($dataMatrix);
        $items=$dataMatrix[$keys[0]];
        $ikeys=array_keys($items);
        foreach ($keys as $k){    
            foreach ($ikeys as $ik){
                if (!isset($newMatrix[$ik])){
                    $newMatrix[$ik]=array();
                }
                $val=$dataMatrix[$k][$ik];
                unset($dataMatrix[$k][$ik]);
                $newMatrix[$ik][$k]=$val;
            }
            unset($dataMatrix[$k]);
        }
        unset($dataMatrix);
        unset($ikeys);
        unset($items);
        unset($keys);
        return $newMatrix;
    }
}

?>