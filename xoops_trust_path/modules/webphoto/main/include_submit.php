<?php
// $Id: include_submit.php,v 1.4 2011/11/13 05:24:37 ohwada Exp $

//=========================================================
// webphoto module
// 2011-11-11 K.OHWADA
//=========================================================

//---------------------------------------------------------
// caller main/header_submit.php admin/header_edit.php bin/retrieve.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'class/d3/mail_template.php' );
webphoto_include_once( 'class/lib/mail.php' );
webphoto_include_once( 'class/lib/mail_send.php' );
webphoto_include_once( 'class/webphoto/mail_send.php' );
webphoto_include_once( 'class/edit/submit.php' );

?>