<?php
// $Id: extra.php,v 1.2 2008/08/25 19:28:06 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

// === define begin ===
if( !defined("_EX_WEBPHOTO_LANG_LOADED") ) 
{

define("_EX_WEBPHOTO_LANG_LOADED" , 1 ) ;

//=========================================================
// mobile
//=========================================================

define("_WEBPHOTO_MYSQL_CHARSET",  "ujis");
define("_WEBPHOTO_CHARSET_MOBILE", "Shift_JIS");
define("_WEBPHOTO_MB_LANGUAGE",    "ja");

function webphoto_mobile_array()
{
	$arr = array(
		'DoCoMo'     => 'docomo' ,
		'J-PHONE'    => 'j-phone' ,
		'KDDI'       => 'au' ,
		'UP.Browser' => 'au' ,
	);
	return $arr;
}

// === define end ===
}

?>