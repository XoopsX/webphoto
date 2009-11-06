<?php
// $Id: ext_base.php,v 1.4 2009/11/06 18:04:17 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-10-25 K.OHWADA
// get_cached_mime_kind_by_ext()
// 2009-01-25 K.OHWADA
// create_swf()
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ext_base
//=========================================================
class webphoto_ext_base
{
	var $_xoops_class;
	var $_utility_class;
	var $_mime_handler;
	var $_config_class;
	var $_multibyte_class;

	var $_is_japanese;
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
function webphoto_ext_base( $dirname )
{
	$this->_xoops_class   =& webphoto_xoops_base::getInstance();
	$this->_utility_class =& webphoto_lib_utility::getInstance();
	$this->_mime_handler  =& webphoto_mime_handler::getInstance( $dirname );
	$this->_config_class  =& webphoto_config::getInstance( $dirname );

	$this->_is_japanese = $this->_xoops_class->is_japanese( _C_WEBPHOTO_JPAPANESE ) ;

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
// create image
//---------------------------------------------------------
function create_image( $param )
{
	return false;
}

//---------------------------------------------------------
// create jpeg
//---------------------------------------------------------
function create_jpeg( $param )
{
	return 0 ;	// no action
}

//---------------------------------------------------------
// create mp3
//---------------------------------------------------------
function create_mp3( $param )
{
	return 0 ;	// no action
}

//---------------------------------------------------------
// create pdf
//---------------------------------------------------------
function create_pdf( $param )
{
	return 0 ;	// no action
}

//---------------------------------------------------------
// create swf
//---------------------------------------------------------
function create_swf( $param )
{
	return 0 ;	// no action
}

//---------------------------------------------------------
// duration
//---------------------------------------------------------
function get_duration_size( $param )
{
	return false;
}

//---------------------------------------------------------
// text content
//---------------------------------------------------------
function get_text_content( $param )
{
	return false;
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

	$mime_arr = $this->_utility_class->str_to_array( $row['mime_type'] , ' ' );
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