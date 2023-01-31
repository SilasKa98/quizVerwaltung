<?php
  session_start();
  if(!$_SESSION["logged_in"]){
    header("Location: frontend/loginAccount.php");
    exit();
  }
  extract($_SESSION["userData"]);

  //get the selected userLanguage to display the system in the right language
  include_once "mongoService.php";
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

    <style>

    .jumbotron{
        width: 29%;
        overflow: hidden;
        float: left;
        margin: 10px 10px 10px 10px;
        height: 300px;
    }

    .jumbotron h1{
        font-size: 46px;
    }

    .jumbotronWrapper{
        margin-left: auto;
        margin-right: auto;
        max-width: 1440px;
        font-family: sans-serif;
        overflow: auto;
    }

    .jumbotronObjectWrapper{
        overflow: auto;
        margin-bottom: 8%;
    }

    .questionHeading{

    }

    #finalImportBtn{
        margin: 10px;
        width: 99%;
    }

    .innerImportCard{
        margin-right:1%;
    }
    .allCardsWrapper{
        margin-top: 1%;
    }
    #importMainHeader{
        text-align: center;
        padding: 1%;
    }
    </style>
</head>
    <body>
        <?php
        include_once "questionService.php";
        include_once "mongoService.php";


        if (isset($_POST['import'])) {
            $inputFile = $_POST['inputFile'];
            $question = new QuestionService();
            $questionObject = $question->getQuestion($inputFile,"topics");
            
            echo '<div class="container-fluid">';
            echo '<h1 id="importMainHeader">'.$importCheckPageTitel.'</h1>';
            $questionCounter=0;
            foreach ($questionObject as $qObj){
                $questionCounter++;
                echo'
                <div class="card text-center allCardsWrapper">
                    <div class="card-header">
                        <h3 class="card-title">Frage '.$questionCounter.'</h3>
                    </div>
                ';
                echo '<div class="card-body">';
                echo '<div class="row row-cols-1 row-cols-md-2 g-1">';
                foreach ($qObj as $key => $value) {
                    if(is_array($value)){
                        foreach ($value as $innerKey => $innerValue) {
                            if(!is_array($innerValue)){
                                echo '
                                    <div class="card mb-3 innerImportCard" style="width: 18rem;">
                                        <div class="card-body">
                                            <h5 class="card-title">'.$innerKey.'</h5>
                                            <p class="card-text">'.$innerValue.'</p>
                                            <a href="#" class="btn btn-primary">Anpassen</a>
                                        </div>
                                    </div>
                                ';
                            }
                        }
                    }else{
                        if($value == null){
                            $value = "-";
                        }
                        echo '
                            <div class="card mb-3 innerImportCard" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">'.$key.'</h5>
                                    <p class="card-text">'.$value.'</p>
                                    <a href="#" class="btn btn-primary">Anpassen</a>
                                </div>
                            </div>
                            ';
                    } 
                }
                echo '
                        </div>
                    </div>
                </div>
                ';
            }
            echo '</div>';
            echo '<form action="insertQuestions.php" id="finalizeImport" method="POST">';
                echo '<input type="hidden" name="finalizeImport" value="finalizeImport">';
                echo '<input type="hidden" name="inputFile" value="'.$inputFile .'">';
                echo '<a href="javascript:$(\'#finalizeImport\').submit();" class="btn btn-primary btn-lg btn-block" role="button" id="finalImportBtn">Fragen Importieren</a>';
            echo '</form>';
        }
        if(isset($_POST['finalizeImport'])){
            //probably needs to be redone when changing/editing question objects is implemented...
            $inputFile = $_POST['inputFile'];
            $question = new QuestionService();
            $questionObject = $question->getQuestion($inputFile,"topics");


            $mongo = new MongoDBService();
            $mongo->insertMultiple("questions",$questionObject);

            header("LOCATION:index.php?insert=success");
        }
        if (isset($_POST['clean'])) {
            $mongo = new MongoDBService();
            $mongo->cleanCollection("questions");
        }
        ?>
    </body>
</html>