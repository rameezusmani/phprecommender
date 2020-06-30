<?php

$uglyRecommenderClassMap=array();
$uglyRecommenderClassMap["UglyRecommender\\DistanceCalculator"]="src/DistanceCalculator.php";
$uglyRecommenderClassMap["UglyRecommender\\VectorsUnequalException"]="src/VectorsUnequalException.php";
$uglyRecommenderClassMap["UglyRecommender\\RecommenderSystem"]="src/RecommenderSystem.php";
$uglyRecommenderClassMap["UglyRecommender\\SubjectNotFoundException"]="src/SubjectNotFoundException.php";
$uglyRecommenderClassMap["UglyRecommender\\NeighborsNotFoundException"]="src/NeighborsNotFoundException.php";

spl_autoload_register(function($class_name) {
    global $uglyRecommenderClassMap;
    if (isset($uglyRecommenderClassMap[$class_name])){
        require_once($uglyRecommenderClassMap[$class_name]);
    } 
});

?>