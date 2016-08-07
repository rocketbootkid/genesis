<?php

function logRequest($definition, $options) {
	
	$filename = $GLOBALS['command_log'];
	$data = date('Y-m-d H:i:s') . ": Definition: " . $definition . ", Options: " . $options . "\n";
	
	$file = fopen($filename, "a+");
	fwrite($file, $data);
	fclose($file);
	
	writeLog("logRequest(): Command written to command log.");
	
}

function writeCSVFile($data) {
	
	$filename = $GLOBALS['outfile'];
	$path = $GLOBALS['outfile_path'];
	
	writeLog("writeFile(): Path: " . $path . "/" . $filename);
	
	# Remove HTML elements from data;
	$data = str_replace("<br/>", "", $data);
	
	$file = fopen($path . "/" . $filename, "w");
	fwrite($file, $data);
	fclose($file);
	
	writeLog("writeCSVFile(): CSV file written.");
	
}

function writeDDLFile($table, $ddl) {
	
	$path = $GLOBALS['outfile_path'];
	
	writeLog("writeDDLFile(): Path: " . $path . "/" . $table . ".sql");
	
	$file = fopen($path . "/" . $table . ".sql", "w");
	fwrite($file, $ddl);
	fclose($file);
	
	writeLog("writeDDLFile(): DDL file written.");
	
}

?>