<?php
// $Id: batch.php,v 1.6 2009/05/17 08:58:59 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-05-05 K.OHWADA
// batch_form.php -> photo_form.php
// 2008-01-10 K.OHWADA
// header_edit.php
// 2008-11-08 K.OHWADA
// imagemagick.php
// 2008-08-01 K.OHWADA
// added photo_create.php
// 2008-07-01 K.OHWADA
// added ffmpeg.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header_edit.php' );
webphoto_include_once( 'class/edit/photo_form.php' );
webphoto_include_once( 'class/edit/misc_form.php' );
webphoto_include_once( 'class/admin/batch.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_batch::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>