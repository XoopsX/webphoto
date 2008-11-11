<?php
// $Id: base_this.php,v 1.11 2008/11/11 06:53:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-11-08 K.OHWADA
// tmpdir -> workdir
// 2008-10-01 K.OHWADA
// webphoto_kind
// build_redirect()
// use get_uploads_path()
// 2008-09-01 K.OHWADA
// photo_handler -> item_handler
// added preload_init()
// changed get_photo_globals()
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
	var $_item_handler;
	var $_file_handler;
	var $_cat_handler;
	var $_post_class;
	var $_perm_class;
	var $_uri_class;
	var $_preload_class;
	var $_kind_class;

	var $_is_japanese = false;

	var $_UPLOADS_DIR;
	var $_PHOTOS_PATH;
	var $_PHOTOS_DIR ;
	var $_PHOTOS_URL ;
	var $_THUMBS_PATH;
	var $_THUMBS_DIR;
	var $_THUMBS_URL;
	var $_MIDDLES_PATH;
	var $_MIDDLES_DIR;
	var $_MIDDLESS_URL;
	var $_CATS_PATH;
	var $_CATS_DIR;
	var $_CATS_URL;
	var $_GICONS_PATH;
	var $_GICONS_DIR;
	var $_GICONS_URL;
	var $_GSHADOWS_PATH;
	var $_GSHADOWS_DIR;
	var $_GSHADOWS_URL;
	var $_FLASHS_PATH;
	var $_FLASHS_DIR;
	var $_FLASHS_URL;
	var $_QRS_DIR;
	var $_QRS_URL;
	var $_PLAYLISTS_DIR;
	var $_LOGOS_DIR;
	var $_MEDIAS_DIR;
	var $_WORK_DIR;
	var $_MAIL_DIR;
	var $_TMP_DIR;
	var $_LOG_DIR;
	var $_FILE_DIR;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_base_this( $dirname, $trust_dirname )
{
	$this->webphoto_lib_base( $dirname, $trust_dirname );

	$this->_item_handler  =& webphoto_item_handler::getInstance( $dirname );
	$this->_file_handler  =& webphoto_file_handler::getInstance( $dirname );
	$this->_cat_handler   =& webphoto_cat_handler::getInstance( $dirname );

	$this->_perm_class   =& webphoto_permission::getInstance( $dirname );
	$this->_config_class =& webphoto_config::getInstance( $dirname );
	$this->_post_class   =& webphoto_lib_post::getInstance();
	$this->_uri_class    =& webphoto_uri::getInstance( $dirname );
	$this->_kind_class   =& webphoto_kind::getInstance();

	$uploads_path    = $this->_config_class->get_uploads_path();
	$medias_path     = $this->_config_class->get_medias_path();
	$this->_WORK_DIR = $this->_config_class->get_by_name( 'workdir' );
	$this->_FILE_DIR = $this->_config_class->get_by_name( 'file_dir' );

	$this->_PHOTOS_PATH     = $uploads_path.'/photos' ;
	$this->_THUMBS_PATH     = $uploads_path.'/thumbs' ;
	$this->_MIDDLES_PATH    = $uploads_path.'/middles' ;
	$this->_CATS_PATH       = $uploads_path.'/categories' ;
	$this->_GICONS_PATH     = $uploads_path.'/gicons' ;
	$this->_GSHADOWS_PATH   = $uploads_path.'/gshadows' ;
	$this->_FLASHS_PATH     = $uploads_path.'/flashs' ;
	$qrs_path               = $uploads_path.'/qrs' ;
	$playlists_path         = $uploads_path.'/playlists' ;
	$logos_path             = $uploads_path.'/logos' ;

	$this->_UPLOADS_DIR    = XOOPS_ROOT_PATH . $uploads_path ;
	$this->_PHOTOS_DIR     = XOOPS_ROOT_PATH . $this->_PHOTOS_PATH ;
	$this->_THUMBS_DIR     = XOOPS_ROOT_PATH . $this->_THUMBS_PATH ;
	$this->_MIDDLES_DIR    = XOOPS_ROOT_PATH . $this->_MIDDLES_PATH ;
	$this->_CATS_DIR       = XOOPS_ROOT_PATH . $this->_CATS_PATH ;
	$this->_GICONS_DIR     = XOOPS_ROOT_PATH . $this->_GICONS_PATH ;
	$this->_GSHADOWS_DIR   = XOOPS_ROOT_PATH . $this->_GSHADOWS_PATH ;
	$this->_FLASHS_DIR     = XOOPS_ROOT_PATH . $this->_FLASHS_PATH ;
	$this->_QRS_DIR        = XOOPS_ROOT_PATH . $qrs_path ;
	$this->_PLAYLISTS_DIR  = XOOPS_ROOT_PATH . $playlists_path ;
	$this->_LOGOS_DIR      = XOOPS_ROOT_PATH . $logos_path ;
	$this->_MEDIAS_DIR     = XOOPS_ROOT_PATH . $medias_path ;
	$this->_PHOTOS_URL     = XOOPS_URL . $this->_PHOTOS_PATH ;
	$this->_THUMBS_URL     = XOOPS_URL . $this->_THUMBS_PATH ;
	$this->_MIDDLES_URL    = XOOPS_URL . $this->_MIDDLES_PATH ;
	$this->_CATS_URL       = XOOPS_URL . $this->_CATS_PATH ;
	$this->_GICONS_URL     = XOOPS_URL . $this->_GICONS_PATH ;
	$this->_GSHADOWS_URL   = XOOPS_URL . $this->_GSHADOWS_PATH ;
	$this->_FLASHS_URL     = XOOPS_URL . $this->_FLASHS_PATH ;
	$this->_QRS_URL        = XOOPS_URL . $qrs_path ;

	$this->_TMP_DIR   = $this->_WORK_DIR .'/tmp' ;
	$this->_MAIL_DIR  = $this->_WORK_DIR .'/mail' ;
	$this->_LOG_DIR   = $this->_WORK_DIR .'/log' ;

	$this->_ICONS_URL = $this->_MODULE_URL .'/images/icons';

	$this->_is_japanese = $this->_xoops_class->is_japanese( _C_WEBPHOTO_JPAPANESE ) ;
}

//---------------------------------------------------------
// photo globals
//---------------------------------------------------------
function get_photo_globals()
{
	$cfg_colsoftableview = $this->get_config_by_name('colsoftableview');
	$cfg_cat_width       = $this->get_config_by_name('cat_width');
	$cfg_csub_width      = $this->get_config_by_name('csub_width');

	$cfg_is_set_mail     = $this->_config_class->is_set_mail() ;
	$cfg_file_dir        = $this->get_config_by_name('file_dir') ;
	$has_mail            = $this->_perm_class->has_mail() ;
	$has_file            = $this->_perm_class->has_file() ;

	$show_menu_mail = ( $cfg_is_set_mail && $has_mail );
	$show_menu_file = ( $cfg_file_dir    && $has_file );

	$arr = array(
		'mydirname'            => $this->_DIRNAME ,
		'photos_url'           => $this->_PHOTOS_URL ,
		'thumbs_url'           => $this->_THUMBS_URL ,
		'middles_url'          => $this->_MIDDLES_URL ,
		'qrs_url'              => $this->_QRS_URL ,
		'use_pathinfo'         => $this->get_config_by_name('use_pathinfo') ,
		'cfg_is_set_mail'      => $cfg_is_set_mail ,
		'width_of_tableview'   => intval( 100 / $cfg_colsoftableview ),
		'has_rateview'         => $this->_perm_class->has_rateview() ,
		'has_ratevote'         => $this->_perm_class->has_ratevote() ,
		'has_tellafriend'      => $this->_perm_class->has_tellafriend() ,
		'has_insertable'       => $this->_perm_class->has_insertable(),
		'show_menu_mail'       => $show_menu_mail ,
		'show_menu_file'       => $show_menu_file ,
		'cat_main_width'       => $cfg_cat_width ,
		'cat_main_height'      => $cfg_cat_width ,
		'cat_sub_width'        => $cfg_csub_width ,
		'cat_sub_height'       => $cfg_csub_width ,

// for XOOPS 2.0.18
		'xoops_dirname'        => $this->_DIRNAME ,
		'xoops_modulename'     => $this->sanitize( $this->_MODULE_NAME ) ,

	);

// config
	$config_array = $this->get_config_array();
	foreach ( $config_array as $k => $v ) {
		$arr[ 'cfg_'.$k ] = $v ;
	}

	return $arr;
}

function get_config_array()
{
	return $this->_config_class->get_config_array();
}

function get_config_by_name( $name )
{
	return $this->_config_class->get_by_name( $name );
}

//---------------------------------------------------------
// check dir
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
// check waiting
//---------------------------------------------------------
function build_check_waiting()
{
	$url = $this->_MODULE_URL.'/admin/index.php?fct=item_manager&amp;op=list_waiting' ;
	$str = '';

	$waiting = $this->_item_handler->get_count_waiting();
	if ( $waiting > 0 ) {
		$str  = '<a href="'. $url .'" style="color:red;">';
		$str .= sprintf( _AM_WEBPHOTO_CAT_FMT_NEEDADMISSION , $waiting ) ;
		$str .= "</a><br />\n";
	}
	return $str;
}

//---------------------------------------------------------
// kind class
//---------------------------------------------------------
function get_normal_exts()
{
	return $this->_kind_class->get_image_exts() ;
}

function is_normal_ext( $ext )
{
	return $this->_kind_class->is_image_ext( $ext ) ;
}

function is_image_ext( $ext )
{
	return $this->_kind_class->is_image_ext( $ext ) ;
}

function is_swfobject_ext( $ext )
{
	return $this->_kind_class->is_swfobject_ext( $ext ) ;
}

function is_mediaplayer_ext( $ext )
{
	return $this->_kind_class->is_mediaplayer_ext( $ext ) ;
}

function is_video_docomo_ext( $ext )
{
	return $this->_kind_class->is_video_docomo_ext( $ext ) ;
}

function is_undefined_kind( $kind )
{
	return $this->_kind_class->is_undefined_kind( $kind ) ;
}

function is_image_kind( $kind )
{
	return $this->_kind_class->is_image_kind( $kind ) ;
}

function is_video_audio_kind( $kind )
{
	return $this->_kind_class->is_video_audio_kind( $kind ) ;
}

function is_video_kind( $kind )
{
	return $this->_kind_class->is_video_kind( $kind ) ;
}

function is_audio_kind( $kind )
{
	return $this->_kind_class->is_audio_kind( $kind ) ;
}

function is_playlist_kind( $kind )
{
	return $this->_kind_class->is_playlist_kind( $kind ) ;
}

function is_playlist_feed_kind( $kind )
{
	return $this->_kind_class->is_playlist_feed_kind( $kind ) ;
}

function is_playlist_dir_kind( $kind )
{
	return $this->_kind_class->is_playlist_dir_kind( $kind ) ;
}

function is_external_type_general( $type )
{
	return $this->_kind_class->is_external_type_general( $type ) ;
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
// file handler
//---------------------------------------------------------
function get_file_row_by_kind( $row, $kind )
{
	$file_id = $this->build_value_fileid_by_kind( $row, $kind );
	if ( $file_id > 0 ) {
		return $this->_file_handler->get_row_by_id( $file_id );
	}
	return null;
}

function get_cached_file_row_by_kind( $row, $kind )
{
	$file_id = $this->build_value_fileid_by_kind( $row, $kind );
	if ( $file_id > 0 ) {
		return $this->_file_handler->get_cached_row_by_id( $file_id );
	}
	return null;
}

function build_value_fileid_by_kind( $row, $kind )
{
	return $this->_item_handler->build_value_fileid_by_kind( $row, $kind );
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
// preload class
//---------------------------------------------------------
function preload_init()
{
	$this->_preload_class =& webphoto_d3_preload::getInstance();
	$this->_preload_class->init( $this->_DIRNAME , $this->_TRUST_DIRNAME );
}

function preload_constant()
{
	$arr = $this->_preload_class->get_preload_const_array();

	if ( !is_array($arr) || !count($arr) ) {
		return true;	// no action
	}

	foreach( $arr as $k => $v )
	{
		$local_name = strtoupper( '_' . $k );

// array type
		if ( strpos($k, 'array_') === 0 ) {
			$temp = $this->str_to_array( $v, '|' );
			if ( is_array($temp) && count($temp) ) {
				$this->$local_name = $temp;
			}

// string type
		} else {
			$this->$local_name = $v;
		}
	}

}

function preload_error( $flag_debug )
{
	$errors = $this->_preload_class->get_errors();
	if ( is_array($errors) && count($errors) ) {
		$this->set_error( $errors );
		if ( $flag_debug ) {
			echo "<pre>";
			print_r( $errors );
			echo "</pre><br />\n";
		}
	}
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