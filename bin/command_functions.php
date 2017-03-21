<?php

// The functions in this file relate to commands stored in the commands.log file

function logCommand($definition, $options) {

	// This function logs the request definition and options to a file: log/commands.log
	
	$combined = $definition . "|" . $options;
	$commands = file('log/commands.log');
	$exists = 0;

	// First check if the combination already exists
	foreach ($commands as $command) {
		if ($command == $combined) {
			$exists = 1;
		}
	}
	
	if ($exists == 0) {
		$commandlog = "log/commands.log";
		$current = file_get_contents($commandlog);
		$current .= $combined;
		file_put_contents($commandlog, $current);
	}
	
}

function displayCommands() {
	
	// This function displays the request definitions
	
	$commands = file('log/commands.log');

	echo "<form id='form_definition' method='post' action='genesis.php'>";
	echo "<p><table cellpadding=3 cellspacing=0 border=1 width=70%>";
	echo "<tr bgcolor=#ddd><td>Step 1. Select an existing data definition<td>Rows<td>Mode<td>Outfile<td></tr>";

	foreach ($commands as $command) {
		$components = explode("|", $command);
		$options = explode(",", $components[1]);
		echo "<tr><td>" . $components[0];
		echo "<td width=20px><input id='rows' type='text' name='rows' size='5' value='" . str_replace("Rows=", "", $options[0]) . "'></input>";
		echo "<td>" . ucwords(str_replace("Mode=", "", $options[1]));
		echo "<td width=50px><input id='file' type='text' name='file' size='15' value='" . str_replace("Outfile=", "", $options[2]) . "'></input>";
		echo "<td width=20px><input name='button' type='submit' value='Create'/>";
		echo "<input type='hidden' name='mode' value='create_existing'><input type='hidden' name='components' value='" . $components[0] . "'><input type='hidden' name='outtype' value='" . str_replace("Mode=", "", $options[1]) . "'>";
		echo "</tr>";
	}
	echo "</table></form>";
}


?>