<?php include_once "modal_insertNewQuestionLang.php";?>

<?php

##################################################################
################Question auslesen und darstellung#################
##################################################################
include_once "./questionService.php";
include_once "./mongoService.php";
include_once "./printService.php";
include_once "./translationService.php";

$question = new QuestionService();
$printer = new Printer();
$mongoRead = new MongoDBService();

//implementation of the users Favorit tags filter system.
//dynamically building the $filterQueryQuestionPrint filter with the favoritTags 
$searchUserFavTagsFilter = (['userId'=>$_SESSION["userData"]["userId"]]);
$searchUserFavTags = $mongoRead->findSingle("accounts",$searchUserFavTagsFilter);
$userFavTags = (array)$searchUserFavTags->favoritTags;

//limit the max shown questions on the lading page and also sort it by creationDate to get new ones
$favTagsOptions = ['sort' => [ 'creationDate' => -1 ,'karma' => -1], 'limit'=> 15];

if(!empty($userFavTags)){
    $filterQueryQuestionPrint = ['tags' => ['$in' => $userFavTags]];
}else{
    $filterQueryQuestionPrint = [];
}
$mongoData = $mongoRead->read("questions", $filterQueryQuestionPrint, $favTagsOptions);


$readyForPrintObjects = [];
foreach ($mongoData as $doc) {
    $fetchedQuestion = $question->parseReadInQuestion($doc);
    array_push($readyForPrintObjects,$fetchedQuestion);
}


echo '<div class="container-fluid" id="allQuestionWrapper">';
    foreach ($readyForPrintObjects as $doc) {
        $printer->printQuestion($doc);
    }
echo '</div>';


?>
<script src="/quizVerwaltung/scripts/questionScripts.js"></script>
