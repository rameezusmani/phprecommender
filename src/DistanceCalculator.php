<?php

namespace UglyRecommender;

class DistanceCalculator {

    private static function checkVectorsEqualityOrThrow($vectorA,$vectorB){
        if (count($vectorA)!=count($vectorB)){
            throw new VectorsUnequalException("Vectors are unequal");
        }
    }

    /**
     * Calculates magnitude of a vector (php array)
     * $vectorA (array): must be an array of numbers. No key/value paiers only simple numeric array like [0,1,2,3]
     * returns magnitude of $vectorA
     */
    private static function calculateMagnitude($vectorA){
        $sum=0;
        foreach ($vectorA as $v){
            $sum+=pow(floatval($v),2);
        }
        return sqrt($sum);
    }

    /**
     * Calculates dot product between 2 vectors (php arrays)
     * $vectorA (array): must be an array of numbers. No key/value pairs only simple array like [0,1,2,3]
     * $vectorB (array): must be an array of numbers. No key/value pairs only simple array like [0,1,2,3]
     * returns $vectorA.$vectorB
     */
    private static function calculateDotProduct($vectorA,$vectorB){
        DistanceCalculator::checkVectorsEqualityOrThrow($vectorA,$vectorB);
        $dotProduct=0;
        for ($a=0;$a<count($vectorA);$a++){
            $dotProduct+=floatval(floatval($vectorA[$a])*floatval($vectorB[$a]));
        }
        return $dotProduct;
    }

    /**
     * Calculates cosine of angle between 2 vectors (php arrays)
     * $vectorA (array): must be an array of numbers. No key/value pairs only simple array like [0,1,2,3]
     * $vectorB (array): must be an array of numbers. No key/value pairs only simple array like [0,1,2,3]
     * returns cosine of angle between $vectorA and $vectorB
     */
    public static function cosineOfAngle($vectorA,$vectorB){
        $dotProduct=DistanceCalculator::calculateDotProduct($vectorA,$vectorB);
        $magnitudeProduct=DistanceCalculator::calculateMagnitude($vectorA)*DistanceCalculator::calculateMagnitude($vectorB);
        return cos($dotProduct/$magnitudeProduct);
    }

    /**
     * Calculates manhattan between 2 vectors (php arrays)
     * $vectorA (array): must be an array of numbers. No key/value pairs only simple array like [0,1,2,3]
     * $vectorB (array): must be an array of numbers. No key/value pairs only simple array like [0,1,2,3]
     * returns manhattan distance between $vectorA and $vectorB
     */
    public static function manhattanDistance($vectorA,$vectorB){
        DistanceCalculator::checkVectorsEqualityOrThrow($vectorA,$vectorB);
        $distance=0;
        for($a=0;$a<count($vectorA);$a++){
            $distance+=abs(floatval($vectorA[$a])-floatval($vectorB[$a]));
        }
        return $distance;
    }

    /**
     * Calculates Euclidean distance between 2 vectors (php arrays)
     * $vectorA (array): must be an array of numbers. No key/value pairs only simple array like [0,1,2,3]
     * $vectorB (array): must be an array of numbers. No key/value pairs only simple array like [0,1,2,3]
     * returns Euclidean distance between $vectorA and $vectorB
     */
    public static function euclideanDistance($vectorA,$vectorB){
        DistanceCalculator::checkVectorsEqualityOrThrow($vectorA,$vectorB);
        $distance=0;
        for($a=0;$a<count($vectorA);$a++){
            $distance+=floatval($vectorA[$a])-floatval($vectorB[$a]);
        }
        $distance=sqrt(abs($distance));
        return $distance;
    }
}

?>