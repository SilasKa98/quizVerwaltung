<?php
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
    

    //TODO muss umgebaut werden, damit einfach die neue übersetzung in bestehendes Object gepusht wird...
    if(isset($fetchedQuestion[0]->question)){
        $newQuestion = [$sourceLanguage => $fetchedQuestion[0]->question->$sourceLanguage];
        $translation = new TranslationService($_POST["selLanguage"]);
        $translatedQuestion = $translation->translateObject($fetchedQuestion,$sourceLanguage);
        $newQuestion += [$targetLanguage => $translatedQuestion[0]->question->$sourceLanguage];
        $update = ['$set' =>  ['question'=> $newQuestion]];
        $mongo->updateEntry("questions",$filterQuery,$update);
    }

    //at this point the whole object is translated, now it can be taken apart and only the needed parts can be inserted into the database into the right object (object with id xxxx)
    #$mongo->insertMultiple("questions",$translatedQuestion);
    exit();
}


?>