<?php
// $Id: utility.php,v 1.21 2011/12/29 03:39:29 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// BUG : logic was reverse
// 2011-05-01 K.OHWADA
// substitute_filename_to_underbar()
// 2010-11-11 K.OHWADA
// Incorrect datetime value
// 2010-10-01 K.OHWADA
// is_image_cmyk()
// 2010-01-10 K.OHWADA
// array_remove()
// 2009-10-20 K.OHWADA
// array_to_key_value()
// 2009-04-21 K.OHWADA
// chmod_file()
// 2009-04-10 K.OHWADA
// mysql_datetime_to_unixtime()
// 2009-01-10 K.OHWADA
// build_random_file_name()
// 2008-11-29 K.OHWADA
// check_file_time()
// 2008-11-08 K.OHWADA
// read_file_cvs() get_array_value_by_key()
// 2008-10-01 K.OHWADA
// undo_htmlspecialchars()
// 2008-09-20 K.OHWADA
// BUG: 12:00:52 -> 12:52
// 2008-08-24 K.OHWADA
// changed write_file()
// 2008-08-01 K.OHWADA
// added get_files_in_dir()
// 2008-07-01 K.OHWADA
// changed parse_ext()
// added build_error_msg()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_utility
//=========================================================
class webphoto_lib_utility
{
	var $_ini_safe_mode ;

	var $_MYSQL_FMT_DATE     = 'Y-m-d';
	var $_MYSQL_FMT_DATETIME = 'Y-m-d H:i:s';

	var $_HTML_SLASH = '&#047;' ;
	var $_HTML_COLON = '&#058;' ;

	var $_ASCII_LOWER_A = 97; 
	var $_ASCII_LOWER_Z = 122;

	var $_C_YES = 1;
	var $_CHMOD_MODE = 0777;

// base on style sheet of default theme
	var $_STYLE_ERROR_MSG = 'background-color: #FFCCCC; text-align: center; border-top: 1px solid #DDDDFF; border-left: 1px solid #DDDDFF; border-right: 1px solid #AAAAAA; border-bottom: 1px solid #AAAAAA; font-weight: bold; padding: 10px; ';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_utility()
{
	$this->_ini_safe_mode = ini_get('safe_mode');
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_utility();
	}
	return $instance;
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
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

function array_to_str( $arr, $glue )
{
	$val = false;
	if ( is_array($arr) && count($arr) ) {
		$val = implode($glue, $arr);
	}
	return $val;
}

function parse_ext( $file )
{
	return strtolower( substr( strrchr( $file , '.' ) , 1 ) );
}

function strip_ext( $file )
{
	return str_replace( strrchr( $file , '.' ), '', $file );
}

function parse_url_to_filename( $url )
{
	$parsed = parse_url( $url );
	if ( isset($parsed['path']) ) {
		$arr = explode( '/', $parsed['path'] );
		if ( is_array($arr) && count($arr) ) {
			return array_pop($arr) ;
		}
	}
	return null;
}

function add_slash_to_head( $str )
{
// ord : the ASCII value of the first character of string
// 0x2f slash

	if( ord( $str ) != 0x2f ) {
		$str = "/". $str;
	}
	return $str;
}

function strip_slash_from_head( $str )
{
// ord : the ASCII value of the first character of string
// 0x2f slash

	if( ord( $str ) == 0x2f ) {
		$str = substr($str, 1);
	}
	return $str;
}

function add_separator_to_tail( $str )
{
// Check the path to binaries of imaging packages
// DIRECTORY_SEPARATOR is defined by PHP

	if( trim( $str ) != '' && substr( $str , -1 ) != DIRECTORY_SEPARATOR ) {
		$str .= DIRECTORY_SEPARATOR ;
	}
	return $str;
}

function strip_slash_from_tail( $str )
{
	if ( substr($str, -1, 1) == '/' ) {
		$str = substr($str, 0, -1);
	}
	return $str;
}

// Checks if string is started from HTTP
function check_http_start( $str )
{
	if ( preg_match("|^https?://|", $str) ) {
		return true;	// include HTTP
	}
	return false;
}

// Checks if string is HTTP only
function check_http_only( $str )
{
	if ( ($str == 'http://') || ($str == 'https://') ) {
		return true;	// http only
	}
	return false;
}

function check_http_null( $str )
{
	if ( ($str == '') || ($str == 'http://') || ($str == 'https://') ) {
		return true;
	}
	return false;
}

function check_http_fill( $str )
{
	$ret = ! $this->check_http_null( $str );
	return $ret;
}

function get_array_value_by_key( $array, $key, $default=null )
{
	if ( isset( $array[ $key ] ) ) {
		return  $array[ $key ] ;
	}
	return $default ;
}

function array_merge_unique( $arr1, $arr2, $key_name=null )
{
	if ( $key_name ) {
		return $this->array_merge_unique_1( $arr1, $arr2, $key_name );
	} else {
		return $this->array_merge_unique_2( $arr1, $arr2 );
	}
}

function array_merge_unique_1( $arr1, $arr2, $key_name )
{
	$arr_ret = null;
	if ( is_array($arr1) && count($arr1)  ) {
		$arr_ret = $this->array_to_key_value( $arr1, $key_name );

		if ( is_array($arr2) && count($arr2) ) {
			foreach ( $arr2 as $a ) 
			{
				$key_val = $a[ $key_name ] ;
				if ( ! isset( $arr_ret[ $key_val ] ) ) {
					$arr_ret[ $key_val ] = $a ;
				}
			}
		}

	} elseif ( is_array($arr2) && count($arr2) ) {
		$arr_ret = $this->array_to_key_value( $arr2, $key_name );
	}

	return $arr_ret;
}

function array_to_key_value( $arr, $key_name )
{
	$arr_ret = null;

// BUG : logic was reverse
	if ( !is_array($arr) || !count($arr) ) {
		return $arr_ret;
	}

	$arr_ret = array();
	foreach ( $arr as $a ) 
	{
		$key_val = $a[ $key_name ] ;
		$arr_ret[ $key_val ] = $a ;
	}
	return $arr_ret;
}

function array_merge_unique_2( $arr1, $arr2 )
{
	if ( !is_array($arr1) || !count($arr1) ) {
		if ( is_array($arr2) ) {
			return $arr2;
		}
	}

	if ( !is_array($arr2) || !count($arr2) ) {
		if ( is_array($arr1) ) {
			return $arr1;
		}
	}

	return array_unique( array_merge( $arr1 , $arr2 ) );
}

function array_remove( $arr1, $arr2 )
{
	if ( !is_array($arr1) || !count($arr1) ) {
		return $arr1;
	}
	if ( !is_array($arr2) || !count($arr2) ) {
		return $arr1;
	}

	$arr = array();
	foreach ( $arr1 as $a ) {
		if ( !in_array( $a, $arr2 ) ) {
			$arr[] = $a;
		}
	}
	return $arr;
}

//---------------------------------------------------------
// format
//---------------------------------------------------------
function format_filesize( $size, $precision=2 ) 
{
	$format = '%.'. intval($precision) .'f';
	$bytes  = array('B','KB','MB','GB','TB');
	foreach ( $bytes as $unit ) 
	{
		if ( $size > 1000 ) {
			$size = $size / 1024;
		} else {
			break;
		}
	}
	$str = sprintf( $format, $size ).' '.$unit;
	return $str;
}

function format_time( $time, $str_hour, $str_min, $str_sec, $flag_zero=false ) 
{
	return $this->build_time( $this->parse_time( $time ), 
		$str_hour, $str_min, $str_sec, $flag_zero ) ;
}

function build_time( $time_array, $str_hour, $str_min, $str_sec, $flag_zero=false ) 
{
	list( $hour, $min, $sec ) = $time_array ;

	$str = null;
	if ( $hour > 0 ) {
		$str = "$hour $str_hour $min $str_min $sec $str_sec";
	} elseif ( $min > 0 ) {
		$str = "$min $str_min $sec $str_sec";
	} elseif (( $sec > 0 ) || $flag_zero ) {
		$str = "$sec $str_sec";
	}
	return $str;
}

function parse_time( $time ) 
{
	$hour = intval( $time / 3600 ) ;
	$min  = intval(( $time - 3600 * $hour ) / 60 ) ;
	$sec  = $time - 3600 * $hour - 60 * $min ;
	return array( $hour, $min, $sec );
}

//---------------------------------------------------------
// file name
//---------------------------------------------------------
function build_random_file_name( $id, $ext, $extra=null )
{
	$str  = $this->build_random_file_node( $id, $extra );
	$str .= '.'.$ext;
	return $str;
}

function build_random_file_node( $id, $extra=null )
{
	$alphabet = $this->build_random_alphabet();
	$str  = $alphabet;
	$str .= $this->build_format_id( $id );
	if ( $extra ) {
		$str .= $extra;
	}
	$str .= uniqid( $alphabet );
	return $str;
}

function build_random_alphabet()
{
// one lower alphabet ( a - z )
	$str = chr( rand( $this->_ASCII_LOWER_A, $this->_ASCII_LOWER_Z ) );
	return $str;
}

function build_format_id( $id, $format='%05d' )
{
	return sprintf( $format, $id );
}

//---------------------------------------------------------
// file
//---------------------------------------------------------
function unlink_file( $file )
{
	if ( $this->check_file( $file ) ) {
		return unlink( $file );
	}
	return false;
}

function copy_file( $src, $dst, $flag_chmod=false )
{
	if ( $this->check_file( $src ) ) {
		$ret = copy( $src, $dst );

// the user can delete this file which apache made.
		if ( $ret && $flag_chmod ) {
			$this->chmod_file( $dst, $this->_CHMOD_MODE );
		}

		return $ret;
	}
	return false;
}

function rename_file( $old, $new )
{
	if ( $this->check_file( $old ) ) {
		return rename( $old, $new );
	}
	return false;
}

function check_file( $file )
{
	if ( $file && file_exists($file) && is_file($file) && !is_dir($file) ) {
		return true;
	}
	return false;
}

function read_file( $file, $mode='r' )
{
	$fp = fopen( $file , $mode ) ;
	if ( !$fp ) { return false ; }

	$date = fread( $fp , filesize( $file ) );
	fclose( $fp ) ;
	return $date;
}

function read_file_cvs( $file, $mode='r' )
{
	$lines = array();

	$fp = fopen( $file , $mode ) ;
	if ( !$fp ) { return false ; }

	while (!feof($fp) ) {        
		$lines[] = fgetcsv($fp, 1024);   
	}

	fclose($fp);
	return $lines;
}

function write_file( $file, $data, $mode='w', $flag_chmod=false )
{
	$fp = fopen( $file , $mode ) ;
	if ( !$fp ) { return false ; }

	$byte = fwrite( $fp , $data ) ;
	fclose( $fp ) ;

// the user can delete this file which apache made.
	if (( $byte > 0 )&& $flag_chmod ) {
		$this->chmod_file( $file, $this->_CHMOD_MODE );
	}

	return $byte;
}

function check_file_time( $file, $interval )
{
// if passing interval time
	if ( file_exists( $file ) ) {
		$time = intval( trim( file_get_contents( $file ) ) );
		if ( ( $time > 0 ) && 
		     ( time() > ( $time + $interval ) ) ) {
			return true;
		}

// if not exists file ( at first time )
	} else {
		return true;
	}

	return false;
}

function renew_file_time( $file, $chmod )
{
	$this->write_file( $file, time(), 'w', $chmod );
}

function chmod_file( $file, $mode )
{
	if ( ! $this->_ini_safe_mode ) {
		chmod( $file, $mode );
	}
}

//---------------------------------------------------------
// dir
//---------------------------------------------------------
function get_files_in_dir( $path, $ext=null, $flag_dir=false, $flag_sort=false, $id_as_key=false  )
{
	$arr = array();

	$lists = $this->get_lists_in_dir( $path );
	if ( !is_array($lists) ) {
		return false;
	}

	$pattern = "/\.". preg_quote($ext) ."$/";

	foreach ( $lists as $list ) 
	{
		$path_list = $path .'/'. $list;

// check is file
		if ( is_dir($path_list) || !is_file($path_list) ) {
			continue;
		}

// check ext
		if ( $ext && !preg_match($pattern, $list) ) {
			continue;
		}

		$list_out = $list;
		if ( $flag_dir ) {
			$list_out = $path_list;
		}
		if ( $id_as_key ) {
			$arr[ $list ] = $list_out;
		} else {
			$arr[] = $list_out;
		}
	}

	if ( $flag_sort ) {
		asort($arr);
		reset($arr);
	}

	return $arr;
}

function get_dirs_in_dir( $path, $flag_dir=false, $flag_sort=false, $id_as_key=false  )
{
	$arr = array();

	$lists = $this->get_lists_in_dir( $path );
	if ( !is_array($lists) ) {
		return false;
	}

	foreach ( $lists as $list ) 
	{
		$path_list = $path .'/'. $list;

// check is dir
		if ( !is_dir($path_list) ) {
			continue;
		}

// myself
		if ( $list == '.' ) {
			continue;
		}

// parent
		if ( $list == '..' ) {
			continue;
		}

		$list_out = $list;
		if ( $flag_dir ) {
			$list_out = $path_list;
		}
		if ( $id_as_key ) {
			$arr[ $list ] = $list_out;
		} else {
			$arr[] = $list_out;
		}
	}

	if ( $flag_sort ) {
		asort($arr);
		reset($arr);
	}

	return $arr;
}

function get_lists_in_dir( $path )
{
	$arr = array();

	$path = $this->strip_slash_from_tail( $path );

// check is dir
	if ( !is_dir($path) ) {
		return false;
	}

// open
	$dh = opendir($path);
	if ( !$dh ) {
		return false;
	}

// read
	while ( false !== ($list = readdir( $dh )) ) 
	{
		$arr[] = $list;
	}

// close
	closedir( $dh );

	return $arr;
}

//---------------------------------------------------------
// image
//---------------------------------------------------------
function adjust_image_size( $width, $height, $max_width, $max_height )
{
	if ( $width > $max_width ) {
		$mag    = $max_width / $width;
		$width  = $max_width;
		$height = $height * $mag;
	}

	if ( $height > $max_height ) {
		$mag    = $max_height / $height;
		$height = $max_height;
		$width  = $width * $mag;
	}

	return array( intval($width), intval($height) );
}

function is_image_cmyk( $file )
{
	$size = getimagesize($file);
	if ( isset($size['channels']) && 
       ( $size['channels'] == 4 )) {
    	return true;
	} 
	return false;
}

//---------------------------------------------------------
// encode
//---------------------------------------------------------
function encode_slash( $str )
{
	return str_replace( '/', $this->_HTML_SLASH, $str );
}

function encode_colon( $str )
{
	return str_replace( ':', $this->_HTML_COLON, $str );
}

function decode_slash( $str )
{
	return str_replace( $this->_HTML_SLASH, '/', $str );
}

function decode_colon( $str )
{
	return str_replace( $this->_HTML_COLON, ':', $str );
}

//---------------------------------------------------------
// file name
//---------------------------------------------------------
function substitute_filename_to_underbar( $name, $char='_' )
{
// substitute the characters that cannot be used as the file name to underbar. 
// \ / : * ? " < > | sapce
	$search  = array('\\','/',':','*','?','"','<','>','|',' ');

	$replace = array();
	for ( $i=0; $i<10; $i++ ){
		$replace[] = $char;
	}

	$str = str_replace( $search, $replace, $name );
	return $str;
}

function build_filename_rfc2231( $name, $charset, $langcode )
{
	$str  = strtolower( $charset."'". $langcode ."'" );
	$str .= rawurlencode($name);
	return $str;
}

//---------------------------------------------------------
// group perms
//---------------------------------------------------------
function convert_group_perms_array_to_str( $perms, $glue='&' )
{
	$arr = $this->arrenge_group_perms_array( $perms );
	return $this->array_to_perm( $arr, $glue );
}

function arrenge_group_perms_array( $perms )
{
	if ( !is_array($perms) || !count($perms) ) {
		return null ;
	}

	$arr = array();
	foreach( $perms as $k => $v ) {
		if ( $v == $this->_C_YES ) {
			$arr[] = intval($k) ;
		}
	}

	return $arr ;
}

function array_to_perm( $arr, $glue )
{
	$val = $this->array_to_str( $arr, $glue );
	if ( $val ) {
		$val = $glue . $val . $glue ;
	}
	return $val;
}

//---------------------------------------------------------
// time
//---------------------------------------------------------
function str_to_time( $str )
{
	$str = trim( $str );
	if ( $str ) {
		$time = strtotime( $str );
		if ( $time > 0 ) {
			return $time;
		}
		return -1;	// failed to convert
	}
	return 0;
}

//---------------------------------------------------------
// footer
//---------------------------------------------------------
function build_execution_time( $time_start=0 )
{
	$str  = 'execution time : ';
	$str .= $this->get_execution_time( $time_start );
	$str .= ' sec'."<br />\n";
	return $str;
}

function build_memory_usage()
{
	$usage = $this->get_memory_usage();
	if ( $usage ) {
		$str  = 'memory usage : '.$usage.' MB'."<br />\n";
		return $str;
	}
	return null;
}

function get_execution_time( $time_start=0 )
{
	list($usec, $sec) = explode(" ",microtime()); 
	$time = floatval($sec) + floatval($usec) - $time_start; 
	$exec = sprintf("%6.3f", $time);
	return $exec;
}

function get_memory_usage()
{
	if ( function_exists('memory_get_usage') ) {
		$usage = sprintf("%6.3f",  memory_get_usage() / 1000000 );
		return $usage;
	}
	return null;
}

function get_happy_linux_url( $is_japanese=false )
{
	if ( $is_japanese ) {
		return 'http://linux.ohwada.jp/';
	}
	return 'http://linux2.ohwada.net/';
}

function get_powered_by()
{
	$str  = '<div align="right">';
	$str .= '<a href="http://linux2.ohwada.net/" target="_blank">';
	$str .= '<span style="font-size : 80%;">Powered by Happy Linux</span>';
	$str .= "</a></div>\n";
	return $str;
}

//---------------------------------------------------------
// base on core's xoops_error
// XLC do not support 'errorMsg' style class in admin cp
//---------------------------------------------------------
function build_error_msg( $msg, $title='', $flag_sanitize=true )
{
	$str = '<div style="'. $this->_STYLE_ERROR_MSG .'">';
	if ($title != '') {
		if ( $flag_sanitize ) {
			$title = $this->sanitize($title);
		}
		$str .= "<h4>".$title."</h4>\n";
	}
	if (is_array($msg)) {
		foreach ($msg as $m) {
			if ( $flag_sanitize ) {
				$m = $this->sanitize($msg);
			}
			$str .= $m."<br />\n";
		}
	} else {
		if ( $flag_sanitize ) {
			$msg = $this->sanitize($msg);
		}
		$str .= $msg;
	}
	$str .= "</div>\n";
	return $str;
}

//---------------------------------------------------------
// sanitize
//---------------------------------------------------------
function sanitize( $str )
{
	return htmlspecialchars( $str, ENT_QUOTES );
}

// --------------------------------------------------------
// Invert special characters from HTML entities
//   &amp;   =>  &
//   &lt;    =>  <
//   &gt;    =>  >
//   &quot;  =>  "
//   &#39;   =>  '
//   &#039;  =>  '
//   &apos;  =>  ' (xml format)
// --------------------------------------------------------
function undo_htmlspecialchars( $str )
{
	$arr = array(
		'&amp;'  =>  '&',
		'&lt;'   =>  '<',
		'&gt;'   =>  '>',
		'&quot;' =>  '"',
		'&#39;'  =>  "'",
		'&#039;' =>  "'",
		'&apos;' =>  "'",
	);
	return strtr( $str, $arr );
}

// --- class end ---
}

?>