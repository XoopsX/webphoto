<?php
// $Id: checkgd2.php,v 1.2 2008/12/10 19:08:56 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-07 K.OHWADA
// webphoto_admin_checkgd2
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'class/admin/checkgd2.php' );

webphoto_include_language( 'admin.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_checkgd2::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();
xoops_cp_header();

?>