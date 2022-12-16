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

    function printQuestion($questionObject){
        for($i=0;$i<count($questionObject);$i++){
            print '
            <div class="panel panel-info">
                <div class="panel-heading">'.$questionObject[$i]->question.'</div>';
                print'<div class="panel-body">';
                print'<p>Antwort: '.$questionObject[$i]->answer."</p>";
                print'<p>Typ: '.$questionObject[$i]->questionType."</p>";

                if(isset($questionObject[$i]->options)){
                    print'<p>Optionen: ';
                    for($x=0;$x<count($questionObject[$i]->options);$x++){
                        print'<span class="badge badge-secondary" style="margin-right: 2px;">'.$questionObject[$i]->options[$x].'</span>';
                    }
                    print'</p>';
                }
                
                print'<p>Erstellungsdatum: '.$questionObject[$i]->creationDate."</p>";
                print'<p>Letzte Änderung: '.$questionObject[$i]->modificationDate."</p>";
                print'<p>Version: '.$questionObject[$i]->version."</p>";
                print'<p>Tags: '.$questionObject[$i]->tags."</p>";
                print'</div>
            </div>';


            #print "<h1 class='question'>".$questionObject[$i]->question."</h1>";
        }
    }
    //JSON_FORCE_OBJECT | 
    function serializeQuestion($questionObject){
        return $encodedJson = json_encode($questionObject, JSON_PRETTY_PRINT);
    }


    function parseReadInQuestion($questionObject){

        $questionType = $questionObject->questionType;
        $question = $questionObject->question;
        $answer = $questionObject->answer;
        $version = $questionObject->version;
    
        if( $questionType == "YesNo" ) {
            $formattedQuestion = new YesNoQuestion($question, $answer, $questionType, $version);
        } else if( $questionType == "RegOpen" ) {
            $formattedQuestion = new RegOpenQuestion($question, $answer, $questionType, $version);
        } else if( $questionType == "Open" ) {
            $formattedQuestion = new OpenQuestion($question, $answer, $questionType, $version);
        } else if( $questionType == "Correct" ) {
            $formattedQuestion = new CorrectQuestion($question, $answer, $questionType, $version);
        } else if( $questionType == "Order" ) {
            $options = $questionObject->options;
            $formattedQuestion = new OrderQuestion($question, $answer, $options, $questionType, $version);
        } else if( $questionType == "Options" ) {
            $options = $questionObject->options;
            $formattedQuestion = new OptionsQuestion($question, $answer, $options, $questionType, $version);
        } else if( $questionType == "MultiOptions" ) {
            $options = $questionObject->options;
            $formattedQuestion = new MultiOptionsQuestion($question, $answer, $options, $questionType, $version);
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
            $formattedQuestion = new Question( "-", "", "", "");
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