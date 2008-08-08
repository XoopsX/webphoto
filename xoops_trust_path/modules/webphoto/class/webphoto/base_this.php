<?php
// $Id: base_this.php,v 1.5 2008/08/08 04:36:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-01 K.OHWADA
// added exists_cat_record()
// used is_set_mail() has_mail()
// tmppath -> tmpdir
// 2008-07-24 K.OHWADA
// BUG : wrong judgment in check_dir
// 2008-07-01 K.OHWADA
// added exif_to_mysql_datetime()
// used config use_pathinfo
// used class  webphoto_build_uri
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_base_this
//=========================================================
class webphoto_base_this extends webphoto_lib_base
{
	var $_config_class;
	var $_photo_handler;
	var $_cat_handler;
	var $_post_class;
	var $_perm_class;
	var $_uri_class;

	var $_is_japanese = false;

	var $_PHOTOS_PATH;
	var $_PHOTOS_DIR ;
	var $_PHOTOS_URL ;
	var $_THUMBS_PATH;
	var $_THUMBS_DIR;
	var $_THUMBS_URL;
	var $_TMP_DIR;
	var $_FILE_DIR;

	var $_NORMAL_EXTS ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_base_this( $dirname, $trust_dirname )
{
	$this->webphoto_lib_base( $dirname, $trust_dirname );

	$this->_photo_handler  =& webphoto_photo_handler::getInstance( $dirname );
	$this->_cat_handler    =& webphoto_cat_handler::getInstance( $dirname );

	$this->_perm_class   =& webphoto_permission::getInstance( $dirname );
	$this->_config_class =& webphoto_config::getInstance( $dirname );
	$this->_post_class   =& webphoto_lib_post::getInstance();
	$this->_uri_class    =& webphoto_uri::getInstance( $dirname );

	$this->_PHOTOS_PATH = $this->_config_class->get_photos_path();
	$this->_THUMBS_PATH = $this->_config_class->get_thumbs_path();
	$this->_TMP_DIR     = $this->_config_class->get_by_name( 'tmpdir' );
	$this->_FILE_DIR    = $this->_config_class->get_by_name( 'file_dir' );

	$this->_PHOTOS_DIR  = XOOPS_ROOT_PATH . $this->_PHOTOS_PATH ;
	$this->_THUMBS_DIR  = XOOPS_ROOT_PATH . $this->_THUMBS_PATH ;
	$this->_PHOTOS_URL  = XOOPS_URL       . $this->_PHOTOS_PATH ;
	$this->_THUMBS_URL  = XOOPS_URL       . $this->_THUMBS_PATH ;

	$this->_ICONS_URL = $this->_MODULE_URL .'/images/icons';

	$this->_NORMAL_EXTS = explode( '|', _C_WEBPHOTO_IMAGE_EXTS );

	$this->_is_japanese = $this->_xoops_class->is_japanese( _C_WEBPHOTO_JPAPANESE ) ;
}

//---------------------------------------------------------
// photo globals
//---------------------------------------------------------
function get_photo_globals()
{
	$cfg_colsoftableview = $this->get_config_by_name('colsoftableview');
	$cfg_is_set_mail     = $this->_config_class->is_set_mail() ;
	$cfg_file_dir        = $this->get_config_by_name('file_dir') ;
	$has_mail            = $this->_perm_class->has_mail() ;
	$has_file            = $this->_perm_class->has_file() ;

	$show_menu_mail = ( $cfg_is_set_mail && $has_mail );
	$show_menu_file = ( $cfg_file_dir    && $has_file );

	$arr = array(
		'mydirname'           => $this->_DIRNAME ,
		'photos_url'          => XOOPS_URL . $this->_PHOTOS_PATH ,
		'thumbs_url'          => XOOPS_URL . $this->_THUMBS_PATH ,
		'use_pathinfo'        => $this->get_config_by_name('use_pathinfo') ,
		'cfg_thumb_width'     => $this->get_config_by_name('thumb_width') ,
		'cfg_thumb_height'    => $this->get_config_by_name('thumb_height') ,
		'cfg_middle_width'    => $this->get_config_by_name('middle_width') ,
		'cfg_middle_height'   => $this->get_config_by_name('middle_height') ,
		'cfg_usehits'         => $this->get_config_by_name('usehits') ,
		'cfg_is_set_mail'     => $cfg_is_set_mail ,
		'cfg_file_dir'        => $cfg_file_dir ,
		'cfg_colsoftableview' => $cfg_colsoftableview ,
		'width_of_tableview'  => intval( 100 / $cfg_colsoftableview ),
		'has_rateview'        => $this->_perm_class->has_rateview() ,
		'has_ratevote'        => $this->_perm_class->has_ratevote() ,
		'has_tellafriend'     => $this->_perm_class->has_tellafriend() ,
		'has_insertable'      => $this->_perm_class->has_insertable(),
		'show_menu_mail'      => $show_menu_mail ,
		'show_menu_file'      => $show_menu_file ,
		'cat_main_width'      => _C_WEBPHOTO_CAT_MAIN_WIDTH_DEFAULT ,
		'cat_main_height'     => _C_WEBPHOTO_CAT_MAIN_HEIGHT_DEFAULT ,
		'cat_sub_width'       => _C_WEBPHOTO_CAT_SUB_WIDTH_DEFAULT ,
		'cat_sub_height'      => _C_WEBPHOTO_CAT_SUB_HEIGHT_DEFAULT ,

// for XOOPS 2.0.18
		'xoops_dirname'       => $this->_DIRNAME ,
		'xoops_modulename'    => $this->sanitize( $this->_MODULE_NAME ) ,

	);

	return $arr;
}

function get_config_by_name( $name )
{
	return $this->_config_class->get_by_name( $name );
}

//---------------------------------------------------------
// check
//---------------------------------------------------------
// BUG : wrong judgment in check_dir
function check_dir( $dir )
{
	if ( $dir && is_dir( $dir ) && is_writable( $dir ) && is_readable( $dir ) ) {
		return 0;
	}
	$this->set_error( 'dir error : '.$dir );
	return _C_WEBPHOTO_ERR_CHECK_DIR ;
}

//---------------------------------------------------------
// normal exts
//---------------------------------------------------------
function get_normal_exts()
{
	return $this->_NORMAL_EXTS ;
}

function is_normal_ext( $ext )
{
	if ( in_array( strtolower( $ext ) , $this->_NORMAL_EXTS ) ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// exif
//---------------------------------------------------------
function exif_to_mysql_datetime( $exif )
{
	$datetime     = $exif['datetime'];
	$datetime_gnu = $exif['datetime_gnu'];

	if ( $datetime_gnu ) {
		return $datetime_gnu;
	}

	$time = $this->_utility_class->str_to_time( $datetime );
	if ( $time <= 0 ) { return false; }

	return $this->_utility_class->time_to_mysql_datetime( $time );
}

//---------------------------------------------------------
// file
//---------------------------------------------------------
function unlink_path( $path )
{
	$file = XOOPS_ROOT_PATH . $path;
	if ( $path && $file && file_exists($file) && is_file($file) && !is_dir($file) ) {
		unlink( $file );
	}
}

//---------------------------------------------------------
// footer
//---------------------------------------------------------
function get_footer_param()
{
	$arr = array(
		'is_module_admin' => $this->_is_module_admin,
		'execution_time'  => $this->_utility_class->get_execution_time( WEBPHOTO_TIME_START ) ,
		'memory_usage'    => $this->_utility_class->get_memory_usage() ,
		'happy_linux_url' => $this->_utility_class->get_happy_linux_url( $this->_is_japanese ) ,
	);
	return $arr;
}

//---------------------------------------------------------
// uri class
//---------------------------------------------------------
function build_uri_operate( $op )
{
	return $this->_uri_class->build_operate( $op );
}

function build_uri_photo( $id )
{
	return $this->_uri_class->build_photo( $id );
}

function build_uri_category( $id, $param=null )
{
	return $this->_uri_class->build_category( $id, $param );
}

function build_uri_user( $id )
{
	return $this->_uri_class->build_user( $id );
}

function rawurlencode_uri_encode_str( $str )
{
	return $this->_uri_class->rawurlencode_encode_str( $str );
}

function decode_uri_str( $str )
{
	return $this->_uri_class->decode_str( $str );
}

//---------------------------------------------------------
// cat handler
//---------------------------------------------------------
function exists_cat_record()
{
	return $this->_cat_handler->exists_record() ;
}

function check_valid_catid( $id )
{
	$row = $this->_cat_handler->get_cached_row_by_id( $id );
	if ( is_array($row) ) {
		return true;
	}
	return false;
}

function get_cached_cat_title_by_id( $cat_id, $flag_sanitize=false )
{
	return $this->_cat_handler->get_cached_value_by_id_name( $cat_id, 'cat_title', $flag_sanitize );
}

function get_cached_cat_value_by_id( $cat_id, $name, $flag_sanitize=false )
{
	return $this->_cat_handler->get_cached_value_by_id_name( $cat_id, $name, $flag_sanitize );
}

function get_cat_nice_path_from_id( $sel_id, $title, $funcURL, $path="" )
{
	return $this->_cat_handler->get_nice_path_from_id( $sel_id, $title, $funcURL, $path );
}

//---------------------------------------------------------
// xoops permission class
//---------------------------------------------------------
function has_editable_by_uid( $uid )
{
	$has_editable = $this->_perm_class->has_editable();

	if ( $has_editable && $this->is_photo_owner( $uid ) ) {
		return true;
	}
	return false;
}

function is_photo_owner( $uid )
{
	if ( ( $this->_xoops_uid == $uid ) || $this->_is_module_admin ) {
		return true;
	}
	return false;
}

// --- class end ---
}

?>