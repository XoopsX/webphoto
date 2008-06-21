<?php
// $Id: optional.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// $MY_DIRNAME WEBPHOTO_TRUST_PATH are set by caller
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// optional functions
// do not replace this file
//=========================================================
function webphoto_fct()
{
	$fct = '';
	if ( isset($_POST['fct']) ) {
		$fct = $_POST['fct'];

	} elseif ( isset($_GET['fct']) ) {
		$fct = $_GET['fct'];

	} elseif ( isset($_SERVER['PATH_INFO']) ) {
		$paths = explode( '/', $_SERVER['PATH_INFO'] );
		if ( is_array($paths) && count($paths) ) {
			foreach ( $paths as $path )
			{
				if ( $path ) {
					$fct = $path;
					break;
				}
			}
		}
	}

	$fct = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , $fct ) ;
	return $fct;
}

function webphoto_include_once( $file, $dirname=null, $debug=true )
{
	$d3_class =& webphoto_d3_optional::getInstance();
	$d3_class->init( webphoto_get_dirname( $dirname ), WEBPHOTO_TRUST_DIRNAME );
	return $d3_class->include_once_file( $file, $debug );
}

function webphoto_include_once_language( $file, $dirname=null )
{
	$d3_class =& webphoto_d3_optional::getInstance();
	$d3_class->init( webphoto_get_dirname( $dirname ), WEBPHOTO_TRUST_DIRNAME );
	return $d3_class->include_once_language( $file );
}

function webphoto_include_language( $file, $dirname=null )
{
	$d3_class =& webphoto_d3_optional::getInstance();
	$d3_class->init( webphoto_get_dirname( $dirname ), WEBPHOTO_TRUST_DIRNAME );
	return $d3_class->include_language( $file );
}

function webphoto_debug_msg( $file, $dirname=null )
{
	$d3_class =& webphoto_d3_optional::getInstance();
	$d3_class->init( webphoto_get_dirname( $dirname ), WEBPHOTO_TRUST_DIRNAME );
	return $d3_class->debug_msg_include_file( $file );
}

function webphoto_include_once_preload( $dirname=null )
{
	$preload_class =& webphoto_d3_preload::getInstance();
	$preload_class->init( webphoto_get_dirname( $dirname ), WEBPHOTO_TRUST_DIRNAME );
	return $preload_class->include_once_preload_files();
}

function webphoto_get_dirname( $dirname )
{
	if ( ! defined("WEBPHOTO_TRUST_DIRNAME") ) {
		die( 'not permit' );
	}

	if ( empty($dirname) ) {
		if ( defined("WEBPHOTO_DIRNAME") ) {
			$dirname = WEBPHOTO_DIRNAME ;
		} else {
			die( 'not permit' );
		}
	}

	return $dirname;
}

?>