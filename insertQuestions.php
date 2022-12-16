<?php

include_once "questionService.php";
include_once "mongoService.php";


if (isset($_POST['import'])) {
    $inputFile = $_POST['inputFile'];
    $question = new QuestionService();
    $questionObject = $question->getQuestion($inputFile,"topics");
    
    $mongo = new MongoDBService();
    $mongo->insertMultiple("questions",$questionObject);
}


header("LOCATION:index.php?insert=success");

?>