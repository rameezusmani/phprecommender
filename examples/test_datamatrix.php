<?php

include "../src/autoload.php";

use UglyRecommender\DataMatrix;
use UglyRecommender\DataHelper\CsvLoadHelper;

$dm=CsvLoadHelper::load("../ratings.csv");
$dm=CsvLoadHelper::loadDataMatrix("../ratings.csv");
echo "After matrix loaded<br />";
echo "<br /><br />";
$dm->printHTML();
/*echo "Row label: ".$dm->row_label."<br />";
echo "Column label: ".$dm->column_label."<br />";
echo "<br />";
print_r($dm->x_labels);
echo "<br /><br />";
print_r($dm->y_labels);
echo "<br /><br />";
print_r($dm->data);*/

/*$test_data=[];
$test_data[0]=22;
$test_data[2]=23;
print_r($test_data);*/


