<?php
// $Id: base.php,v 1.1 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_base
//=========================================================
class webphoto_edit_base extends webphoto_base_this
{
	var $_mime_class ;
	var $_icon_build_class ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_base( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );

	$this->_mime_class =& webphoto_mime::getInstance( $dirname );
	$this->_icon_build_class =& webphoto_edit_icon_build::getInstance( $dirname );
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
// post class
//---------------------------------------------------------
function get_post_text( $key, $default=null )
{
	return $this->_post_class->get_post_text( $key, $default );
}

function get_post_int( $key, $default=0 )
{
	return $this->_post_class->get_post_int( $key, $default );
}

function get_post_float( $key, $default=0 )
{
	return $this->_post_class->get_post_float( $key, $default );
}

function get_post( $key, $default=null )
{
	return $this->_post_class->get_post( $key, $default );
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
// mime class
//---------------------------------------------------------
function ext_to_kind( $ext )
{
	return $this->_mime_class->ext_to_kind( $ext );
}

function get_my_allowed_mimes()
{
	return $this->_mime_class->get_my_allowed_mimes();
}

function is_my_allow_ext( $ext )
{
	return $this->_mime_class->is_my_allow_ext( $ext );
}

//---------------------------------------------------------
// icon
//---------------------------------------------------------
function build_item_row_icon_if_empty( $row, $ext=null )
{
	return $this->_icon_build_class->build_row_icon_if_empty( $row, $ext );
}

function build_icon_image( $ext )
{
	return $this->_icon_build_class->build_icon_image( $ext );
}

//---------------------------------------------------------
// timestamp
//---------------------------------------------------------
function get_server_time_by_post( $key, $default=0 )
{
	$time = $this->_post_class->get_post_time( $key, $default );
	if ( $time > 0 ) {
		return $this->user_to_server_time( $time );
	} else {
		return $default ;
	}
}

//---------------------------------------------------------
// msg
//---------------------------------------------------------
function check_msg_level_admin()
{
	return $this->check_msg_level( _C_WEBPHOTO_MSG_LEVEL_ADMIN );
}

function check_msg_level_user()
{
	return $this->check_msg_level( _C_WEBPHOTO_MSG_LEVEL_USER );
}

function set_msg_level_admin( $msg, $flag_highlight=false, $flag_br=false )
{
	if ( ! $this->check_msg_level_admin() ) {
		return ;	// no action
	}
	$str = $this->build_msg( $msg, $flag_highlight, $flag_br );
	if ( $str ) {
		$this->set_msg( $str );
	}
}

function set_msg_level_user( $msg, $flag_highlight=false, $flag_br=false )
{
	if ( ! $this->check_msg_level_user() ) {
		return ;	// no action
	}
	$str = $this->build_msg( $msg, $flag_highlight, $flag_br );
	if ( $str ) {
		$this->set_msg( $str );
	}
}

//---------------------------------------------------------
// for admin/redothumbs.php
//---------------------------------------------------------
function clear_tmp_files_in_tmp_dir()
{
	return $this->clear_tmp_files( $this->_TMP_DIR, _C_WEBPHOTO_UPLOADER_PREFIX );
}

function clear_tmp_files( $dir_path , $prefix )
{
	$files = $this->_utility_class->get_files_in_dir( $dir_path );
	if ( !is_array($files) ) {
		return 0 ;
	}

	$prefix_len = strlen( $prefix ) ;
	$count = 0 ;

	foreach( $files as $file ) 
	{
		if( strncmp( $file , $prefix , $prefix_len ) === 0 ) {
			$file_full = $dir_path .'/'. $file ;
			$ret = unlink( $file_full );
			if ( $ret ){ 
				$count ++ ;
			}
		}
	}

	return $count ;
}

// --- class end ---
}

?>