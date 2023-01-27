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
    async function changeLanguage(e){
        console.log(e);
        console.log(e.value);
        let selLanguage = e.value;
        let buttonWrapper = e.nextElementSibling;
        let id = e.nextElementSibling.nextElementSibling.value;
        buttonWrapper.style.display = "inline";
        let method = "changeLanguage";
        let sourceLanguage = e.nextElementSibling.nextElementSibling.nextElementSibling.value;

        const saveOnly = e.nextElementSibling.children[0];
        const transAndSave = e.nextElementSibling.children[1];

        await buttonCheck(saveOnly,transAndSave,selLanguage,method,sourceLanguage,id);
    }

    function buttonCheck(saveOnly,transAndSave,selLanguage,method,sourceLanguage,id) {
        transAndSave.addEventListener("click", function() {
            $.ajax({
                type: "POST",
                url: 'changeObjectService.php',
                data: {
                    selLanguage: selLanguage,
                    method: method,
                    sourceLanguage: sourceLanguage,
                    id: id
                },
                success: function(response) {
                    $("#response").text(response);
                    console.log(response);
                    console.log("save successfull");
                }
            });
        });

        saveOnly.addEventListener("click", function() {
            console.log('saveOnly not implemented yet');
        });
    }

    function changeKarma(e){
        let job = e.name;
        let id = e.id;
        let method = "changeKarma";
        $.ajax({
                type: "POST",
                url: 'changeObjectService.php',
                data: {
                    job: job,
                    method: method,
                    id: id
                },
                success: function(response) {
                    console.log(response);
                    //instantly shows the changes to the karma without reload
                    let karmaId = document.getElementById("karma_"+id);
                    karmaId.innerHTML = response;
                    console.log("karma change successfull");
                }
            });


    }

</script>