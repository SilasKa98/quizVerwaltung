<?php
    class MongoDBSerive2 {
        const DATABASE_NAME = "test";
        const COLLECTION_NAME = "test";

        public $mongoClient;

        public function __construct($mongoUrl, $mongoUsername, $mongoPassword) {
            try {
                $this->mongoClient = new MongoDB\Driver\Manager
                (
                    "mongodb://" . $mongoUrl, 
                    ['authMechanism' => "SCRAM-SHA-1", 
                    "username" => $mongoUsername, "password" => $mongoPassword]
                ); 
                echo "Connection to database successfully !!! <br>";

            } catch (Throwable $e){
                echo "Connection could not be established. <br>";
            }
        }

        public function testInsert(string $name, int $age) {
            $bulk = new MongoDB\Driver\BulkWrite();
            $document1 = ["name" => $name, "age" => $age];
            $bulk -> insert($document1);
            
            try {
                $this->mongoClient->executeBulkWrite(self::DATABASE_NAME . "." . self::COLLECTION_NAME ,$bulk);
                echo("Write successful !!! <br>");
            } catch (Throwable $e) {
             echo("Error writing in Database. <br>" . $e);
            }
        }

        public function testRead($name){
            $filter = ["name" => $name];
            $options = [];
            $query = new MongoDB\Driver\Query($filter, $options);

            $results = $this->mongoClient->executeQuery("test.test", $query);
            foreach ($results as $r) {
                print("<br>");
                print_r($r);
            }
        }
    }
    
    $testmongo = new MongoDBSerive("192.168.2.97:27017", "root", "masterprojekt");
    #$testmongo->testInsert("moritz", 25);
    $testmongo->testRead("moritz");

### Stunden ca. 5 !!!!!

?>