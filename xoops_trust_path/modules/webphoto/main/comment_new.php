<?php
// $Id: comment_new.php,v 1.2 2008/09/05 08:03:36 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-09-01 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto
//---------------------------------------------------------
if( !defined("WEBPHOTO_DIRNAME") ) {
	  define("WEBPHOTO_DIRNAME", $MY_DIRNAME );
}
if( !defined("WEBPHOTO_ROOT_PATH") ) {
	  define("WEBPHOTO_ROOT_PATH", XOOPS_ROOT_PATH.'/modules/'.WEBPHOTO_DIRNAME );
}

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
include_once WEBPHOTO_TRUST_PATH.'/class/d3/optional.php';
include_once WEBPHOTO_TRUST_PATH.'/include/optional.php';

webphoto_include_once( 'preload/debug.php' );
webphoto_include_once( 'class/lib/error.php' );
webphoto_include_once( 'class/lib/handler.php' );
webphoto_include_once( 'class/handler/item_handler.php' );

//=========================================================
// main
//=========================================================
$webphoto_item_handler =& webphoto_item_handler::getInstance( WEBPHOTO_DIRNAME );

$com_replytitle = $webphoto_item_handler->get_replytitle();
if ( $com_replytitle ) {

// $com_replytitle is required
	include XOOPS_ROOT_PATH.'/include/comment_new.php';

} else {
	echo "No photo matches your request <br>\n";
}

exit();

?>