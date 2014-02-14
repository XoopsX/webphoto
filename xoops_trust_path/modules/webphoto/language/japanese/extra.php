<?php
// $Id: extra.php,v 1.3 2008/09/04 00:46:47 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-09-01 K.OHWADA
// webphoto_mobile_array() -> webphoto_mobile_carrier_array()
//---------------------------------------------------------

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

//---------------------------------------------------------
// http://www.nttdocomo.co.jp/service/imode/make/content/spec/useragent/
// DoCoMo/1.0/N501i
// DoCoMo/2.0 F2051(c100;TB)
//
// http://www.au.kddi.com/ezfactory/tec/spec/4_4.html
// KDDI-SA31 UP.Browser/6.2.0.7.3.129 (GUI) MMP/2.0
//
// http://creation.mb.softbank.jp/web/web_ua_about.html
// J-PHONE/2.0/J-T03
// Vodafone/1.0/V904SH/SHJ001/SN1234
// SoftBank/1.0/910T/TJ001/SN1234
//---------------------------------------------------------
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