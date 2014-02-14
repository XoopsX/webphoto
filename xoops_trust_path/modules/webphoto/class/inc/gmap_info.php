<?php
// $Id: gmap_info.php,v 1.7 2011/12/27 00:27:22 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// webphoto_lib_mysql_utility
// 2011-06-04 K.OHWADA
// webphoto_inc_uri
// 2010-06-06 K.OHWADA
// show youtube thumb image
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_gmap_info
//=========================================================
class webphoto_inc_gmap_info
{
	var $_uri_class;
	var $_mysql_utility_class ;

	var $_has_editable     = false ;

	var $_xoops_uid       = 0 ;
	var $_module_id       = 0 ;
	var $_is_module_admin = false ;

	var $_lang_title_edit = 'edit';

	var $_DIRNAME    ;
	var $_MODULE_URL ;
	var $_MODULE_DIR ;
	var $_ICONS_URL  ;
	var $_IMG_EDIT   ;
	var $_IMAGE_EXTS ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_gmap_info( $dirname , $trust_dirname )
{
	$this->_DIRNAME    = $dirname ;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'. $dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'. $dirname;
	$this->_ICONS_URL  = $this->_MODULE_URL .'/images/icons';

	$this->_IMAGE_EXTS = explode( '|', _C_WEBPHOTO_IMAGE_EXTS );

	$this->_init_xoops_param( $dirname );
	$this->_init_permission(  $dirname , $trust_dirname );

	$this->_uri_class =& webphoto_inc_uri::getSingleton( $dirname );

	$this->_mysql_utility_class =& webphoto_lib_mysql_utility::getInstance();
}

function &getSingleton( $dirname , $trust_dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_gmap_info( $dirname , $trust_dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// gmap
//---------------------------------------------------------
function build_info( $param )
{
	return $this->build_info_default( $param );
}

function build_info_default( $param )
{
	$info  = '<div style="text-align:center; font-size: 80%; ">';
	$info .= $this->build_info_thumb(    $param );
	$info .= $this->build_info_title(    $param );
	$info .= $this->build_info_author(   $param );
	$info .= $this->build_info_datetime( $param );
	$info .= $this->build_info_place(    $param );
	$info .= '</div>';

	return $info;
}

function build_info_thumb( $param )
{
	$a_photo   = $this->build_a_photo(   $param );
	$img_thumb = $this->build_img_thumb( $param );

	$str = null;
	if ( $img_thumb && $a_photo ) {
		$str = $a_photo . $img_thumb .'</a><br />';
	} elseif ( $img_thumb ) {
		$str = $img_thumb .'<br />';
	}
	return $str;
}

function build_info_title( $param )
{
	$str = '';

	$title_s = $this->sanitize( $param['item_title'] );
	$a_photo = $this->build_a_photo( $param );

	if ( $this->has_editable_by_uid( $param['item_uid'] ) ) {
		$href = $this->build_uri_edit( $param['item_id'] );
		$str .= '<a href="'. $href .'" target="_top" >';
		$str .= $this->build_img_edit() ;
		$str .= '</a> ';
	}

	if ( $title_s && $a_photo ) {
		$str .= $a_photo . $title_s .'</a><br />';
	} elseif ( $title_s ) {
		$str .= $title_s .'<br />';
	}
	return $str;
}

function build_info_author( $param )
{
	$uid   = intval( $param['item_uid'] );
	$href  = $this->build_uri_user( $uid ) ;
	$uname = $this->get_xoops_uname_by_uid( $uid );
	if ( $uid > 0 ) {
		$str  = '<a href="'. $href .'">';
		$str .= $uname .'</a><br />';
	} else {
		$str = $uname .'<br />';
	}
	return $str;
}

function build_info_datetime( $param )
{
	$datetime_disp = $this->mysql_datetime_to_str( $param['item_datetime'] );
	if ( $datetime_disp ) {
		$str = $datetime_disp .'<br />';
		return $str;
	}
	return null;
}

function build_info_place( $param )
{
	$place_s = $this->sanitize( $param['item_place'] );
	if ( $place_s ) {
		$str = $place_s .'<br />';
		return $str;
	}
	return null;
}

function build_img_thumb( $param )
{
	$title_s = $this->sanitize( $param['item_title'] );
	$url_s   = $this->sanitize( $param['thumb_url'] );
	$width   = intval( $param['thumb_width'] );
	$height  = intval( $param['thumb_height'] );

	$url_external_s = $this->sanitize( $param['item_external_thumb'] );
	$kind           = $param['item_kind'];
	$is_embed       = $this->is_embed_kind( $kind );

	$img = null;

// if thumb
	if ( $url_s && $width && $height ) {
		$img = '<img src="'. $url_s .'" width="'. $width .'"  height="'. $height .'" alt="'. $title_s .' "border="0" />';

	} elseif ( $url_s ) {
		$img = '<img src="'. $url_s .'" alt="'. $title_s .'" border="0" />';

// if embed
	} elseif ( $url_external_s && $is_embed ) {
		$img = '<img src="'. $url_external_s .'" alt="'. $title_s .'" border="0" />';
	}
	return $img;
}

function build_a_photo( $param )
{
	$href   = $this->build_href_photo(   $param );
	$target = $this->build_target_photo( $param );
	if ( $href && $target ) {
		$str = '<a href="'. $href .'" target="'. $target .'">';
		return $str;
	}
	return null;
}

function build_href_photo( $param )
{
	return $this->build_uri_photo( $param['item_id'] ) ;
}

function build_target_photo( $param )
{
	$str = '_top';
	if ( ! $this->check_normal_ext( $param ) ) {
		$str = '_blank';
	}
	return $str;
}

function check_normal_ext( $param )
{
	return $this->is_image_ext( $param['item_ext'] );
}

function is_embed_kind( $kind )
{
	if ( $kind == _C_WEBPHOTO_ITEM_KIND_EMBED ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// uri
//---------------------------------------------------------
function build_uri_photo( $item_id )
{
	return $this->build_uri( 'photo', intval($item_id) );
}

function build_uri_user( $uid )
{
	return $this->build_uri( 'user', intval($uid) );
}

function build_uri_search( $query )
{
	return $this->build_uri( 'search', rawurlencode($query) );
}

function build_uri( $fct, $param )
{
// BUG: wrong url
	return $this->_uri_class->build_full_uri_mode_param( $fct, $param );
}

function build_uri_edit( $item_id )
{
	$str = $this->_MODULE_URL.'/index.php?fct=edit&amp;photo_id='. $item_id ;
	return $str;
}

function build_img_edit()
{
	$str = '<img src="'. $this->_ICONS_URL.'/edit.png" width="18" height="15" border="0" alt="' . $this->_lang_title_edit .'" title="'. $this->_lang_title_edit .'" />';
	return $str ;
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function sanitize( $str )
{
	return htmlspecialchars( $str, ENT_QUOTES );
}

function is_image_ext( $ext )
{
	return $this->is_ext_in_array( $ext, $this->_IMAGE_EXTS );
}

function is_ext_in_array( $ext, $arr )
{
	if ( in_array( strtolower( $ext ) , $arr ) ) {
		return true;
	}
	return false ;
}

//---------------------------------------------------------
// utility class
//---------------------------------------------------------
function mysql_datetime_to_str( $date )
{
	return $this->_mysql_utility_class->mysql_datetime_to_str( $date ) ;
}

//---------------------------------------------------------
// xoops permission class
//---------------------------------------------------------
function has_editable_by_uid( $uid )
{
	if ( ! $this->_has_editable ) {
		return false ;
	}
	if ( ! $this->is_photo_owner( $uid ) ) {
		return false ;
	}
	return true ;
}

function is_photo_owner( $uid )
{
	if ( $this->_xoops_uid == $uid ) {
		return true;
	}
	if ( $this->_is_module_admin ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// group_permission
//---------------------------------------------------------
function _init_permission( $dirname , $trust_dirname )
{
	$permission_handler =& webphoto_inc_group_permission::getSingleton(
		$dirname , $trust_dirname );

	$this->_has_editable = $permission_handler->has_perm( 'editable' );
}

//---------------------------------------------------------
// xoops
//---------------------------------------------------------
function _init_xoops_param( $dirname )
{
	$this->_module_id       = $this->get_xoops_mid_by_dirname( $dirname );
	$this->_is_module_admin = $this->get_xoops_is_module_admin();
	$this->_xoops_uid       = $this->get_xoops_uid();
}

function get_xoops_uid()
{
	global $xoopsUser;
	if ( is_object($xoopsUser) ) {
		$this->_xoops_uid = $xoopsUser->getVar( 'uid' );
	}
	return false;
}

function get_xoops_is_module_admin()
{
	global $xoopsUser;
	if ( is_object($xoopsUser) ) {
		if ( $xoopsUser->isAdmin( $this->_module_id ) ) {
			return true;
		}
	}
	return false;
}

function get_xoops_mid_by_dirname( $dirname )
{
	$module_handler =& xoops_gethandler('module');
	$module = $module_handler->getByDirname( $dirname );
	if ( is_object($module) ) {
		return $module->getVar( 'mid' );
	}
	return false;
}

function get_xoops_uname_by_uid( $uid, $usereal=0 )
{
	return XoopsUser::getUnameFromId( $uid, $usereal );
}

// --- class end ---
}

?>