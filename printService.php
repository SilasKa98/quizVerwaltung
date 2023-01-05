<?php

class Printer{

    public function __construct() {
    }

    function printQuestion($questionObject){
        for($i=0;$i<count($questionObject);$i++){

            print '
            <div class="panel panel-info">
                <div class="panel-heading">'.$questionObject[$i]->question.'
                <select onchange="changeLanguage(this)" name="language">
                    <option></option>
                    <option>DE</option>
                    <option>En-us</option>
                </select>
                <div style="display:none;"><input type="submit" value="Nur Übersetzen"><input type="submit" value="Übersetzen & Speichern"></div>
                <input type="hidden" value="'.$questionObject[$i]->id.'">
                </div>';
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
        }
    }
}


?>