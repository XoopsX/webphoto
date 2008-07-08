<?php
// $Id: rss.php,v 1.2 2008/07/08 20:31:22 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// usage
// index.php/rss/mode/param/limit=xxx/
//   mode : latest (default)
//          category, user, random, etc
//   param : non (default)
//          category id, user id, etc
//   limit : 20 (default) : max 100
//---------------------------------------------------------

//---------------------------------------------------------
// TODO
//   show video in mediaRSS
//---------------------------------------------------------

//=========================================================
// class webphoto_main_rss
//=========================================================
class webphoto_main_rss extends webphoto_lib_rss
{
	var $_photo_handler;
	var $_cat_handler;
	var $_pathinfo_class;
	var $_multibyte_class;
	var $_sort_class;
	var $_search_class;
	var $_utility_class;
	var $_tag_class;

	var $_mode  = null;
	var $_param = null;
	var $_limit = 20;

	var $_is_japanese = false;

	var $_MAX_SUMMARY  = 500;
	var $_MODE_DEFAULT = 'latest';

	var $_CACHE_TIME_RAMDOM = 60;	// 1 min
	var $_CACHE_TIME_LATEST = 3600;	// 1 hour

	var $_LIMIT_DEFAULT = 20;
	var $_LIMIT_MAX = 100;

	var $_NORMAL_EXTS;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_rss( $dirname, $trust_dirname )
{
	$this->webphoto_lib_rss( $dirname ) ;
	$this->set_template( 'db:'.$dirname.'_main_rss.html' );

	$this->_photo_handler  =& webphoto_photo_handler::getInstance( $dirname );
	$this->_cat_handler    =& webphoto_cat_handler::getInstance(   $dirname );
	$this->_pathinfo_class =& webphoto_lib_pathinfo::getInstance();
	$this->_search_class   =& webphoto_lib_search::getInstance();
	$this->_utility_class  =& webphoto_lib_utility::getInstance();
	$this->_sort_class     =& webphoto_photo_sort::getInstance( $dirname, $trust_dirname );

	$this->_NORMAL_EXTS = explode('|', _C_WEBPHOTO_IMAGE_EXTS);
	$this->_is_japanese = $this->_is_xoops_japanese( _C_WEBPHOTO_JPAPANESE ) ;

	$this->_tag_class =& webphoto_tag::getInstance( $dirname );
	$this->_tag_class->set_is_japanese( $this->_is_japanese );

	$this->_multibyte_class =& webphoto_lib_multibyte::getInstance();
	$this->_multibyte_class->set_is_japanese( $this->_is_japanese );
	$this->_multibyte_class->set_ja_kuten(   _WEBPHOTO_JA_KUTEN );
	$this->_multibyte_class->set_ja_dokuten( _WEBPHOTO_JA_DOKUTEN );
	$this->_multibyte_class->set_ja_period(  _WEBPHOTO_JA_PERIOD );
	$this->_multibyte_class->set_ja_comma(   _WEBPHOTO_JA_COMMA );

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_rss( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$this->_get_pathinfo_param();

	switch ( $this->_mode )
	{
		case 'clear':
			$this->clear_compiled_tpl();
			exit();

		case 'random':
			$cache_time = $this->_CACHE_TIME_RAMDOM;
			break;

		case 'latest':
		case 'popular':
		case 'highrate':
		case 'tag':
		case 'date':
		case 'place':
		case 'search':
		case 'category':
		case 'user':
		default:
			$cache_time = $this->_CACHE_TIME_LATEST;
			break;
	}

	$cache_id = md5( $this->_mode.$this->_param );
	echo $this->build_rss( $cache_id, $cache_time );
}

function _get_pathinfo_param()
{
	$mode = $this->_pathinfo_class->get( 'mode' );
	if ( empty($mode) ){
		$mode = $this->_pathinfo_class->get_path( 1 );
	}

	$param = $this->_pathinfo_class->get( 'param' );
	if ( empty($param) ){
		$param = $this->_pathinfo_class->get_path( 2 );
	}

	$this->_get_limit();

	switch ( $mode )
	{
		case 'clear':
			$this->_mode = $mode;
			break;

		case 'tag':
		case 'date':
		case 'place':
		case 'search':
		case 'latest':
		case 'popular':
		case 'highrate':
		case 'random':
		case 'category':
		case 'user':
			$this->_mode  = $mode;
			$this->_param = $param;
			break;

		default:
			$this->_mode = $this->_MODE_DEFAULT;
			break;
	}
}

function _get_limit()
{
	$limit = $this->_pathinfo_class->get_int('limit');
	if ( $limit <=0 ) {
		$limit = $this->_LIMIT_DEFAULT;
	} elseif ( $limit > $this->_LIMIT_MAX ) {
		$limit = $this->_LIMIT_MAX;
	}
	$this->_limit = $limit;
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
		$cat_row = $this->_cat_handler->get_cached_row_by_id( $row['photo_cat_id'] );

		$link_xml  = $this->xml_text( $this->_build_link( $row ) ) ;
		$title_xml = $this->xml_text( $row['photo_title'] ) ;
		$pubdate   = date('r', $row['photo_time_update'] ) ;
		list( $content, $summary, $desc ) = $this->_build_description( $row );

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

		if ( $row['photo_cont_url'] ) {

			$media_title_xml        = $title_xml ;
			$media_description      = $summary ;
			$media_content_url      = $row['photo_cont_url'] ;
			$media_content_filesize = $row['photo_cont_size'];
			$media_content_height   = $row['photo_cont_height'];
			$media_content_width    = $row['photo_cont_width'];
			$media_content_duration = $row['photo_cont_duration'];
			$media_content_type     = $row['photo_cont_mime'];

// imaeg type
			if ( $this->_is_image( $row ) ) {
				$media_content_medium   = 'image';

				if ( $row['photo_thumb_url'] ) {
					$media_thumbnail_url          = $row['photo_thumb_url'] ;
					$media_thumbnail_height       = $row['photo_thumb_height'];
					$media_thumbnail_width        = $row['photo_thumb_width'];
					$media_thumbnail_large_url    = $media_content_url ;
					$media_thumbnail_large_height = $media_content_height ;
					$media_thumbnail_large_width  = $media_content_width ;
				}

// video type
			} elseif ( $this->_is_video( $row ) ) {
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

function _build_description( $row )
{
	$context = $this->_build_context( $row );
	$summary = $this->_multibyte_class->build_summary( $context, $this->_MAX_SUMMARY );

	$desc = '';

	$thumb_url    = $row['photo_thumb_url'] ;
	$thumb_width  = intval( $row['photo_thumb_width'] );
	$thumb_height = intval( $row['photo_thumb_height'] );

	if ( $thumb_url && $this->_is_image( $row ) ) {
		$img  = '<img src="'. $thumb_url .'" ' ;
		$img .= 'alt="'. $row['photo_title'] .'" ';
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
	return $this->_photo_handler->build_show_description_disp( $row );
}

function _build_link( $row )
{
	$link = $this->_MODULE_URL .'/index.php/photo/'. $row['photo_id'] .'/';
	return $link;
}

function _is_image( $row )
{
	if ( $row['photo_cont_medium'] == 'image' ) {
		return true;
	} elseif ( in_array( strtolower( $row['photo_cont_ext'] ) , $this->_NORMAL_EXTS ) ) {
		return true;
	}
	return false;
}

function _is_video( $row )
{
	if ( $row['photo_cont_medium'] == 'video' ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// handler
//---------------------------------------------------------
function _get_photo_rows()
{
	$limit  = $this->_limit ;

	$param     = $this->_param;
	$param_int = intval( $param );
	$place_arr = $this->_utility_class->str_to_array( $param, '+' );

	$where   = null;
	$orderby = null;
	$rows    = null;
	$orderby_default = $this->_sort_class->sort_to_orderby( null );

	switch ( $this->_mode )
	{
		case 'tag':
			if ( $param ) {
				$orderby = str_replace( 'photo_', 'p.photo_', $orderby_default );
				$id_array = $this->_tag_class->get_photo_id_array_public_latest_by_tag_orderby(
					$param, $orderby, $limit );
				$rows = $this->_photo_handler->get_rows_from_id_array( $id_array );
			}
			break;

		case 'date':
			if ( $param ) {
				$where = $this->_photo_handler->build_where_public_by_like_datetime( $param );
			}
			break;

		case 'place':
			if ( $param == _C_WEBPHOTO_PLACE_STR_NOT_SET ) {
				$where = $this->_photo_handler->build_where_public_by_place(
					_C_WEBPHOTO_PLACE_VALUE_NOT_SET );
			} elseif ( is_array($place_arr) && count($place_arr) ) {
				$where = $this->_photo_handler->build_where_public_by_place_array( $place_arr );
			}
			break;

		case 'search':
			if ( $param ) {
				$where = $this->_build_where_by_query( $param );
			}
			break;

		case 'category':
			if ( $param_int > 0 ) {
				$where = $this->_photo_handler->build_where_public_by_catid( $param_int );
			}
			break;

		case 'user':
			if ( $param_int > 0 ) {
				$where = $this->_photo_handler->build_where_public_by_uid( $param_int );
			}
			break;

// only photo for slide show
		case 'random':
			$orderby = $this->_sort_class->get_random_orderby();
			if ( $param_int > 0 ) {
				$where = $this->_photo_handler->build_where_public_photo_by_catid( $param_int );
			} else {
				$where = $this->_photo_handler->build_where_public_photo();
			}
			break;

		case 'latest':
		case 'popular':
		case 'highrate':
		default:
			$orderby = $this->_sort_class->mode_to_orderby( $this->_mode );
			if ( $param_int > 0 ) {
				$where = $this->_photo_handler->build_where_public_by_catid( $param_int );
			}
			break;
	}

	if ( is_array($rows) ) {
		return $rows;
	}

	if ( empty($where) ) {
		$where = $this->_photo_handler->build_where_public();
	}

	if ( empty($orderby) ) {
		$orderby = $orderby_default;
	}

	$rows = $this->_photo_handler->get_rows_by_where_orderby( $where, $orderby, $limit );
	return $rows;
}

function _build_where_by_query( $query )
{
	$this->_search_class->set_lang_zenkaku( $this->get_constant('SR_ZENKAKU') );
	$this->_search_class->set_lang_hankaku( $this->get_constant('SR_HANKAKU') );

	$this->_search_class->set_min_keyword( 
		$this->_search_class->get_xoops_config_search_keyword_min() );
	$this->_search_class->set_is_japanese( $this->_is_japanese );
	$this->_search_class->get_post_get_param();
	$this->_search_class->set_query( $query );

	$ret = $this->_search_class->parse_query();
	if ( !$ret ) {
		return false;
	}

	$where = $this->_search_class->build_sql_query( 'photo_search' );
	return $where;
}

//---------------------------------------------------------
// xoops param
//---------------------------------------------------------
function _is_xoops_japanese( $str )
{
	global $xoopsConfig ;

	if ( in_array( $xoopsConfig['language'], explode('|', $str ) ) ) {
		return true;
	}
	return false;
}

// --- class end ---
}

?>