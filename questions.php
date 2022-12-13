<?php
class Question {
  public $question;
  public $answer;

  public function __construct($question, $answer) {
    $this->question = $question;
    $this->answer  = $answer;
  }

  public function print() {
	return $this->question;
 }
  public function getQuestionInfo() {
	return $this->question;
 }
}

class YesNoQuestion extends Question{

  public function print() {
	return $this->question . getYesNoButton( );
 }
}

class OpenQuestion extends Question{

  public function print() {
	return $this->question . getOpenField( );
 }
}

class RegOpenQuestion extends Question{

  public function print() {
	return $this->question . getRegOpenField( );
 }
}

class CorrectQuestion extends Question{

  public function print() {
	return "Bitte korrigieren" . getCorrectField( $this->question  );
 }
}

class MultiLineQuestion extends Question{
  public function print() {
	return getPre( $this->question ) . getOpenField( );
 }
  public function getQuestionInfo() {
	return html_entity_decode( preg_replace('/\s/', '', $this->question) );
 }
}

class OptionsQuestion extends Question{
  public $options;

  public function __construct($question, $answer, $options) {
    $this->question = $question;
    $this->answer  = $answer;
    $this->options = $options;
  }

  public function print() {
	return $this->question . getOptionsSelector( $this->options );
 }
}

class MultiOptionsQuestion extends OptionsQuestion{
  public function print() {
	return $this->question . getMultiOptions( $this->options );
 }
}

class OrderQuestion extends Question{
  public $options;

  public function __construct($question, $answer, $options) {
    $this->question = $question;
    $this->answer  = $answer;
    $this->options = $options;
  }

  public function print() {
	return $this->question . getOrderSelector( $this->options );
 }
}


function parseLine( $line ) {
	$parts = preg_split( "/#/", $line );
	$parts = str_replace( "&num;", "#", $parts );
	if( $parts[0] == "YesNo" ) {
		return new YesNoQuestion( $parts[1], $parts[2] );
	} else if( $parts[0] == "RegOpen" ) {
		return new RegOpenQuestion( $parts[1], $parts[2] );
	} else if( $parts[0] == "Open" ) {
		return new OpenQuestion( $parts[1], $parts[2] );
	} else if( $parts[0] == "Correct" ) {
		return new CorrectQuestion( $parts[1], $parts[2] );
	} else if( $parts[0] == "Order" ) {
		return new OrderQuestion( $parts[1], $parts[2], array_slice($parts,3) );
	} else if( $parts[0] == "Options" ) {
		return new OptionsQuestion( $parts[1], $parts[2], array_slice($parts,3) );
	} else if( $parts[0] == "MultiOptions" ) {
		return new MultiOptionsQuestion( $parts[1], $parts[2], array_slice($parts,3) );
	} else if( $parts[0] == "Dyn" ) {
		$func = $parts[1];
		if( method_exists( "Dyn", $func ) ) {
			return Dyn::{$func}();
		} else {
			return new Question( "-", "" );
		}
	} else {
		return new Question( "-", "" );
	}
}


?>