<?php
// $Id: comment.inc.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// $MY_DIRNAME WEBPHOTO_TRUST_PATH are set by caller
//---------------------------------------------------------

if ( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

include_once WEBPHOTO_TRUST_PATH.'/class/d3/optional.php';
include_once WEBPHOTO_TRUST_PATH.'/include/optional.php';

webphoto_include_once( 'class/inc/handler.php',  $MY_DIRNAME );
webphoto_include_once( 'class/inc/comment.php' , $MY_DIRNAME );

//=========================================================
// comment callback functions
//=========================================================
// --- eval begin ---
eval( '

function '.$MY_DIRNAME.'_comments_update( $id, $comments )
{
	return webphoto_comments_update_base( "'.$MY_DIRNAME.'" , $id, $comments ) ;
}

function '.$MY_DIRNAME.'_comments_approve( &$comment )
{
	return webphoto_comments_approve_base( "'.$MY_DIRNAME.'" , $comment ) ;
}

' );
// --- eval end ---

// === com_update_base begin ===
if( !function_exists( 'webphoto_comments_update_base' ) ) 
{

function webphoto_comments_update_base( $dirname , $id , $comments ) 
{
	$inc_handler =& webphoto_inc_comment::getInstance();
	return $inc_handler->update_photo_comments( $dirname, $id, $comments );
}

function webphoto_comments_approve_base( $dirname , &$comment )
{
	// notification mail here
}

// === com_update_base end ===
}

?>