<?php
// $Id: photo_create.php,v 1.1 2008/08/08 04:39:14 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_create
//=========================================================
class webphoto_photo_create extends webphoto_base_this
{
	var $_build_class;
	var $_image_class;
	var $_mime_class;
	var $_video_class;
	var $_exif_class;

// config
	var $_cfg_makethumb    = false;
	var $_cfg_use_ffmpeg   = false;
	var $_cfg_use_pathinfo = false;
	var $_has_resize       = false;
	var $_has_rotate       = false;

// set param
	var $_flag_print_first_msg = false;
	var $_flag_force_db        = false;

// result
	var $_newid = 0;
	var $_row   = null;
	var $_photo_cat_id  = 0;
	var $_flag_resized  = false;
	var $_flag_video_flash_created = false ;
	var $_flag_video_flash_failed  = false ;
	var $_flag_video_thumb_created = false ;
	var $_flag_video_thumb_failed  = false ;

	var $_TITLE_DEFAULT = 'no title';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_create( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_build_class  =& webphoto_photo_build::getInstance( $dirname );
	$this->_mime_class   =& webphoto_mime::getInstance( $dirname );
	$this->_video_class  =& webphoto_video::getInstance( $dirname );
	$this->_exif_class   =& webphoto_lib_exif::getInstance();
	$this->_image_class  =& webphoto_image_create::getInstance( $dirname , $trust_dirname );

	$this->_cfg_makethumb    = $this->get_config_by_name( 'makethumb' );
	$this->_cfg_use_ffmpeg   = $this->get_config_by_name( 'use_ffmpeg' );
	$this->_cfg_use_pathinfo = $this->get_config_by_name( 'use_pathinfo' );

	$this->_has_resize  = $this->_image_class->has_resize();
	$this->_has_rotate  = $this->_image_class->has_rotate();

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo_create( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create from param
//---------------------------------------------------------
function create_from_param( $param )
{
	$title = $param['title'] ;

// insert
	$row = $this->_photo_handler->create( true );
	$row['photo_title']       = $title ;
	$row['photo_time_create'] = $param['time_create'] ;
	$row['photo_time_update'] = $param['time_update'] ;
	$row['photo_uid']         = $param['uid'] ;
	$row['photo_cat_id']      = $param['cat_id'] ;
	$row['photo_description'] = $param['description'] ;
	$row['photo_status']      = $param['status'] ;
	$row['photo_search']      = $this->_build_class->build_search( $row );

// insert record
	$photo_id = $this->_photo_handler->insert( $row, $this->_flag_force_db );
	if ( !$photo_id ) {
		$this->print_msg_level_admin( ' DB Error, ', true );
		$this->set_error( $this->_photo_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB ;
	}

	$this->print_first_msg( $photo_id, $title );

	return $photo_id ;
}

//---------------------------------------------------------
// create from file
//---------------------------------------------------------
function create_from_file( $param )
{
	$this->_newid = 0 ;
	$this->_row   = null;
	$this->_flag_resized = false ;

	$msg_exif = null ;

	if ( isset( $param['src_file'] ) ) {
		$src_file = $param['src_file'];

	} else {
		$this->print_msg_level_admin( ' Empty file, ', true );
		return _C_WEBPHOTO_ERR_EMPTY_FILE ;
	}

	if ( ! is_readable($src_file) ) {
		$this->print_msg_level_admin( ' Cannot read file, ', true );
		return _C_WEBPHOTO_ERR_FILEREAD;
	}

	if ( isset( $param['cat_id'] ) ) {
		$cat_id = intval($param['cat_id']);

	} else {
		$this->print_msg_level_admin( ' Empty cat_id, ', true );
		return _C_WEBPHOTO_ERR_EMPTY_CAT ;
	}

	$uid         = isset($param['uid'])     ? intval($param['uid'])    : $this->_xoops_uid ;
	$status      = isset($param['status'])  ? intval($param['status']) : _C_WEBPHOTO_STATUS_APPROVED ;
	$time_create = isset($param['time_create']) ? intval($param['time_create']) : time() ;
	$time_update = isset($param['time_update']) ? intval($param['time_update']) : time() ;
	$title       = isset($param['title'])       ? $param['title']       : $this->_TITLE_DEFAULT ;
	$description = isset($param['description']) ? $param['description'] : null ;
	$rotate      = isset($param['rotate'])      ? $param['rotate']      : null ;

	$mode_video_thumb = isset($param['mode_video_thumb']) ?
		intval($param['mode_video_thumb']) : _C_WEBPHOTO_VIDEO_THUMB_SINGLE ;

	$flag_video_thumb     = false;
	$video_thumb_tmp_file = null;
	$thumb_info           = null;

// insert
	$row = $this->_photo_handler->create( true );
	$row['photo_title']       = $title ;
	$row['photo_uid']         = $uid;
	$row['photo_cat_id']      = $cat_id ;
	$row['photo_time_create'] = $time_create ;
	$row['photo_time_update'] = $time_update ;
	$row['photo_description'] = $description ;
	$row['photo_status']      = $status ;

// get exif date
	$exif_info = $this->get_exif_info( $src_file );
	if ( is_array($exif_info) ) {
		$datetime  = $exif_info['datetime_mysql'];
		$equipment = $exif_info['equipment'] ;
		$exif      = $exif_info['all_data'] ;
		if ( $datetime ) {
			$row['photo_datetime'] = $datetime ;
		}
		if ( $equipment ) {
			$row['photo_equipment'] = $equipment ;
		}
		if ( $exif ) {
			$msg_exif = ' get exif, ' ;
			$row['photo_cont_exif'] = $exif ;
		} else {
			$msg_exif = ' no exif, ' ;
		}
	}

	$row['photo_search'] = $this->_build_class->build_search( $row );

// insert record
	$photo_id = $this->_photo_handler->insert( $row, $this->_flag_force_db );
	if ( !$photo_id ) {
		$this->print_msg_level_admin( ' DB Error, ', true );
		$this->set_error( $this->_photo_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB ;
	}

	$row['photo_id'] = $photo_id ;
	$this->_newid    = $photo_id ;
	$this->_row      = $row ;

	$this->print_first_msg( $photo_id, $title );

	if ( $msg_exif ) {
		$this->print_msg_level_admin( $msg_exif );
	}

// create photo
	if ( $rotate ) {
		$this->_image_class->cmd_set_mode_rotate( $rotate );
	}

	$ret1 = $this->_image_class->create_photo( $src_file , $photo_id );
	if ( $ret1 == _C_WEBPHOTO_IMAGE_READFAULT ) {
		$this->print_msg_level_admin( ' Cannot read file, ', true );
		return _C_WEBPHOTO_ERR_FILEREAD;
	}
	if ( $ret1 == _C_WEBPHOTO_IMAGE_RESIZE ) {
		$this->_flag_resized = true;
		$this->print_msg_level_admin( ' resize photo, ' );
	}

	$photo_info = $this->_image_class->get_photo_info(); 
	if ( !is_array($photo_info) ) {
		$this->print_msg_level_admin( ' Cannot create photo, ', true );
		return _C_WEBPHOTO_ERR_CREATE_PHOTO ;
	}

	$photo_path = $photo_info['photo_cont_path'] ;
	$photo_name = $photo_info['photo_cont_name'] ;
	$photo_ext  = $photo_info['photo_cont_ext'] ;
	$photo_file = XOOPS_ROOT_PATH . $photo_path ;

	$thumb_src_file  = $photo_file ;
	$thumb_src_ext   = $photo_ext ;

	$photo_info = $this->_mime_class->add_mime_to_info_if_empty( $photo_info );

// create video
	$param_video = $this->create_video_flash_thumb( 
		$mode_video_thumb, $photo_id, $photo_info );

	if ( is_array($param_video) ) {
		$photo_info       = $param_video['photo_info'];
		$flag_video_thumb = $param_video['thumb_flag'];
		$thumb_src_file   = $param_video['thumb_file'];
		$thumb_src_ext    = $param_video['thumb_ext'];
		$video_thumb_tmp_file = $thumb_src_file ;
	}

	if ( $this->is_normal_ext( $photo_ext ) || $flag_video_thumb ) {

// create thumb
		if ( $this->_cfg_makethumb ) {
			$this->print_msg_level_admin( ' create thumb, ' );
			$this->_image_class->create_thumb_from_image_file( 
				$thumb_src_file, $photo_id, $thumb_src_ext );
			$thumb_info = $this->_image_class->get_thumb_info();

// substitute with photo image
		} else {
			$this->_image_class->create_thumb_substitute( $photo_path, $photo_ext );
			$thumb_info = $this->_image_class->get_thumb_info();
		}

// thumb icon
	} else {
		$this->_image_class->create_thumb_icon( $photo_id, $photo_ext );
		$thumb_info = $this->_image_class->get_thumb_info();
	}

	$photo_thumb_info 
		= $this->_image_class->merge_photo_thumb_info( $photo_info, $thumb_info );

// remove temp file
	if ( $video_thumb_tmp_file ) {
		$this->_utility_class->unlink_file( $video_thumb_tmp_file );
	}

// update date
	$update_row = array_merge( $row, $photo_thumb_info );

// update record
	$ret2 = $this->_photo_handler->update( $update_row, $this->_flag_force_db );
	if ( !$ret2 ) {
		$this->print_msg_level_admin( ' DB Error, ', true );
		$this->set_error( $this->_photo_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB ;
	}

	$this->_row = $update_row ;

	return 0 ;
}

function get_exif_info( $file )
{
	$info = $this->_exif_class->read_file( $file );
	if ( !is_array($info) ) {
		return null;
	}

	$info['datetime_mysql'] = $this->exif_to_mysql_datetime( $info );
	return $info;
}

function create_video_flash_thumb( $mode, $photo_id, $photo_info )
{
	$photo_path = $photo_info['photo_cont_path'] ;
	$photo_ext  = $photo_info['photo_cont_ext'] ;
	$photo_file = XOOPS_ROOT_PATH . $photo_path ;

	$this->_flag_video_flash_created = false ;
	$this->_flag_video_flash_failed  = false ;
	$this->_flag_video_thumb_created = false ;
	$this->_flag_video_thumb_failed  = false ;

	if ( ! $this->_mime_class->is_video_ext( $photo_ext ) || ! $this->_cfg_use_ffmpeg ) {
		return null;
	}

	$thumb_flag = false ;
	$thumb_file = null ;
	$thumb_ext  = null ;

	$photo_info = $this->_video_class->add_duration_size_to_info( $photo_info );

// create flash
	$flash_name = $this->_image_class->build_photo_name( 
		$photo_id, $this->_video_class->get_flash_ext() );

	$ret1 = $this->_video_class->create_flash( $photo_file, $flash_name ) ;
	if ( $ret1 == _C_WEBPHOTO_VIDEO_CREATED ) {
		$this->_flag_video_flash_created  = true ;
		$this->print_msg_level_admin( ' create flash, ' );
		$photo_info = array_merge( $photo_info, $this->_video_class->get_flash_info() );

	} elseif ( $ret1 == _C_WEBPHOTO_VIDEO_FAILED ) {
		$this->_flag_video_flash_failed = true;
		$this->print_msg_level_admin( ' fail to create flash, ', true );
	}

// create video thumb
	$param_thumb = $this->create_video_thumb_for_file( 
		$mode, $photo_id, $photo_file, $photo_ext );

	if ( is_array($param_thumb) ) {
		$thumb_flag = $param_thumb['flag'];
		$thumb_file = $param_thumb['file'];
		$thumb_ext  = $param_thumb['ext'];
	}

	$param = array(
		'photo_info' => $photo_info ,
		'thumb_flag' => $thumb_flag ,
		'thumb_file' => $thumb_file ,
		'thumb_ext'  => $thumb_ext ,
	);

	return $param;
}

function create_video_thumb_for_file( $mode, $photo_id, $photo_file, $photo_ext )
{
	$param = null;

	if ( ! $this->_cfg_makethumb ) {
		return null;
	}

	if ( $mode == _C_WEBPHOTO_VIDEO_THUMB_PLURAL ) {
		$this->create_video_plural_thumbs( $photo_id, $photo_file, $photo_ext );
	} else {
		$param = $this->create_video_single_thumb( $photo_id, $photo_file );
	}

	return $param;
}

function create_video_plural_thumbs( $photo_id, $photo_file, $photo_ext )
{
	$count = $this->_video_class->create_plural_thumbs( $photo_id, $photo_file );
	if ( $count ) {

// create thumb icon
		$this->_image_class->copy_thumb_icon_in_dir( 
			$this->_TMP_DIR, 
			$this->_video_class->get_first_thumb_node(), 
			$photo_ext );

		$this->_flag_video_thumb_created = true;
		return true;

	}

	$this->_flag_video_thumb_failed = true;
	return false;
}

function create_video_single_thumb( $photo_id, $photo_file )
{
	$video_thumb_file = $this->_video_class->create_single_thumb( $photo_id, $photo_file ) ;
	if ( $video_thumb_file ) {
		$param = array(
			'flag' => true ,
			'file' => $video_thumb_file ,
			'ext'  => $this->_video_class->get_thumb_ext() ,
		);
		return $param;
	}
	return null;
}

function print_first_msg( $photo_id, $title )
{
	if ( $this->_flag_print_first_msg ) {
		$this->print_msg_level_user( $this->build_msg_photo_title( $photo_id, $title ) );
	}
}

function build_msg_photo_title( $photo_id, $title )
{
	if ( $this->_cfg_use_pathinfo ) {
		$url = $this->_MODULE_URL .'/index.php/photo/'. $photo_id .'/';
	} else {
		$url = $this->_MODULE_URL .'/index.php?fct=photo&amp;p='. $photo_id ;
	}

	$msg  = ' <a href="'. $url .'" target="_blank">';
	$msg .= $photo_id;
	$msg .= ' : ';
	$msg .= $this->sanitize( $title );
	$msg .= '</a> : ';
	return $msg ;
}

//---------------------------------------------------------
// update video thumb
//---------------------------------------------------------
function update_video_thumb( $photo_id, $name )
{
	$this->_row = null;
	$this->_flag_video_thumb_created = false ;
	$this->_flag_video_thumb_failed  = false ;

	$row = $this->_photo_handler->get_row_by_id( $photo_id );
	if ( !is_array($row) ) {
		return _C_WEBPHOTO_ERR_NO_RECORD;
	}

	$thumb_info = $this->create_video_thumb_for_update( $row, $name );
	if ( !is_array($thumb_info) || !count($thumb_info) ) {
		return _C_WEBPHOTO_ERR_CREATE_THUMB ;
	}

// update
	$row_update = array_merge( $row, $thumb_info );
	$ret = $this->_photo_handler->update( $row_update );
	if ( !$ret ) {
		$this->set_error( $this->_photo_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

// save row
	$this->_row = $row_update ;
	$this->_photo_cat_id = $row_update['photo_cat_id'];

	return 0;
}

function create_video_thumb_for_update( $row, $name )
{
	$thumb_info = null;

	$photo_id   = $row['photo_id'];
	$photo_path = $row['photo_cont_path'];
	$photo_name = $row['photo_cont_name'];
	$photo_ext  = $row['photo_cont_ext'];
	$thumb_path = $row['photo_thumb_path'];

	$tmp_file = $this->_TMP_DIR .'/'.  $name;

// create thumb
	if ( is_file( $tmp_file) ) {

// remove old file
		if ( $thumb_path ) {
			$this->unlink_file( XOOPS_ROOT_PATH.$thumb_path );
		}

		$this->_image_class->create_thumb_from_image_file( $tmp_file, $photo_id );
		$thumb_info = $this->_image_class->get_thumb_info();
	}

// success
	if ( is_array($thumb_info) ) {
		$this->_flag_video_thumb_created = true ;

// fail to ceate
	} else  {
		$this->_flag_video_thumb_failed = true ;
		$this->_image_class->create_thumb_substitute( $photo_path, $photo_name, $photo_ext );
		$thumb_info = $this->_image_class->get_thumb_info();
	}

// remove tmp
	$max = $this->_video_class->get_thumb_plural_max();
	for ( $i=0; $i<=$max; $i++ )
	{
		$tmp_name = $this->_video_class->build_thumb_name( $photo_id, $i, true );
		$tmp_file = $this->_TMP_DIR .'/'.  $tmp_name;
		$this->unlink_file( $tmp_file );
	}

	return $thumb_info;
}

function get_photo_cat_id()
{
	return $this->_photo_cat_id ;
}

//---------------------------------------------------------
// msg
//---------------------------------------------------------
function print_msg_level_admin( $msg, $flag_highlight=false, $flag_br=false )
{
	$str = $this->build_msg_level( _C_WEBPHOTO_MSG_LEVEL_ADMIN, $msg, $flag_highlight, $flag_br );
	if ( $str ) {
		echo $str;
	}
}

function print_msg_level_user( $msg, $flag_highlight=false, $flag_br=false )
{
	$str = $this->build_msg_level( _C_WEBPHOTO_MSG_LEVEL_USER, $msg, $flag_highlight, $flag_br );
	if ( $str ) {
		echo $str;
	}
}

//---------------------------------------------------------
// mime class
//---------------------------------------------------------
function get_my_allowed_mimes()
{
	return $this->_mime_class->get_cached_my_allowed_mimes();
}

function is_my_allow_ext( $ext )
{
	return $this->_mime_class->is_my_allow_ext( $ext );
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_flag_force_db( $val )
{
	$this->_flag_force_db = (bool)$val;
}

function set_flag_print_first_msg( $val )
{
	$this->_flag_print_first_msg = (bool)$val;
}

//---------------------------------------------------------
// get param
//---------------------------------------------------------
function has_resize()
{
	return $this->_has_resize ;
}

function has_rotate()
{
	return $this->_has_rotate ;
}

function get_newid()
{
	return $this->_newid ;
}

function get_row()
{
	return $this->_row ;
}

function get_resized()
{
	return $this->_flag_resized ;
}

function get_video_flash_created()
{
	return $this->_flag_video_flash_created ;
}

function get_video_flash_failed()
{
	return $this->_flag_video_flash_failed ;
}

function get_video_thumb_created()
{
	return $this->_flag_video_thumb_created ;
}

function get_video_thumb_failed()
{
	return $this->_flag_video_thumb_failed;
}

// --- class end ---
}

?>