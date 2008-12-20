<?php
// $Id: sitemap.php,v 1.3 2008/12/20 06:11:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
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
function webphoto_inc_sitemap( $dirname )
{
	$this->webphoto_inc_handler();
	$this->init_handler( $dirname );

	$this->_init_xoops_config( $dirname );
}

function &getSingleton( $dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_sitemap( $dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function sitemap()
{
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
	$config_handler =& webphoto_inc_config::getSingleton( $dirname );

	$this->_cfg_use_pathinfo = $config_handler->get_by_name('use_pathinfo');
}

// --- class end ---
}

?>