<?php
require __DIR__ . '/vendor/autoload.php';
use DeepL\DeepLException;
use DeepL\Translator;

//read in the .env file credentials
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
//use the credentials with $_ENV["xxxx"];
//check https://github.com/vlucas/phpdotenv

class TranslationService{
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
$translation = new TranslationService("en-Us");
$translation->translateText("Das ist ein Test");
*/


?>
