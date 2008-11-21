<?php
// $Id: server_info.php,v 1.1 2008/11/21 07:56:57 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//=========================================================
// class webphoto_lib_server_info
//=========================================================
class webphoto_lib_server_info
{
	var $_NETPBM_PIPES = array(
		 "jpegtopnm" , "giftopnm" , "pngtopnm" , 
		 "pnmtojpeg" , "pnmtopng" , "ppmquant" , 
		 "ppmtogif"  , "pnmscale" , "pnmflip" ) ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_server_info()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_server_info();
	}
	return $instance;
}

//---------------------------------------------------------
// server info
//---------------------------------------------------------
function build_server()
{
	$str  = "OS: ". php_uname() ."<br />\n"; 
	$str .= "PHP: ". PHP_VERSION ."<br />\n"; 
	$str .= "MySQL: ". mysql_get_server_info() ."<br />\n"; 
	$str .= "XOOPS: ". XOOPS_VERSION ."<br />\n"; 
	return $str;
}

function build_php_secure( $dsc )
{
	$str  = $this->build_ini_on_off( 'register_globals' ) . $dsc ."<br />\n";
	$str .= $this->build_ini_on_off( 'allow_url_fopen' )  . $dsc ."<br />\n";
	return $str;
}

function build_php_etc()
{
	$str  = "error_reporting: ". error_reporting() ."<br />\n";
	$str .= $this->build_ini_int( 'display_errors' ) ."<br />\n";
	$str .= $this->build_ini_int( 'memory_limit' ) ."<br />\n";
	$str .= "magic_quotes_gpc: ". intval( get_magic_quotes_gpc() ) ."<br />\n";
	$str .= $this->build_ini_int( 'safe_mode' ) ."<br />\n";
	$str .= $this->build_ini_val( 'open_basedir' ) ."<br />\n";
	return $str;
}

function build_php_iconv()
{
	$str = "iconv extention: ". $this->build_func_load( 'iconv' ) ."<br />\n" ;
	return $str;
}

function build_php_exif()
{
	$str = "exif extention: ". $this->build_func_load( 'exif_read_data' ) ."<br />\n" ;
	return $str;
}

function build_php_mbstring()
{
	$str = '' ;
	if ( function_exists('mb_internal_encoding') ) {
		$str .= "mbstring.language: ". mb_language() ."<br />\n";
		$str .= "mbstring.detect_order: ". implode (' ', mb_detect_order() ) ."<br />\n";
		$str .= $this->build_ini_val( 'mbstring.http_input' ) ."<br />\n";
		$str .= "mbstring.http_output: ". mb_http_output() ."<br />\n";
		$str .= "mbstring.internal_encoding: ". mb_internal_encoding() ."<br />\n";
		$str .= $this->build_ini_val( 'mbstring.script_encoding' ) ."<br />\n";
		$str .= $this->build_ini_val( 'mbstring.substitute_character' ) ."<br />\n";
		$str .= $this->build_ini_val( 'mbstring.func_overload' ) ."<br />\n";
		$str .= $this->build_ini_int( 'mbstring.encoding_translation' ) ."<br />\n";
		$str .= $this->build_ini_int( 'mbstring.strict_encoding' ) ."<br />\n";

	} else {
		$str .= $this->font_red( 'mbstring: not loaded' ) ."<br />\n";
	}

	return $str;
}

function build_php_upload( $dsc=null )
{
	$str  = $this->build_ini_on_off( 'file_uploads' ) . $dsc . "<br />\n";
	$str .= $this->build_ini_val( 'upload_max_filesize' ) ."<br />\n";
	$str .= $this->build_ini_val( 'post_max_size' ) ."<br />\n";
	$str .= $this->build_php_upload_tmp_dir();
	return $str;
}

function build_php_upload_tmp_dir()
{
	$upload_tmp_dir = ini_get('upload_tmp_dir') ;

	$str = "upload_tmp_dir : ". $upload_tmp_dir ."<br />\n" ;

	$tmp_dirs = explode( PATH_SEPARATOR , $upload_tmp_dir ) ;
	foreach( $tmp_dirs as $dir ) 
	{
		if( $dir != "" && ( ! is_writable( $dir ) || ! is_readable( $dir ) ) ) {
			$msg = "Error: upload_tmp_dir ($dir) is not writable nor readable ." ;
			$str .= $this->font_red( $msg ) ."<br />\n";
		}
	}
	return $str;
}

function build_ini_int( $key )
{
	$str = $key .': '. intval( ini_get( $key ) ) ;
	return $str;
}

function build_ini_val( $key )
{
	$str = $key .': '. ini_get( $key ) ;
	return $str;
}

function build_ini_on_off( $key )
{
	$str = $key .': '. $this->build_on_off( ini_get( $key ) );
	return $str;
}

function build_func_load( $func )
{
	if ( function_exists( $func ) ) {
		$str = 'loaded';
	} else {
		$str = $this->font_red( 'not loaded' );
	}
	return $str;
}

//---------------------------------------------------------
// program version
//---------------------------------------------------------
function build_php_gd_version()
{
	$ret = false;

	$str = "<b>GD</b><br />\n";
	if ( function_exists( 'gd_info' ) ) {
		$ret = true ;
		$gd_info = gd_info() ;
		$str .= 'GD Version: '. $gd_info['GD Version'] ."<br />\n" ;

	} else {
		$str .= $this->font_red( 'not loaded' ) ."<br />\n";
	}

	return array( $ret, $str );
}

function build_imagemagick_version( $path )
{
	$ret = false;
	$ret_array = array() ;

	$str  = "<b>ImageMagick</b><br />\n";
	$str .= "Path: ". $path ."<br />\n" ;

	exec( "{$path}convert --help" , $ret_array ) ;
	if( count( $ret_array ) > 0 ) {
		$ret = true ;
		$str .= $ret_array[0]. "<br />\n";

	} else {
		$msg  = "Error: {$path}convert can't be executed" ;
		$str .= $this->font_red( $msg ). "<br />\n";
	}

	return array( $ret, $str );
}

function build_netpbm_version( $path )
{
	$str  = "<b>NetPBM</b><br />\n";
	$str .= "Path: ". $path ."<br />\n" ;

	foreach( $this->_NETPBM_PIPES as $pipe ) 
	{
		$ret_array = array() ;
		exec( "{$path}$pipe --version 2>&1" , $ret_array ) ;
		if( count( $ret_array ) > 0 ) {
			$str .= $ret_array[0]. "<br />\n";

		} else {
			$msg = "Error: {$path}$pipe can't be executed" ;
			$str .= $this->font_red( $msg ). "<br />\n";
		}
	}

	return $str;
}

function build_ffmpeg_version( $path )
{
	$ret = false;
	$ret_array = array() ;

	$str  = "<b>FFmpeg</b><br />\n";
	$str .= "Path: $path <br />\n" ;

	exec( "{$path}ffmpeg -version 2>&1" , $ret_array ) ;
	if ( is_array($ret_array) && count($ret_array) ) {
		foreach ( $ret_array as $line ) {
			if ( preg_match('/version/i', $line ) ) {
				$str .= $line ."<br />\n";
				$ret  = true;
			}
		}
	}

	if ( !$ret ) {
		$msg  = "Error: {$path}ffmpeg can't be executed" ;
		$str .= $this->font_red( $msg ) ."<br />\n";
	}

	return array( $ret, $str );
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function build_on_off( $val, $flag_red=false )
{
	$str = '';
	if ( $val ) {
		$str = $this->font_green('on');
	} elseif ( $flag_red ) { 
		$str = $this->font_red('off');
	} else { 
		$str = $this->font_green('off');
	}
	return $str;
}

function font_red( $str )
{
	$str = '<font color="#FF0000"><b>'. $str .'</b></font>' ;
	return $str;
}

function font_green( $str )
{
	$str = '<font color="#00FF00"><b>'. $str .'</b></font>' ;
	return $str;
}

// --- class end ---
}

?>