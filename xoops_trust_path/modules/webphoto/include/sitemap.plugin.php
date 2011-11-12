<?php
// $Id: sitemap.plugin.php,v 1.6 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// remove class/inc/config.php
// 2011-06-04 K.OHWADA
// class/inc/uri.php
// 2009-11-11 K.OHWADA
// WEBPHOTO_TRUST_DIRNAME in webphoto_inc_sitemap
// include/header.php
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-07-01 K.OHWADA
// added config.php
//---------------------------------------------------------

//---------------------------------------------------------
// $MY_DIRNAME WEBPHOTO_TRUST_PATH are set by caller
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

include_once WEBPHOTO_TRUST_PATH.'/include/header.php';

webphoto_include_once( 'class/inc/uri.php' ,     $MY_DIRNAME );
webphoto_include_once( 'class/inc/sitemap.php' , $MY_DIRNAME );

//=========================================================
// search functions
//=========================================================
// --- eval begin ---
eval( '

function b_sitemap_'.$MY_DIRNAME.'()
{
	return webphoto_sitemap_base( "'.$MY_DIRNAME.'" ) ;
}

' );
// --- eval end ---

// === function begin ===
if( !function_exists( 'webphoto_sitemap_base' ) ) 
{

function webphoto_sitemap_base( $dirname )
{
	$inc_class =& webphoto_inc_sitemap::getSingleton( 
		$dirname, WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->sitemap();
}

// === function end ===
}

?>