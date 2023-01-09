<?php

include_once "questionService.php";
include_once "mongoService.php";


if (isset($_POST['import'])) {
    $inputFile = $_POST['inputFile'];
    $inputLang = $_POST['inputLang'];
    $question = new QuestionService();
    $questionObject = $question->getQuestion($inputFile,"topics");
    
    $mongo = new MongoDBService();
    $mongo->insertMultiple("questions",$questionObject);
}
if (isset($_POST['clean'])) {
    $mongo = new MongoDBService();
    $mongo->cleanCollection("questions");
}


header("LOCATION:index.php?insert=success");

?>