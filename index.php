<?php
ini_set ( 'display_errors', 1 );
error_reporting ( E_ALL );


define('VERSION', '1.0.0');
require_once('core/base.php');

$shop = new Shop(); 
$shop->process();
//error_log("1212You messed up!", 3, "d:/Nameless/logs/php_scripts.log");
/*$request = new Request();
$layout = $request->get[$layoutParameterName];  

$dispatcher = new Dispatcher($layout);
$dispatcher->runDispatcher();
   
$response = new Response();
$response->setOutput($output);
$response->output();
*/
// Dispatch
//$controller->dispatch($action, new Action('error/not_found'));

// Output
//$response->output();
?>