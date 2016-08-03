<?php

$log = "";

function generate($rows, $definition) {
	
	writeLog("generate(): " . $definition);
	
	$columns = explode(",", $definition);
	$num_of_columns = count($columns);
	$data = "";
	
	for ($r = 0; $r < $rows; $r++) {
	
		for ($c = 0; $c < $num_of_columns; $c++) {
			writeLog("generate(): " . $columns[$c]);
			if (substr($columns[$c], 1, 1) == "N") { # Name: [N:<rest of definition>]
				$data = $data . "\"" . generateName(substr($columns[$c], 3, strlen($columns[$c])-4)) . "\",";
			} elseif (substr($columns[$c], 1, 1) == "T") { # Telephone: [T:<rest of definition>]
				$data = $data . "\"" . generateTelephone(substr($columns[$c], 3, strlen($columns[$c])-4)) . "\",";
			} elseif (substr($columns[$c], 1, 1) == "D") { # Date / Time: [D:<rest of definition>]
				$data = $data . "\"" . generateDate(substr($columns[$c], 3, strlen($columns[$c])-4)) . "\",";
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

function generateTelephone($definition) {
	
	writeLog("generateTelephone(): " . $definition);
	$elements = str_split($definition);
	$number_of_elements = count($elements);
	$telephone = "";	

	for ($t = 0; $t < $number_of_elements; $t++) {
		if ($elements[$t] == "?") { # number
			srand();
			$telephone = $telephone . rand(0,9);
		} else {
			$telephone = $telephone . $elements[$t]; # Just append the character
		}
	}

	writeLog("generateTelephone(): " . $telephone);
	
	return $telephone;
	
}

function generateDate($definition) {

	writeLog("generateDate(): " . $definition);
	$elements = str_split($definition);
	$number_of_elements = count($elements);
	$datetime = "";

	for ($d = 0; $d < $number_of_elements; $d++) {
		if ($elements[$d] == "y") { # year
			$datetime = $datetime . mt_rand(1970, date('Y'));
		} elseif ($elements[$d] == "m") { # month
			$datetime = $datetime . str_pad(mt_rand(1, 12),2,"0", STR_PAD_LEFT);
		} elseif ($elements[$d] == "d") { # day
			$datetime = $datetime . str_pad(mt_rand(1, 31),2,"0", STR_PAD_LEFT);
		} elseif ($elements[$d] == "H") { # hour
			$datetime = $datetime . str_pad(mt_rand(0, 23),2,"0", STR_PAD_LEFT);
		} elseif ($elements[$d] == "M") { # minute
			$datetime = $datetime . str_pad(mt_rand(0, 59),2,"0", STR_PAD_LEFT);
		} elseif ($elements[$d] == "S") { # second
			$datetime = $datetime . str_pad(mt_rand(0, 59),2,"0", STR_PAD_LEFT);
		} else {
			$datetime = $datetime . $elements[$d]; # Just append the character
		}
	}
	
	writeLog("generateTelephone(): " . $datetime);
	
	return $datetime;
	
}


function outputLog() {
	
	echo "<hr>Debug Log<p>" . $GLOBALS['log'];
	
}

function writeLog($message) {
	
	$GLOBALS['log'] = $GLOBALS['log'] . date('Y-m-d H:i:s') . ": " . $message . "<br/>\n";
	
}


?>