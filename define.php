<html>

<head>
<title>Genesis | Define Data</title>
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

buildSelector($_POST['rows']);

?>

</body>

</html>