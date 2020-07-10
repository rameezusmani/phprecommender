<?php

include "../src/autoload.php";

use UglyRecommender\RecommenderSystem;
use UglyRecommender\DataHelper\CsvLoadHelper;

$dm=CsvLoadHelper::load("../ratings.csv");
//fill missing values with 0 to make matrix correct dimension
$dm->fillMissingValues(0);
$recommender=new RecommenderSystem();
$recommender->setDataMatrix($dm);
$recommender->setDistanceMethod("cosine");
//predict rating to be given by "4" to movie "47"
//use maximum 100 neighbors to predict
$value=$recommender->predict("4","47",100);
echo $value;
echo "<br /><br />";
//predit rating to be given by "4" to movie "47"
//use maximum 100 neighbors and apply weights to nearest 10 neighbors
$value=$recommender->predict("4","47",100,[1.3,1.8,1.7,1.4,1.2,1.1,1.05,1.12,1.13]);
echo $value;
echo "<br /><br />";
?>