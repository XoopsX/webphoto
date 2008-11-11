<?php
// $Id: mail_retrieve.php,v 1.2 2008/11/11 06:53:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-11-08 K.OHWADA
// imagemagick.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

webphoto_include_once( 'main/header.php' );
webphoto_include_once( 'class/lib/element.php' );
webphoto_include_once( 'class/lib/gd.php' );
webphoto_include_once( 'class/lib/imagemagick.php' );
webphoto_include_once( 'class/lib/netpbm.php' );
webphoto_include_once( 'class/lib/image_cmd.php' );
webphoto_include_once( 'class/lib/exif.php' );
webphoto_include_once( 'class/lib/ffmpeg.php' );
webphoto_include_once( 'class/lib/mail_pop.php' );
webphoto_include_once( 'class/lib/mail_parse.php' );
webphoto_include_once( 'class/handler/cat_photo_handler.php' );
webphoto_include_once( 'class/handler/mime_handler.php' );
webphoto_include_once( 'class/handler/syno_handler.php' );
webphoto_include_once( 'class/handler/user_handler.php' );
webphoto_include_once( 'class/handler/maillog_handler.php' );
webphoto_include_once( 'class/webphoto/image_create.php' );
webphoto_include_once( 'class/webphoto/photo_build.php' );
webphoto_include_once( 'class/webphoto/mime.php' );
webphoto_include_once( 'class/webphoto/video.php' );
webphoto_include_once( 'class/webphoto/photo_create.php' );
webphoto_include_once( 'class/webphoto/mail_check.php' );
webphoto_include_once( 'class/webphoto/mail_photo.php' );
webphoto_include_once( 'class/webphoto/mail_unlink.php' );
webphoto_include_once( 'class/webphoto/mail_retrieve.php' );
webphoto_include_once( 'class/main/mail_retrieve.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_mail_retrieve::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );

// exit if false
$manage->check();

include XOOPS_ROOT_PATH . "/header.php" ;

echo $manage->main();

include( XOOPS_ROOT_PATH . "/footer.php" ) ;
exit();

?>