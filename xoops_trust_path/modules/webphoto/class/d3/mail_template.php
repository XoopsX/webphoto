<?php
// $Id: mail_template.php,v 1.3 2010/04/22 03:50:48 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-04-22 K.OHWADA
// get_template_file()
// 2010-02-01 K.OHWADA
// replace_str_by_tags()
//---------------------------------------------------------

//=========================================================
// class webphoto_d3_mail_template
// referrence: kernel/notification.php
//=========================================================
class webphoto_d3_mail_template
{
	var $_DIRNAME;
	var $_TRUST_DIRNAME;
	var $_MODULE_DIR;
	var $_MODULE_URL;
	var $_TRUST_DIR;
	var $_PRELOAD_DIR;
	var $_SITE_URL;
	var $_UNSUBSCRIBE_URL;

	var $_xoops_language;
	var $_xoops_sitename;
	var $_xoops_adminmail;
	var $_xoops_module_name;

	var $_tag_array = array();

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_d3_mail_template( $dirname, $trust_dirname )
{
	$this->_DIRNAME       = $dirname;
	$this->_TRUST_DIRNAME = $trust_dirname;
	$this->_MODULE_DIR    = XOOPS_ROOT_PATH  .'/modules/'. $dirname;
	$this->_MODULE_URL    = XOOPS_URL        .'/modules/'. $dirname;
	$this->_TRUST_DIR     = XOOPS_TRUST_PATH .'/modules/'. $trust_dirname;
	$this->_PRELOAD_DIR   = $this->_MODULE_DIR.'/preload';

	$this->_SITE_URL        = XOOPS_URL .'/';
	$this->_UNSUBSCRIBE_URL = XOOPS_URL .'/notifications.php';

	$this->_xoops_language    = $this->get_xoops_language();
	$this->_xoops_sitename    = $this->get_xoops_sitename();
	$this->_xoops_adminmail   = $this->get_xoops_adminmail();
	$this->_xoops_module_name = $this->get_xoops_module_name();
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_d3_mail_template( $dirname, $trust_dirname );
	}
	return $instance;
}

//-------------------------------------------------------------------
// get_dir_mail_template
//-------------------------------------------------------------------
function get_template_file( $file ) 
{
	$template_file = $this->_PRELOAD_DIR.'/'.$file;
	if ( file_exists( $template_file ) ) {
		return $template_file;
	}

	$dir = $this->get_dir_mail_template( $file );
	if ( $dir ) {
		return $dir.$file;
	}

	return false;
}

function get_dir_mail_template( $file ) 
{
	$dir_trust_lang = $this->build_dir( $this->_TRUST_DIR,  $this->_xoops_language );
	$dir_trust_eng  = $this->build_dir( $this->_TRUST_DIR,  'english' );
	$dir_root_lang  = $this->build_dir( $this->_MODULE_DIR, $this->_xoops_language );

	if ( file_exists( $dir_root_lang.$file ) ) {
		return $dir_root_lang;
	} elseif ( file_exists( $dir_trust_lang.$file ) ) {
		return $dir_trust_lang;
	} elseif ( file_exists( $dir_trust_eng.$file ) ) {
		return $dir_trust_eng;
	}
	return false;
}

function build_dir( $dir, $lang ) 
{
	$str = $dir .'/language/'. $lang .'/mail_template/';
	return $str;
}

function build_dir_preload() 
{
	$str = $dir .'/language/'. $lang .'/mail_template/';
	return $str;
}

//---------------------------------------------------------
// read template file
//---------------------------------------------------------
function replace_tag_array_by_template( $file ) 
{
	return $this->replace_tag_array( $this->read_template( $file ) );
}

function read_template( $file ) 
{
	$temp_file = $this->get_template_file( $file );
	if ( $temp_file ) {
		return $this->read_file( $temp_file );
	}
	return false;
}

function read_file( $file ) 
{
	$fp = fopen($file, 'r');
	if ( $fp ) {
		$ret = fread( $fp, filesize($file) );
		return $ret;
	}
	return false;
}

//---------------------------------------------------------
// assign tags
//---------------------------------------------------------
function init_tag_array()
{
	$this->assign('X_SITEURL',          $this->_SITE_URL );
	$this->assign('X_SITENAME',         $this->_xoops_sitename );
	$this->assign('X_ADMINMAIL',        $this->_xoops_adminmail );
	$this->assign('X_MODULE',           $this->_xoops_module_name );
	$this->assign('X_MODULE_URL',       $this->_MODULE_URL );
	$this->assign('X_UNSUBSCRIBE_URL',  $this->_UNSUBSCRIBE_URL );
}

function merge_tag_array( $tags )
{
	if ( is_array($tags) ) {
		$this->_tag_array = array_merge( $this->_tag_array, $tags );
	}
}

function assign($tag, $value=null)
{
	if ( is_array($tag) ) {
		foreach ( $tag as $k => $v ) {
			$this->assign($k, $v);
		}
	} else {
		if ( !empty($tag) && isset($value) ) {
			$tag = strtoupper(trim($tag));
			$this->_tag_array[$tag] = $value;
		}
	}
}

function replace_tag_array( $str ) 
{
	return $this->replace_str_by_tags( $str, $this->_tag_array );
}

function replace_str_by_tags( $str, $tags ) 
{
	foreach ( $tags as $k => $v ) {
		$str = str_replace("{".$k."}", $v, $str);
	}
	return $str;
}

//---------------------------------------------------------
// XOOPS system
//---------------------------------------------------------
function get_xoops_language()
{
	global $xoopsConfig;
	return $xoopsConfig['language'];
}

function get_xoops_sitename()
{
	global $xoopsConfig;
	return $xoopsConfig['sitename'];
}

function get_xoops_adminmail()
{
	global $xoopsConfig;
	return $xoopsConfig['adminmail'];
}

function get_xoops_module_name( $format='n' )
{
	$name = false;
	$module_handler =& xoops_gethandler('module');
	$module =& $module_handler->getByDirname( $this->_DIRNAME );
	if ( is_object($module) ) {
		$name =  $module->getVar('name', $format );
	}
	return $name;
}

// --- class end ---
}

?>