<?php
// $Id: upload.php,v 1.10 2010/10/08 15:53:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// remove $this->unlink_file( $this->_uploader_class->getSavedDestination() )
// 2010-06-06 K.OHWADA
// upload_allowed_mimes
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_mime
// 2009-05-05 K.OHWADA
// Notice [PHP]: Undefined index: file_photo
// 2008-11-16 K.OHWADA
// clear_errors()
// 2008-11-08 K.OHWADA
// webphoto_lib_uploader::getInstance()
// 2008-10-01 K.OHWADA
// init_media_uploader_full()
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
	var $_mime_class;
	var $_uploader_class;

	var $_max_filesize = 0 ;
	var $_max_width    = 0 ;
	var $_max_height   = 0 ;

	var $_ini_allowed_mimes = true;

	var $_uploader_media_name = null;
	var $_uploader_media_type = null;
	var $_uploader_file_name  = null;
	var $_tmp_name            = null;

	var $_PHP_UPLOAD_ERRORS = array();
	var $_UPLOADER_ERRORS   = array();

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_upload( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_mime_class =& webphoto_mime::getInstance( 
		$dirname , $trust_dirname );

	$this->_uploader_class =& webphoto_lib_uploader::getInstance() ;
	$this->_uploader_class->setPrefix( _C_WEBPHOTO_UPLOADER_PREFIX ) ;
	$this->_uploader_class->setUploadDir( $this->_TMP_DIR );

	$this->_ini_allowed_mimes = $this->get_ini( 'upload_allowed_mimes' );

	$this->set_max_filesize( $this->get_config_by_name( 'fsize' )  ) ;

	$this->_init_errors();
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
function fetch_media( $field, $flag_allow_all )
{
	$this->_tmp_name = null;
	$this->clear_errors() ;

	if ( $flag_allow_all ) {
		list ( $allowed_mimes, $allowed_exts ) = 
			$this->_mime_class->get_my_allowed_mimes();

	} else {
		$allowed_mimes = $this->_mime_class->get_image_mimes();
		$allowed_exts  = $this->_mime_class->get_image_exts();
	}

	$this->_uploader_class->setAllowedExtensions( $allowed_exts );
	$this->_uploader_class->setMaxFileSize( $this->_max_filesize );

	if ( $this->_ini_allowed_mimes ) {
		$this->_uploader_class->setAllowedMimeTypes(  $allowed_mimes );
	}

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

function fetch_image( $field )
{
	$this->_tmp_name = null;
	$this->clear_errors() ;

	$allowed_mimes = $this->_mime_class->get_image_mimes();
	$allowed_exts  = $this->_mime_class->get_image_exts();

	$this->_uploader_class->setAllowedExtensions( $allowed_exts );
	$this->_uploader_class->setMaxFileSize( $this->_max_filesize );

	if ( $this->_ini_allowed_mimes ) {
		$this->_uploader_class->setAllowedMimeTypes(  $allowed_mimes );
	}

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

function get_tmp_name()
{
	return $this->_tmp_name;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_max_filesize( $val )
{
	$this->_max_filesize = intval($val);
}

//---------------------------------------------------------
// uploader class
//---------------------------------------------------------
function uploader_fetch( $media_name, $index=null )
{
// http://www.php.net/manual/en/features.file-upload.errors.php
// UPLOAD_ERR_NO_FILE = 4

	$this->_uploader_media_name = null ;
	$this->_uploader_media_type = null ;
	$this->_uploader_file_name  = null ;

	$ret1 = $this->_uploader_class->fetchMedia( $media_name, $index );
	if ( !$ret1 ) {
		$error_num = $this->_uploader_class->getMediaError();
		if ( $error_num == UPLOAD_ERR_NO_FILE ) {
			return 0;	// no action
		}

		$this->build_uploader_errors();
		return _C_WEBPHOTO_ERR_UPLOAD;
	}

	$ret2 = $this->_uploader_class->upload();
	if ( !$ret2 ) {
		$this->build_uploader_errors();
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
// Notice [PHP]: Undefined index: file_photo
	if ( isset( $_FILES[ $field ]['tmp_name'] ) ) {
		return is_readable( $_FILES[ $field ]['tmp_name'] );
	}
	return false;
}

function is_readable_in_tmp_dir( $name )
{
	$file = $this->_TMP_DIR .'/' . $name;
	if ( $name && file_exists( $file ) && is_readable( $file ) ) {
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
	$codes = array_unique( $this->_uploader_class->getErrorCodes() );
	foreach ( $codes as $code ) {
		$this->build_uploader_error_single( $code );
	}
}

function build_uploader_error_single( $code )
{
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
			$err1 .= ' > '. $this->_max_filesize ;
			break;

		case 12:
			$err1 .= ' : '.$this->_uploader_class->getMediaWidth();
			$err1 .= ' > '. $this->_max_width ;
			break;

		case 13:
			$err1 .= ' : '.$this->_uploader_class->getMediaHeight();
			$err1 .= ' > '. $this->_max_height ;
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
	$err_2 = sprintf( $this->get_constant('PHP_UPLOAD_ERR_FORM_SIZE'), 
		$this->_utility_class->format_filesize( $this->_max_filesize ) );

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

// --- class end ---
}

?>