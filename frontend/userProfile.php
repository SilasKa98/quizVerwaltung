<?php
    session_start();
    if(!$_SESSION["logged_in"]){
        header("Location: frontend/loginAccount.php");
        exit();
    }
    extract($_SESSION["userData"]);

    //get the selected userLanguage to display the system in the right language
    include_once "../mongoService.php";
    include_once "../questionService.php";
    include_once "../printService.php";


    $mongo = new MongoDBService();
    $question = new QuestionService();
    $printer = new Printer();

    $filterQuery = (['userId' => $userId]);
    $selectedLanguage= $mongo->findSingle("accounts",$filterQuery,[]);
    $selectedLanguage = $selectedLanguage->userLanguage;
    include "../systemLanguages/text_".$selectedLanguage.".php";


    $visitedUserprofile = $_GET["profileUsername"];
    $filterQueryUserprofile = (['username' => $visitedUserprofile]);
    $foundProfile= $mongo->findSingle("accounts",$filterQueryUserprofile,[]);


    $filterQuery = ['author' => $foundProfile->username];
    $options = [];
    $usersQuestions = $mongo->read("questions", $filterQuery, $options);

    $readyForPrintQuestions = [];
    foreach ($usersQuestions as $doc) {
        $fetchedQuestion = $question->parseReadInQuestion($doc);
        array_push($readyForPrintQuestions,$fetchedQuestion);
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $foundProfile->username;?></title>

    <link rel="stylesheet" href="../stylesheets/general.css">
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>
<body>
    <div class="modal fade" id="changeLangModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"><?php echo $InsertNewLanguageMainHeader; ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <?php echo $InsertNewLanguageTitel; ?><br>
                <select class="selLanguageDropDown" id="insertNewLanguageDrpDwn" name="language">
                    <option></option>
                    <option>DE</option>
                    <option>en-Us</option>
                    <option>es</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="submitNewLanguageInsertBtn"  data-bs-dismiss="modal" class="btn btn-primary">Save</button>
            </div>
            </div>
        </div>
    </div>




    <section style="background-color: #eee;">
        <div class="container py-5">
            <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="../media/defaultAvatar.png" alt="avatar"
                    class="rounded-circle img-fluid" style="width: 150px;">
                    <h5 class="my-3"><?php echo $foundProfile->username;?></h5>
                    <p class="text-muted mb-1">Full Stack Developer</p>
                    <p class="text-muted mb-4">Bay Area, San Francisco, CA</p>
                    <div class="d-flex justify-content-center mb-2">
                    <button type="button" class="btn btn-primary">Follow</button>
                    <button type="button" class="btn btn-outline-primary ms-1">Message</button>
                    </div>
                </div>
                </div>
                <div class="card mb-4 mb-lg-0">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush rounded-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <img src="#" >
                        <p class="mb-0">Karma</p>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <img src="#" >
                        <p class="mb-0">Foo</p>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <img src="#" >
                        <p class="mb-0">Bar</p>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <img src="#" >  
                        <p class="mb-0">Buz</p>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <img src="#" >
                        <p class="mb-0">Tst</p>
                    </li>
                    </ul>
                </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">Full Name</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0">Maybe take full name @register</p>
                    </div>
                    </div>
                    <hr>
                    <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">Email</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $foundProfile->mail; ?></p>
                    </div>
                    </div>
                    <hr>
                    <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">other field</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0"></p>
                    </div>
                    </div>
                    <hr>
                    <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">other field</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0"></p>
                    </div>
                    </div>
                    <hr>
                    <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">other field</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0"></p>
                    </div>
                    </div>
                </div>
                </div>

                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <?php
                             foreach ($readyForPrintQuestions as $doc) {
                                $printer->printQuestion($doc);
                            }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
            </div>
        </div>
    </section>

<!--notification Toast to show all sorts of notifications, can be called with this: $(".toast").toast('show');-->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <img src="/quizVerwaltung/media/logo.jpg" width="20px" class="rounded me-2" alt="our logo">
        <strong class="me-auto">Quiz-Verwaltung</strong>
        <small><?php echo $toastTimeDisplay; ?></small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body" id="toastMsgBody">
       
      </div>
    </div>
  </div>

    <script src="/quizVerwaltung/scripts/questionScripts.js"></script>
</body>
</html>