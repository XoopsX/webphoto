<?php
// $Id: redothumbs.php,v 1.5 2009/01/24 07:10:39 ohwada Exp $

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
// 2008-08-24 K.OHWADA
// added maillog_handler.php
// 2008-07-01 K.OHWADA
// added exif.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// xoops system files
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php' ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header_edit.php' );
webphoto_include_once( 'class/admin/redo_form.php' );
webphoto_include_once( 'class/admin/redothumbs.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_redothumbs::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>