<?php
// $Id: small_create.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2009-04-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_small_create
//=========================================================
class webphoto_edit_small_create extends webphoto_edit_middle_thumb_create
{
	var $_remote_class ;

	var $_tmp_file = null;

	var $_FOPEN_MODE = 'wb';
	var $_FLAG_CHMOD = true;
	var $_FLAG_CREATE_FROM_ICON = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_small_create( $dirname , $trust_dirname  )
{
	$this->webphoto_edit_middle_thumb_create( $dirname , $trust_dirname  );

	$this->_remote_class =& webphoto_lib_remote_file::getInstance();
}

function &getInstance( $dirname , $trust_dirname  )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_small_create( $dirname , $trust_dirname  );
	}
	return $instance;
}

//---------------------------------------------------------
// create small image
//---------------------------------------------------------
// factory_create
function create_small_param_from_external_icon( $row )
{
	$param = $this->build_small_param_from_external_icon( $row );
	if ( ! is_array($param) ) {
		return false;
	}

	$small_param = $this->create_small_param( $param );

// remove tmp file
	if ( $this->_tmp_file ) {
		unlink( $this->_tmp_file );
	}

	return $small_param;
}

// update_130
function build_small_param_from_external_icon( $row )
{
	$external_url   = $row['item_external_url'];
	$external_thumb = $row['item_external_thumb'];
	$icon_name      = $row['item_icon_name'];

	if ( $this->is_image_url($external_url) ) {
		return $this->build_small_param_external( $row, $external_url );
	}
	if ( $this->is_image_url($external_thumb) ) {
		return $this->build_small_param_external( $row, $external_thumb );
	}
	if ( $this->_FLAG_CREATE_FROM_ICON && $icon_name ) {
		return $this->build_small_param_icon( $icon_name );
	}
	return null;
}

function build_small_param_external( $row, $url )
{
	$this->_tmp_file = null;

	$src_ext  = $this->parse_ext( $url );
	$src_file = $this->build_tmp_file( $src_ext );

	$data = $this->get_remote_file( $url );
	if ( empty($data) ) {
		return false;
	}

	$this->write_file( $src_file, $data );
	if ( ! file_exists($src_file) ) {
		return false;
	}

	$this->_tmp_file = $src_file;

	$param = array(
		'item_id'  => $row['item_id'] ,
		'src_ext'  => $src_ext ,
		'src_file' => $src_file ,
	);
	return $param;
}

function build_small_param_icon( $icon_name)
{
	$src_file = $this->build_icon_file( $icon_name, false );
	if ( ! file_exists($src_file) ) {
		return false ;
	}

	$param = array(
		'item_id'  => $row['item_id'] ,
		'src_ext'  => $this->parse_ext( $icon_name ) ,
		'src_file' => $src_file ,
	);

	return $param;
}

function is_image_url( $url )
{
	if ( empty($url) ) {
		return false;
	}
	$ext = $this->parse_ext( $url );
	if ( empty($ext) ) {
		return false;
	}
	return $this->is_image_ext( $ext );
}

//---------------------------------------------------------
// remote class
//---------------------------------------------------------
function get_remote_file( $url )
{
	return $this->_remote_class->read_file( $url );
}

//---------------------------------------------------------
// utility class
//---------------------------------------------------------
function write_file( $file, $data )
{
	return $this->_utility_class->write_file( $file, $data, $this->_FOPEN_MODE, $this->_FLAG_CHMOD );
}

// --- class end ---
}

?>