<?php
// $Id: import_myalbum.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/lib/image_cmd.php' );
webphoto_include_once( 'class/handler/vote_handler.php' );
webphoto_include_once( 'class/handler/mime_handler.php' );
webphoto_include_once( 'class/handler/syno_handler.php' );
webphoto_include_once( 'class/handler/xoops_comments_handler.php' );
webphoto_include_once( 'class/handler/myalbum_handler.php' );
webphoto_include_once( 'class/webphoto/image_info.php' );
webphoto_include_once( 'class/webphoto/image_create.php' );
webphoto_include_once( 'class/webphoto/photo_build.php' );
webphoto_include_once( 'class/webphoto/mime.php' );
webphoto_include_once( 'class/webphoto/import.php' );
webphoto_include_once( 'class/admin/import_form.php' );
webphoto_include_once( 'class/admin/import_myalbum.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_import_myalbum::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>