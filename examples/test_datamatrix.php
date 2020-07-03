<?php

include "../src/autoload.php";

use UglyRecommender\DataMatrix;
use UglyRecommender\RecommenderSystem;
use UglyRecommender\DataHelper\CsvLoadHelper;

$dm=CsvLoadHelper::loadDataMatrix("../ratings.csv");
echo "After matrix loaded<br />";
echo "<br /><br />";
echo "Dimensions:<br />";
print_r($dm->get_dimensions());
echo "<br /><br />";
echo "Original:<br />";
$dm->printHTML(10,10);
echo "<br /><br />";
$dm->fillEmptyValues();
echo "After filling empty values:<br />";
$dm->printHTML(10,10);
echo "<br /><br />";
$rs=new RecommenderSystem();
$rs->setDataMatrix($dm);
$ns=$rs->getNearestNeighbors("6","110",2);
echo "Neighbors<br />";
print_r($ns);
echo "<br /><br />";
echo "Prediction<br />";
echo $rs->predict("1","6",100);
echo "<br /><br />";
$rms=$rs->getRecommendations("1");
echo "Recommendations<br />";
print_r($rms);
echo "<br /><br />";
$rms=$rs->getRecommendations(0);
echo "Recommendations<br />";
print_r($rms);
echo "<br /><br />";
$dm->transpose();
echo "Transpose:<br />";
$dm->printHTML(10,10);



