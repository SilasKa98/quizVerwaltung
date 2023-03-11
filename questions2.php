<?php

class Question {
  public $question;
  public $answer;


  public function __construct($question, $answer, $questionType, $version, $id, $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount) {
    $this->question =  $question;
    $this->answer  = $answer;
    $this->id = $id;
    $this->questionType = $questionType;
    $this->creationDate = $creationDate;
    $this->modificationDate = $modificationDate;
    $this->version = $version;
    $this->tags = $tags;
    $this->karma = $karma;
    $this->author = $author;
    $this->verification = $verification;
    $this->downloadCount = $downloadCount;
  }

}

class YesNoQuestion extends Question{

}

class OpenQuestion extends Question{

}

class RegOpenQuestion extends Question{

}

class CorrectQuestion extends Question{

}

class MultiLineQuestion extends Question{
 
}


class OptionsQuestion extends Question{
  public $options;

  public function __construct($question, $answer, $options, $questionType, $version, $id, $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount) {
    $this->question = $question;
    $this->answer  = $answer;
    $this->options = $options;
    $this->id = $id;
    $this->questionType = $questionType;
    $this->creationDate = $creationDate;
    $this->modificationDate = $modificationDate;
    $this->version = $version;
    $this->tags = $tags;
    $this->karma = $karma;
    $this->author = $author;
    $this->verification = $verification;
    $this->downloadCount = $downloadCount;
  }
}

class MultiOptionsQuestion extends OptionsQuestion{
  
}

class OrderQuestion extends Question{
  public $options;

  public function __construct($question, $answer, $options, $questionType, $version, $id, $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount) {
    $this->question = $question;
    $this->answer  = $answer;
    $this->options = $options;
    $this->id = $id;
    $this->questionType = $questionType;
    $this->creationDate = $creationDate;
    $this->modificationDate = $modificationDate;
    $this->version = $version;
    $this->tags = $tags;
    $this->karma = $karma;
    $this->author = $author;
    $this->verification = $verification;
    $this->downloadCount = $downloadCount;
  }
}


function parseLine( $line ) {
	$parts = preg_split( "/#/", $line );
	$parts = str_replace( "&num;", "#", $parts );
  $questionType = $parts[0];


  //language is automatically detected with the deepL Api
  include_once "services/translationService.php";
  $deepLDetectLanguage = new TranslationService("de");
  $language = $deepLDetectLanguage->detectLanguage($parts[1]);

  if($language == "en"){
    $language = "en-us";
  }

  
  //check the inserted questions for illegal stuff and escape chars that need to be escaped
  $parts[1] = str_replace("<span>", "<br><span class='displayCodeInQuestion' translate=\"no\">", $parts[1]);
  $parts[1] = str_replace("\"", "'", $parts[1]);
  if( $parts[0] == "YesNo" || $parts[0] == "RegOpen" || $parts[0] == "Open" &&  $parts[0] == "Correct") {
    if(count($parts) != 3){
      header("LOCATION: frontend/frontend_insertQuestion.php?error=illegalQuestionFormat");
      exit();
    }
  }

  if( $parts[0] == "Order" || $parts[0] == "Options" || $parts[0] == "MultiOptions") {
    $optionsCount = count(array_slice($parts,3));
    if(count($parts) != 3+$optionsCount){
      header("LOCATION: frontend/frontend_insertQuestion.php?error=illegalQuestionFormat");
      exit();
    }
  }


  $karma = 0;

  include_once "services/versionService.php";
  $version = new VersionService();
  $version->setVersion("1.0");
  $version = $version->version;

  $author = $_SESSION["userData"]["username"];
  $tags = "";

  $creationDate = date("Y-m-d");
  $modificationDate = date("Y-m-d");

  $verification = "not verified";
  $downloadCount = 0;
	if( $parts[0] == "YesNo" ) {
		return new YesNoQuestion( [$language=>$parts[1]], $parts[2], $questionType, $version, uniqid(), $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount);
	} else if( $parts[0] == "RegOpen" ) {
		return new RegOpenQuestion( [$language=>$parts[1]], $parts[2], $questionType, $version, uniqid(), $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount);
	} else if( $parts[0] == "Open" ) {
		return new OpenQuestion( [$language=>$parts[1]], $parts[2], $questionType, $version, uniqid(), $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount);
	} else if( $parts[0] == "Correct" ) {
		return new CorrectQuestion( [$language=>$parts[1]], $parts[2], $questionType, $version, uniqid(), $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount);
	} else if( $parts[0] == "Order" ) {
		return new OrderQuestion( [$language=>$parts[1]], $parts[2], [$language=>array_slice($parts,3)], $questionType, $version, uniqid(), $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount);
	} else if( $parts[0] == "Options" ) {
		return new OptionsQuestion( [$language=>$parts[1]], $parts[2], [$language=>array_slice($parts,3)], $questionType, $version, uniqid(), $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount);
	} else if( $parts[0] == "MultiOptions" ) {
		return new MultiOptionsQuestion( [$language=>$parts[1]], $parts[2], [$language=>array_slice($parts,3)], $questionType, $version, uniqid(), $karma, $author, $tags, $creationDate, $modificationDate, $verification, $downloadCount);
	} else if( $parts[0] == "Dyn" ) {
		$func = $parts[1];
		if( method_exists( "Dyn", $func ) ) {
			return Dyn::{$func}();
		} else {
			return new Question( "-", "", "", "", "", "", "", "", "", "", "", "");
		}
	} else {
		return new Question( "-", "", "", "", "", "", "", "", "", "", "", "");
	}
}


?>