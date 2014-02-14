<?php
// $Id: rss.php,v 1.10 2011/06/05 07:23:40 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-06-04 K.OHWADA
// webphoto_uri
// 2010-11-03 K.OHWADA
// webphoto_main
// 2010-01-10 K.OHWADA
// webphoto_tag
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_item_handler
// 2009-08-30 K.OHWADA
// user name
// 2009-02-28 K.OHWADA
// use_pathinfo
// georss
// 2008-12-12 K.OHWADA
// webphoto_photo_public
// 2008-12-09 K.OHWADA
// Parse error & Fatal error
// 2008-11-29 K.OHWADA
// _build_file_image()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// usage
// index.php/rss/mode/param/limit=xxx/clear=1/
//   mode : latest (default)
//          category, user, random, etc
//   param : non (default)
//          category id, user id, etc
//   limit : 20 (default) : max 100
//   clear : 0 = noting (default)
//           1 = clear compiled template & cache
//---------------------------------------------------------

//---------------------------------------------------------
// TODO
//   show video in mediaRSS
//---------------------------------------------------------

//=========================================================
// class webphoto_rss
//=========================================================
class webphoto_rss extends webphoto_lib_rss
{
	var $_config_class;
	var $_item_handler;
	var $_file_handler;
	var $_cat_handler;
	var $_pathinfo_class;
	var $_multibyte_class;
	var $_sort_class;
	var $_search_class;
	var $_utility_class;
	var $_public_class;
	var $_tag_class;
	var $_main_class ;
	var $_category_class ;
	var $_user_class ;
	var $_place_class ;
	var $_date_class ;
	var $_uri_class ;

	var $_mode  = null;
	var $_param = null;
	var $_limit = 20;

	var $_MAX_SUMMARY  = 500;
	var $_MODE_DEFAULT = 'latest';
	var $_ORDERBY_RANDOM = 'rand()';

	var $_CACHE_TIME_RAMDOM = 60;	// 1 min
	var $_CACHE_TIME_LATEST = 3600;	// 1 hour
	var $_CACHE_TIME_DEBUG  = 0;	// no cache

	var $_LIMIT_DEFAULT = 20;
	var $_LIMIT_MAX = 100;

	var $_DEBUG_FLAG_CACHE_TIME = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_rss( $dirname, $trust_dirname )
{
	$this->webphoto_lib_rss( $dirname ) ;
	$this->set_template( 'db:'.$dirname.'_main_rss.html' );

	$this->_cat_handler    
		=& webphoto_cat_handler::getInstance( $dirname, $trust_dirname );
	$this->_item_handler   
		=& webphoto_item_handler::getInstance( $dirname, $trust_dirname  );
	$this->_file_handler   
		=& webphoto_file_handler::getInstance( $dirname, $trust_dirname );
	$this->_public_class   
		=& webphoto_photo_public::getInstance( $dirname, $trust_dirname );
	$this->_sort_class     
		=& webphoto_photo_sort::getInstance( $dirname, $trust_dirname );
	$this->_tag_class 
		=& webphoto_tag::getInstance( $dirname , $trust_dirname );
	$this->_main_class 
		=& webphoto_main::getInstance( $dirname , $trust_dirname );
	$this->_category_class 
		=& webphoto_category::getInstance( $dirname , $trust_dirname );
	$this->_user_class 
		=& webphoto_user::getInstance( $dirname , $trust_dirname );
	$this->_place_class 
		=& webphoto_place::getInstance( $dirname , $trust_dirname );
	$this->_date_class 
		=& webphoto_date::getInstance( $dirname , $trust_dirname );
	$this->_search_class 
		=& webphoto_search::getInstance( $dirname , $trust_dirname );

	$this->_config_class   =& webphoto_config::getInstance( $dirname );
	$this->_uri_class      =& webphoto_uri::getInstance( $dirname );

	$this->_pathinfo_class  =& webphoto_lib_pathinfo::getInstance();
	$this->_utility_class   =& webphoto_lib_utility::getInstance();
	$this->_multibyte_class =& webphoto_multibyte::getInstance();
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_rss( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function show_rss()
{
	$this->_mode  = $this->_get_mode();
	$this->_param = $this->_get_param( $this->_mode );
	$this->_limit = $this->_get_limit();

	$clear      = $this->_get_clear();
	$cache_id   = $this->_get_cache_id(   $this->_mode , $this->_param );
	$cache_time = $this->_get_cache_time( $this->_mode );

	if ( $clear ) {
		$this->clear_compiled_tpl_for_admin( $cache_id, true );
		exit();
	}

	echo $this->build_rss( $cache_id, $cache_time );
}

function _get_mode()
{
	$mode_input = $this->_pathinfo_class->get( 'mode' );
	if ( empty($mode_input) ){
		$mode_input = $this->_pathinfo_class->get_path( 1 );
	}

	switch ( $mode_input )
	{
		case 'clear':
		case 'randomphotos':
			$mode = $mode_input;
			break;

		default:
			list( $mode, $mode_orig )
				= $this->_sort_class->input_to_mode( $mode_input );
			break;
	}

	return $mode;
}

function _get_param( $mode )
{
	$param_input = $this->_pathinfo_class->get( 'param' );
	if ( empty($param_input) ){
		$param_input = $this->_pathinfo_class->get_path( 2 );
	}

	$param = $this->_sort_class->input_to_param_for_rss( $mode, $param_input );
	return $param;
}

function _get_limit()
{
	$limit = $this->_pathinfo_class->get_int('limit');
	if ( $limit <=0 ) {
		$limit = $this->_LIMIT_DEFAULT;
	} elseif ( $limit > $this->_LIMIT_MAX ) {
		$limit = $this->_LIMIT_MAX;
	}
	return $limit;
}

function _get_clear()
{
	return $this->_pathinfo_class->get_int('clear');
}

function _get_cache_id( $mode, $param )
{
	$cache_id = md5( $mode . $param );
	return $cache_id;
}

function _get_cache_time( $mode )
{
	if ( $this->_DEBUG_FLAG_CACHE_TIME ) {
		return $this->_CACHE_TIME_DEBUG ;
	}

	switch ( $mode )
	{
		case 'random':
			$cache_time = $this->_CACHE_TIME_RAMDOM;
			break;

		default:
			$cache_time = $this->_CACHE_TIME_LATEST;
			break;
	}

	return $cache_time;
}

//---------------------------------------------------------
// items
//---------------------------------------------------------
function build_items()
{
	$ret = array() ;

	$rows = $this->_get_photo_rows();
	foreach ( $rows as $row )
	{
		$cat_row   = $this->_cat_handler->get_cached_row_by_id( $row['item_cat_id'] );
		$cont_row  = $this->_get_file_row_by_kind( $row, _C_WEBPHOTO_FILE_KIND_CONT );
		$thumb_row = $this->_get_file_row_by_kind( $row, _C_WEBPHOTO_FILE_KIND_THUMB );

		$link_xml  = $this->xml_text( $this->_build_link( $row ) ) ;
		$title_xml = $this->xml_text( $row['item_title'] ) ;
		$pubdate   = date('r', $row['item_time_update'] ) ;
		list( $content, $summary, $desc ) = $this->_build_description( $row, $thumb_row );

// georss
		$geo_lat      = '';
		$geo_long     = '';
		$georss_point = '';

		if (( $row['item_gmap_latitude']  != 0 )||
		    ( $row['item_gmap_longitude'] != 0 )||
		    ( $row['item_gmap_zoom']      != 0 )) {
			$geo_lat  = $row['item_gmap_latitude'] ;
			$geo_long = $row['item_gmap_longitude'];
			$georss_point = $geo_lat.' '.$geo_long;
		}

// mediarss
		$media_title_xml        = '';
		$media_description      = '';
		$media_content_url      = '';
		$media_content_filesize = '';
		$media_content_height   = '';
		$media_content_width    = '';
		$media_content_type     = '';
		$media_content_medium   = '';
		$media_content_duration = '';
		$media_thumbnail_url    = '';
		$media_thumbnail_height = '';
		$media_thumbnail_width  = '';
		$media_thumbnail_large_url    = '' ;
		$media_thumbnail_large_height = '' ;
		$media_thumbnail_large_width  = '' ;

		if ( is_array($cont_row) ) {

			list( $media_content_url, $media_content_width, $media_content_height ) =
				$this->_build_file_image( $cont_row ) ;

			$media_title_xml        = $title_xml ;
			$media_description      = $summary ;
			$media_content_filesize = $cont_row['file_size'];
			$media_content_duration = $cont_row['file_duration'];
			$media_content_type     = $cont_row['file_mime'];

// imaeg type
			if ( $this->_is_kind_image( $row ) ) {
				$media_content_medium   = 'image';

				if ( is_array($thumb_row) ) {

					list( $media_thumbnail_url, $media_thumbnail_width, $media_thumbnail_height ) =
						$this->_build_file_image( $thumb_row ) ;

					$media_thumbnail_large_url    = $media_content_url ;
					$media_thumbnail_large_height = $media_content_height ;
					$media_thumbnail_large_width  = $media_content_width ;
				}

// video type
			} elseif ( $this->_is_kind_video( $row ) ) {
				$media_content_medium   = 'video';
			}
		}

		$arr = array(
			'link'         => $link_xml ,
			'guid'         => $link_xml ,
			'title'        => $title_xml ,
			'category'     => $this->xml_text( $cat_row['cat_title'] ),
			'pubdate'      => $this->xml_text( $pubdate ), 
			'description'  => $this->xml_text( $desc ),

// user name
			'dc_creator'   => XoopsUser::getUnameFromId( $row['item_uid'] ),

			'geo_lat'      => $geo_lat ,
			'geo_long'     => $geo_long ,
			'georss_point' => $georss_point ,
			'media_title'            => $media_title_xml ,
			'media_description'      => $this->xml_text( $media_description ) ,
			'media_content_url'      => $this->xml_url( $media_content_url ),
			'media_content_filesize' => intval( $media_content_filesize ),
			'media_content_height'   => intval( $media_content_height ),
			'media_content_width'    => intval( $media_content_width ),
			'media_content_type'     => $this->xml_text( $media_content_type ),
			'media_content_medium'   => $this->xml_text( $media_content_medium ),
			'media_content_duration' => intval( $media_content_duration ),
			'media_thumbnail_url'    => $this->xml_url( $media_thumbnail_url ),
			'media_thumbnail_height' => intval( $media_thumbnail_height ),
			'media_thumbnail_width'  => intval( $media_thumbnail_width ),
			'media_thumbnail_large_url'    => $this->xml_url( $media_thumbnail_large_url ),
			'media_thumbnail_large_height' => intval( $media_thumbnail_large_height ) ,
			'media_thumbnail_large_width'  => intval( $media_thumbnail_large_width ) ,

		);

		$ret[] = $arr;
	}

	return $ret;
}

function _build_description( $row, $thumb_row )
{
	$context = $this->_build_context( $row );
	$summary = $this->_multibyte_class->build_summary( $context, $this->_MAX_SUMMARY );

	$desc = '';

	if ( $this->_is_kind_image( $row ) && is_array($thumb_row) ) {

// Parse error & Fatal error
		list( $thumb_url, $thumb_width, $thumb_height ) =
			$this->_build_file_image( $thumb_row ) ;

		$img  = '<img src="'. $thumb_url .'" ' ;
		$img .= 'alt="'. $row['item_title'] .'" ';
		if ( $thumb_width && $thumb_height ) {
			$img .= 'width="'.  $thumb_width  .'" '  ;
			$img .= 'height="'. $thumb_height .'" ' ;
		}
		$img .= '">';

		$desc .= '<a href="'. $this->_build_link( $row ) .'" target="_blank">';
		$desc .= $img .'</a><br />';
	}

	if ( strlen($context) > $this->_MAX_SUMMARY ) {
		$desc .= $summary ;
	} else {
		$desc .= $context ;
	}

	return array( $context, $summary, $desc );
}

function _build_context( $row )
{
	return $this->_item_handler->build_show_description_disp( $row );
}

function _build_link( $row )
{
	return $this->_uri_class->build_photo( $row['item_id'], false );
}

function _is_kind_image( $row )
{
	if ( $row['item_kind'] == _C_WEBPHOTO_ITEM_KIND_IMAGE ) {
		return true;
	}
	return false;
}

function _is_kind_video( $row )
{
	if ( $row['item_kind'] == _C_WEBPHOTO_ITEM_KIND_VIDEO ) {
		return true ;
	}
	return false;
}

function _get_file_row_by_kind( $row, $kind )
{
	$file_id = $this->_item_handler->build_value_fileid_by_kind( $row, $kind );
	if ( $file_id > 0 ) {
		return $this->_file_handler->get_row_by_id( $file_id );
	}
	return null;
}

function _build_file_image( $file_row )
{
	return $this->_file_handler->build_show_file_image( $file_row );
}

//---------------------------------------------------------
// handler
//---------------------------------------------------------
function _get_photo_rows()
{
	$limit  = $this->_limit ;

	$param        = $this->_param;
	$param_int    = intval( $param );
	$param_decode = $this->_multibyte_class->convert_from_utf8( $param );

	$where   = null;
	$orderby = null;
	$rows    = null;

	$sort = null;
	$orderby_default = $this->_sort_class->sort_to_orderby( $sort );
	$orderby         = $orderby_default ;

	switch ( $this->_mode )
	{
		case 'category':
			if ( $param_int > 0 ) {
				$rows = $this->_category_class->build_rows_for_rss(
					$param_int, $orderby, $limit );
			}
			break;

		case 'date':
			if ( $param ) {
				$rows = $this->_date_class->build_rows_for_rss(
					$param, $orderby, $limit ) ;
			}
			break;

		case 'place':
			if ( $param_decode ) {
				$rows = $this->_place_class->build_rows_for_rss(
					$param_decode, $orderby, $limit );
			}
			break;

		case 'tag':
			if ( $param_decode ) {
				$rows = $this->_tag_class->build_rows_for_rss(
					$param_decode, $orderby, $limit );
			}
			break;

		case 'user':
			if ( $param_int > 0 ) {
				$rows = $this->_user_class->build_rows_for_rss(
					$param_int, $orderby, $limit );
			}
			break;

		case 'search':
			if ( $param ) {
				$rows = $this->_search_class->build_rows_for_rss(
					$param, $orderby, $limit );
			}
			break;

// only photo for slide show
		case 'randomphotos':
			$rows = $this->_build_rows_randomphotos( $param_int, $limit );
			break;

		default:
			$rows = $this->_main_class->build_rows_for_rss( 
				$this->_mode, $limit ) ;
			break;
	}

	if ( is_array($rows) && count($rows) ) {
		return $rows;
	}

	$rows = $this->_main_class->build_rows_for_rss( 
		$this->_MODE_DEFAULT, $limit ) ;
	return $rows;
}

function _build_rows_randomphotos( $param_int, $limit )
{
	if ( $param_int > 0 ) {
		$rows = $this->_public_class->get_rows_photo_by_catid_orderby(
			$param_int, $this->_ORDERBY_RANDOM, $limit );

	} else {
		$rows = $this->_public_class->get_rows_photo_by_orderby(
			$this->_ORDERBY_RANDOM, $limit );
	}

	return $rows;
}

// --- class end ---
}

?>