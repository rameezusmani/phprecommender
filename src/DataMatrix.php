<?php

namespace UglyRecommender;

class DataMatrix {
    public $row_label=""; //y axis label
    public $column_label=""; //x axis label

    public $x_labels=[]; //these are labels/identifiers of each column
    public $y_labels=[]; //these are labels/identifiers of each row
    public $data=[]; //2d array of values like a matrix

    public function __construct($data_rows=[],$x_labels=[],$y_labels=[]){
        $this->set_matrix($data_rows);
        $this->set_labels($x_labels,"x");
        $this->set_labels($y_labels,"y");
    }

    public function get_dimensions(){
        return [count($this->y_labels),count($this->x_labels)];
    }

    public function get_value($i,$j){
        if (is_int($i) && is_int($j)){
            return $this->dataMatrix[$i][$j];
        }
        return $this->_get_value_from_row($this->get_row($i),$j);
    }

    public function get_value_from_row($row,$j){
        return $this->_get_value_from_row($row,$j);
    }

    private function _get_value_from_row($row,$j){
        if (is_int($j)){
            return $row[$j];
        }
        $idx=$this->get_label_index($j);
        if ($idx===FALSE)
            return FALSE;
        return $row[$idx];
    }

    public function get_row($idx){
        if (is_int($idx)){
            return $this->data[$idx];
        }
        //its a label to y_labels
        $idx=$this->get_label_index($idx,"y");
        if ($idx===FALSE){
            return FALSE;
        }
        return $this->data[$idx];
    }

    public function get_column($idx){
        $column=[];
        if (!is_int($idx)){
            //its a label to x_labels
            $idx=$this->get_label_index($idx,"x");
            if ($idx===FALSE){
                return FALSE;
            }
        }
        foreach ($this->data as $d){
            $column[]=$d[$idx];
        }
        return $column;
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

    public function clear_matrix(){
        unset($this->data);
        $this->data=[];
    }

    public function set_matrix($mat){
        unset($this->data);
        $this->data=[];
        $this->append_matrix($mat);
    }

    public function append_matrix($mat){
        foreach ($mat as $m){
            $this->append_row($m);
        }
    }

    public function append_row($row){
        $this->data[]=$row;
    }

    private function _transposeMatrix(){
        $new_data=[];
        for($i=0;$i<count($this->data);$i++){
            $new_data[]=[];
        }
        for($i=0;$i<count($this->data);$i++){
            for($j=0;$j<count($this->data[$i]);$j++){
                $new_data[$j][]=$this->data[$i][$j];
            }
        }
        $this->data=$new_data;
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
        $this->_transposeMatrix();
        $this->_swapLabels();
    }

    public function fillEmptyValues($empty_value=0){
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