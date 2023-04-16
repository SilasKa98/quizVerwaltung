<?php
include_once "services/accountService.php";
//in this class all exports get handeld expect the moodle xml export, this is excluded in the moodleXMLParser.php because its more complexe...

// This class contains more than one parser
// JSON / LaTeX / Standard 
// Parser used here are rather simple and implementable with few lines of code
class ExportParser{
    
    function serializeQuestion($questionObject){
        //TODI iterate over the object and filter for the correct language.. currently all languages get exported
        return json_encode($questionObject, JSON_PRETTY_PRINT);
    }

    
    //this function only converts the questions with the answer options not the whole object. So it can be used in latex for askin questions directly (e.g. exams)
    function convertToLatex($obj, $userId, $exportName) {
      $account = new AccountService();
      
      $latex = "";

      $latex .= "\\documentclass{article}\n";
      $latex .= "\\title{".$exportName."}\n";
      $latex .= "\\begin{document}\n";
      $latex .= "\\maketitle\n\n";

      foreach((array)$obj as $value){
        $lang = $account->getUserQuestionLangRelation($userId, $value->id);
        $questionText = $value->question->$lang;
        $questionType = $value->questionType;

        $latex .= "\\section{".$questionText."}\n";
        $latex .= "\\begin{itemize}\n";

        if($questionType == "YesNo"){
          $latex .= "\\item[$\\bigcirc$] Yes\n";
          $latex .= "\\item[$\\bigcirc$] No\n";
        }
        elseif($questionType == "Options" || $questionType == "MultiOptions"){
          foreach($value->options->$lang as $optionsValue){
            $latex .= "\\item[$\\bigcirc$] ".$optionsValue."\n";
          }
        }
        elseif($questionType == "Open"){
          //$latex .= "\\item[$\\bigcirc$] ".$value->answer."\n";
          $latex .= "\\item[] \line(1,0){90}\n";
        }
        $latex .= "\\end{itemize}\n\n";
      }
      $latex .= "\\end{document}";
      return $latex;
    }


    //This converts the object to the simpqui format
    function convertToStandard($obj, $userId, $exportName){
      $account = new AccountService();

      $standard = "";
      foreach((array)$obj as $value){
        $lang = $account->getUserQuestionLangRelation($userId, $value->id);
        $questionText = $value->question->$lang;
        $questionType = $value->questionType;

        //here we add the deilimter # to nessecary spots
        $standard .= $questionType."#";
        $standard .= $questionText."#";
        //check for the question typen and then add type specific tokens
        if($questionType == "YesNo" || $questionType == "Open"){
          $standard .=  $value->answer."\n";
        }
        elseif($questionType == "Options" || $questionType == "MultiOptions"){
          $standard .=  $value->answer;
          foreach($value->options->$lang as $optionsValue){
            $standard .= "#".$optionsValue;
          }
          $standard .= "\n";
        }
      }
      return $standard;
    }

    //function to download the file from the platform, does not exactly correspond to a parser
    function downloadFile($objectToSave, $exportName, $userId, $fileEnding){
        $filename = 'catalogExports/'.$exportName.'_'.$userId.$fileEnding;
        $filenamePrint = $exportName.$fileEnding;
        file_put_contents($filename, $objectToSave);

        header('Content-type: application/octet-stream');
        header("Content-Type: ".mime_content_type($filename));
        header("Content-Disposition: attachment; filename=".$filenamePrint);
        readfile($filename);
        unlink($filename);      
    }

}

?>