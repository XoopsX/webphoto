<?php
// $Id: edit.php,v 1.1 2008/06/21 12:22:14 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// xoops system files
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header.php' );
webphoto_include_once( 'class/lib/gtickets.php' );
webphoto_include_once( 'class/lib/element.php' );
webphoto_include_once( 'class/lib/form.php' );
webphoto_include_once( 'class/lib/uploader.php' );
webphoto_include_once( 'class/lib/image_cmd.php' );
webphoto_include_once( 'class/lib/exif.php' );
webphoto_include_once( 'class/handler/vote_handler.php' );
webphoto_include_once( 'class/handler/mime_handler.php' );
webphoto_include_once( 'class/handler/syno_handler.php' );
webphoto_include_once( 'class/webphoto/form_this.php' );
webphoto_include_once( 'class/webphoto/upload.php' );
webphoto_include_once( 'class/webphoto/image_create.php' );
webphoto_include_once( 'class/webphoto/mime.php' );
webphoto_include_once( 'class/webphoto/photo_build.php' );
webphoto_include_once( 'class/webphoto/photo_delete.php' );
webphoto_include_once( 'class/webphoto/photo_edit.php' );
webphoto_include_once( 'class/webphoto/photo_edit_form.php' );
webphoto_include_once( 'class/main/edit.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_edit::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );

// exit if execute edit
$manage->check_action();

include( XOOPS_ROOT_PATH.'/header.php' ) ;

$manage->print_form();

include( XOOPS_ROOT_PATH.'/footer.php' ) ;
exit();

?>