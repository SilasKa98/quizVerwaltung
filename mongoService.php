<?php

require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class MongoDBService {
    private $client;
    private $db;
  
    public function __construct() {
      $this->client = new MongoDB\Client($_ENV["dbConnection"]);
      $this->db = $this->client->quizVerwaltung;
    }
  
    public function insert($collection, $data) {
      $result = $this->db->$collection->insertOne($data);
      return $result->getInsertedId();
    }
  }


  $mongo = new MongoDBService();
  $id = $mongo->insert('questions', [
    'name' => 'test Doe',
    'email' => 'johndoe@example.com'
  ]);


?>