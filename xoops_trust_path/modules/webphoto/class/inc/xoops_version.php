<?php
// $Id: xoops_version.php,v 1.41 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-25 K.OHWADA
// OPT_TIMELINE_SCALE_CENTURY
// 2011-05-01 K.OHWADA
// pear_path
// 2010-06-06 K.OHWADA
// embed_width
// 2010-04-27 K.OHWADA
// webphoto_inc_catlist
// 2010-04-02 K.OHWADA
// xoops_version_blocks_top_options
// 2010-04-02 K.OHWADA
// xoops_version_cfg_catonsubmenu_default
// 2010-01-10 K.OHWADA
// use_lightbox
// xoops_version_cfg_perm_item_read_default
// 2009-12-06 K.OHWADA
// config: groupid_admin
// event: waiting
// 2009-11-11 K.OHWADA
// webphoto_inc_handler -> webphoto_inc_base_ini
// 2009-10-25 K.OHWADA
// use_lame
// 2009-05-30 K.OHWADA
// _C_WEBPHOTO_OPT_PERM_READ_ALL
// 2009-05-17 K.OHWADA
// cat_summary
// 2009-04-10 K.OHWADA
// b_webphoto_timeline_show
// 2009-03-15 K.OHWADA
// timeline_dirname
// 2009-01-25 K.OHWADA
// _build_blocks_top_options()
// 2009-01-10 K.OHWADA
// xpdfpath
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// perm_cat_read
// 2008-12-05 K.OHWADA
// _init_workdir()
// 2008-11-29 K.OHWADA
// b_webphoto_catlist_show
// 2008-11-08 K.OHWADA
// webphoto_inc_workdir
// tmpdir -> workdir
// gicon_width cat_main_width etc
// 2008-10-01 K.OHWADA
// uploadspath etc
// used update_xoops_config()
// 2008-09-09 K.OHWADA
// set default at use_ffmpeg
// 2008-09-01 K.OHWADA
// added bin_pass comment_dirname
// 2008-08-01 K.OHWADA
// added TRUST_DIRNAME
// added mail_host
// added cachetime in _build_blocks()
// tmppath -> tmpdir
// 2008-07-01 K.OHWADA
// added use_ffmpeg use_pathinfo
// webphoto_xoops_base -> xoops_gethandler()
// 2008-06-30 K.OHWADA
// typo
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_xoops_version
//=========================================================
class webphoto_inc_xoops_version extends webphoto_inc_base_ini
{
	var $_cfg_catonsubmenu  = false;
	var $_cfg_use_pathinfo  = false;
	var $_has_insertable    = false;
	var $_has_rateview      = false;
	var $_is_module_admin   = false;

	var $_ini_cfg_groupid_admin = false;
	var $_ini_cfg_groupid_user  = false;
	var $_ini_sub_myphoto    = true;
	var $_ini_sub_popular    = true;
	var $_ini_sub_highrate   = true;
	var $_ini_sub_search     = false;
	var $_ini_sub_cat_prefix = null;

	var $_config_workdir_default = null;

	var $_TRUST_DIRNAME = null ;
	var $_MODULE_ID     = 0;
	var $_PATH_UPLOADS_MOD      = null;
	var $_PATH_MOD_MEDIAS       = null;
	var $_DIR_TRUST_MOD_UPLOADS = null;
	var $_DIR_TRUST_UPLOADS     = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_xoops_version( $dirname, $trust_dirname )
{
	$this->webphoto_inc_base_ini();
	$this->init_base_ini( $dirname , $trust_dirname );
	$this->init_handler( $dirname );

	$this->_init_xoops_module( $dirname );
	$this->_init_config( $dirname );
	$this->_init_group_permission( $dirname, $trust_dirname );
	$this->_init_is_module_admin();
	$this->_init_workdir( $dirname, $trust_dirname );
	$this->_init_ini(     $dirname, $trust_dirname );

	$this->_TRUST_DIRNAME = $trust_dirname ;

	$this->_PATH_UPLOADS_MOD = '/uploads/'. $dirname;
	$this->_PATH_MOD_MEDIAS  = '/modules/'. $dirname .'/medias';

	$this->_DIR_TRUST_UPLOADS 
		= XOOPS_TRUST_PATH .'/modules/'. $trust_dirname .'/uploads' ;

	$this->_DIR_TRUST_MOD_UPLOADS 
		= XOOPS_TRUST_PATH .'/modules/'. $trust_dirname .'/uploads/'. $dirname;
}

function &getSingleton( $dirname, $trust_dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = 
			new webphoto_inc_xoops_version( $dirname, $trust_dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function build_modversion()
{
// probably install or update
	if ( $this->_is_module_admin && 
	     xoops_refcheck() &&
	   ( strtolower($_SERVER['REQUEST_METHOD']) == 'post' ) ) {
		$this->update_xoops_config();
	}

	$arr           = $this->_build_basic();
	$arr['sub']    = $this->_build_sub();
	$arr['blocks'] = $this->_build_blocks();
	$arr['config'] = $this->_build_config();

	return $arr;
}

//---------------------------------------------------------
// Basic Defintion
//---------------------------------------------------------
function _build_basic()
{
	$module_icon = 'module_icon.php';
	if ( file_exists( $this->_MODULE_DIR.'/images/module_icon.png' ) ) {
		$module_icon = 'images/module_icon.png' ;
	}

	$arr = array();

	$arr['name']        = $this->_constant( 'NAME' ) . '('.$this->_DIRNAME.')';
	$arr['description'] = $this->_constant( 'DESC' );
	$arr['author']   = "Kenichi Ohwada" ;
	$arr['credits']  = "Kenichi Ohwada<br />\n(http://linux2.ohwada.net/)<br />\nGIJOE<br />\n(http://www.peak.ne.jp/)<br />\nDaniel Branco<br />\n(http://bluetopia.homeip.net)<br />\n" ;
	$arr['help']     = "" ;
	$arr['license']  = "GPL see LICENSE" ;
	$arr['official'] = 0;
	$arr['image']    = $module_icon ;
	$arr['dirname']  = $this->_DIRNAME;
	$arr['version']  = _C_WEBPHOTO_VERSION ;

// Any tables can't be touched by modulesadmin.
	$arr['sqlfile'] = false ;
	$arr['tables'] = array() ;

// Admin things
	$arr['hasAdmin'] = 1;
	$arr['adminindex'] = "admin/index.php";
	$arr['adminmenu']  = "admin/menu.php";

// Menu
	$arr['hasMain'] = 1 ;

// Search
	$arr['hasSearch'] = 1;
	$arr['search'] = $this->_build_search();

// Comments
	$arr['hasComments'] = 1;
	$arr['comments'] = $this->_build_comments();

// Notification
	$arr['hasNotification'] = 1;
	$arr['notification'] = $this->_build_notification();

// onInstall, onUpdate, onUninstall
	$arr['onInstall']   = 'include/oninstall.inc.php' ;
	$arr['onUpdate']    = 'include/oninstall.inc.php' ;
	$arr['onUninstall'] = 'include/oninstall.inc.php' ;

	return $arr;
}

//---------------------------------------------------------
// Search 
//---------------------------------------------------------
function _build_search()
{
	$arr = array();
	$arr['file'] = "include/search.inc.php";
	$arr['func'] = $this->_DIRNAME.'_search';
	return $arr;
}

//---------------------------------------------------------
// Comments
//---------------------------------------------------------
function _build_comments()
{
	$arr = array();

// Comments
	$arr['pageName'] = 'index.php';
	$arr['itemName'] = 'photo_id';

// Comment callback functions
	$arr['callbackFile'] = 'include/comment.inc.php';
	$arr['callback']['approve'] = $this->_DIRNAME.'_comments_approve';
	$arr['callback']['update']  = $this->_DIRNAME.'_comments_update';

	return $arr;
}

//---------------------------------------------------------
// Notification
//---------------------------------------------------------
function _build_notification()
{
	$arr = array();

	$arr['lookup_file'] = 'include/notification.inc.php' ;
	$arr['lookup_func'] = $this->_DIRNAME."_notify_iteminfo" ;

	$arr['category'][1]['name'] = 'global';
	$arr['category'][1]['title'] = $this->_constant( 'GLOBAL_NOTIFY' );
	$arr['category'][1]['description'] = $this->_constant( 'GLOBAL_NOTIFYDSC' );
	$arr['category'][1]['subscribe_from'] = array('index.php');

	$arr['category'][2]['name'] = 'category';
	$arr['category'][2]['title'] = $this->_constant( 'CATEGORY_NOTIFY' );
	$arr['category'][2]['description'] = $this->_constant( 'CATEGORY_NOTIFYDSC' );
	$arr['category'][2]['subscribe_from'] = array('index.php');
	$arr['category'][2]['item_name'] = 'cat_id';
	$arr['category'][2]['allow_bookmark'] = 1;

	$arr['category'][3]['name'] = 'photo';
	$arr['category'][3]['title'] = $this->_constant( 'PHOTO_NOTIFY' );
	$arr['category'][3]['description'] = $this->_constant( 'PHOTO_NOTIFYDSC' );
	$arr['category'][3]['subscribe_from'] = array('index.php');
	$arr['category'][3]['item_name'] = 'photo_id';
	$arr['category'][3]['allow_bookmark'] = 1;

	$arr['event'][1]['name'] = 'new_photo';
	$arr['event'][1]['category'] = 'global';
	$arr['event'][1]['title'] = $this->_constant( 'GLOBAL_NEWPHOTO_NOTIFY' );
	$arr['event'][1]['caption'] = $this->_constant( 'GLOBAL_NEWPHOTO_NOTIFYCAP' );
	$arr['event'][1]['description'] = $this->_constant( 'GLOBAL_NEWPHOTO_NOTIFYDSC' );
	$arr['event'][1]['mail_template'] = 'global_newphoto_notify';
	$arr['event'][1]['mail_subject'] = $this->_constant( 'GLOBAL_NEWPHOTO_NOTIFYSBJ' );

	$arr['event'][2]['name'] = 'new_photo';
	$arr['event'][2]['category'] = 'category';
	$arr['event'][2]['title'] = $this->_constant( 'CATEGORY_NEWPHOTO_NOTIFY' );
	$arr['event'][2]['caption'] = $this->_constant( 'CATEGORY_NEWPHOTO_NOTIFYCAP' );
	$arr['event'][2]['description'] = $this->_constant( 'CATEGORY_NEWPHOTO_NOTIFYDSC' );
	$arr['event'][2]['mail_template'] = 'category_newphoto_notify';
	$arr['event'][2]['mail_subject'] = $this->_constant( 'CATEGORY_NEWPHOTO_NOTIFYSBJ' );

	$arr['event'][3]['name'] = 'waiting';
	$arr['event'][3]['category'] = 'global';
	$arr['event'][3]['admin_only'] = 1;
	$arr['event'][3]['title'] = $this->_constant( 'GLOBAL_WAITING_NOTIFY' );
	$arr['event'][3]['caption'] = $this->_constant( 'GLOBAL_WAITING_NOTIFYCAP' );
	$arr['event'][3]['description'] = $this->_constant( 'GLOBAL_WAITING_NOTIFYDSC' );
	$arr['event'][3]['mail_template'] = 'global_waiting_notify';
	$arr['event'][3]['mail_subject'] = $this->_constant( 'GLOBAL_WAITING_NOTIFYSBJ' );

	return $arr;
}

//---------------------------------------------------------
// Sub Menu
//---------------------------------------------------------
function _build_sub()
{
	$arr = array();

	if ( $this->_has_insertable ) {
		$arr[] = $this->_build_sub_array_const(
			'SMNAME_SUBMIT', $this->_build_sub_url_fct( 'submit' ) );

		if ( $this->_ini_sub_myphoto ) {
			$arr[] = $this->_build_sub_array_const(
				'SMNAME_MYPHOTO', $this->_build_sub_url_fct( 'myphoto' ) );
		}
	}

	if ( $this->_ini_sub_popular ) {
		$arr[] = $this->_build_sub_array_const(
		'SMNAME_POPULAR', $this->_build_sub_url_op( 'popular' ) );
	}

	if ( $this->_ini_sub_highrate && $this->_has_rateview ) {
		$arr[] = $this->_build_sub_array_const(
			'SMNAME_HIGHRATE', $this->_build_sub_url_op( 'highrate' ) );
	}

	if ( $this->_cfg_catonsubmenu ) {
		$rows = $this->_get_top_cat_rows() ;
		if( is_array($rows) && count($rows) ) {
			foreach ( $rows as $row )
			{
				$name  = $this->_ini_sub_cat_prefix . $this->sanitize( $row['cat_title'] ) ;
				$url   = $this->_build_sub_url_category( $row['cat_id'] ) ;
				$arr[] = $this->_build_sub_array( $name, $url );
			}
		}
	}

	if ( $this->_ini_sub_search ) {
		$arr[] = $this->_build_sub_array_const(
		'SMNAME_SEARCH', $this->_build_sub_url_op( 'search' ) );
	}

	return $arr;
}

function _build_sub_array_const( $name, $url )
{
	return $this->_build_sub_array( $this->_constant( $name ) , $url );
}

function _build_sub_array( $name, $url )
{
	$arr = array(
		'name' => $name ,
		'url'  => $url ,
	);
	return $arr;
}

function _build_sub_url_fct( $fct )
{
	if ( $this->_cfg_use_pathinfo ) {
		$str = 'index.php/'. $fct .'/' ;
	} else {
		$str = 'index.php?fct='. $fct ;
	}
	return $str ;
}

function _build_sub_url_op( $op )
{
	if ( $this->_cfg_use_pathinfo ) {
		$str = 'index.php/'. $op .'/' ;
	} else {
		$str = 'index.php?op='. $op ;
	}
	return $str ;
}

function _build_sub_url_category( $id )
{
	if ( $this->_cfg_use_pathinfo ) {
		$str = 'index.php/category/'. $id .'/' ;
	} else {
		$str = 'index.php?fct=category&amp;p='. $id ;
	}
	return $str ;
}

//---------------------------------------------------------
// Blocks
//---------------------------------------------------------
function _build_blocks()
{
	$arr = array();

	$arr[1]['file'] = "blocks.php";
	$arr[1]['name'] = $this->_constant( 'BNAME_RECENT' ) .' ('.$this->_DIRNAME.')' ;
	$arr[1]['description'] = "Shows recently added photos";
	$arr[1]['show_func'] = "b_webphoto_topnews_show";
	$arr[1]['edit_func'] = "b_webphoto_topnews_edit";
	$arr[1]['options']   = $this->_build_blocks_top_options() ;
	$arr[1]['template']  = '' ;
	$arr[1]['can_clone'] = true ;

	$arr[2]['file'] = "blocks.php";
	$arr[2]['name'] = $this->_constant( 'BNAME_HITS' ) .' ('.$this->_DIRNAME.')' ;
	$arr[2]['description'] = "Shows most viewed photos";
	$arr[2]['show_func'] = "b_webphoto_tophits_show";
	$arr[2]['edit_func'] = "b_webphoto_tophits_edit";
	$arr[2]['options']   = $this->_build_blocks_top_options() ;
	$arr[2]['template']  = '' ;
	$arr[2]['can_clone'] = true ;

	$arr[3]['file'] = "blocks.php";
	$arr[3]['name'] = $this->_constant( 'BNAME_RECENT_P' ) .' ('.$this->_DIRNAME.')' ;
	$arr[3]['description'] = "Shows recently added photos";
	$arr[3]['show_func'] = "b_webphoto_topnews_p_show";
	$arr[3]['edit_func'] = "b_webphoto_topnews_p_edit";
	$arr[3]['options']   = $this->_build_blocks_top_p_options() ;
	$arr[3]['template']  = '' ;
	$arr[3]['can_clone'] = true ;

	$arr[4]['file'] = "blocks.php";
	$arr[4]['name'] = $this->_constant( 'BNAME_HITS_P' ) .' ('.$this->_DIRNAME.')' ;
	$arr[4]['description'] = "Shows most viewed photos";
	$arr[4]['show_func'] = "b_webphoto_tophits_p_show";
	$arr[4]['edit_func'] = "b_webphoto_tophits_p_edit";
	$arr[4]['options']   = $this->_build_blocks_top_p_options() ;
	$arr[4]['template']  = '' ;
	$arr[4]['can_clone'] = true ;

	$arr[5]['file'] = "blocks.php";
	$arr[5]['name'] = $this->_constant( 'BNAME_RANDOM' ) .' ('.$this->_DIRNAME.')' ;
	$arr[5]['description'] = "Shows a random photo";
	$arr[5]['show_func'] = "b_webphoto_rphoto_show";
	$arr[5]['edit_func'] = "b_webphoto_rphoto_edit";
	$arr[5]['options']   = $this->_build_blocks_top_p_options() ;
	$arr[5]['template']  = '' ;
	$arr[5]['can_clone'] = true ;

	$arr[6]['file'] = "blocks.php";
	$arr[6]['name'] = $this->_constant( 'BNAME_CATLIST' ) .' ('.$this->_DIRNAME.')' ;
	$arr[6]['description'] = "Shows category list";
	$arr[6]['show_func'] = "b_webphoto_catlist_show";
	$arr[6]['edit_func'] = "b_webphoto_catlist_edit";
	$arr[6]['options'] = $this->_DIRNAME.'|1|1|1|3';
	$arr[6]['template'] = '' ;
	$arr[6]['can_clone'] = true ;

	$arr[7]['file'] = "blocks.php";
	$arr[7]['name'] = $this->_constant( 'BNAME_TAGCLOUD' ) .' ('.$this->_DIRNAME.')' ;
	$arr[7]['description'] = "Shows tag cloud";
	$arr[7]['show_func'] = "b_webphoto_tagcloud_show";
	$arr[7]['edit_func'] = "b_webphoto_tagcloud_edit";
	$arr[7]['options'] = $this->_DIRNAME.'|100';
	$arr[7]['template'] = '' ;
	$arr[7]['can_clone'] = true ;

	$arr[8]['file'] = "blocks.php";
	$arr[8]['name'] = $this->_constant( 'BNAME_TIMELINE' ) .' ('.$this->_DIRNAME.')' ;
	$arr[8]['description'] = "Shows timeline";
	$arr[8]['show_func'] = "b_webphoto_timeline_show";
	$arr[8]['edit_func'] = "b_webphoto_timeline_edit";
	$arr[8]['options'] = $this->_DIRNAME.'|10|100|300|month';
	$arr[8]['template'] = '' ;
	$arr[8]['can_clone'] = true ;

// keep block's options
	if( ! defined( 'XOOPS_CUBE_LEGACY' ) && substr( XOOPS_VERSION , 6 , 3 ) < 2.1 && ! empty( $_POST['fct'] ) && ! empty( $_POST['op'] ) && $_POST['fct'] == 'modulesadmin' && $_POST['op'] == 'update_ok' && $_POST['dirname'] == $this->_DIRNAME ) {
		$arr = $this->_blocks_keep_option( $arr );
	}

	return $arr;
}

function _build_blocks_top_options()
{
	$str  = $this->_DIRNAME;
	$str .= $this->get_ini('xoops_version_blocks_top_options');
	return $str ;
}

function _build_blocks_top_p_options()
{
	$str  = $this->_DIRNAME;
	$str .= $this->get_ini('xoops_version_blocks_top_p_options');
	return $str ;
}

//---------------------------------------------------------
// Config Settings (only for modules that need config settings generated automatically)
// max length of config_name is 25
// max length of conf_title and conf_desc is 30
//---------------------------------------------------------
function _build_config()
{
	$arr = array();

//---------------------------------------------------------
// same as myalbum
//---------------------------------------------------------
// remove photospath thumbspath

// add for webphoto
	$arr[] = array(
		'name'			=> 'uploadspath' ,
		'title'			=> $this->_constant_name( 'CFG_UPLOADSPATH' ) ,
		'description'	=> $this->_constant_name( 'CFG_UPLOADSPATH_DSC' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> $this->_PATH_UPLOADS_MOD ,
		'options'		=> array()
	) ;

// add for webphoto
	$arr[] = array(
		'name'			=> 'workdir' ,
		'title'			=> $this->_constant_name( 'CFG_WORKDIR' ) ,
		'description'	=> $this->_constant_name( 'CFG_WORKDIR_DSC' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> $this->_config_workdir_default ,
		'options'		=> array()
	) ;

// add for webphoto
	$arr[] = array(
		'name'			=> 'mediaspath' ,
		'title'			=> $this->_constant_name( 'CFG_MEDIASPATH' ) ,
		'description'	=> $this->_constant_name( 'CFG_MEDIASPATH_DSC' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> $this->_PATH_MOD_MEDIAS ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'imagingpipe' ,
		'title'			=> $this->_constant_name( 'CFG_IMAGINGPIPE' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCIMAGINGPIPE' ) ,
		'formtype'		=> 'select' ,
		'valuetype'		=> 'int' ,
		'default'		=> $this->get_ini('xoops_version_cfg_imagingpipe_default') ,
		'options'		=> array( 
			'GD'          => _C_WEBPHOTO_PIPEID_GD , 
			'ImageMagick' => _C_WEBPHOTO_PIPEID_IMAGICK , 
			'NetPBM'      => _C_WEBPHOTO_PIPEID_NETPBM
		)
	) ;

	$arr[] = array(
		'name'			=> 'forcegd2' ,
		'title'			=> $this->_constant_name( 'CFG_FORCEGD2' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCFORCEGD2' ) ,
		'formtype'		=> 'yesno' ,
		'valuetype'		=> 'int' ,
		'default'		=> '1' ,
		'options'		=> array()
	) ;

// for webphoto
	$arr[] = array(
		'name'			=> 'jpeg_quality' ,
		'title'			=> $this->_constant_name( 'CFG_JPEG_QUALITY' ) ,
		'description'	=> $this->_constant_name( 'CFG_JPEG_QUALITY_DSC' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '75' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'pear_path' ,
		'title'			=> $this->_constant_name( 'CFG_PEAR_PATH' ) ,
		'description'	=> $this->_constant_name( 'CFG_PEAR_PATH_DSC' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> '' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'imagickpath' ,
		'title'			=> $this->_constant_name( 'CFG_IMAGICKPATH' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCIMAGICKPATH' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> $this->get_ini('xoops_version_cfg_imagickpath_default') ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'netpbmpath' ,
		'title'			=> $this->_constant_name( 'CFG_NETPBMPATH' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCNETPBMPATH' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> '' ,
		'options'		=> array()
	) ;

// for webphoto
	$arr[] = array(
		'name'			=> 'use_ffmpeg' ,
		'title'			=> $this->_constant_name( 'CFG_USE_FFMPEG' ) ,
		'description'	=> '' ,
		'formtype'		=> 'yesno' ,
		'valuetype'		=> 'int' ,
		'default'		=> $this->get_ini('xoops_version_cfg_use_ffmpeg_default') ,
		'options'		=> array()
	) ;

// for webphoto
	$arr[] = array(
		'name'			=> 'ffmpegpath' ,
		'title'			=> $this->_constant_name( 'CFG_FFMPEGPATH' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCFFMPEGPATH' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> $this->get_ini('xoops_version_cfg_ffmpegpath_default') ,
		'options'		=> array()
	) ;

// for webphoto
	$arr[] = array(
		'name'			=> 'use_lame' ,
		'title'			=> $this->_constant_name( 'CFG_USE_LAME' ) ,
		'description'	=> '' ,
		'formtype'		=> 'yesno' ,
		'valuetype'		=> 'int' ,
		'default'		=> 0 ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'lamepath' ,
		'title'			=> $this->_constant_name( 'CFG_LAMEPATH' ) ,
		'description'	=> $this->_constant_name( 'CFG_LAMEPATH_DSC' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> '' ,
		'options'		=> array()
	) ;

// for webphoto
	$arr[] = array(
		'name'			=> 'use_timidity' ,
		'title'			=> $this->_constant_name( 'CFG_USE_TIMIDITY' ) ,
		'description'	=> '' ,
		'formtype'		=> 'yesno' ,
		'valuetype'		=> 'int' ,
		'default'		=> 0 ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'timiditypath' ,
		'title'			=> $this->_constant_name( 'CFG_TIMIDITYPATH' ) ,
		'description'	=> $this->_constant_name( 'CFG_TIMIDITYPATH_DSC' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> '' ,
		'options'		=> array()
	) ;

// for webphoto
	$arr[] = array(
		'name'			=> 'use_xpdf' ,
		'title'			=> $this->_constant_name( 'CFG_USE_XPDF' ) ,
		'description'	=> '' ,
		'formtype'		=> 'yesno' ,
		'valuetype'		=> 'int' ,
		'default'		=> $this->get_ini('xoops_version_cfg_use_xpdf_default') ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'xpdfpath' ,
		'title'			=> $this->_constant_name( 'CFG_XPDFPATH' ) ,
		'description'	=> $this->_constant_name( 'CFG_XPDFPATH_DSC' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> $this->get_ini('xoops_version_cfg_xpdfpath_default') ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'width' ,
		'title'			=> $this->_constant_name( 'CFG_WIDTH' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCWIDTH' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '1024' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'height' ,
		'title'			=> $this->_constant_name( 'CFG_HEIGHT' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCHEIGHT' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '1024' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'fsize' ,
		'title'			=> $this->_constant_name( 'CFG_FSIZE' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCFSIZE' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '102400' , // 100 KB
		'default'		=> $this->get_ini('xoops_version_cfg_fsize_default') ,
		'options'		=> array()
	) ;

// middlepixel -> middle_width middle_height
	$arr[] = array(
		'name'			=> 'middle_width' ,
		'title'			=> $this->_constant_name( 'CFG_MIDDLE_WIDTH' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '480' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'middle_height' ,
		'title'			=> $this->_constant_name( 'CFG_MIDDLE_HEIGHT' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '480' ,
		'options'		=> array()
	) ;

// thumbsize -> thumb_width thumb_height
	$arr[] = array(
		'name'			=> 'thumb_width' ,
		'title'			=> $this->_constant_name( 'CFG_THUMB_WIDTH' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '140' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'thumb_height' ,
		'title'			=> $this->_constant_name( 'CFG_THUMB_HEIGHT' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '140' ,
		'options'		=> array()
	) ;

// for webphoto
	$arr[] = array(
		'name'			=> 'small_width' ,
		'title'			=> $this->_constant_name( 'CFG_SMALL_WIDTH' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '70' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'small_height' ,
		'title'			=> $this->_constant_name( 'CFG_SMALL_HEIGHT' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '70' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'allownoimage' ,
		'title'			=> $this->_constant_name( 'CFG_ALLOWNOIMAGE' ) ,
		'description'	=> '' ,
		'formtype'		=> 'yesno' ,
		'valuetype'		=> 'int' ,
		'default'		=> '1' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'makethumb' ,
		'title'			=> $this->_constant_name( 'CFG_MAKETHUMB' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCMAKETHUMB' ) ,
		'formtype'		=> 'yesno' ,
		'valuetype'		=> 'int' ,
		'default'		=> '1' ,
		'options'		=> array()
	) ;

// remove thumbrule

// for webphoto
	$arr[] = array(
		'name'			=> 'embed_width' ,
		'title'			=> $this->_constant_name( 'CFG_EMBED_WIDTH' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '480' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'embed_height' ,
		'title'			=> $this->_constant_name( 'CFG_EMBED_HEIGHT' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '360' ,
		'options'		=> array()
	) ;

// for webphoto
	$arr[] = array(
		'name'			=> 'cat_width' ,
		'title'			=> $this->_constant_name( 'CFG_CAT_WIDTH' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '120' ,
		'options'		=> array()
	) ;

// for webphoto
	$arr[] = array(
		'name'			=> 'csub_width' ,
		'title'			=> $this->_constant_name( 'CFG_CSUB_WIDTH' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '50' ,
		'options'		=> array()
	) ;

// for webphoto
	$arr[] = array(
		'name'			=> 'gicon_width' ,
		'title'			=> $this->_constant_name( 'CFG_GICON_WIDTH' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '120' ,
		'options'		=> array()
	) ;

// for webphoto
	$arr[] = array(
		'name'			=> 'logo_width' ,
		'title'			=> $this->_constant_name( 'CFG_LOGO_WIDTH' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '120' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'popular' ,
		'title'			=> $this->_constant_name( 'CFG_POPULAR' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '100' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'newdays' ,
		'title'			=> $this->_constant_name( 'CFG_NEWDAYS' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '7' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'newphotos' ,
		'title'			=> $this->_constant_name( 'CFG_NEWPHOTOS' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '10' ,
		'options'		=> array()
	) ;

// for webphoto
	$arr[] = array(
		'name'			=> 'tags' ,
		'title'			=> $this->_constant_name( 'CFG_TAGS' ) ,
//		'description'	=> $this->_constant_name( 'CFG_TAGS_DSC' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '100' ,
		'options'		=> array()
	) ;

// defaultorder -> sort
	$arr[] = array(
		'name'			=> 'sort' ,
		'title'			=> $this->_constant_name( 'CFG_SORT' ) ,
		'description'	=> '' ,
		'formtype'		=> 'select' ,
		'valuetype'		=> 'text' ,
		'default'		=> 'dated' ,
		'options'		=> array(
			$this->_constant( 'OPT_SORT_IDA' )      => 'ida' ,
			$this->_constant( 'OPT_SORT_IDD' )      => 'idd' ,
			$this->_constant( 'OPT_SORT_TITLEA' )   => 'titlea' ,
			$this->_constant( 'OPT_SORT_TITLED' )   => 'titled' ,
			$this->_constant( 'OPT_SORT_DATEA' )    => 'datea' ,
			$this->_constant( 'OPT_SORT_DATED' )    => 'dated' ,
			$this->_constant( 'OPT_SORT_HITSA' )    => 'hitsa' ,
			$this->_constant( 'OPT_SORT_HITSD' )    => 'hitsd' ,
			$this->_constant( 'OPT_SORT_RATINGA' )  => 'ratinga' ,
			$this->_constant( 'OPT_SORT_RATINGD' )  => 'ratingd' ,
			$this->_constant( 'OPT_SORT_RANDOM' )   => 'random' ,
		)
	) ;

	$arr[] = array(
		'name'			=> 'perpage' ,
		'title'			=> $this->_constant_name( 'CFG_PERPAGE' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCPERPAGE' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> _C_WEBPHOTO_CFG_OPT_PERPAGE ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'addposts' ,
		'title'			=> $this->_constant_name( 'CFG_ADDPOSTS' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCADDPOSTS' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '1' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'catonsubmenu' ,
		'title'			=> $this->_constant_name( 'CFG_CATONSUBMENU' ) ,
		'description'	=> '' ,
		'formtype'		=> 'yesno' ,
		'valuetype'		=> 'int' ,
		'default'		=> $this->get_ini('xoops_version_cfg_catonsubmenu_default') ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'nameoruname' ,
		'title'			=> $this->_constant_name( 'CFG_NAMEORUNAME' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCNAMEORUNAME' ) ,
		'formtype'		=> 'select' ,
		'valuetype'		=> 'text' ,
		'default'		=> 'uname' ,
		'options'		=> array(
			$this->_constant( 'OPT_USENAME'  ) => 'name',
			$this->_constant( 'OPT_USEUNAME' ) => 'uname'
		)
	) ;

// viewcattype -> viewtype
	$arr[] = array(
		'name'			=> 'viewtype' ,
		'title'			=> $this->_constant_name( 'CFG_VIEWTYPE' ) ,
		'description'	=> '' ,
		'formtype'		=> 'select' ,
		'valuetype'		=> 'text' ,
		'default'		=> 'list' ,
		'options'		=> array(
			$this->_constant( 'OPT_VIEWLIST'  ) => 'list',
			$this->_constant( 'OPT_VIEWTABLE' ) => 'table'
		)
	) ;

// remove allowedexts allowedmime
// use mime table

	$arr[] = array(
		'name'			=> 'colsoftableview' ,
		'title'			=> $this->_constant_name( 'CFG_COLSOFTABLE' ) ,	// short name
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '4' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'usesiteimg' ,
		'title'			=> $this->_constant_name( 'CFG_USESITEIMG' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCUSESITEIMG' ) ,
		'formtype'		=> 'yesno' ,
		'valuetype'		=> 'int' ,
		'default'		=> '0' ,
		'options'		=> array()
	) ;

//---------------------------------------------------------
// added for webphoto
//---------------------------------------------------------
	$arr[] = array(
		'name'			=> 'item_summary' ,
		'title'			=> $this->_constant_name( 'CFG_ITEM_SUMMARY' ) ,
		'description'	=> $this->_constant_name( 'CFG_ITEM_SUMMARY_DSC' ) ,
		'formtype'		=> 'text' ,
		'valuetype'		=> 'int' ,
		'default'		=> '100' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'cat_summary' ,
		'title'			=> $this->_constant_name( 'CFG_CAT_SUMMARY' ) ,
		'description'	=> $this->_constant_name( 'CFG_CAT_SUMMARY_DSC' ) ,
		'formtype'		=> 'text' ,
		'valuetype'		=> 'int' ,
		'default'		=> '50' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'cat_child' ,
		'title'			=> $this->_constant_name( 'CFG_CAT_CHILD' ) ,
		'description'	=> $this->_constant_name( 'CFG_CAT_CHILD_DSC' ) ,
		'formtype'		=> 'select' ,
		'valuetype'		=> 'int' ,
		'default'		=> _C_WEBPHOTO_CAT_CHILD_EMPTY ,
		'options'		=> array(
			$this->_constant( 'OPT_CAT_CHILD_NON'  )   => _C_WEBPHOTO_CAT_CHILD_NON,
			$this->_constant( 'OPT_CAT_CHILD_EMPTY' )  => _C_WEBPHOTO_CAT_CHILD_EMPTY,
			$this->_constant( 'OPT_CAT_CHILD_ALWAYS' ) => _C_WEBPHOTO_CAT_CHILD_ALWAYS,
		)
	) ;

	$arr[] = array(
		'name'			=> 'gmap_apikey' ,
		'title'			=> $this->_constant_name( 'CFG_APIKEY' ) ,
		'description'	=> $this->_constant_name( 'CFG_APIKEY_DSC' ) ,
		'formtype'		=> 'textarea' ,
		'valuetype'		=> 'text' ,
		'default'		=> '' ,
		'options'		=> array()
	) ;

// near hawaii
	$arr[] = array(
		'name'			=> 'gmap_latitude' ,
		'title'			=> $this->_constant_name( 'CFG_LATITUDE' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> '2' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'gmap_longitude' ,
		'title'			=> $this->_constant_name( 'CFG_LONGITUDE' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> '-155' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'gmap_zoom' ,
		'title'			=> $this->_constant_name( 'CFG_ZOOM' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '2' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'gmap_photos' ,
		'title'			=> $this->_constant_name( 'CFG_GMAP_PHOTOS' ) ,
//		'description'	=> $this->_constant_name( 'CFG_GMAP_PHOTOS_DSC' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '100' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'use_popbox' ,
		'title'			=> $this->_constant_name( 'CFG_USE_POPBOX' ) ,
		'description'	=> '' ,
		'formtype'		=> 'yesno' ,
		'valuetype'		=> 'int' ,
		'default'		=> $this->get_ini('xoops_version_cfg_use_popbox_default') ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'use_lightbox' ,
		'title'			=> $this->_constant_name( 'CFG_USE_LIGHTBOX' ) ,
		'description'	=> '' ,
		'formtype'		=> 'yesno' ,
		'valuetype'		=> 'int' ,
		'default'		=> $this->get_ini('xoops_version_cfg_use_lightbox_default') ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'use_pathinfo' ,
		'title'			=> $this->_constant_name( 'CFG_USE_PATHINFO' ) ,
		'description'	=> '' ,
		'formtype'		=> 'yesno' ,
		'valuetype'		=> 'int' ,
		'default'		=> '1' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'use_callback' ,
		'title'			=> $this->_constant_name( 'CFG_USE_CALLBACK' ) ,
		'description'	=> $this->_constant_name( 'CFG_USE_CALLBACK_DSC' ) ,
		'formtype'		=> 'yesno' ,
		'valuetype'		=> 'int' ,
		'default'		=> '0' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'index_desc' ,
		'title'			=> $this->_constant_name( 'CFG_INDEX_DESC' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textarea' ,
		'valuetype'		=> 'text' ,
		'default'		=> $this->_build_config_index_desc() ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'mail_host' ,
		'title'			=> $this->_constant_name( 'CFG_MAIL_HOST' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> '' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'mail_user' ,
		'title'			=> $this->_constant_name( 'CFG_MAIL_USER' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> '' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'mail_pass' ,
		'title'			=> $this->_constant_name( 'CFG_MAIL_PASS' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> '' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'mail_addr' ,
		'title'			=> $this->_constant_name( 'CFG_MAIL_ADDR' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> '' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'mail_charset' ,
		'title'			=> $this->_constant_name( 'CFG_MAIL_CHARSET' ) ,
		'description'	=> $this->_constant_name( 'CFG_MAIL_CHARSET_DSC' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> $this->_constant( 'CFG_MAIL_CHARSET_LIST' ) ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'file_dir' ,
		'title'			=> $this->_constant_name( 'CFG_FILE_DIR' ) ,
		'description'	=> $this->_constant_name( 'CFG_FILE_DIR_DSC' ) ,
		'formtype'		=> 'text' ,
		'valuetype'		=> 'text' ,
		'default'		=> '' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'file_size' ,
		'title'			=> $this->_constant_name( 'CFG_FILE_SIZE' ) ,
		'description'	=> '' ,
		'formtype'		=> 'text' ,
		'valuetype'		=> 'text' ,
		'default'		=> '1049000' , // 1.0 MB
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'file_desc' ,
		'title'			=> $this->_constant_name( 'CFG_FILE_DESC' ) ,
		'description'	=> $this->_constant_name( 'CFG_FILE_DESC_DSC' ) ,
		'formtype'		=> 'textarea' ,
		'valuetype'		=> 'text' ,
		'default'		=> $this->_constant( 'CFG_FILE_DESC_TEXT' ) ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'bin_pass' ,
		'title'			=> $this->_constant_name( 'CFG_BIN_PASS' ) ,
		'description'	=> '' ,
		'formtype'		=> 'text' ,
		'valuetype'		=> 'text' ,
		'default'		=> xoops_makepass() ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'comment_dirname' ,
		'title'			=> $this->_constant_name( 'CFG_COM_DIRNAME' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> '' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'comment_forum_id' ,
		'title'			=> $this->_constant_name( 'CFG_COM_FORUM_ID' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'int' ,
		'default'		=> '0' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'comment_view' ,
		'title'			=> $this->_constant_name( 'CFG_COM_VIEW' ) ,
		'description'	=> '' ,
		'formtype'		=> 'select' ,
		'valuetype'		=> 'text' ,
		'default'		=> 'listposts_flat' ,
		'options'		=> array(
			'_FLAT'     => 'listposts_flat' ,
			'_THREADED' => 'listtopics'
		)
	) ;

	$arr[] = array(
		'name'			=> 'perm_cat_read' ,
		'title'			=> $this->_constant_name( 'CFG_PERM_CAT_READ' ) ,
		'description'	=> $this->_constant_name( 'CFG_PERM_CAT_READ_DSC' ) ,
		'formtype'		=> 'select' ,
		'valuetype'		=> 'int' ,
		'default'		=> '0' ,
		'options'		=> array(
			$this->_constant( 'OPT_PERM_READ_ALL'  )    => _C_WEBPHOTO_OPT_PERM_READ_ALL ,
			$this->_constant( 'OPT_PERM_READ_NO_ITEM' ) => _C_WEBPHOTO_OPT_PERM_READ_NO_ITEM ,
			$this->_constant( 'OPT_PERM_READ_NO_CAT'  ) => _C_WEBPHOTO_OPT_PERM_READ_NO_CAT ,
		)
	) ;

	$arr[] = array(
		'name'			=> 'perm_item_read' ,
		'title'			=> $this->_constant_name( 'CFG_PERM_ITEM_READ' ) ,
		'description'	=> $this->_constant_name( 'CFG_PERM_ITEM_READ_DSC' ) ,
		'formtype'		=> 'select' ,
		'valuetype'		=> 'int' ,
		'default'		=> $this->get_ini('xoops_version_cfg_perm_item_read_default') ,
		'options'		=> array(
			$this->_constant( 'OPT_PERM_READ_ALL'  )    => _C_WEBPHOTO_OPT_PERM_READ_ALL ,
			$this->_constant( 'OPT_PERM_READ_NO_ITEM' ) => _C_WEBPHOTO_OPT_PERM_READ_NO_ITEM ,
		)
	) ;

	$arr[] = array(
		'name'			=> 'timeline_dirname' ,
		'title'			=> $this->_constant_name( 'CFG_TIMELINE_DIRNAME' ) ,
		'description'	=> $this->_constant_name( 'CFG_TIMELINE_DIRNAME_DSC' ) ,
		'formtype'		=> 'text' ,
		'valuetype'		=> 'text' ,
		'default'		=> 'timeline' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'timeline_latest' ,
		'title'			=> $this->_constant_name( 'CFG_TIMELINE_LATEST' ) ,
//		'description'	=> $this->_constant_name( 'CFG_TIMELINE_LATEST_DSC' ) ,
		'formtype'		=> 'text' ,
		'valuetype'		=> 'int' ,
		'default'		=> '10' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'timeline_random' ,
		'title'			=> $this->_constant_name( 'CFG_TIMELINE_RANDOM' ) ,
//		'description'	=> $this->_constant_name( 'CFG_TIMELINE_RANDOM_DSC' ) ,
		'formtype'		=> 'text' ,
		'valuetype'		=> 'int' ,
		'default'		=> '100' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'timeline_scale' ,
		'title'			=> $this->_constant_name( 'CFG_TIMELINE_SCALE' ) ,
		'description'	=> $this->_constant_name( 'CFG_TIMELINE_SCALE_DSC' ) ,
		'formtype'		=> 'select' ,
		'valuetype'		=> 'text' ,
		'default'		=> 'month' ,
		'options'		=> array(
			$this->_constant( 'OPT_TIMELINE_SCALE_DAY'   )   => 'day',
			$this->_constant( 'OPT_TIMELINE_SCALE_WEEK'   )  => 'week',
			$this->_constant( 'OPT_TIMELINE_SCALE_MONTH'  )  => 'month',
			$this->_constant( 'OPT_TIMELINE_SCALE_YEAR'   )  => 'year',
			$this->_constant( 'OPT_TIMELINE_SCALE_DECADE' )  => 'decade',
			$this->_constant( 'OPT_TIMELINE_SCALE_CENTURY' ) => 'century',
		)
	) ;

	if ( $this->_ini_cfg_groupid_admin ) {
		$arr[] = array(
			'name'			=> 'groupid_admin' ,
			'title'			=> $this->_constant_name( 'CFG_GROUPID_ADMIN' ) ,
			'description'	=> $this->_constant_name( 'CFG_GROUPID_ADMIN_DSC' ) ,
			'formtype'		=> 'text' ,
			'valuetype'		=> 'int' ,
			'default'		=> '0' ,
			'options'		=> array()
		) ;
	}

	if ( $this->_ini_cfg_groupid_user ) {
		$arr[] = array(
			'name'			=> 'groupid_user' ,
			'title'			=> $this->_constant_name( 'CFG_GROUPID_USER' ) ,
			'description'	=> $this->_constant_name( 'CFG_GROUPID_USER_DSC' ) ,
			'formtype'		=> 'text' ,
			'valuetype'		=> 'int' ,
			'default'		=> '0' ,
			'options'		=> array()
		) ;
	}

	return $arr;
}

function _build_config_index_desc()
{
	$str  = '<span style="color: #0000ff">';
	$str .= $this->_constant( 'DESC' );
	$str .= '<br />';
	$str .= $this->_constant( 'CFG_INDEX_DESC_DEFAULT' );
	$str .= '</span>';
	return $str;
}

//---------------------------------------------------------
// langauge
//---------------------------------------------------------
function _constant( $name )
{
	return constant( $this->_constant_name( $name ) );
}

function _constant_name( $name )
{
	return strtoupper( '_MI_' . $this->_DIRNAME . '_' . $name );
}

//---------------------------------------------------------
// blocks handler
//---------------------------------------------------------
function _blocks_keep_option( $blocks )
{
// Keep Block option values when update (by nobunobu) for XOOPS 2.0.x

	$local_msgs = array();

	$count = count($blocks);

	$rows = $this->_get_newblocks_rows( $count );
	foreach ($rows as $row ) 
	{
		$local_msgs[] = "Non Defined Block <b>". $row['name'] ."</b> will be deleted";
		$iret = $this->_delete_newblocks( $row['bid'] );
	}

	for ($i = 1 ; $i <= $count ; $i++) 
	{
		$fblock = $this->_get_newblocks_row( $i, $blocks[$i]['show_func'], $blocks[$i]['file'] );

		if ( isset( $fblock['options'] ) ) {
			$old_vals=explode("|",$fblock['options']);
			$def_vals=explode("|",$blocks[$i]['options']);

			if (count($old_vals) == count($def_vals)) {
				$blocks[$i]['options'] = $fblock['options'];
				$local_msgs[] = "Option's values of the block <b>".$fblock['name']."</b> will be kept. (value = <b>".$fblock['options']."</b>)";

			} else if (count($old_vals) < count($def_vals)){
				for ($j=0; $j < count($old_vals); $j++) {
					$def_vals[$j] = $old_vals[$j];
				}

				$blocks[$i]['options'] = implode("|",$def_vals);
				$local_msgs[] = "Option's values of the block <b>".$fblock['name']."</b> will be kept and new option(s) are added. (value = <b>".$blocks[$i]['options']."</b>)";

			} else {
				$local_msgs[] = "Option's values of the block <b>".$fblock['name']."</b> will be reset to the default, because of some decrease of options. (value = <b>".$blocks[$i]['options']."</b>)";
			}
		}
	}

	$this->_blocks_msg( $local_msgs );

	return $blocks;
}

function _get_newblocks_rows( $func_num, $limit=0, $offset=0 )
{
	$sql  = "SELECT * FROM ". $this->_db->prefix('newblocks');
	$sql .= " WHERE mid=". intval( $this->_MODULE_ID );
	$sql .= " AND block_type <>'D' ";
	$sql .= " AND func_num > ". intval( $func_num );
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function _delete_newblocks( $bid )
{
	$sql  = "DELETE FROM ". $this->_db->prefix('newblocks');
	$sql .= " WHERE bid=". intval( $bid );
	return $this->query($sql);
}

function _get_newblocks_row( $func_num, $show_func, $func_file )
{
	$sql  = "SELECT * FROM ". $this->_db->prefix('newblocks');
	$sql .= " WHERE mid=". intval( $this->_MODULE_ID ) ;
	$sql .= " AND func_num=". intval( $func_num );
	$sql .= " AND show_func=". $this->quote( $show_func );
	$sql .= " AND func_file=". $this->quote( $func_file );
	return $this->get_row_by_sql( $sql );
}

function _blocks_msg( $local_msgs )
{
	global $msgs , $myblocksadmin_parsed_updateblock ;
	if( ! empty( $msgs ) && ! empty( $local_msgs ) && empty( $myblocksadmin_parsed_updateblock ) ) {
		$msgs = array_merge( $msgs , $local_msgs ) ;
		$myblocksadmin_parsed_updateblock = true ;
	}
}

//---------------------------------------------------------
// cat table
//---------------------------------------------------------
function _get_top_cat_rows()
{
	$this->_catlist_class =& webphoto_inc_catlist::getSingleton( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	return $this->_catlist_class->get_top_cat_rows();
}

//---------------------------------------------------------
// config
//---------------------------------------------------------
function _init_config( $dirname )
{
	$config_handler =& webphoto_inc_config::getSingleton( $dirname );

	$this->_cfg_catonsubmenu = $config_handler->get_by_name('catonsubmenu');
	$this->_cfg_use_pathinfo = $config_handler->get_by_name('use_pathinfo');
}

//---------------------------------------------------------
// group_permission
//---------------------------------------------------------
function _init_group_permission( $dirname, $trust_dirname  )
{
	$permission_handler =& webphoto_inc_group_permission::getSingleton( 
		$dirname, $trust_dirname );

	$this->_has_insertable = $permission_handler->has_perm( 'insertable' );
	$this->_has_rateview   = $permission_handler->has_perm( 'rateview' );
}

//---------------------------------------------------------
// xoops_module
//---------------------------------------------------------
function _init_xoops_module( $dirname )
{
	$module_handler =& xoops_gethandler('module');
	$module = $module_handler->getByDirname( $dirname );
	if ( is_object($module) ) {
		$this->_MODULE_ID = $module->getVar( 'mid' );
	}
}

//---------------------------------------------------------
// xoops param
//---------------------------------------------------------
function _init_is_module_admin()
{
	global $xoopsUser;
	if ( is_object($xoopsUser) ) {
		if ( $xoopsUser->isAdmin( $this->_MODULE_ID ) ) {
			$this->_is_module_admin = true;
		}
	}
}

//---------------------------------------------------------
// workdir
//---------------------------------------------------------
function _init_workdir( $dirname, $trust_dirname )
{
	$workdir_class =& webphoto_inc_workdir::getSingleton( $dirname, $trust_dirname );
	$this->_config_workdir_default = $workdir_class->get_config_workdir() ;
}

//---------------------------------------------------------
// ini
//---------------------------------------------------------
function _init_ini( $dirname, $trust_dirname )
{
	$this->init_base_ini( $dirname , $trust_dirname );

	$this->_ini_cfg_groupid_admin
		= $this->get_ini('xoops_version_cfg_groupid_admin');
	$this->_ini_cfg_groupid_user
		= $this->get_ini('xoops_version_cfg_groupid_user');
	$this->_ini_sub_myphoto
		= $this->get_ini('xoops_version_sub_myphoto');
	$this->_ini_sub_popular
		= $this->get_ini('xoops_version_sub_popular');
	$this->_ini_sub_highrate
		= $this->get_ini('xoops_version_sub_highrate');
	$this->_ini_sub_search
		= $this->get_ini('xoops_version_sub_search');
	$this->_ini_sub_cat_prefix
		= $this->get_ini('xoops_version_sub_cat_prefix');
}

// --- class end ---
}

?>