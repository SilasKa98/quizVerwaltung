<?php

class Printer{

    public function __construct() {
    }

    function printQuestion($questionObject,$lang){
        for($i=0;$i<count($questionObject);$i++){
            #print_r($questionObject[$i]);
            print '
            <div class="panel panel-info">
                <div class="panel-heading">'.$questionObject[$i]->question->$lang.'
                <select onchange="changeLanguage(this)" name="language">
                    <option></option>
                    <option>DE</option>
                    <option>en-Us</option>
                </select>
                <div style="display:none;"><button id="saveOnly">Nur Übersetzen</button><button id="transAndSave">Übersetzen & Speichern</button></div>
                <input type="hidden" value="'.$questionObject[$i]->id.'">
                <input type="hidden" value="'.$lang.'">

                <button id="'.$questionObject[$i]->id.'" name="increaseKarma" onclick="changeKarma(this)">&#8593;</button>
                <button id="'.$questionObject[$i]->id.'" name="decreaseKarma" onclick="changeKarma(this)">&#8595;</button>
                </div>';
                print'<div class="panel-body" id="'.$questionObject[$i]->id.'">';
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
                print'<p id="karma_'.$questionObject[$i]->id.'">Karma: '.$questionObject[$i]->karma."</p>";
                print'</div>
            </div>';
        }
    }
}


?>