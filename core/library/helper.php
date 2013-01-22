<?php
class Helper {
	public function getImageServer($protocol) {
		if (PORT != 80) {
			return $protocol . "://" . HOSTNAME . ":" . PORT . "/" . SHOPNAME . "/" . IMAGEPATH . "/";
		} else {
			return $protocol . "://" . HOSTNAME . "/" . SHOPNAME . "/" . IMAGEPATH . "/";
		}
	}
	public function redirect($url, $status = 302) {
		header ( 'Status: ' . $status );
		header ( 'Location: ' . str_replace ( array (
				'&amp;',
				"\n",
				"\r" 
		), array (
				'&',
				'',
				'' 
		), $url ) );
		exit ();
	}
}
?>