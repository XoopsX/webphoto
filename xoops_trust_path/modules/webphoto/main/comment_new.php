<?php
// $Id: comment_new.php,v 1.4 2009/11/29 07:34:23 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// main/header_item_handler.php
// 2008-12-12 K.OHWADA
// constants.php
// 2008-09-01 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header_item_handler.php' );

//=========================================================
// main
//=========================================================
$webphoto_item_handler 
	=& webphoto_item_handler::getInstance( WEBPHOTO_DIRNAME, WEBPHOTO_TRUST_DIRNAME );

$com_replytitle = $webphoto_item_handler->get_replytitle();
if ( $com_replytitle ) {

// $com_replytitle is required
	include XOOPS_ROOT_PATH.'/include/comment_new.php';

} else {
	echo "No photo matches your request <br>\n";
}

exit();

?>