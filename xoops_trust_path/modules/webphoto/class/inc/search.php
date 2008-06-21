<?php
// $Id: search.php,v 1.1 2008/06/21 12:22:26 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_search
//=========================================================
class webphoto_inc_search extends webphoto_inc_handler
{
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

// preload
	$name = strtoupper( '_C_'. $dirname .'_SEARCH_SUBSTITUTE' );
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

	$rows = $this->_get_photo_rows( $query_array, $andor, $uid, $limit, $offset );
	if ( !is_array($rows) ) { return array(); }

	$keywords = urlencode( implode(' ', $query_array) );

	$i   = 0;
	$ret = array();

	foreach( $rows as $row )
	{
		$arr['link']    = 'index.php?fct=photo&amp;photo_id='. $row['photo_id'] .'&amp;keywords='. $keywords;
		$arr['title']   = $row['photo_title'];
		$arr['time']    = $row['photo_time_update'];
		$arr['uid']     = $row['photo_uid'];
		$arr['image']   = 'images/icons/search.png';
		$arr['context'] = $this->_build_context( $row, $query_array );

		// photo image
		if (( $this->is_normal_ext( $row['photo_cont_ext'] ) || 
		      $this->_FLAG_SUBSTITUTE ) && 
		      $row['photo_thumb_url'] ) {
			$arr['img_url']    = $row['photo_thumb_url'];
			$arr['img_width']  = $row['photo_thumb_width'];
			$arr['img_height'] = $row['photo_thumb_height'];
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
	return $myts->displayTarea( $row['photo_description'] , 0 , 1 , 1 , 1 , 1 , 1 );
}

//---------------------------------------------------------
// photo handler
//---------------------------------------------------------
function _get_photo_rows( $query_array, $andor, $uid, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->prefix_dirname( 'photo' );
	$sql .= ' WHERE '. $this->_build_where_for_search( $query_array, $andor, $uid );
	$sql .= ' ORDER BY photo_time_update DESC, photo_id DESC';
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function _build_where_for_search( $keyword_array, $andor, $uid )
{
	$where_key = $this->_build_where_by_keyword_array( $keyword_array, $andor );

	$where_uid = null;
	if ( $uid != 0 ) {
		$where_uid = 'uid='. intval($uid);
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
		$where .= ' AND photo_status > 0 ';
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
	$text = "photo_search LIKE '%" . addslashes( $str ) . "%'" ;
	return $text;
}

// --- class end ---
}

?>