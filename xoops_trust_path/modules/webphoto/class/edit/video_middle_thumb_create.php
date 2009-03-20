<?php
// $Id: video_middle_thumb_create.php,v 1.2 2009/03/20 04:18:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-03-15 K.OHWADA
// create_small_param()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_video_middle_thumb_create
//=========================================================
class webphoto_edit_video_middle_thumb_create extends webphoto_edit_base
{
	var $_ffmpeg_class;
	var $_middle_thumb_create_class;
	var $_item_build_class;

// config
	var $_cfg_makethumb;
	var $_cfg_use_ffmpeg;

	var $_item_row     = null;
	var $_item_cat_id  = 0 ;
	var $_flag_created = false ;
	var $_flag_failed  = false ;

	var $_VIDEO_THUMB_MAX = _C_WEBPHOTO_VIDEO_THUMB_PLURAL_MAX ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_video_middle_thumb_create( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base( $dirname , $trust_dirname );

	$this->_ffmpeg_class     =& webphoto_ffmpeg::getInstance( $dirname );
	$this->_item_build_class =& webphoto_edit_item_build::getInstance( $dirname );
	$this->_middle_thumb_create_class =& webphoto_edit_middle_thumb_create::getInstance( $dirname );

	$this->_cfg_makethumb  = $this->get_config_by_name( 'makethumb' );
	$this->_cfg_use_ffmpeg = $this->get_config_by_name( 'use_ffmpeg' );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_video_middle_thumb_create( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// video thumb
//---------------------------------------------------------
function video_thumb( $item_row )
{
	$num = $this->_post_class->get_post_text('num') ;
	$ret = $this->video_thumb_exec( $item_row, $num );
	return $this->build_failed_msg( $ret );
}

function video_thumb_exec( $item_row, $num )
{
	$this->clear_msg_array();

	$ret = $this->update_video_thumb_by_item_row( $item_row, $num ) ;
	if ( $ret < 0 ) {
		return $ret;
	}

	if ( $this->_flag_failed ) {
		$this->set_msg_array( $this->get_constant('ERR_VIDEO_THUMB') ) ;
	}

	return 0;
}

// Fatal error: Call to undefined method build_failed_msg()
function build_failed_msg( $ret )
{
	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_DB:
			$this->set_error_in_head_with_admin_info( 'DB Error' );
			return false;
	}
	return true;
}

//---------------------------------------------------------
// update video thumb
//---------------------------------------------------------
function update_video_thumb_by_item_row( $item_row, $num )
{
	if ( !is_array($item_row) ) {
		return 0 ;	// no action
	}

	$item_id = $item_row['item_id'] ;
	$cat_id  = $item_row['item_cat_id'] ;
	$ext     = $item_row['item_ext'] ;

	$this->_item_row    = null;
	$this->_item_cat_id = 0 ;

	$file_id_array = $this->update_video_middle_thumb( $item_row, $num );

	$row_update = $this->_item_build_class->build_row_files( $item_row, $file_id_array );

// --- update item ---
	$ret = $this->_item_handler->update( $row_update, $this->_flag_force_db );
	if ( !$ret ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB ;
	}

// save row
	$this->_item_row    = $row_update ;
	$this->_item_cat_id = $cat_id ;

	return 0;
}

//---------------------------------------------------------
// create video images
//---------------------------------------------------------
function create_video_plural_images( $param )
{
	$this->_flag_created = false ;
	$this->_flag_failed  = false ;

	$item_id  = $param['item_id'];
	$src_file = $param['src_file'];
	$src_kind = $param['src_kind'];

	if ( ! $this->_cfg_makethumb ) {
		return 0 ;
	}
	if ( ! $this->_cfg_use_ffmpeg ) {
		return 0 ;
	}
	if ( ! $this->is_video_kind( $src_kind ) ) {
		return 0 ;
	}

	$count = $this->_ffmpeg_class->create_plural_images( $item_id, $src_file );
	if ( $count ) {
		$this->_flag_created = true;
		return 1 ;
	}

	$this->_flag_failed = true;
	return -1 ;
}

//---------------------------------------------------------
// update video thumb
//---------------------------------------------------------
function update_video_middle_thumb( $item_row, $num )
{
	$this->_flag_created = false ;
	$this->_flag_failed  = false ;

	$item_id = $item_row['item_id'] ;

	$thumb_id   = 0 ;
	$middle_id  = 0 ;
	$small_id   = 0 ;

// created thumb
	$src_file = $this->build_video_thumb_file( $item_id, $num );
	if ( is_file($src_file) ) {
		$thumb_id = $this->create_update_video_thumb_common(
			$item_row, $src_file, _C_WEBPHOTO_FILE_KIND_THUMB );
		$middle_id = $this->create_update_video_thumb_common(
			$item_row, $src_file, _C_WEBPHOTO_FILE_KIND_MIDDLE );
		$small_id = $this->create_update_video_thumb_common(
			$item_row, $src_file, _C_WEBPHOTO_FILE_KIND_SMALL );

		if ( $thumb_id > 0 ) {
			$this->_flag_created = true ;
		} else {
			$this->_flag_failed  = true ;
		}
	}

// remove files
	$this->unlink_video_thumb_temp_files( $item_id );

// update date
	$file_id_array = array(
		'thumb_id'  => $thumb_id ,
		'middle_id' => $middle_id ,
		'small_id'  => $small_id ,
	);

	return $file_id_array;
}

function create_update_video_thumb_common( $item_row, $src_file, $kind )
{
	if ( !is_file( $src_file) ) {
		return 0 ;	// no action
	}

	$item_id  = $item_row['item_id'] ;
	$item_ext = $item_row['item_ext'] ;

	$flag_update = false;

	$file_id = $this->_item_handler->build_value_fileid_by_kind( $item_row, $kind );
	if ( $file_id > 0 ) {
		$file_row = $this->_file_handler->get_row_by_id( $file_id );

		if ( is_array($file_row) ) {
			$flag_update = true ;
			$file_path   = $file_row['file_path'] ;

// remove old file
			if ( $file_path ) {
				$this->unlink_file( XOOPS_ROOT_PATH . $file_path );
			}
		}
	}

	$param = array(
		'item_id'   => $item_id ,
		'src_file'  => $src_file ,
		'icon_name' => $item_ext ,
	);

	if ( $kind == _C_WEBPHOTO_FILE_KIND_THUMB ) {
		$param = $this->_middle_thumb_create_class->create_thumb_param( $param );

	} elseif ( $kind == _C_WEBPHOTO_FILE_KIND_MIDDLE ) {
		$param = $this->_middle_thumb_create_class->create_middle_param( $param );

	} elseif ( $kind == _C_WEBPHOTO_FILE_KIND_SMALL ) {
		$param = $this->_middle_thumb_create_class->create_small_param( $param );
	}

	$param['duration'] = 0 ;
	$param['kind']     = $kind ;

// update
	if ( $flag_update ) {
		$this->unlink_current_file( $file_row, $param );

		$ret = $this->update_file( $file_row, $param );
		if ( !$ret ) {
			$file_id = 0;	// fail
		}

// insert
	} else {
		$ret = $this->insert_file( $item_id, $param );
		if ( $ret > 0 ) {
			$file_id = $ret;	// newid
		}
	}

	return $file_id ;
}

function insert_file( $item_id, $param )
{
	$param['item_id'] = $item_id ;

	$row = $this->_file_handler->create();
	$row = $this->_file_handler->build_row_by_param( $row, $param );

	$newid = $this->_file_handler->insert( $row, $this->_flag_force_db );
	if ( ! $newid ) {
		$this->set_error( $this->_file_handler->get_errors() );
		return false ;
	}

	return $newid;
}

function update_file( $row, $param )
{
	$param['time_update'] = time();

	$row = $this->_file_handler->build_row_by_param( $row, $param );

// update
	$ret = $this->_file_handler->update( $row );
	if ( ! $ret ) {
		$this->set_error( $this->_file_handler->get_errors() );
		return false ;
	}

	return true ;
}

function unlink_current_file( $file_row, $param )
{
	$file_path = $file_row['file_path'];
	$path      = $param['path'];

	if ( $file_path && ( $file_path != $path ) ) {
		$this->unlink_path($file_path);
	}
}

function unlink_video_thumb_temp_files( $item_id )
{
	for ( $i = 1; $i <= $this->_VIDEO_THUMB_MAX; $i ++ )
	{
		$file = $this->build_video_thumb_file( $item_id, $i );
		$this->unlink_file( $file );
	}
}

function build_video_thumb_file( $item_id, $num )
{
	$file = null ;
	$name = $this->_ffmpeg_class->build_thumb_name( $item_id, $num );
	if ( $name ) {
		$file = $this->_TMP_DIR .'/'.  $name;
	}
	return $file ;
}

//---------------------------------------------------------
// get param
//---------------------------------------------------------
function get_flag_created()
{
	return $this->_flag_created ;
}

function get_flag_failed()
{
	return $this->_flag_failed;
}

// --- class end ---
}

?>