<?php
// $Id: embed.php,v 1.1 2008/10/30 00:25:51 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_embed
//=========================================================
class webphoto_embed extends webphoto_lib_base
{
	var $_param = null ;

	var $_EMBEDS_DIR;

	var $_WIDTH_DEFAULT  = _C_WEBPHOTO_EMBED_WIDTH_DEFAULT ;
	var $_HEIGHT_DEFAULT = _C_WEBPHOTO_EMBED_HEIGHT_DEFAULT ;

	var $_CLASS_PREFIX = 'webphoto_embed_' ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_embed( $dirname, $trust_dirname )
{
	$this->webphoto_lib_base( $dirname, $trust_dirname );

	$this->_EMBEDS_DIR = $this->_TRUST_DIR .'/plugins/embeds' ;
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_embed( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// embed
//---------------------------------------------------------
function set_param( $val )
{
	if ( is_array($val) ) {
		$this->_param = $val;
	}
}

function build_embed_link( $type, $src )
{
	if ( empty($type) ) {
		return false;
	}

	if ( empty($src) ) {
		return false;
	}

	$class =& $this->get_class_object( $type );
	if ( ! is_object($class) ) {
		return false;
	}

	if ( is_array($this->_param) ) {
		$class->set_param( $this->_param );
	}

	$embed = $class->embed( $src, $this->_WIDTH_DEFAULT, $this->_HEIGHT_DEFAULT );
	$link  = $class->link(  $src );

	return array( $embed, $link );
}

function build_link( $type, $src )
{
	if ( empty($type) ) {
		return false;
	}

	if ( empty($src) ) {
		return false;
	}

	$class =& $this->get_class_object( $type );
	if ( ! is_object($class) ) {
		return false;
	}

	return $class->link( $src );
}

function build_type_options()
{
	$files = $this->_utility_class->get_files_in_dir( $this->_EMBEDS_DIR, 'php', false, true );

	$options = array() ;
	foreach ( $files as $file ) {
		$opt_name = str_replace( '.php', '', $file );
		$options[ $opt_name ] = $opt_name ;
	}

	return $options;
}

function build_src_desc( $type, $src )
{
	if ( empty($type) ) {
		return false;
	}

	$class =& $this->get_class_object( $type );
	if ( ! is_object($class) ) {
		return false;
	}

	$lang = $class->lang_desc();
	if ( empty($lang) ) {
		$lang = 'Enter the video id from the url.';
	}

	$str  = $lang ."<br />\n";
	$str .= 'Exsample: ' ."<br />\n";
	$str .= $class->desc() ."<br />\n";

	if ( $src ) {
		$str .= '<img src="'. $class->thumb( $src ) .' border="0" />';
		$str .= "<br />\n";
	}

	return $str;
}

function build_thumb( $type, $src )
{
	if ( empty($type) ) {
		return false;
	}

	if ( empty($src) ) {
		return false;
	}

	$class =& $this->get_class_object( $type );
	if ( ! is_object($class) ) {
		return false;
	}

	return $class->thumb( $src );
}

function &get_class_object( $type )
{
	$false = false;

	if ( empty($type) ) {
		return $false;
	}

	$this->include_once_file( $type ) ;

	$class_name = $this->get_class_name( $type );
	if ( empty($class_name) ) {
		return $false;
	}

	$class = new $class_name();
	return $class ;
}

function include_once_file( $type )
{
	$file = $this->get_file_name( $type ) ;
	if ( $file ) {
		include_once $file ;
	}
}

function get_file_name( $type )
{
	$file = $this->_EMBEDS_DIR .'/'. $type .'.php' ;
	if ( file_exists( $file ) ) {
		return $file ;
	}
	return false;
}

function get_class_name( $type )
{
	$class = $this->_CLASS_PREFIX . $type ;
	if ( class_exists( $class ) ) {
		return $class;
	}
	return false;
}

// --- class end ---
}

?>