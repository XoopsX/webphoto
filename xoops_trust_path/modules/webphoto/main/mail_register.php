<?php
// $Id: mail_register.php,v 1.5 2011/11/12 17:17:47 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// class/lib/mail.php
// 2011-05-16 K.OHWADA
// class/edit/mail_check.php
// 2010-04-28 K.OHWADA
// class/edit/item_create.php
// 2008-01-10 K.OHWADA
// class/edit/xxx
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header.php' );
webphoto_include_once( 'class/xoops/user.php' );
webphoto_include_once( 'class/lib/gtickets.php' );
webphoto_include_once( 'class/lib/element.php' );
webphoto_include_once( 'class/lib/form.php' );
webphoto_include_once( 'class/lib/mail.php' );
webphoto_include_once( 'class/handler/mime_handler.php' );
webphoto_include_once( 'class/handler/user_handler.php' );
webphoto_include_once( 'class/webphoto/mime.php' );
webphoto_include_once( 'class/edit/item_create.php' );
webphoto_include_once( 'class/edit/base.php' );
webphoto_include_once( 'class/edit/form.php' );
webphoto_include_once( 'class/edit/icon_build.php' );
webphoto_include_once( 'class/edit/mail_register_form.php' );
webphoto_include_once( 'class/main/mail_register.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_mail_register::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );

// exit if execute edit
$manage->check_action();

include( XOOPS_ROOT_PATH.'/header.php' ) ;

$manage->print_form();

include( XOOPS_ROOT_PATH.'/footer.php' ) ;
exit();

?>