<?php
// $Id: sitemap.php,v 1.2 2008/07/05 16:57:40 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// used use_pathinfo
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_sitemap
//=========================================================
class webphoto_inc_sitemap extends webphoto_inc_handler
{
	var $_cfg_use_pathinfo = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_sitemap()
{
	$this->webphoto_inc_handler();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_sitemap();
	}
	return $instance;
}

function _init( $dirname )
{
	$this->init_handler( $dirname );
	$this->_init_xoops_config( $dirname );
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function sitemap( $dirname )
{
	$this->_init( $dirname );

	$table_cat = $this->prefix_dirname( 'cat' );

	if ( $this->_cfg_use_pathinfo ) {
		$link = 'index.php/category/' ;
	} else {
		$link = 'index.php?fct=category&amp;p=' ;
	}

// this function is defined in sitemap module
	if ( function_exists('sitemap_get_categoires_map') ) {
    	return sitemap_get_categoires_map( 
    		$table_cat, 'cat_id', 'cat_pid', 'cat_title', $link, 'cat_title' );
	}

	return array();
}

//---------------------------------------------------------
// xoops_config
//---------------------------------------------------------
function _init_xoops_config( $dirname )
{
	$config_handler =& webphoto_inc_config::getInstance();
	$config_handler->init( $dirname );

	$this->_cfg_use_pathinfo = $config_handler->get_by_name('use_pathinfo');
}

// --- class end ---
}

?>