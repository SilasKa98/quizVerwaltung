<?php
##############Experiment file --> not in use.##############

include_once "questions.php";
$basePath = "topics";

//hier frage typ angeben
$set       = filter_var( "GUI", FILTER_SANITIZE_STRING);

function getQuestions( $set ) {
	global $basePath;
	$lines =  file("$basePath/$set.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	return array_map('parseLine', $lines);
}

$questionArray = getQuestions($set);

for($i=0;$i<count($questionArray);$i++){
   print_r($questionArray[$i]);
   print "<br><br>";
}

/*
include_once "questions.php";

$basePath = "topics";
$set       = filter_var( "GUI", FILTER_SANITIZE_STRING);
$topicName = "none";

if( isset( $_REQUEST['name'] ) ) {
	$setName = filter_var( $_REQUEST['name'], FILTER_SANITIZE_STRING);
	$topic   = filter_var( $_REQUEST['topic'], FILTER_SANITIZE_STRING);
	$set = getBuild( $topic, $setName );
} else {
	$setName = $set;
}

echo "<h3>";
if( isset( $_REQUEST['topic'] ) ) {
	$topic     = filter_var( $_REQUEST['topic'], FILTER_SANITIZE_STRING);
	$topicName = str_replace(".top","", $topic);
	echo "Thema <a href='index.php?inhalt=topic&topic=$topic'>$topicName</a>, ";
}
echo "Einheit <em>$setName</em>";
echo "</h3>";


$parts =  explode( "|", $set );
$set = trim( $parts[0] );
echo '<div>' . getComments( $set ) . '</div><hr>';

$questions = getAllQuestions( $set );

for( $i=1; $i<count( $parts ); ++$i ) {
	$command = trim( $parts[$i] );
	//echo "command: $command <br>";
	if( $command == "random" ) {
		shuffle( $questions );
	} else if( preg_match( "/head ([0-9]+)/", $command, $matches ) ) {
		$questions = array_slice(  $questions, 0, intval( $matches[1]) );
	} else if( preg_match( "/add (.+)/", $command, $matches ) ) {
		$questions = array_merge( $questions, getAllQuestions( $matches[1] ) );
        }
}

echo "<h4 id='qCounter'>Bitte eine Frage ausw&auml;hlen</h4>";
for( $i=1; $i<=count($questions ); ++$i ) {
	echo "<button type='button' id='$i' class='qButton btn btn-secondary' title='Frage $i'>$i</button> ";
}


$c = 1;
foreach( $questions as $q )  {
	echo "<div id='divP$c' class='question_card hidden'>";
	echo "<div id='div$c' class='questions hidden' style='font-size:18px;'>" . $q->print() . " </div>";
	echo "<div id='sol$c' class='hidden'>" . $q->answer . " </div>";
	echo "</div>";
	++$c;
	
}


























function getTopics( ) {
	global $basePath;
	$files = glob($basePath . '/*.top' );
	return array_map('basename', $files);
}

print_r(getTopics());


function getGroups( $task ) {
	global $basePath;
	$dirs = glob("$basePath/$task" . '/*' , GLOB_ONLYDIR);
	return array_map('basename', $dirs);
}



function getSets( $topic ) {
	global $basePath;
	return file("$basePath/$topic", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

function getBuild( $topic, $setName ) {
	global $basePath;
	$lines =  file("$basePath/$topic", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	foreach( $lines as $line ) {
		if( preg_match("/^$setName/", $line ) ) {
			$parts = explode( ":", $line );
			return $parts[1]; 
		}
	}
}

function getAllQuestions( $set ) {
	$questions = array_filter( getQuestions( $set ), function($q) {
    				return $q->question != "-";
  		} );
	$questions = array_merge( $questions, getMultiLineQuestions( $set ) );
	return $questions;
}




function getQuestions( $set ) {
	global $basePath;
	$lines =  file("$basePath/$set.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	return array_map('parseLine', $lines);
}

echo "FOO:";
print_r(getQuestions($set));

function getMultiLineQuestions( $set ) {
	global $basePath;
	$questions = [];
	$lines =  file("$basePath/$set.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	for( $i=0; $i<count($lines); ++$i ) {
		$q = [];
		if( preg_match('/^<</', $lines[$i] ) ) {
			while( ! preg_match('/^#/', $lines[++$i] ) ) {
				$q[] = htmlentities( $lines[$i] );
			}
			$a = $lines[++$i];
			$questions[] = new MultiLineQuestion( implode("\n", $q), $a );
		}
	}
	
	return $questions;
}

function getComments( $set, $ext = "txt" ) {
	global $basePath;
	$lines =  file("$basePath/$set.$ext", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$comments =  "";
	foreach( $lines as $line ) {
		if( preg_match('/^!/', $line ) ) {
			$comments .= substr($line,1 ) . " ";
		}
	}
	return $comments;
}

function getSolutions( $task, $group ) {
	global $basePath;
	$dirs = glob("$basePath/$task/$group" . '/*.xml' );
	return array_map('basename', $dirs);
}

function saveTry( $topic, $set, $questionId, $result ) {
	$file = 'protocol/protocol.txt';
	$questions = getAllQuestions( $set );
	$qtext = $questions[$questionId]->getQuestionInfo();
	$current = date("Y-m-d:H:i:sa") . "#$topic#$set#$qtext#$result\n";
	return file_put_contents($file, $current, FILE_APPEND | LOCK_EX);
}

function getProtocol() {
	$file = 'protocol/protocol.txt';
	return file($file,  FILE_SKIP_EMPTY_LINES);
}
*/




?>
