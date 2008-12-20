<?php
// $Id: waiting.plugin.php,v 1.2 2008/12/20 06:11:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
//---------------------------------------------------------

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
	$inc_class =& webphoto_inc_waiting::getSingleton( $dirname );
	return $inc_class->waiting();
}

// === function end ===
}

?>