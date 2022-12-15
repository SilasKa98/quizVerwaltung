<?php
include_once "questions2.php";

class QuestionService{
    var $questionToRead;
    var $basePath;

    function __construct($setQuestionToRead,$setBasePath){
        $this->questionToRead = $setQuestionToRead;
        $this->basePath = $setBasePath;
    }

    function getQuestion(){
        $set = filter_var($this->questionToRead, FILTER_SANITIZE_STRING);
        $lines =  file($this->basePath."/".$set, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
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

    function serializeQuestion($questionObject){
        return json_encode($questionObject, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
    }
}


/*
$question = new ReadQuestion("Java","topics");
$question->getQuestion();
$question->printQuestion();
*/

?>