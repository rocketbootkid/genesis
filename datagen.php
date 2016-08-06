<?php

error_reporting(E_ALL ^ E_DEPRECATED);

include ('bin/functions.php');
include ('bin/mysql_functions.php');
include ('bin/log_functions.php');
include ('bin/file_functions.php');
include ('bin/data_functions.php');

echo generate("[N:t],[N:f],[N:s],[A:N S|T|R|P],[D:y/m/d],[D:H:M:S],[T:01??-???-????],[T:07???-??????]", "Rows=10,Outfile=data.csv,Mode=insert,Table=People4");

outputLog();

?>