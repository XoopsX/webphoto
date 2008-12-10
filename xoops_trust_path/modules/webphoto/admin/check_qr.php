<?php
// $Id: check_qr.php,v 1.1 2008/12/10 19:10:04 ohwada Exp $

//=========================================================
// webphoto module
// 2008-12-06 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
define("QRCODE_DATA_PATH", WEBPHOTO_TRUST_PATH.'/class/qrcode/qrcode_data' );

webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/qrcode/qrcode_img.php' );
webphoto_include_once( 'class/admin/check_qr.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_check_qr::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>