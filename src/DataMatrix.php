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

    public function clear_matrix($mat){
        unset($this->data);
    }

    public function set_matrix($mat){
        unset($this->data);
        $this->data=[];
        $this->append_matrix($mat);
    }

    public function append_matrix($mat){
        foreach ($mat as $m){
            $this->add_row($m);
        }
    }

    public function add_row($row){
        $this->data[]=$row;
    }

    public function transpose(){
        $new_data=[];
        for($i=0;$i<count($this->data);$i++){
            $new_data[]=[];
        }
        for($i=0;$i<count($this->data);$i++){
            for($j=0;$j<count($this->data[$i]);$j++){
                $new_data[$j][]=$this->data[$i][$j];
            }
        }
        unset($this->data);
        $this->data=$new_data;
        unset($new_data);
        //swap labels
        $v=$this->column_label;
        $this->column_label=$this->row_label;
        $this->row_label=$v;

        //swap axis labels
        $vals=$this->x_labels;
        $this->x_labels=$this->y_labels;
        unset($this->y_labels);
        $this->y_labels=[];
        foreach ($vals as $v){
            $this->y_labels[]=$v;
        }
        unset($vals);
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
    
    public function printHTML($max_records=10){
        $dmns=$this->get_dimensions();
        echo "<h3>".$dmns[0]." x ".$dmns[1]." MATRIX</h3>";
        echo "<table border='1' cellpadding='2' cellspacing='0'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>".$this->row_label."</th>";
        echo "<th style='text-align: left;' colspan='".count($this->x_labels)."'>".$this->column_label."</th>";
        echo "</tr>";
        echo "<tr>";
        echo "<th>&nbsp;</th>";
        foreach ($this->x_labels as $l){
            echo "<th>".$l."</th>";
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