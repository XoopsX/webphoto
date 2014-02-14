<?php
// $Id: lame.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2009-10-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lame
// wrapper for webphoto_lib_lame
//=========================================================
class webphoto_lame extends webphoto_cmd_base
{
	var $_lame_class;
	var $_cfg_use_lame;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lame( $dirname, $trust_dirname )
{
	$this->webphoto_cmd_base( $dirname, $trust_dirname );

	$this->_lame_class   =& webphoto_lib_lame::getInstance();

	$this->_cfg_use_lame = $this->get_config_by_name( 'use_lame' );

	$this->_lame_class->set_cmd_path( 
		$this->get_config_dir_by_name( 'lamepath' ) );

	$this->set_debug_by_ini_name( $this->_lame_class );
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lame( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create mp3
//---------------------------------------------------------
function create_mp3( $src_file, $dst_file, $option='' )
{
	if ( empty($src_file) ) {
		return 0 ;	// no action
	}
	if ( ! is_file($src_file) ) {
		return 0 ;	// no action
	}
	if ( ! $this->_cfg_use_lame ) {
		return 0 ;	// no action
	}

	$this->_lame_class->wav_to_mp3( $src_file, $dst_file, $option );

	if ( is_file($dst_file) ) {
		$this->chmod_file( $dst_file );
		return 1 ;	// suceess
	}

	$this->set_error( $this->_lame_class->get_msg_array() );
	return -1 ;	// fail
}

// --- class end ---
}

?>