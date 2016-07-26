<?php

include ('bin/functions.php');

echo parser(10, "[N:f s],[N:t f f s-s]");

/*

echo generateName('f s') . "<p>";

echo generateName('t f s') . "<p>";

echo generateName('t f f s') . "<p>";

echo generateName('f f f s-s') . "<p>";

echo generateName('f f s') . "<p>";

echo generateName('t t f s') . "<p>";

echo generateName('t-t f s') . "<p>";

echo generateName('t f-f f s-s') . "<p>";

echo generateName('f-f s') . "<p>";

echo generateName('f') . "<p>";

*/

outputLog();

?>