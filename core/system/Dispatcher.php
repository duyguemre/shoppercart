<?php
final class Dispatcher {
	protected $pre_dispatch = array();
	protected $dispatch;
	protected $error;
	
	public function __construct($layout) {
		$this->dispatch = new Dispatch($layout);		
	}
	
	public function addPreDispatch($pre_dispatch) {
		$this->pre_dispatch [] = $pre_dispatch;
	}
	
	public function runDispatcher() {
		foreach ( $this->pre_dispatch as $pre_dispatch ) {
			$ret = $this->execute ( $pre_dispatch );			
			if ($ret) {
				$this->dispatch=$ret;
				break;
			}
		}		
		$dispatch = $this->dispatch;
		while ( $dispatch ) {
			$dispatch = $this->execute ( $dispatch );
		}
		
	}
	
	private function execute($dispatch) {
		if (file_exists ( $dispatch->getFile () )) {
			require_once ($dispatch->getFile ());
				
			$classname = $dispatch->getClass();
			$controller = new $classname("");
				
			if (is_callable ( array (
					$controller,
					$dispatch->getMethod () 
			) )) {
				$dispatch = call_user_func_array ( array (
						$controller,
						$dispatch->getMethod () 
				), $dispatch->getArgs () );
				//$dispatch="";
			} else {
				$dispatch = $this->error;
				
				$this->error = '';
			}
		} else {
			$dispatch = $this->error;
			$this->error = 'Not a dispatch';
		}
		
		return $dispatch;
	}
}
?>