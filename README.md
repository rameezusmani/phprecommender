# phprecommender
PHP based recommender system that can be used to predict values, find similar items or recommend items to user.

#### Movielens small dataset file ratings.csv is added. Example code in index.php use this file and pass this dataset to RecommenderSystem after transformation and then try to predict the rating a particular user will give to a particular movie

##### Please read index.php completely to understand how to use this recommender system in your code

### DISTANCE CALCULATION METHODS
- Cosine of angle
- Manhattan distance
- Euclidean distance

## COMPLETE SET OF EXAMPLES USING MovieLens dataset ratings.csv

#### include autoload.php and add use statements for classes

    include "src/autoload.php";

    use UglyRecommender\DistanceCalculator;
    use UglyRecommender\VectorsUnequalException;
    use UglyRecommender\NeighborsNotFoundException;
    use UglyRecommender\RecommenderSystem;

#### LOADING DATASET

Every dataset is different in format but our class **RecommenderSystem** expects data to be normalized
in a specific format before it is passed to the class for operations
we are using MovieLens dataset "ratings.csv" throughout for our examples
each row in ratings.csv is in the format
userid,movieid,rating_given
we will construct php array from ratings.csv in following format

For **USER BASED COLLABORATIVE FILTERING** each item of data matrix will be in the format:
- array("274"=>array("59315"=>3.5))
- 274=userid,59315=movieid,3.5=rating given by userid 274 to movieid 59315 

For **ITEM BASED COLLABORATIVE FILTERING** each item of data matrix will be in the format:
- array("59315"=>array("274"=>3.5))
- 59315=movieid,274=userid,3.5=rating given to movieid 59315 by userid 274

    $dataMatrix=array();  
    //load ratings csv file  
    $fp=fopen("ratings.csv","r");  
    //ignore first row because it is a header row  
    fgetcsv($fp);  
    //start constructing dataMatrix  
    //read till end of file  
    while(($row=fgetcsv($fp))!==FALSE){  
        //$row[0]=userid,$row[1]=movieid,$row[2]=rating  
        //we will first check if our dataMatrix dont have a key=userid  
        $matrixKey="".$row[0].""; //for item based collaborative filtering this will be $row[1]  
        $itemKey="".$row[1].""; //for item based collaborative filtering this will be $row[0]  
        if (!isset($dataMatrix[$matrixKey])){  
            //set row of matrix for this user id  
            $dataMatrix[$itemKey]=array();  
        }  
        //for user based collaborative filtering it will be $dataMatrix[userid][movieid]=rating  
        //for item based collaborative filtering it will be $dataMatrix[movieid][userid]=rating  
        $dataMatrix[$matrixKey][$itemKey]=floatval($row[2]);  
    }  
    fclose($fp);  
    //we have to normalize our dataset by setting unknown values (movies that are not rated by user x) to 0  
    //we are only using ratings.csv which has a lot of duplicates for each movieid  
    $movieIds=array();  
    //load ratings csv file  
    $fp=fopen("ratings.csv","r");  
    //ignore first row because it is a header row  
    fgetcsv($fp);  
    $keys=array_keys($dataMatrix);  
    while(($row=fgetcsv($fp))!==FALSE){  
        $mkey="".$row[1]."";  
        if (!in_array($mkey,$movieIds)){  
            $movieIds[]=$mkey;  
            foreach ($keys as $k){  
                if (!isset($dataMatrix[$k][$mkey])){  
                    $dataMatrix[$k][$mkey]=0;  
                }  
            }  
        }  
    }  
    unset($movieIds);  
    fclose($fp);

#### Create instance of RecommenderSystem and set parameters

    $recommender=new RecommenderSystem();  
    $recommender->setDataMatrix($dataMatrix);  
    $recommender->setUnknownValue(0); //this tells RecommenderSystem that 0 is considered as unknown value  

#### TASK#1: GET MOVIE RECOMMENDATONS FOR USER "448"

##### USE COSINE SIMILARITY

    $recommender->setDistanceMethod("cosine"); //use cosine similarity
    //get recommendations for user "448".Maximum neighbors(similar users) to use=5,Maximum results to bring=5
    $recommendations=$recommender->getRecommendations("448",5,5);
    echo "<b>RECOMMENDATIONS FOR USER '448' USING <i>COSINE SIMILARITY</i></b><br />";
    echo count($recommendations)." RECOMMENDATIONS FOUND<br />";
    foreach ($recommendations as $r){
        echo "MOVIEID: ".$r['key'].",EXPECTED RATING TO BE GIVEN BY USER: ".$r['value'];
        echo "<br />";
    }

##### USE MANHATTAN DISTANCE

    $recommender->setDistanceMethod("manhattan"); //use manhattan distance
    //get recommendations for user "448".Maximum neighbors(similar users) to use=5,Maximum results to bring=5
    $recommendations=$recommender->getRecommendations("448",5,5);
    echo "<b>RECOMMENDATIONS FOR USER '448' USING <i>MANHATTAN DISTANCE</i></b><br />";
    echo count($recommendations)." RECOMMENDATIONS FOUND<br />";
    foreach ($recommendations as $r){
        echo "MOVIEID: ".$r['key'].",EXPECTED RATING TO BE GIVEN BY USER: ".$r['value'];
        echo "<br />";
    }

#### TASK#2: PREDICT RATING TO BE GIVEN BY USER "448" to MOVIE "5"
    
    //use maximum 100 neighbors to predict
    $value=$recommender->predictValue("448","5",100);
    echo "<br />";
    echo "USER 448 IS PREDICTED TO GIVE ".$value." RATING TO MOVIE 5<br />";

#### TASK#3: GET MAXIMUM 15 USERS SIMILAR TO USER 448

    $recommender->setDistanceMethod("cosine"); //use cosine similarity
    $similar_users=$recommender->getOrderedNeighbors("448","",15,"asc");
    echo "<br />";
    echo "<b>USERS SIMILAR TO USER 448</b><br />";
    echo count($similar_users)." USERS SIMILAR TO USER 448<br />";
    foreach ($similar_users as $su){
        echo "USER ".$su['key']." HAS ".$su['distance']." SIMILARITY WITH USER 448<br />";
    }


### Future updates
- Feature to add weights to each value
- Adding Error calculation methods
- Adjust weights depending on error value
- Include hamming distance to use string based data
- Matrix factorization
