<?php
class Cache {
	public function get($key) {
		$files = glob ( CACHE_DIR . 'cache.' . preg_replace ( '/[^A-Z0-9\._-]/i', '', $key ) . '.*' );
		
		if ($files) {
			$cache = file_get_contents ( $files [0] );
			
			$data = unserialize ( $cache );
			
			foreach ( $files as $file ) {
				$time = substr ( strrchr ( $file, '.' ), 1 );
				
				if ($time < time ()) {
					if (file_exists ( $file )) {
						unlink ( $file );
					}
				}
			}
			
			return $data;
		}
	}
	public function set($key, $value) {
		$this->delete ( $key );
		
		$file = CACHE_DIR . 'cache.' . preg_replace ( '/[^A-Z0-9\._-]/i', '', $key ) . '.' . (time () + DEFAULT_CACHE_EXPIRE_TIME);
		
		$handle = fopen ( $file, 'w' );
		
		fwrite ( $handle, serialize ( $value ) );
		
		fclose ( $handle );
	}
	public function delete($key) {
		$files = glob ( CACHE_DIR . 'cache.' . preg_replace ( '/[^A-Z0-9\._-]/i', '', $key ) . '.*' );
		
		if ($files) {
			foreach ( $files as $file ) {
				if (file_exists ( $file )) {
					unlink ( $file );
				}
			}
		}
	}
}
?>