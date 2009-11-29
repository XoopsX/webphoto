<?php
// $Id: ext.php,v 1.5 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname in plugin class
// 2009-10-25 K.OHWADA
// create_jpeg()
// 2009-01-25 K.OHWADA
// create_swf()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ext
//=========================================================
class webphoto_ext extends webphoto_lib_plugin
{
	var $_cached_list = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ext( $dirname, $trust_dirname )
{
	$this->webphoto_lib_plugin( $dirname, $trust_dirname );
	$this->set_dirname( 'exts' );
	$this->set_prefix(  'webphoto_ext_' );
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_ext( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function create_image( $param )
{
	$src_ext     = isset($param['src_ext'])    ? $param['src_ext']            : null ;
	$flag_video  = isset($param['flag_video']) ? (bool)($param['flag_video']) : false ;
	$flag_extra  = isset($param['flag_extra']) ? (bool)($param['flag_extra']) : false ;

	$list = $this->get_cached_list();
	foreach ( $list as $type )
	{
		if ( !$flag_video && ( $type == 'video' ) ) {
			continue;
		}
		if ( !$flag_extra && ( $type != 'video' ) ) {
			continue;
		}

		$class =& $this->get_cached_class_object( $type );
		if ( ! is_object($class) ) {
			continue;
		}
		if ( ! $class->is_ext( $src_ext ) ) {
			continue;
		}

		return $class->create_image( $param );
	}

	return null ;
}

function create_jpeg( $param )
{
	$src_ext  = isset($param['src_ext'])  ? $param['src_ext'] : null ;

	$list = $this->get_cached_list();
	foreach ( $list as $type )
	{
		$class =& $this->get_cached_class_object( $type );
		if ( ! is_object($class) ) {
			continue;
		}
		if ( ! $class->is_ext( $src_ext ) ) {
			continue;
		}

		return $class->create_jpeg( $param );
	}

	return 0 ;	// no action
}

function create_pdf( $param )
{
	$src_ext  = isset($param['src_ext'])  ? $param['src_ext'] : null ;

	$list = $this->get_cached_list();
	foreach ( $list as $type )
	{
		$class =& $this->get_cached_class_object( $type );
		if ( ! is_object($class) ) {
			continue;
		}
		if ( ! $class->is_ext( $src_ext ) ) {
			continue;
		}

		return $class->create_pdf( $param );
	}

	return 0 ;	// no action
}

function create_swf( $param )
{
	$src_ext  = isset($param['src_ext'])  ? $param['src_ext'] : null ;

	$list = $this->get_cached_list();
	foreach ( $list as $type )
	{
		$class =& $this->get_cached_class_object( $type );
		if ( ! is_object($class) ) {
			continue;
		}
		if ( ! $class->is_ext( $src_ext ) ) {
			continue;
		}

		return $class->create_swf( $param );
	}

	return 0 ;	// no action
}

function create_mp3( $param )
{
	$src_ext  = isset($param['src_ext'])  ? $param['src_ext'] : null ;

	$list = $this->get_cached_list();
	foreach ( $list as $type )
	{
		$class =& $this->get_cached_class_object( $type );
		if ( ! is_object($class) ) {
			continue;
		}
		if ( ! $class->is_ext( $src_ext ) ) {
			continue;
		}

		return $class->create_mp3( $param );
	}

	return 0 ;	// no action
}

function get_duration_size( $param )
{
	$src_ext = isset($param['src_ext']) ? $param['src_ext'] : null ;

	$list = $this->get_cached_list();
	foreach ( $list as $type )
	{
		$class =& $this->get_cached_class_object( $type );
		if ( ! is_object($class) ) {
			continue;
		}
		if ( ! $class->is_ext( $src_ext ) ) {
			continue;
		}
		return $class->get_duration_size( $param );
	}

	return null ;
}

function get_text_content( $param )
{
	$src_ext = isset($param['src_ext']) ? $param['src_ext'] : null ;

	$list = $this->get_cached_list();
	foreach ( $list as $type )
	{
		$class =& $this->get_cached_class_object( $type );
		if ( ! is_object($class) ) {
			continue;
		}
		if ( ! $class->is_ext( $src_ext ) ) {
			continue;
		}

		return $class->get_text_content( $param );
	}

	return null ;
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function get_cached_list()
{
	if ( is_array( $this->_cached_list ) ) {
		return $this->_cached_list ;
	}

	$list = $this->build_list();
	$this->_cached_list = $list;
	return $list;
}

// overwrite
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

	$class = new $class_name( $this->_DIRNAME, $this->_TRUST_DIRNAME );
	return $class ;
}

// --- class end ---
}

?>