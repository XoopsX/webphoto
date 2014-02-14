<?php
// $Id: submit_file.php,v 1.6 2009/04/19 11:39:45 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-04-19 K.OHWADA
// template_main
// 2009-01-10 K.OHWADA
// header_submit.php
// 2009-01-04 K.OHWADA
// editor.php
// 2008-11-08 K.OHWADA
// imagemagick.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header_submit.php' );
webphoto_include_once( 'class/edit/photo_form.php' );
webphoto_include_once( 'class/edit/misc_form.php' );
webphoto_include_once( 'class/main/submit_file.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_submit_file::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );

// exit if execute
$manage->check_action();

$xoopsOption['template_main'] = WEBPHOTO_DIRNAME.'_main_submit_file.html' ;
include( XOOPS_ROOT_PATH.'/header.php' ) ;

$xoopsTpl->assign( $manage->form_param() ) ;

include( XOOPS_ROOT_PATH.'/footer.php' ) ;
exit();

?>