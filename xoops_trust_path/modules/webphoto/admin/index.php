<?php
// $Id: index.php,v 1.2 2008/08/25 19:28:05 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// added photo_handler.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/handler/photo_handler.php' );
webphoto_include_once( 'class/admin/checkconfigs.php' );
webphoto_include_once( 'class/admin/index.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_index::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>