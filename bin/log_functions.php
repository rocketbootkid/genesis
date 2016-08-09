<?php

function outputLog() {
	
	$log_data = $GLOBALS['log'];
	
	# Parse contents for ERROR, only display log if error found
	#if (substr_count($log_data, "ERROR") > 0) {
	
		$log_data = str_replace("ERROR", "<strong>ERROR</strong>", $log_data);
		
		echo "<hr>Debug Log<p>" . $log_data;
	
	#}
	
}

function writeLog($message) {
	
	$GLOBALS['log'] = $GLOBALS['log'] . date('Y-m-d H:i:s') . ": " . $message . "<br/>\n";
	
}

?>