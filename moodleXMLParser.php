<?php
    class MoodleXMLParser{

        private $dom;
        private $catalogName;

        function __construct($catalogName){
            $this->dom = new DOMDocument();
            $this->dom->encoding = 'utf-8';
            $this->dom->xmlVersion = '1.0';
            $this->dom->formatOutput = true;

            $this->catalogName = $catalogName;

            $this->dom->createElement('quiz');
        }

        function parseQuestionObject($questionType){
            //TODO je nach type wird dann eine andere XML classe verwendet bzw. geparsed
        }

        function saveXML(){
            $this->dom->saveXML("moodleXML/".$this->catalogName."xml");
        }
    }

    class MultiChoiceQuestion{

    }

?>
