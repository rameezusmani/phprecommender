<?php

include "../src/autoload.php";

use UglyRecommender\VectorsUnequalException;
use UglyRecommender\NeighborsNotFoundException;
use UglyRecommender\RecommenderSystem;
use UglyRecommender\DataHelper\CsvLoadHelper;

$dataMatrix=CsvLoadHelper::load("../ratings.csv");
$recommender=new RecommenderSystem();
$recommender->setDataMatrix($dataMatrix);
//use cosine of angle....you can also use manhattan or euclidean as values
$recommender->setDistanceMethod("cosine");
//predict rating to be given by "448" to movie "5"
//use maximum 100 neighbors to predict
$value=$recommender->predictValue("448","5",100);
echo "<br />";
echo "USER 448 IS EXPECTED TO GIVE ".$value." RATING TO MOVIE 5<br />";

?>