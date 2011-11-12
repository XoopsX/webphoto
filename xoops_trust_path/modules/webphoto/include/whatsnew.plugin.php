<?php
// $Id: whatsnew.plugin.php,v 1.8 2011/11/12 11:05:02 ohwada Exp $

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
// WEBPHOTO_TRUST_DIRNAME in webphoto_inc_whatsnew
// include/header.php
// 2008-12-12 K.OHWADA
// public.php
// 2008-11-29 K.OHWADA
// auto_publish.php
// 2008-07-01 K.OHWADA
// added config.php
//---------------------------------------------------------

//---------------------------------------------------------
// $MY_DIRNAME WEBPHOTO_TRUST_PATH are set by caller
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

include_once WEBPHOTO_TRUST_PATH.'/include/header.php';

webphoto_include_once( 'class/inc/public.php' ,      $MY_DIRNAME );
webphoto_include_once( 'class/inc/auto_publish.php', $MY_DIRNAME );
webphoto_include_once( 'class/inc/uri.php' ,         $MY_DIRNAME );
webphoto_include_once( 'class/inc/whatsnew.php' ,    $MY_DIRNAME );

//=========================================================
// search functions
//=========================================================
// --- eval begin ---
eval( '

function '.$MY_DIRNAME.'_new( $limit=0 , $offset=0 )
{
	return webphoto_whatsnew_new_base( "'.$MY_DIRNAME.'" , $limit , $offset ) ;
}

' );
// --- eval end ---

// === function begin ===
if( !function_exists( 'webphoto_whatsnew_new_base' ) ) 
{

function webphoto_whatsnew_new_base( $dirname , $limit=0 , $offset=0 )
{
	$inc_class =& webphoto_inc_whatsnew::getSingleton( 
		$dirname, WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->whatsnew( $limit , $offset );
}

// === function end ===
}

?>