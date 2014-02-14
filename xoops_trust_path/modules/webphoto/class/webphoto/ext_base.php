<?php
// $Id: ext_base.php,v 1.7 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// execute()
// 2009-11-11 K.OHWADA
// webphoto_base_ini
// 2009-10-25 K.OHWADA
// get_cached_mime_kind_by_ext()
// 2009-01-25 K.OHWADA
// create_swf()
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ext_base
//=========================================================
class webphoto_ext_base extends webphoto_base_ini
{
	var $_utility_class;
	var $_mime_handler;
	var $_config_class;
	var $_multibyte_class;

	var $_cfg_work_dir;
	var $_cfg_makethumb;
	var $_constpref;

	var $_flag_chmod = false;
	var $_cached     = array();
	var $_errors     = array();
	var $_cached_mime_type_array = array();
	var $_cached_mime_kind_array  = array();

	var $_TMP_DIR;

	var $_JPEG_EXT     = 'jpg';
	var $_TEXT_EXT     = 'txt';
	var $_ASX_EXT      = 'asx';

	var $_FLAG_DEBUG = false ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ext_base( $dirname, $trust_dirname )
{
	$this->webphoto_base_ini( $dirname, $trust_dirname );

	$this->_mime_handler  =& webphoto_mime_handler::getInstance(
		$dirname, $trust_dirname );

	$this->_config_class    =& webphoto_config::getInstance( $dirname );
	$this->_multibyte_class =& webphoto_multibyte::getInstance();

	$this->_cfg_work_dir  = $this->_config_class->get_by_name( 'workdir' );
	$this->_cfg_makethumb = $this->_config_class->get_by_name( 'makethumb' );

	$this->_TMP_DIR       = $this->_cfg_work_dir.'/tmp' ;

	$this->_constpref = strtoupper( '_P_' . $dirname. '_DEBUG_' ) ;
}

//---------------------------------------------------------
// check type
//---------------------------------------------------------
function is_ext( $ext )
{
	return false;
}

function is_ext_in_array( $ext, $array )
{
	if ( in_array( strtolower($ext), $array ) ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// excute
//---------------------------------------------------------
function execute( $method, $param )
{
	switch( $method )
	{
	case 'image':
		return $this->create_image( $param );
		break;

	case 'flv':
		return $this->create_flv( $param );
		break;

	case 'jpeg':
		return $this->create_jpeg( $param );
		break;

	case 'wav':
		return $this->create_wav( $param );
		break;

	case 'pdf':
		return $this->create_pdf( $param );
		break;

	case 'swf':
		return $this->create_swf( $param );
		break;

	case 'video_images':
		return $this->create_video_images( $param );
		break;

	case 'video_info':
		return $this->get_video_info( $param );
		break;

	case 'text_content':
		return $this->get_text_content( $param );
		break;

	case 'exif':
		return $this->get_exif( $param );
		break;
	}

	return null;
}

function create_image( $param )
{
	return null;
}

function create_video_images( $param )
{
	return null;
}

function create_flv( $param )
{
	return null;
}

function create_jpeg( $param )
{
	return null;
}

function create_wav( $param )
{
	return null;
}

function create_mp3( $param )
{
	return null;
}

function create_pdf( $param )
{
	return null;
}

function create_swf( $param )
{
	return null;
}

function get_video_info( $param )
{
	return null;
}

function get_text_content( $param )
{
	return null;
}

function get_exif( $param )
{
	return null;
}

//---------------------------------------------------------
// error 
//---------------------------------------------------------
function clear_error()
{
	$this->_errors = array();
}

function set_error( $errors )
{
	if ( is_array($errors) ) {
		foreach( $errors as $err ) {
			$this->_errors[] = $err ;
		}
	} else {
		$this->_errors[] = $errors ;
	}
}

function get_errors()
{
	return $this->_errors;
}

//---------------------------------------------------------
// mime handler
//---------------------------------------------------------
function get_cached_mime_type_by_ext( $ext )
{
	if ( isset( $this->_cached_mime_type_array[ $ext ] ) ) {
		return  $this->_cached_mime_type_array[ $ext ];
	}

	$row = $this->_mime_handler->get_cached_row_by_ext( $ext );
	if ( !is_array($row) ) {
		return false;
	}

	$mime_arr = $this->str_to_array( $row['mime_type'] , ' ' );
	if ( isset( $mime_arr[0] ) ) {
		$mime = $mime_arr[0];
		$this->_cached_mime_type_array[ $ext ] = $mime;
		return  $mime ;
	}

	return false;
}

function get_cached_mime_kind_by_ext( $ext )
{
	if ( isset( $this->_cached_mime_kind_array[ $ext ] ) ) {
		return  $this->_cached_mime_kind_array[ $ext ];
	}

	$row = $this->_mime_handler->get_cached_row_by_ext( $ext );
	if ( !is_array($row) ) {
		return false;
	}

	$kind = $row['mime_kind'];
	$this->_cached_mime_kind_array[ $ext ] = $kind;
	return $kind ;
}

function match_ext_kind( $ext, $kind )
{
	if ( $this->get_cached_mime_kind_by_ext( $ext ) == $kind ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// debug
//---------------------------------------------------------
function set_debug_by_name( $name )
{
	$const_name = strtoupper( $this->_constpref . $name ) ;

	if ( defined($const_name) ) {
		$val = constant($const_name);
		$this->set_flag_debug( $val );
	}
}

function set_flag_debug( $val )
{
	$this->_FLAG_DEBUG = (bool)$val ;
}

//---------------------------------------------------------
// set param 
//---------------------------------------------------------
function set_flag_chmod( $val )
{
	$this->_flag_chmod = (bool)$val ;
}

// --- class end ---
}

?>