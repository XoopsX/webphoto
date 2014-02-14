<?php
// $Id: photo.php,v 1.8 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-11-11 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// build_photo_file_link_dual()
// 2010-11-11 K.OHWADA
// full_path_exists
// 2010-11-03 K.OHWADA
// webphoto_uri_parse
// 2010-10-01 K.OHWADA
// _C_WEBPHOTO_CODEINFO_SHOW_LIST -> explode_ini('view_codeinfo_list')
// 2010-06-06 K.OHWADA
// build_photo_embed_text()
// 2010-01-10 K.OHWADA
// webphoto_show_main_photo -> webphoto_photo
// get_photo_row()
// 2009-12-06 K.OHWADA
// build_uri_list_navi_url()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo
//=========================================================
class webphoto_photo extends webphoto_show_photo
{
	var $_flash_class;
	var $_embed_class;
	var $_photo_navi_class;
	var $_item_public_class;
	var $_comment_view_class;
	var $_catlist_class;
	var $_rate_check_class ;
	var $_public_class;
	var $_uri_parse_class;

	var $_cfg_cat_child;
	var $_cfg_embed_width;
	var $_cfg_embed_height;

	var $_param      = null;
	var $_param_out  = null;
	var $_list_mode  = null;
	var $_navi_mode  = null;

// for photo
	var $_get_photo_id;
	var $_get_cat_id;
	var $_get_kind;
	var $_orderby;

	var $_photo_row = null;
	var $_has_tagedit    = false;
	var $_show_codebox   = false ;
	var $_perm_download  = false;
	var $_codeinfo_array = null;

	var $_CODEINFO_LIST;
	var $_FILE_LIST;

	var $_TIME_SUCCESS = 1;
	var $_TIME_PENDING = 3;
	var $_TIME_FAILED  = 5;

// for future
	var $_get_viewtype = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo( $dirname , $trust_dirname )
{
	$this->webphoto_show_photo( $dirname , $trust_dirname );

	$this->_flash_class       
		=& webphoto_flash_player::getInstance( $dirname, $trust_dirname  );
	$this->_embed_class       
		=& webphoto_embed::getInstance( $dirname, $trust_dirname );
	$this->_item_public_class 
		=& webphoto_item_public::getInstance( $dirname, $trust_dirname );
	$this->_photo_navi_class 
		=& webphoto_photo_navi::getInstance( $dirname , $trust_dirname );
	$this->_catlist_class  
		=& webphoto_inc_catlist::getSingleton( $dirname , $trust_dirname );
	$this->_rate_check_class 
		=& webphoto_rate_check::getInstance( $dirname, $trust_dirname );
	$this->_public_class     
		=& webphoto_photo_public::getInstance( $dirname, $trust_dirname  );
	$this->_uri_parse_class     
		=& webphoto_uri_parse::getInstance( $dirname, $trust_dirname  );

	$this->_photo_navi_class->set_mark_id_prev( '<b>'. $this->get_constant('NAVI_PREVIOUS') .'</b>' );
	$this->_photo_navi_class->set_mark_id_next( '<b>'. $this->get_constant('NAVI_NEXT') .'</b>' );

	$this->_comment_view_class =& webphoto_d3_comment_view::getInstance();
	$this->_comment_view_class->init( $dirname );

	$this->_cfg_cat_child    = $this->get_config_by_name( 'cat_child' );
	$this->_cfg_embed_width  = $this->get_config_by_name( 'embed_width' );
	$this->_cfg_embed_height = $this->get_config_by_name( 'embed_height' );

	$this->_has_tagedit   = $this->_perm_class->has_tagedit();

	$this->_FILE_LIST          = explode( '|', _C_WEBPHOTO_FILE_LIST );

	$this->_CODEINFO_LIST = $this->explode_ini('view_codeinfo_list');
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo( $dirname , $trust_dirname );
	}
	return $instance;
}

//=========================================================
// main photo
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
	$this->_get_photo_id = $this->_uri_parse_class->get_id_by_key( 'photo_id' ) ;
	$this->_get_cat_id   = $this->_uri_parse_class->get_int_by_key( 'cat_id' );
	$this->_orderby      = $this->_uri_parse_class->get_photo_orderby();

	$row = $this->_item_public_class->get_item_row( $this->_get_photo_id ) ;
	if( !is_array($row) ) {
		redirect_header( $this->_MODULE_URL.'/' , $this->_TIME_FAILED , $this->get_constant('NOMATCH_PHOTO') ) ;
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
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAILED , _NOPERM ) ;
			exit ;

		case _C_WEBPHOTO_ERR_TOKEN:
			$msg = 'Token Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_token_errors();
			}
			redirect_header( $redirect_this_url, $this->_TIME_FAILED , $msg );
			exit();

		case _C_WEBPHOTO_ERR_DB:
			$msg = 'DB Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_format_error();
			}
			redirect_header( $redirect_this_url, $this->_TIME_FAILED, $msg ) ;
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
	$post_array = $this->_tag_build_class->str_to_tag_name_array( $post_tags );

	$ret = $this->_tag_build_class->update_tags( $photo_id, $this->_xoops_uid, $post_array );
	if ( !$ret ) {
		return _C_WEBPHOTO_ERR_DB;
	}

	return 0;
}

function get_photo_row()
{
	return $this->_photo_row;
}

//---------------------------------------------------------
// show main
//---------------------------------------------------------
function build_photo_for_photo( $row )
{
	$arr1 = $this->build_photo_by_row( $row );
	$this->_perm_download = $arr1['perm_download'];

	$arr2 = $this->build_photo_flash_player( $row, $arr1 ) ;
	$arr3 = $this->build_photo_embed_link( $row );
	$arr4 = $this->build_photo_code( $row, $arr1, $arr2, $arr3 );

	$arr = array_merge( $arr1, $arr2, $arr3, $arr4 );
	return $arr;
}

function build_photo_by_row( $row )
{
	$arr = $this->build_photo_show( $row ) ;
	$arr['can_rate'] = $this->_rate_check_class->can_rate( $row['item_id'] ) ;
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

	$flash = null ;
	$embed = null ;
	$js    = null ;

	if ( $displaytype >= _C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT ) {

// countup views if not submitter or admin.
		if ( $this->check_not_owner( $uid ) ) {
			$this->_item_handler->countup_views( $item_id, true );
		}

		$flash              = $this->_flash_class->build_movie_by_item_row(     $item_row );
		list( $embed, $js ) = $this->_flash_class->build_code_embed_by_item_row( $item_row );
	}

	$arr = array(
		'flash_player' => $flash ,
		'code_embed'   => $embed  ,
		'code_js'      => $js ,
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

// default if empty
	if ( empty($width) ) {
		$width = $this->_cfg_embed_width ;
	}
	if ( empty($height) ) {
		$height = $this->_cfg_embed_height ;
	}

// when already set text & suteurl
	if ( $text && $siteurl) {
		$embed = $this->build_photo_embed_text( $text, $width, $height );
		$link  = $siteurl;

// when already set text, not set link
	} elseif ( $text ) {
		$embed = $this->build_photo_embed_text( $text, $width, $height );
		list( $dummy, $link ) 
			= $this->_embed_class->build_embed_link( $type, $src, $width, $height );

// when not set embed & link
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

function build_photo_embed_text( $text, $width, $height )
{
	$text = str_replace( _C_WEBPHOTO_EMBED_REPLACE_WIDTH,  $width,  $text );
	$text = str_replace( _C_WEBPHOTO_EMBED_REPLACE_HEIGHT, $height, $text );
	return $text;
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

// list up cont url
	$cont_values = array();
	if ( isset( $param['cont']['dual'] ) ) {
		$cont_values[] = $param['cont']['download']['value'];
		if ( isset( $param['cont']['image'] )) {
			$cont_values[] = $param['cont']['image']['value'];
		}
	} else {
		$cont_values[] = $param['cont']['value'];
	}

// file codeinfo 
	$codes1 = array();
	foreach ( $this->_CODEINFO_LIST as $name ) 
	{
		if ( isset( $param[ $name ]['dual'] ) ) {
			if ( isset( $param[ $name ]['image'] )) {
				$codes1[] = $param[ $name ]['image'];
			}
			$codes1[] = $param[ $name ]['download'];
		} else {
			$codes1[] = $param[ $name ];
		}
	}

// set no show if same as cont url
	if ( is_array($cont_values) && is_array($cont_values) ) {
		$codes2 = array();
		foreach ( $codes1 as $code ) 
		{
			if ( isset($code['name']) && isset($code['value']) &&
				( $code['name'] != 'cont' ) && 
				in_array( $code['value'], $cont_values ) ) {
				$code['show'] = false;
			}
			$codes2[] = $code;
		}
	} else {
		$codes2 = $codes1;
	}

	$arr = array();
	$arr['codes'] = $codes2;

// always last
	$arr['show_codebox'] = $this->_show_codebox ;

	return $arr;
}

function build_photo_file_link( $item_row, $show_arr, $name )
{
	$item_id             = $item_row['item_id'] ;
	$item_detail_onclick = $item_row['item_detail_onclick'] ;

	$show     = false;
	$show_img = false;
	$url      = null;
	$title    = null;
	$target   = null;
	$filesize = null;

	$onclick_download = false;
	$cont_download    = false;

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
			$item_name     = 'item_external_url' ;
			break;

		case 'thumb' :
			$item_name  = 'item_external_thumb' ;
			break;

		case 'middle' :
			$item_name  = 'item_external_middle' ;
			break;
	}

	$caption   = $this->build_photo_code_caption( $name );
	$lang_down = $this->get_constant( 'DOWNLOAD' );
	$file_row  = $this->get_show_file_row( $show_arr, $file_kind ) ; 

// if file exists
	if ( is_array($file_row) ) {
		return 	$this->build_photo_file_link_dual( $name, $item_id, $file_row );

// if external
	} elseif ( $item_name ) {
		$item_url = $item_row[ $item_name ] ;
		if ( $item_url ) {
			$url    = $item_url ;
			$title  = $caption ;
			$target = '_blank';
		}
	}

	$arr = $this->build_photo_code_result_link( $name, $url, $title, $target );
	$arr['filesize'] = $filesize;
	return $arr;
}

function build_photo_file_link_dual( $name, $item_id, $file_row )
{
	$filesize = null;

	$file_kind  = constant( strtoupper( '_C_WEBPHOTO_FILE_KIND_'.$name ) );
	$caption    = $this->build_photo_code_caption( $name );
	$lang_down  = $this->get_constant( 'DOWNLOAD' );

	$url    = $file_row['file_url'] ;
	$ext    = $file_row['file_ext'] ;
	$size   = $file_row['file_size'] ;
	$path   = $file_row['file_path'] ;
	$file   = $file_row['full_path'] ;
	$exists = $file_row['full_path_exists'] ;

	$base_url1 = $this->_MODULE_URL.'/index.php?fct=download';
	$title1    = $lang_down .' '. $caption ;
	$target1   = '_self';
	$url1       = $url;

	$base_url2 = $this->_MODULE_URL.'/index.php?fct=image';
	$title2    = $caption ;
	$target2   = '_blank';
	$url2      = $url;

	if ( $exists && $file ) {
		$url1  = $base_url1 .'&item_id='. $item_id .'&file_kind='. $file_kind;
		$url2  = $base_url2 .'&item_id='. $item_id .'&file_kind='. $file_kind;

		if ( $size > 0 ) {
			$filesize = $this->build_show_filesize( $size );
		}
	}

	$arr1 = $this->build_photo_code_result_link( $name, $url1, $title1, $target1 );
	$arr1['filesize'] = $filesize;
	$arr1['show_img_download'] = true;
	$arr1['show_img_view']     = false;

	$arr2 = $this->build_photo_code_result_link( $name, $url2, $title2, $target2 );
	$arr2['filesize'] = $filesize;
	$arr2['show_img_download'] = false;
	$arr2['show_img_view']     = true;

	$arr = array();
	$arr['dual']     = true;
	$arr['show']     = true;
	$arr['download'] = $arr1;

	if ( $this->is_image_ext( $ext ) ) {
		$arr['image'] = $arr2;
	}

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
	$id_array = $this->_public_class->get_id_array_by_catid_orderby( $cat_id, $this->_orderby );

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
	return $this->_tag_build_class->build_tags_for_photo( $photo_id, $this->_xoops_uid );
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