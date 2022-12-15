<?php
class Question {
  public $question;
  public $answer;

  public function __construct($question, $answer, $questionType) {
    $this->question = $question;
    $this->answer  = $answer;
    $this->id = uniqid();
    $this->questionType = $questionType;
    $this->creationDate = date("Y-m-d");
    $this->modificationDate = "";
    $this->version = "";
    $this->tags = "";
    $this->karma = "";
    $this->author = "";
    $this->verification = "";
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

  public function __construct($question, $answer, $options, $questionType) {
    $this->question = $question;
    $this->answer  = $answer;
    $this->options = $options;
    $this->id = uniqid();
    $this->questionType = $questionType;
    $this->creationDate = date("Y-m-d");
    $this->modificationDate = "";
    $this->version = "";
    $this->tags = "";
    $this->karma = "";
    $this->author = "";
    $this->verification = "";
  }
}

class MultiOptionsQuestion extends OptionsQuestion{
  
}

class OrderQuestion extends Question{
  public $options;

  public function __construct($question, $answer, $options, $questionType) {
    $this->question = $question;
    $this->answer  = $answer;
    $this->options = $options;
    $this->id = uniqid();
    $this->questionType = $questionType;
    $this->creationDate = date("Y-m-d");
    $this->modificationDate = "";
    $this->version = "";
    $this->tags = "";
    $this->karma = "";
    $this->author = "";
    $this->verification = "";
  }
}


function parseLine( $line ) {
	$parts = preg_split( "/#/", $line );
	$parts = str_replace( "&num;", "#", $parts );
    $questionType = $parts[0];
	if( $parts[0] == "YesNo" ) {
		return new YesNoQuestion( $parts[1], $parts[2], $questionType);
	} else if( $parts[0] == "RegOpen" ) {
		return new RegOpenQuestion( $parts[1], $parts[2], $questionType);
	} else if( $parts[0] == "Open" ) {
		return new OpenQuestion( $parts[1], $parts[2], $questionType);
	} else if( $parts[0] == "Correct" ) {
		return new CorrectQuestion( $parts[1], $parts[2], $questionType);
	} else if( $parts[0] == "Order" ) {
		return new OrderQuestion( $parts[1], $parts[2], array_slice($parts,3), $questionType);
	} else if( $parts[0] == "Options" ) {
		return new OptionsQuestion( $parts[1], $parts[2], array_slice($parts,3), $questionType);
	} else if( $parts[0] == "MultiOptions" ) {
		return new MultiOptionsQuestion( $parts[1], $parts[2], array_slice($parts,3), $questionType);
	} else if( $parts[0] == "Dyn" ) {
		$func = $parts[1];
		if( method_exists( "Dyn", $func ) ) {
			return Dyn::{$func}();
		} else {
			return new Question( "-", "", "" );
		}
	} else {
		return new Question( "-", "", "" );
	}
}


?>