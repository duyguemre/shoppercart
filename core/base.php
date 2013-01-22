<?php
// HTTP
define('HTTP_SERVER', 'http://localhost:81/shoppercart/');
define('HTTP_IMAGE', 'http://localhost:81/shoppercart/image/');
define('HTTP_ADMIN', 'http://localhost:81/shoppercart/admin/');

// HTTPS
define('HTTPS_SERVER', 'http://localhost:81/shoppercart/');
define('HTTPS_IMAGE', 'http://localhost:81/shoppercart/image/');


define('HTTP', 'http');
define('HTTPS', 'https');
define('HOSTNAME', 'localhost');
define('PORT', '81');
define('SHOPNAME', 'shoppercart');

define('IMAGEPATH', 'image');

define('MODULES_DIR', 'D:\Nameless\Zend\Apache2\htdocs\shoppercart/storefront/modules/');
define('MODULES_WEB_DIR', '/shoppercart/storefront/modules/');
define('STOREFRONT_DIR', 'D:\Nameless\Zend\Apache2\htdocs\shoppercart/storefront/');
define('CONTROLLER_DIR', STOREFRONT_DIR.'controller/');
define('MODEL_DIR', STOREFRONT_DIR.'model/');
define('LANGUAGE_DIR', STOREFRONT_DIR.'language/');
define('COMPONENT_CONTROLLER_DIR', CONTROLLER_DIR.'component/');

define('TEMPLATE_DIR', STOREFRONT_DIR.'theme/');
define('CORE_DIR', 'D:\Nameless\Zend\Apache2\htdocs\shoppercart/core/');
define('IMAGE_DIR', 'D:\Nameless\Zend\Apache2\htdocs\shoppercart/image/');
define('CACHE_DIR', STOREFRONT_DIR.'cache/');
define('DEFAULT_LOG_FILE', 'C:\Documents and Settings\is96203\Desktop/plog');

define('TEMPLATE_NAME', 'default/');
define('DEFAULT_TEMPLATE_NAME', 'default/');
define('THEME_DIR', STOREFRONT_DIR.'theme/'. TEMPLATE_NAME);
define('LANGUAGE_NAME', 'english/');
define('TEMPLATE', TEMPLATE_NAME.'template/');
define('MODULE_CSS_DIR', 'storefront/theme/'.TEMPLATE_NAME.'stylesheet/');
define('MODULE_JS_DIR', 'storefront/theme/javascript/');
define('MODULE_WEB_DIR', 'storefront/modules/');

define('COMPONENT_TEMPLATE_DIR', TEMPLATE_DIR . TEMPLATE . 'component/');

define('HEADER_TEMPLATE_FILE', 'header.tpl');
define('FOOTER_TEMPLATE_FILE', 'footer.tpl');
define('DEFAULT_CACHE_EXPIRE_TIME', 3600);
define('DEFAULT_TIMEZONE', 'Europe/Istanbul');

define('DEFAULT_MODULE_CONTROLLER_FILE', MODULES_DIR . "default_module.php");
define('DEFAULT_MODULE_CLASSNAME', "default_module");
define('DEFAULT_MODULE_PATH', "common/");
define('DEFAULT_MODULE_TEMPLATE_FILE', STOREFRONT_DIR.'theme/' . TEMPLATE_NAME . "template/default_module.tpl");
define('DEFAULT_TEMPLATE_FILE', TEMPLATE_DIR . TEMPLATE . "default.tpl");
define('DEFAULT_LAYOUTS_FOLDER', TEMPLATE_DIR . TEMPLATE ."layouts/");
define('DEFAULT_POSITION_FOLDER', TEMPLATE_DIR . TEMPLATE ."/");

		
define('DIR_DATABASE', 'D:\Nameless\Zend\Apache2\htdocs\shoppercart/core/database/');
define('DIR_LANGUAGE', 'D:\Nameless\Zend\Apache2\htdocs\shoppercart/storefront/language/');
define('DIR_CONFIG', 'D:\Nameless\Zend\Apache2\htdocs\shoppercart/core/config/');
define('DIR_DOWNLOAD', 'D:\Nameless\Zend\Apache2\htdocs\shoppercart/download/');
 



// DB
define('DB_DRIVER', 'mysql');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'sc');
define('DB_PASSWORD', 'asd12345');
define('DB_DATABASE', 'shoper_cart');
define('DB_PREFIX', ''); 

error_reporting ( E_ALL );

//require_once ('utility/utilityBase.php');
require_once ('system/system_base.php');
require_once ('service/service_base.php');
require_once ('library/library_base.php');
require_once ('utility/utf8.php');

require_once ('shop.php');


$layoutParameterName = "layout";

?>