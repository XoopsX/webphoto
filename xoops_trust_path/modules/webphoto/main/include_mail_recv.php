<?php
// $Id: include_mail_recv.php,v 1.1 2011/11/13 05:25:32 ohwada Exp $

//=========================================================
// webphoto module
// 2011-05-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// change file name
// include_mail.php -> include_mail_recv.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// caller main/mail_retrieve.php i_post.php 
//   admin/maillog_manager.php
//   bin/retrieve.php
//---------------------------------------------------------

//---------------------------------------------------------
// PEAR
//---------------------------------------------------------
if ( !defined('_WEBPHOTO_PEAR_LOADED') ) {
	define('_WEBPHOTO_PEAR_LOADED', '1' );

	$config_class =& webphoto_config::getInstance( WEBPHOTO_DIRNAME );
	$pear_path    =  $config_class->get_by_name('pear_path');

	if ( empty($pear_path) || !is_dir($pear_path)) {
		$pear_path = WEBPHOTO_TRUST_PATH.'/PEAR';
	}

	set_include_path( get_include_path() . PATH_SEPARATOR . $pear_path );
}

require_once 'Net/POP3.php';
require_once 'Mail/mimeDecode.php';

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'class/pear/mail_pop3.php' );
webphoto_include_once( 'class/pear/mail_decode.php' );
webphoto_include_once( 'class/pear/mail_parse.php' );

webphoto_include_once( 'class/lib/mail.php' );

webphoto_include_once( 'class/edit/mail_check.php' );
webphoto_include_once( 'class/edit/mail_photo.php' );
webphoto_include_once( 'class/edit/mail_unlink.php' );
webphoto_include_once( 'class/edit/mail_retrieve.php' );

?>