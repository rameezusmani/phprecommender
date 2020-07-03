<?php

set_time_limit(0);

include "../src/autoload.php";

use UglyRecommender\RecommenderSystem;
use UglyRecommender\DataHelper\CsvLoadHelper;

$dm=CsvLoadHelper::load("../ratings.csv");
//fill missing values with 0 to make matrix correct dimension
$dm->fillMissingValues(0);
$recommender=new RecommenderSystem();
$recommender->setDataMatrix($dm);
$recommender->setDistanceMethod("cosine");
//get recommendations for user '1'
$recommendations=$recommender->getRecommendations("1");
print_r($recommendations);

?>