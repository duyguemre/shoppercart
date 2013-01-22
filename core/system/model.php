<?php
abstract class Model extends system{
	public function __construct($system) {
		$this->initialize($system);
	}
}
?>