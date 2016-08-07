<?php

# Parameters

$log = "";
$rows = 1; # Default number of rows produced; set by "Rows" option.
$outfile = "default.csv";
$outfile_path = "data";
$mode = ""; # Defines the mode for data target; create|insert (for db) or outfile (for csv).
$table_name = "";
$command_log = "log/commands.log";


function generate($definition, $options) {
	
	writeLog("generate(): Data Definition: " . $definition);	
	writeLog("generate(): Options: " . $options);

	# Log Requested
	logRequest($definition, $options);
	
	$data = "";
	$header = "";

	$columns = explode(",", $definition);
	$num_of_columns = count($columns);
	
	# Parse options
	parseOptions($options);
	
	# Headers
	$header = parseHeaders($definition);
	
	for ($r = 0; $r < $GLOBALS['rows']; $r++) {
	
		for ($c = 0; $c < $num_of_columns; $c++) {
			writeLog("generate(): " . $columns[$c]);
			if (substr($columns[$c], 1, 1) == "N") { # Name: [N:<rest of definition>]
				$data = $data . "\"" . generateName(substr($columns[$c], 3, strlen($columns[$c])-4)) . "\",";
			} elseif (substr($columns[$c], 1, 1) == "T") { # Telephone: [T:<rest of definition>]
				$data = $data . "\"" . generateTelephone(substr($columns[$c], 3, strlen($columns[$c])-4)) . "\",";
			} elseif (substr($columns[$c], 1, 1) == "D") { # Date / Time: [D:<rest of definition>]
				$data = $data . "\"" . generateDate(substr($columns[$c], 3, strlen($columns[$c])-4)) . "\",";
			} elseif (substr($columns[$c], 1, 1) == "A") { # Address: [A:<rest of definition>]
				$data = $data . "\"" . generateAddress(substr($columns[$c], 3, strlen($columns[$c])-4)) . "\",";
			}				
		}
		
		$data = substr($data, 0, strlen($data)-1) . "<br/>\n";
		
	}

	# Produce CSV file
	if ($GLOBALS['mode'] == "file") {
		writeLog("generate(): Producing output file...");	
		writeCSVFile($header . "\n" . $data);
	}
	
	# Write data to MySQL
	if ($GLOBALS['mode'] == "create" || $GLOBALS['mode'] == "insert") {
		writeLog("generate(): Writing to database...");	
		writeDatabase($header, $data);
	}
	
	return $data;
	
}

function parseOptions($options) {

	writeLog("parseOptions(): Options: " . $options);	

	$options = explode(",", $options);
	$num_of_options = count($options);
	for ($o = 0; $o < $num_of_options; $o++) {
		$option_components = explode("=", $options[$o]);
		if ($option_components[0] == "Rows") { # Rows
			writeLog("parseOptions(): Rows: " . $option_components[1]);
			$GLOBALS['rows'] = $option_components[1];
		} elseif ($option_components[0] == "Outfile") { # Outfile
			writeLog("parseOptions(): Outfile: " . $option_components[1]);
			$GLOBALS['outfile'] = $option_components[1];
			$GLOBALS['mode'] = "outfile";
		} elseif ($option_components[0] == "Mode") { # DML Mode
			writeLog("parseOptions(): Data Mode: " . $option_components[1]);
			$GLOBALS['mode'] = $option_components[1];
		} elseif ($option_components[0] == "Table") { # Table Name
			writeLog("parseOptions(): Table Name: " . $option_components[1]);
			$GLOBALS['table_name'] = $option_components[1];
		}
		
	}
	
}

function parseHeaders($definition) {
	
	writeLog("parseHeaders(): Definition: " . $definition);	
	
	$header = "";
	
	preg_match_all("/[[][A-Z]/", $definition, $fields);
	$num_fields = count($fields[0]);
	writeLog("parseHeaders(): Columns: " . $num_fields);	
	
	for ($f = 0; $f < $num_fields; $f++) {
		if (substr($fields[0][$f], 1, 1) == "N") { # Name: [N:<rest of definition>]
			$header = $header . "name" . $f . ",";
		} elseif (substr($fields[0][$f], 1, 1) == "T") { # Telephone: [T:<rest of definition>]
			$header = $header . "telephone" . $f . ",";
		} elseif (substr($fields[0][$f], 1, 1) == "D") { # Date / Time: [D:<rest of definition>]
			$header = $header . "date" . $f . ",";
		} elseif (substr($fields[0][$f], 1, 1) == "A") { # Address: [A:<rest of definition>]
			$header = $header . "address" . $f . ",";
		}
	}
	
	$header = substr($header, 0, strlen($header)-1);
	
	writeLog("parseHeaders(): Header: " . $header);	
	
	return $header;
	
}

function getMaxFieldLength($field_number, $rows) {
	
	$max_length = 0;
	
	writeLog("getMaxFieldLength(): Field Number: " . $field_number);
	
	# Need to go through each row
	for ($r = 0;  $r < count($rows); $r++) {
		writeLog("getMaxFieldLength(): Row Data: " . $rows[$r]);
		
		$columns = explode("\",", $rows[$r]); # Explode the contents by comma
		
		writeLog("getMaxFieldLength(): Number of columns: " . count($columns));
		
		# Then get the length of $field_number, and update max_length
		writeLog("getMaxFieldLength(): Requested Column Data: " . $columns[$field_number]);
		if (strlen($columns[$field_number]) > $max_length) {
			$max_length = strlen($columns[$field_number]);
		}
			
	}
	
	writeLog("getMaxFieldLength(): Max length of Field " . $field_number . " is " . $max_length . "");
	
	return $max_length;
	
}

function getColumnType($field) {
	
	$type = "";
	
	if (substr($field, 0, 4) == "name") {
		$type = "varchar";
	} elseif (substr($field, 0, 4) == "addr") {
		$type = "varchar";
	} elseif (substr($field, 0, 4) == "date") {
		$type = "varchar";
	} elseif (substr($field, 0, 4) == "tele") {
		$type = "varchar";
	}
	
	writeLog("getColumnType(): Field Type: " . $type);
	
	return $type;
	
}

?>