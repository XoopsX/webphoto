<?php
// $Id: submit_imagemanager.php,v 1.9 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-01-10 K.OHWADA
// header_submit.php
// 2008-01-04 K.OHWADA
// editor.php
// 2008-11-08 K.OHWADA
// imagemagick.php
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
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header_submit.php' );
webphoto_include_once( 'class/edit/imagemanager_form.php' );
webphoto_include_once( 'class/main/submit_imagemanager.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_submit_imagemanager::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manage->main();
exit();

?>