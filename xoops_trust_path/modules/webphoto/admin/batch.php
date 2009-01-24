<?php
// $Id: batch.php,v 1.5 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
webphoto_include_once( 'class/admin/batch_form.php' );
webphoto_include_once( 'class/admin/batch.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_batch::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>