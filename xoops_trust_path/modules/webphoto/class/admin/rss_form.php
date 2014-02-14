<?php
// $Id: rss_form.php,v 1.1 2009/03/06 03:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_rss_form
//=========================================================
class webphoto_admin_rss_form extends webphoto_lib_form
{
	var $_THIS_FCT = 'rss_manager' ;
	var $_THIS_URL ;
	var $_URL_ADMIN_INDEX ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_rss_form( $dirname, $trust_dirname )
{
	$this->webphoto_lib_form( $dirname, $trust_dirname );

	$this->_THIS_URL        = $this->_MODULE_URL .'/admin/index.php?fct='.$this->_THIS_FCT ;
	$this->_URL_ADMIN_INDEX = $this->_MODULE_URL .'/admin/index.php';
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_rss_form( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// refresh playlist cache
//---------------------------------------------------------
function print_form_clear_cache()
{
	echo $this->build_form_tag( 'rss_clear', $this->_URL_ADMIN_INDEX );
	echo $this->build_html_token();

	echo $this->build_input_hidden( 'fct', $this->_THIS_FCT );
	echo $this->build_input_hidden( 'op',  'clear_cache' );
	echo $this->build_input_submit( 'submit', _AM_WEBPHOTO_RSS_CLEAR );

	echo $this->build_form_end();
}

// --- class end ---
}

?>