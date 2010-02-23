<?php
// $Id: mime.php,v 1.10 2010/02/23 01:10:59 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-02-20 K.OHWADA
// get_my_allowed_ext_array_groupby_kind()
// 2009-11-11 K.OHWADA
// webphoto_base_ini
// 2009-10-25 K.OHWADA
// get_cached_mime_kind_by_ext()
// 2008-10-01 K.OHWADA
// get_image_mimes()
// 2008-08-24 K.OHWADA
// added ext_to_kind()
// 2008-08-01 K.OHWADA
// added get_allowed_mimes_by_groups() is_my_allow_mime()
// 2008-07-01 K.OHWADA
// added is_video_ext()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_mime
//=========================================================
class webphoto_mime extends webphoto_base_ini
{
	var $_mime_handler ;

	var $_cached_my_allowed_mimes = null;
	var $_cached_kind_array = array();
	var $_cached_mime_array = array();
	var $_cached_mime_options_array = array();

	var $_mime_kind_image_array;
	var $_mime_kind_video_array;
	var $_mime_kind_audio_array;
	var $_mime_kind_office_array;
	var $_mime_kind_image_other_array;

	var $_IMAGE_MEDIUM = 'image' ;
	var $_VIDEO_MEDIUM = 'video' ;
	var $_AUDIO_MEDIUM = 'audio' ;

	var $_MIME_OPTION_DELMITA_1 = ';';
	var $_MIME_OPTION_DELMITA_2 = ':';

// asx is meta file (text)
	var $_EXT_ASX = 'asx';

	var $_IMAGE_EXTS;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_mime( $dirname, $trust_dirname )
{
	$this->webphoto_base_ini( $dirname, $trust_dirname );

	$this->_mime_handler  =& webphoto_mime_handler::getInstance(
		$dirname, $trust_dirname );

	$this->_mime_kind_image_array  = $this->explode_ini( 'mime_kind_list_image' );
	$this->_mime_kind_video_array  = $this->explode_ini( 'mime_kind_list_video' );
	$this->_mime_kind_audio_array  = $this->explode_ini( 'mime_kind_list_audio' );
	$this->_mime_kind_office_array = $this->explode_ini( 'mime_kind_list_office' );
	$this->_mime_kind_image_other_array = $this->explode_ini( 'mime_kind_list_image_other' );

	$this->_item_kind_image_array  = $this->explode_ini('item_kind_list_image');
	$this->_item_kind_video_array  = $this->explode_ini('item_kind_list_video');
	$this->_item_kind_audio_array  = $this->explode_ini('item_kind_list_audio');
	$this->_item_kind_office_array = $this->explode_ini('item_kind_list_office');

	$this->_IMAGE_EXTS = explode( '|', _C_WEBPHOTO_IMAGE_EXTS );
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_mime( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// get image mime type
//---------------------------------------------------------
function get_image_exts()
{
	return $this->_IMAGE_EXTS ;
}

function get_image_mimes( $limit=0, $offset=0 )
{
	$type_arr = array();

	$rows = $this->_mime_handler->get_rows_by_exts(
		$this->_IMAGE_EXTS, $limit, $offset );

	if ( !is_array($rows) || !count($rows) ) {
		return false;
	}

	foreach ( $rows as $row )
	{
		$mime_type = $row['mime_type'];

		$temp_arr = $this->str_to_array( $mime_type , ' ' );
		if ( !is_array($temp_arr) || !count($temp_arr) ) {
			continue; 
		}

		foreach ( $temp_arr as $type ) {
			$type_arr[] = $type;
		}
	}

	$type_arr = array_unique( $type_arr );

	return $type_arr ;
}

//---------------------------------------------------------
// get ext type
//---------------------------------------------------------
function get_my_item_kind_group_array( $limit=0, $offset=0 )
{
	$ext_array = $this->get_my_item_kind_group_array_groupby_item_kind( $limit, $offset );

	$arr = array();
	foreach ( $ext_array as $k => $v )
	{
		if ( !is_array($v) || !count($v) ) {
			continue;
		}

		$kind = $this->build_item_kind_group( $k );
		if ( isset( $arr[ $kind ] ) ) {
			$arr[ $kind ] = array_merge( $arr[ $kind ] , $v );
		} else {
			$arr[ $kind ] = $v;
		}
	}
	ksort( $arr, SORT_NUMERIC );
	return $arr;
}

function build_item_kind_group( $k )
{
	$str = '';
	if ( in_array( $k, $this->_item_kind_image_array ) ) {
		$str = _C_WEBPHOTO_ITEM_KIND_GROUP_IMAGE ;
	} elseif ( in_array( $k, $this->_item_kind_video_array ) ) {
		$str = _C_WEBPHOTO_ITEM_KIND_GROUP_VIDEO ;
	} elseif ( in_array( $k, $this->_item_kind_audio_array ) ) {
		$str = _C_WEBPHOTO_ITEM_KIND_GROUP_AUDIO ;
	} elseif ( in_array( $k, $this->_item_kind_office_array ) ) {
		$str = _C_WEBPHOTO_ITEM_KIND_GROUP_OFFICE ;
	} else {
		$str = _C_WEBPHOTO_ITEM_KIND_GROUP_OTHERS ;
	}
	return $str;
}

function get_item_kind_group_name( $kind )
{
	$str = '';
	switch ( $kind )
	{
		case _C_WEBPHOTO_ITEM_KIND_GROUP_IMAGE :
			$str = $this->get_constant('ITEM_KIND_GROUP_IMAGE');
			break;

		case _C_WEBPHOTO_ITEM_KIND_GROUP_VIDEO :
			$str = $this->get_constant('ITEM_KIND_GROUP_VIDEO');
			break;

		case _C_WEBPHOTO_ITEM_KIND_GROUP_AUDIO :
			$str = $this->get_constant('ITEM_KIND_GROUP_AUDIO');
			break;

		case _C_WEBPHOTO_ITEM_KIND_GROUP_OFFICE :
			$str = $this->get_constant('ITEM_KIND_GROUP_OFFICE');
			break;

		case _C_WEBPHOTO_ITEM_KIND_GROUP_OTHERS :
		default:
			$str = $this->get_constant('ITEM_KIND_GROUP_OTHERS');
			break;
	}
	return $str;
}

function get_item_kind_group_value( $v, $glue=' ' )
{
	return implode( $glue, $v );
}

function get_my_item_kind_group_array_groupby_item_kind( $limit=0, $offset=0 )
{
	$rows = $this->_mime_handler->get_rows_by_mygroups(
		$this->_xoops_groups, $limit, $offset );

	if ( !is_array($rows) || !count($rows) ) {
		return false;
	}

	$arr  = array();

	foreach ( $rows as $row )
	{
		$ext  = $row['mime_ext'];
		$kind = $this->ext_to_kind( $ext );

		$arr[ $kind ][] = $ext;
	}
	return $arr;
}

//---------------------------------------------------------
// get mime type
//---------------------------------------------------------
function get_cached_my_allowed_mimes()
{
	if ( is_array( $this->_cached_my_allowed_mimes ) ) {
		    return $this->_cached_my_allowed_mimes;
	}

	$ret = $this->get_my_allowed_mimes();
	$this->_cached_my_allowed_mimes = $ret;

	return $ret;
}

function get_my_allowed_mimes( $limit=0, $offset=0 )
{
	return $this->get_allowed_mimes_by_groups(
		$this->_xoops_groups, $limit, $offset );
}

function get_allowed_mimes_by_groups( $groups, $limit=0, $offset=0 )
{
	$type_arr = array();
	$ext_arr  = array();

	$rows = $this->_mime_handler->get_rows_by_mygroups(
		$groups, $limit, $offset );

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

function get_cached_mime_kind_by_ext( $ext )
{
	$row = $this->_mime_handler->get_cached_row_by_ext( $ext );
	if ( !is_array($row) ) {
		return false;
	}
	return $row['mime_kind'];
}

function get_cached_mime_options_by_ext( $ext )
{
	if ( isset( $this->_cached_mime_options_array[ $ext ] ) ) {
		return  $this->_cached_mime_options_array[ $ext ];
	}

	$opt_arr = array();

	$row = $this->_mime_handler->get_cached_row_by_ext( $ext );
	if ( !is_array($row) ) {
		return false;
	}

	$arr1 = $this->str_to_array( $row['mime_option'] , $this->_MIME_OPTION_DELMITA_1 );
	if ( !is_array($arr1) || !count($arr1) ) {
		return null;
	}

	foreach ($arr1 as $opt)
	{
		$arr2 = $this->str_to_array( $opt , $this->_MIME_OPTION_DELMITA_2 );
		if ( isset($arr2[0]) && isset($arr2[1]) ) {
			$opt_arr[ $arr2[0] ] = $arr2[1];
		}
	}

	$this->_cached_mime_options_array[ $ext ] = $opt_arr;
	return $opt_arr;
}

//---------------------------------------------------------
// judge mime type
//---------------------------------------------------------
function ext_to_kind( $ext )
{
	if ( isset( $this->_cached_kind_array[ $ext ] ) ) {
		return  $this->_cached_kind_array[ $ext ];
	}

	$kind = _C_WEBPHOTO_ITEM_KIND_UNDEFINED ;
	if ( $this->is_mime_ext_image( $ext ) ) {
		$kind = _C_WEBPHOTO_ITEM_KIND_IMAGE ;
	} elseif ( $this->is_mime_ext_image_other( $ext ) ) {
		$kind = _C_WEBPHOTO_ITEM_KIND_IMAGE_OTHER ;
	} elseif ( $this->is_mime_ext_video( $ext ) ) {
		$kind = _C_WEBPHOTO_ITEM_KIND_VIDEO ;
	} elseif ( $this->is_mime_ext_audio( $ext ) ) {
		$kind = _C_WEBPHOTO_ITEM_KIND_AUDIO ;
	} elseif ( $this->is_mime_ext_office( $ext ) ) {
		$kind = _C_WEBPHOTO_ITEM_KIND_OFFICE ;
	} elseif ( $ext != '' ) {
		$kind = _C_WEBPHOTO_ITEM_KIND_GENERAL ;
	}
	$this->_cached_kind_array[ $ext ] = $kind;
	return $kind ;
}

function is_mime_ext_image( $ext )
{
	return $this->match_mime_ext_in_kind_array( 
		$ext, $this->_mime_kind_image_array );
}

function is_mime_ext_image_other( $ext )
{
	return $this->match_mime_ext_in_kind_array( 
		$ext, $this->_mime_kind_image_other_array );
}

function is_mime_ext_video( $ext )
{
	return $this->match_mime_ext_in_kind_array( 
		$ext, $this->_mime_kind_video_array );
}

function is_mime_ext_audio( $ext )
{
	return $this->match_mime_ext_in_kind_array( 
		$ext, $this->_mime_kind_audio_array );
}

function is_mime_ext_office( $ext )
{
	return $this->match_mime_ext_in_kind_array( 
		$ext, $this->_mime_kind_office_array );
}

function match_mime_ext_in_kind_array( $ext, $kind_array )
{
	foreach ( $kind_array as $kind )
	{
		if ( $this->get_cached_mime_kind_by_ext( $ext ) == $kind ) {
			return true;
		}
	}
	return false;
}

function ext_to_mime( $ext )
{
	return $this->get_cached_mime_type_by_ext( $ext );
}

function ext_to_mime_kind( $ext )
{
	return $this->get_cached_mime_kind_by_ext( $ext );
}

function mime_to_medium( $mime )
{
	$medium = '' ;
	if ( $this->is_image_mime( $mime ) ) {
		$medium = $this->_IMAGE_MEDIUM ;
	} elseif ( $this->is_video_mime( $mime ) ) {
		$medium = $this->_VIDEO_MEDIUM ;
	}
	return $medium ;
}

function is_normal_ext( $ext )
{
	return $this->is_image_ext( $ext );
}

function is_image_ext( $ext )
{
	if ( in_array( strtolower( $ext ) , $this->_IMAGE_EXTS ) ) {
		return true;
	}
	return false;
}

function is_video_ext( $ext )
{
	if ( $ext == $this->_EXT_ASX ) {
		return false;
	}
	return $this->is_video_mime( 
		$this->ext_to_mime( $ext ) );
}

function is_audio_ext( $ext )
{
	return $this->is_audio_mime( 
		$this->ext_to_mime( $ext ) );
}

function is_image_mime( $mime )
{
	if ( preg_match('/^image/', $mime ) ) {
		return true;
	}
	return false;
}

function is_video_mime( $mime )
{
	if ( preg_match('/^video/', $mime ) ) {
		return true;
	}
	return false;
}

function is_audio_mime( $mime )
{
	if ( preg_match('/^audio/', $mime ) ) {
		return true;
	}
	return false;
}

function get_image_medium()
{
	return $this->_IMAGE_MEDIUM ;
}

function get_video_medium()
{
	return $this->_VIDEO_MEDIUM ;
}

function get_audio_medium()
{
	return $this->_AUDIO_MEDIUM ;
}

//---------------------------------------------------------
// is my allow mime
//---------------------------------------------------------
function is_my_allow_mime( $mime )
{
	list ( $allowed_mimes, $allowed_exts ) 
		= $this->get_cached_my_allowed_mimes();

	if ( $mime && in_array( strtolower($mime), $allowed_mimes ) ) {
		return true;
	}
	return false;
}

function is_my_allow_ext( $ext )
{
	list ( $allowed_mimes, $allowed_exts ) 
		= $this->get_cached_my_allowed_mimes();

	if ( $ext && in_array( strtolower($ext), $allowed_exts ) ) {
		return true;
	}
	return false;
}

// --- class end ---
}

?>