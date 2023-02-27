<?php
    include_once "accountService.php";

    class MoodleXMLParser{

        private $dom;
        private $catalogName;
        private $root;

        function __construct($catalogName){
            $this->account = new AccountService();
            $this->dom = new DOMDocument();
            $this->dom->encoding = 'utf-8';
            $this->dom->xmlVersion = '1.0';
            $this->dom->formatOutput = true;

            $this->catalogName = $catalogName;
            $this->root = $this->dom->createElement('quiz');
        }

        function parseQuestionObject($questionObject){
            $questionSection = $this->dom->createElement('question');
            $questionType = $questionObject->questionType;
            $questionId = $questionObject->id;

            //Question Type separation
            switch($questionType) {
                case 'Options':
                    $questionType = 'multichoice';
                    $questionClass = new ExportMultiChoiceQuestion($questionObject, $this->dom);
                    break;
                case 'MultiOptions':
                    $questionType = 'multichoice';
                    $questionClass = new ExportMultiChoiceQuestion($questionObject, $this->dom);
                    break;
                case 'Open':
                    $questionType = 'shortanswer';
                    $questionClass = new ExportOpenQuestion($questionObject, $this->dom);
                    break;
                case 'YesNo':
                    $questionType = 'truefalse';
                    $questionClass = new ExportYesNoQuestion($questionObject, $this->dom);
                    break;
                default:
                    //TODO
            }
            /*
            Fragetypen die noch gemacht werden müssen => Order
            */

            $questionType = new DOMAttr('type', $questionType);
            $questionSection->setAttributeNode($questionType);

            $nameSection = $this->dom->createElement('name');   //TODO ggf. kann man hier sogar noch Tags in als Namen verwenden // andererseits scheint moodle xml auch tags zu unterstützen
            $nameText = $this->dom->createElement('text', $this->catalogName);

            $questionTextSection = $this->dom->createElement('questiontext');
            $lang = $this->account->getUserQuestionLangRelation($_SESSION["userData"]["userId"], $questionId);
            $questionText = $this->dom->createElement('text', $questionObject->question->$lang); //TODO sprachen auslesen aus db !!!!
            
            $nameSection->appendChild($nameText);
            $questionSection->appendChild($nameSection);
            
            $questionTextSection->appendChild($questionText);
            $questionSection->appendChild($questionTextSection);

            $questionBodyFinished = $questionClass->getQuestionBodyAsDom($questionSection);
            $this->root->appendChild($questionBodyFinished);
        }

        function saveXML(){
            $this->dom->appendChild($this->root);
            $this->dom->save("catalogExports/".$this->catalogName."_".$_SESSION["userData"]["userId"].".xml");

            $filename = $this->catalogName."_".$_SESSION["userData"]["userId"].".xml";
            $filenamePrint = $this->catalogName.".xml";
            $file = "catalogExports/".$filename;

            header('Content-type: application/octet-stream');
            header("Content-Type: ".mime_content_type($file));
            header("Content-Disposition: attachment; filename=".$filenamePrint);
            readfile($file);

            unlink($file);      
        }
    }


    class ExportQuestion {
        function __construct($questionObject, $dom){
            $this->questionObject = $questionObject;
            $this->dom = $dom;
            $this->account = new AccountService();
        }
        
        function getQuestionBodyAsDom($questionSection){
            return $questionSection;
        }

        function createFeedback($fraction){
            $feedback = $this->dom->createElement('feedback');
            if ($fraction != '0') {
                //$feedback = $this->dom->createElement('correctfeedback');
                $text = $this->dom->createElement('text', 'Correct!');
            }else {
                //$feedback = $this->dom->createElement('incorrectfeedback');
                $text = $this->dom->createElement('text', 'Incorrect :(');
            }
            $feedback->appendChild($text);

            return $feedback;
        }
    }

    class ExportMultiChoiceQuestion extends ExportQuestion {
        function getQuestionBodyAsDom($questionSection){
            $answerArray = explode(',', ($this->questionObject->answer));
            $lang = $this->account->getUserQuestionLangRelation($_SESSION["userData"]["userId"], $this->questionObject->id);
            $options = (array)$this->questionObject->options->$lang; //TODO hier müssen noch die anderen Sprachen angepasst werden !!!!

            $fraction;

            foreach ($options as $index => $option) {
                $fractionAmount = (string)(100/count($answerArray));
                
                if (in_array($index, $answerArray)) {
                    $fraction = $fractionAmount;
                }else{
                    if (count($answerArray) > 1) {
                        $fraction = "-100"; 
                    }else{
                        $fraction = "0";
                    }
                }
                $answerSection = $this->dom->createElement('answer');
                $answerAttribute = new DOMAttr('fraction', $fraction);
                $answerSection->setAttributeNode($answerAttribute);
                //option Text
                $text = $this->dom->createElement('text', $option);
                $feedback = $this->createFeedback($fraction);

                $answerSection->appendChild($text);
                $answerSection->appendChild($feedback);
                $questionSection->appendChild($answerSection);
            }
            
            $shuffel = $this->dom->createElement('shuffleanswers', '1');
            if (count($answerArray) > 1) {
                $single = $this->dom->createElement('single', 'false');
            }else{
                $single = $this->dom->createElement('single', 'true');
            }
            $numbering = $this->dom->createElement('answernumbering', 'abc');

            $questionSection->appendChild($shuffel);
            $questionSection->appendChild($single);
            $questionSection->appendChild($numbering);

            return $questionSection;
        }
    }

    class ExportOpenQuestion extends ExportQuestion {
        function getQuestionBodyAsDom($questionSection){
            //TODO aktuell ausgeblendet da wir die antworden noch nicht übersetzen das kann noch gemacht werden
            //$lang = $this->account->getUserQuestionLangRelation($_SESSION["userData"]["userId"], $this->questionObject->id);
            $answerArray = explode(',', ($this->questionObject->answer));
            $fraction = "100";

            foreach ($answerArray as $answer) {
                $answerSection = $this->dom->createElement('answer');
                $answerAttribute = new DOMAttr('fraction', $fraction);
                $answerSection->setAttributeNode($answerAttribute);
                $feedback = $this->createFeedback($fraction);
                //answerText
                $text = $this->dom->createElement('text', $answer);

                $answerSection->appendChild($text);
                $answerSection->appendChild($feedback);
                $questionSection->appendChild($answerSection);
            }

            $usecase = $this->dom->createElement('usecase', '0');
            $questionSection->appendChild($usecase);

            return $questionSection;
        }
    }

    class ExportYesNoQuestion extends ExportQuestion {
        function getQuestionBodyAsDom($questionSection){
            $answer = $this->questionObject->answer;

            for ($i=0; $i < 2; $i++) { 
                if ($i == 0) {
                    $text = $this->dom->createElement('text', 'true');
                    if ($answer == 'true') {
                       $fraction = '100';
                    }else {
                        $fraction = '0';
                    }
                }else{
                    $text = $this->dom->createElement('text', 'false');
                    if ($answer == 'false') {
                        $fraction = '100';
                     }else {
                         $fraction = '0';
                     }
                }
                $answerSection = $this->dom->createElement('answer');
                $answerAttribute = new DOMAttr('fraction', $fraction);
                $answerSection->setAttributeNode($answerAttribute);
                $feedback = $this->createFeedback($fraction);

                $answerSection->appendChild($text);
                $answerSection->appendChild($feedback);

                $questionSection->appendChild($answerSection);
            }

            return $questionSection;
        }
    }
?>
