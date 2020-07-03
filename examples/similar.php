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
//get 5(default) nearest similar users for user '1'
$similar=$recommender->getNearestNeighbors("1");
print_r($similar);

?>