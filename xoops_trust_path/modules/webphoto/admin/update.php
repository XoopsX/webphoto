<?php
// $Id: update.php,v 1.3 2008/11/01 23:53:08 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/handler/player_handler.php' );
webphoto_include_once( 'class/handler/photo_handler.php' );
webphoto_include_once( 'class/admin/update_check.php' );
webphoto_include_once( 'class/admin/update.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_update::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>