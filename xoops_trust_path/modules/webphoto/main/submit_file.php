<?php
// $Id: submit_file.php,v 1.5 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-01-10 K.OHWADA
// header_submit.php
// 2008-01-04 K.OHWADA
// editor.php
// 2008-11-08 K.OHWADA
// imagemagick.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header_submit.php' );
webphoto_include_once( 'class/edit/misc_form.php' );
webphoto_include_once( 'class/main/submit_file.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_submit_file::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );

// exit if execute
$manage->check_action();

include( XOOPS_ROOT_PATH.'/header.php' ) ;

$manage->print_form();

include( XOOPS_ROOT_PATH.'/footer.php' ) ;
exit();

?>