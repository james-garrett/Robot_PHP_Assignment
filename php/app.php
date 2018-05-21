<?php
	echo "aaaaaaaaaa";
	// $doc = new DOMDocument;
	// $doc->loadHTMLfile('http://localhost/PHP_Assignment/php/index.html');
	// $doc->validateOnParse = true;
	// $outputStream = $doc->getElementById('stream')->nodeValue;
	// echo $outputStream;
	if(isset($_POST["command"])) {	
		$placeholder = $_POST["command"];
		$outputStream->appendChild('<p>' . $outputStream . '</p>');

	}
	function printStart() {
		for($row =0; $row < 6; $row++) {
			echo "<span>";
			for($col =0; $col < 6; $col++) {
				if($col == 1 && $row == 5) {
					echo "<span> R </span>";
				} else if(($col == 0) || ($row == 0)) {
					echo "<span> ~ </span>";
				} else {
					echo "<span> . </span>";
				}
			}
			echo "<span> ~ </span><br>";
			echo "</span>";
		}
		echo "<span>~ ~ ~ ~ ~ ~ ~ </span>";
	}

	function parseCommand() {
		// $placeholder = $_POST["command"];
		// echo $placeholder;
		// get textCommand from $_POST["form"]
		// if(textCommand && textCommand.length > 5)
		// length > 5 because PLACE is always first
		//grab elements, separate by space
		//if first command not place, ignore rest

		//___________________PLACE, X, Y, F_________________
		// line needs to be comma separated
		// 4 elements ALWAYS
		// elements load in non-linearly so doesn't matter if its not the first element
		// HOwever, order in PLACE command itself must be rigidly followed
		//-------------------X & Y--------------------------
		// same line as PLACE
		// two numbers, single-digit
		// check if type is numbers
		// if(X > 0 && X < 5 ) && (Y > 0 && Y < 5 )

		//-------------------F------------------------------
		//Can only be string type
		//NORTH, SOUTH, EAST or WEST
		//Must be all-caps
	}

	function parsePlaceInstruction() {

	}
	?>