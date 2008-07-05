<?php
// $Id: upload.php,v 1.3 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// init_media_uploader( $has_resize )
//   -> init_media_uploader( $has_resize, $allowed_mimes, $allowed_exts )
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_upload
//=========================================================
class webphoto_upload extends webphoto_base_this
{
	var $_uploader_class;

	var $_uploader_media_name = null;
	var $_uploader_media_type = null;
	var $_uploader_file_name  = null;
	var $_tmp_name            = null;

	var $_PHP_UPLOAD_ERRORS = array();
	var $_UPLOADER_ERRORS   = array();

	var $_NORMAL_EXTS = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_upload( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_init_errors();
	$this->_NORMAL_EXTS = explode( '|', _C_WEBPHOTO_IMAGE_EXTS );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_upload( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function fetch_for_edit( $field )
{
	$this->_tmp_name = null;

	$ret = $this->uploader_fetch( $field );
	if ( $ret <= 0 ) { 
		return $ret;	// failed or no file
	}

	if ( ! $this->is_readable_in_tmp_dir( $this->_uploader_file_name ) ) {
		return _C_WEBPHOTO_ERR_FILEREAD;
	}

	$this->_tmp_name = $this->_uploader_file_name;
	return 1 ;	// success
}

function fetch_for_gicon( $field_name, $allow_noimage=false )
{
	$tmp_name        = null;
	$this->_tmp_name = null;

// if image file uploaded
	$ret1 = $this->uploader_fetch( $field_name );
	if ( $ret1 <= 0 ) { 
		return $ret1;	// failed or no file
	}

	$tmp_name = $this->get_uploader_file_name();

	if ( empty($tmp_name) && !$allow_noimage ) {
		return 0;	// no image
	}

	if ( ! $this->is_readable_in_tmp_dir( $tmp_name ) ) {
		return _C_WEBPHOTO_ERR_FILEREAD;
	}

	$ext = $this->_parse_ext( $tmp_name );

	if ( $ext && !$this->_is_normal_ext($ext) ) {
		if ( $tmp_name ) {
			$this->unlink_file( $this->_TMP_DIR .'/'. $tmp_name ) ;
		}
		return _C_WEBPHOTO_ERR_NOT_ALLOWED_EXT;
	}

	$this->_tmp_name = $tmp_name;
	return 1;	// with image
}

function get_tmp_name()
{
	return $this->_tmp_name;
}

//---------------------------------------------------------
// uploader class
//---------------------------------------------------------
function init_media_uploader( $has_resize, $allowed_mimes, $allowed_exts )
{
	$cfg_fsize    = $this->get_config_by_name( 'fsize' );
	$cfg_width    = $this->get_config_by_name( 'width' );
	$cfg_height   = $this->get_config_by_name( 'height' );

	if ( $has_resize ) {
		$this->_uploader_class = new webphoto_lib_uploader( $this->_TMP_DIR , $allowed_mimes , $cfg_fsize , null , null , $allowed_exts ) ;
	} else {
		$this->_uploader_class = new webphoto_lib_uploader( $this->_TMP_DIR , $allowed_mimes , $cfg_fsize , $cfg_width , $cfg_height , $allowed_exts ) ;
	}

	$this->_uploader_class->setPrefix( _C_WEBPHOTO_UPLOADER_PREFIX ) ;
}

function set_image_extensions()
{
	$this->_uploader_class->setAllowedExtensions( $this->_get_normal_exts() ) ;
}

function set_allowed_extensions( $extensions )
{
	$this->_uploader_class->setAllowedExtensions( $extensions ) ;
}

function uploader_fetch( $media_name, $index=null )
{
// http://www.php.net/manual/en/features.file-upload.errors.php
// UPLOAD_ERR_NO_FILE = 4

	$ret1 = $this->_uploader_class->fetchMedia( $media_name, $index );
	if ( !$ret1 ) {
		$error_num = $this->_uploader_class->getMediaError();
		if ( $error_num == UPLOAD_ERR_NO_FILE ) {
			return 0;	// no action
		}

		$this->build_uploader_errors();
		$this->unlink_file( $this->_uploader_class->getSavedDestination() );
		return _C_WEBPHOTO_ERR_UPLOAD;
	}

	$ret2 = $this->_uploader_class->upload();
	if ( !$ret2 ) {
		$this->build_uploader_errors();
		$this->unlink_file( $this->_uploader_class->getSavedDestination() );
		return _C_WEBPHOTO_ERR_UPLOAD;
	}

	// Succeed to upload
	// The original file name will be the title if title is empty
	$this->_uploader_media_name = $this->_uploader_class->getMediaName() ;
	$this->_uploader_media_type = $this->_uploader_class->getMediaType();
	$this->_uploader_file_name  = $this->_uploader_class->getSavedFileName() ;

	return 1;	// Succeed
}

function get_uploader_file_name()
{
	return $this->_uploader_file_name ;
}

function get_uploader_media_name()
{
	return $this->_uploader_media_name;
}

function get_uploader_media_type()
{
	return $this->_uploader_media_type;
}

function is_readable_files_tmp_name( $field )
{
	return is_readable( $_FILES[ $field ]['tmp_name'] );
}

function is_readable_in_tmp_dir( $name )
{
	$file = $this->_TMP_DIR .'/' . $name;
	if ( $name && is_readable( $file ) ) {
		return true;
	}
	return false;
}

function exist_file_param( $field )
{
	if ( isset($_FILES[ $field ]) && $_FILES[ $field ]['name'] && $_FILES[ $field ]['tmp_name'] ) {
		return true;
	}
	return false;
}

function build_uploader_errors()
{
	$codes = $this->_uploader_class->getErrorCodes();
	foreach ( $codes as $code ) {
		$this->build_uploader_error_single( $code );
	}
}

function build_uploader_error_single( $code )
{
	$cfg_fsize    = $this->get_config_by_name( 'fsize' );
	$cfg_width    = $this->get_config_by_name( 'width' );
	$cfg_height   = $this->get_config_by_name( 'height' );

	$err1 = $this->get_uploader_error_msg( $code );
	$err2 = '';

	switch ( $code )
	{
		case 7:
			$err2 = $this->get_php_upload_error_msg( $this->_uploader_class->getMediaError() );
			break;

		case 8:
		case 9:
			$err2 = $this->_uploader_class->getUploadDir();
			break;

		case 10:
			$err2 = $this->_uploader_class->getMediaType();
			break;

		case 11:
			$err1 .= ' : '.$this->_uploader_class->getMediaSize();
			$err1 .= ' > '.$cfg_size;
			break;

		case 12:
			$err1 .= ' : '.$this->_uploader_class->getMediaWidth();
			$err1 .= ' > '.$cfg_width;
			break;

		case 13:
			$err1 .= ' : '.$this->_uploader_class->getMediaHeight();
			$err1 .= ' > '.$cfg_height;
			break;

		case 14:
			$err2 = $this->_uploader_class->getMediaName();
			break;

		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
		default:
			break;
	}

	$this->set_error( $err1 );
	if ( $err2 ) {
		$this->set_error( $err2 );
	}
}

//---------------------------------------------------------
// error msg
//---------------------------------------------------------
function _init_errors()
{
	$cfg_fsize = $this->get_config_by_name( 'fsize' );

	$err_2 = sprintf( $this->get_constant('PHP_UPLOAD_ERR_FORM_SIZE'), 
		$this->_utility_class->format_filesize( $cfg_fsize ) );

// http://www.php.net/manual/en/features.file-upload.errors.php
	$this->_PHP_UPLOAD_ERRORS = array(
//		0 => $this->get_constant('PHP_UPLOAD_ERR_OK') ,
		1 => $this->get_constant('PHP_UPLOAD_ERR_INI_SIZE') ,
		2 => $err_2 ,
		3 => $this->get_constant('PHP_UPLOAD_ERR_PARTIAL') ,
		4 => $this->get_constant('PHP_UPLOAD_ERR_NO_FILE') ,
		6 => $this->get_constant('PHP_UPLOAD_ERR_NO_TMP_DIR') ,
		7 => $this->get_constant('PHP_UPLOAD_ERR_CANT_WRITE') ,
		8 => $this->get_constant('PHP_UPLOAD_ERR_EXTENSION') ,
	);

	$this->_UPLOADER_ERRORS = array(
		1  => $this->get_constant('UPLOADER_ERR_NOT_FOUND') ,
		2  => $this->get_constant('UPLOADER_ERR_INVALID_FILE_SIZE') ,
		3  => $this->get_constant('UPLOADER_ERR_EMPTY_FILE_NAME') ,
		4  => $this->get_constant('UPLOADER_ERR_NO_FILE') ,
		5  => $this->get_constant('UPLOADER_ERR_NOT_SET_DIR') ,
		6  => $this->get_constant('UPLOADER_ERR_NOT_ALLOWED_EXT') ,
		7  => $this->get_constant('UPLOADER_ERR_PHP_OCCURED') , // mediaError
		8  => $this->get_constant('UPLOADER_ERR_NOT_OPEN_DIR') , // uploadDir
		9  => $this->get_constant('UPLOADER_ERR_NO_PERM_DIR') , // uploadDir
		10 => $this->get_constant('UPLOADER_ERR_NOT_ALLOWED_MIME') , // mediaType
		11 => $this->get_constant('UPLOADER_ERR_LARGE_FILE_SIZE') , // mediaSize
		12 => $this->get_constant('UPLOADER_ERR_LARGE_WIDTH') , // maxWidth
		13 => $this->get_constant('UPLOADER_ERR_LARGE_HEIGHT') , // maxHeight
		14 => $this->get_constant('UPLOADER_ERR_UPLOAD') , // mediaName
	);
}

function get_php_upload_error_msg( $num )
{
	if ( isset( $this->_PHP_UPLOAD_ERRORS[ $num ] ) ) {
		return  $this->_PHP_UPLOAD_ERRORS[ $num ];
	}
	return 'Other Error';
}

function get_uploader_error_msg( $num )
{
	if ( isset( $this->_UPLOADER_ERRORS[ $num ] ) ) {
		return  $this->_UPLOADER_ERRORS[ $num ];
	}
	return 'Other Error';
}

//---------------------------------------------------------
// normal exts
//---------------------------------------------------------
function _get_normal_exts()
{
	return $this->_NORMAL_EXTS ;
}

function _is_normal_ext( $ext )
{
	if ( in_array( strtolower( $ext ) , $this->_NORMAL_EXTS ) ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// utility class
//---------------------------------------------------------
function _parse_ext( $file )
{
	return $this->_utility_class->parse_ext( $file );
}

// --- class end ---
}

?>