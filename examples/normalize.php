<?php

include "../src/autoload.php";

use UglyRecommender\DataMatrix;
use UglyRecommender\DataHelper\CsvLoadHelper;

$dm=CsvLoadHelper::load("../ratings.csv");
//fill missing values with 0 to make matrix correct dimension
$dm->fillMissingValues(0);
//L1 normalizaton
$dm->normalize('l1');
//L2 normalizaton
$dm->normalize('l2');
//max normalizaton
$dm->normalize('max');
?>