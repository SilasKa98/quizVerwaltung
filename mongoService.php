<?php

require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//TODO session management irgendwie machen damit es keine überschneidungen etc. gibt !!!!

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
            echo("Error writing in Database. <br>" . $e);
        }
    }

    /**
     * Inserts a single php object into a mongodb collection.
     *
     * @param string     $collection     The collection name of the mongodb to which the data should be inserted
     * @param object     $data           The data to be inserted as a php object
     *
     * @return //TODO which type     $result     The _id of the inserted Object whithin mongodb
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
     * @param array[string] $options        An array of assignments defining options
     * 
     * @return object       $result         Returns the first match as a php object
     */
    public function findSingle($collection, $filter, $options) {
        $result = $this->db->$collection->findOne($filter, $options);

        return $result;
    }

    /**
     * Function to find all matching entries in the database and returning them
     * 
     * @param string        $collection     The collection name of the mongodb
     * @param array[string] $filter         An array of assignments [id => 123, "name" => "xyz] defining filters
     * @param array[string] $options        An array of assignments defining options
     * 
     * @return object       $documents      Returns an array of php objects matching the criteria
     */
    public function read($collection, $filter, $options) {
        $results = $this->db->$collection->find($filter, $options);

        $documents = [];
        foreach ($results as $document) {
            $documents[] = $document;
        }
        
        return $documents;
    }

    //TODO delete by
    
    //TODO update

    //TODO vllt auch start und end session als extra function ?!?!? --> ansonsten in den einzelnen Funktionen machen 
  }
?>