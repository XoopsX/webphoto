<?php
// $Id: search.php,v 1.5 2008/08/25 19:28:05 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// table_photo -> table_item
// 2008-07-01 K.OHWADA
// used use_pathinfo
// used is_video_mime()
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_search
//=========================================================
class webphoto_inc_search extends webphoto_inc_handler
{
	var $_cfg_use_pathinfo = false;

	var $_FLAG_SUBSTITUTE = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_search()
{
	$this->webphoto_inc_handler();
	$this->set_normal_exts( _C_WEBPHOTO_IMAGE_EXTS );
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_search();
	}
	return $instance;
}

function _init( $dirname )
{
	$this->init_handler( $dirname );
	$this->_init_xoops_config( $dirname );

// preload
	$name = strtoupper( '_P_'. $dirname .'_SEARCH_SUBSTITUTE' );
	if ( defined( $name ) ) {
		$this->_FLAG_SUBSTITUTE = constant( $name );
	}
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function search( $dirname, $query_array, $andor, $limit, $offset, $uid )
{
	$this->_init( $dirname );

	$item_rows = $this->_get_item_rows( $query_array, $andor, $uid, $limit, $offset );
	if ( !is_array($item_rows) ) {
		return array(); 
	}

	$keywords = urlencode( implode(' ', $query_array) );

	$i   = 0;
	$ret = array();

	foreach( $item_rows as $item_row )
	{
		$item_id   = $item_row['item_id'];
		$item_kind = $item_row['item_kind'];
	
		$thumb_url    = null;
		$thumb_width  = 0;
		$thumb_height = 0;

		$thumb_row = $this->get_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_THUMB );

		if ( is_array($thumb_row) ) {
			$thumb_url    = $thumb_row['file_url'];
			$thumb_width  = $thumb_row['file_width'];
			$thumb_height = $thumb_row['file_height'];
		}

		if ( $this->_cfg_use_pathinfo ) {
			$link = 'index.php/photo/'. $item_id .'/keywords='. $keywords .'/' ;
		} else {
			$link = 'index.php?fct=photo&amp;p='. $item_id .'&amp;keywords='. $keywords ;
		}

		$arr['link']    = $link ;
		$arr['title']   = $item_row['item_title'];
		$arr['time']    = $item_row['item_time_update'];
		$arr['uid']     = $item_row['item_uid'];
		$arr['image']   = 'images/icons/search.png';
		$arr['context'] = $this->_build_context( $item_row, $query_array );

		$is_image = $this->is_image_kind( $item_kind );
		$is_video = $this->is_image_kind( $item_kind );

		// photo image
		if (( $is_image || $is_video || $this->_FLAG_SUBSTITUTE ) && 
		      $thumb_url ) {
			$arr['img_url']    = $thumb_url;
			$arr['img_width']  = $thumb_width;
			$arr['img_height'] = $thumb_height;
		}

		$ret[ $i ] = $arr;
		$i++;
	}

	return $ret;
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function _build_context( $row, $query_array )
{
	$str = $this->_build_desc_disp( $row );
	$str = preg_replace("/>/", '> ', $str);
	$str = strip_tags( $str );

// this function is defined in happy_linux module
	if ( function_exists('happy_linux_build_search_context') ) {
		$str = happy_linux_build_search_context( $str, $query_array );

// this function is defined in search module
	} elseif ( function_exists('search_make_context') ) {
		$str = search_make_context( $str, $query_array );
	}

	return $str;
}

function _build_desc_disp( $row )
{
	$myts =& MyTextSanitizer::getInstance();
	return $myts->displayTarea( $row['item_description'] , 0 , 1 , 1 , 1 , 1 , 1 );
}

//---------------------------------------------------------
// handler
//---------------------------------------------------------
function _get_item_rows( $query_array, $andor, $uid, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->prefix_dirname( 'item' );
	$sql .= ' WHERE '. $this->_build_where_for_search( $query_array, $andor, $uid );
	$sql .= ' ORDER BY item_time_update DESC, item_id DESC';
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function _build_where_for_search( $keyword_array, $andor, $uid )
{
	$where_key = $this->_build_where_by_keyword_array( $keyword_array, $andor );

	$where_uid = null;
	if ( $uid != 0 ) {
		$where_uid = 'item_uid='. intval($uid);
	}

	$where = null;
	if ( $where_key && $where_uid ) {
		$where = $where_key .' AND '. $where_uid ;
	} elseif ( $where_key ) {
		$where = $where_key;
	} elseif ( $where_uid ) {
		$where = $where_uid;
	}

	if ( $where ) {
		$where .= ' AND item_status > 0 ';
	}

	return $where;
}

function _build_where_by_keyword_array( $keyword_array, $andor='AND' )
{
	if ( !is_array($keyword_array) || !count($keyword_array) ) {
		return null;
	}

	switch ( strtolower($andor) )
	{
		case 'exact':
			$where = $this->_build_where_search_single( $keyword_array[0] );
			return $where;

		case 'or':
			$andor_glue = 'OR';
			break;

		case 'and':
		default:
			$andor_glue = 'AND';
			break;
	}

	$arr = array();

	foreach( $keyword_array as $keyword ) 
	{
		$keyword = trim($keyword);
		if ( $keyword ) {
			$arr[] = $this->_build_where_search_single( $keyword ) ;
		}
	}

	if ( is_array( $arr ) && count( $arr ) ) {
		$glue  = ' '. $andor_glue .' ';
		$where = ' ( '. implode( $glue , $arr ) .' ) ' ;
		return $where;
	}

	return null;
}

function _build_where_search_single( $str )
{
	$text = "item_search LIKE '%" . addslashes( $str ) . "%'" ;
	return $text;
}

//---------------------------------------------------------
// xoops_config
//---------------------------------------------------------
function _init_xoops_config( $dirname )
{
	$config_handler =& webphoto_inc_config::getInstance();
	$config_handler->init( $dirname );

	$this->_cfg_use_pathinfo = $config_handler->get_by_name('use_pathinfo');
}

// --- class end ---
}

?>