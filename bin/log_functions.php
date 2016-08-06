<?php

	function outputLog() {
		
		echo "<hr>Debug Log<p>" . $GLOBALS['log'];
		
	}

	function writeLog($message) {
		
		$GLOBALS['log'] = $GLOBALS['log'] . date('Y-m-d H:i:s') . ": " . $message . "<br/>\n";
		
	}

?>