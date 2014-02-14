<?php
// $Id: invite.php,v 1.3 2011/11/12 17:17:47 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// class/lib/mail.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// xoops system files
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php' ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/d3/mail_template.php' );
webphoto_include_once( 'class/lib/mail.php' );
webphoto_include_once( 'class/lib/mail_send.php' );
webphoto_include_once( 'class/admin/invite.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_invite::getInstance( 
	WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>