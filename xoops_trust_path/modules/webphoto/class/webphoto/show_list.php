<?php
// $Id: show_list.php,v 1.11 2009/11/06 18:04:17 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-10-25 K.OHWADA
// build_photos_param_in_category()
// 2009-06-28 K.OHWADA
// set_list_mode()
// 2009-05-30 K.OHWADA
// BUG : not show cat_id
// 2009-04-10 K.OHWADA
// build_common_param()
// 2009-03-15 K.OHWADA
// build_init_show()
// 2008-12-12 K.OHWADA
// public_class
// 2008-12-07 K.OHWADA
// build_photo_show() -> build_photo_show_main()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// 2008-07-01 K.OHWADA
// used get_list_pathinfo_param()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_show_list
//=========================================================
class webphoto_show_list extends webphoto_show_main
{
	var $_flash_class;
	var $_embed_class;
	var $_photo_navi_class;
	var $_item_public_class;
	var $_d3_comment_view_class;

	var $_param      = null;
	var $_param_out  = null;
	var $_list_mode  = null;
	var $_navi_mode  = null;

	var $_get_uid     = -1;	// not set
	var $_UID_DEFAULT = -1;	// not set

// for photo
	var $_get_photo_id;
	var $_get_cat_id;
	var $_get_order;
	var $_get_kind;

	var $_photo_row = null;
	var $_has_tagedit    = false;
	var $_show_codebox   = false ;
	var $_perm_download  = false;
	var $_codeinfo_array = null;

	var $_CODEINFO_SHOW_LIST;
	var $_FILE_LIST;

	var $_SHOW_PHOTO_SUMMARY = false;
	var $_SHOW_PHOTO_CONTENT = true;
	var $_SHOW_PHOTO_VIEW    = false;
	var $_SHOW_PHOTOS_IN_CAT = false;

	var $_TEMPLATE_LIST   = 'main_list.html' ;
	var $_TEMPLATE_DETAIL = 'main_index.html' ;

// for future
	var $_get_viewtype = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_show_list( $dirname , $trust_dirname )
{
	$this->webphoto_show_main( $dirname , $trust_dirname );

	$this->_flash_class       =& webphoto_flash_player::getInstance( $dirname );
	$this->_embed_class       =& webphoto_embed::getInstance( $dirname, $trust_dirname );
	$this->_item_public_class =& webphoto_item_public::getInstance( $dirname, $trust_dirname );

	$this->_photo_navi_class =& webphoto_photo_navi::getInstance( $dirname );
	$this->_photo_navi_class->set_mark_id_prev( '<b>'. $this->get_constant('NAVI_PREVIOUS') .'</b>' );
	$this->_photo_navi_class->set_mark_id_next( '<b>'. $this->get_constant('NAVI_NEXT') .'</b>' );

	$this->_comment_view_class =& webphoto_d3_comment_view::getInstance();
	$this->_comment_view_class->init( $dirname );

	$this->_has_tagedit = $this->_perm_class->has_tagedit();

	$this->_CODEINFO_SHOW_LIST = explode( '|', _C_WEBPHOTO_CODEINFO_SHOW_LIST );
	$this->_FILE_LIST          = explode( '|', _C_WEBPHOTO_FILE_LIST );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_show_list( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
// overwrite
function set_mode( $val )
{
	$this->_mode      = $val;
	$this->_list_mode = $val;
}

// for myphoto
function set_list_mode( $val )
{
	$this->_list_mode = $val;
}

// for photo
function set_navi_mode( $val )
{
	$this->_navi_mode = $val;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function list_get_template()
{
	$this->list_get_pathinfo_param();

	if ( $this->list_sel() ) {
		$str = $this->_TEMPLATE_DETAIL;
	} else {
		$str = $this->_TEMPLATE_LIST;
	}
	$ret = $this->_DIRNAME . '_'. $str;
	return $ret;
}

function list_main()
{
	if ( $this->list_sel() ) {
		return $this->list_build_detail( $this->_param );
	}
	return $this->list_build_list();
}

function list_sel()
{
	if ( $this->_param ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// get pathinfo param
//---------------------------------------------------------
function list_get_pathinfo_param()
{
	$this->_param    = $this->get_uri_list_pathinfo_param() ;
	$this->_get_page = $this->get_pathinfo_page() ;
	$this->_get_sort = $this->get_photo_sort_name_by_pathinfo();
	$this->_get_kind = $this->get_photo_kind_name_by_pathinfo();

	$this->set_param_out( $this->_param );
}

function set_param_out( $val )
{
	$this->_param_out = $val;
}

//---------------------------------------------------------
// list
//---------------------------------------------------------
function list_build_list()
{
	return $this->list_build_list_default();
}

function list_build_list_default()
{
	$this->assign_xoops_header_default();
	return $this->list_build_list_common();
}

function list_build_list_common( $show_photo_desc=false, $title=null )
{
	$mode = $this->_mode;

	if ( empty($title) ) {
		$const = 'title_'. $mode .'_list';
		$title = $this->get_constant( $const );
	}

	$title_s = $this->sanitize( $title );

	$param = array(
		'xoops_pagetitle'    => $title_s ,
		'title_bread_crumb'  => $title_s,
		'sub_title_s'        => $title_s ,
		'photo_list'         => $this->list_get_photo_list() ,
	);

	$arr = array_merge( 
		$param, 
		$this->build_common_param( $mode, $show_photo_desc ) 
	);
	return $arr;
}

// overwrite
function list_get_photo_list()
{
	// dummy
}

function list_build_photo_array( $title, $param, $total, $row, $link=null, $photo=null )
{
	if ( empty($link) && $param ) {
		$link = $this->build_uri_list_link( $param ) ;
	}

	if ( empty($photo) && is_array($row) ) {
		$photo = $this->build_photo_show_main( $row );
	}

	$arr = array(
		'title'   => $title ,
		'title_s' => $this->sanitize( $title ) ,
		'link'    => $link ,
		'link_s'  => $this->sanitize( $link ) ,
		'total'   => $total ,
		'photo'   => $photo ,
	);
	return $arr;
}

//---------------------------------------------------------
// detail list
//---------------------------------------------------------
// overwrite
function list_build_detail( $param )
{
	// dummy
}

function list_build_detail_common( $title, $total, $rows, $photos=null )
{
	$title_s = $this->sanitize( $title );

	$show_photo = false ; 
	$photos     = null;

	if ( empty($photos) && is_array($rows) && count($rows) ) {
		$photos = $this->build_photo_show_from_rows( $rows );
	}

	if ( is_array($photos) && count($photos) ) {
		$show_photo = true ; 
	}

	$arr = array(
		'xoops_pagetitle'   => $title_s ,
		'title_bread_crumb' => $title_s ,
		'total_bread_crumb' => $total ,
		'sub_title_s'       => $title_s ,
		'sub_desc_s'        => '' ,
		'show_photo'        => $show_photo , 
		'photo_total'       => $total ,
		'photos'            => $photos ,
		'show_nomatch'      => $this->build_show_nomatch( $total ) ,
		'show_sort'         => $this->build_show_sort( $total ) ,
		'random_more_url_s' => $this->list_build_random_more( $total ) ,
	);
	return $arr;
}

// BUG : not show cat_id
function list_build_init_param( $show_photo_desc=false, $cat_id=0 )
{
	$param = $this->build_common_param( $this->_mode, $show_photo_desc, $cat_id ) ;
	$param['param_sort'] = $this->build_uri_list_sort() ;
	return $param;
}

function list_build_random_more( $total, $url=null )
{
	if ( empty($url) ) {
		$url = $this->build_uri_list_link( $this->_param_out ) ;
	}
	return $this->build_random_more_url_with_check_sort( $url, $total );
}

function list_assign_xoops_header( $rss_param=null, $flag_gmap=false )
{
	if ( empty($rss_param) ) {
		$rss_param = $this->_param_out;
	}

	$this->assign_xoops_header( $this->_mode, $rss_param, $flag_gmap );
}

//---------------------------------------------------------
// navi
//---------------------------------------------------------
function list_build_navi( $total, $limit, $get_page=null, $get_sort=null )
{
	if ( empty($get_sort) ) {
		$get_sort = $this->_get_sort;
	}

	if ( $this->check_show_navi_sort( $get_sort ) ) {
		$url = $this->build_uri_list_navi_url( $get_sort );
		return $this->build_navi( $url, $total, $limit, $get_page );
	}

	$arr = array(
		'show_navi' => false
	);
	return $arr;
}

//---------------------------------------------------------
// uri class
//---------------------------------------------------------
function get_uri_list_pathinfo_param()
{
// list_mode for myphoto
	return $this->_uri_class->get_list_pathinfo_param( $this->_list_mode );
}

function build_uri_list_navi_url( $get_sort )
{
	return $this->_uri_class->build_list_navi_url(
		$this->_mode, $this->_param_out, $get_sort );
}

function build_uri_list_navi_url_kind( $mode, $param, $kind )
{
	return $this->_uri_class->build_list_navi_url_kind( 
		$mode, $param, $kind );
}

function build_uri_list_sort()
{
	return $this->_uri_class->build_list_sort(
		$this->_mode, $this->_param_out, $this->_get_viewtype );
}

function build_uri_list_kind( $mode, $param, $viewtype=null )
{
	return $this->_uri_class->build_list_sort(
		$mode, $param, $viewtype );
}

function build_uri_list_link( $param )
{
	return $this->_uri_class->build_list_link( $this->_mode, $param );
}

//=========================================================
// photo
//=========================================================
//---------------------------------------------------------
// check
//---------------------------------------------------------
function check_photo_edittag()
{
	$this->check_photo_init();

	if ( $this->is_photo_edittag() ) {
		$this->photo_edittag();
		exit();
	}

}

function check_photo_init()
{
	$this->_get_photo_id = $this->_uri_class->get_pathinfo_id( 'photo_id' ) ;
	$this->_get_cat_id   = $this->_pathinfo_class->get_int( 'cat_id' );
	$this->_get_order    = $this->_pathinfo_class->get( 'order' );

	$row = $this->_item_public_class->get_item_row( $this->_get_photo_id ) ;
	if( !is_array($row) ) {
		redirect_header( $this->_MODULE_URL.'/' , $this->_TIME_FAIL , $this->get_constant('NOMATCH_PHOTO') ) ;
		exit();
	}

// save row
	$this->_photo_row = $row;
}

//---------------------------------------------------------
// edittag
//---------------------------------------------------------
function is_photo_edittag()
{
	if ( $this->_post_class->get_post('op') == 'tagedit' ) {
		return true;
	}
	return false;
}

function photo_edittag()
{
	$redirect_this_url = $this->build_uri_photo( $this->_get_photo_id );

	$ret = $this->excute_photo_edittag();
	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_NO_PERM:
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAIL , _NOPERM ) ;
			exit ;

		case _C_WEBPHOTO_ERR_TOKEN:
			$msg = 'Token Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_token_errors();
			}
			redirect_header( $redirect_this_url, $this->_TIME_FAIL , $msg );
			exit();

		case _C_WEBPHOTO_ERR_DB:
			$msg = 'DB Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_format_error();
			}
			redirect_header( $redirect_this_url, $this->_TIME_FAIL, $msg ) ;
			exit();

		case 0:
		default:
			break;
	}

	redirect_header( $redirect_this_url , $this->_TIME_SUCCESS , $this->get_constant('DBUPDATED') ) ;
	exit();
}

function excute_photo_edittag()
{
	if ( ! $this->_has_tagedit ) {
		return _C_WEBPHOTO_ERR_NO_PERM;
	}

	if ( ! $this->check_token() ) { 
		return _C_WEBPHOTO_ERR_TOKEN;
	}

// load row
	$row = $this->_photo_row;

	$photo_id  = $row['item_id'];

	$post_tags  = $this->_post_class->get_post_text( 'tags' );
	$post_array = $this->_tag_class->str_to_tag_name_array( $post_tags );

	$ret = $this->_tag_class->update_tags( $photo_id, $this->_xoops_uid, $post_array );
	if ( !$ret ) {
		return _C_WEBPHOTO_ERR_DB;
	}

	return 0;
}

//---------------------------------------------------------
// show main
//---------------------------------------------------------
function build_photo_show_photo( $row )
{
	$arr1 = $this->build_photo_show_main( $row );

	$this->_perm_download = $arr1['perm_download'] ;

	$arr2 = $this->build_photo_flash_player( $row, $arr1 ) ;
	$arr3 = $this->build_photo_embed_link( $row );
	$arr4 = $this->build_photo_code( $row, $arr1, $arr2, $arr3 );

	$arr = array_merge( $arr1, $arr2, $arr3, $arr4 );
	return $arr;
}

//---------------------------------------------------------
// flash player
//---------------------------------------------------------
function build_photo_flash_player( $item_row, $show_arr )
{
	$item_id     = $item_row['item_id'] ;
	$displaytype = $item_row['item_displaytype'] ;
	$uid         = $item_row['item_uid'] ;

	$flag  = false ;
	$flash = null ;
	$embed = null ;
	$js    = null ;

	if ( $displaytype < _C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT ) {
		return array( $flag, $flash, $embed, $js );
	}

	$flag  = true;

// countup views if not submitter or admin.
	if ( $this->check_not_owner( $uid ) ) {
		$this->_item_handler->countup_views( $item_id, true );
	}

	$flash              = $this->_flash_class->build_movie_by_item_row(     $item_row );
	list( $embed, $js ) = $this->_flash_class->build_code_embed_by_item_row( $item_row );

	$arr = array(
		'displaytype_flash' => $flag ,
		'flash_player'      => $flash ,
		'code_embed'        => $embed  ,
		'code_js'           => $js ,
	);
	return $arr;
}

//---------------------------------------------------------
// embed
//---------------------------------------------------------
function build_photo_embed_link( $item_row )
{
	$kind    = $item_row['item_kind'];
	$siteurl = $item_row['item_siteurl'];
	$type    = $item_row['item_embed_type'];
	$src     = $item_row['item_embed_src'];
	$text    = $item_row['item_embed_text'];
	$width   = $item_row['item_page_width'];
	$height  = $item_row['item_page_height'];

	$can = false ;

	if ( $text && $siteurl) {
		$embed = $text;
		$link  = $siteurl;

	} elseif ( $text ) {
		$embed = $text;
		list( $dummy, $link ) 
			= $this->_embed_class->build_embed_link( $type, $src, $width, $height );

	} else {
		list( $embed, $link ) 
			= $this->_embed_class->build_embed_link( $type, $src, $width, $height, true, true );
	}

	if ( $embed ) {
		$can = true ;
	}

	$arr = array(
		'embed_can'   => $can ,
		'embed_embed' => $embed ,
		'embed_link'  => $link ,
	);
	return $arr;
}

//---------------------------------------------------------
// code
//---------------------------------------------------------
function build_photo_code( $item_row, $show_arr, $flash_arr, $embed_arr )
{
	$item_id  = $item_row['item_id'] ;
	$title    = $item_row['item_title'] ;
	$siteurl  = $item_row['item_siteurl'] ;
	$kind     = $item_row['item_kind'] ;
	$feed     = $item_row['item_playlist_feed'] ;
	$cache    = $item_row['item_playlist_cache'] ;

	$this->_codeinfo_array = $this->_item_handler->get_codeinfo_array( $item_row );

	$param = array();
	$param['page']  = $this->build_photo_page_link( $item_id );
	$param['site']  = $this->build_photo_site_link( $item_id, $siteurl, $embed_arr );
	$param['play']  = $this->build_photo_play_link( $item_id, $kind, $title, $feed, $cache );

	$temp = $this->build_photo_code_embed_link( $item_row, $flash_arr, $embed_arr );
	$param['embed'] = $temp['embed'];
	$param['js']    = $temp['js'];

	foreach ( $this->_FILE_LIST as $name ) {
		$param[ $name ] = $this->build_photo_file_link( $item_row, $show_arr, $name );
	}

	if ( $param['thumb']['show'] && ( $param['cont']['value'] == $param['thumb']['value'] )) {
		 $param['thumb']['show'] = false;
	}

	if ( $param['middle']['show'] && ( $param['cont']['value'] == $param['middle']['value'] )) {
		 $param['middle']['show'] = false;
	}

	if ( $param['small']['show'] && ( $param['cont']['value'] == $param['small']['value'] )) {
		 $param['small']['show'] = false;
	}

	$codes = array();
	foreach ( $this->_CODEINFO_SHOW_LIST as $name ) {
		$codes[] = $param[ $name ];
	}

	$arr = array();
	$arr['codes'] = $codes;

// always last
	$arr['show_codebox'] = $this->_show_codebox ;

	return $arr;
}

function build_photo_file_link( $item_row, $show_arr, $name )
{
	$show      = false;
	$show_img = false;
	$url      = null;
	$title    = null;
	$filesize = null;

	$arr = array(
		'show' => $show,
		'url'  => $url,
	);

	if ( ! $this->_perm_download ) {
		return $arr;
	}

	$img        = null;
	$item_name  = null ;
	$file_kind  = constant( strtoupper( '_C_WEBPHOTO_FILE_KIND_'.$name ) );

	switch ( $name )
	{
		case 'cont' :
			$item_name  = 'item_external_url' ;
			break;

		case 'thumb' :
			$item_name  = 'item_external_thumb' ;
			break;

		case 'middle' :
			$item_name  = 'item_external_middle' ;
			break;
	}

	$item_id   = $item_row['item_id'] ;
	$caption   = $this->build_photo_code_caption( $name );
	$lang_down = $this->get_constant( 'DOWNLOAD' );
	$file_row  = $this->get_show_file_row( $show_arr, $file_kind ) ; 

// if file exists
	if ( is_array($file_row) ) {
		$url   = $file_row['file_url'] ;
		$ext   = $file_row['file_ext'] ;
		$size  = $file_row['file_size'] ;
		$path  = $file_row['file_path'] ;
		$file  = XOOPS_ROOT_PATH .'/'. $path ;

		if ( $this->is_image_ext( $ext ) ) {
			$base_url = $this->_MODULE_URL.'/index.php?fct=image';
			$title    = $caption ;

		} else {
			$base_url = $this->_MODULE_URL.'/index.php?fct=download';
			$title    = $lang_down .' '. $caption ;
			$show_img = true;
		}

		if ( $path && file_exists($file) ) {
			$url  = $base_url .'&item_id='. $item_id .'&file_kind='. $file_kind;

			if ( $size > 0 ) {
				$filesize = $this->build_show_filesize( $size );
			}
		}

// if external
	} elseif ( $item_name ) {
		$item_url = $item_row[ $item_name ] ;
		if ( $item_url ) {
			$url   = $item_url ;
			$title = $caption ;
		}
	}

	$arr = $this->build_photo_code_result_link( $name, $url, $title );
	$arr['show_img'] = $show_img;
	$arr['filesize'] = $filesize;
	return $arr;
}

function build_photo_page_link( $item_id )
{
	$name  = 'page';
	$url   = $this->build_uri_photo( $item_id );
	$title = $this->get_constant( 'page_view' );

	return $this->build_photo_code_result_link( $name, $url, $title, '_self' );
}

function build_photo_site_link( $item_id, $item_siteurl, $embed_arr )
{
	$show  = false;
	$url   = null;
	$href  = null;

	$name    = 'site';
	$caption = $this->build_photo_code_caption( $name );
	$title   = $caption .' : '. $item_siteurl ;

// external site
	if ( $item_siteurl ) {
		$url  = $item_siteurl;
		$href = $this->_MODULE_URL.'/index.php?fct=visit&item_id='.$item_id;

// embed link
	} elseif ( isset( $embed_arr['embed_link'] ) && $embed_arr['embed_link'] ) {
		$url  = $embed_arr['embed_link'] ;
		$href = $url ;
	}

	$arr = $this->build_photo_code_result_value( $name, $url );
	$arr['href']    = $href;
	$arr['href_s']  = $this->sanitize( $href );
	$arr['title']   = $title;
	$arr['title_s'] = $this->sanitize( $title );
	$arr['target']  = '_blank';
	return $arr;
}

function build_photo_play_link( $item_id, $kind, $item_title, $feed, $cache )
{
	$show  = false;
	$url   = null;


	$arr = array(
		'show' => $show,
	);

	if ( ! $this->_perm_download ) {
		return $arr;
	}

	$name    = 'play';
	$caption = $this->build_photo_code_caption( $name );
	$title   = $item_title .' '. $caption ;
	$icon    = $this->_MODULE_URL.'/images/icons/webfeed.png';

// external playlist
	if ( $this->is_playlist_feed_kind( $kind ) ) {
		$url = $feed;

// playlist cache
	} elseif( $this->_perm_download && $this->is_playlist_dir_kind( $kind ) ) {
		$file = $this->_PLAYLISTS_DIR .'/'. $cache ;
		if ( empty($cache) || !file_exists($file) ) {
			return $arr;
		}

		$url  = $this->_MODULE_URL.'/index.php?fct=view_playlist&item_id='.$item_id;

// other
	} else {
		return $arr;
	}

	return $this->build_photo_code_result_link( $name, $url, $title );
}

function build_photo_code_embed_link( $item_row, $flash_arr, $embed_arr )
{
	$embed = null;
	$js    = null;

// embed
	if ( isset( $flash_arr['code_embed'] ) && $flash_arr['code_embed'] ) {
		$embed  = $flash_arr['code_embed'] ;
		$js     = $flash_arr['code_js'] ;

// flash player
	} elseif ( isset( $embed_arr['embed_embed'] ) && $embed_arr['embed_embed'] ) {
		$embed = $embed_arr['embed_embed'] ;
	}

	$arr = array(
		'embed'   => $this->build_photo_code_result_value( 'embed', $embed ),
		'js'      => $this->build_photo_code_result_value( 'js',    $js ),
	);
	return $arr;

}

function build_photo_code_result_link( $name, $url, $title, $target='_blank' )
{
	$arr = $this->build_photo_code_result_value( $name, $url );
	$arr['href']    = $url;
	$arr['href_s']  = $this->sanitize( $url );
	$arr['title']   = $title;
	$arr['title_s'] = $this->sanitize( $title );
	$arr['target']  = $target;
	return $arr;
}

function build_photo_code_result_value( $name, $value )
{
	$caption = $this->build_photo_code_caption( $name );

	$arr = array(
		'show'      => $this->is_photo_code_show_by_name( $name, $value ),
		'name'      => $name ,
		'caption'   => $caption ,
		'caption_s' => $this->sanitize( $caption ),
		'value'     => $value,
		'value_s'   => $this->sanitize( $value ),
	);
	return $arr;
}

function build_photo_code_caption( $name )
{
	return $this->get_constant( strtoupper( 'ITEM_CODEINFO_'.$name ) );
}

function is_photo_code_show_by_name( $name, $value )
{
	$const = constant( strtoupper( '_C_WEBPHOTO_CODEINFO_'.$name ) );
	return $this->is_photo_code_show_by_const( $const, $value );
}

function is_photo_code_show_by_const( $const, $value )
{
	if ( in_array( $const, $this->_codeinfo_array ) && $value ) {
		$this->_show_codebox = true ;
		return true ;
	}
	return false;
}

function get_photo_catid_row_or_post( $row )
{
	$cat_id = ( $row['item_cat_id'] > 0 ) ? $row['item_cat_id'] : $this->_get_cat_id ;
	return $cat_id;
}

function build_photo_gmap_param( $row )
{
	$show  = false;
	$icons = null;

	$photo = $this->_gmap_class->build_show( $row );
	if ( is_array($photo) ) {
		$show  = true;
		$icons = $this->_gmap_class->build_icon_list();
	}

	$arr = array(
		'show_gmap'       => $show,
		'gmap_photo'      => $photo,
		'gmap_icons'      => $icons ,
		'gmap_latitude'   => $row['item_gmap_latitude'] ,
		'gmap_longitude'  => $row['item_gmap_longitude'] ,
		'gmap_zoom'       => $row['item_gmap_zoom'] ,
		'gmap_lang_not_compatible' => $this->get_constant('GMAP_NOT_COMPATIBLE') ,
	);
	return $arr;
}

function build_photo_navi( $photo_id, $cat_id )
{
	$script   = $this->_uri_class->build_photo_pagenavi() ;
	$orderby  = $this->_sort_class->sort_to_orderby( $this->_get_order );
	$id_array = $this->_public_class->get_id_array_by_catid_orderby( $cat_id, $orderby );

	return $this->_photo_navi_class->build_navi( $script, $id_array, $photo_id );
}

function build_photo_tags_param( $photo_id )
{
	if ( ! $this->_has_tagedit ) {
		$arr = array(
			'show_tagedit' => false
		);
		return $arr;
	}

	$arr = array(
		'show_tagedit' => true ,
		'token_name'   => $this->get_token_name() ,
		'token_value'  => $this->get_token() ,
		'photo_id'     => $photo_id ,
		'tags'         => $this->build_photo_tags( $photo_id ) ,
	);
	return $arr;
}

function build_photo_tags( $photo_id )
{
	return $this->_tag_class->build_tags_for_photo( $photo_id, $this->_xoops_uid );
}

//---------------------------------------------------------
// xoops comment
//---------------------------------------------------------
function comment_view()
{
	$this->_comment_view_class->assgin_tmplate();
}

//=========================================================
// category
//=========================================================
function build_photos_param_in_category( $cat_id )
{
	$limit = $this->_MAX_PHOTOS;
	$start = $this->pagenavi_calc_start( $limit );

	$cat_param = $this->build_photos_in_category( $cat_id, $limit, $start );
	$title      = $cat_param['cat_title'] ;
	$total      = $cat_param['cat_photo_total'] ;
	$photo_rows = $cat_param['cat_photo_rows'] ;
	$show_sort  = $cat_param['cat_show_sort'] ;

	$show_photo = false ; 
	$photos     = null;

	if ( is_array($photo_rows) && count($photo_rows) ) {
		$photos = $this->build_photo_show_from_rows( $photo_rows );
	}

	if ( is_array($photos) && count($photos) ) {
		$show_photo = true ; 
	}

	$photo_param = array(
		'title_bread_crumb' => '' ,
		'sub_title_s'       => '' ,
		'show_photo'        => $show_photo , 
		'photo_total'       => $total ,
		'photos'            => $photos ,
		'show_sort'         => $show_sort ,
		'show_nomatch'      => $this->build_show_nomatch( $total ) ,
		'random_more_url_s' => $this->list_build_random_more( $total ) ,
		'param_kind'        => $this->build_uri_list_kind( 'category', $cat_id ) ,
	);

	$navi_param = $this->build_photos_navi_in_category( $cat_id, $total, $limit );

	return array_merge( $cat_param, $photo_param, $navi_param );
}

function build_photos_in_category( $cat_id, $limit, $start )
{
	$row = $this->_public_class->get_cat_row( $cat_id );

	if ( !is_array( $row ) ) {
		$arr = array(
			'cat_title'       => '',
			'cat_photo_total' => 0,
			'cat_photo_rows'  => null,
			'cat_show_sort'   => false,

			'photo_sum'      => 0,
			'show_catpath'   => false , 
			'catpath'        => '' , 
			'cat_desc_disp'  => '' , 
		);
		return $arr;
	}

	$cat_title = $row['cat_title'];

	$orderby = $this->get_orderby_by_post();

	$show_sort     = false ;
	$show_catpath  = false ;

	list( $photo_rows, $total, $this_sum ) =
		$this->_public_class->get_rows_total_by_catid( 
			$cat_id, $orderby, $limit, $start );

	if (( $this_sum > 1 ) ||
	    ( $this_sum == 0 ) && ( $total > 1 )) {
		$show_sort = true ;
	}

	$catpath = $this->build_cat_path( $cat_id );
	if ( is_array($catpath) && count($catpath) ) {
		$show_catpath = true;
	}

	$arr = array(
		'cat_title'       => $cat_title,
		'cat_photo_total' => $total,
		'cat_photo_rows'  => $photo_rows,
		'cat_show_sort'   => $show_sort,

		'photo_sum'      => $this_sum,
		'show_catpath'   => $show_catpath , 
		'catpath'        => $catpath , 
		'cat_desc_disp'  => $this->build_cat_desc_disp( $row ) , 
	);

	return $arr;

}

function build_photos_navi_in_category( $cat_id, $total, $limit )
{
	if ( $this->_navi_mode == 'kind' ) {
		$url = $this->build_uri_list_navi_url_kind( 'category', $cat_id, $this->_get_kind );
	} else {
		$url = $this->build_uri_list_navi_url( $this->_get_sort );
	}
	return $this->build_navi( $url, $total, $limit, $this->_get_page );
}

// --- class end ---
}

?>