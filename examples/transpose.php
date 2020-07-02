<?php

include "../src/autoload.php";

use UglyRecommender\RecommenderSystem;

$dataMatrix=array();
$dataMatrix["123"]=array("244"=>5.0,"243"=>6);
$dataMatrix["125"]=array("242"=>5.0,"243"=>6);
$dataMatrix["124"]=array("243"=>5.0,"242"=>6,"247"=>3);

$recommender=new RecommenderSystem();
$recommender->setDataMatrix($dataMatrix);
$recommender->fillEmptyValues();
echo "Before transpose<br />";
print_r($recommender->getDataMatrix());
echo "<br />";
$recommender->pivotDataMatrix(); //transpose the data matrix
echo "Data Matrix tranposed<br />";
print_r($recommender->getDataMatrix());
echo "<br />";
?>