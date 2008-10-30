<?php
// $Id: player_manager.php,v 1.1 2008/10/30 00:25:51 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// xoops system files
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH.'/class/snoopy.php';

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/handler/player_handler.php' );
webphoto_include_once( 'class/handler/flashvar_handler.php' );
webphoto_include_once( 'class/lib/remote_file.php' );
webphoto_include_once( 'class/lib/xml.php' );
webphoto_include_once( 'class/webphoto/flash_player.php' );
webphoto_include_once( 'class/webphoto/playlist.php' );
webphoto_include_once( 'class/admin/player_form.php' );
webphoto_include_once( 'class/admin/player_manager.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_player_manager::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>