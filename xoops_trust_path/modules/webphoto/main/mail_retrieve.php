<?php
// $Id: mail_retrieve.php,v 1.3 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-01-10 K.OHWADA
// class/edit/xxx
// 2008-11-08 K.OHWADA
// imagemagick.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header_submit.php' );
webphoto_include_once( 'class/lib/mail_parse.php' );
webphoto_include_once( 'class/lib/mail_pop.php' );
webphoto_include_once( 'class/edit/mail_check.php' );
webphoto_include_once( 'class/edit/mail_photo.php' );
webphoto_include_once( 'class/edit/mail_unlink.php' );
webphoto_include_once( 'class/edit/mail_retrieve.php' );
webphoto_include_once( 'class/main/mail_retrieve.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_mail_retrieve::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );

// exit if false
$manage->check();

include XOOPS_ROOT_PATH . "/header.php" ;

echo $manage->main();

include( XOOPS_ROOT_PATH . "/footer.php" ) ;
exit();

?>