<?php include_once "modal_insertNewQuestionLang.php";?>

<?php

##################################################################
################Question auslesen und darstellung#################
##################################################################
include_once "./services/questionService.php";
include_once "./services/mongoService.php";
include_once "./services/printService.php";
include_once "./services/translationService.php";

$question = new QuestionService();
$printer = new Printer();
$mongoRead = new MongoDBService();

//implementation of the users Favorit tags filter system.
//dynamically building the $filterQueryQuestionPrint filter with the favoritTags 
$searchUserFavTagsFilter = (['userId'=>$_SESSION["userData"]["userId"]]);
$searchUserFavTags = $mongoRead->findSingle("accounts",$searchUserFavTagsFilter);
$userFavTags = (array)$searchUserFavTags->favoritTags;

if(isset($_GET["questionPage"])){
    $currentSelectedPage = $_GET["questionPage"];
}else{
    //when get is missing set currentSelectedPage to 1;
    $currentSelectedPage = 1;
}

$questionsPerPage = 15;

$questionSelectionLimit = $currentSelectedPage * 15;
$questionSelectionSkip = ($currentSelectedPage - 1) * 15;

#echo "limit: ".$questionSelectionLimit."<br>";
#echo "skip: ".$questionSelectionSkip."<br>";
//limit the max shown questions on the lading page and also sort it by creationDate to get new ones
$favTagsOptions = ['sort' => [ 'creationDate' => -1 ,'karma' => -1], 'limit'=> $questionSelectionLimit, 'skip'=>$questionSelectionSkip];

if(!empty($userFavTags)){
    $filterQueryQuestionPrint = ['tags' => ['$in' => $userFavTags]];
}else{
    $filterQueryQuestionPrint = [];
}
$mongoData = $mongoRead->read("questions", $filterQueryQuestionPrint, $favTagsOptions);

$allQuestionCount= count($mongoRead->read("questions", $filterQueryQuestionPrint));
$pageNumber = round($allQuestionCount/$questionsPerPage);

$readyForPrintObjects = [];
foreach ($mongoData as $doc) {
    $fetchedQuestion = $question->parseReadInQuestion($doc);
    array_push($readyForPrintObjects,$fetchedQuestion);
}


echo '<div class="container-fluid" id="allQuestionWrapper">';
    foreach ($readyForPrintObjects as $doc) {
        $printer->printQuestion($doc);
    }


#echo $allQuestionCount;
?>
    <div class="d-flex justify-content-center">
        <form method="GET" action="/quizVerwaltung/index.php" id="questionPageSubmitForm">
            <input type="hidden" name="questionPage" id="questionPageInput">
        </form>
        <nav aria-label="...">
        <ul class="pagination">
            <?php
                if($pageNumber >= 10){
                    $pageNumberMax = 10;
                }
            ?>
            <?php for($i=1;$i<=$pageNumber;$i++){?>
                    <?php if($i <= 10){?>
                        <li class="page-item <?php if($currentSelectedPage == $i){ echo "active"; } ?>">
                            <a class="page-link" style="cursor:pointer" id="<?php echo $i; ?>" onclick="setQuestionPage(this)"><?php echo $i;?></a>
                        </li>
                    <?php }else{?>
                        <li class="page-item <?php if($currentSelectedPage == $i){ echo "active"; } ?>">
                            <a class="page-link" style="cursor:pointer" id="<?php echo $i; ?>" onclick="setQuestionPage(this)">...</a>
                        </li>
                    <?php
                            break;
                        }
                    ?>
            <?php }?>
        </ul>
        </nav>
    </div>
            </div>
    <script>
    function setQuestionPage(e){
       let questionPageInput = document.getElementById("questionPageInput");
       questionPageInput.value = e.id;

       let questionPageSubmitForm = document.getElementById("questionPageSubmitForm");
       questionPageSubmitForm.submit();
    }
</script>
<script src="/quizVerwaltung/scripts/questionScripts.js"></script>
