<?php
// $Id: visit.php,v 1.1 2008/11/19 10:26:45 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
if( !defined("WEBPHOTO_DIRNAME") ) {
	  define("WEBPHOTO_DIRNAME", $MY_DIRNAME );
}
if( !defined("WEBPHOTO_ROOT_PATH") ) {
	  define("WEBPHOTO_ROOT_PATH", XOOPS_ROOT_PATH.'/modules/'.WEBPHOTO_DIRNAME );
}

include_once WEBPHOTO_TRUST_PATH.'/class/d3/optional.php';
include_once WEBPHOTO_TRUST_PATH.'/include/optional.php';

webphoto_include_once( 'include/constants.php' );
webphoto_include_once( 'class/lib/error.php' );
webphoto_include_once( 'class/lib/handler.php' );
webphoto_include_once( 'class/lib/post.php' );
webphoto_include_once( 'class/handler/item_handler.php' );
webphoto_include_once( 'class/main/visit.php' );

//=========================================================
// main
//=========================================================
$webphoto_manage =& webphoto_main_visit::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$webphoto_manage->main();
exit();

?>