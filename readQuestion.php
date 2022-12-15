<?php
include_once "questions2.php";

class ReadQuestion{
    var $questionToRead;
    var $basePath;

    function __construct($setQuestionToRead,$setBasePath){
        $this->questionToRead = $setQuestionToRead;
        $this->basePath = $setBasePath;
    }

    function getQuestion(){
        $set = filter_var($this->questionToRead, FILTER_SANITIZE_STRING);
        $lines =  file($this->basePath."/".$set.".txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return array_map('parseLine', $lines);
    }

    function printQuestion($questionObject){
        for($i=0;$i<count($questionObject);$i++){
            print_r($questionObject[$i]);
            print "<br><br>";
        }
    }

    function serializeQuestion($questionObject){
        return json_encode($questionObject, JSON_PRETTY_PRINT);
    }

    function addAttributesToObject($questionObject){
        $id = uniqid();
        for($i=0;$i<count($questionObject);$i++){
           # print_r($questionObject[$i]);
            print get_class($questionObject[$i]);
            print "<br><br>";
        }
    }

}


/*
$question = new ReadQuestion("Java","topics");
$question->getQuestion();
$question->printQuestion();
*/

?>