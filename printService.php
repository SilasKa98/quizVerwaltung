<?php
include_once "karmaService.php";
include_once "mongoService.php";
include_once "questionService.php";

class Printer{

    public function __construct() {
       $this->currentUserId = $_SESSION["userData"]["userId"];
    }


    function printQuestion($questionObject){
        $karmaObj = new KarmaService();
        $userKarmaGiven = $karmaObj->getKarmaUserRelation($this->currentUserId);
        $userKarmaGivenUp = (array)$userKarmaGiven->up;
        $userKarmaGivenDown = (array)$userKarmaGiven->down;

        $mongo = new MongoDbService();
        $options = [];

        //find QuestionLangRelation of current user:
        $searchUserFilter = (['userId'=>$this->currentUserId]);
        $searchUser = $mongo->findSingle("accounts",$searchUserFilter,[]);
        $questionLanguageRelation = (array)$searchUser["questionLangUserRelation"];
        $isUserAdmin = $searchUser["isAdmin"];


        for($i=0;$i<count($questionObject);$i++){
            
            $lang = array_search($questionObject[$i]->id,$questionLanguageRelation);

            //check if there is no language set for this question by the user
            if(!$lang){
                //get the first key of the question so it can be used to set it as the default language
                $lang = array_key_first((array)$questionObject[$i]->question);
            }
               
            //find all available translation for this question to display it in the <select> dropdown for questionLanguage selection
            $filterQuery = (['id' => $questionObject[$i]->id]);
            $checkAvailableTranslations = $mongo->findSingle("questions",$filterQuery,$options);
            $checkAvailableTranslations = $checkAvailableTranslations->question;
            $allAvailableTranslations = array_keys((array)$checkAvailableTranslations);

            print'
                <div class="container-fluid">
                    <div class="btn-group rightOuterMenu">
                        <button class="btn btn-secondary btn-sm dropdown-toggle rightOuterMenuToggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                           
                        </button>
                        <ul class="dropdown-menu outerMenuItems">
                            <li data-bs-toggle="modal" name="'.$questionObject[$i]->id.'_'.$lang.'" data-bs-target="#changeLangModal" class="outerMenuItemsListElem" onclick="insertNewLanguage(this)">
                                <a class="dropdown-item">
                                    <img src="/quizVerwaltung/media/language.svg" width="20px" >
                                </a>
                            </li>
                            <li class="outerMenuItemsListElem" name="'.$questionObject[$i]->id.'" onclick="addToCart(this)">
                                <a class="dropdown-item add-to-cart">
                                    <img src="/quizVerwaltung/media/basket-shopping.svg" width="20px">
                                </a>
                            </li>
                            ';
                            //check if user owns question or if user as admin access
                            if($questionObject[$i]->author == $_SESSION["userData"]["username"] || $isUserAdmin){
                                print'
                                <li class="outerMenuItemsListElem" onclick="editQuestion(this)">
                                    <form action="/quizVerwaltung/frontend/editQuestion.php" method="GET">
                                        <input type="hidden" name="questionId" value="'.$questionObject[$i]->id.'">
                                    </form>
                                    <a class="dropdown-item">
                                        <img src="/quizVerwaltung/media/pen-to-square.svg" width="17px">
                                    </a>
                                </li>
                                ';
                            }
                            
                   print'</ul>
                    </div>
                    <div class="card questionCard">
                        <div class="card-header"'; if(isset($_GET["searchedQuestionId"]) && $_GET["searchedQuestionId"] == $questionObject[$i]->id ){ print "style=\"background-color: #fbf5bf\""; } print'>
                            <a class="collapsable_header" data-bs-toggle="collapse" href="#collapsable_'.$questionObject[$i]->id.'" '; if(isset($_GET["searchedQuestionId"]) && $_GET["searchedQuestionId"] == $questionObject[$i]->id ){ print "id=\"searchFocus\""; } print'>
                                <span id="headerText_'.$questionObject[$i]->id.'">'.$questionObject[$i]->question->$lang.'</span>
                            </a>
                            <div class="rightInnerMenuWrapper">
                                <p class="karmaDisplay">
                                    <span id="karma_'.$questionObject[$i]->id.'">'.$questionObject[$i]->karma.'</span>
                                </p>
                                <button class="karmaBtn" id="'.$questionObject[$i]->id.'" name="increaseKarma" onclick="changeKarma(this)"'; if(array_search($questionObject[$i]->id,$userKarmaGivenUp)!== false){ print "style='background: rgb(5, 125, 238);'";} print'>&#8593;</button>
                                <button class="karmaBtn" id="'.$questionObject[$i]->id.'" name="decreaseKarma" onclick="changeKarma(this)"'; if(array_search($questionObject[$i]->id,$userKarmaGivenDown)!== false){ print "style='background: rgb(5, 125, 238);'";} print'>&#8595;</button>
                                <br>';

                                if(count($allAvailableTranslations) > 1){
                                    print'<select class="selLanguageDropDown" name="changeLang_'.$questionObject[$i]->id.'" onchange="changeQuestionLanguage(this)" name="language">';
                                        foreach($allAvailableTranslations as $avLang){ 
                                            if($avLang == $lang){
                                                print '<option selected>'.$avLang.'</option>';
                                            }else{
                                                print '<option>'.$avLang.'</option>'; 
                                            }       
                                        }
                                    print'</select>';
                                }
                                
                      print'</div>
                        </div>
                        <div class="collapse" id="collapsable_'.$questionObject[$i]->id.'">';
                            print'<div class="card-body">';
                                print'<p "card-text">Antwort: '.$questionObject[$i]->answer."</p>";
                                print'<p "card-text">Typ: '.$questionObject[$i]->questionType."</p>";

                                if(isset($questionObject[$i]->options)){
                                    print'<p "card-text">Optionen: ';
                                    for($x=0;$x<count($questionObject[$i]->options->$lang);$x++){
                                        $questionAnswers = explode(",",$questionObject[$i]->answer);
                                        if(in_array($x, $questionAnswers)){
                                            print'<span id="optionField_'.$x.'_'.$questionObject[$i]->id.'" class="badge rounded-pill text-bg-success" style="margin-right: 2px;">'.$questionObject[$i]->options->$lang[$x].'</span>';
                                        }else{
                                            print'<span id="optionField_'.$x.'_'.$questionObject[$i]->id.'" class="badge rounded-pill text-bg-secondary" style="margin-right: 2px;">'.$questionObject[$i]->options->$lang[$x].'</span>';
                                        }
                                        
                                    }
                                    print'</p>';
                                }
                                
                                print'<p "card-text">Erstellungsdatum: '.$questionObject[$i]->creationDate."</p>";
                                print'<p "card-text">Letzte Änderung: '.$questionObject[$i]->modificationDate."</p>";
                                print'<p "card-text">Version: '.$questionObject[$i]->version."</p>";
                                
                                if(isset($questionObject[$i]->tags)){
    
                                    print'<p "card-text" class="questionTagsWrapper">Tags: ';
                                    for($x=0;$x<count($questionObject[$i]->tags);$x++){
                                        print'<span class="badge rounded-pill text-bg-secondary" style="margin-right: 2px;">'.$questionObject[$i]->tags[$x].'</span>';
                                    }
                                    print'</p>';
                                }

                                print'<p "card-text">Author: <a href="/quizVerwaltung/frontend/userProfile.php?profileUsername='.$questionObject[$i]->author.'&section=questions"><span class="badge rounded-pill text-bg-primary authorPill" style="margin-right: 2px;">@'.$questionObject[$i]->author."</span></a></p>";
                            print'</div>
                        </div>
                    </div> 
                </div>      
            ';
        }
    }

    function printCatalog($catalogObject){
        $mongo = new MongoDbService();
        $printer = new Printer();

        //find QuestionLangRelation of current user:
        $searchUserFilter = (['userId'=>$this->currentUserId]);
        $searchUser = $mongo->findSingle("accounts",$searchUserFilter,[]);
        $questionLanguageRelation = (array)$searchUser["questionLangUserRelation"];

        print'
        <div class="card questionCard">
            <div class="card-header">
                <a class="collapsable_header" data-bs-toggle="collapse" href="#collapsable_'.$catalogObject->id.'">
                    <span id="headerText_'.$catalogObject->id.'">'.$catalogObject->name.'</span>
                </a>
            </div>
            <div class="collapse" id="collapsable_'.$catalogObject->id.'">
                <div class="card-body">';

                //print each question of the catalog with the printQuestion function
                    foreach($catalogObject->questions as $question){
                        $searchQuestionFilter = (['id'=>$question]);
                        $searchQuestion = $mongo->read("questions",$searchQuestionFilter,[]);
                        $readyForPrintQuestions = [];
                        foreach ($searchQuestion as $doc) {
                            $question = new QuestionService();
                            $fetchedQuestion = $question->parseReadInQuestion($doc);
                            array_push($readyForPrintQuestions,$fetchedQuestion);
                        }

                        foreach ($readyForPrintQuestions as $doc) {
                            $printer->printQuestion($doc);
                        }
                    }

                print'  
                </div>
            </div>
        </div>
        ';
    }
}


?>


