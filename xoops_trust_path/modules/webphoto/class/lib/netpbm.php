<?php
// $Id: netpbm.php,v 1.3 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// version()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_netpbm
//=========================================================

class webphoto_lib_netpbm
{
	var $_cmd_path = null;
	var $_DEBUG    = false ;

	var $_NETPBM_PIPES = array(
		 "jpegtopnm" , "giftopnm" , "pngtopnm" , 
		 "pnmtojpeg" , "pnmtopng" , "ppmquant" , 
		 "ppmtogif"  , "pnmscale" , "pnmflip" ) ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_netpbm()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_netpbm();
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function set_cmd_path( $val )
{
	$this->_cmd_path = $val ;
}

function resize_rotate( $src, $dst, $max_width=0, $max_height=0, $rotate=0 )
{
	$image_size = getimagesize( $src ) ;
	if ( ! is_array($image_size) ) {
		return false;
	}

	$type = $image_size[2] ;

	list( $cmd_in, $cmd_out ) = 
		$this->build_cmd_in_out( $type );

	if( empty($cmd_in) || empty($cmd_out) ) {
		return false;
	}

	$cmd_resize = null ;
	$cmd_rotate = null ;

	if (( $max_width > 0 )&&( $max_height > 0 )) {
		$cmd_resize = $this->build_cmd_resize( $max_width, $max_height ) ;
	}

	if ( $rotate > 0 ) {
		$cmd_rotate = $this->build_cmd_rotate( 360 - $rotate );
	}

	// Do Modify and check success
	if ( $cmd_resize && $cmd_rotate ) {
		$cmd_resize_rotate = $cmd_resize .' | '. $cmd_rotate;
	} elseif ( $cmd_resize ) {
		$cmd_resize_rotate = $cmd_resize;
	} elseif ( $cmd_rotate ) {
		$cmd_resize_rotate = $cmd_rotate;
	} else {
		return false;
	}

	$cmd = $cmd_in .' < '. $src .' | '. $cmd_resize_rotate .' | '. $cmd_out .' > '. $dst;
	exec( $cmd ) ;
	if ( $this->_DEBUG ) {
		echo $cmd."<br />\n";
	}

	return true;
}

function build_cmd_in_out( $type )
{
	$cmd_in  = null ;
	$cmd_out = null ;

	switch( $type ) 
	{
	// GIF	
		case 1 :
			$cmd_in   = $this->_cmd_path .'giftopnm';
			$cmd_out  = $this->_cmd_path .'ppmquant 256 | ';
			$cmd_out .= $this->_cmd_path .'ppmtogif';
			break ;

	// JPEG
		case 2 :
			$cmd_in  = $this->_cmd_path. 'jpegtopnm';
			$cmd_out = $this->_cmd_path. 'pnmtojpeg';
			break ;

	// PNG
		case 3 :
			$cmd_in  = $this->_cmd_path .'pngtopnm';
			$cmd_out = $this->_cmd_path .'pnmtopng';
			break ;

		default :
			break;
	}

	return array( $cmd_in, $cmd_out ) ;
}

function build_cmd_resize( $max_width, $max_height )
{
	$cmd = $this->_cmd_path .'pnmscale -xysize '. $max_width .' '. $max_height ;
	return $cmd;
}

function build_cmd_rotate( $angle )
{
	$cmd = $this->_cmd_path .'pnmflip -r'. $angle  .' ';
	return $cmd;
}

//---------------------------------------------------------
// version
//---------------------------------------------------------
function version( $path )
{
	$arr = array();
	foreach( $this->_NETPBM_PIPES as $pipe ) 
	{
		$ret_array = array() ;
		exec( "{$path}$pipe --version 2>&1" , $ret_array ) ;
		if( count( $ret_array ) > 0 ) {
			$ret = true;
			$str = $ret_array[0] ;

		} else {
			$ret = false;
			$str = "Error: {$path}$pipe can't be executed" ;
		}
		$arr[] = array( $ret, $str );
	}

	return $arr ;
}

// --- class end ---
}

?>