<?php
// $Id: ext.php,v 1.7 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// execute()
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
	var $_cached_objs_by_ext = array() ;

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
function execute( $method, $param )
{
	$ext = isset($param['src_ext'])  ? $param['src_ext'] : null ;
	if ( empty($ext) ) {
		return null ;	// no action
	}

	$class =& $this->get_cached_class_object_by_ext( $ext );
	if ( ! is_object($class) ) {
		return null ;	// no action
	}

	return $class->execute( $method, $param );
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function &get_cached_class_object_by_ext( $ext )
{
	if ( isset( $this->_cached_objs_by_ext[ $ext ] ) ) {
		return  $this->_cached_objs_by_ext[ $ext ] ;
	}

	$list = $this->get_cached_list();
	foreach ( $list as $type )
	{
		$class =& $this->get_cached_class_object( $type );
		if ( ! is_object($class) ) {
			continue;
		}
		if ( ! $class->is_ext( $ext ) ) {
			continue;
		}

		return $class;
	}

	$false = false;
	return $false;
}

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