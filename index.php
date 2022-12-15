<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="general.css">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>

</head>
<body>
    
<form method="post">
  <input type="file" name="inputFile">
  <button type="submit" name="import">importieren</button>
</form>

<?php
include_once "questionService.php";
include_once "translationService.php";
include_once "mongoService.php";
include_once "questions2.php";


if (isset($_POST['import'])) {
    $inputFile = $_POST['inputFile'];
    echo $inputFile;
    $question = new QuestionService();
    $questionObject = $question->getQuestion($inputFile,"topics");
    /*
    echo "<pre>";
    print_r($questionObject);
    echo "</pre>";
    */
    #$serializedQuestion = $question->serializeQuestion($questionObject);
   # print "<pre>".$serializedQuestion."</pre>";
    #print gettype($serializedQuestion);
    $mongo = new MongoDBService();
    $mongo->insert("questions",$questionObject);
}



##################################################################
################Question auslesen und darstellung#################
##################################################################
$question = new QuestionService();
$mongoRead = new MongoDBService();
$mongoData = $mongoRead->read("questions", "639b260d1c6fc");
foreach ($mongoData as $doc) {
    $fetchedObject = $doc;
}
$fetchedQuestion = $question -> parseReadInQuestion($fetchedObject);


echo '<div id="questionWrapper">';
    $question->printQuestion($fetchedQuestion);

    /*
    ############################################
    #!!delete this comment to use translation!!#
    ############################################
    $translation = new TranslationService("en-Us");
    $objectTest = $translation->translateObject($fetchedQuestion);

    print "<br><br><br><br>";
    print "<h3>Translated: </h3>";
    print "<br>";

    $question->printQuestion($objectTest);
    */

echo '</div>';


?>


</body>
</html>