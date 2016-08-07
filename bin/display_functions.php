<?php

function selectRows() {
	
	echo "<form id='form_rows' method='post' action='datagen.php'>";
	echo "<table cellpadding=3 cellspacing=0 border=1><tr><td>Step 1. How many columns of data do you want to generate?";
	echo "<td><input id='url' type='text' name='rows' size='10'></input>";
	echo "<td><input name='button' type='submit' value='Next >'/></tr><table>";
	echo "</form>";

}

function buildSelector($columns) {
	
	writeLog("buildSelector(): Columns: " . $columns);
	
	echo "<form id='form_definition' method='post' action='datagen.php'>";
	echo "<table cellpadding=3 cellspacing=0 border=1>";
	echo "<tr><td colspan=5 bgcolor=#ddd>Step 2. Define your columns</tr>";
		
	for ($r = 0; $r < $columns; $r++) {
		echo "<tr><td>Field " . $r;
		echo "<td>Type<td><select id='type" . $r . "' name='type" . $r . "'>";
			echo "<option value='' selected>Select Type</option>";
			echo "<option value='N'>Name</option>";
			echo "<option value='A'>Address</option>";
			echo "<option value='D'>Date / Time</option>";
			echo "<option value='T'>Telephone</option>";
		echo "</input>";
		echo "<td>Definition<td><input id='definition" . $r . "' type='text' name='definition" . $r . "' size='50'></input></tr>";	
	}
	
	echo "<tr><td colspan=5 bgcolor=#ddd>Options</tr>";
	
	echo "<td colspan=2>Rows<td colspan=3><input id='rows' type='text' name='rows' size='10'></input></tr>";
	echo "<td colspan=2>Mode<td colspan=3><input id='mode' type='text' name='mode' size='20'></input>  [file | create | insert]</tr>";
	echo "<td colspan=2>CSV Filename<td colspan=3><input id='csvoutfile' type='text' name='csvoutfile' size='50'></input></tr>";
	echo "<td colspan=2>Db Table Name<td colspan=3><input id='table_name' type='text' name='table_name' size='20'></input></tr>";
	
	echo "<tr><td colspan=5><input name='button' type='submit' value='Create'/></tr></table>";
	
	echo "<input type='hidden' value='" . $columns . "' name='columns'></input>";
	echo "<input type='hidden' value='1' name='request'></input>";
	
	echo "</form><p>";	
	
}

function buildGenerateCommand() {
	
	$columns = $_POST['columns'];
	writeLog("buildGenerateCommand(): Columns: " . $columns);
	$whole_definition = "";
	$options = "";
	
	for ($c = 0; $c < $columns; $c++) {
		$type_name = "type" . $c;
		$type = $_POST[$type_name];
		$def_name = "definition" . $c;
		$definition = $_POST[$def_name];
		
		$column_definition = "[" . $type . ":" . $definition . "]";
		writeLog("buildGenerateCommand(): Column " . $c . " definition: " . $column_definition);
		
		$whole_definition = $whole_definition . $column_definition . ",";
		
	}
	
	$whole_definition = substr($whole_definition, 0, strlen($whole_definition)-1);
	writeLog("buildGenerateCommand(): Complete definition: " . $whole_definition);
	
	if ($_POST['rows'] != "") {
		$options = $options . "Rows=" . $_POST['rows'] . ",";
	}
	if ($_POST['mode'] != "") {
		$options = $options . "Mode=" . $_POST['mode'] . ",";
	}
	if ($_POST['csvoutfile'] != "") {
		$options = $options . "Outfile=" . $_POST['csvoutfile'] . ",";
	}	
	if ($_POST['table_name'] != "") {
		$options = $options . "Table=" . $_POST['table_name'] . ",";
	}
	
	$options = substr($options, 0, strlen($options)-1);
	writeLog("buildGenerateCommand(): All options: " . $options);
	
	$array = array();
	$array[0] = $whole_definition;
	$array[1] = $options;
	
	return $array;
	
}

function convertDataToTable($data) {

	# Takes long string of CSV text (with br between lines) and renders as table

	$rows = explode("<br/>", $data);
	writeLog("convertDataToTable(): Rows: " . count($rows)-1);
	
	$table = "<table cellpadding=3 cellspacing=1 border=1 width=100%>";
	
	for ($r = 0; $r < count($rows)-1; $r++) {
		
		$row_data = str_replace("\",\"", "<td>", $rows[$r]);
		$row_data = str_replace("\"", "", $row_data);
		
		$table = $table . "<tr><td>" . $row_data . "</tr>";
		
	}
	
	$table = $table . "</table>";
	
	echo $table;
	
}


?>