# phprecommender
PHP based recommender system that can be used to predict values, find similar items or recommend items to user.

#### Movielens small dataset file ratings.csv is added. Example codes use this file to build dataset for test cases

### Distance calculation methods
- Cosine of angle
- Manhattan distance
- Euclidean distance

## Complete set of examples using MovieLens dataset **ratings.csv**

#### include autoload.php and add use statements for classes

    include "src/autoload.php";

    use UglyRecommender\DistanceCalculator;
    use UglyRecommender\VectorsUnequalException;
    use UglyRecommender\NeighborsNotFoundException;
    use UglyRecommender\RecommenderSystem;
    use UglyRecommender\DataHelper\CsvLoadHelper;

#### Loading Dataset

Every dataset is different in format but our class **RecommenderSystem** expects data to be normalized
in a specific format before it is passed to the class for operations
we are using MovieLens dataset "ratings.csv" throughout for our examples
each row in ratings.csv is in the format
userid,movieid,rating_given

This recommender system use DataMatrix class to represet dataset. We will use CsvLoadHelper to construct DataMatrix
filled with values from "ratings.csv"

#### Construct data matrix

    $dataMatrix=CsvLoadHelper::load("ratings.csv");

#### Fill missing values so matrix is in correct dimension

    //we will fill missing values with 0 for this dataset
    $dataMatrix->setMissingValues(0);

#### Create instance of RecommenderSystem and set parameters

    $recommender=new RecommenderSystem();  
    $recommender->setDataMatrix($dataMatrix);  
    $recommender->setUnknownValue(0); //this tells RecommenderSystem that missing values are filled with 0

#### GET MOVIE RECOMMENDATONS FOR USER "448"

##### Use Cosine similarity

    $recommender->setDistanceMethod("cosine");
    //get recommendations for user "448".Maximum neighbors(similar users) to use=100,Maximum results to bring=5
    $recommendations=$recommender->getRecommendations("448",100,5);

##### Use Manhattan distance

    $recommender->setDistanceMethod("manhattan");
    $recommendations=$recommender->getRecommendations("448",100,5)

#### PREDICT RATING TO BE GIVEN BY USER "448" to MOVIE "5"
    
    //use maximum 100 neighbors to predict
    $value=$recommender->predict("448","5",100);

#### PREDICT RATING TO BE GIVEN BY USER "448" to MOVIE "5" using weighted neighbors

    //use maximum 100 neighbors to predict and use weights for nearest 10 neighbors
    $value=$recommender->predict("448","5",100,[1.3,1.8,1.7,1.4,1.2,1.1,1.05,1.12,1.13]);


#### GET MAXIMUM 15 USERS SIMILAR TO USER 448

    $recommender->setDistanceMethod("cosine"); //use cosine similarity
    $similar_users=$recommender->getNearestNeighbors("448","",15);

#### GET MAXIMUM 15 USERS SIMILAR TO USER 448 WHO HAS WATCHED MOVIE "5"

    $recommender->setDistanceMethod("cosine");
    $similar_users=$recommender->getNearestNeighbors("448","5",15)


### Future updates
- Feature to add weights to each value
- Adding Error calculation methods
- Adjust weights depending on error value
- Include hamming distance to use string based data
- Matrix factorization
