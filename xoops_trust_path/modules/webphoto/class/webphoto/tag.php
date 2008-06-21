<?php
// $Id: tag.php,v 1.1 2008/06/21 12:22:22 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;


//=========================================================
// class webphoto_tag
//=========================================================
class webphoto_tag extends webphoto_lib_error
{
	var $_tag_handler;
	var $_p2t_handler;
	var $_photo_tag_handler;
	var $_utility_class;

	var $_is_japanese = false;

	var $_tag_id_array = null;

	var $_DIRNAME;
	var $_MODULE_URL;
	var $_MODULE_DIR;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_tag( $dirname )
{
	$this->webphoto_lib_error();

	$this->_tag_handler       =& webphoto_tag_handler::getInstance(   $dirname );
	$this->_p2t_handler       =& webphoto_p2t_handler::getInstance(   $dirname );
	$this->_photo_tag_handler =& webphoto_photo_tag_handler::getInstance( $dirname );
	$this->_utility_class     =& webphoto_lib_utility::getInstance();

	$this->_DIRNAME    = $dirname ;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'. $dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'. $dirname;

}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_tag( $dirname );
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

// delete no-use linkage
		$ret = $this->_p2t_handler->delete_by_photoid_uid_tagid_array( $photo_id, $uid, $tags );
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
// tag cloud
//---------------------------------------------------------
function build_tagcloud( $limit=0, $offset=0  )
{
	$show     = false;
	$tagcloud = null;

	$rows = $this->get_tag_rows_with_count( 'tag_id', $limit, $offset );
	if ( is_array($rows) ) {
		$tagcloud = $this->build_tagcloud_by_rows( $rows );
		if ( is_array($tagcloud) && count($tagcloud) ) {
			$show = true;
		}
	}

	$arr = array(
		'show_tagcloud' => $show,
		'tagcloud'      => $tagcloud,
	);
	return $arr;
}

function build_tagcloud_by_rows( $rows )
{
	if ( !is_array($rows) || !count($rows) ) {
		return array();
	}

	$cloud_class =& new webphoto_lib_cloud();

	ksort($rows);

	foreach ( array_keys($rows) as $i )
	{
		$name  = $rows[$i]['tag_name'];
		$count = $rows[$i]['photo_count'];
		$link  = $this->_MODULE_URL .'/index.php/tag/'. $this->url_encode( $name ) .'/';
		$cloud_class->addElement( $name,  $link, $count );
	}

	$ret = $cloud_class->build();
	return $ret;
}

//---------------------------------------------------------
// for index.php
//---------------------------------------------------------
function get_photo_count_public_by_tag( $tag )
{
	return $this->_photo_tag_handler->get_photo_count_public_by_tag( $tag );
}

function get_photo_id_array_public_latest_by_tag_orderby( $tag, $orderby, $limit=0, $offset=0 )
{
	return $this->_photo_tag_handler->get_photo_id_array_public_latest_by_tag_orderby(
		$tag, $orderby, $limit, $offset );
}

function get_tag_rows_with_count( $key='tag_id', $limit=0, $offset=0 )
{
	return $this->_photo_tag_handler->get_tag_rows_with_count( $key, $limit, $offset );
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
		$row['urlencoded'] = $this->url_encode( $tag_name );
		$arr[] = $row;
	}
	return $arr;
}

function url_encode( $str )
{
	return rawurlencode( $this->encode_tag( $str ) );
}

function encode_tag( $str )
{
	return $this->_utility_class->encode_slash( $str );
}

function decode_tag( $str )
{
	return $this->_utility_class->decode_slash( $str );
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