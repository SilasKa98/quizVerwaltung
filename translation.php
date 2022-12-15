<?php
require __DIR__ . '/vendor/autoload.php';
use DeepL\DeepLException;
use DeepL\Translator;

//read in the .env file credentials
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
//use the credentials with $_ENV["xxxx"];
//check https://github.com/vlucas/phpdotenv

class Translator1{
    var $targetLanguage;

    function __construct($setTragetLanguage) {
        $this->targetLanguage = $setTragetLanguage;
        $this->authKey = $_ENV["apiKey"];
    }

    function translateText($inputText){
        $translator = new \DeepL\Translator($this->authKey);
        $result = $translator->translateText($inputText, null, $this->targetLanguage);
        return $result->text;
    }

    function translateObject($inputObject){
        for($i=0;$i<count($inputObject);$i++){
            $questionValues = get_object_vars($inputObject[$i]);
            foreach($questionValues as $key => &$value){
                if($key == "question"){
                    #translating the questions here
                    $inputObject[$i]->$key = $this->translateText($inputObject[$i]->$key); 
                }elseif($key == "options"){
                    #translating arrays (options) here
                    for($x=0;$x<count($inputObject[$i]->options);$x++){
                        $inputObject[$i]->options[$x] = $this->translateText($inputObject[$i]->options[$x]); 
                    }
                }else{
                    //all other fields, that are not handeled in the else if statement wont get translated
                   //do not translate these fields;
                }
            }
        }  
        return $inputObject;      
    }
}


//create Object for the class (take care of the 500k chars/month)
/*
$translation = new Translator1("en-Us");
$translation->translateText("Das ist ein Test");
*/
















/*
class Translator1{
    var $targetLanguage;
    var $inputText;
    var $inputObject;

    function __construct($setTragetLanguage,$setInputText,$setInputObject) {
        $this->targetLanguage = $setTragetLanguage;
        $this->inputText = $setInputText;
        $this->inputObject = $setInputObject;
        $this->authKey = $_ENV["apiKey"];
    }

    function translateText(){
        $translator = new \DeepL\Translator($this->authKey);
        $result = $translator->translateText($this->inputText, null, $this->targetLanguage);
        echo $result->text; //translated text
    }

    function translateObject(){
        for($i=0;$i<count($this->inputObject);$i++){
            $questionValues = get_object_vars($this->inputObject[$i]);
            foreach($questionValues as $key => &$value){
                print "[".$key."]";
                print_r($this->inputObject[$i]->$key);
                print "<br>";
                if(is_string($this->inputObject[$i]->$key)){
                    $this->inputObject[$i]->$key = "FOO"; 
                }else{
          
                } 
            }
            print "<br>";
        }        
    }
}




*/



?>
