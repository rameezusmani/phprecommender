<?php

namespace UglyRecommender\DataHelper;

class CsvLoadHelper {
    public static function load($filename,$first_row_header=true,$key_column=0,$item_column=1,$value_column=2,$max_records=-1){
        $dataMatrix=array();
        $fp=fopen($filename,"r");
        if ($first_row_header){
            //ignore header row
            fgetcsv($fp);
        }
        $num_records=0;
        
        while(($row=fgetcsv($fp))!==FALSE){
            $matrixKey="".$row[$key_column]."";
            $itemKey="".$row[$item_column]."";
            if (!isset($dataMatrix[$matrixKey])){
                $dataMatrix[$itemKey]=array();
            }
            $dataMatrix[$matrixKey][$itemKey]=floatval($row[$value_column]);
            $num_records++;
            if ($max_records!=-1 && $num_records==$max_records)
                break;
        }
        fclose($fp);
        return $dataMatrix;
    }
}


?>