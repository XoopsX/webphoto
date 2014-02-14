<?php
// $Id: submit_imagemanager.php,v 1.10 2011/11/13 05:24:37 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// new main/header_submit_imagemanager.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header_submit_imagemanager.php' );
webphoto_include_once( 'class/edit/imagemanager_form.php' );
webphoto_include_once( 'class/main/submit_imagemanager.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_submit_imagemanager::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manage->main();
exit();

?>