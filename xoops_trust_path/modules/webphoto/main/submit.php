<?php
// $Id: submit.php,v 1.10 2010/02/07 12:20:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-02-01 K.OHWADA
// class/webphoto/mail_send.php
// 2009-04-19 K.OHWADA
// template_main
// 2009-01-10 K.OHWADA
// header_submit.php
// 2009-01-04 K.OHWADA
// editor.php
// 2008-11-08 K.OHWADA
// imagemagick.php
// 2008-10-01 K.OHWADA
// photo_action.php
// 2008-08-24 K.OHWADA
// removed photo_delete.php
// 2008-08-01 K.OHWADA
// added class/xoops/user.php photo_create.php
// removed msg.php
// 2008-07-01 K.OHWADA
// added ffmpeg.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header_submit.php' );

webphoto_include_once( 'class/d3/mail_template.php' );
webphoto_include_once( 'class/lib/mail_send.php' );
webphoto_include_once( 'class/webphoto/mail_send.php' );

webphoto_include_once( 'class/edit/submit.php' );
webphoto_include_once( 'class/edit/action.php' );
webphoto_include_once( 'class/edit/photo_form.php' );
webphoto_include_once( 'class/edit/misc_form.php' );
webphoto_include_once( 'class/main/submit.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_submit::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );

// exit if execute submit
$manage->check_submit();

$xoopsOption['template_main'] = WEBPHOTO_DIRNAME.'_main_submit.html' ;
include( XOOPS_ROOT_PATH.'/header.php' ) ;

$xoopsTpl->assign( $manage->form_param() ) ;

include( XOOPS_ROOT_PATH.'/footer.php' ) ;
exit();

?>