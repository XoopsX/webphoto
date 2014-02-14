<?php
// $Id: notification.inc.php,v 1.4 2011/06/05 07:23:40 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-06-04 K.OHWADA
// class/inc/uri.php
// 2009-11-11 K.OHWADA
// WEBPHOTO_TRUST_DIRNAME in webphoto_inc_notification
// include/header.php
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
//---------------------------------------------------------

//---------------------------------------------------------
// $MY_DIRNAME WEBPHOTO_TRUST_PATH are set by caller
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

include_once WEBPHOTO_TRUST_PATH.'/include/header.php';

webphoto_include_once( 'class/inc/uri.php' ,          $MY_DIRNAME );
webphoto_include_once( 'class/inc/notification.php' , $MY_DIRNAME );

//=========================================================
// notification functions
//=========================================================
// --- eval begin ---
eval( '

function '.$MY_DIRNAME.'_notify_iteminfo( $category, $item_id )
{
	return webphoto_notify_iteminfo_base( "'.$MY_DIRNAME.'" , $category, $item_id ) ;
}

' );
// --- eval end ---

// === notify_iteminfo_base begin ===
if( !function_exists( 'webphoto_notify_iteminfo_base' ) ) 
{

//---------------------------------------------------------
// function
//---------------------------------------------------------
function webphoto_notify_iteminfo_base( $dirname, $category, $item_id )
{
	$inc_class =& webphoto_inc_notification::getSingleton( 
		$dirname, WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->notify( $category, $item_id );
}

// === notify_iteminfo_base end ===
}

?>