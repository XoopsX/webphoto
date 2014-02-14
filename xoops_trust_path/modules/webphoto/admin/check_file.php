<?php
// $Id: check_file.php,v 1.1 2009/12/24 06:33:24 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/lib/dir.php' );
webphoto_include_once( 'class/lib/file_check.php' );
webphoto_include_once( 'class/admin/check_file.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_check_file::getInstance( 
	WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>