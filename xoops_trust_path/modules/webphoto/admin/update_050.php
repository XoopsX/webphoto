<?php
// $Id: update_050.php,v 1.3 2010/03/19 00:23:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-03-18 K.OHWADA
// class/edit/item_create.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/handler/player_handler.php' );
webphoto_include_once( 'class/handler/mime_handler.php' );
webphoto_include_once( 'class/webphoto/mime.php' );
webphoto_include_once( 'class/edit/item_create.php' );
webphoto_include_once( 'class/admin/update_050.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_update_050::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>