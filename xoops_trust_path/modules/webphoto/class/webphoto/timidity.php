<?php
// $Id: timidity.php,v 1.3 2010/09/27 03:42:54 ohwada Exp $

//=========================================================
// webphoto module
// 2009-10-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-20 K.OHWADA
// create_wav()
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_timidity
// wrapper for webphoto_lib_timidity
//=========================================================
class webphoto_timidity extends webphoto_cmd_base
{
	var $_timidity_class;
	var $_cfg_use_timidity;

	var $_WAV_EXT    = 'wav';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_timidity( $dirname, $trust_dirname )
{
	$this->webphoto_cmd_base( $dirname, $trust_dirname );

	$this->_timidity_class   =& webphoto_lib_timidity::getInstance();

	$this->_cfg_use_timidity = $this->get_config_by_name( 'use_timidity' );

	$this->_timidity_class->set_cmd_path( 
		$this->get_config_dir_by_name( 'timiditypath' ) );

	$this->set_debug_by_ini_name( $this->_timidity_class );
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_timidity( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create wav
//---------------------------------------------------------
function create_wav( $src_file, $dst_file, $option='' )
{
	if ( empty($src_file) ) {
		return null ;
	}
	if ( ! is_file($src_file) ) {
		return null ;
	}
	if ( ! $this->_cfg_use_timidity ) {
		return null ;
	}

	$this->_timidity_class->mid_to_wav( $src_file, $dst_file, $option );

	if ( is_file($dst_file) ) {
		$this->chmod_file( $dst_file );
		return 1 ;	// suceess
	}

	$this->set_error( $this->_timidity_class->get_msg_array() );
	return -1 ;	// fail
}

//---------------------------------------------------------
// create wav tmp
//---------------------------------------------------------
function create_wav_tmp( $item_id, $src_file, $option='' )
{
	if ( empty($src_file) ) {
		return null ;
	}
	if ( ! is_file($src_file) ) {
		return null ;
	}
	if ( ! $this->_cfg_use_timidity ) {
		return null ;
	}

	$dst_file = $this->build_wav_file( $item_id );

	$this->_timidity_class->mid_to_wav( $src_file, $dst_file, $option );

	if ( ! is_file($dst_file) ) {
		$arr = array(
			'flag'   => false ,
			'errors' => $this->set_get_errors( $this->_timidity_class->get_msg_array() ),
		);
		return $arr ;
	}

	$this->chmod_file( $dst_file );

	$arr = array(
		'flag'      => true ,
		'item_id'   => $item_id ,
		'src_file'  => $dst_file ,
		'src_ext'   => $this->_MP3_EXT ,
	);
	return $arr;
}

function build_wav_file( $item_id )
{
	return $this->build_file_by_prefix_ext( 
		$this->build_prefix( $item_id ), $this->_WAV_EXT );
}

// --- class end ---
}

?>