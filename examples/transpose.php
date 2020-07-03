<?php

include "../src/autoload.php";

use UglyRecommender\DataMatrix;
use UglyRecommender\DataHelper\CsvLoadHelper;

$dm=CsvLoadHelper::load("../ratings.csv");
$dm->tranpose();
?>