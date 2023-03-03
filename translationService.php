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
        $result = $translator->translateText($inputText, null, $this->targetLanguage, ['tag_handling' => 'html']);
        return $result->text;
    }

    function detectLanguage($inputText){
        $translator = new \DeepL\Translator($this->authKey);
        $result = $translator->translateText($inputText, null, $this->targetLanguage);
        return $result->detectedSourceLang;
    }

    function getAllTargetLanguageCodes(){
        $translator = new \DeepL\Translator($this->authKey);
        $targetLanguages = $translator->getTargetLanguages();

        $allLangCodes = [];
        foreach($targetLanguages as $language){
            array_push($allLangCodes,strtolower($language->code));
        }
        return $allLangCodes;
    }

    function getAllTargetLanguages(){
        $translator = new \DeepL\Translator($this->authKey);
        $targetLanguages = $translator->getTargetLanguages();
        return $targetLanguages;
    }

    function translateObject($inputObject,$sourceLanguage){
        for($i=0;$i<count($inputObject);$i++){
            $questionValues = get_object_vars($inputObject[$i]);
            foreach($questionValues as $key => &$value){
                if($key == "question"){
                    #translating the questions here
                    $inputObject[$i]->$key->$sourceLanguage = $this->translateText($inputObject[$i]->$key->$sourceLanguage); 
                }elseif($key == "options"){
                    #translating arrays (options) here
                    for($x=0;$x<count($inputObject[$i]->options->$sourceLanguage->jsonSerialize());$x++){
                       $inputObject[$i]->options->$sourceLanguage[$x] = $this->translateText($inputObject[$i]->options->$sourceLanguage[$x]); 
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
