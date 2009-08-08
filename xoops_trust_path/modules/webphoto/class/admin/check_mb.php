<?php
// $Id: check_mb.php,v 1.3 2009/08/08 08:40:06 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-08-08 K.OHWADA
// mb_conv() iconv_conv()
// 2008-12-07 K.OHWADA
// window.close()
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_check_mb
//=========================================================
class webphoto_admin_check_mb extends webphoto_base_this
{
	var $_multibyte_class ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_check_mb( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );

	$this->_multibyte_class =& webphoto_lib_multibyte::getInstance();
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_check_mb( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	restore_error_handler() ;
	error_reporting( E_ALL ) ;

	$charset = $this->_post_class->get_get_text( 'charset', _CHARSET );

	$this->http_output('pass');
	header( 'Content-Type:text/html; charset='.$charset );

	$title = 'Check Multibyte';

	$text  = $this->build_html_head( $title, $charset );
	$text .= $this->build_html_body_begin();
	$text .= 'charset : '.$charset."<br /><br />\n";
	
	if ( $this->mb_exists() ) {
		$text .= "<b>mb_convert_encoding</b> <br />\n";
		$text .= $this->mb_conv( _AM_WEBPHOTO_MULTIBYTE_SUCCESS, $charset );
		$text .= "<br /><br />\n";
	} else {
		$text .= "<b>mb_convert_encoding</b> not exist <br /><br />\n";
	}

	if ( $this->i_exists() ) {
		$text .= "<b>iconv</b> <br />\n";
		$text .= $this->i_conv( _AM_WEBPHOTO_MULTIBYTE_SUCCESS, $charset );
		$text .= "<br /><br />\n";
	} else {
		$text .= "<b>iconv</b> not exist <br /><br />\n";
	}

	$text .= '<input class="formButton" value="CLOSE" type="button" onclick="javascript:window.close();" />';
	$text .= $this->build_html_body_end();

	echo $text;
}

//---------------------------------------------------------
// multibyte
//---------------------------------------------------------
function http_output( $encoding )
{
	return $this->_multibyte_class->m_mb_http_output( $encoding );
}

function conv( $str, $charset )
{
	return $this->_multibyte_class->convert_encoding( $str, $charset, _CHARSET );
}

function mb_exists()
{
	if ( function_exists('mb_convert_encoding') ) {
		return true;
	}
	return false;
}

function mb_conv( $str, $to )
{
	if ( $to == _CHARSET ) {
		return $str;
	}
	return mb_convert_encoding( $str, $to, _CHARSET );
}

function i_exists()
{
	if ( function_exists('iconv') ) {
		return true;
	}
	return false;
}

function i_conv( $str, $to, $extra='//IGNORE' )
{
	if ( $to == _CHARSET ) {
		return $str;
	}
	return iconv( _CHARSET, $to.$extra , $str );
}

// --- class end ---
}

?>