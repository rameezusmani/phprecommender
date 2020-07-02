<?php

include "../src/autoload.php";

use UglyRecommender\RecommenderSystem;

$dataMatrix=array();
$dataMatrix["123"]=array("244"=>5.0,"243"=>6);
$dataMatrix["125"]=array("242"=>5.0,"243"=>6);
$dataMatrix["124"]=array("243"=>5.0,"242"=>6,"247"=>3);
$recommender=new RecommenderSystem();
$recommender->setDataMatrix($dataMatrix);
unset($dataMatrix);
echo "Before filling empty values<br />";
print_r($recommender->getDataMatrix());
echo "<br />";
//fill all missing values on x-axis (matrix columns) with 0
$recommender->fillEmptyValues(0,"x");
echo "Empty values filled<br />";
print_r($recommender->getDataMatrix());
echo "<br />";
?>