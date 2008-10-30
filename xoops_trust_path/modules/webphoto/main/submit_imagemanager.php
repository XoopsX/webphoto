<?php
// $Id: submit_imagemanager.php,v 1.6 2008/10/30 00:22:49 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-10-01 K.OHWADA
// photo_action.php
// 2008-08-24 K.OHWADA
// added class/xoops/user.php photo_create.php
// removed photo_delete.php
// 2008-08-01 K.OHWADA
// removed msg.php
// 2008-07-01 K.OHWADA
// added ffmpeg.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// xoops system files
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header.php' );
webphoto_include_once( 'class/xoops/user.php' );
webphoto_include_once( 'class/d3/notification_event.php' );
webphoto_include_once( 'class/lib/gtickets.php' );
webphoto_include_once( 'class/lib/element.php' );
webphoto_include_once( 'class/lib/form.php' );
webphoto_include_once( 'class/lib/uploader.php' );
webphoto_include_once( 'class/lib/image_cmd.php' );
webphoto_include_once( 'class/lib/exif.php' );
webphoto_include_once( 'class/lib/ffmpeg.php' );
webphoto_include_once( 'class/handler/vote_handler.php' );
webphoto_include_once( 'class/handler/mime_handler.php' );
webphoto_include_once( 'class/handler/syno_handler.php' );
webphoto_include_once( 'class/webphoto/form_this.php' );
webphoto_include_once( 'class/webphoto/upload.php' );
webphoto_include_once( 'class/webphoto/image_create.php' );
webphoto_include_once( 'class/webphoto/mime.php' );
webphoto_include_once( 'class/webphoto/video.php' );
webphoto_include_once( 'class/webphoto/photo_create.php' );
webphoto_include_once( 'class/webphoto/photo_build.php' );
webphoto_include_once( 'class/webphoto/photo_edit.php' );
webphoto_include_once( 'class/webphoto/photo_redirect.php' );
webphoto_include_once( 'class/webphoto/photo_action.php' );
webphoto_include_once( 'class/webphoto/photo_edit_form.php' );
webphoto_include_once( 'class/webphoto/notification_event.php' );
webphoto_include_once( 'class/main/submit_imagemanager.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_submit_imagemanager::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manage->main();
exit();

?>