<?php

	function outputLog() {
		
		$log_data = $GLOBALS['log'];
		
		$log_data = str_replace("ERROR", "<strong>ERROR</strong>", $log_data);
		
		echo "<hr>Debug Log<p>" . $log_data;
		
	}

	function writeLog($message) {
		
		$GLOBALS['log'] = $GLOBALS['log'] . date('Y-m-d H:i:s') . ": " . $message . "<br/>\n";
		
	}

?>