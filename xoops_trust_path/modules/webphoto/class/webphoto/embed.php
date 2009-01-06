<?php
// $Id: embed.php,v 1.3 2009/01/06 09:41:35 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-04 K.OHWADA
// webphoto_lib_plugin
// 2008-11-16 K.OHWADA
// $class->width()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_embed
//=========================================================
class webphoto_embed extends webphoto_lib_plugin
{
	var $_param = null ;

	var $_WIDTH_DEFAULT  = _C_WEBPHOTO_EMBED_WIDTH_DEFAULT ;
	var $_HEIGHT_DEFAULT = _C_WEBPHOTO_EMBED_HEIGHT_DEFAULT ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_embed( $dirname, $trust_dirname )
{
	$this->webphoto_lib_plugin( $dirname, $trust_dirname );
	$this->set_dirname( 'embeds' );
	$this->set_prefix(  'webphoto_embed_' );
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

function build_embed_link( $type, $src, $width, $height )
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

// plugin if empty
	if ( empty($width) ) {
		$width  = $class->width();
	}
	if ( empty($height) ) {
		$height = $class->height();
	}

// default if empty
	if ( empty($width) ) {
		$width = $this->_WIDTH_DEFAULT ;
	}
	if ( empty($height) ) {
		$height = $this->_HEIGHT_DEFAULT ;
	}

	$embed  = $class->embed( $src, $width, $height );
	$link   = $class->link(  $src );

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

function build_type_options( $flag_general )
{
	$list = $this->build_list();

	$arr = array() ;
	foreach ( $list as $type ) 
	{
		if ( ( $type == _C_WEBPHOTO_EMBED_NAME_GENERAL ) && !$flag_general ) {
			continue;
		}
		$arr[ $type ] = $type ;
	}

	return $arr;
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

// --- class end ---
}

?>