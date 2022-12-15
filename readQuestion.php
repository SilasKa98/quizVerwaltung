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
            print '
            <div class="panel panel-info">
                <div class="panel-heading">'.$questionObject[$i]->question.'</div>';
                print'<div class="panel-body">';
                print'<p>Antwort: '.$questionObject[$i]->answer."</p>";
                print'<p>Typ: '.$questionObject[$i]->questionType."</p>";
                #print'<p>Optionen: '.$questionObject[$i]->options."</p>";
                print'<p>Erstellungsdatum: '.$questionObject[$i]->creationDate."</p>";
                print'<p>Letzte Änderung: '.$questionObject[$i]->modificationDate."</p>";
                print'<p>Version: '.$questionObject[$i]->version."</p>";
                print'<p>Tags: '.$questionObject[$i]->tags."</p>";
                print'</div>
            </div>';


            #print "<h1 class='question'>".$questionObject[$i]->question."</h1>";
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