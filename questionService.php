<?php
include_once "questions2.php";

class QuestionService{
    var $questionToRead;
    var $basePath;

    function __construct(){
    }

    function getQuestion($questionToRead,$basePath){
        $set = filter_var($questionToRead, FILTER_SANITIZE_STRING);
        $lines =  file($basePath."/".$set, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        return array_map('parseLine', $lines);
    }

    //JSON_FORCE_OBJECT | 
    function serializeQuestion($questionObject){
        return $encodedJson = json_encode($questionObject, JSON_PRETTY_PRINT);
    }


    function parseReadInQuestion($questionObject){


        $questionType = $questionObject->questionType;
        $question = $questionObject->question->jsonSerialize();
        $answer = $questionObject->answer;
        $version = $questionObject->version;
        $id = $questionObject->id;
        $karma = $questionObject->karma;
        $author = $questionObject->author;
        $tags = $questionObject->tags;

        if( $questionType == "YesNo" ) {
            $formattedQuestion = new YesNoQuestion($question, $answer, $questionType, $version, $id, $karma, $author, $tags);
        } else if( $questionType == "RegOpen" ) {
            $formattedQuestion = new RegOpenQuestion($question, $answer, $questionType, $version, $id, $karma, $author, $tags);
        } else if( $questionType == "Open" ) {
            $formattedQuestion = new OpenQuestion($question, $answer, $questionType, $version, $id, $karma, $author, $tags);
        } else if( $questionType == "Correct" ) {
            $formattedQuestion = new CorrectQuestion($question, $answer, $questionType, $version, $id, $karma, $author, $tags);
        } else if( $questionType == "Order" ) {
            $options = $questionObject->options;
            $formattedQuestion = new OrderQuestion($question, $answer, $options, $questionType, $version, $id, $karma, $author, $tags);
        } else if( $questionType == "Options" ) {
            $options = $questionObject->options;
            $formattedQuestion = new OptionsQuestion($question, $answer, $options, $questionType, $version, $id, $karma, $author, $tags);
        } else if( $questionType == "MultiOptions" ) {
            $options = $questionObject->options;
            $formattedQuestion = new MultiOptionsQuestion($question, $answer, $options, $questionType, $version, $id, $karma, $author, $tags);
        } else if( $questionType == "Dyn" ) {
            /*
            $func = $parts[1];
            if( method_exists( "Dyn", $func ) ) {
                return Dyn::{$func}();
            } else {
                return new Question( "-", "", "", "");
            }
            */
        } else {
            $formattedQuestion = new Question( "-", "", "", "", "", "", "", "");
        }
        return [$formattedQuestion];
    }
}


/*
$question = new ReadQuestion("Java","topics");
$question->getQuestion();
$question->printQuestion();
*/

?>