<?php
// $Id: include_mail.php,v 1.1 2011/05/10 02:59:15 ohwada Exp $

//=========================================================
// webphoto module
// 2011-05-01 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// PEAR
//---------------------------------------------------------
if ( !defined('_WEBPHOTO_PEAR_LOADED') ) {
	define('_WEBPHOTO_PEAR_LOADED', '1' );

	$config    =& webphoto_inc_config::getSingleton( WEBPHOTO_DIRNAME );
	$pear_path =  $config->get_by_name('pear_path');

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

webphoto_include_once( 'class/edit/mail_check.php' );
webphoto_include_once( 'class/edit/mail_photo.php' );
webphoto_include_once( 'class/edit/mail_unlink.php' );
webphoto_include_once( 'class/edit/mail_retrieve.php' );

?>