<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="general.css">
    <title>Document</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>

</head>
<body>
    
</body>
</html>

<?php
include_once "readQuestion.php";
include_once "translation.php";


$question = new ReadQuestion("Java","topics");
$questionObject = $question->getQuestion();

$question->printQuestion($questionObject);


$serializedQuestion = $question->serializeQuestion($questionObject);

print "<br><br><br>";
$question->addAttributesToObject($questionObject);

/*
echo "<pre>".$serializedQuestion."</pre>";
print "<br><br>";
print_r(json_decode($serializedQuestion));
for($i=0;$i<count(json_decode($serializedQuestion));$i++){
    print_r(json_decode($serializedQuestion)[$i]);
    print "<br><br>";
}

*/



/*
$translation = new Translator1("en-Us");
$objectTest = $translation->translateObject($questionObject);

print_r($objectTest);

for($i=0;$i<count($objectTest);$i++){
    print_r($objectTest[$i]);
    print "<br><br>";
}
*/


#print_r($serializedQuestion);


#print_r(unserialize($serializedQuestion));


/*
for($i=0;$i<count($questionObject);$i++){
  # print_r(get_object_vars($questionObject[$i]));
   #print "<br><br>";
   $questionValues = get_object_vars($questionObject[$i]);
   #print "<br>";
   #print_r($questionValues);
   foreach($questionValues as $key => &$value){
        print "[".$key."]";
        print_r($questionObject[$i]->$key);
        print "<br>";
        if(is_string($questionObject[$i]->$key)){
            $questionObject[$i]->$key = "FOO"; 
        }else{

        } 
   }
   print "<br>";
}

print_r($questionObject);

*/

?>