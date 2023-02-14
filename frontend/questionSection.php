
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
<script>
/*

    //check if the user presses "save" in the insert new Language modal
    function submitNewLanguageInsert(){

        subCheck =  new Promise(function (resolve, reject) {
            var submitNewLang = document.getElementById("submitNewLanguageInsertBtn");
            submitNewLang.addEventListener('click', (event) => {
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


    //get what language the user wants to translate the question into.. 
    //this function is also asyncrounus so it will wait until the user submits the selection with the save button --> function submitNewLanguageInsert()
    async function getNewQuestionLanguage(){

        p =  new Promise(function (resolve, reject) {
            var newLang = document.getElementById("insertNewLanguageDrpDwn");
            newLang.addEventListener('change', (event) => {
                var result = event;
                if(result != ""){
                    resolve(result);
                }
                else{
                    reject("error ...")
                } 
            });
        })
        let checkFinalSubmit = await submitNewLanguageInsert();
        return p;

    }


    //this function awaits a return from getNewQuestionLanguage(), the getNewQuestionLanguage() only returns if the user submitted his selection.
    //with this structure its secured that the user can change the target language as often as he wants, till he presses submit
    async function insertNewLanguage(e){

        let newLang = await getNewQuestionLanguage();
        let selLanguage = newLang.target.value;
        let id = e.getAttribute("name").split("_")[0];
        let sourceLanguage = e.getAttribute("name").split("_")[1];
        let method = "insertNewLanguage";

        $.ajax({
            type: "POST",
            url: 'doTransaction.php',
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
                toastMsgBody.innerHTML = "New Language added successfully!";
                $(".toast").toast('show');
                
            }
        });

    }


    function changeKarma(e){

        var job = e.name;
        let id = e.id;
        let method = "changeKarma";
        $.ajax({
            type: "POST",
            url: 'doTransaction.php',
            data: {
                job: job,
                method: method,
                id: id
            },
            success: function(response) {
                console.log(response);
                //instantly shows the changes to the karma without reload
                let karmaId = document.getElementById("karma_"+id);  
                if(job == "increaseKarma"){
                    var otherBtn = e.nextElementSibling;
                }else{
                    var otherBtn = e.previousElementSibling;
                }
                otherBtn.style.background = "none";
                if(e.style.background == "rgb(5, 125, 238)"){
                    e.style.background = "none"; 
                }else{
                    e.style.background = "rgb(5, 125, 238)"; 
                }
                
                
                karmaId.innerHTML = response;
                console.log("karma change successfull");
            }
        });

    }

    
    function changeQuestionLanguage(e){

        let questionId = e.getAttribute("name").split("_")[1];
        let questionLang = e.value;
        const method = "changeQuestionLanguageRelation";
        $.ajax({
            type: "POST",
            url: 'doTransaction.php',
            data: {
                questionId: questionId,
                questionLang: questionLang,
                method: method
            },
            success: function(response) {
                //adjust displayed values --> currently only question Titel, others need to be added too (TODO)
                let questionText = document.getElementById("headerText_"+questionId);
                questionText.innerHTML = response;

                console.log("language change successfull");
                toastMsgBody.innerHTML = "Changed the question language successfully!";
                $(".toast").toast('show');
            }
        });

    }
*/

</script>