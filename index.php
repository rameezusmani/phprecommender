<?php

include "src/autoload.php";

use UglyRecommender\DistanceCalculator;
use UglyRecommender\VectorsUnequalException;
use UglyRecommender\NeighborsNotFoundException;
use UglyRecommender\RecommenderSystem;

//using movielens data set ratings.csv
//we will predict what rating a user will give to a movie
$userId="1"; //user id to use for prediction
$movieId="110"; //movie id to use for prediction
//our data matrix
//we will construct php array from ratings.csv in following format
//array("274"=>array("59315"=>3.5))
//274=userid,59315=movieid,3.5=rating given to movieid 59315 by userid 274
$dataMatrix=array();
$movieIds=array(); //unique ids of movies that are rated
//load ratings csv file
$fp=fopen("ratings.csv","r");
//ignore first row because it is a header row
fgetcsv($fp);
while(($row=fgetcsv($fp))!==FALSE){
    $mkey="".$row[1]."";
    //if (!isset($movieIds[$mkey]))
        //$movieIds[$mkey]="1";
    if (!in_array($mkey,$movieIds)){
        $movieIds[]=$mkey;
    }
}
fclose($fp);

//load ratings csv file
$fp=fopen("ratings.csv","r");
//ignore first row because it is a header row
fgetcsv($fp);
//start constructing dataMatrix
//i am assuming that ratings.csv is sorted by userid
//read till end of file
while(($row=fgetcsv($fp))!==FALSE){
    //$row[0]=userid,$row[1]=movieid,$row[2]=rating
    //we will first check if our dataMatrix dont have a key=userid
    $matrixKey="".$row[0]."";
    $objectKey="".$row[1]."";
    if (!isset($dataMatrix[$matrixKey])){
        //set row of matrix for this user id
        $dataMatrix[$matrixKey]=array();
    }
    //$dataMatrix[userid][movieid]=rating
    $dataMatrix[$matrixKey][$objectKey]=floatval($row[2]);
}
fclose($fp);
//now we have $dataMatrix array setup in the format we wanted
//now we will remove unwanted users from $dataMatrix. In this case any user that has not rated movieid=59315
//will be removed
$keys=array_keys($dataMatrix);
foreach ($keys as $k){
    if (!isset($dataMatrix[$k][$movieId])){
        unset($dataMatrix[$k]);
        //echo $k." has not rated ".$movieId."<br />";
    }
}
//set 0 for all movies that are not rated by this user
$keys=array_keys($dataMatrix);
//$mkeys=array_keys($movieIds);
foreach ($keys as $k){
    //foreach ($mkeys as $mid){
    foreach($movieIds as $mid){
        if (!isset($dataMatrix[$k][$mid])){
            $dataMatrix[$k][$mid]=0;
        }
    }
}

//now we have users only who has rated movieId=59315

try{
    $recommender=new RecommenderSystem();
    $recommender->setDataMatrix($dataMatrix);
    $recommender->setUnknownValue(0); //this tells recommender system that 0 is considered as unknown value
    //set cosine of angle as distance calculation method
    //$recommender->setDistanceMethod("cosine");
    //set euclidean as distance calculation method
    //$recommender->setDistanceMethod("euclidean");
    //set manhattan as distance calculation method
    $recommender->setDistanceMethod("manhattan");
    //how to get neighbors sorted by distance
    //$neighbors=$recommender->getOrderedNeighbors("3",5,"asc"); //asc=ascending order of distance
    //$neighbors=$recommender->getOrderedNeighbors("3",5,"desc"); //desc=descending order of distance
    //echo "Value Prediction<br />";
    //$value=$recommender->predictValue($userId,$movieId,100);
    //echo $userId." is predicted to rate ".$movieId.": ".$value;
    //echo "<br />";
    echo "Recommendations<br />";
    $rms=$recommender->getRecommendations($userId);
    print_r($rms);
}catch(VectorsUnequalException $ex){
    echo "VectorsUnequalException: ".$ex->getMessage();
}catch(NeighborsNotFoundException $ex){
    echo "No neighbors found";
}catch(Exception $ex){
    echo "Exception: ".$ex->getMessage();
}

?>