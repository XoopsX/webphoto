<?php
// $Id: item_manager.php,v 1.5 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-01-10 K.OHWADA
// header_edit.php
// 2008-01-04 K.OHWADA
// editor.php
// 2008-11-16 K.OHWADA
// show_image.php
// 2008-11-08 K.OHWADA
// imagemagick.php flash_log.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header_edit.php' );
webphoto_include_once( 'class/edit/photo_form.php' );
webphoto_include_once( 'class/edit/misc_form.php' );
webphoto_include_once( 'class/edit/flashvar_edit.php' );
webphoto_include_once( 'class/edit/flashvar_form.php' );
webphoto_include_once( 'class/admin/item_form.php' );
webphoto_include_once( 'class/admin/item_manager.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_admin_item_manager::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manage->main();
exit();

?>