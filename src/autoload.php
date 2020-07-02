<?php

$uglyRecommenderClassMap=array();
$uglyRecommenderClassMap["UglyRecommender\\DistanceCalculator"]="DistanceCalculator.php";
$uglyRecommenderClassMap["UglyRecommender\\VectorsUnequalException"]="VectorsUnequalException.php";
$uglyRecommenderClassMap["UglyRecommender\\RecommenderSystem"]="RecommenderSystem.php";
$uglyRecommenderClassMap["UglyRecommender\\SubjectNotFoundException"]="SubjectNotFoundException.php";
$uglyRecommenderClassMap["UglyRecommender\\NeighborsNotFoundException"]="NeighborsNotFoundException.php";
$uglyRecommenderClassMap["UglyRecommender\\DataHelper\\CsvLoadHelper"]="datahelper/CsvLoadHelper.php";
$uglyRecommenderClassMap["UglyRecommender\\DataHelper\\MatrixHelper"]="datahelper/MatrixHelper.php";
$uglyRecommenderClassMap["UglyRecommender\\DataMatrix"]="DataMatrix.php";

spl_autoload_register(function($class_name) {
    global $uglyRecommenderClassMap;
    if (isset($uglyRecommenderClassMap[$class_name])){
        $class_filename=$uglyRecommenderClassMap[$class_name];
        $include_path=dirname(__FILE__)."/".$class_filename;
        require_once($include_path);
    } 
});

?>