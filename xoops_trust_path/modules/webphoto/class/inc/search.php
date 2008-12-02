<?php
// $Id: search.php,v 1.9 2008/12/02 12:19:43 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-11-29 K.OHWADA
// build_show_file_image()
// 2008-10-01 K.OHWADA
// BUG : implode() : Bad arguments
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
	var $_cfg_workdir      = null;

	var $_SHOW_IMAGE = true ;
	var $_SHOW_ICON  = false ;

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
	$this->_auto_publish( $dirname );

// preload
	$name_image= strtoupper( '_P_'. $dirname .'_SEARCH_SHOW_IMAGE' );
	$name_icon = strtoupper( '_P_'. $dirname .'_SEARCH_SHOW_ICON' );

	if ( defined( $name_image ) ) {
		$this->_SHOW_IMAGE = constant( $name_image );
	}
	if ( defined( $name_icon ) ) {
		$this->_SHOW_ICON = constant( $name_icon );
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

// no query_array called by userinfo
	$keywords = null ;
	if ( is_array($query_array) ) {
		$keywords = urlencode( implode(' ', $query_array) );
	}

	$i   = 0;
	$ret = array();

	foreach( $item_rows as $item_row )
	{
		$item_id   = $item_row['item_id'];
		$item_kind = $item_row['item_kind'];

		$img_url    = null ;
		$img_width  = 0 ;
		$img_height = 0 ;

		$is_image  = $this->is_image_kind( $item_kind );
		$thumb_row = $this->get_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_THUMB );

		list( $thumb_url, $thumb_width, $thumb_height ) =
			$this->build_show_file_image( $thumb_row ) ;

		list( $icon_url, $icon_width, $icon_height ) =
			$this->build_show_icon_image( $item_row );

		if ( $is_image || $this->_SHOW_IMAGE ) {
			if ( $thumb_url ) {
				$img_url    = $thumb_url;
				$img_width  = $thumb_width;
				$img_height = $thumb_height;

			} elseif ( $this->_SHOW_ICON && $icon_url ) {
				$img_url    = $icon_url;
				$img_width  = $icon_width;
				$img_height = $icon_height;	
			}
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

		if ( $img_url ) {
			$arr['img_url']    = $img_url;
			$arr['img_width']  = $img_width;
			$arr['img_height'] = $img_height;
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
// auto publish
//---------------------------------------------------------
function _auto_publish( $dirname )
{
	$publish_class =& webphoto_inc_auto_publish::getInstance();
	$publish_class->init( $dirname );
	$publish_class->set_workdir( $this->_cfg_workdir );

	$publish_class->auto_publish();
}

//---------------------------------------------------------
// xoops_config
//---------------------------------------------------------
function _init_xoops_config( $dirname )
{
	$config_handler =& webphoto_inc_config::getInstance();
	$config_handler->init( $dirname );

	$this->_cfg_use_pathinfo = $config_handler->get_by_name('use_pathinfo');
	$this->_cfg_workdir      = $config_handler->get_by_name( 'workdir' );
}

// --- class end ---
}

?>