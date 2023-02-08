<?php
include_once "mongoService.php";

class Catalog{
    public $author;
    public $questions = [];
    public $creationDate;
    public $modificationDate;
    public $status;


    function __construct($author, $questions, $creationDate, $modificationDate, $status){
        $this->author = $author;
        $this->questions = $questions;
        $this->creationDate = $creationDate;
        $this->modificationDate = $modificationDate;
        $this->status = $status;
    }
}

class CartService{

    private $mongo;
    private $userId;

    function __construct() {
        extract($_SESSION["userData"]);
        $this->mongo = new MongoDBService();
        $this->userId = $userId;

    }

    function addItem($questionId){
        $filterQuery = (['userId' => $this->userId]);
        
        //get current questionCart
        $result = $this->mongo->findSingle("accounts", $filterQuery);
        //fetch as array
        $cart = (array)$result["questionCart"];
        
        //check if id is in the cart already
        if (in_array($questionId, $cart)){
            echo "Frage ist schon vorhanden";
            //TODO Toast hier um mitzuteilen das die Frage schon im Warenkorb ist!!
        }else{
            $update = ['$push' => ['questionCart' => $questionId]];
            $this->mongo->updateEntry("accounts", $filterQuery, $update);
        }
    }

    function removeItem($questionId){
        $filterQuery = (['userId' => $this->userId]);
        $update = ['$pull' => ['questionCart' => $questionId]];
        $this->mongo->updateEntry("accounts", $filterQuery, $update);
    }

    function createCatalog(){
        $filterQuery = (['userId' => $this->userId]);
        //get all questions currently in the cart
        $result = $this->mongo->findSingle("accounts", $filterQuery);
        $cart = (array)$result["questionCart"];

        //check if cart is not emtpy 
        if (!empty($cart)){
            //clean the cart
            $update = ['$set' => ['questionCart' => []]];
            $this->mongo->updateEntry("accounts", $filterQuery, $update);

            $catalog = new Catalog($result["username"], $cart, "TODO", "TODO", "public"); //TODO rest des Katalogs noch machen !!!

            //create a new entry in catalogs with the userId
            $this->mongo->insertSingle("catalog", $catalog);
        }else{
            //TODO Toast wenn empty !!!!!
            echo "cart ist leer !!!!";
        }
    }

    function printCart(){
        $filterQuery = (['userId' => $this->userId]);

        $result = $this->mongo->findSingle("accounts", $filterQuery);
        $cart = (array)$result["questionCart"];

        if (empty($cart)){
            print"
                Du hast aktuell keine Fragen in deinem Korb. <br>
                Füge einfach eine Frage hinzu indem du neben einer Frage
                das Dropdown Menü öffnest und den Warenkorb anklicks.
                ";
        }else{
            foreach ($cart as $questionId) {
                $usedLanguage = "de"; //TODO hier sollte das ganze dann in der sprache gemaht werden die der user auch ausgewählt hatte als er die frage hinzugefügt hat
                
                //get the questions from the ids
                $filterQuery = ["id" => $questionId];
                $questionObject = $this->mongo->findSingle("questions", $filterQuery);
                $question = $questionObject["question"];
                print   "  <div class='card' style='margin: .5rem; --bs-card-spacer-y: .5rem;'>
                            <div class='card-body'>
                                $question[$usedLanguage]
                            </div>
                        </div>
                        ";
            }
        }
    }

}
?>
