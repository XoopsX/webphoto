<?php
// $Id: flashvar_edit.php,v 1.3 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// JW Player 5.6
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_flashvar_handler
// 2009-01-10 K.OHWADA
// webphoto_flashvar_edit -> webphoto_edit_flashvar_edit
// 2008-11-08 K.OHWADA
// uploader_fetch() -> fetch_image()
// rename() -> resize_rotate() 
// _C_WEBPHOTO_UPLOAD_FIELD_PLOGO
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_flashvar_edit
//=========================================================
class webphoto_edit_flashvar_edit extends webphoto_edit_base
{
	var $_config_class;
	var $_flashvar_handler;
	var $_upload_class;
	var $_image_cmd_class;

	var $_cfg_logo_width ;

	var $_newid = 0 ;
	var $_error_upload = false;

	var $_PLAYERLOGO_SIZE       = _C_WEBPHOTO_PLAYERLOGO_SIZE ;	// 30 KB
	var $_PLAYERLOGO_FIELD_NAME = _C_WEBPHOTO_UPLOAD_FIELD_PLOGO ;

	var $_NORMAL_EXTS = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_flashvar_edit( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base( $dirname , $trust_dirname );

	$this->_flashvar_handler 
		=& webphoto_flashvar_handler::getInstance( $dirname , $trust_dirname );
	$this->_upload_class     
		=& webphoto_upload::getInstance( $dirname , $trust_dirname );

	$this->_image_cmd_class  =& webphoto_lib_image_cmd::getInstance();

	$this->_cfg_logo_width = $this->_config_class->get_by_name( 'logo_width' );

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_flashvar_edit( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// ssubmit
//---------------------------------------------------------
function submit()
{
	$this->_newid = 0 ;

	$row = $this->_flashvar_handler->create( true );
	$row = $this->build_row_by_post( $row );

// logo
	$logo = $this->fetch_logo();
	if ( $logo ) {
		$row['flashvar_logo'] = $logo ;
	}

	$newid = $this->_flashvar_handler->insert( $row );
	if ( !$newid ) {
		$this->set_error( $this->_flashvar_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	$this->_newid = $newid;
	return 0 ;
}

function build_row_by_post( $row ) 
{
	$row['flashvar_item_id']          = $this->_post_class->get_post_int( 'flashvar_item_id' );
	$row['flashvar_width']            = $this->_post_class->get_post_int( 'flashvar_width' );
	$row['flashvar_height']           = $this->_post_class->get_post_int( 'flashvar_height' );
	$row['flashvar_displaywidth']     = $this->_post_class->get_post_int( 'flashvar_displaywidth' );
	$row['flashvar_displayheight']    = $this->_post_class->get_post_int( 'flashvar_displayheight' );
	$row['flashvar_image_show']       = $this->_post_class->get_post_int(  'flashvar_image_show' );
	$row['flashvar_searchbar']        = $this->_post_class->get_post_int( 'flashvar_searchbar' );
	$row['flashvar_showeq']           = $this->_post_class->get_post_int( 'flashvar_showeq' );
	$row['flashvar_showicons']        = $this->_post_class->get_post_int( 'flashvar_showicons' );
	$row['flashvar_shownavigation']   = $this->_post_class->get_post_int( 'flashvar_shownavigation' );
	$row['flashvar_showstop']         = $this->_post_class->get_post_int( 'flashvar_showstop' );
	$row['flashvar_showdigits']       = $this->_post_class->get_post_int( 'flashvar_showdigits' );
	$row['flashvar_showdownload']     = $this->_post_class->get_post_int( 'flashvar_showdownload' );
	$row['flashvar_usefullscreen']    = $this->_post_class->get_post_int( 'flashvar_usefullscreen' );
	$row['flashvar_autoscroll']       = $this->_post_class->get_post_int( 'flashvar_autoscroll' );
	$row['flashvar_thumbsinplaylist'] = $this->_post_class->get_post_int( 'flashvar_thumbsinplaylist' );
	$row['flashvar_autostart']        = $this->_post_class->get_post_int( 'flashvar_autostart' );
	$row['flashvar_repeat']           = $this->_post_class->get_post_int( 'flashvar_repeat' );
	$row['flashvar_shuffle']          = $this->_post_class->get_post_int( 'flashvar_shuffle' );
	$row['flashvar_smoothing']        = $this->_post_class->get_post_int( 'flashvar_smoothing' );
	$row['flashvar_enablejs']         = $this->_post_class->get_post_int( 'flashvar_enablejs' );
	$row['flashvar_linkfromdisplay']  = $this->_post_class->get_post_int( 'flashvar_linkfromdisplay' );
	$row['flashvar_link_type']        = $this->_post_class->get_post_int( 'flashvar_link_type' );
	$row['flashvar_bufferlength']     = $this->_post_class->get_post_int( 'flashvar_bufferlength' );
	$row['flashvar_rotatetime']       = $this->_post_class->get_post_int( 'flashvar_rotatetime' );
	$row['flashvar_volume']           = $this->_post_class->get_post_int( 'flashvar_volume' );
	$row['flashvar_linktarget']       = $this->_post_class->get_post_text( 'flashvar_linktarget' );
	$row['flashvar_overstretch']      = $this->_post_class->get_post_text( 'flashvar_overstretch' );
	$row['flashvar_transition']       = $this->_post_class->get_post_text( 'flashvar_transition' );
	$row['flashvar_screencolor']      = $this->_post_class->get_post_text( 'flashvar_screencolor' );
	$row['flashvar_backcolor']        = $this->_post_class->get_post_text( 'flashvar_backcolor' );
	$row['flashvar_frontcolor']       = $this->_post_class->get_post_text( 'flashvar_frontcolor' );
	$row['flashvar_lightcolor']       = $this->_post_class->get_post_text( 'flashvar_lightcolor' );
	$row['flashvar_type']             = $this->_post_class->get_post_text( 'flashvar_type' );
	$row['flashvar_file']             = $this->_post_class->get_post_text( 'flashvar_file' );
	$row['flashvar_image']            = $this->_post_class->get_post_text( 'flashvar_image' );
	$row['flashvar_logo']             = $this->_post_class->get_post_text( 'flashvar_logo' );
	$row['flashvar_link']             = $this->_post_class->get_post_text( 'flashvar_link' );
	$row['flashvar_captions']         = $this->_post_class->get_post_text( 'flashvar_captions' );
	$row['flashvar_fallback']         = $this->_post_class->get_post_text( 'flashvar_fallback' );
	$row['flashvar_callback']         = $this->_post_class->get_post_text( 'flashvar_callback' );
	$row['flashvar_javascriptid']     = $this->_post_class->get_post_text( 'flashvar_javascriptid' );
	$row['flashvar_recommendations']  = $this->_post_class->get_post_text( 'flashvar_recommendations' );
	$row['flashvar_streamscript']     = $this->_post_class->get_post_text( 'flashvar_streamscript' );
	$row['flashvar_searchlink']       = $this->_post_class->get_post_text( 'flashvar_searchlink' );
	$row['flashvar_audio']            = $this->_post_class->get_post_url( 'flashvar_audio' );

// JW Player 5.6
	$row['flashvar_dock']         = $this->_post_class->get_post_int( 'flashvar_dock' );
	$row['flashvar_icons']        = $this->_post_class->get_post_int( 'flashvar_icons' );
	$row['flashvar_mute']         = $this->_post_class->get_post_int( 'flashvar_mute' );
	$row['flashvar_duration']     = $this->_post_class->get_post_int( 'flashvar_duration' );
	$row['flashvar_start']        = $this->_post_class->get_post_int( 'flashvar_start' );
	$row['flashvar_item']         = $this->_post_class->get_post_int( 'flashvar_item' );
	$row['flashvar_logo_hide']    = $this->_post_class->get_post_int( 'flashvar_logo_hide' );
	$row['flashvar_logo_margin']  = $this->_post_class->get_post_int( 'flashvar_logo_margin' );
	$row['flashvar_logo_timeout'] = $this->_post_class->get_post_int( 'flashvar_logo_timeout' );
	$row['flashvar_controlbar_idlehide'] = $this->_post_class->get_post_int( 'flashvar_controlbar_idlehide' );
	$row['flashvar_display_showmute']    = $this->_post_class->get_post_int( 'flashvar_display_showmute' );
	$row['flashvar_playlist_size']       = $this->_post_class->get_post_int( 'flashvar_playlist_size' );

	$row['flashvar_logo_over']    = $this->_post_class->get_post_float( 'flashvar_logo_over' );
	$row['flashvar_logo_out']     = $this->_post_class->get_post_float( 'flashvar_logo_out' );

	$row['flashvar_playlistfile'] = $this->_post_class->get_post_text( 'flashvar_playlistfile' );
	$row['flashvar_mediaid']      = $this->_post_class->get_post_text( 'flashvar_mediaid' );
	$row['flashvar_provider']     = $this->_post_class->get_post_text( 'flashvar_provider' );
	$row['flashvar_streamer']     = $this->_post_class->get_post_text( 'flashvar_streamer' );
	$row['flashvar_skin']         = $this->_post_class->get_post_text( 'flashvar_skin' );
	$row['flashvar_playerready']  = $this->_post_class->get_post_text( 'flashvar_playerready' );
	$row['flashvar_plugins']      = $this->_post_class->get_post_text( 'flashvar_plugins' );
	$row['flashvar_stretching']   = $this->_post_class->get_post_text( 'flashvar_stretching' );
	$row['flashvar_netstreambasepath']   = $this->_post_class->get_post_text( 'flashvar_netstreambasepath' );
	$row['flashvar_player_repeat']       = $this->_post_class->get_post_text( 'flashvar_player_repeat' );
	$row['flashvar_controlbar_position'] = $this->_post_class->get_post_text( 'flashvar_controlbar_position' );
	$row['flashvar_playlist_position']   = $this->_post_class->get_post_text( 'flashvar_playlist_position' );
	$row['flashvar_logo_file']           = $this->_post_class->get_post_text( 'flashvar_logo_file' );
	$row['flashvar_logo_link']           = $this->_post_class->get_post_text( 'flashvar_logo_link' );
	$row['flashvar_logo_linktarget']     = $this->_post_class->get_post_text( 'flashvar_logo_linktarget' );
	$row['flashvar_logo_position']       = $this->_post_class->get_post_text( 'flashvar_logo_position' );

	return $row;
}

function fetch_logo()
{
	$this->_error_upload = false;

	$ret = $this->_upload_class->fetch_image( $this->_PLAYERLOGO_FIELD_NAME );
	if ( $ret < 0 ) { 
		$this->_error_upload = true;
		$this->set_error( 'ERROR failed to update player logo' );
		$this->set_error( $this->_upload_class->get_errors() );
		return 0;	// failed
	}

	$tmp_name   = $this->_upload_class->get_uploader_file_name() ;
	$media_name = $this->_upload_class->get_uploader_media_name() ;

	if ( $tmp_name && $media_name ) {
		$tmp_file  = $this->_TMP_DIR   .'/'. $tmp_name;
		$logo_file = $this->_LOGOS_DIR .'/'. $media_name ;

		$this->_image_cmd_class->resize_rotate( 
			$tmp_file, $logo_file, $this->_cfg_logo_width, $this->_cfg_logo_width );

		return $media_name ;
	}

	return 0 ;
}

function get_newid()
{
	return $this->_newid ;
}

function get_error_upload()
{
	return $this->_error_upload ;
}

//---------------------------------------------------------
// modify
//---------------------------------------------------------
function modify()
{
	$flashvar_id = $this->_post_class->get_post_int( 'flashvar_id' );

	$row = $this->_flashvar_handler->get_row_by_id( $flashvar_id );
	if ( ! is_array($row) ) {
		$this->set_error( $this->_flashvar_handler->get_errors() );
		return _C_WEBPHOTO_ERR_NO_FALSHVAR ;
	}

	$row = $this->build_row_by_post( $row );
	$row['flashvar_time_update'] = time() ;

// logo
	$logo = $this->fetch_logo();
	if ( $logo ) {
		$row['flashvar_logo'] = $logo ;
	}

	$ret = $this->_flashvar_handler->update( $row );
	if ( !$ret ) {
		$this->set_error( $this->_flashvar_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}
	return 0;
}

//---------------------------------------------------------
// restore
//---------------------------------------------------------
function restore() 
{
	$flashvar_id = $this->_post_class->get_post_int( 'flashvar_id' );

	$current_row = $this->_flashvar_handler->get_row_by_id( $flashvar_id );
	if ( ! is_array($current_row) ) {
		$this->set_error( $this->_flashvar_handler->get_errors() );
		return _C_WEBPHOTO_ERR_NO_FALSHVAR ;
	}

	$update_row = $this->_flashvar_handler->create( true );
	$update_row['flashvar_id']          = $current_row['flashvar_id'] ;
	$update_row['flashvar_item_id']     = $current_row['flashvar_item_id'] ;
	$update_row['flashvar_time_create'] = $current_row['flashvar_time_create'] ;
	$update_row['flashvar_time_update'] = time() ;

	$ret = $this->_flashvar_handler->update( $update_row );
	if ( !$ret ) {
		$this->set_error( $this->_flashvar_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB;
	}

	return 0;
}

// --- class end ---
}

?>