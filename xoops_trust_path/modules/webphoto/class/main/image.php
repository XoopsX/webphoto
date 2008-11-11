<?php
// $Id: image.php,v 1.2 2008/11/11 06:53:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-11-08 K.OHWADA
// tmpdir -> workdir
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_image
//=========================================================
class webphoto_main_image
{
	var $_config_class;
	var $_post_class;

	var $_DIRNAME;
	var $_TRUST_DIRNAME;
	var $_TMP_DIR;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_image( $dirname, $trust_dirname )
{
	$this->_DIRNAME       = $dirname;
	$this->_TRUST_DIRNAME = $trust_dirname;

	$this->_config_class =& webphoto_config::getInstance( $dirname );
	$this->_post_class   =& webphoto_lib_post::getInstance();

	$work_dir        = $this->_config_class->get_by_name( 'workdir' );
	$this->_TMP_DIR  = $work_dir.'/tmp' ;
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_image( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function main()
{
	$name = $this->_post_class->get_get_text('name');
	$file = $this->_TMP_DIR .'/'. $name ;

	if ( empty($name) || !is_file($file) ) {
		exit();
	}

	$image_size = GetImageSize( $file ) ;
	if ( !is_array($image_size) ) {
		exit();
	}
	$mime = $image_size['mime'];

	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Cache-Control: no-store, no-cache, max-age=1, s-maxage=1, must-revalidate, post-check=0, pre-check=0');
	header('Content-type: '.$mime);

	readfile( $file ) ;

}

// --- class end ---
}

?>