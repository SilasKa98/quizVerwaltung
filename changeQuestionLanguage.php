<?php
include_once "mongoService.php";
include_once "translationService.php";
include_once "questionService.php";


$question = new QuestionService();
$mongo = new MongoDBService();

if(isset($_POST["method"]) && $_POST["method"] == "changeLanguage"){
    $filterQuery = (['id' => $_POST["id"]]);
    $options = [];
    $objToTranslate = $mongo->read("questions",$filterQuery,$options);
    print_r($objToTranslate);
    echo "<br><br><br>";
    $translation = new TranslationService($_POST["selLanguage"]);
    $translatedQuestion = $translation->translateObject($objToTranslate);
    print_r($translatedQuestion);
    #$mongo->insertMultiple("questions",$translatedQuestion);
    exit();
}


?>