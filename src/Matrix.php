<?php

namespace UglyRecommender;

class Matrix {
    public $data=[]; //2d array of values like a matrix
    public function __construct($data_rows=[]){
        $this->set_matrix($data_rows);
    }

    public function get_dimensions(){
        $rows=count($this->data);
        $cols=0;
        if ($rows>0){
            $cols=count($this->data[0]);
        }
        //rows x cols dimensions
        return array($rows,$cols);
    }

    public function get_row($i){
        return $this->data[$i];
    }

    public function get_column($idx){
        $column=[];
        foreach ($this->data as $d){
            $column[]=$d[$idx];
        }
        return $column;
    }

    public function get_value($i,$j){
        return $this->get_value_from_row($this->data[$i],$j);
    }

    public function get_value_from_row($row,$j){
        return $row[$j];
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
        $this->data=$new_data;
    }
}

?>