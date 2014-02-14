<?php
// $Id: comment.inc.php,v 1.3 2009/11/29 07:34:23 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// WEBPHOTO_TRUST_DIRNAME in webphoto_inc_comment
// include/header.php
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
//---------------------------------------------------------

//---------------------------------------------------------
// $MY_DIRNAME WEBPHOTO_TRUST_PATH are set by caller
//---------------------------------------------------------

if ( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

include_once WEBPHOTO_TRUST_PATH.'/include/header.php';

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
	$inc_handler =& webphoto_inc_comment::getSingleton( 
		$dirname, WEBPHOTO_TRUST_DIRNAME );
	return $inc_handler->update_photo_comments( $id, $comments );
}

function webphoto_comments_approve_base( $dirname , &$comment )
{
	// notification mail here
}

// === com_update_base end ===
}

?>