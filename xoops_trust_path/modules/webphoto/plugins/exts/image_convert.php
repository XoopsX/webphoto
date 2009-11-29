<?php
// $Id: image_convert.php,v 1.2 2009/11/29 07:34:23 ohwada Exp $

//=========================================================
// webphoto module
// 2009-10-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname 
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ext_image_convert
//=========================================================
class webphoto_ext_image_convert extends webphoto_ext_base
{
	var $_imagemagick_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ext_image_convert( $dirname, $trust_dirname )
{
	$this->webphoto_ext_base( $dirname, $trust_dirname );

	$this->_imagemagick_class =& webphoto_imagemagick::getInstance( $dirname, $trust_dirname );

	$this->set_debug_by_name( 'IMAGE_CONVERT' );
}

//---------------------------------------------------------
// check ext
//---------------------------------------------------------
function is_ext( $ext )
{
	return $this->is_image_convert_ext( $ext );
}

function is_image_convert_ext( $ext )
{
	return $this->match_ext_kind( $ext, _C_WEBPHOTO_MIME_KIND_IMAGE_CONVERT );
}

//---------------------------------------------------------
// create jpeg
//---------------------------------------------------------
function create_jpeg( $param )
{
	$src_file  = $param['src_file'] ;
	$jpeg_file = $param['jpeg_file'] ;
	return $this->_imagemagick_class->create_jpeg( $src_file, $jpeg_file );
}

// --- class end ---
}

?>