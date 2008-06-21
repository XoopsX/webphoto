<?php
// $Id: rate.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

webphoto_include_once( 'main/header.php' );
webphoto_include_once( 'class/lib/gtickets.php' );
webphoto_include_once( 'class/lib/base.php' );
webphoto_include_once( 'class/handler/vote_handler.php' );
webphoto_include_once( 'class/main/rate.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_rate::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );

// exit if execute rate
$manage->rate();

$xoopsOption['template_main'] = WEBPHOTO_DIRNAME.'_main_rate.html' ;
include XOOPS_ROOT_PATH . "/header.php" ;

$xoopsTpl->assign( $manage->get_photo_globals() ) ;
$xoopsTpl->assign( $manage->get_lang_array() ) ;
$xoopsTpl->assign( $manage->get_photo() ) ;

include( XOOPS_ROOT_PATH . "/footer.php" ) ;
exit();

?>