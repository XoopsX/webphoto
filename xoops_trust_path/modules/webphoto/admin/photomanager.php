<?php
// $Id: photomanager.php,v 1.2 2008/08/27 04:51:49 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// added maillog_handler.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/lib/pagenavi.php' );
webphoto_include_once( 'class/lib/highlight.php' );
webphoto_include_once( 'class/lib/multibyte.php' );
webphoto_include_once( 'class/handler/cat_photo_handler.php' );
webphoto_include_once( 'class/handler/mime_handler.php' );
webphoto_include_once( 'class/handler/syno_handler.php' );
webphoto_include_once( 'class/handler/vote_handler.php' );
webphoto_include_once( 'class/handler/tag_handler.php' );
webphoto_include_once( 'class/handler/p2t_handler.php' );
webphoto_include_once( 'class/handler/photo_tag_handler.php' );
webphoto_include_once( 'class/handler/maillog_handler.php' );
webphoto_include_once( 'class/webphoto/gmap_info.php' );
webphoto_include_once( 'class/webphoto/gmap.php' );
webphoto_include_once( 'class/webphoto/image_info.php' );
webphoto_include_once( 'class/webphoto/show_photo.php' );
webphoto_include_once( 'class/webphoto/tag.php' );
webphoto_include_once( 'class/webphoto/mail_unlink.php' );
webphoto_include_once( 'class/webphoto/photo_build.php' );
webphoto_include_once( 'class/webphoto/photo_delete.php' );
webphoto_include_once( 'class/webphoto/cat_selbox.php' );
webphoto_include_once( 'class/admin/photo_form.php' );
webphoto_include_once( 'class/admin/photomanager.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_photomanager::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>