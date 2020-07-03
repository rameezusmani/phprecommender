<?php

namespace UglyRecommender;

use UglyRecommender\Matrix;

class DataMatrix extends Matrix {
    public $row_label=""; //y axis label
    public $column_label=""; //x axis label

    public $x_labels=[]; //these are labels/identifiers of each column
    public $y_labels=[]; //these are labels/identifiers of each row
    
    public function __construct($data_rows=[],$x_labels=[],$y_labels=[]){
        parent::__construct($data_rows);
        $this->set_labels($x_labels,"x");
        $this->set_labels($y_labels,"y");
    }

    public function get_value($i,$j){
        if (is_int($i) && is_int($j)){
            return parent::get_value($i,$j);
        }
        return $this->get_value_from_row($this->get_row($i),$j);
    }

    public function get_value_from_row($row,$j){
        if (is_int($j)){
            return parent::get_value_from_row($row,$j);
        }
        return $this->_get_value_from_row($row,$j);
    }

    private function _get_value_from_row($row,$j){
        $idx=$this->get_label_index($j);
        return ($idx===FALSE)?FALSE:parent::get_value_from_row($row,$idx);
    }

    public function get_row($idx){
        if (!is_int($idx)){
            $idx=$this->get_label_index($idx,"y");
        }
        return ($idx===FALSE)?FALSE:parent::get_row($idx);
    }

    public function get_column($idx){
        if (!is_int($idx)){
            $idx=$this->get_label_index($idx,"x");
        }
        return ($idx===FALSE)?FALSE:parent::get_column($idx);
    }

    public function get_label_index($l,$axis="x"){
        if ($axis=="x"){
            return array_search($l,$this->x_labels);
        }
        return array_search($l,$this->y_labels);
    }

    public function set_labels($lbls,$axis="x"){
        if ($axis=="x"){
            unset($this->x_labels);
            $this->x_labels=$lbls;
        }else{
            unset($this->y_labels);
            $this->y_labels=$lbls;
        }
    }

    private function _add_label($l,$axis){
        if ($axis=="x"){
            if (!in_array($l,$this->x_labels)){
                $this->x_labels[]=$l;
            }
        }else{
            if (!in_array($l,$this->y_labels)){
                $this->y_labels[]=$l;
            }
        }
    }

    public function add_label($lbls,$axis="x"){
        if (is_array($lbls)){
            foreach ($lbls as $l){
                $this->_add_label($l,$axis);
            }
        }else{
            $this->_add_label($lbls,$axis);
        }
    }

    private function _swapLabels(){
        //swap labels
        $v=$this->column_label;
        $this->column_label=$this->row_label;
        $this->row_label=$v;

        //swap axis labels
        $vals=$this->x_labels;
        $this->x_labels=$this->y_labels;
        $this->y_labels=[];
        foreach ($vals as $v){
            $this->y_labels[]=$v;
        }
    }

    public function transpose(){
        parent::transpose();
        $this->_swapLabels();
    }

    public function fillMissingValues($empty_value=0){
        for($i=0;$i<count($this->data);$i++){
            for ($j=0;$j<count($this->x_labels);$j++){
                if (!isset($this->data[$i][$j])){
                    $this->data[$i][$j]=$empty_value;
                }
            }
        }
    }


    // ---------------- PRINTING FUNCTIONS -----------------------------//
    
    public function printHTML($max_records=10,$max_cols=10){
        $dmns=$this->get_dimensions();
        $total_x_cols=count($this->x_labels);
        if ($max_cols!=-1 && $total_x_cols>$max_cols){
            $total_x_cols=$max_cols;
        }
        echo "<h3>".$dmns[0]." x ".$dmns[1]." MATRIX</h3>";
        echo "<table border='1' cellpadding='2' cellspacing='0'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>".$this->row_label."</th>";
        echo "<th style='text-align: left;' colspan='".$total_x_cols."'>".$this->column_label."</th>";
        echo "</tr>";
        echo "<tr>";
        echo "<th>&nbsp;</th>";
        $cnt=0;
        foreach ($this->x_labels as $l){
            if ($cnt==$total_x_cols){
                break;
            }
            echo "<th>".$l."</th>";
            $cnt++;
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        $cnt=0;
        foreach ($this->y_labels as $l){
            if ($cnt==$max_records)
                break;
            
            echo "<tr>";
            echo "<th>".$l."</th>";
            $c_cnt=0;
            foreach($this->x_labels as $l){
                if ($max_cols!=-1 && $c_cnt==$max_cols){
                    break;
                }
                echo "<td style='text-align: center;'>";
                echo isset($this->data[$cnt][$c_cnt])?$this->data[$cnt][$c_cnt]:"";
                echo "</td>";
                $c_cnt++;
            }
            echo "</tr>";
            $cnt++;
        }
        echo "</tbody>";
        echo "</table>";
    }
}