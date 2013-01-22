<?php
class Setting extends System {
	private $data = array ();
	public function __construct($system) {
		$this->initialize ( $system );
		$this->load_settings ();
	}
	public function get($key, $group = NULL) {
		if ($group == null)
			$group = 'config';
		return (isset ( $this->data [$group] [$key] ) ? $this->data [$group] [$key] : null);
	}
	public function set($key, $value, $group = NULL) {
		if ($group == null)
			$group = 'config';
		$this->data [$group] [$key] = $value;
	}
	public function has($key, $group = NULL) {
		if ($group == null)
			$group = 'config';
		return isset ( $this->data [$group] [$key] );
	}
	public function load_settings() {
		$settings = $this->services->get_settings ();
		foreach ( $settings as $set ) {
			if (! $set ['serialized']) {
				$this->set ( $set ['key'], $set ['value'], $set ['group'] );
			} else {
				$this->set ( $set ['key'], unserialize ( $set ['value'] ), $set ['group'] );
			}
		}
	}
	public function load($filename) {
		$file = DIR_CONFIG . $filename . '.php';
		if (file_exists ( $file )) {
			$_ = array ();
			require ($file);
			$this->data = array_merge ( $this->data, $_ );
		} else {
			trigger_error ( 'Error: Could not load config ' . $filename . '!' );
			exit ();
		}
	}
}
?>