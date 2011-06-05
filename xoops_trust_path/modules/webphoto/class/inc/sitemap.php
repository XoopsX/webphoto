<?php
// $Id: sitemap.php,v 1.5 2011/06/05 07:23:40 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-06-04 K.OHWADA
// webphoto_inc_uri
// 2009-11-11 K.OHWADA
// webphoto_inc_handler -> webphoto_inc_base_ini
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-07-01 K.OHWADA
// used use_pathinfo
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_sitemap
//=========================================================
class webphoto_inc_sitemap extends webphoto_inc_base_ini
{
	var $_uri_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_sitemap( $dirname , $trust_dirname )
{
	$this->webphoto_inc_base_ini();
	$this->init_base_ini( $dirname , $trust_dirname );
	$this->init_handler( $dirname );

	$this->_uri_class =& webphoto_inc_uri::getSingleton( $dirname );
}

function &getSingleton( $dirname , $trust_dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_sitemap( $dirname , $trust_dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function sitemap()
{
	$table_cat = $this->prefix_dirname( 'cat' );

	$link = $this->_uri_class->build_sitemap_category();

// this function is defined in sitemap module
	if ( function_exists('sitemap_get_categoires_map') ) {
    	return sitemap_get_categoires_map( 
    		$table_cat, 'cat_id', 'cat_pid', 'cat_title', $link, 'cat_title' );
	}

	return array();
}

// --- class end ---
}

?>