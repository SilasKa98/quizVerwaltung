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

    
    ############################################
    #!!delete this comment to use translation!!#
    ############################################
    print "<br><br><br><br>";
    print "<h3>Translated: </h3>";
    print "<br>";
    $translation = new TranslationService("en-Us");
    foreach ($readForPrintObjects as $doc) {
        $objectTest = $translation->translateObject($doc);
        $question->printQuestion($objectTest);
    }

echo '</div>';




?>