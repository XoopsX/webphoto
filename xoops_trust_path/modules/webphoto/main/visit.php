<?php
// $Id: visit.php,v 1.4 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// class/webphoto/base_ini.php
// 2009-11-11 K.OHWADA
// main/header_item_handler.php
// 2008-12-12 K.OHWADA
// item_public.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header_item_handler.php' );

webphoto_include_once( 'class/xoops/base.php' );
webphoto_include_once( 'class/d3/language.php' );
webphoto_include_once( 'class/lib/post.php' );
webphoto_include_once( 'class/lib/base.php' );
webphoto_include_once( 'class/lib/msg.php' );
webphoto_include_once( 'class/handler/cat_handler.php' );
webphoto_include_once( 'class/webphoto/base_ini.php' );
webphoto_include_once( 'class/webphoto/config.php' );
webphoto_include_once( 'class/webphoto/item_public.php' );
webphoto_include_once( 'class/main/visit.php' );

//=========================================================
// main
//=========================================================
$webphoto_manage =& webphoto_main_visit::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$webphoto_manage->main();
exit();

?>