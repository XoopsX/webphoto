<?php
// $Id: extra.php,v 1.3 2008/09/04 00:46:47 ohwada Exp $

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

define("_WEBPHOTO_MYSQL_CHARSET",  "utf8");
define("_WEBPHOTO_CHARSET_MOBILE", "Shift_JIS");
define("_WEBPHOTO_MB_LANGUAGE",    "ja");

function webphoto_mobile_carrier_array()
{
	$arr = array(
		'DoCoMo'     => 'docomo' ,
		'KDDI'       => 'au' ,
		'UP.Browser' => 'au' ,
		'SoftBank'   => 'softbank' ,
		'Vodafone'   => 'softbank' ,
		'J-PHONE'    => 'softbank' ,
	);
	return $arr;
}

// === define end ===
}

?>