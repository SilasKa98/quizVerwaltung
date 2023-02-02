<?php
include_once "karmaService.php";
include_once "mongoService.php";

class Printer{

    public function __construct() {
       $this->currentUserId = $_SESSION["userData"]["userId"];
    }


    function printQuestion($questionObject,$lang){
        $karmaObj = new KarmaService();
        $userKarmaGiven = $karmaObj->getKarmaUserRelation($this->currentUserId);
        $userKarmaGivenUp = (array)$userKarmaGiven->up;
        $userKarmaGivenDown = (array)$userKarmaGiven->down;

        $mongo = new MongoDbService();
        $options = [];


        for($i=0;$i<count($questionObject);$i++){
            $filterQuery = (['id' => $questionObject[$i]->id]);
            $checkAvailableTranslations = $mongo->findSingle("questions",$filterQuery,$options);
            $checkAvailableTranslations = $checkAvailableTranslations->question;
            $allAvailableTranslations = array_keys((array)$checkAvailableTranslations);

            print'
                <div class="container-fluid">
                    <div class="btn-group rightOuterMenu">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                           
                        </button>
                        <ul class="dropdown-menu outerMenuItems">
                        <li data-bs-toggle="modal" name="'.$questionObject[$i]->id.'_'.$lang.'" data-bs-target="#changeLangModal" class="outerMenuItemsListElem" onclick="changeLanguage(this)"><a class="dropdown-item"><img src="media/square-plus.svg" width="20px" ></a></li>
                        <li class="outerMenuItemsListElem"><a class="dropdown-item" href="#"><img src="media/basket-shopping.svg" width="20px"></a></li>
                        </ul>
                    </div>
                    <div class="card questionCard">
                        <div class="card-header">
                            <a class="collapsable_header" data-bs-toggle="collapse" href="#collapsable_'.$questionObject[$i]->id.'" >
                                '.$questionObject[$i]->question->$lang.'
                            </a>
                            <div class="rightInnerMenuWrapper">
                                <p class="karmaDisplay">
                                    <span id="karma_'.$questionObject[$i]->id.'">'.$questionObject[$i]->karma.'</span>
                                </p>
                                <button class="karmaBtn" id="'.$questionObject[$i]->id.'" name="increaseKarma" onclick="changeKarma(this)"'; if(array_search($questionObject[$i]->id,$userKarmaGivenUp)!== false){ print "style='background: rgb(5, 125, 238);'";} print'>&#8593;</button>
                                <button class="karmaBtn" id="'.$questionObject[$i]->id.'" name="decreaseKarma" onclick="changeKarma(this)"'; if(array_search($questionObject[$i]->id,$userKarmaGivenDown)!== false){ print "style='background: rgb(5, 125, 238);'";} print'>&#8595;</button>
                                <br>
                                <select class="selLanguageDropDown" name="language">
                                    <option></option>
                                ';
                                    foreach($allAvailableTranslations as $avLang){
                                        print '<option>'.$avLang.'</option>';
                                    }
                          print'</select>';
                      print'</div>
                        </div>
                        <div class="collapse" id="collapsable_'.$questionObject[$i]->id.'">';
                            print'<div class="card-body">';
                                print'<p "card-text">Antwort: '.$questionObject[$i]->answer."</p>";
                                print'<p "card-text">Typ: '.$questionObject[$i]->questionType."</p>";

                                if(isset($questionObject[$i]->options)){
                                    print'<p "card-text">Optionen: ';
                                    for($x=0;$x<count($questionObject[$i]->options->$lang);$x++){
                                        print'<span class="badge rounded-pill text-bg-primary" style="margin-right: 2px;">'.$questionObject[$i]->options->$lang[$x].'</span>';
                                    }
                                    print'</p>';
                                }
                                
                                print'<p "card-text">Erstellungsdatum: '.$questionObject[$i]->creationDate."</p>";
                                print'<p "card-text">Letzte Änderung: '.$questionObject[$i]->modificationDate."</p>";
                                print'<p "card-text">Version: '.$questionObject[$i]->version."</p>";
                                print'<p "card-text">Tags: '.$questionObject[$i]->tags."</p>";
                                print'<p "card-text">Author: <a href="frontend/userProfile.php?username='.$questionObject[$i]->author.'"><span class="badge rounded-pill text-bg-primary" style="margin-right: 2px;">'.$questionObject[$i]->author."</span></a></p>";
                            print'</div>
                        </div>
                    </div> 
                </div>      
            ';
            
            /*
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
                        <p class="karmaDisplay"><span id="karma_'.$questionObject[$i]->id.'">'.$questionObject[$i]->karma.'</span></p>
                        <button class="karmaBtn" id="'.$questionObject[$i]->id.'" name="increaseKarma" onclick="changeKarma(this)"'; if(array_search($questionObject[$i]->id,$userKarmaGivenUp)!== false){ print "style='background: rgb(5, 125, 238);'";} print'>&#8593;</button>
                        <button class="karmaBtn" id="'.$questionObject[$i]->id.'" name="decreaseKarma" onclick="changeKarma(this)"'; if(array_search($questionObject[$i]->id,$userKarmaGivenDown)!== false){ print "style='background: rgb(5, 125, 238);'";} print'>&#8595;</button>
                    </div>
                </div>';
                print'<div class="card-body" id="'.$questionObject[$i]->id.'">';
                print'<p "card-text">Antwort: '.$questionObject[$i]->answer."</p>";
                print'<p "card-text">Typ: '.$questionObject[$i]->questionType."</p>";

                if(isset($questionObject[$i]->options)){
                    print'<p "card-text">Optionen: ';
                    for($x=0;$x<count($questionObject[$i]->options->$lang);$x++){
                        print'<span class="badge rounded-pill text-bg-primary" style="margin-right: 2px;">'.$questionObject[$i]->options->$lang[$x].'</span>';
                    }
                    print'</p>';
                }
                
                print'<p "card-text">Erstellungsdatum: '.$questionObject[$i]->creationDate."</p>";
                print'<p "card-text">Letzte Änderung: '.$questionObject[$i]->modificationDate."</p>";
                print'<p "card-text">Version: '.$questionObject[$i]->version."</p>";
                print'<p "card-text">Tags: '.$questionObject[$i]->tags."</p>";
                print'<p "card-text">Author: <a href="frontend/userProfile.php?username='.$questionObject[$i]->author.'"><span class="badge rounded-pill text-bg-primary" style="margin-right: 2px;">'.$questionObject[$i]->author."</span></a></p>";
                print'</div>
            </div>';
            */
        }
    }
}


?>