<?php
// $Id: check_mb.php,v 1.2 2008/12/10 19:08:56 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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

	$title = $this->_xoops_class->get_config_by_name( 'sitename' ) .' - '. $this->_MODULE_NAME ;

	$text  = $this->build_html_head( $this->sanitize($title), $charset );
	$text .= $this->build_html_body_begin();
	$text .= 'charset : '.$charset."<br />\n";
	$text .= _AM_WEBPHOTO_MULTIBYTE_SUCCESS ;
	$text .= "<br /><br />\n";
	$text .= '<input class="formButton" value="'. _CLOSE .'" type="button" onclick="javascript:window.close();" />';
	$text .= $this->build_html_body_end();

	echo $this->conv( $text, $charset );
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

// --- class end ---
}

?>