<?php $ACTIVE = true;

if(!array_key_exists('trials', $_POST)) {
	$trials = array(
		// Instruction, test
		// These should be names of the objects in data.js
		array('KA1', 'KA2'),
		array('KB1', 'KB2'),
		array('KC1', 'KC2'),
		array('KD1', 'KD2'),
		array('MHA1', 'MHA2'),
		array('MHB1', 'MHB2'),
		array('MHC1', 'MHC2'),
		array('MHD1', 'MHD2'),
	);
	shuffle($trials);
} else {
	$raw_trials = stripslashes($_POST['trials']);
	$trials = json_decode($raw_trials);
}

// Connect to database 
$username = "";
$password = "";
$dbname = "";
$servername = "";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

function generate_subject_id() {
	global $conn;
	$query = "SELECT MAX( subject_id ) FROM trials";
	$result = $conn->query($query);
	while($row = $result->fetch_assoc()) {
        if(array_key_exists('MAX( subject_id )', $row)) {
	        $id = (int) $row['MAX( subject_id )'];
        }
        else {
        	$id = rand(0,10000);
        }
    }
    return $id + 1;
}

// Set subject id
if(array_key_exists('subject_id', $_POST)) {
	$subject_id = (int) $_POST['subject_id'];
} else {
	$subject_id = generate_subject_id();
}

// Total number of trials
$num_trials = count($trials);

// Set current trial
if(array_key_exists('trial', $_POST)) {
	$trial = (int) $_POST['trial'];
} else {
	$trial = 0;
}

// Page Title
$page_title = 'Colouring!';

// Step
if(array_key_exists('step', $_POST)) {
	$step = (int) $_POST['step'];
} else {
	$step = 0;
}

// Save data?
if((($step == 3) & ($trial >= 1)) || ($step == 4)) {
	if(array_key_exists('results', $_POST)){
		if (get_magic_quotes_gpc()) {
 			$results = stripslashes($_POST['results']);
 		}
	} else {
		$results = '';
	}

	if(!array_key_exists('test', $_GET)) {
		// Store
		$sql = "INSERT INTO trials (subject_id, trial_number, results)
		VALUES ('$subject_id', '$trial', '$results')";
		$res = $conn->query($sql);
	}
}



if($step == 5){
	$selections = array();
	for($i=1; $i<=50; $i++) {
		$selections[$i] = -1;
		if(array_key_exists('question-'.$i, $_POST)){
			$selections[$i] = (int) $_POST['question-'.$i];
		}
	}
	
	// Calculate score
	$agree = array(1, 2, 4, 5, 6, 7, 9, 12, 13, 16, 18, 19, 20, 21, 22, 23, 26, 33, 35, 39, 41, 42, 43, 45, 46);
	$disagree = array(3, 8, 10, 11, 14, 15, 17, 24, 25, 27, 28, 29, 30, 31, 32, 34, 36, 37, 38, 40, 44, 47, 48, 49, 50);
	$score = 0;
	foreach($selections as $j => $selection) {
		if(in_array($j, $agree) && in_array($selection, array(0,1)) ) {
			$score += 1;
		}
		elseif(in_array($j, $disagree) && in_array($selection, array(2,3)) ) {
			$score += 1;
		}
	}
	
	if(array_key_exists('age', $_POST)){
		$age = (int) $_POST['age'];
	} else {
		$age = -1;
	}
	
	if(array_key_exists('gender', $_POST)){
		$gender = strip_tags($_POST['gender']);
	} else {
		$gender = '';
	}
	
	$goal = '';
	if(array_key_exists('goal', $_POST)) {
		$goal = strip_tags($_POST['goal']);
	}
	
	$comments = '';
	if(array_key_exists('comments', $_POST)) {
		$comments = strip_tags($_POST['comments']);
	}
	
	$selections = json_encode($selections);

	if(!array_key_exists('test', $_GET)) {
		$sql = "INSERT INTO survey (subject_id, AQ_score, AQ_selections, age, gender, goal, comments)
		VALUES ('$subject_id', '$score', '$selections', '$age', '$gender', '$goal', '$comments')";
		$res = $conn->query($sql);
	}

}


// Load the right step
if(file_exists('steps/step' . $step . '.inc.php')) {
	include_once('steps/step' . $step . '.inc.php');
} else {
	echo 'The requested step could not be found';
}


?>