<?php
// $Id: file_md5.php,v 1.1 2009/04/19 11:41:45 ohwada Exp $

//=========================================================
// webphoto module
// 2009-04-19 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_file_md5
//=========================================================
class webphoto_lib_file_md5
{
	var $_dir_class;

	var $_DIRNAME;
	var $_TRUST_DIRNAME;
	var $_MODULE_DIR;
	var $_TRUST_DIR;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_file_md5( $dirname , $trust_dirname )
{
	$this->_DIRNAME       = $dirname ;
	$this->_MODULE_DIR    = XOOPS_ROOT_PATH .'/modules/'. $dirname;
	$this->_TRUST_DIRNAME = $trust_dirname;
	$this->_TRUST_DIR     = XOOPS_TRUST_PATH .'/modules/'. $trust_dirname;

	$this->_dir_class =& webphoto_lib_dir::getInstance();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_file_md5( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// function
//---------------------------------------------------------
function create_md5( $name )
{
	$data  = '';
	$dir   = $this->build_dir( $name );

	$files = $this->_dir_class->get_files_in_deep_dir( $dir );
	foreach( $files as $file )
	{
		$file_full = $dir . $file;
		if ( is_dir($file_full) || !is_file($file_full) ) {
			continue;
		}
		$md5 = md5_file( $file_full ) ;
		$str = $file.' : '.$md5."\n";
		$data .= $str;
	}

	$this->write_file( $name, $data );
	return $data;
}

function check_md5( $name )
{
	$msg  = '';
	$dir  = $this->build_dir( $name );
	$data = $this->read_file( $name );

	$lines = $this->str_to_array( $data, "\n" );
	foreach ( $lines as $line )
	{
		list($file, $md5) = $this->str_to_array( $line, ":" );
		$file_full = $dir.trim($file);
		if ( !file_exists( $file_full) ) {
			$msg .= 'not exist : '.$file_full."<br />\n";
			continue;
		}
		if ( md5_file($file_full) != trim($md5) ) {
			$msg .= 'unmatch md5 : '.$file_full."<br />\n";
		}
	}
	return $msg;
}

function build_dir( $name )
{
	switch ( $name )
	{
		case 'trust':
			$dir = $this->_TRUST_DIR;
			break;

		case 'root':
			$dir = $this->_MODULE_DIR;
			break;
	}
	return $dir;
}

function write_file( $name, $data, $mode='w', $flag_chmod=true )
{
	$file = XOOPS_TRUST_PATH.'/tmp/'.$this->build_filename( $name );
	$fp = fopen( $file , $mode ) ;
	if ( !$fp ) { return false ; }

	$byte = fwrite( $fp , $data ) ;
	fclose( $fp ) ;

// the user can delete this file which apache made.
	if (( $byte > 0 )&& $flag_chmod ) {
		chmod( $file, 0777 );
	}

	return $byte;
}

function read_file( $name )
{
	$file = $this->_TRUST_DIR.'/include/'.$this->build_filename( $name );
	return file_get_contents( $file );
}

function build_filename( $name )
{
	$file = $this->_TRUST_DIRNAME.'_md5_'.$name.'.txt';
	return $file;
}

function str_to_array( $str, $pattern )
{
	$arr1 = explode( $pattern, $str );
	$arr2 = array();
	foreach ( $arr1 as $v )
	{
		$v = trim($v);
		if ($v == '') { continue; }
		$arr2[] = $v;
	}
	return $arr2;
}

// --- class end ---
}

?>