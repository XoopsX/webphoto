<?php
// $Id: whatsnew.plugin.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// $MY_DIRNAME WEBPHOTO_TRUST_PATH are set by caller
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

include_once WEBPHOTO_TRUST_PATH.'/class/d3/optional.php';
include_once WEBPHOTO_TRUST_PATH.'/include/optional.php';

webphoto_include_once( 'include/constants.php',   $MY_DIRNAME );
webphoto_include_once( 'class/inc/handler.php',   $MY_DIRNAME );
webphoto_include_once( 'class/inc/whatsnew.php' , $MY_DIRNAME );
webphoto_include_once( 'preload/whatsnew.php' ,   $MY_DIRNAME  );

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
	$inc_class =& webphoto_inc_whatsnew::getInstance();
	return $inc_class->whatsnew( $dirname , $limit , $offset );
}

// === function end ===
}

?>