<?php
// $Id: jodconverter.php,v 1.3 2009/02/01 13:49:52 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_jodconverter
// wrapper for webphoto_lib_jodconverter
//=========================================================
class webphoto_jodconverter extends webphoto_lib_error
{
	var $_config_class;
	var $_jod_class;
	var $_multibyte_class;
	var $_utility_class;

	var $_use_jod    = false;
	var $_java_path  = '';
	var $_flag_chmod = true;
	var $_junk_words = null;

	var $_TMP_DIR;
	var $_TEXT_EXT = 'txt';
	var $_HTML_EXT = 'html';

	var $_JUNK_WORDS_ENG = array(
		'Slide', 'First page', 'Last page', 'Back', 'Continue', 'Graphics', 'Text', 
		'Overview', 'Sheet'
	); 

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_jodconverter( $dirname )
{
	$this->webphoto_lib_error();

	$this->_config_class    =& webphoto_config::getInstance( $dirname );
	$this->_jod_class       =& webphoto_lib_jodconverter::getInstance();
	$this->_multibyte_class =& webphoto_multibyte::getInstance();
	$this->_utility_class   =& webphoto_lib_utility::getInstance();

	$this->_TMP_DIR = $this->_config_class->get_work_dir( 'tmp' );

// set param
	if ( defined("_C_WEBPHOTO_JAVA_PATH" ) ) {
		$this->_java_path = _C_WEBPHOTO_JAVA_PATH ;
		$this->_jod_class->set_cmd_path_java( _C_WEBPHOTO_JAVA_PATH );
	}
	if ( defined("_C_WEBPHOTO_JODCONVERTER_JAR" ) ) {
		$this->_jod_class->set_jodconverter_jar( _C_WEBPHOTO_JODCONVERTER_JAR );
		$this->_use_jod = true;
	}

// junk words
	$arr = $this->str_to_array( _WEBPHOTO_JODCONVERTER_JUNK_WORDS, '|' );
	if ( is_array($arr) ) {
		$this->_junk_words = array_merge( $this->_JUNK_WORDS_ENG, $arr );
	} else {
		$this->_junk_words = $this->_JUNK_WORDS_ENG ;
	}
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_jodconverter( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create pdf
//---------------------------------------------------------
function create_pdf( $src_file, $dst_file )
{
	return $this->convert_single( $src_file, $dst_file );
}

//---------------------------------------------------------
// create swf
//---------------------------------------------------------
function create_swf( $src_file, $dst_file )
{
	return $this->convert_single( $src_file, $dst_file );
}

//---------------------------------------------------------
// text content
//---------------------------------------------------------
function get_text_content_for_doc( $src_file )
{
	if ( empty($src_file) ) {
		return null ;	// no action
	}
	if ( ! is_file($src_file) ) {
		return null;	// no action
	}
	if ( !$this->_use_jod ) {
		return null ;	// no action
	}

	$txt_file = $this->_TMP_DIR .'/'. uniqid('tmp_') .'.'. $this->_TEXT_EXT ;
	$ret = $this->convert_single( $src_file, $txt_file );
	if ( !$ret ) {
		$arr = array(
			'flag'   => false ,
			'errors' => $this->get_errors(),
		);
		return $arr;
	}

	$text = file_get_contents( $txt_file );
	$text = $this->_multibyte_class->convert_from_utf8( $text );
	$text = $this->_multibyte_class->build_plane_text(  $text );

	unlink($txt_file);

	$arr = array(
		'flag'    => true ,
		'content' => $text ,
	);
	return $arr;
}

function get_text_content_for_xls_ppt( $src_file )
{
	if ( empty($src_file) ) {
		return null ;	// no action
	}
	if ( ! is_file($src_file) ) {
		return null ;	// no action
	}
	if ( !$this->_use_jod ) {
		return null ;	// no action
	}

	$flag_dir = false ;
	$dir_new  = null;

	$node    = uniqid('n');
	$dir     = $this->_TMP_DIR ;
	$dir_new = $this->_TMP_DIR .'/'. uniqid('d');

// make tmp dir
	if ( ! ini_get('safe_mode') ) {
		mkdir( $dir_new );
		if ( is_dir($dir_new) ) {
			chmod( $dir_new, 0777 ) ;
			$dir = $dir_new ;
			$flag_dir = true ;
		}
	}

	$html_file = $dir .'/'. $node .'.'. $this->_HTML_EXT ;
	$this->_jod_class->convert( $src_file, $html_file );

	$file_arr = $this->get_files_in_dir( $dir, $this->_HTML_EXT, false, true );
	if ( !is_array($file_arr) ) {
		$arr = array(
			'flag'   => false ,
			'errors' => $this->_jod_class->get_msg_array() ,
		);
		return $arr;
	}

	$flag_match = false;
	$text = '';

	foreach ( $file_arr as $file ) 
	{
		$flag_file = false;
		if ( strpos($file, $node) === 0 ) {
			$flag_file = true;
		}
		if ( $flag_dir && ( strpos($file, 'text') === 0 ) ) {
			$flag_file = true;
		}
		if ( ! $flag_file ) {
			continue;
		}

		$flag_match = true;
		$file_full  = $dir .'/'. $file;
		$text .= file_get_contents( $file_full );

// remove tmp file
		unlink($file_full);
	}

// remove tmp dir
	if ( $flag_dir ) {
		$file_arr = $this->get_files_in_dir( $dir );
		if ( is_array($file_arr) ) {
			foreach ( $file_arr as $file ) 
			{
				$file_full = $dir .'/'. $file;
				unlink($file_full);
			}
		}
		rmdir( $dir_new );
	}

// no match file
	if ( ! $flag_match ) {
		$arr = array(
			'flag'   => false ,
			'errors' => array('no match file') ,
		);
		return $arr;
	}

	$text = $this->_multibyte_class->convert_from_utf8( $text );
	$text = $this->remove_junk( $text );
	$text = $this->_multibyte_class->build_plane_text( $text );

	$arr = array(
		'flag'    => true ,
		'content' => $text ,
	);
	return $arr;
}

function remove_junk( $text )
{
	foreach ( $this->_junk_words as $word ) {
		$text = str_replace( '>'.$word.'<', '> <', $text );
		$text = str_replace( '>'.$word.' ', '>  ', $text );
		$text = str_replace( ' '.$word.'<', '  <', $text );
		$text = str_replace( ' '.$word.' ', '   ', $text );
		$text = preg_replace( "/[\n|\r]".preg_quote($word)." /i", ' ', $text );
	}
	return $textN ;
}

//---------------------------------------------------------
// convert
//---------------------------------------------------------
function convert_single( $src_file, $dst_file )
{
	if ( !$this->_use_jod ) {
		return 0 ;	// no action
	}

	$ret = $this->_jod_class->convert( $src_file, $dst_file );
	if ( is_file($dst_file) ) {
		if ( $this->_flag_chmod ) {
			chmod( $dst_file, 0777 );
		}
		return 1 ;	// suceess
	}

	$this->set_error( $this->_jod_class->get_msg_array() );
	return -1 ;	// fail
}

//---------------------------------------------------------
// version
//---------------------------------------------------------
function use_jod()
{
	return $this->_use_jod ;
}

function java_path()
{
	return $this->_java_path ;
}

function version()
{
	return $this->_jod_class->version();
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function str_to_array( $str, $pattern )
{
	return $this->_utility_class->str_to_array( $str, $pattern );
}

function get_files_in_dir( $path, $ext=null, $flag_dir=false, $flag_sort=false, $id_as_key=false )
{
	return $this->_utility_class->get_files_in_dir( $path, $ext, $flag_dir, $flag_sort, $id_as_key );
}

// --- class end ---
}

?>