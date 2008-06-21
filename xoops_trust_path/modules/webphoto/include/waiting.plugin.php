<?php
// $Id: waiting.plugin.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

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

webphoto_include_once( 'class/inc/handler.php',  $MY_DIRNAME );
webphoto_include_once( 'class/inc/waiting.php' , $MY_DIRNAME );

//=========================================================
// search functions
//=========================================================
// --- eval begin ---
eval( '

function b_waiting_'.$MY_DIRNAME.'()
{
	return webphoto_waiting_base( "'.$MY_DIRNAME.'" ) ;
}

' );
// --- eval end ---

// === function begin ===
if( !function_exists( 'webphoto_waiting_base' ) ) 
{

function webphoto_waiting_base( $dirname )
{
	$inc_class =& webphoto_inc_waiting::getInstance();
	return $inc_class->waiting( $dirname );
}

// === function end ===
}

?>