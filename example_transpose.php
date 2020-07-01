<?php

include "src/autoload.php";

use UglyRecommender\VectorsUnequalException;
use UglyRecommender\NeighborsNotFoundException;
use UglyRecommender\RecommenderSystem;
use UglyRecommender\DataHelper\CsvLoadHelper;

$dataMatrix=CsvLoadHelper::load("ratings.csv");
$recommender=new RecommenderSystem();
$recommender->setDataMatrix($dataMatrix);
$recommender->pivotDataMatrix(); //transpose the data matrix
echo "Data Matrix tranposed";

?>