<?php
// $Id: uri_parse.php,v 1.1 2010/11/04 02:24:16 ohwada Exp $

//=========================================================
// webphoto module
// 2010-11-03 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_uri_parse
//=========================================================
class webphoto_uri_parse
{
	var $_sort_class ;
	var $_pathinfo_class ;
	var $_xoops_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_uri_parse( $dirname, $trust_dirname )
{
	$this->_sort_class 
		=& webphoto_photo_sort::getInstance( $dirname, $trust_dirname );

	$this->_xoops_class    =& webphoto_xoops_base::getInstance();
	$this->_pathinfo_class =& webphoto_lib_pathinfo::getInstance();
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_uri_parse( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// factory
//---------------------------------------------------------
function get_page_mode()
{
	$input = $this->_pathinfo_class->get_fct_op_0();
	return $this->_sort_class->input_to_mode( $input );
}

function get_get_page()
{
	return $this->_pathinfo_class->get_page() ;
}

function get_get_sort()
{
	return $this->_sort_class->get_photo_sort_name(
		$this->_pathinfo_class->get_text('sort') );
}

function get_get_kind()
{
	return $this->_sort_class->get_photo_kind_name( 
		$this->_pathinfo_class->get_text('kind') );
}

function get_sort_orderby()
{
	$get_sort = $this->_sort_class->get_photo_sort_name(
		$this->_pathinfo_class->get_text('sort') );
	$sort    = $this->_sort_class->get_photo_sort_name( $get_sort, true );
	$orderby = $this->_sort_class->sort_to_orderby( $sort );

	$arr = array(
		'get_sort' => $get_sort ,
		'sort'     => $sort ,
		'orderby'  => $orderby ,
	);
	return $arr;
}

function get_param_by_mode( $mode )
{
	$input = $this->get_pathinfo_param();
	$isset = $this->isset_pathinfo_param();
	$path_second = $this->get_pathinfo_path_second();

	$second = $this->get_second( $input, $path_second );
	$uid    = $this->get_uid( $input, $isset, $path_second );
	$cat_id = $this->get_id_by_key( 'cat_id' );
	$my_uid = $this->_xoops_class->get_my_user_uid() ;

	return $this->_sort_class->input_to_param( 
		$mode, $input, $second, $cat_id, $uid, $my_uid );
}

function get_uid( $input, $isset, $path_second )
{
	$uid = _C_WEBPHOTO_UID_DEFAULT;	// not set
	if ( $isset ) {
		$uid = $input;
	} elseif ( !$isset && ( $path_second !== false ) ) {
		$uid = intval($path_second);
	}
	return $uid;
}

function get_second( $input, $path_second )
{
	if ( $input ) {
		$ret = $input;
	} else {
		$ret = $path_second;
	}
	return $ret;
}

//---------------------------------------------------------
// photo
//---------------------------------------------------------
function get_photo_orderby()
{
	return $this->_sort_class->sort_to_orderby( 
		$this->get_by_key( 'order' ) );
}

function get_photo_keyword_array()
{
	$keywords = $this->_pathinfo_class->get_text( 'keywords' );
	$arr = preg_split("/[\s|\+]/", $keywords);
	return array_unique($arr);
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function get_id_by_key( $id_name )
{
// POST
	$id = isset($_POST[ $id_name ]) ? intval($_POST[ $id_name ]) : 0 ;
	if ( $id > 0 ) { return $id; }

// GET
	$id = isset($_GET[ $id_name ]) ? intval($_GET[ $id_name ]) : 0 ;
	if ( $id > 0 ) { return $id; }

// PATH_INFO
	$id = intval( $this->get_pathinfo_param() );
	if ( $id > 0 ) { return $id; }

	$id = intval( $this->get_pathinfo_path_second() );
	return $id;
}

function isset_pathinfo_param()
{
	return $this->_pathinfo_class->isset_param( _C_WEBPHOTO_URI_PARAM_NAME );
}

function get_pathinfo_param()
{
	return $this->_pathinfo_class->get( _C_WEBPHOTO_URI_PARAM_NAME );
}

function get_pathinfo_path_second()
{
	return $this->_pathinfo_class->get_path( _C_WEBPHOTO_URI_PATH_SECOND );
}

function get_by_key( $key )
{
	return $this->_pathinfo_class->get( $key );
}

function get_int_by_key( $key )
{
	return $this->_pathinfo_class->get_int( $key );
}

// --- class end ---
}

?>