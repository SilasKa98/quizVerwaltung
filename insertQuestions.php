<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Question</title>

    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>

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
            
            #echo $inputFile;
            #print_r($questionObject[0]);
            echo '<div class="jumbotronWrapper">';
            $questionCounter=0;
            foreach ($questionObject as $qObj){
                $questionCounter++;
                echo '<div class="jumbotronObjectWrapper">';
                echo '<h1 class="questionHeading">Frage '.$questionCounter.'</h1>';
                foreach ($qObj as $key => $value) {
                    if(is_array($value)){
                        foreach ($value as $innerKey => $innerValue) {
                            if(!is_array($innerValue)){
                                echo '
                                <div class="jumbotron">
                                    <div class="container">
                                        <h1>Sprache</h1>
                                        <p>'.$innerKey.'</p>
                                        <p><a class="btn btn-primary btn-lg" href="#" role="button">Anpassen</a></p>
                                    </div>
                                </div>
                                ';
                                echo '
                                <div class="jumbotron">
                                    <div class="container">
                                        <h1>Frage</h1>
                                        <p>'.$innerValue.'</p>
                                        <p><a class="btn btn-primary btn-lg" href="#" role="button">Anpassen</a></p>
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
                        <div class="jumbotron">
                            <div class="container">
                                <h1>'.$key.'</h1>
                                <p>'.$value.'</p>
                                <p><a class="btn btn-primary btn-lg" href="#" role="button">Anpassen</a></p>
                            </div>
                        </div> 
                        ';
                    } 
                }
                echo '</div>';
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
        //header("LOCATION:index.php?insert=success");

        ?>
    </body>
</html>