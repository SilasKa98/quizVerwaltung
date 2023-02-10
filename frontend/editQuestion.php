<?php
session_start();
if(!$_SESSION["logged_in"]){
    header("Location: loginAccount.php");
    exit();
}
extract($_SESSION["userData"]);

include_once "../mongoService.php";

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

//get language of the edited question
$questionLanguageRelation = (array)$userInfo["questionLangUserRelation"];
$lang = array_search($_GET["questionId"],$questionLanguageRelation);
//check if there is no language set for this question by the user
if(!$lang){
    //get the first key of the question so it can be used to set it as the default language
    $lang = array_key_first((array)$selectedQuestion->question);
}
   

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
                        <input type="checkbox" name="<?php echo $tag; ?>" class="btn-check" id="btncheck<?php echo $key;?>" <?php if(in_array($tag, (array)$tags)){ echo"checked"; } ?>>
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
                    <textarea id="changeQuestionTextarea" id="changeQuestionText" name="questionText"><?php echo $question; ?></textarea>
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
                        <p class="card-text"><?php echo $answer;?></p>
                        <button class="btn btn-primary"><?php echo $adjustButton;?></button>
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
                            <button class="btn btn-primary"><?php echo $adjustButton;?></button>
                        </div>
                    </div>
                <?php }?>



                <?php if(isset($isAdmin) && $isAdmin == true){?>
                    <div class="card mb-3 innerImportCard" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">Verification</h5>         
                            <p class="card-text"><?php echo $verification;?></p>
                            <button class="btn btn-primary"><?php echo $adjustButton;?></button>
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