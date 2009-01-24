<?php
// $Id: pdf.php,v 1.2 2009/01/24 08:55:26 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_pdf
// wrapper for webphoto_lib_xpdf
//=========================================================
class webphoto_pdf
{
	var $_config_class;
	var $_multibyte_class;
	var $_xpdf_class;
	var $_imagemagick_class;

	var $_cfg_use_xpdf;
	var $_flag_chmod = true;

	var $_cached = array();

	var $_TMP_DIR;

	var $_PDF_EXT  = 'pdf';
	var $_JPEG_EXT = 'jpg';
	var $_TEXT_EXT = 'txt';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_pdf( $dirname )
{
	$this->_config_class      =& webphoto_config::getInstance( $dirname );
	$this->_xpdf_class        =& webphoto_lib_xpdf::getInstance();
	$this->_imagemagick_class =& webphoto_lib_imagemagick::getInstance();
	$this->_multibyte_class   =& webphoto_multibyte::getInstance();

	$this->_cfg_use_xpdf = $this->_config_class->get_by_name( 'use_xpdf' );
	$this->_cfg_xpdfpath = $this->_config_class->get_dir_by_name(  'xpdfpath' );
	$this->_TMP_DIR      = $this->_config_class->get_work_dir( 'tmp' );

	$this->_xpdf_class->set_cmd_path( $this->_cfg_xpdfpath );
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_pdf( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create image
//---------------------------------------------------------
function create_image( $item_id, $src_file )
{
	if ( empty($src_file) ) {
		return null;
	}
	if ( ! is_file($src_file) ) {
		return null;
	}
	if ( ! $this->_cfg_use_xpdf ) {
		return null;
	}

	if ( isset( $this->_cached[ $item_id ] ) ) {
		$created_file = $this->_cached[ $item_id ];
	} else {
		$created_file = $this->create_jpeg( $item_id, $src_file );
	}

	if ( ! is_file($created_file) ) {
		return null;
	}

	$this->_cached[ $item_id ] = $created_file ;

	$arr = array(
		'flag'      => true ,
		'item_id'   => $item_id ,
		'src_file'  => $created_file ,
		'src_ext'   => $this->_JPEG_EXT ,
		'icon_name' => $this->_PDF_EXT ,
	);
	return $arr;
}

function create_jpeg( $item_id, $pdf_file )
{
	if ( !$this->_cfg_use_xpdf ) {
		return false;
	}

	$prefix   = 'tmp_'. sprintf("%04d", $item_id );
	$root     = $this->_TMP_DIR .'/'. $prefix;
	$jpg_file = $this->_TMP_DIR .'/'. $prefix .'.'. $this->_JPEG_EXT;
	$ppm_file = $this->_xpdf_class->pdf_to_ppm( $pdf_file, $root );

	if ( !is_file($ppm_file) ) {
		return false;
	}

	$this->_imagemagick_class->convert( $ppm_file, $jpg_file );
	unlink( $ppm_file );
	if ( $this->_flag_chmod ) {
		chmod( $jpg_file, 0777 );
	}

	return $jpg_file;
}

//---------------------------------------------------------
// text content
//---------------------------------------------------------
function get_text_content( $pdf_file )
{
	if ( empty($pdf_file) ) {
		return false;
	}
	if ( ! is_file($pdf_file) ) {
		return false;
	}
	if ( !$this->_cfg_use_xpdf ) {
		return false;
	}

	$txt_file = $this->_TMP_DIR .'/'. uniqid('tmp_') .'.'. $this->_TEXT_EXT ;
	$this->pdf_to_text( $pdf_file, $txt_file );
	if ( !is_file($txt_file) ) {
		return false;
	}

	$text = file_get_contents( $txt_file );
	$text = $this->_multibyte_class->convert_from_utf8( $text );
	$text = $this->_multibyte_class->build_plane_text(  $text );

	unlink($txt_file);
	return $text;
}

function pdf_to_text( $pdf_file, $txt_file )
{
	if ( !$this->_cfg_use_xpdf ) {
		return false;
	}

	$this->_xpdf_class->pdf_to_text( $pdf_file, $txt_file );
	if ( $this->_flag_chmod && is_file($txt_file) ) {
		chmod( $txt_file, 0777 );
	}
}

// --- class end ---
}

?>