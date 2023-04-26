<?php
$basePath = dirname(__DIR__, 1);
require $basePath. '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable($basePath);
$dotenv->load();

class MongoDBService {
    private $client;    # Connection to The MongoDB instance
    private $db;        # The actual Database of the instance
  
    public function __construct() {
        try {
            # Connecting to the MongoDB using the dbConnection defined in the .env file
            $this->client = new MongoDB\Client($_ENV["dbConnection"]);
            # From the Client one Database is selected and only the DB is used for Operations
            $this->db = $this->client->quizVerwaltung;
            #echo("Connection successfull !!!");

        } catch (\Throwable $e) {
            echo("Error connecting to the Database. <br>" . $e);
        }
    }

    /**
     * Inserts a single php object into a mongodb collection.
     *
     * @param string     $collection     The collection name of the mongodb to which the data should be inserted
     * @param object     $data           The data to be inserted as a php object
     *
     * @return           $result         The _id of the inserted Object whithin mongodb
     */
    public function insertSingle($collection, $data) {
      try {
        $result = $this->db->$collection->insertOne($data);
        #echo("Insertion of Single Data successfull !!!");
      } catch (\Throwable $e) {
        echo("Insertion Failed !!! . $e");
        return null;
      }
        
      return $result->getInsertedId();
    }

    /**
     * Inserts multiple php objects into a mongodb collection.
     * 
     * @param string        $collection     The collection name of the mongodb to which the data should be inserted
     * @param array[object] $data           An array of php objects to be inserted into the db
     * 
     * @return array        $array           An array of vlaues which represents the _ids of the php objects in mongodb
     */
    public function insertMultiple($collection, $data) {
        $array = [];
        try {
            $result = $this->db->$collection->insertMany($data);
            #echo("Insertion of Multiple Data successfull !!!");
            # convert the curser object from mongodb into an array of ids of the objects
            foreach ($result as $value) {
                $array[] = $value;
            }
        } catch (\Throwable $e) {
            echo("Insertion Failed !!!" . $e);
        }

        return $array;
    }

    /**
     * Function for finding the first entry in the db corresponding to the defined arguments
     * 
     * @param string        $collection     The collection name of the mongodb
     * @param array[string] $filter         An array of assignments [id => 123, "name" => "xyz] defining filters
     * @param array[string] $options        An array of assignments defining options // default value is an empty array
     * 
     * @return object       $result         Returns the first match as a php object
     */
    public function findSingle($collection, $filter, $options = []) {
        $result = $this->db->$collection->findOne($filter, $options);

        return $result;
    }

    /**
     * Function to find all matching entries in the database and returning them
     * 
     * @param string        $collection     The collection name of the mongodb
     * @param array[string] $filter         An array of assignments [id => 123, "name" => "xyz] defining filters
     * @param array[string] $options        An array of assignments defining options // default value is an empty array
     * 
     * @return object       $documents      Returns an array of php objects matching the criteria
     */
    public function read($collection, $filter, $options = []) {
        $results = $this->db->$collection->find($filter, $options);

        $documents = [];
        foreach ($results as $document) {
            $documents[] = $document;
        }
        
        return $documents;
    }

    /**
     * Funtion to delete one entry in the Database by its id
     * 
     * @param string        $collection     The collection name of the mongodb
     * @param string        $id             The oid of the entry in the mongodb
     * @param array[string] $options        An array of assignments defining options // default value is an empty array
     */
    public function deleteById($collection, $id, $options = []){
        $oid = $id;    
        $filter = ["_id" => new MongoDB\BSON\ObjectId($oid)];

        try {
            $deleteResult = $this->db->$collection->deleteOne($filter, $options);
            # echo("Deleting DB Entry Succsessful !!!");
            if ($deleteResult->getDeletedCount() == 1) {
                echo("Entry deleted !!");
            }else {
                echo("Entry could not be deleted");
            }
        } catch (\Throwable $e) {
            # echo("Deletion cloud not be performed !!!" . $e);
        }
    }

        /**
     * Funtion to delete one entry in the Database by its id
     * 
     * @param string        $collection     The collection name of the mongodb
     * @param string        $id             The id of the entry in the mongodb
     * @param array[string] $options        An array of assignments defining options // default value is an empty array
     */
    public function deleteByUid($collection, $filter, $options = []){
        try {
            $deleteResult = $this->db->$collection->deleteOne($filter, $options);
        } catch (\Throwable $e) {
             echo("Db Error - Deletion cloud not be performed !!!" . $e);
        }
    }

    /**
     * Function to completly remove every entry in the Collection of the MongoDB
     * 
     * @param string        $collection     The collection name of the mongodb
     */
    private function cleanCollection($collection) {
        try {
            $this->db->$collection->drop();
            $this->db->createCollection($collection);
            echo("Succsessfully cleaned the Collection !!!");
        } catch (\Throwable $e) {
            echo("Collection could not be deleted(cleaned) !!!" . $e);
        }
    }

    public function updateEntry($collection,$filter,$update){
        $result = $this->db->$collection->updateMany($filter, $update);
        return $result;
    } 
  }
?>