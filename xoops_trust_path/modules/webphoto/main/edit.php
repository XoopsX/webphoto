<?php
// $Id: edit.php,v 1.13 2011/11/13 05:24:37 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// new main/header_submit_imagemanager.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header_submit.php' );
webphoto_include_once( 'class/edit/action.php' );
webphoto_include_once( 'class/main/edit.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_edit::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );

// exit if execute edit
$manage->check_action();

$xoopsOption['template_main'] = WEBPHOTO_DIRNAME.'_main_edit.html' ;
include( XOOPS_ROOT_PATH.'/header.php' ) ;

$xoopsTpl->assign( $manage->form_param() ) ;

include( XOOPS_ROOT_PATH.'/footer.php' ) ;
exit();

?>