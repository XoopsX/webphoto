<?php
// $Id: giconmanager.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/lib/uploader.php' );
webphoto_include_once( 'class/lib/image_cmd.php' );
webphoto_include_once( 'class/handler/vote_handler.php' );
webphoto_include_once( 'class/handler/mime_handler.php' );
webphoto_include_once( 'class/handler/syno_handler.php' );
webphoto_include_once( 'class/handler/gicon_handler.php' );
webphoto_include_once( 'class/webphoto/upload.php' );
webphoto_include_once( 'class/webphoto/image_info.php' );
webphoto_include_once( 'class/webphoto/image_create.php' );
webphoto_include_once( 'class/webphoto/mime.php' );
webphoto_include_once( 'class/admin/gicon_form.php' );
webphoto_include_once( 'class/admin/giconmanager.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_giconmanager::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>