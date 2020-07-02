<?php

namespace UglyRecommender\DataHelper;

use UglyRecommender\DataMatrix;

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

    public static function loadDataMatrix($filename,$first_row_header=true,$x_column=1,$y_column=0,$value_column=2,$max_records=10){
        $dataMatrix=new DataMatrix();
        $fp=fopen($filename,"r");
        if ($first_row_header){
            $row=fgetcsv($fp);
            if ($row!==FALSE){
                //get labels
                $dataMatrix->row_label=$row[$y_column];
                $dataMatrix->column_label=$row[$x_column];
            }
        }
        $num_records=0;
        $last_key="";
        $matrix_row=[];
        while(($row=fgetcsv($fp))!==FALSE){
            $key=trim($row[$y_column]);
            if ($key!=$last_key && $last_key!=""){
                $dataMatrix->add_label($last_key,"y");
                $dataMatrix->add_row($matrix_row);
                unset($matrix_row);
                $num_records++;    
                if ($max_records!=-1 && $num_records==$max_records){
                    break;
                }
                $matrix_row=[];
            }
            $last_key=$key;
            $x_label=trim($row[$x_column]);
            $dataMatrix->add_label($x_label,"x");
            $idx=$dataMatrix->get_label_index($x_label,"x");
            $matrix_row[$idx]=floatval(trim($row[$value_column]));
        }
        fclose($fp);
        return $dataMatrix;
    }
}


?>