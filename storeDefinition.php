<?php

error_reporting(E_ALL ^ E_DEPRECATED);

include ('bin/functions.php');
include ('bin/mysql_functions.php');
include ('bin/log_functions.php');
include ('bin/file_functions.php');
include ('bin/data_functions.php');
include ('bin/display_functions.php');

$details = buildGenerateCommand();
logRequest($details[0], $details[1]);

#outputLog();

header('Location: genesis.php');

?>