<?php
// $Id: photo.php,v 1.3 2008/12/18 13:23:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-12 K.OHWADA
// item_public.php
// 2008-11-16 K.OHWADA
// photo_navi.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header.php' );
webphoto_include_once( 'class/xoops/groupperm.php' );
webphoto_include_once( 'class/d3/comment_view.php' );
webphoto_include_once( 'class/lib/gtickets.php' );
webphoto_include_once( 'class/webphoto/item_public.php' );
webphoto_include_once( 'class/webphoto/photo_navi.php' );
webphoto_include_once( 'class/main/photo.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_photo::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );

// exit if execute edittag
$manage->check_edittag();

$xoopsOption['template_main'] = WEBPHOTO_DIRNAME.'_main_photo.html' ;
include XOOPS_ROOT_PATH . '/header.php' ;

$xoopsTpl->assign( $manage->get_photo_show_globals() ) ;
$xoopsTpl->assign( $manage->get_lang_array() ) ;
$xoopsTpl->assign( $manage->main() ) ;

// subsutitute XOOPS_ROOT_PATH.'/include/comment_view.php';
$manage->comment_view();

$xoopsTpl->assign( $manage->get_footer_param() ) ;
include XOOPS_ROOT_PATH .'/footer.php' ;
exit();

?>