<?php
// $Id: index.php,v 1.9 2009/04/19 11:39:45 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-04-19 K.OHWADA
// class/lib/file_md5.php
// 2008-04-10 K.OHWADA
// include/version.php
// 2008-01-25 K.OHWADA
// jodconverter.php
// 2000-01-10 K.OHWADA
// xpdf.php
// 2008-11-16 K.OHWADA
// server_info.php
// 2008-11-08 K.OHWADA
// workdir.php
// 2008-10-01 K.OHWADA
// added player_handler.php
// 2008-08-24 K.OHWADA
// added photo_handler.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'include/version.php' );
webphoto_include_once( 'class/inc/workdir.php' );
webphoto_include_once( 'class/lib/server_info.php' );
webphoto_include_once( 'class/lib/gd.php' );
webphoto_include_once( 'class/lib/imagemagick.php' );
webphoto_include_once( 'class/lib/netpbm.php' );
webphoto_include_once( 'class/lib/ffmpeg.php' );
webphoto_include_once( 'class/lib/xpdf.php' );
webphoto_include_once( 'class/lib/jodconverter.php' );
webphoto_include_once( 'class/lib/dir.php' );
webphoto_include_once( 'class/lib/file_md5.php' );
webphoto_include_once( 'class/handler/player_handler.php' );
webphoto_include_once( 'class/handler/photo_handler.php' );
webphoto_include_once( 'class/webphoto/jodconverter.php' );
webphoto_include_once( 'class/admin/checkconfigs.php' );
webphoto_include_once( 'class/admin/update_check.php' );
webphoto_include_once( 'class/admin/index.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_index::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>