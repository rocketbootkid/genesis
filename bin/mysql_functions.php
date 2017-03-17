<?php

function writeDatabase($fields, $data) {
	
	$fields = explode(",", $fields);
	$rows = explode("<br/>", $data);
	writeLog("writeDatabase(): Fields: " . count($fields) . ", Rows:" . count($rows) . "");
	$status = 0;
	
	$create_table = "";	
	if ($GLOBALS['mode'] == "create") { # Create Table first
		writeLog("writeDatabase(): Looks like we're creating a new table");
		$table_name = createDatabase($fields, $rows);
	}
	
	if ($GLOBALS['mode'] == "insert") {
		$table_name = $GLOBALS['table_name'];
	}
	
	# Insert Data
	insertData($table_name, $fields, $rows);
	
}

function createDatabase($fields, $rows) {
	
	$create_table = "";
	$table_name = "";
	
	$status = setupDatabase();
	
	if ($status == 1) { # Create Table
		
		# Determine how many tables with the same name exist already, and then suffix a number
		$sql = "SELECT count(*) FROM information_schema.tables WHERE table_schema = 'genesis' AND table_name LIKE '" . $GLOBALS['table_name'] . "%';";
		writeLog("createDatabase(): Count Table SQL: " . $sql);
		$result = mysqlSQL($sql);
		
		$table_count = $result[0][0];
		$table_name = $GLOBALS['table_name'] . $table_count;
		
		$create_table = $create_table . "CREATE TABLE `genesis`.`" . $table_name . "` (\n";
	
		# Create primary key field based on table name
		$create_table = $create_table . "`" . strtolower($GLOBALS['table_name']) . "_id` int(10) NOT NULL AUTO_INCREMENT,\n";
		
		# Need to get max length of each field, and determine type from field name
		for ($f = 0; $f < count($fields); $f++) {
			
			$fieldname = $fields[$f];
			$length = getMaxFieldLength($f, $rows);
			$type = getColumnType($fields[$f]);
			
			$create_table = $create_table . "`" . $fieldname . "` " . $type . "(" . $length . ") DEFAULT NULL,\n";

		}
		
		$create_table = $create_table . "PRIMARY KEY (`" . strtolower($GLOBALS['table_name']) . "_id`)\n);";
		
		# Write DDL file
		writeDDLFile($GLOBALS['table_name'], $create_table);
	
		$status = mysqlDDL($create_table);
		if ($status != 1) {
			writeLog("createDatabase(): ERROR: Table creation didn't work!");
			writeLog("createDatabase(): ERROR: Here's the table DDL: " . $create_table);
			$table_name = "";
		} else {
			writeLog("createDatabase(): Table '" . $table_name . "' created!");
		}
	
	} else {
		writeLog("createDatabase(): ERROR: Schema creation failed: " . $status);
	}
	
	return $table_name;
	
}

function setupDatabase() {
	
	# Create schema if it's not already there
	writeLog("setupDatabase(): Creating Schema 'genesis', if it doesn't already exist.");
	$ddl = "CREATE SCHEMA IF NOT EXISTS genesis;";
	$status = mysqlDDL($ddl);

	# Select schema
	writeLog("setupDatabase(): Using Schema 'genesis'");
	$ddl = "USE genesis;";
	$status = mysqlDDL($ddl);

	return $status;
	
}

function insertData($table_name, $columns, $rows) {

	writeLog("insertData(): Table name: " . $table_name);
	
	if ($table_name != "") {
		
		$number_rows = count($rows)-1;
		writeLog("insertData(): Rows of Data: " . $number_rows);
		
		$columns = implode(",", $columns);
		
		for ($r = 0; $r < $number_rows; $r++) {
		
			$dml = "INSERT INTO genesis." . $table_name . " (" . $columns . ") VALUES (" . $rows[$r] . ");";
			writeLog("insertData(): Insert statement: " . $dml);
			$status = mysqlDDL($dml);
			
			if ($status == 1) {
				writeLog("insertData(): Insert succeeded: " . $dml);
			} else {
				writeLog("insertData(): ERROR: Insert failed: " . $dml);
			}
			
		}
		
	} else {
		writeLog("insertData(): ERROR: No table name provided");
	}

}

function logRequest($definition, $options) {
	
	$filename = $GLOBALS['command_log'];
	$data =  "\n" . date('Y-m-d H:i:s') . ": Definition: " . $definition . ", Options: " . $options;
	
	# Check if that definition / options set already exists
	$sql = "SELECT count(*) FROM genesis.definitions WHERE definition = '" . $definition . "' AND options = '" . $options . "';";
	writeLog("logRequest(): Check if Exists SQL: " . $sql);
	$results = mysqlSQL($sql);
	
	# If not, add it to the database
	if ($results[0][0] == 0) {
		writeLog("logRequest(): No matching existing definitions");
		$dml = "INSERT INTO genesis.definitions (definition, options) VALUES ('" . $definition . "', '" . $options . "');";
		writeLog("logRequest(): Insert Definition: " . $dml);
		mysqlDDL($dml);
	
	} else {
		writeLog("logRequest(): Matching existing definition found; discarding");
	}
	
	writeLog("logRequest(): Command written to command log.");
	
}

function displayCommandHistory() {
	
	$sql = "SELECT DISTINCT concat(`definition`, '|', `options`) from genesis.definitions;";
	writeLog("displayCommandHistory(): SQL: " . $sql);
	$results = mysqlSQL($sql);
	
	echo "<p><table cellpadding=3 cellspacing=0 border=1 width=100%>";
	echo "<tr bgcolor=#ddd><td>Step 1. Select an existing data definition</tr>";
	
	$num_results = count($results);
	writeLog("displayCommandHistory(): Results: " . $num_results);
	
	print_r($results);
	
	for ($r = 0; $r < count($results); $r++) {
		echo "<tr><td>" . $results[0] . "</tr>";
	}
	echo "</table>";
	
}

function mysqlConnect() {
	
	$connection = mysql_connect('localhost', 'root', 'root');
	if (!$connection) {
		die(writeLog("mysqlConnect(): ERROR: Cannot connect to MySQL: " . mysql_error()));
	} else {
		writeLog("mysqlConnect(): Connected");
	}

	return $connection;	
	
}

function mysqlDDL($command) {
	
	$flag = 1;
	
	$connection = mysqlConnect();
	
	mysql_query($command, $connection);
	
	if (mysql_errno()) { 
		writeLog("mysqlDDL(): ERROR: Error executing command: " . mysql_errno() . ": " . mysql_error() . ""); 
		$flag = mysql_errno();
	}

	mysql_close($connection);
	
	return $flag;
	
}

function mysqlSQL($command) {
	
	$connection = mysqlConnect();
	
	$result = mysql_query($command, $connection);
	
	if (mysql_errno()) { 
		writeLog("mysqlSQL(): ERROR: Error executing command: " . mysql_errno() . ": " . mysql_error() . ""); 
		$flag = mysql_errno();
	}
	
	$result = mysql_fetch_array($result);

	mysql_close($connection);
	
	return $result;
	
}

?>