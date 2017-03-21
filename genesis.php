<html>

<head>
<title>Genesis</title>
</head>

<body>

<?php

# Edit

error_reporting(E_ALL ^ E_DEPRECATED);

include ('bin/functions.php');
include ('bin/mysql_functions.php');
include ('bin/log_functions.php');
include ('bin/file_functions.php');
include ('bin/data_functions.php');
include ('bin/display_functions.php');
include ('bin/command_functions.php');

if (!isset($_POST['mode']) && !isset($_POST['mode'])) {
	selectRows();
	echo "<p>OR<p>";
	displayCommands();
}

if (isset($_POST['mode']) && $_POST['mode'] == "define") {
	buildSelector($_POST['rows']);	
}

if (isset($_POST['mode']) && $_POST['mode'] == "create") {
	$command = buildGenerateCommand();
	$_POST['rows'] = ""; # empty the POST data
	$data = generate($command[0], $command[1]);
}

if (isset($_POST['mode']) && $_POST['mode'] == "create_existing") {
	$options = "Rows=" . $_POST['rows'] . ",Mode=" . $_POST['outtype'] . ",Outfile=" . $_POST['file'];
	$data = generate($_POST['components'], $options);
}

listDataFiles();

outputLog();

?>

</body>

</html>