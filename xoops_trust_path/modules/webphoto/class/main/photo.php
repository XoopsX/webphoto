<?php
// $Id: photo.php,v 1.14 2009/03/20 04:18:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-03-15 K.OHWADA
// add_box_list() -> add_show_js_windows()
// 2009-01-25 K.OHWADA
// build_movie() -> build_movie_by_item_row()
// 2008-12-12 K.OHWADA
// webphoto_item_public
// 2008-12-07 K.OHWADA
// build_photo_show() -> build_photo_show_main()
// 2008-11-16 K.OHWADA
// _build_code()
// refresh_cache_by_item_row()
// 2008-10-01 K.OHWADA
// update_hits() -> countup_hits()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// QR code
// 2008-07-01 K.OHWADA
// used build_uri_photo() build_photo_pagenavi()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_photo
//=========================================================
class webphoto_main_photo extends webphoto_show_main
{
	var $_flash_class;
	var $_embed_class;
	var $_d3_comment_view_class;
	var $_photo_navi_class;
	var $_item_public_class;

	var $_get_photo_id;
	var $_get_cat_id;
	var $_get_order;

	var $_row = null;
	var $_has_tagedit    = false;
	var $_show_codebox   = false ;
	var $_perm_download  = false;
	var $_codeinfo_array = null;

	var $_TIME_SUCCESS = 1;
	var $_TIME_FAIL    = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_photo( $dirname , $trust_dirname )
{
	$this->webphoto_show_main( $dirname , $trust_dirname );
	$this->set_mode( 'photo' );
	$this->set_flag_highlight( true );

	$this->_flash_class       =& webphoto_flash_player::getInstance( $dirname );
	$this->_embed_class       =& webphoto_embed::getInstance( $dirname, $trust_dirname );
	$this->_item_public_class =& webphoto_item_public::getInstance( $dirname, $trust_dirname );

	$this->_photo_navi_class =& webphoto_photo_navi::getInstance( $dirname );
	$this->_photo_navi_class->set_mark_id_prev( '<b>'. $this->get_constant('NAVI_PREVIOUS') .'</b>' );
	$this->_photo_navi_class->set_mark_id_next( '<b>'. $this->get_constant('NAVI_NEXT') .'</b>' );

	$this->_comment_view_class =& webphoto_d3_comment_view::getInstance();
	$this->_comment_view_class->init( $dirname );

	$this->_has_tagedit = $this->_perm_class->has_tagedit();

	$this->init_preload();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_photo( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// check
//---------------------------------------------------------
function check_edittag()
{
	$this->_check();

	if ( $this->_is_edittag() ) {
		$this->_edittag();
		exit();
	}

}

function _check()
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
	$this->_row = $row;
}

//---------------------------------------------------------
// edittag
//---------------------------------------------------------
function _is_edittag()
{
	if ( $this->_post_class->get_post('op') == 'tagedit' ) {
		return true;
	}
	return false;
}

function _edittag()
{
	$redirect_this_url = $this->build_uri_photo( $this->_get_photo_id );

	$ret = $this->_excute_edittag();
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

function _excute_edittag()
{
	if ( ! $this->_has_tagedit ) {
		return _C_WEBPHOTO_ERR_NO_PERM;
	}

	if ( ! $this->check_token() ) { 
		return _C_WEBPHOTO_ERR_TOKEN;
	}

// load row
	$row = $this->_row;

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
// main
//---------------------------------------------------------
function main()
{
// load row
	$row = $this->_row;
	$photo_id  = $row['item_id'];
	$photo_uid = $row['item_uid'];

// for xoops comment & notification
	$_GET['photo_id'] = $photo_id;

	$this->set_keyword_array_by_get();

// countup hits
	if ( $this->check_not_owner( $row['item_uid'] ) ) {
		$this->_item_handler->countup_hits( $photo_id, true );
	}

	$total_all  = $this->_public_class->get_count();
	$photo      = $this->_build_photo_show_photo( $row );
	$gmap_param = $this->_build_gmap_param( $row );
	$show_gmap  = $gmap_param['show_gmap'];
	$tags_param = $this->_build_tags_param( $photo_id );
	$noti_param = $this->build_notification_select();
	$cat_id     = $this->_get_catid_row_or_post( $row ) ;

	$this->assign_xoops_header( 'category', $cat_id, $show_gmap );

	$this->create_mobile_qr( $photo_id );

	$arr = array(
		'xoops_pagetitle' => $photo['title_s'],
		'photo'           => $photo,
		'sub_title'       => $this->build_cat_sub_title( $cat_id ),
		'photo_nav'       => $this->_build_navi( $photo_id, $cat_id ),
		'use_box_js'      => $this->_USE_BOX_JS ,
		'show_photo_desc' => true ,
		'show_photo_exif' => true ,
		'photo_total_all' => $total_all ,
		'lang_thereare'   => $this->build_lang_thereare( $total_all ) ,
		'mobile_email'    => $this->get_mobile_email() ,
		'mobile_url'      => $this->build_mobile_url( $photo_id ) ,
		'mobile_qr_image' => $this->build_mobile_filename( $photo_id ) ,
	);

	$ret = array_merge( 
		$arr, $gmap_param, $tags_param, $noti_param, 
		$this->build_init_show( $this->_mode ) );
	return $this->add_show_js_windows( $ret );
}

//---------------------------------------------------------
// show main
//---------------------------------------------------------
function _build_photo_show_photo( $row )
{
	$arr1 = $this->build_photo_show_main( $row );

	$this->_perm_download = $arr1['perm_download'] ;

	$arr2 = $this->_build_flash_player( $row, $arr1 ) ;
	$arr3 = $this->_build_embed_link( $row );
	$arr4 = $this->_build_code( $row, $arr1, $arr2, $arr3 );

	$arr = array_merge( $arr1, $arr2, $arr3, $arr4 );
	return $arr;
}

//---------------------------------------------------------
// flash player
//---------------------------------------------------------
function _build_flash_player( $item_row, $show_arr )
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
function _build_embed_link( $item_row )
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
function _build_code( $item_row, $show_arr, $flash_arr, $embed_arr )
{
	$item_id  = $item_row['item_id'] ;
	$title    = $item_row['item_title'] ;
	$siteurl  = $item_row['item_siteurl'] ;
	$kind     = $item_row['item_kind'] ;
	$feed     = $item_row['item_playlist_feed'] ;
	$cache    = $item_row['item_playlist_cache'] ;

	$this->_codeinfo_array = $this->_item_handler->get_codeinfo_array( $item_row );

	list( $cont_url, $cont_link, $cont_size ) =
		$this->_build_file_link( $item_row, $show_arr, _C_WEBPHOTO_FILE_KIND_CONT );

	list( $thumb_url, $thumb_link, $thumb_size ) =
		$this->_build_file_link( $item_row, $show_arr, _C_WEBPHOTO_FILE_KIND_THUMB );

	list( $middle_url, $middle_link, $middle_size ) =
		$this->_build_file_link( $item_row, $show_arr, _C_WEBPHOTO_FILE_KIND_MIDDLE );

	list( $flash_url, $flash_link, $flash_size ) =
		$this->_build_file_link( $item_row, $show_arr, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH );

	list( $pdf_url, $pdf_link, $pdf_size ) =
		$this->_build_file_link( $item_row, $show_arr, _C_WEBPHOTO_FILE_KIND_PDF );

	list( $swf_url, $swf_link, $swf_size ) =
		$this->_build_file_link( $item_row, $show_arr, _C_WEBPHOTO_FILE_KIND_SWF );

	list( $site_url, $site_link ) =
		$this->_build_site_link( $item_id, $siteurl, $embed_arr );

	list( $play_url, $play_link ) =
		$this->_build_play_link( $item_id, $kind, $title, $feed, $cache );

	list( $embed, $js ) =
		$this->_build_code_embed_link( $item_row, $flash_arr, $embed_arr );

	if ( $cont_url == $thumb_url ) {
		$thumb_url  = null;
		$thumb_link = null;
	}

	if ( $cont_url == $middle_url ) {
		$middle_url  = null;
		$middle_link = null;
	}

	$show_code_cont     = $this->_has_code_parm( _C_WEBPHOTO_CODEINFO_CONT    , $cont_url ) ;
	$show_code_thumb    = $this->_has_code_parm( _C_WEBPHOTO_CODEINFO_THUMB   , $thumb_url ) ;
	$show_code_middle   = $this->_has_code_parm( _C_WEBPHOTO_CODEINFO_MIDDLE  , $middle_url ) ;
	$show_code_flash    = $this->_has_code_parm( _C_WEBPHOTO_CODEINFO_FLASH   , $flash_url ) ;
	$show_code_pdf      = $this->_has_code_parm( _C_WEBPHOTO_CODEINFO_PDF     , $pdf_url ) ;
	$show_code_swf      = $this->_has_code_parm( _C_WEBPHOTO_CODEINFO_SWF     , $swf_url ) ;
	$show_code_page     = $this->_has_code_parm( _C_WEBPHOTO_CODEINFO_PAGE    , true ) ;
	$show_code_site     = $this->_has_code_parm( _C_WEBPHOTO_CODEINFO_SITE    , $site_link );
	$show_code_play     = $this->_has_code_parm( _C_WEBPHOTO_CODEINFO_PLAY    , $play_url );
	$show_code_embed    = $this->_has_code_parm( _C_WEBPHOTO_CODEINFO_EMBED   , $embed );
	$show_code_js       = $this->_has_code_parm( _C_WEBPHOTO_CODEINFO_JS      , $js ) ;

	$arr = array(
		'show_codebox'         => $this->_show_codebox ,
		'show_code_cont'       => $show_code_cont ,
		'show_code_thumb'      => $show_code_thumb ,
		'show_code_middle'     => $show_code_middle ,
		'show_code_flash'      => $show_code_flash ,
		'show_code_pdf'        => $show_code_pdf ,
		'show_code_swf'        => $show_code_swf ,
		'show_code_page'       => $show_code_page ,
		'show_code_site'       => $show_code_site ,
		'show_code_play'       => $show_code_play ,
		'show_code_embed'      => $show_code_embed ,
		'show_code_js'         => $show_code_js ,
		'code_cont_url_s'      => $this->sanitize( $cont_url ) ,
		'code_cont_link'       => $cont_link ,
		'code_cont_size'       => $cont_size ,
		'code_thumb_url_s'     => $this->sanitize( $thumb_url ) ,
		'code_thumb_link'      => $thumb_link ,
		'code_thumb_size'      => $thumb_size ,
		'code_middle_url_s'    => $this->sanitize( $middle_url ) ,
		'code_middle_link'     => $middle_link ,
		'code_middle_size'     => $middle_size ,
		'code_flash_url_s'     => $this->sanitize( $flash_url ) ,
		'code_flash_link'      => $flash_link ,
		'code_flash_size'      => $flash_size ,
		'code_pdf_url_s'       => $this->sanitize( $pdf_url ) ,
		'code_pdf_link'        => $pdf_link ,
		'code_pdf_size'        => $pdf_size ,
		'code_swf_url_s'       => $this->sanitize( $swf_url ) ,
		'code_swf_link'        => $swf_link ,
		'code_swf_size'        => $swf_size ,
		'code_page_url'        => $this->build_uri_photo( $item_id ) ,
		'code_site_url_s'      => $this->sanitize( $site_url ) ,
		'code_site_link'       => $site_link ,
		'code_play_url_s'      => $this->sanitize( $play_url ) ,
		'code_play_link'       => $play_link ,
		'code_embed_s'         => $this->sanitize( $embed ) ,
		'code_js_s'            => $this->sanitize( $js ) ,
	);
	return $arr;
}

function _build_file_link( $item_row, $show_arr, $file_kind )
{
	$url  = null;
	$link = null;
	$size = null;

	if ( ! $this->_perm_download ) {
		return array( $url, $link, $size );
	}

	$img       = null;
	$item_name = null ;

	switch ( $file_kind )
	{
		case _C_WEBPHOTO_FILE_KIND_CONT :
			$lang_const = 'ITEM_CODEINFO_CONT' ;
			$item_name  = 'item_external_url' ;
			break;

		case _C_WEBPHOTO_FILE_KIND_THUMB :
			$lang_const = 'ITEM_CODEINFO_THUMB' ;
			$item_name  = 'item_external_thumb' ;
			break;

		case _C_WEBPHOTO_FILE_KIND_MIDDLE :
			$lang_const = 'ITEM_CODEINFO_MIDDLE' ;
			$item_name  = 'item_external_middle' ;
			break;

		case _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH :
			$lang_const = 'ITEM_CODEINFO_FLASH' ;
			break;

		case _C_WEBPHOTO_FILE_KIND_VIDEO_DOCOMO :
			$lang_const = 'ITEM_CODEINFO_DOCOMO' ;
			break;

		case _C_WEBPHOTO_FILE_KIND_PDF :
			$lang_const = 'ITEM_CODEINFO_PDF' ;
			break;

		case _C_WEBPHOTO_FILE_KIND_SWF :
			$lang_const = 'ITEM_CODEINFO_SWF' ;
			break;

		default :
			return array( $url, $link, $size );
			break;	
	}

	$item_id   = $item_row['item_id'] ;
	$icon      = $this->_MODULE_URL.'/images/icons/download.png';
	$lang      = $this->get_constant( $lang_const );
	$lang_down = $this->get_constant( 'DOWNLOAD' );
	$file_row = $this->get_show_file_row( $show_arr, $file_kind ) ; 

// if file exists
	if ( is_array($file_row) ) {
		$url       = $file_row['file_url'] ;
		$ext       = $file_row['file_ext'] ;
		$file_size = $file_row['file_size'] ;
		$path      = $file_row['file_path'] ;
		$file      = XOOPS_ROOT_PATH .'/'. $path ;

		if ( $this->is_image_ext( $ext ) ) {
			$base_url = $this->_MODULE_URL.'/index.php?fct=image';
			$title    = $lang ;

		} else {
			$base_url = $this->_MODULE_URL.'/index.php?fct=download';
			$title    = $lang_down .' '. $lang ;
			$down_s   = $this->sanitize($lang_down);
			$img      = ' <img src="'. $icon .'" border="0" alt="'. $down_s .'" title="'. $down_s .'" > ';
		}

		if ( $path && file_exists($file) ) {
			$url  = $base_url .'&item_id='. $item_id .'&file_kind='. $file_kind;

			if ( $file_size > 0 ) {
				$size = $img .' ( '. $this->build_show_filesize( $file_size ) .' ) ';
			}
		}

// if external
	} elseif ( $item_name ) {
		$item_url = $item_row[ $item_name ] ;
		if ( $item_url ) {
			$url   = $item_url ;
			$title = $lang ;
		}
	}

	if ( $url ) {
		$url_s    = $this->sanitize( $url ) ;
		$title_s  = $this->sanitize( $title ) ;
		$link     = '<a href="'.$url_s.'" target="_blank" title="'.$title_s.'">';
		$link    .= $lang . '</a>' ;
	}

	return array( $url, $link, $size );
}

function _build_site_link( $item_id, $item_siteurl, $embed_arr )
{
	$url  = null;
	$link = null;

	$lang_site = $this->get_constant( 'ITEM_CODEINFO_SITE' );
	$title     = $lang_site .' : '. $item_siteurl ;

// external site
	if ( $item_siteurl ) {
		$url  = $item_siteurl;
		$href = $this->_MODULE_URL.'/index.php?fct=visit&item_id='.$item_id;

// embed link
	} elseif ( isset( $embed_arr['embed_link'] ) && $embed_arr['embed_link'] ) {
		$url  = $embed_arr['embed_link'] ;
		$href = $url ;
	}

	if ( $url ) {
		$title_s  = $this->sanitize( $title ) ;
		$href_s   = $this->sanitize( $href );
		$link     = '<a href="'. $href_s .'" target="_blank" title="'. $title_s .'">';
		$link    .= $lang_site .'</a>';
	}

	return array( $url, $link );
}

function _build_play_link( $item_id, $kind, $item_title, $feed, $cache )
{
	$url  = null;
	$link = null;

	if ( ! $this->_perm_download ) {
		return array( $url, $link );
	}

	$lang_play = $this->get_constant( 'ITEM_CODEINFO_PLAY' );
	$icon      = $this->_MODULE_URL.'/images/icons/webfeed.png';

// external playlist
	if ( $this->is_playlist_feed_kind( $kind ) ) {
		$url = $feed;

// playlist cache
	} elseif( $this->_perm_download && $this->is_playlist_dir_kind( $kind ) ) {
		$file = $this->_PLAYLISTS_DIR .'/'. $cache ;
		if ( empty($cache) || !file_exists($file) ) {
			return array( $url, $link );
		}

		$url  = $this->_MODULE_URL.'/index.php?fct=view_playlist&item_id='.$item_id;

// other
	} else {
		return array( $url, $link );
	}

	if ( $url ) {
		$url_s    = $this->sanitize($url);
		$title_s  = $this->sanitize( $item_title .' '. $lang_play ) ;
		$link     = '<a href="'. $url_s .'" target="_blank" title="'.$title_s.'">';
		$link    .= $lang_play .'</a>'; 
	}

	return array( $url, $link );
}

function _build_code_embed_link( $item_row, $flash_arr, $embed_arr )
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

	return array( $embed, $js );
}

function _has_code_parm( $const, $value )
{
	if ( in_array( $const, $this->_codeinfo_array ) && $value ) {
		$this->_show_codebox = true ;
		return true ;
	}
	return false;
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function _get_catid_row_or_post( $row )
{
	$cat_id = ( $row['item_cat_id'] > 0 ) ? $row['item_cat_id'] : $this->_get_cat_id ;
	return $cat_id;
}

function _build_gmap_param( $row )
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

function _build_navi( $photo_id, $cat_id )
{
	$script   = $this->_uri_class->build_photo_pagenavi() ;
	$orderby  = $this->_sort_class->sort_to_orderby( $this->_get_order );
	$id_array = $this->_public_class->get_id_array_by_catid_orderby( $cat_id, $orderby );

	return $this->_photo_navi_class->build_navi( $script, $id_array, $photo_id );
}

function _build_tags_param( $photo_id )
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
		'tags'         => $this->_build_tags( $photo_id ) ,
	);
	return $arr;
}

function _build_tags( $photo_id )
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

// --- class end ---
}

?>