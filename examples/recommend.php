<?php

set_time_limit(0);

include "../src/autoload.php";

use UglyRecommender\VectorsUnequalException;
use UglyRecommender\NeighborsNotFoundException;
use UglyRecommender\RecommenderSystem;
use UglyRecommender\DataHelper\CsvLoadHelper;

$dataMatrix=CsvLoadHelper::load("../ratings.csv");
array_splice($dataMatrix,500); //reduce records for quick runtime
$recommender=new RecommenderSystem();
$recommender->setDataMatrix($dataMatrix);
$recommender->setUnknownValue(0);
$recommender->fillEmptyValues("x");
//use cosine of angle....you can also use manhattan or euclidean as values
$recommender->setDistanceMethod("cosine");
//get recommendations for user 448, use 5 similar users to calculate and get maximum 5 recommendations
$recommendations=$recommender->getRecommendations("448",5,5);
echo "<b>RECOMMENDATIONS FOR USER '448'</b><br />";
echo count($recommendations)." RECOMMENDATIONS FOUND<br />";
foreach ($recommendations as $r){
    echo "MOVIEID: ".$r['key'].",EXPECTED RATING TO BE GIVEN BY USER: ".$r['value'];
    echo "<br />";
}

?>