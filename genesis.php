<html>

<head>
<title>Genesis</title>
</head>

<body>

<?php

error_reporting(E_ALL ^ E_DEPRECATED);

include ('bin/functions.php');
include ('bin/mysql_functions.php');
include ('bin/log_functions.php');
include ('bin/file_functions.php');
include ('bin/data_functions.php');
include ('bin/display_functions.php');

selectRows();

echo "<p>OR<p>";

displayCommandHistory();


if (isset($_POST['request']) && $_POST['request'] == 1) {
	$command = buildGenerateCommand();
	$_POST['rows'] = ""; # empty the POST data
	$data = generate($command[0], $command[1]);
	convertDataToTable($data);
}

outputLog();

?>

</body>

</html>