<?php
// $Id: mail_retrieve.php,v 1.5 2011/11/13 05:24:37 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// main/include_mail_recv.php
// 2011-05-01 K.OHWADA
// main/include_submit.php
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
webphoto_include_once( 'main/include_mail_recv.php' );
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