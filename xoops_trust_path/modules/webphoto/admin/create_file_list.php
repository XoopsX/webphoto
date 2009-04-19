<?php
// $Id: create_file_list.php,v 1.1 2009/04/19 16:07:42 ohwada Exp $

//=========================================================
// webphoto module
// 2009-04-19 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/lib/dir.php' );
webphoto_include_once( 'class/lib/file_check.php' );
webphoto_include_once( 'class/admin/create_file_list.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_create_file_list::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>