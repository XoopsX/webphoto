<?php
// $Id: tag_build.php,v 1.2 2011/11/04 15:08:24 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-03 K.OHWADA
// update_tags_admin()
// 2010-01-10 K.OHWADA
// webphoto_tag -> webphoto_tag_build
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_tag_handler
// 2008-12-12 K.OHWADA
// move build_tagcloud() to webphoto_inc_tag
// 2008-07-01 K.OHWADA
// used uri_class
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_tag_build
//=========================================================
class webphoto_tag_build extends webphoto_lib_error
{
	var $_tag_handler;
	var $_p2t_handler;
	var $_photo_tag_handler;
	var $_utility_class;
	var $_uri_class;

	var $_is_japanese = false;

	var $_tag_id_array = null;

	var $_DIRNAME;
	var $_MODULE_URL;
	var $_MODULE_DIR;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_tag_build( $dirname, $trust_dirname )
{
	$this->webphoto_lib_error();

	$this->_tag_handler       
		=& webphoto_tag_handler::getInstance(   $dirname, $trust_dirname );
	$this->_p2t_handler       
		=& webphoto_p2t_handler::getInstance(   $dirname, $trust_dirname );
	$this->_photo_tag_handler 
		=& webphoto_photo_tag_handler::getInstance( $dirname, $trust_dirname );

	$this->_utility_class     =& webphoto_lib_utility::getInstance();
	$this->_uri_class         =& webphoto_uri::getInstance( $dirname );

	$this->_DIRNAME    = $dirname ;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'. $dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'. $dirname;

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_tag_build( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// tag handler
//---------------------------------------------------------
function update_tags( $photo_id, $uid, $tag_name_array )
{
// get user's tags
	$old_array = $this->_p2t_handler->get_tag_id_array_by_photoid_uid( $photo_id, $uid );

	$this->add_tags(    $photo_id, $uid, $tag_name_array );
	$this->delete_tags( $photo_id, $uid, $old_array, $this->_tag_id_array );

	return $this->return_code();
}

function update_tags_admin( $photo_id, $uid, $tag_name_array )
{
// get user's tags
	$old_array = $this->_p2t_handler->get_tag_id_array_by_photoid( $photo_id );

	$this->add_tags(          $photo_id, $uid, $tag_name_array );
	$this->delete_tags_admin( $photo_id, $old_array, $this->_tag_id_array );

	return $this->return_code();
}

function add_tags( $photo_id, $uid, $tag_name_array )
{
	if ( !is_array($tag_name_array) || !count($tag_name_array) ) {
		return true; // no action
	}

	$arr = array();

	foreach ( $tag_name_array as $tag_name )
	{

// check exist tag
		$tag_row = $this->_tag_handler->get_row_by_name( $tag_name );

// already exists
		if ( isset(   $tag_row['tag_id'] ) ) {
			$tag_id = $tag_row['tag_id'];

// add new tag
		} else {
			$tag_row = $this->_tag_handler->create( true );
			$tag_row['tag_name'] = $tag_name;

			$tag_id = $this->_tag_handler->insert( $tag_row );
			if ( !$tag_id ) {
				$this->set_error( $this->_tag_handler->get_errors() );
			}
		}

		if ( empty($tag_id) ) { continue; }

		$arr[] = $tag_id;

// check exist all user's linkage
		$p2t_count = $this->_p2t_handler->get_count_by_photoid_tagid( $photo_id, $tag_id );
		if ( $p2t_count > 0 ) { continue; }

// add new linkage
		$p2t_row = $this->_p2t_handler->create( true );
		$p2t_row['p2t_photo_id'] = $photo_id;
		$p2t_row['p2t_tag_id']   = $tag_id;
		$p2t_row['p2t_uid']      = $uid;

		$ret = $this->_p2t_handler->insert( $p2t_row );
		if ( !$ret ) {
			$this->set_error( $this->_p2t_handler->get_errors() );
		}
	}

// save id_array
	$this->_tag_id_array = $arr;

	return $this->return_code();
}

function delete_tags( $photo_id, $uid, $old_array, $new_array )
{
	$tags = $this->build_delete_tags( $old_array, $new_array );
	if ( is_array($tags) && count($tags) ) {
		$ret = $this->_p2t_handler->delete_by_photoid_uid_tagid_array( 
			$photo_id, $uid, $tags );
		if ( !$ret ) {
			$this->set_error( $this->_p2t_handler->get_errors() );
		}
	}
	return $this->return_code();
}

function delete_tags_admin( $photo_id, $old_array, $new_array )
{
	$tags = $this->build_delete_tags( $old_array, $new_array );
	if ( is_array($tags) && count($tags) ) {
		$ret = $this->_p2t_handler->delete_by_photoid_tagid_array( 
			$photo_id, $tags );
		if ( !$ret ) {
			$this->set_error( $this->_p2t_handler->get_errors() );
		}
	}
	return $this->return_code();
}

function build_delete_tags( $old_array, $new_array )
{
	if ( !is_array($old_array) || !count($old_array) ) {
		return null;
	}

	if ( !is_array($new_array) || !count($new_array) ) {
		return $old_array;
	}

	$arr = array();
	foreach ( $old_array as $id )
	{
// check not exist in new
		if ( !in_array( $id, $new_array ) ) {
			$arr[] = $id;
		}
	}
	return $arr;
}

//---------------------------------------------------------
// for main_photo.php
//---------------------------------------------------------
function build_tags_for_photo( $photo_id, $uid )
{
	$arr = $this->get_tag_name_array_by_photoid_uid( $photo_id, $uid );
	if ( is_array($arr) && count($arr) ) {
		return $this->tag_name_array_to_str( $arr );
	}
	return null;
}

//---------------------------------------------------------
// for show photo
//---------------------------------------------------------
function get_tag_name_array_by_photoid( $photo_id )
{
	$id_array = $this->_p2t_handler->get_tag_id_array_by_photoid( $photo_id );
	if ( !is_array($id_array) || !count($id_array) ) {
		return null;
	}
	return $this->build_tag_name_array_by_id_array( $id_array );
}

function get_tag_name_array_by_photoid_uid( $photo_id, $uid )
{
	$id_array = $this->_p2t_handler->get_tag_id_array_by_photoid_uid( $photo_id, $uid );
	if ( !is_array($id_array) || !count($id_array) ) {
		return null;
	}
	return $this->build_tag_name_array_by_id_array( $id_array );
}

function get_tag_name_array_by_photoid_without_uid( $photo_id, $uid )
{
	$id_array = $this->_p2t_handler->get_tag_id_array_by_photoid_without_uid( $photo_id, $uid );
	if ( !is_array($id_array) || !count($id_array) ) {
		return null;
	}
	return $this->build_tag_name_array_by_id_array( $id_array );
}

function build_tag_name_array_by_id_array( $id_array )
{
	if ( !is_array($id_array) || !count($id_array) ) {
		return null;
	}

	$arr = array();
	foreach ( $id_array as $id )
	{
		$row = $this->_tag_handler->get_cached_row_by_id( $id );
		$arr[] = $row['tag_name'];
	}
	return $arr;
}

function build_show_tags_from_tag_name_array( $tag_name_array )
{
	if ( !is_array($tag_name_array) || !count($tag_name_array) ) {
		return array(); 
	}

	$arr = array();
	foreach ( $tag_name_array as $tag_name )
	{
		$row = array();
		$row['tag_name']   = $this->sanitize( $tag_name );
		$row['tag_name_s'] = $this->sanitize( $tag_name );
		$row['urlencoded'] = $this->_uri_class->rawurlencode_encode_str( $tag_name );
		$arr[] = $row;
	}
	return $arr;
}

function decode_tag( $str )
{
	return $this->_uri_class->decode_str( $str );
}

//---------------------------------------------------------
// for submit.php edit.php
//---------------------------------------------------------
function tag_name_array_to_str( $arr )
{
	return $this->_utility_class->array_to_str( $arr, _C_WEBPHOTO_TAG_SEPARATOR.' ' );
}

//---------------------------------------------------------
// for Japanese
//---------------------------------------------------------
function str_to_tag_name_array( $str )
{
	if ( $this->_is_japanese ) {
		$str = str_replace( _WEBPHOTO_JA_DOKUTEN, _C_WEBPHOTO_TAG_SEPARATOR, $str );
		$str = str_replace( _WEBPHOTO_JA_COMMA,   _C_WEBPHOTO_TAG_SEPARATOR, $str );
	}

	return $this->_utility_class->str_to_array( $str, _C_WEBPHOTO_TAG_SEPARATOR ) ;
}

function set_is_japanese( $val )
{
	$this->_is_japanese = (bool)$val;
}

// --- class end ---
}

?>