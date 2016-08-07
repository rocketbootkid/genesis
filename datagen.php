<?php

error_reporting(E_ALL ^ E_DEPRECATED);

include ('bin/functions.php');
include ('bin/mysql_functions.php');
include ('bin/log_functions.php');
include ('bin/file_functions.php');
include ('bin/data_functions.php');
include ('bin/display_functions.php');

$command = "";

if (!isset($_POST['rows'])) {
	selectRows();
}

if (isset($_POST['rows'])) {
	buildSelector($_POST['rows']);
}

if (isset($_POST['request']) && $_POST['request'] == 1) {
	$command = buildGenerateCommand();
	$_POST['rows'] = ""; # empty the POST data
}

$data = generate($command[0], $command[1]);

#$definition = "[N:t],[N:f],[N:s],[A:N S|T|R|P],[D:y/m/d],[D:H:M:S],[T:01??-???-????],[T:07???-??????]";
#$options = "Rows=10,Outfile=data.csv,Mode=insert,Table=People4";

#$data = generate($definition, $options);
convertDataToTable($data);

outputLog();

?>