<?php
class Log {
	public function __construct() {
	}
	public function write($message) {
		$handle = fopen ( DEFAULT_LOG_FILE, 'a+' );
		fwrite ( $handle, date ( 'Y-m-d G:i:s' ) . ' - ' . $message . "\n" );
		fclose ( $handle );
	}
}
?>