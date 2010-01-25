<?php
// $Id: photo.php,v 1.6 2010/01/25 10:03:07 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-01-10 K.OHWADA
// init()
// 2009-10-25 K.OHWADA
// get_template_main()
// 2009-04-10 K.OHWADA
// remove get_photo_globals()
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
webphoto_include_once( 'class/main/photo.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_photo::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manage->init();

// exit if execute edittag
$manage->check_edittag();

$xoopsOption['template_main'] = $manage->get_template_main() ;
include XOOPS_ROOT_PATH . '/header.php' ;

$xoopsTpl->assign( $manage->main() ) ;

// subsutitute XOOPS_ROOT_PATH.'/include/comment_view.php';
$manage->comment_view();

include XOOPS_ROOT_PATH .'/footer.php' ;
exit();

?>