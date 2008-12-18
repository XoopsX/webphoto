<?php
// $Id: download.php,v 1.2 2008/12/18 13:23:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-12 K.OHWADA
// header_file.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header_file.php' );
webphoto_include_once( 'class/main/download.php' );

//=========================================================
// main
//=========================================================
$webphoto_manage =& webphoto_main_download::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$webphoto_manage->main();
exit();

?>