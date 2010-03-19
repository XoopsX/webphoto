<?php
// $Id: photomanager.php,v 1.7 2010/03/19 00:23:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-03-18 K.OHWADA
// class/edit/item_create.php
// 2010-01-10 K.OHWADA
// class/webphoto/tag.php -> tag_build.php
// 2009-01-25 K.OHWADA
// remove gmap.php
// 2008-01-10 K.OHWADA
// class/edit/xxx
// 2008-11-16 K.OHWADA
// show_image.php
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
webphoto_include_once( 'class/handler/mime_handler.php' );
webphoto_include_once( 'class/handler/syno_handler.php' );
webphoto_include_once( 'class/handler/vote_handler.php' );
webphoto_include_once( 'class/handler/tag_handler.php' );
webphoto_include_once( 'class/handler/p2t_handler.php' );
webphoto_include_once( 'class/handler/photo_tag_handler.php' );
webphoto_include_once( 'class/handler/maillog_handler.php' );
webphoto_include_once( 'class/webphoto/cat_selbox.php' );
webphoto_include_once( 'class/webphoto/show_image.php' );
webphoto_include_once( 'class/webphoto/show_photo.php' );
webphoto_include_once( 'class/webphoto/tag_build.php' );
webphoto_include_once( 'class/edit/item_create.php' );
webphoto_include_once( 'class/edit/mail_unlink.php' );
webphoto_include_once( 'class/edit/search_build.php' );
webphoto_include_once( 'class/edit/item_delete.php' );
webphoto_include_once( 'class/admin/photo_form.php' );
webphoto_include_once( 'class/admin/photomanager.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_photomanager::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>