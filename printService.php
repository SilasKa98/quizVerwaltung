<?php
include_once "karmaService.php";

class Printer{

    public function __construct() {
       $this->currentUserId = $_SESSION["userData"]["userId"];
    }


    function printQuestion($questionObject,$lang){
        $karmaObj = new KarmaService();
        $userKarmaGiven = $karmaObj->getKarmaUserRelation($this->currentUserId);
        $userKarmaGivenUp = (array)$userKarmaGiven->up;
        $userKarmaGivenDown = (array)$userKarmaGiven->down;
        for($i=0;$i<count($questionObject);$i++){
            print '
            <div class="card questionCard">
                <div class="card-header"><h5 class="card-title">'.$questionObject[$i]->question->$lang.'</h5>
                    <select onchange="changeLanguage(this)" name="language">
                        <option></option>
                        <option>DE</option>
                        <option>en-Us</option>
                    </select>
                    <div style="display:none;"><button id="saveOnly">Nur Übersetzen</button><button id="transAndSave">Übersetzen & Speichern</button></div>
                    <input type="hidden" value="'.$questionObject[$i]->id.'">
                    <input type="hidden" value="'.$lang.'">
                    <div class="karmaWrapper">
                        <p class="karmaDisplay">Karma:<span id="karma_'.$questionObject[$i]->id.'">'.$questionObject[$i]->karma.'</span></p>
                        <button class="karmaBtn" id="'.$questionObject[$i]->id.'" name="increaseKarma" onclick="changeKarma(this)"'; if(array_search($questionObject[$i]->id,$userKarmaGivenUp)!== false){ print "style='background: rgb(5, 125, 238);'";} print'>&#8593;</button>
                        <button class="karmaBtn" id="'.$questionObject[$i]->id.'" name="decreaseKarma" onclick="changeKarma(this)"'; if(array_search($questionObject[$i]->id,$userKarmaGivenDown)!== false){ print "style='background: rgb(5, 125, 238);'";} print'>&#8595;</button>
                    </div>
                </div>';
                print'<div class="card-body" id="'.$questionObject[$i]->id.'">';
                print'<p "card-text">Antwort: '.$questionObject[$i]->answer."</p>";
                print'<p "card-text">Typ: '.$questionObject[$i]->questionType."</p>";

                if(isset($questionObject[$i]->options)){
                    print'<p "card-text">Optionen: ';
                    for($x=0;$x<count($questionObject[$i]->options);$x++){
                        print'<span class="badge badge-secondary" style="margin-right: 2px;">'.$questionObject[$i]->options[$x].'</span>';
                    }
                    print'</p>';
                }
                
                print'<p "card-text">Erstellungsdatum: '.$questionObject[$i]->creationDate."</p>";
                print'<p "card-text">Letzte Änderung: '.$questionObject[$i]->modificationDate."</p>";
                print'<p "card-text">Version: '.$questionObject[$i]->version."</p>";
                print'<p "card-text">Tags: '.$questionObject[$i]->tags."</p>";
                print'</div>
            </div>';
        }
    }
}


?>