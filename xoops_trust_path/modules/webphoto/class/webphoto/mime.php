<?php
// $Id: mime.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// added is_video_ext()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_mime
//=========================================================
class webphoto_mime
{
	var $_mime_handler ;
	var $_utility_class ;
	var $_xoops_class ;

	var $_cached_mime_array = array();

	var $_VIDEO_MEDIUM = 'video';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_mime( $dirname )
{
	$this->_mime_handler  =& webphoto_mime_handler::getInstance( $dirname );
	$this->_utility_class =& webphoto_lib_utility::getInstance();
	$this->_xoops_class   =& webphoto_xoops_base::getInstance();

}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_mime( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// get mime type
//---------------------------------------------------------
function get_my_allowed_mimes( $limit=0, $offset=0 )
{
	$type_arr = array();
	$ext_arr  = array();

	$rows = $this->_mime_handler->get_rows_by_mygroups(
		$this->_xoops_class->get_my_user_groups(), $limit, $offset );

	if ( !is_array($rows) || !count($rows) ) {
		return false;
	}

	foreach ( $rows as $row )
	{
		$mime_ext  = $row['mime_ext'];
		$mime_type = $row['mime_type'];

		$ext_arr[] = $mime_ext;

		$temp_arr = $this->str_to_array( $mime_type , ' ' );
		if ( !is_array($temp_arr) || !count($temp_arr) ) { continue; }

		foreach ( $temp_arr as $type ) {
			$type_arr[] = $type;
		}

		$this->_cached_mime_array[ $mime_ext ] = $temp_arr[0];
	}

	$type_arr = array_unique( $type_arr );
	$ext_arr  = array_unique( $ext_arr );

	return array( $type_arr, $ext_arr );
}

function get_cached_mime_type_by_ext( $ext )
{
	if ( isset( $this->_cached_mime_array[ $ext ] ) ) {
		return  $this->_cached_mime_array[ $ext ];
	}

	$row = $this->_mime_handler->get_cached_row_by_ext( $ext );
	if ( !is_array($row) ) {
		return false;
	}

	$mime_arr = $this->str_to_array( $row['mime_type'] , ' ' );
	if ( isset( $mime_arr[0] ) ) {
		$mime = $mime_arr[0];
		$this->_cached_mime_array[ $ext ] = $mime;
		return  $mime ;
	}

	return false;
}

//---------------------------------------------------------
// add mime type
//---------------------------------------------------------
function add_mime_to_info_if_empty( $info, $mime_in=null )
{
	$medium = null ;

// no image  info
	if ( !is_array($info) || !count($info) ) {
		return $info;
	}

// if set mime
	if ( $info['photo_cont_mime'] ) {
		return $info;
	}

// if not set mime
	if ( $mime_in ) {
		$mime = $mime_in ;
	} else {
		$mime = $this->get_cached_mime_type_by_ext( $info['photo_cont_ext'] );
	}

// set mime
	if ( $mime ) {
		$info['photo_file_mime'] = $mime ;
		$info['photo_cont_mime'] = $mime ;

// if video type
		if ( $this->is_video_mime( $mime ) ) {
			$medium = $this->_VIDEO_MEDIUM ;
		}
	}
			
// set medium
	if ( $medium ) {
		$info['photo_file_medium'] = $medium ;
		$info['photo_cont_medium'] = $medium ;
	}

	return $info;
}

function is_video_ext( $ext )
{
	$mime = $this->get_cached_mime_type_by_ext( $ext );
	return $this->is_video_mime( $mime );
}

function is_video_mime( $mime )
{
	if ( preg_match('/^video/', $mime ) ) {
		return true;
	}
	return false;
}

function get_video_medium()
{
	return $this->_VIDEO_MEDIUM ;
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function str_to_array( $str, $pattern )
{
	return $this->_utility_class->str_to_array( $str, $pattern );
}

// --- class end ---
}

?>