
<?php
require_once __DIR__ . "/vendor/autoload.php";
//define("_DEFAULT_DATABASE_", "macklessons");
//date_default_timezone_set("US/New York");
// $collection = (new MongoDB\Client("mongodb://192.168.1.27:32775"))->macklessons->episodes;
// $insertOneResult = $collection->insertOne(['id'=> 1,'title' => 'Alice']);
// printf("Inserted %d document(s)\n", $insertOneResult->getInsertedCount());
// var_dump($insertOneResult->getInsertedId());

class EpisodeModel{
    public $collection;
    function __construct() {
        $this->collection = (new MongoDB\Client("mongodb://192.168.1.27:32775"))->macklessons->episodes;
    }
    function addEpisode($episodeObj){
        $this->collection->insertOne($episodeObj);
    }
}
?>