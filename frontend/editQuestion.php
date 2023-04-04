<?php
session_start();
if(!$_SESSION["logged_in"]){
    header("Location: loginAccount.php");
    exit();
}
extract($_SESSION["userData"]);

include_once "../services/mongoService.php";

$mongo = new MongoDBService();

//get the selected userLanguage to display the system in the right language
$filterQuery = (['userId' => $userId]);
$userInfo= $mongo->findSingle("accounts",$filterQuery,[]);
$selectedLanguage = $userInfo->userLanguage;
include "../systemLanguages/text_".$selectedLanguage.".php";


//get all available Tags
$allTagsObj= $mongo->findSingle("tags",[],[]);
$allTags = implode(",",(array)$allTagsObj->allTags);


//get the selected question
$questionFilterQuery = (['id' => $_GET["questionId"]]);
$selectedQuestion= $mongo->findSingle("questions",$questionFilterQuery,[]);

if(!isset($selectedQuestion)){
    echo "Illegal Parameters";
    echo " <a href='../index.php'>Back to Home</a>";
    exit();
}
$isAdmin = $userInfo["isAdmin"];

include_once "../services/accountService.php";
$account = new AccountService();
$lang = $account->getUserQuestionLangRelation($userId, $_GET["questionId"]);


$question = $selectedQuestion->question[$lang];
$answer = $selectedQuestion->answer;
$tags = $selectedQuestion->tags;
$version = $selectedQuestion->version;
$modificationDate = $selectedQuestion->modificationDate;


if(isset($selectedQuestion->options)){
    $options = $selectedQuestion->options;
}


if(isset($isAdmin) && $isAdmin == true){
    $verification = $selectedQuestion->verification;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/quizVerwaltung/stylesheets/importQuestionCheck.css">
    <link rel="stylesheet" href="/quizVerwaltung/stylesheets/general.css">
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>

    <?php include_once "navbar.php";?>
    <?php include_once "notificationToast.php";?>


<!-- modal for tag changes-->
    <div class="modal fade" id="changeTagsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"><?php echo $selectYourTagsHeader; ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="ChangeModal-body">
                <div class="btn-group" role="group" id="tags_holder" aria-label="Basic checkbox toggle button group">
                    <?php foreach((array)$allTagsObj->allTags as $key => $tag){ ?>
                        <input type="checkbox" name="<?php echo $tag; ?>" class="btn-check tagBtn" id="btncheck<?php echo $key;?>" <?php if(in_array($tag, (array)$tags)){ echo"checked"; } ?>>
                        <label class="btn btn-outline-secondary tagsBtn" for="btncheck<?php echo $key; ?>"><?php echo $tag; ?></label>
                    <?php } ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="submitTagSelectionBtn"  data-bs-dismiss="modal" class="btn btn-primary">Save</button>
            </div>
            </div>
        </div>
    </div>

    <!-- modal for Question change-->
    <div class="modal fade" id="changeQuestionModal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel2"><?php echo $writeNewQuestionHeader; ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"  id="question_holder" >
                <p id="ChangeModal-body">
                    <textarea id="changeQuestionTextarea" name="questionText"><?php echo $question; ?></textarea>
                    <input type="hidden"  id="changeQuestionLanguage" name="questionLanguage" value="<?php echo $lang;?>">
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="submitQuestionChangeBtn"  data-bs-dismiss="modal" class="btn btn-primary">Save</button>
            </div>
            </div>
        </div>
    </div>

     <!-- modal for Verification-->
     <div class="modal fade" id="changeVerificationModal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel2"><?php echo $changeVerificationModalHeader; ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p style="display: inline;"><?php echo $currentVerificationStatusText; ?></p>
                <button type="button" class="btn btn-outline-success verificationBtn <?php if($verification == "verified"){ echo "active";}?>" name="verified" onclick="frontendChangeVerificationDisplay(this)"><?php echo $verifiedStatus; ?></button>
                <button type="button" class="btn btn-outline-danger verificationBtn <?php if($verification == "not verified"){ echo "active";}?>" name="not verified" onclick="frontendChangeVerificationDisplay(this)"><?php echo $notVerifiedStatus; ?></button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="sumbitVerificationBtn"  data-bs-dismiss="modal" class="btn btn-primary">Save</button>
            </div>
            </div>
        </div>
    </div>

    <?php if(isset($options[$lang])){?> 
        <!-- modal for options change (also handling answers for options questions)-->
        <div class="modal fade" id="changeOptionsModal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel2"><?php echo $changeOptionsModalHeader; ?></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php $explodedOptionsAnswers = explode(",", $answer);?>
                    <?php  foreach($options[$lang] as $key => $value) {?>
                        <input type="hidden" id="answerOptionsQuestionType" name="answerOptionsQuestionType" value="<?php echo $selectedQuestion->questionType;?>">
                        <input type="hidden" id="answerOptionsLang" name="answerOptionsLang" value="<?php echo $lang;?>">
                        <div class="input-group mb-3" style="margin-bottom: 5px !important;">
                            <div class="input-group-text">
                                <?php if($selectedQuestion->questionType == "MultiOptions"){?> 
                                    <input class="form-check-input mt-0 editOptionsCheck" type="checkbox" aria-label="options Input" <?php if(in_array($key, $explodedOptionsAnswers)){echo "checked";}?>>
                                <?php }else{?>
                                    <input class="form-check-input mt-0 editOptionsCheck" name="editQuestionOption" type="radio" aria-label="options Input" <?php if(in_array($key, $explodedOptionsAnswers)){echo "checked";}?>>
                                <?php }?>
                            </div>
                            <input type="text" class="text-bg-secondary form-control editOptionsValue" value="<?php echo $value;?>">
                        </div>
                    <?php }?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="sumbitOptionsBtn"  data-bs-dismiss="modal" class="btn btn-primary">Save</button>
                </div>
                </div>
            </div>
        </div>
    <?php }?>


    <?php if(!isset($options[$lang])){?> 
        <!-- modal for answer change (only open questions)-->
        <div class="modal fade" id="changeAnswerModal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel2"><?php echo $changeAnswerModalHeader; ?></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="answerQuestionType" name="answerQuestionType" value="<?php echo $selectedQuestion->questionType;?>">
                    <?php if($selectedQuestion->questionType == "YesNo"){?> 
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="radio" name="trueFalseRadio" role="switch" id="flexSwitchCheckAnswerTrue" <?php if($answer == "true"){ echo "checked";}?>>
                            <label class="form-check-label" for="flexSwitchCheckAnswerTrue"><?php echo $editTrueAnswer;?></label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="radio" name="trueFalseRadio" role="switch" id="flexSwitchCheckAnswerFalse" <?php if($answer == "false"){ echo "checked";}?>>
                            <label class="form-check-label" for="flexSwitchCheckAnswerFalse"><?php echo $editFalseAnswer;?></label>
                        </div>
                    <?php }else{?>
                        <p id="ChangeModal-body">
                            <textarea id="changeAnswerTextarea" name="answerText"><?php echo $answer; ?></textarea>
                        </p>
                    <?php }?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="sumbitAnswerBtn"  data-bs-dismiss="modal" class="btn btn-primary">Save</button>
                </div>
                </div>
            </div>
        </div>
    <?php }?>




    <h1 id="editQuestionHeader">Frage bearbeiten</h1>
    <div class="card text-center allCardsWrapper">
        <div class="card-header">
            <h3 class="card-title"></h3>
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-5 g-1">
                <div class="card mb-3 innerImportCard" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Question</h5>               
                        <p class="card-text"><?php echo $question;?></p>
                        <button class="btn btn-primary" onclick="changeQuestion('<?php echo $_GET['questionId']; ?>')"><?php echo $adjustButton;?></button>
                    </div>
                </div>
                
                <div class="card mb-3 innerImportCard" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Antwort</h5>
                        <?php if(!isset($options[$lang])){?>        
                            <p class="card-text"><?php echo $answer;?></p>
                        <?php }else{?>
                            <?php $explodedAnswers = explode(",", $answer);?>
                            <?php  foreach($options[$lang] as $key => $value) {?>
                                <?php if(in_array($key, $explodedAnswers)){?>                 
                                    <span class="badge rounded-pill text-bg-success"><?php echo $value;?></span>
                                <?php }?>
                            <?php }?>
                        <?php }?> 
                        <?php if(!isset($options[$lang])){?>          
                            <button class="btn btn-primary" onclick="changeAnswer('<?php echo $_GET['questionId']; ?>')"><?php echo $adjustButton;?></button>     
                        <?php }?>      
                    </div>
                </div>

                <div class="card mb-3 innerImportCard" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Tags</h5>
                        <p class="card-text">
                            <?php if(count($tags) == 0){?>   
                                <span id="selectedTagsZone">-</span>
                            <?php }?>     
                            <?php  foreach ($tags as $value) {?>       
                                <span class="badge rounded-pill text-bg-secondary" id="selectedTagsZone"><?php echo $value." ";?></span>
                            <?php }?>
                        </p>
                        <button class="btn btn-primary" onclick="changeQuestionTags('<?php echo $_GET['questionId']; ?>')"><?php echo $adjustButton;?></button>
                    </div>
                </div>

                <?php if(isset($selectedQuestion->options)){?>
                    <div class="card mb-3 innerImportCard" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Optionen</h5>
                            <p class="card-text">
                                <?php  foreach($options[$lang] as $value) {?>                  
                                    <span class="badge rounded-pill text-bg-secondary"><?php echo $value;?></span>
                                <?php }?>
                            </p>
                            <button class="btn btn-primary" onclick="changeOptions('<?php echo $_GET['questionId']; ?>')"><?php echo $adjustButton;?></button>
                        </div>
                    </div>
                <?php }?>



                <?php if(isset($isAdmin) && $isAdmin == true){?>
                    <div class="card mb-3 innerImportCard" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Verification</h5>         
                            <p class="card-text">
                                <?php echo $verification;?>
                                <?php if($verification == "verified"){?>
                                    <img src="/quizVerwaltung/media/verified.png" width=25px>
                                <?php }else{?>
                                    <img src="/quizVerwaltung/media/notVerified.png" width=25px>
                                <?php }?>
                            </p>
                            <button class="btn btn-primary" onclick="changeVerification('<?php echo $_GET['questionId']; ?>')"><?php echo $adjustButton;?></button>
                        </div>
                    </div>
                <?php }?>

                <div class="card mb-3 innerImportCard" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Version</h5>               
                        <p class="card-text"><?php echo $version;?></p>
                    </div>
                </div>

                <div class="card mb-3 innerImportCard" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Modification Date</h5>               
                        <p class="card-text"><?php echo $modificationDate;?></p>
                    </div>
                </div>

                <div class="card mb-3 innerImportCard" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Delete Question</h5>        
                        <br>       
                        <button class="btn btn-danger" id="deleteQuestionBtn" onclick="deleteQuestion('<?php echo $_GET['questionId']; ?>')">Delete Question</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="/quizVerwaltung/scripts/searchSystemScript.js"></script>
    <script src="/quizVerwaltung/scripts/editQuestionScript.js"></script>
    <script src="/quizVerwaltung/scripts/questionScripts.js"></script>
    
</body>
</html>