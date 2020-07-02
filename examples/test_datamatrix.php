<?php

include "../src/autoload.php";

use UglyRecommender\DataMatrix;
use UglyRecommender\DataHelper\CsvLoadHelper;

$dm=CsvLoadHelper::load("../ratings.csv");
$dm=CsvLoadHelper::loadDataMatrix("../ratings.csv");
echo "After matrix loaded<br />";
echo "<br /><br />";
echo "Dimensions:<br />";
print_r($dm->get_dimensions());
echo "<br /><br />";
echo "Original:<br />";
$dm->printHTML();
echo "<br /><br />";
$dm->fillEmptyValues();
echo "After filling empty values:<br />";
$dm->printHTML();
echo "<br /><br />";
$dm->transpose();
echo "Transpose:<br />";
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


