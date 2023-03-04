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


    function increaseDownloadCount($questionId){
        include_once "mongoService.php";
        $mongo = new MongoDBService();
        $filterQueryQuestion = (['id' => $questionId]);
        $searchQuestion = $mongo->findSingle('questions', $filterQueryQuestion);

        if(!isset($searchQuestion->downloadCount)){
            $downloadCount = 0;
        }else{
            $downloadCount = $searchQuestion->downloadCount;
        }
        $newDownloadCount = $downloadCount+1;
        
        $updateDownloadCounter = ['$set' =>  ['downloadCount' => $newDownloadCount]];
        $mongo->updateEntry("questions",$filterQueryQuestion,$updateDownloadCounter);   
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
        $creationDate = $questionObject->creationDate;
        $modificationDate = $questionObject->modificationDate;
        $verification = $questionObject->verification;
        $downloadCount = $questionObject->downloadCount;

        if( $questionType == "YesNo" ) {
            $formattedQuestion = new YesNoQuestion($question, $answer, $questionType, $version, $id, $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount);
        } else if( $questionType == "RegOpen" ) {
            $formattedQuestion = new RegOpenQuestion($question, $answer, $questionType, $version, $id, $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount);
        } else if( $questionType == "Open" ) {
            $formattedQuestion = new OpenQuestion($question, $answer, $questionType, $version, $id, $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount);
        } else if( $questionType == "Correct" ) {
            $formattedQuestion = new CorrectQuestion($question, $answer, $questionType, $version, $id, $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount);
        } else if( $questionType == "Order" ) {
            $options = $questionObject->options;
            $formattedQuestion = new OrderQuestion($question, $answer, $options, $questionType, $version, $id, $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount);
        } else if( $questionType == "Options" ) {
            $options = $questionObject->options;
            $formattedQuestion = new OptionsQuestion($question, $answer, $options, $questionType, $version, $id, $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount);
        } else if( $questionType == "MultiOptions" ) {
            $options = $questionObject->options;
            $formattedQuestion = new MultiOptionsQuestion($question, $answer, $options, $questionType, $version, $id, $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount);
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
            $formattedQuestion = new Question( "-", "", "", "", "", "", "", "", "", "", "", "");
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