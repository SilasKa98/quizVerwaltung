<?php
include_once "mongoService.php";

class Catalog{
    public $author;
    public $id;
    public $name;
    public $questions = [];
    public $creationDate;
    public $modificationDate;
    public $status;


    function __construct($author, $id, $name, $questions, $creationDate, $modificationDate, $status){
        $this->author = $author;
        $this->id = $id;
        $this->name = $name;
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
            // Question already in cart
            return "ItemExists";
            exit();
        }else{
            $update = ['$push' => ['questionCart' => $questionId]];
            $this->mongo->updateEntry("accounts", $filterQuery, $update);
        }
        return "Success";
    }

    function removeItem($questionId){
        $filterQuery = (['userId' => $this->userId]);
        $update = ['$pull' => ['questionCart' => $questionId]];
        $this->mongo->updateEntry("accounts", $filterQuery, $update);

        return "successfullRemove";
    }

    function createCatalog($name, $status){
        $filterQuery = (['userId' => $this->userId]);
        //get all questions currently in the cart
        $result = $this->mongo->findSingle("accounts", $filterQuery);
        $cart = (array)$result["questionCart"];

        //check if cart is not emtpy 
        if (!empty($cart)){
            //clean the cart
            $update = ['$set' => ['questionCart' => []]];
            $this->mongo->updateEntry("accounts", $filterQuery, $update);

            $dateNow = date("Y-m-d");
            $id = uniqid();
            $catalog = new Catalog($result["username"], $id, $name, $cart, $dateNow, $dateNow, $status);

            //create a new entry in catalogs with the userId
            $this->mongo->insertSingle("catalog", $catalog);
            return "catalogCreated";
        }else{
            return "cartEmpty";
        }
    }

    function emptyQuestionCart(){
        $filterQuery = (['userId' => $this->userId]);

        $update = ['$set' => ['questionCart' => []]];
        $this->mongo->updateEntry("accounts", $filterQuery, $update);

        return "cartGotDeleted";
    }

    function printCart(){
        $filterQuery = (['userId' => $this->userId]);

        $result = $this->mongo->findSingle("accounts", $filterQuery);
        $cart = (array)$result["questionCart"];

        if (empty($cart)){
            print"
                <p id='cartInfoText'>Du hast aktuell keine Fragen in deinem Korb.
                Füge einfach eine Frage hinzu indem du neben einer Frage
                das Dropdown Menü öffnest und den Warenkorb anklicks.</p>
                ";
        }else{
            foreach ($cart as $questionId) {
                $searchUserFilter = (['userId'=>$this->userId]);
                $searchUser = $this->mongo->findSingle("accounts",$searchUserFilter);
                $questionLanguageRelation = (array)$searchUser["questionLangUserRelation"];

                $lang = array_search($questionId,$questionLanguageRelation);

                //get the questions from the ids
                $filterQuery = ["id" => $questionId];
                $questionObject = $this->mongo->findSingle("questions", $filterQuery);
                $question = $questionObject["question"];

                if(!$lang){
                    //get the first key of the question so it can be used to set it as the default language
                    $lang = array_key_first((array)$question);
                }

                $answerType;
                $answer;
                
                if ($questionObject["questionType"] == "Options" || $questionObject["questionType"] == "MultiOptions"){
                    $answerType = "Options";
                    $answer = $this->__createOptionsBubbles($questionObject["options"]->$lang, $questionObject["answer"]);
                }else{
                    $answerType = "Answer";
                    $answer = $questionObject["answer"];
                }
                
                $author = $questionObject["author"];
                $tags = (array)$questionObject["tags"];
                
                $tagBadges = "";

                if (count($tags) != 0){
                    foreach ($tags as $tag) {
                        if ($tag != ""){
                            $tagBadges = $tagBadges."<span class='badge rounded-pill text-bg-secondary' style='margin-right: 2px;'> $tag </span>";
                        }
                    }  
                }
                
                print   "  <div class='card' id=$questionId style='margin: .5rem; --bs-card-spacer-y: .5rem;'>
                            <div class='card-body'>
                             <div class='row'>
                                <div class='col question' name='$questionId'>    
                                    <a class='collapsable_questionText' data-bs-toggle='collapse' href='#collabsable_$questionId'>
                                        $question[$lang]
                                    </a>
                                </div>
                                <div class='col-1 d-flex flex-column cancel' style='justify-content: center;'>
                                    <button type='button' class='btn-close' aria-label='Close' name='$questionId'
                                        onclick='removeCartItem(this)'
                                        style='width: .4rem; height: .4rem; float: right;'>
                                    </button>
                                </div>
                             </div>
                            </div>

                            <div class='collapse' id='collabsable_$questionId'>
                            <div class=card questionCartCard' style='margin: .5rem; --bs-card-spacer-y: .5rem;'>
                                <div class=card-body questionCartCard>
                                    <p 'question-text'> $answerType : $answer</p>
                                    <p 'question-text'> Tags: $tagBadges</p>
                                    <p 'question-text'> Author: 
                                        <a href='/quizVerwaltung/frontend/userProfile.php?profileUsername=$author&section=questions'>
                                            <span class='badge rounded-pill bg-primary authorPill' style='margin-right: 2px;'> $author</span>
                                        </a>
                                    </p>
                                </div>
                            </div>
                            </div>
                        </div>
                        ";
            }
        }
    }

    //TODO hier vllt noch etwas mehr formatierung machen damit das nicht so überlappt ?????!?!??
    function __createOptionsBubbles($options, $answers){
        $answerPills = "";
        $answers = explode(",",$answers);
        $isArray = gettype($answers);

        foreach ($options as $index => $option) {
            if (in_array($index, $answers)){
                $answerPills = $answerPills."<span class='badge rounded-pill text-bg-success' style='margin-right: 2px;'> $option </span>";
            }else{
                $answerPills = $answerPills."<span class='badge rounded-pill text-bg-secondary' style='margin-right: 2px;'> $option </span>";
            }
        }
        return $answerPills;
    }
}
?>
