<div id="response"></div>
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
#$filterQuery = ['id' => '639baa04deacd'];
$filterQuery = [];
$options = [];
$mongoData = $mongoRead->read("questions", $filterQuery, $options);



$readForPrintObjects = [];
foreach ($mongoData as $doc) {
    $fetchedQuestion = $question->parseReadInQuestion($doc);
    array_push($readForPrintObjects,$fetchedQuestion);
}

echo '<div id="questionWrapper">';
foreach ($readForPrintObjects as $doc) {
    //set desired language here for each object
    $lang = "de";
    $printer->printQuestion($doc,$lang);
}

echo '</div>';


?>

<script>

    function changeLanguage(e){
        console.log(e);
        console.log(e.value);
        let selLanguage = e.value;
        let buttonWrapper = e.nextElementSibling;
        let id = e.nextElementSibling.nextElementSibling.value;
        buttonWrapper.style.display = "inline";
        let method = "changeLanguage";
        let sourceLanguage = e.nextElementSibling.nextElementSibling.nextElementSibling.value;

        console.log(id);
        $.ajax({
            type: "POST",
            url: 'changeQuestionLanguage.php',
            data: {
				selLanguage: selLanguage,
                method: method,
                sourceLanguage: sourceLanguage,
                id: id
			},
			success: function(response) {
				$("#response").text(response);
			}
        });

    }
</script>