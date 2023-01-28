<?php
/**
 * All sorts of calls to the different services get handeled in this file
 * 
 * 
 */

include_once "mongoService.php";
include_once "translationService.php";
include_once "questionService.php";


$question = new QuestionService();
$mongo = new MongoDBService();

if(isset($_POST["method"]) && $_POST["method"] == "changeLanguage"){
    $filterQuery = (['id' => $_POST["id"]]);
    $options = [];
    $sourceLanguage = $_POST["sourceLanguage"];
    $targetLanguage = $_POST["selLanguage"];
    $objToTranslate = $mongo->read("questions",$filterQuery,$options);
    foreach ($objToTranslate as $doc) {
        $fetchedQuestion = $question -> parseReadInQuestion($doc);
    }
    

    if(isset($fetchedQuestion[0]->question)){
        $newQuestion = [$sourceLanguage => $fetchedQuestion[0]->question->$sourceLanguage];
        $translation = new TranslationService($_POST["selLanguage"]);
        $translatedQuestion = $translation->translateObject($fetchedQuestion,$sourceLanguage);
        $newLangQuestion = $translatedQuestion[0]->question->$sourceLanguage;

        //first get all question versions that exist
        $searchDbEntry = $mongo->findSingle("questions",$filterQuery,[]);
        //cast it to array so the new field can get added
        $searchDbEntry = (array)$searchDbEntry["question"];
        //and then add the new language version
        $searchDbEntry[$targetLanguage] = $newLangQuestion;
        $update = ['$set' =>  ['question'=> $searchDbEntry]];
        $mongo->updateEntry("questions",$filterQuery,$update);
    }

    //at this point the whole object is translated, now it can be taken apart and only the needed parts can be inserted into the database into the right object (object with id xxxx)
    //same as done obove with the question
    exit();
}

if(isset($_POST["method"]) && $_POST["method"] == "changeKarma"){
    include_once "karmaService.php";
    $karma = new KarmaService();
    if($_POST["job"] == "increaseKarma"){
        $newKarma = $karma->increaseKarma($_POST["id"]);
        echo $newKarma;
    }else{
        $newKarma = $karma->decreaseKarma($_POST["id"]);
        echo $newKarma;
    }

    $filterQuery = (['id' => $_POST["id"]]);
    $update = ['$set' =>  ['karma'=> $newKarma]];
    $mongo->updateEntry("questions",$filterQuery,$update);
}


if(isset($_POST["method"]) && $_POST["method"] == "registerAccount"){
    include_once "accountService.php";
    $account = new AccountService();
    $account->register($_POST["username"],$_POST["mail"],$_POST["pwd"],$_POST["pwd_repeat"],$_POST["language"]);
}

if(isset($_POST["method"]) && $_POST["method"] == "loginAccount"){
    include_once "accountService.php";
    $account = new AccountService();
    $account->login($_POST["mailuid"],$_POST["pwd"]);
}

if(isset($_POST["logout"])){
    include_once "accountService.php";
    $account = new AccountService();
    $account->logout();
}

if(isset($_POST["language"])){
    include_once "accountService.php";
    $account = new AccountService();
    session_start();
    $account->changeLanguage($_POST["language"], $_SESSION["userData"]["userId"]);
}

?>