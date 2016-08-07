<?php

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

function generateAddress($definition) {

	writeLog("generateAddress(): " . $definition);
	$elements = str_split($definition);
	$number_of_elements = count($elements);
	writeLog("generateAddress(): Number of Components: " . $number_of_elements);
	$address = "";

	for ($d = 0; $d < $number_of_elements; $d++) {
		
		writeLog("generateAddress(): Current Element: " . $elements[$d]);
		
		if ($elements[$d] == "N") { # Number
			writeLog("generateAddress(): Number");
			$address = $address . mt_rand(1, 1000);
			writeLog("generateAddress(): Current Address: " . $address);
		} elseif ($elements[$d] == "S") { # Street
			writeLog("generateAddress(): Street");
			$address = $address . generateStreet();
			writeLog("generateAddress(): Current Address: " . $address);
		} elseif ($elements[$d] == "T") { # Town
			writeLog("generateAddress(): Town");
			$address = $address . generateTown();
			writeLog("generateAddress(): Current Address: " . $address);
		} elseif ($elements[$d] == "R") { # Region
			writeLog("generateAddress(): Region");
			$address = $address . generateRegion();
			writeLog("generateAddress(): Current Address: " . $address);
		} elseif ($elements[$d] == "P") { # Postcode
			writeLog("generateAddress(): Postcode");
			$address = $address . generatePostcode();
			writeLog("generateAddress(): Current Address: " . $address);
		} elseif ($elements[$d] == "|") { # Delimiter
			$address = $address . ", ";
			writeLog("generateAddress(): Current Address: " . $address);
		} else {
			writeLog("generateAddress(): Character");
			$address = $address . $elements[$d]; # Just append the character
			writeLog("generateAddress(): Current Address: " . $address);
		}
	}
	
	
	writeLog("generateAddress(): " . $address);
	
	return $address;	
	
}

function generateStreet() {
	
	$first = file("bin/lists/streetnames_part_1.txt");
	$second = file("bin/lists/streetnames_part_2.txt");
	srand();
	$streetname = ucwords(strtolower(trim($first[mt_rand(0, count($first)-1)]) . " " . trim($second[mt_rand(0, count($second)-1)])));
	
	writeLog("generateStreet(): " . $streetname);
	
	return $streetname;
	
}

function generateTown() {
	
	$towns = file("bin/lists/towns.txt");
	srand();
	
	$town = "";
	while ($town == "") {
		$town = trim(ucwords(strtolower($towns[mt_rand(0, count($towns))])));
	}
	
	writeLog("generateTown(): " . $town);
	
	return $town;
	
}

function generateRegion() {
	
	$regions = file("bin/lists/region.txt");
	srand();
	$region = "";
	while ($region == "") {
		$region = trim(ucwords(strtolower($regions[mt_rand(0, count($regions))])));
	}
	
	writeLog("generateRegion(): " . $region);
	
	return $region;
	
}

function generatePostcode() {
	
	srand();
	$postcode = chr(rand(65, 87)) . chr(rand(65, 90)) . rand(1, 87) . " " . rand(1, 9) . chr(rand(65, 90)) . chr(rand(65, 90));
	
	writeLog("generatePostcode(): " . $postcode);
	
	return $postcode;	
	
}

?>