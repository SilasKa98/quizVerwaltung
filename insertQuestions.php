<?php
  session_start();
  if(!$_SESSION["logged_in"]){
    header("Location: frontend/loginAccount.php");
    exit();
  }
  if(basename($_FILES["inputFileData"]["name"]) == ""){
    header("Location:frontend/frontend_insertQuestion.php");
    exit();
  }
  extract($_SESSION["userData"]);

  //get the selected userLanguage to display the system in the right language
  include_once "services/mongoService.php";
  $mongo = new MongoDBService();
  $filterQuery = (['userId' => $userId]);
  $selectedLanguage= $mongo->findSingle("accounts",$filterQuery,[]);
  $selectedLanguage = $selectedLanguage->userLanguage;
  include "systemLanguages/text_".$selectedLanguage.".php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Question</title>

    <link rel="stylesheet" href="stylesheets/importQuestionCheck.css">
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>
    <body onload="callReminder()">
    <div id="response"></div>

        <div class="modal fade" id="changeActionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"><?php echo $selectYourTagsHeader; ?></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ChangeModal-body">
                    <div class="btn-group" role="group" id="tags_holder" aria-label="Basic checkbox toggle button group">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitTagSelectionBtn"  data-bs-dismiss="modal" class="btn btn-primary">Save</button>
                </div>
                </div>
            </div>
        </div>


        <?php
        include_once "services/questionService.php";
        include_once "services/mongoService.php";

        if (isset($_POST['import'])) {

            //upload the file to the server so it can be imported
            $dotPosition = strpos(basename($_FILES["inputFileData"]["name"]), ".");
            $userSpecificFilename = substr_replace(basename($_FILES["inputFileData"]["name"]), "_".$userId, $dotPosition, 0);

            $target_dir = "topics/";
            $target_file = $target_dir . $userSpecificFilename;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            move_uploaded_file($_FILES["inputFileData"]["tmp_name"], $target_file);

            echo "<input type='hidden' value='".$userSpecificFilename."' id='inputFilename' readonly>";
            

            //get all available Tags
            $allTagsObj= $mongo->findSingle("tags",[],[]);
            $allTags = implode(",",(array)$allTagsObj->allTags);

            #$inputFile = $_POST['inputFile'];
            $inputFile = $userSpecificFilename;
            $question = new QuestionService();
            $questionObject = $question->getQuestion($inputFile,"topics");
            
            echo '<div class="container-fluid">';
            echo '<h1 id="importMainHeader">'.$importCheckPageTitel.'</h1>';
            $questionCounter=0;

            foreach ($questionObject as $qObj){
                $questionLanguage = array_key_first($qObj->question);
                echo '<input type="hidden" class="importInput" name="question" value="'.$qObj->question[$questionLanguage].'">';
                echo '<input type="hidden" class="importInput" name="language" value="'.$questionLanguage .'">';
                echo '<input type="hidden" class="importInput" name="answer" value="'.$qObj->answer .'">';
                echo '<input type="hidden" class="importInput" id="tags_'.$qObj->id.'" name="tags" value="">';
                echo '<input type="hidden" class="importInput" name="questionType" value="'.$qObj->questionType .'">';
                if(isset($qObj->options)){
                    $questionOptions = implode(",",$qObj->options[$questionLanguage]);
                    echo '<input type="hidden" class="importInput" name="options" value="'.$questionOptions.'">';
                }
                
                $questionCounter++;
                echo'
                <div class="card text-center allCardsWrapper">
                    <div class="card-header">
                        <h3 class="card-title">Frage '.$questionCounter.'</h3>
                    </div>
                ';
                echo '<div class="card-body">';
                echo '<div class="row row-cols-1 row-cols-md-2 g-1">';
                
                echo "<input type='hidden' value='".$qObj->id."'>";

                //unset unnessecary fields
                #unset($qObj->id);
                #unset($qObj->karma);
                foreach ($qObj as $key => $value) {
                    if(is_array($value)){
                        foreach ($value as $innerKey => $innerValue) {
                            if(!is_array($innerValue)){
                                echo '
                                    <div class="card mb-3 innerImportCard" style="width: 18rem;">
                                        <div class="card-body">
                                            <h5 class="card-title">'.$innerKey.'</h5>
                                            <p class="card-text">'.$innerValue.'</p>';
                                        echo'</div>
                                    </div>
                                ';
                                $currentQuestion = $innerValue;
                            }
                        }
                    }else{
                        if($value == null){
                            $value = "-";
                        }
                        //add all fields that shouldnt be displayed here
                        if($key != "id"){
                            echo '
                                <div class="card mb-3 innerImportCard" style="width: 18rem;">
                                    <div class="card-body">
                                        <h5 class="card-title">'.$key.'</h5>';
                                        if($key == "tags"){
                                            echo'<p class="card-text tagContext" id="tagsCard_'.$qObj->id.'">'.$value.'</p>';
                                            echo'<button onclick="changeTags(this,\''.$allTags.'\',tags_'.$qObj->id.',tagsCard_'.$qObj->id.')" class="btn btn-primary">'; echo $adjustButton; echo'</button>';
                                        }else{
                                            echo'<p class="card-text">'.$value.'</p>';
                                        }
                                    echo'</div>
                                </div>
                            ';
                        }
                    } 
                }
                echo '
                        </div>
                    </div>
                </div>
                ';
            }
            echo '</div>';

            echo'
            <div class="alert alert-danger" id="giveTagsReminderAlert" role="alert">
               '.$giveTagsReminderAlert.'
            </div>
            ';

            echo '<button class="btn btn-primary btn-lg btn-block" role="button" onclick="finalizeImport()" id="finalImportBtn">'; echo $finalizeImportButton; echo'</button>';
        }

        ?>


    <?php include_once "frontend/notificationToast.php";?>
    </body>


    <script>
        
        function callReminder(){
            setInterval(function () {
                let allTagHolders = document.querySelectorAll(".tagContext");
                let countEmptys = 0;

                for(let i=0;i<allTagHolders.length;i++){
                    if(allTagHolders[i].innerHTML == "-"){
                        countEmptys++;
                    }
                }

                if(countEmptys >0){
                    document.getElementById("giveTagsReminderAlert").style.display = "block";
                }else{
                    document.getElementById("giveTagsReminderAlert").style.display = "none";
                }

            }, 1000);
        }


        async function changeTags(e, allTags,tagsField, tagsCard){
            allTags = allTags.split(",");
            let tagsHolder = document.getElementById("tags_holder").innerHTML;
            //<input type="checkbox" class="btn-check" id="btncheck1" autocomplete="off">
            //<label class="btn btn-outline-primary" for="btncheck1">Checkbox 1</label>
            $('#tags_holder').html("");
            for(let i=0;i<allTags.length;i++){
                let inputElement = '<input type="checkbox" name="'+allTags[i]+'" class="btn-check" id="btncheck'+i+'" autocomplete="off">';
                let labelElement = '<label class="btn btn-outline-secondary tagsBtn" for="btncheck'+i+'">'+allTags[i]+'</label>';
                $('#tags_holder').append(inputElement);
                $('#tags_holder').append(labelElement);
            }

            $('#changeActionModal').modal('toggle');
            let tagSubmit = await submitTagSelection();

            //select the choosen tags when the save button is pressed and procceed with the steps to save them
            var selectedTags = [];
            let allBtnTags = document.querySelectorAll(".btn-check");
            for(let i=0;i<allBtnTags.length;i++){
                if(allBtnTags[i].checked){
                    let selectedTag = (allBtnTags[i].name).toString();
                    selectedTags.push(selectedTag);
                }
            }
            let questionId = e.parentNode.parentNode.parentNode.children[0].value;
            tagsField.value = selectedTags.toString();
            tagsCard.innerHTML =selectedTags.toString();

            if(selectedTags.length == 0){
                tagsCard.innerHTML = "-";
            }

        }   


        function submitTagSelection(){
            subCheck =  new Promise(function (resolve, reject) {
                var submitTagSel = document.getElementById("submitTagSelectionBtn");
                submitTagSel.addEventListener('click', (event) => {
                    if(event){
                        resolve(event);
                    }
                    else{
                        reject("error ...")
                    } 
                });
            })
            return subCheck;
        }


        function finalizeImport(){
            let inputFilename = document.getElementById("inputFilename").value;
            let importInput = document.querySelectorAll(".importInput");
            var allQuestions = {};
            var additionalCounter = 0;
            for(let i=0;i<importInput.length;i++){
                
                if(importInput[i].name == "question"){
                    allQuestions["question_"+additionalCounter] = {};
                    var currentQuestionIndex = additionalCounter;
                    additionalCounter++;
                }
                allQuestions["question_"+currentQuestionIndex][importInput[i].name]= importInput[i].value;
           } 


           let method = "finalizeImport";
            
           $.ajax({
                type: "POST",
                url: 'doTransaction.php',
                data: {
                    allQuestions: allQuestions,
                    method: method,
                    inputFilename: inputFilename
                },
                success: function(response) {
                    toastMsgBody.innerHTML = 'Import Successfull! <div style="float:right;"><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden"></span></div> loading..</div>';
                    $(".toast").toast('show');
                    setTimeout(function() {
                        location.href="index.php?insert=success";
                    }, 2500);      
                }
            });
        }

    </script>
</html>