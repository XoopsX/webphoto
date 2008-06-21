<?php
// $Id: sitemap.php,v 1.1 2008/06/21 12:22:26 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_sitemap
//=========================================================
class webphoto_inc_sitemap extends webphoto_inc_handler
{

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

//---------------------------------------------------------
// public
//---------------------------------------------------------
function sitemap( $dirname )
{
	$this->init_handler( $dirname );

	$table_cat = $this->prefix_dirname( 'cat' );

// this function is defined in sitemap module
	if ( function_exists('sitemap_get_categoires_map') ) {
    	return sitemap_get_categoires_map( 
    		$table_cat, 'cat_id', 'cat_pid', 'cat_title', 'index.php?cat_id=', 'cat_title' );
	}

	return array();
}

// --- class end ---
}

?>