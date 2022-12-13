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