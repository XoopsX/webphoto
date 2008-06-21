<?php
// $Id: checkgd2.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_language( 'admin.php' );

//=========================================================
// main
//=========================================================
xoops_cp_header();

restore_error_handler() ;
error_reporting( E_ALL ) ;

if( imagecreatetruecolor(200, 200) ) {
	echo _AM_WEBPHOTO_GD2SUCCESS ;
}

xoops_cp_footer();

?>