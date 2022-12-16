<?php


##################################################################
################Question auslesen und darstellung#################
##################################################################
$question = new QuestionService();
$mongoRead = new MongoDBService();
#$filterQuery = ['id' => '639baa04deacd'];
$filterQuery = [];
$options = [];
$mongoData = $mongoRead->read("questions", $filterQuery, $options);

$readForPrintObjects = [];
foreach ($mongoData as $doc) {
    $fetchedQuestion = $question -> parseReadInQuestion($doc);
    array_push($readForPrintObjects,$fetchedQuestion);
}


echo '<div id="questionWrapper">';

foreach ($readForPrintObjects as $doc) {
    $question->printQuestion($doc);
}

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