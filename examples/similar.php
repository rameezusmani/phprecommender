<?php

include "src/autoload.php";

use UglyRecommender\VectorsUnequalException;
use UglyRecommender\NeighborsNotFoundException;
use UglyRecommender\RecommenderSystem;
use UglyRecommender\DataHelper\CsvLoadHelper;

$dataMatrix=CsvLoadHelper::load("../ratings.csv");
$recommender=new RecommenderSystem();
$recommender->setDataMatrix($dataMatrix);
//use cosine of angle....you can also use manhattan or euclidean as values
$recommender->setDistanceMethod("cosine");
//get maximum 15 users similar to user 448 and sort by similarity in ascending
$similar_users=$recommender->getOrderedNeighbors("448","",15,"asc");
echo "<b>USERS SIMILAR TO USER 448</b><br />";
echo count($similar_users)." USERS SIMILAR TO USER 448<br />";
foreach ($similar_users as $su){
     echo "USER ".$su['key']." HAS ".$su['distance']." SIMILARITY WITH USER 448<br />";
}

?>