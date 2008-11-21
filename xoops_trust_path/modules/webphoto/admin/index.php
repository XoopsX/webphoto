<?php
// $Id: index.php,v 1.5 2008/11/21 07:56:57 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
webphoto_include_once( 'class/inc/workdir.php' );
webphoto_include_once( 'class/lib/server_info.php' );
webphoto_include_once( 'class/handler/player_handler.php' );
webphoto_include_once( 'class/handler/photo_handler.php' );
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