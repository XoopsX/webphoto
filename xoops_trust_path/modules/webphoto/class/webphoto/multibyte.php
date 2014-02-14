<?php
// $Id: multibyte.php,v 1.1 2009/01/24 07:39:58 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_multibyte
// wrapper for webphoto_lib_multibyte
//=========================================================
class webphoto_multibyte extends webphoto_lib_multibyte
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_multibyte()
{
	$this->webphoto_lib_multibyte();

	$xoops_class =& webphoto_xoops_base::getInstance();
	$is_japanese =  $xoops_class->is_japanese( _C_WEBPHOTO_JPAPANESE ) ;

	$this->set_is_japanese( $is_japanese );
	$this->set_ja_kuten(   _WEBPHOTO_JA_KUTEN );
	$this->set_ja_dokuten( _WEBPHOTO_JA_DOKUTEN );
	$this->set_ja_period(  _WEBPHOTO_JA_PERIOD );
	$this->set_ja_comma(   _WEBPHOTO_JA_COMMA );
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_multibyte();
	}
	return $instance;
}

// --- class end ---
}

?>