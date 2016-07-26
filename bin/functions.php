<?php

$log = "";

function parser($rows, $definition) {
	
	writeLog("parser(): " . $definition);
	
	$columns = explode(",", $definition);
	$num_of_columns = count($columns);
	$data = "";
	
	for ($r = 0; $r < $rows; $r++) {
	
		for ($c = 0; $c < $num_of_columns; $c++) {
			writeLog("parser(): " . $columns[$c]);
			if (substr($columns[$c], 1, 1) == "N") { # Name: [N:<rest of definition>]
				$data = $data . "\"" . generateName(substr($columns[$c], 3, strlen($columns[$c])-4)) . "\",";
			}			
		}
		
		$data = substr($data, 0, strlen($data)-1) . "<br/>\n";
	}

	
	return $data;
	
}


function generateName($definition) {
	
	writeLog("generateName(): " . $definition);
	$elements = str_split($definition);
	$number_of_elements = count($elements);
	$name = "";
	
	for ($e = 0; $e < $number_of_elements; $e++) {
		if ($elements[$e] == "t") { # title
			$titles = file('bin/lists/titles.txt');
			$name = $name . trim(ucwords(strtolower($titles[rand(0, count($titles)-1)])));
		} elseif ($elements[$e] == "f") { # forename
			$forenames = file('bin/lists/forenames.txt');
			$name = $name . trim(ucwords(strtolower($forenames[rand(0, count($forenames)-1)])));
		} elseif ($elements[$e] == "s") { # surname
			$surnames = file('bin/lists/surnames.txt');
			$name = $name . trim(ucwords(strtolower($surnames[rand(0, count($surnames)-1)])));
		} else {
			$name = $name . $elements[$e]; # Just append the character
		}
	}
	
	writeLog("generateName(): " . $name);
	
	return $name;
	
}

function outputLog() {
	
	echo "<hr>Debug Log<p>" . $GLOBALS['log'];
	
}

function writeLog($message) {
	
	$GLOBALS['log'] = $GLOBALS['log'] . date('Y-m-d H:i:s') . ": " . $message . "<br/>\n";
	
}


?>