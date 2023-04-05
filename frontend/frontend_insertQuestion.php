<?php
  session_start();
  if(!$_SESSION["logged_in"]){
    header("Location: loginAccount.php");
    exit();
  }
  extract($_SESSION["userData"]);
  //get the selected userLanguage to display the system in the right language
  include_once "../services/mongoService.php";
  $mongo = new MongoDBService();
  $filterQuery = (['userId' => $userId]);
  $selectedLanguage= $mongo->findSingle("accounts",$filterQuery,[]);
  $selectedLanguage = $selectedLanguage->userLanguage;
  include "../systemLanguages/text_".$selectedLanguage.".php";


  //get all available Tags
  $allTagsObj = $mongo->findSingle("tags",[]);
  $allTagsArray = (array)$allTagsObj->allTags;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../stylesheets/general.css">
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>
<body>

    <?php include_once "navbar.php";?>
    <?php include_once "notificationToast.php";?>


    <div class="container-fluid">

        <div class="alert alert-danger checkHelpAlert" role="alert">
            <p class="checkHelpAlertText"><?php echo $checkHelpPageAlertText;?><a href="/quizVerwaltung/frontend/helpPage.php#Importstructure" class="checkHelpAlertText"><?php echo $checkHelpPageBtn;?></a></p>
        </div>
        <hr>
    
        <h1 class="uploadMainHeading"><?php echo $upload_headingImportQuestions; ?></h1>
        <div class="container inputWithFileWrapper">
            <div class="center">
                <form method="post" action="/quizVerwaltung/insertQuestions.php"  enctype="multipart/form-data" class="inputFileUploadForm">
                    <input class="inputfile" type="file" name="inputFileData" onchange="setFilename(this)">
                    <button type="submit" name="import" class="btn btn-success"><?php echo $text_import_form["import_btn"]?></button>
                </form>
            </div>
        </div>


        <hr>

        <h1 class="uploadMainHeading"><?php echo $upload_headingCreateQuestions;?></h1>
        <div class="container inputWithFileWrapper">
            <div class="mb-3">
                <p style="margin-bottom: 5px;"><?php echo $upload_questionChoose; ?></p>
                <input type="radio" class="btn-check" name="questionOptions" id="yesNoOption" autocomplete="off" onclick="loadQuestionContent(this);">
                <label class="btn btn-outline-secondary" for="yesNoOption"><?php echo $liYesNoQuestion; ?></label>

                <input type="radio" class="btn-check" name="questionOptions" id="optionsOption" autocomplete="off" onclick="loadQuestionContent(this);">
                <label class="btn btn-outline-secondary" for="optionsOption"><?php echo $liOptionsQuestion; ?></label>

                <input type="radio" class="btn-check" name="questionOptions" id="multiOptionsOption" autocomplete="off" onclick="loadQuestionContent(this);">
                <label class="btn btn-outline-secondary" for="multiOptionsOption"><?php echo $liMultiOptionsQuestion; ?></label>

                <input type="radio" class="btn-check" name="questionOptions" id="openOption" autocomplete="off" onclick="loadQuestionContent(this);">
                <label class="btn btn-outline-secondary" for="openOption"><?php echo $liOpenQuestion; ?></label>
            </div>
            <form id="createQuestionForm" action="/quizVerwaltung/doTransaction.php" method="post">

                <div class="mb-3 formElemWrapper" id="questionTextWrapper">
                    <label for="questionText" class="form-label"><?php echo $upload_createQuestionText;?></label>
                    <textarea id="questionText" name="questionText" class="form-control"></textarea>
                </div>

                <div class="mb-3 formElemWrapper" id="questionTagsWrapper">
                    <span class="form-label"><?php echo $upload_questionTags;?></span>
                    <div class="btn-group" role="group" id="createQuestionTags" aria-label="Basic checkbox toggle button group">
                        <?php 
                        $counter = 0;
                        foreach($allTagsArray as $item){ 
                        ?>
                            <input type="checkbox" name="tags[<?php echo $item;?>]" class="btn-check" id="btncheck<?php echo $counter;?>">
                            <label class="btn btn-outline-secondary tagsBtn" for="btncheck<?php echo $counter;?>"><?php echo $item;?></label>
                        <?php
                            $counter++;
                        } 
                        ?>
                    </div>
                </div>

                <input type="hidden" name="method" value="createQuestionWithForm">

            </form>
        </div>
    </div>
    <script src="/quizVerwaltung/scripts/questionScripts.js"></script>
    <script src="/quizVerwaltung/scripts/searchSystemScript.js"></script>
    <script src="/quizVerwaltung/scripts/createQuestionForm.js"></script>
    <script>
        function loadQuestionContent(e){
            console.log(e.id);
            var questionForm = document.getElementById("createQuestionForm");

            document.getElementById("questionTextWrapper").style.display = "block";
            document.getElementById("questionTagsWrapper").style.display = "block";

            if(document.contains(document.getElementById("questionAnswerWrapper"))){
                document.getElementById("questionAnswerWrapper").remove();
            }
            if(document.contains(document.getElementById("createQuestionSubmitBtn"))){
                document.getElementById("createQuestionSubmitBtn").remove();
            }
            if(document.contains(document.getElementById("addButtonWrapper"))){
                document.getElementById("addButtonWrapper").remove();
            }
            
        
            let divAnswer;
            let questionType;
            let addBtn;
            switch(e.id) {
                case "yesNoOption":
                    divAnswer = createLabelAndSelect("questionAnswer", "<?php echo $upload_createQuestionAnswer;?>", ["True","False"]);
                    questionType = createhiddenInput("questionType", "YesNo");
                    questionForm.append(questionType);
                    questionForm.append(divAnswer);
                    break;
                case "openOption":
                    divAnswer = createLabelAndTextarea("questionAnswer", "<?php echo $upload_createQuestionAnswer;?>");
                    questionType = createhiddenInput("questionType", "Open");
                    questionForm.append(questionType);
                    questionForm.append(divAnswer);
                    break;
                case "optionsOption":
                    addBtn = createLabelAndButton("addButton", "<?php echo $upload_addOption; ?>", "+", "addInputAndRadio(this)");
                    questionType = createhiddenInput("questionType", "Options");
                    questionForm.append(addBtn);
                    questionForm.append(questionType);
                    break;
                case "multiOptionsOption":
                    addBtn = createLabelAndButton("addButton", "<?php echo $upload_addOption; ?>", "+", "addInputAndCheckbox(this)");
                    questionType = createhiddenInput("questionType", "MultiOptions");
                    questionForm.append(addBtn);
                    questionForm.append(questionType);
                    break;
            }

            //let method = createhiddenInput("method", "createQuestionWithForm");
            //questionForm.append(method);

            let submitBtn = document.createElement("button");
            submitBtn.setAttribute("type", "submit");
            submitBtn.setAttribute("class", "btn btn-success");
            submitBtn.setAttribute("id", "createQuestionSubmitBtn");
            submitBtn.innerHTML = "<?php echo $text_import_form["import_btn"];?>";
            questionForm.append(submitBtn);
            
        }
    </script>


<script type="text/javascript">
    //script to track if the user is active atm, then inserts the current timestamp in the database 
        var timeout;
        var delay = 2000;   // 2s
        document.addEventListener("mousemove", function(e) {
            if(timeout) {
                clearTimeout(timeout);
            }
            timeout = setTimeout(function() {
                <?php
                    //update the last user activity time
                    $currentTimestamp = time();
                    $currentUserFilter = (['userId' => $_SESSION["userData"]["userId"]]);
                    $updateLastActivityTimestamp = ['$set' =>  ['lastActivityTimestamp'=> $currentTimestamp]];
                    $mongo->updateEntry("accounts",$currentUserFilter,$updateLastActivityTimestamp); 
                ?>
            }, delay);
        });

        document.addEventListener("keypress", function(e) {
            if(timeout) {
                clearTimeout(timeout);
            }
            timeout = setTimeout(function() {
                <?php
                    //update the last user activity time
                    $currentTimestamp = time();
                    $currentUserFilter = (['userId' => $_SESSION["userData"]["userId"]]);
                    $updateLastActivityTimestamp = ['$set' =>  ['lastActivityTimestamp'=> $currentTimestamp]];
                    $mongo->updateEntry("accounts",$currentUserFilter,$updateLastActivityTimestamp); 
                ?>
            }, delay);
        });

    </script>
</body>
</html>