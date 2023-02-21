<?php
    class MoodleXMLParser{

        private $dom;
        private $catalogName;
        private $root;

        function __construct($catalogName){
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

            //Question Type separation
            switch($questionType) {
                case 'Options':
                    $questionType = 'multioptions';
                    $questionClass = new MultiChoiceQuestion($questionObject, $this->dom);
                    break;
                case 'MultiOptions':
                    $questionType = 'multioptions';
                    $questionClass = new MultiChoiceQuestion($questionObject, $this->dom);
                    break;
                case 'Open':
                    $questionType = 'shortanswer';
                    $questionClass = new OpenQuestion($questionObject, $this->dom);
                    break;
                case 'YesNo':
                    $questionType = 'truefalse';
                    $questionClass = new YesNoQuestion($questionObject, $this->dom);
                    break;
                default:
                    //TODO
            }
            /*
            Fragetypen die noch gemacht werden müssen => Order
            */

            $questionType = new DOMAttr('type', $questionType);
            $questionSection->setAttributeNode($questionType);

            $questionTextSection = $this->dom->createElement('questiontext');
            $questionText = $this->dom->createElement('text', $questionObject->question->de); //TODO sprachen auslesen aus db !!!!
            $questionTextSection->appendChild($questionText);
            $questionSection->appendChild($questionTextSection);

            $questionBodyFinished = $questionClass->getQuestionBodyAsDom($questionSection);
            $this->root->appendChild($questionBodyFinished);
        }

        function saveXML(){
            $this->dom->appendChild($this->root);
            $this->dom->save("catalogExports/".$this->catalogName.".xml");
        }
    }


    class Question {
        function __construct($questionObject, $dom){
            $this->questionObject = $questionObject;
            $this->dom = $dom;
        }
        
        function getQuestionBodyAsDom($questionSection){
            return $questionSection;
        }

        function createFeedback($fraction){
            $feedback = $this->dom->createElement('feedback');
            if ($fraction != '0') {
                $text = $this->dom->createElement('text', 'Correct!');
            }else {
                $text = $this->dom->createElement('text', 'Incorrect :(');
            }
            $feedback->appendChild($text);

            return $feedback;
        }
    }

    class MultiChoiceQuestion extends Question {
        function getQuestionBodyAsDom($questionSection){
            $answerArray = explode(',', ($this->questionObject->answer));
            $options = (array)$this->questionObject->options->de; //TODO hier müssen noch die anderen Sprachen angepasst werden !!!!
            var_dump($options);
            print("<hr>");
            $fraction;

            foreach ($options as $index => $option) {
                if (in_array($index, $answerArray)) {
                    $fraction = "100";
                }else{
                    $fraction = "0";
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
            $numbering = $this->dom->createElement('answernumbering', 'ABC');

            $questionSection->appendChild($shuffel);
            $questionSection->appendChild($single);
            $questionSection->appendChild($numbering);

            return $questionSection;
        }
    }

    class OpenQuestion extends Question {
        function getQuestionBodyAsDom($questionSection){
            return; //TODO angepasst an Fragetyp
        }
    }

    class YesNoQuestion extends Question {
        function getQuestionBodyAsDom($questionSection){
            $answer = $this->questionObject->answer;

            for ($i=0; $i < 2; $i++) { 
                if ($i == 0) {
                    $text = $this->dom->createElement('text', 'True');
                    if ($answer == 'true') {
                       $fraction = '100';
                    }else {
                        $fraction = '0';
                    }
                }else{
                    $text = $this->dom->createElement('text', 'False');
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
