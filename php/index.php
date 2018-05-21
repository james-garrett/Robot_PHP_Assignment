<!DOCTYPE html>
<!-- <html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../css/index.css" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<meta name="Author" content="YOUR NAME HERE">
<meta name="Keywords" content="KEYWORD1, KEYWORD2, KEYWORD3">
<meta name="Description" content="BRIEF DESCRIPTION OF PAGE HERE.">
<title>TITLE HERE</title>
</head>
<body> -->
	<!-- <div class="cursor">
		<form name="form" action="" method="POST" ajax="true">
			<div id="textOut"></div>
			<span>Enter you command ></span>
			<input id="textIn" name='command' type="text" class="rq-form-element" placeholder="Enter Commands Here" autofocus/></span>
		</form>
	</div>
<div id="footer">



</div> -->
<style>
<?php include '../css/index.css'; ?>
</style>
<?php
	
	class placementInstructions
	{
		public $facing;
		public $coordinates = array();
		public $place = false;
		function convertFaceToSymbol($facing) {

			switch($facing) {
				case 'NORTH':
					$arrow = '^';
					break;
				case 'SOUTH': 
					$arrow = 'V';
					break;
				case 'EAST': 
					$arrow = '>';
					break;
				case 'WEST':
					$arrow = '<';
					break;
			}
			return $arrow;
		}


	}

	$doc = new DOMDocument;
	$html = '<html><body></body></html>';
	$doc->validateOnParse = true;
	$doc->loadHTML($html);
	$doc->preserveWhiteSpace = false;
	$div = $doc->createElement('div');
	// $textEntry = $doc->createElement('p', 'aaaaaaaaaaaa');
	// $div->appendChild($textEntry);
	$body = $doc->getElementsByTagName('body')[0];
	// echo $body->nodeValue;
	$body->appendChild($div);
	// $outputStream = document.getElementById('stream');
	// echo $outputStream;
	$dontPrint = true;
	$form = createForm($doc);

	$body->appendChild($form);
	$placed = false;
	$doc->appendChild($body);
	echo $doc->saveHTML();
	

	if(isset($_POST["command"])) {	
		$commandReceived = $_POST["command"];
		parseCommand($commandReceived, $placed, null);
	}

	function createForm($doc) {
		$div = $doc->createElement('div');
		$divId = $doc->createAttribute('id');
		$divId->value = "cursor";
		$div->appendChild($divId);

		$form = $doc->createElement('form');
		$formName = $doc->createAttribute('name');
		$formName->value = "form";
		$form->appendChild($formName);
		
		$formMethod = $doc->createAttribute('method');
		$formMethod->value = "POST";

		$autocomplete = $doc->createElement('autocomplete');
		$autocomplete->value = "off";
		$form->appendChild($autocomplete);

		$form->appendChild($formMethod);

		$innerDiv = $doc->createElement('div');
		$innerDivId = $doc->createAttribute('id');
		$innerDivId->value = "textOut";
		$innerDiv->appendChild($innerDivId);

		$input = $doc->createElement('input');
		$inputId = $doc->createAttribute('id');
		$inputId->value = "textIn";
		$inputName = $doc->createAttribute('name');
		$inputName->value = 'command';
		
		$inputType = $doc->createAttribute('type');
		$inputType->value = 'text';

		$inputClass= $doc->createAttribute('class');
		$inputClass->value = "rq-form-element";

		$inputPlaceHolder= $doc->createAttribute('placeholder');
		$inputPlaceHolder->value = "Enter Commands Here";

		$autofocus = $doc->createAttribute('autofocus');

		$input->appendChild($inputId);
		$input->appendChild($inputName);
		$input->appendChild($inputType);
		$input->appendChild($inputClass);
		$input->appendChild($inputPlaceHolder);
		$input->appendChild($autofocus);

		$span = $doc->createElement('span', "Enter your command>");
		$form->appendChild($span);
		$form->appendChild($input);
		$div->appendChild($form);
		return $div;
	}

	function printStart($instructionSet) {
		$output = "";
		for($row =0; $row < 6; $row++) {
			// echo "<span>";
			for($col = 0; $col < 6; $col++) {
				if($col == $instructionSet->coordinates[0]+1 && $row == $instructionSet->coordinates[1]+1) {
					$output .= " " . $instructionSet->convertFaceToSymbol($instructionSet->facing) . " ";
				} else if(($col == 0) || ($row == 0)) {
					$output .= "<span> ~ </span>";
				} else {
					$output .= "<span> . </span>";
				}
			}
			$output .= "<span>~</span><br>";
			// echo "</span>";
		}
		$output .= "<span>~ ~ ~ ~ ~ ~ ~</span>";
		return "<div>" . $output . "</div>";
	}

	function parseCommand($command, $placed, $instructions) {
		global $doc, $body, $form;
		// get textCommand from $_POST["form"]
		// if(textCommand && textCommand.length > 5)
		// length > 5 because PLACE is always first
		//grab elements, separate by space
		//if first command not place, ignore rest
			if(strpos($command, 'PLACE') !== false) {
				$instructions = parsePlaceInstruction($doc, $form, $body, $command);

			//These commands are only accessible if we've already placed the robot
			} else if($placed) {
				if(strpos($command, 'LEFT') !== false ||
			  		 strpos($command, 'RIGHT') !== false){
					$instructions->turn($instructions->facing, $command);
				} else if(strpos($command, 'MOVE') !== false) {

				} else if(strpos($command, 'REPORT') !== false) {

				} else if(strpos($command, 'Output:') !== false) {

			}
		}
	}

	function parsePlaceInstruction($doc, $form, $body, $command) {
		// global $doc, $form, $body;
		$placeholder = $_POST["command"];

	 	$div = $doc->createElement('div', $placeholder);
	 	$textEntry = $doc->createElement('p', $placeholder);
	 	// $div->appendChild($textEntry);
	 	$doc->appendChild($div);
	 	
	 	$instructionSet = new placementInstructions();
	 	if(strlen($command) < 5) {
			echo "string";
			//Command too short 
			return;
		}

		$acceptedDirection = array('NORTH', 'SOUTH', 'EAST', 'WEST');
		
		$instructionSet = new placementInstructions();
		$commandArray = explode(",", $command);
		// echo count($commandArray);

		if(count($commandArray) < 2 || count($commandArray) > 3) {
			// At least 2 commas should be present in a command
			return;
		}

		for($i = 0; $i < count($commandArray); $i++) {
			$commandArray[$i] = str_replace(' ', '', $commandArray[$i]);

			// "PLACE0" split 1st coord from place
			if(strpos($commandArray[$i], 'PLACE') !== false) {
				$tempStr = $commandArray[$i];
				$tmpArray = explode("PLACE", $commandArray[$i]);
				// $tempStr->str_replace('PLACE', '', $tempStr);
				// Put first coord in coord array.
				array_unshift($instructionSet->coordinates, $tmpArray[1]);
				$instructionSet->place = true;
			} else if(in_array($commandArray[$i], $acceptedDirection)) {
				$instructionSet->facing = $commandArray[$i];
				//echo $facing;
			} else {
				array_push($instructionSet->coordinates, $commandArray[$i]);
			}
		}	


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
	 	// $instructionSet = parseCommand($placeholder);

	 	echo printStart($instructionSet);
		// $placeholder = $doc->getElementsByTagName('command')[0];
		// $formsToRemove = $doc->getElementById('cursor');
		// echo $formsToRemove->nodeValue;
		// foreach($formsToRemove as $form) {
			// $formParent = $formsToRemove->parentNode;
			// $formParent->removeChild($formsToRemove);	
		// }
		
		setAttr($doc, 'input', 'placeholder', 'Enter next Instructions');

		// $form = $doc->getElementsByTagName('command')[0];
		// setAttr($doc, 'input', 'placeholder', 'Enter next Instructions');
		return $instructionSet;
	}

	function setAttr($node, $tag, $attr, $val) {
		foreach ($node->getElementsByTagName($tag) as $item) {
			$item->setAttribute($attr, $val);
		}
		echo $node->saveHTML();
	}


	function turn($direction, $facing) {
		if($direction == 'LEFT') {
			switch($facing) {
				case 'NORTH':
					$facing = 'WEST';
					break;
				case 'SOUTH': 
					$facing = 'EAST';
					break;
				case 'EAST': 
					$facing = 'NORTH';
					break;
				case 'WEST':
					$facing = 'SOUTH';
					break;
			}
		} else if ($direction == 'RIGHT') {
			switch($facing) {
				case 'NORTH':
					$facing = 'EAST';
					break;
				case 'SOUTH': 
					$facing = 'WEST';
					break;
				case 'EAST': 
					$facing = 'SOUTH';
					break;
				case 'WEST':
					$facing = 'NORTH';
					break;
			}
		}
		return $facing;
	}

	?>
<!-- </body>
</html> -->