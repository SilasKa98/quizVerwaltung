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
    
        <h1 class="uploadMainHeading">Upload questions with a file</h1>
        <div class="container inputWithFileWrapper">
            <div class="center">
                <form method="post" action="/quizVerwaltung/insertQuestions.php"  enctype="multipart/form-data" class="inputFileUploadForm">
                    <input class="inputfile" type="file" name="inputFileData" onchange="setFilename(this)">
                    <button type="submit" name="import" class="btn btn-success"><?php echo $text_import_form["import_btn"]?></button>
                </form>
            </div>
        </div>


        <hr>

        <h1 class="uploadMainHeading">Create questions with our form</h1>
        <div class="container inputWithFileWrapper">
            <div class="mb-3">
                <span>Which question Type would you like to create?</span><br>
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
                
            </form>
        </div>
    </div>
    <script src="/quizVerwaltung/scripts/questionScripts.js"></script>
    <script>

        function createLabelAndTextarea(createdField, fieldLabelText){

            let div = document.createElement("div");
            div.setAttribute("class", "mb-3");

            let questionLabel = document.createElement("label");
            questionLabel.setAttribute("for", createdField);
            questionLabel.setAttribute("class", "form-label");
            questionLabel.innerHTML = fieldLabelText;
            
            let questionInput = document.createElement("textarea");
            questionInput.setAttribute("id", createdField);
            questionInput.setAttribute("name", createdField);
            questionInput.setAttribute("class", "form-control");

            div.append(questionLabel);
            div.append(questionInput);
            return div;
        }


        function createLabelAndSelect(createdField, fieldLabelText, selectOptions){

            let div = document.createElement("div");
            div.setAttribute("class", "mb-3");

            let questionLabel = document.createElement("label");
            questionLabel.setAttribute("for", createdField);
            questionLabel.setAttribute("class", "form-label");
            questionLabel.innerHTML = fieldLabelText;

            let select = document.createElement("select");
            select.setAttribute("id", createdField);
            select.setAttribute("name", createdField);
            select.setAttribute("class", "form-control");

            for(let i=0;i<selectOptions.length;i++){
                let option = document.createElement("option");
                option.setAttribute("value", selectOptions[i]);
                option.innerHTML = selectOptions[i];
                select.append(option);
            }

            div.append(questionLabel);
            div.append(select);
            return div;
        }

        function createhiddenInput(name, value){
            let input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", name);
            input.setAttribute("value", value);
            return input;
        }


        function loadQuestionContent(e){
            console.log(e.id);
            var questionForm = document.getElementById("createQuestionForm");

            questionForm.innerHTML = "";
            //needed for all questions...
            let div = createLabelAndTextarea("questionText", "<?php echo $upload_creatQuestionText;?>");
            questionForm.append(div);
            let divAnswer;
            let questionType;
            switch(e.id) {
                case "yesNoOption":
                    divAnswer = createLabelAndSelect("questionAnswer", "<?php echo $upload_creatQuestionAnswer;?>", ["True","False"]);
                    questionType = createhiddenInput("questionType", "YesNo");
                    questionForm.append(questionType);
                    questionForm.append(divAnswer);
                    break;
                case "openOption":
                    divAnswer = createLabelAndTextarea("questionAnswer", "<?php echo $upload_creatQuestionAnswer;?>");
                    questionType = createhiddenInput("questionType", "Open");
                    questionForm.append(questionType);
                    questionForm.append(divAnswer);
                    break;
                case "optionsOption":
                    // code block
                    break;
                case "multiOptionsOption":
                    // code block
                    break;
            }

            let method = createhiddenInput("method", "createQuestionWithForm");
            questionForm.append(method);

            let submitBtn = document.createElement("button");
            submitBtn.setAttribute("type", "submit");
            submitBtn.setAttribute("class", "btn btn-success");
            submitBtn.innerHTML = "<?php echo $text_import_form["import_btn"]?>";
            questionForm.append(submitBtn);
        }
    </script>
</body>
</html>