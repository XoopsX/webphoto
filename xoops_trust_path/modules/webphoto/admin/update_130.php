<?php
// $Id: update_130.php,v 1.1 2009/03/20 04:21:42 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-15 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header_edit.php' );
webphoto_include_once( 'class/admin/update_130.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_update_130::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>