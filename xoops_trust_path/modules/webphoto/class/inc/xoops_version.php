<?php
// $Id: xoops_version.php,v 1.6 2008/07/06 04:41:31 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// added use_ffmpeg use_pathinfo
// webphoto_xoops_base -> xoops_gethandler()
//
// 2008-06-30 K.OHWADA
// typo
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_xoops_version
//=========================================================
class webphoto_inc_xoops_version extends webphoto_inc_handler
{
	var $_MODULE_ID = 0;

	var $_cfg_catonsubmenu = false;
	var $_cfg_use_pathinfo = false;
	var $_has_insertable   = false;
	var $_has_rateview     = false;

	var $_UPLOAD_DIR;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_xoops_version()
{
	$this->webphoto_inc_handler();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_xoops_version();
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function build_modversion( $dirname )
{
	$this->_init( $dirname );

	$arr           = $this->_build_basic();
	$arr['sub']    = $this->_build_sub();
	$arr['blocks'] = $this->_build_blocks();
	$arr['config'] = $this->_build_config();

	return $arr;
}

function _init( $dirname )
{
	$this->init_handler( $dirname );
	$this->_init_xoops_module( $dirname );
	$this->_init_xoops_config( $dirname );
	$this->_init_xoops_group_permission( $dirname );

	$this->_UPLOAD_DIR = '/uploads/'.$dirname;
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
		$arr[] = $this->_build_sub_array_const(
			'SMNAME_MYPHOTO', $this->_build_sub_url_fct( 'myphoto' ) );
	}

	$arr[] = $this->_build_sub_array_const(
		'SMNAME_POPULAR', $this->_build_sub_url_op( 'popular' ) );

	if ( $this->_has_rateview ) {
		$arr[] = $this->_build_sub_array_const(
			'SMNAME_HIGHRATE', $this->_build_sub_url_op( 'highrate' ) );
	}

	if ( $this->_cfg_catonsubmenu ) {
		$rows = $this->_get_cat_rows_by_pid(0) ;
		if( is_array($rows) && count($rows) ) {
			foreach ( $rows as $row )
			{
				$name  = ' - '. $this->sanitize( $row['cat_title'] ) ;
				$url   = $this->_build_sub_url_category( $row['cat_id'] ) ;
				$arr[] = $this->_build_sub_array( $name, $url );
			}
		}
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
	$arr[1]['options'] = $this->_DIRNAME.'|5|0|1|20|1' ;
	$arr[1]['template'] = '' ;
	$arr[1]['can_clone'] = true ;

	$arr[2]['file'] = "blocks.php";
	$arr[2]['name'] = $this->_constant( 'BNAME_HITS' ) .' ('.$this->_DIRNAME.')' ;
	$arr[2]['description'] = "Shows most viewed photos";
	$arr[2]['show_func'] = "b_webphoto_tophits_show";
	$arr[2]['edit_func'] = "b_webphoto_tophits_edit";
	$arr[2]['options'] = $this->_DIRNAME.'|5|0|1|20|1';
	$arr[2]['template'] = '' ;
	$arr[2]['can_clone'] = true ;

	$arr[3]['file'] = "blocks.php";
	$arr[3]['name'] = $this->_constant( 'BNAME_RECENT_P' ) .' ('.$this->_DIRNAME.')' ;
	$arr[3]['description'] = "Shows recently added photos";
	$arr[3]['show_func'] = "b_webphoto_topnews_p_show";
	$arr[3]['edit_func'] = "b_webphoto_topnews_edit";
	$arr[3]['options'] = $this->_DIRNAME.'|5|0|1|20|1';
	$arr[3]['template'] = '' ;
	$arr[3]['can_clone'] = true ;

	$arr[4]['file'] = "blocks.php";
	$arr[4]['name'] = $this->_constant( 'BNAME_HITS_P' ) .' ('.$this->_DIRNAME.')' ;
	$arr[4]['description'] = "Shows most viewed photos";
	$arr[4]['show_func'] = "b_webphoto_tophits_p_show";
	$arr[4]['edit_func'] = "b_webphoto_tophits_edit";
	$arr[4]['options'] = $this->_DIRNAME.'|5|0|1|20|1';
	$arr[4]['template'] = '' ;
	$arr[4]['can_clone'] = true ;

	$arr[5]['file'] = "blocks.php";
	$arr[5]['name'] = $this->_constant( 'BNAME_RANDOM' ) .' ('.$this->_DIRNAME.')' ;
	$arr[5]['description'] = "Shows a random photo";
	$arr[5]['show_func'] = "b_webphoto_rphoto_show";
	$arr[5]['edit_func'] = "b_webphoto_rphoto_edit";
	$arr[5]['options'] = $this->_DIRNAME.'|5|0|1|20|1';
	$arr[5]['template'] = '' ;
	$arr[5]['can_clone'] = true ;

// keep block's options
	if( ! defined( 'XOOPS_CUBE_LEGACY' ) && substr( XOOPS_VERSION , 6 , 3 ) < 2.1 && ! empty( $_POST['fct'] ) && ! empty( $_POST['op'] ) && $_POST['fct'] == 'modulesadmin' && $_POST['op'] == 'update_ok' && $_POST['dirname'] == $this->_DIRNAME ) {
		$arr = $this->_blocks_keep_option( $arr );
	}

	return $arr;
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
	$arr[] = array(
		'name'			=> 'photospath' ,
		'title'			=> $this->_constant_name( 'CFG_PHOTOSPATH' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCPHOTOSPATH' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> $this->_UPLOAD_DIR.'/photos' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'thumbspath' ,
		'title'			=> $this->_constant_name( 'CFG_THUMBSPATH' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCTHUMBSPATH' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> $this->_UPLOAD_DIR.'/thumbs' ,
		'options'		=> array()
	) ;

// add for webphoto
	$arr[] = array(
		'name'			=> 'giconspath' ,
		'title'			=> $this->_constant_name( 'CFG_GICONSPATH' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCTHUMBSPATH' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> $this->_UPLOAD_DIR.'/gicons' ,
		'options'		=> array()
	) ;

// add for webphoto
	$arr[] = array(
		'name'			=> 'tmppath' ,
		'title'			=> $this->_constant_name( 'CFG_TMPPATH' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCTHUMBSPATH' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> $this->_UPLOAD_DIR.'/tmp' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'imagingpipe' ,
		'title'			=> $this->_constant_name( 'CFG_IMAGINGPIPE' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCIMAGINGPIPE' ) ,
		'formtype'		=> 'select' ,
		'valuetype'		=> 'int' ,
		'default'		=> _C_WEBPHOTO_PIPEID_GD ,
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

	$arr[] = array(
		'name'			=> 'imagickpath' ,
		'title'			=> $this->_constant_name( 'CFG_IMAGICKPATH' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCIMAGICKPATH' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> '' ,
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

// add for webphoto
	$arr[] = array(
		'name'			=> 'use_ffmpeg' ,
		'title'			=> $this->_constant_name( 'CFG_USE_FFMPEG' ) ,
		'description'	=> '' ,
		'formtype'		=> 'yesno' ,
		'valuetype'		=> 'int' ,
		'default'		=> '' ,
		'options'		=> array()
	) ;

// add for webphoto
	$arr[] = array(
		'name'			=> 'ffmpegpath' ,
		'title'			=> $this->_constant_name( 'CFG_FFMPEGPATH' ) ,
		'description'	=> $this->_constant_name( 'CFG_DESCFFMPEGPATH' ) ,
		'formtype'		=> 'textbox' ,
		'valuetype'		=> 'text' ,
		'default'		=> '' ,
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
		'default'		=> '100000' ,
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

	$arr[] = array(
		'name'			=> 'thumbrule' ,
		'title'			=> $this->_constant_name( 'CFG_THUMBRULE' ) ,
		'description'	=> '' ,
		'formtype'		=> 'select' ,
		'valuetype'		=> 'text' ,
		'default'		=> 'w' ,
		'options'		=> array(
			$this->_constant( 'OPT_CALCFROMWIDTH'   ) => 'w' ,
			$this->_constant( 'OPT_CALCFROMHEIGHT'  ) => 'h' ,
			$this->_constant( 'OPT_CALCWHINSIDEBOX' ) => 'b'
		)
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
		'default'		=> '0' ,
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

	$arr[] = array(
		'name'			=> 'viewcattype' ,
		'title'			=> $this->_constant_name( 'CFG_VIEWCATTYPE' ) ,
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
		'formtype'		=> 'text' ,
		'valuetype'		=> 'text' ,
		'default'		=> '2' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'gmap_longitude' ,
		'title'			=> $this->_constant_name( 'CFG_LONGITUDE' ) ,
		'description'	=> '' ,
		'formtype'		=> 'text' ,
		'valuetype'		=> 'text' ,
		'default'		=> '-155' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'gmap_zoom' ,
		'title'			=> $this->_constant_name( 'CFG_ZOOM' ) ,
		'description'	=> '' ,
		'formtype'		=> 'text' ,
		'valuetype'		=> 'int' ,
		'default'		=> '2' ,
		'options'		=> array()
	) ;

	$arr[] = array(
		'name'			=> 'use_popbox' ,
		'title'			=> $this->_constant_name( 'CFG_USE_POPBOX' ) ,
		'description'	=> '' ,
		'formtype'		=> 'yesno' ,
		'valuetype'		=> 'int' ,
		'default'		=> '1' ,
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
		'name'			=> 'index_desc' ,
		'title'			=> $this->_constant_name( 'CFG_INDEX_DESC' ) ,
		'description'	=> '' ,
		'formtype'		=> 'textarea' ,
		'valuetype'		=> 'text' ,
		'default'		=> $this->_build_config_index_desc() ,
		'options'		=> array()
	) ;

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
function _get_cat_rows_by_pid( $pid, $limit=0, $offset=0 )
{
	$sql  = "SELECT * FROM ". $this->prefix_dirname( 'cat' );
	$sql .= " WHERE cat_pid=". $pid;
	$sql .= " ORDER BY cat_title ASC";
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

//---------------------------------------------------------
// xoops_config
//---------------------------------------------------------
function _init_xoops_config( $dirname )
{
	$config_handler =& webphoto_inc_config::getInstance();
	$config_handler->init( $dirname );

	$this->_cfg_catonsubmenu = $config_handler->get_by_name('catonsubmenu');
	$this->_cfg_use_pathinfo = $config_handler->get_by_name('use_pathinfo');
}

//---------------------------------------------------------
// xoops_group_permission
//---------------------------------------------------------
function _init_xoops_group_permission( $dirname )
{
	$permission_handler =& webphoto_inc_group_permission::getInstance();
	$permission_handler->init( $dirname );

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

// --- class end ---
}

?>