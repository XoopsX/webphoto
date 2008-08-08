<?php
// $Id: config.php,v 1.3 2008/08/08 04:36:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-01 K.OHWADA
// added is_set_mail()
// removed get_tmp_path()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_config
//=========================================================
class webphoto_config
{
	var $_utility_class ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_config( $dirname )
{
	$this->_init( $dirname );

	$this->_utility_class =& webphoto_lib_utility::getInstance();
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_config( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// init
//---------------------------------------------------------
function _init( $dirname )
{
	$xoops_class =& webphoto_xoops_base::getInstance();
	$this->_config_array = $xoops_class->get_module_config_by_dirname( $dirname );
}

//---------------------------------------------------------
// get
//---------------------------------------------------------
function get_by_name( $name )
{
	if ( isset($this->_config_array[ $name ]) ) {
		return $this->_config_array[ $name ];
	}
	return null;
}

function get_array_by_name( $name )
{
	$str = $this->get_by_name( $name );
	if ( $str ) {
		$arr = explode( '|' , $str ) ;
	} else {
		$arr = array() ;
	}
	return $arr;
}

function get_dir_by_name( $name )
{
	$str = $this->get_by_name( $name );
	return $this->add_separator_to_tail( $str );
}

function get_photos_path()
{
	return $this->_get_path_by_name( 'photospath' );
}

function get_thumbs_path()
{
	return $this->_get_path_by_name( 'thumbspath' );
}

function get_gicons_path()
{
	return $this->_get_path_by_name( 'giconspath' );
}

function get_middle_wh()
{
	$width  = $this->get_by_name('middle_width');
	$height = $this->get_by_name('middle_height');

	if ( $width && empty($height) ) {
		$height = $width;
	} elseif ( empty($width) && $height ) {
		$width = $height;
	}

	return array($width, $height);
}

function get_thumb_wh()
{
	$width  = $this->get_by_name('thumb_width');
	$height = $this->get_by_name('thumb_height');

	if ( $width && empty($height) ) {
		$height = $width;
	} elseif ( empty($width) && $height ) {
		$width = $height;
	}

	return array($width, $height);
}

function _get_path_by_name( $name )
{
	$path = $this->get_by_name( $name );
	if ( $path ) {
		return $this->add_slash_to_head( $path );
	}
	return null;
}

function is_set_mail()
{
	$host = $this->get_by_name('mail_host');
	$user = $this->get_by_name('mail_user');
	$pass = $this->get_by_name('mail_pass');
	
	if ( $host && $user && $pass ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// utlity class
//---------------------------------------------------------
function add_slash_to_head( $str )
{
	return $this->_utility_class->add_slash_to_head( $str );
}

function add_separator_to_tail( $str )
{
	return $this->_utility_class->add_separator_to_tail( $str );
}

// --- class end ---
}

?>