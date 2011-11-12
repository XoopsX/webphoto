<?php
// $Id: mail_send.php,v 1.3 2011/11/12 17:17:47 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// webphoto_lib_mail
//---------------------------------------------------------

//=========================================================
// class webphoto_lib_mail_send
//=========================================================
class webphoto_lib_mail_send extends webphoto_lib_error
{
	var $_mail_class;

	var $_xoops_sitename;
	var $_xoops_adminmail;
	var $_msg_array = array();

	var $_LANG_ERR_NO_TO_EMAIL = 'Not Set Email Address';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_mail_send()
{
	$this->webphoto_lib_error();

	$this->_mail_class =& webphoto_lib_mail::getInstance();

	$this->_xoops_sitename    = $this->get_xoops_sitename();
	$this->_xoops_adminmail   = $this->get_xoops_adminmail();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_mail_send();
	}
	return $instance;
}

//---------------------------------------------------------
// send email
//---------------------------------------------------------
function send( $param )
{
	$to_emails   = isset($param['to_emails'])  ? $param['to_emails']  : null;
	$users       = isset($param['users'])      ? $param['users']      : null;
	$subject     = isset($param['subject'])    ? $param['subject']    : null;
	$body        = isset($param['body'])       ? $param['body']       : null;
	$tags        = isset($param['tags'])       ? $param['tags']       : null;
	$debug       = isset($param['debug'])      ? $param['debug']      : false;
	$from_name   = isset($param['from_name'])  ? $param['from_name']  : $this->_xoops_sitename;
	$from_email  = isset($param['from_email']) ? $param['from_email'] : $this->_xoops_adminmail;

	if ( empty($to_emails) && empty($users) ) {
		$this->set_error( $this->_LANG_ERR_NO_TO_EMAIL );
		return false;
	}

	$this->clear_errors();
	$this->clear_msg_array();

// mail start
	$mailer =& getMailer();
	$mailer->reset();
	$mailer->setFromName(  $from_name );
	$mailer->setFromEmail( $from_email );
	$mailer->setSubject( $subject );
	$mailer->setBody( $body );
	$mailer->useMail();

	if ( $to_emails ) {
		$mailer->setToEmails( $to_emails );
	}

	if ( is_array($users) && count($users) ) {
		$mailer->setToUsers( $users );
	}

	if ( is_array($tags) && count($tags) ) {
		$mailer->assign( $tags );
	}

	$ret = $mailer->send( $debug );
	if ( !$ret ) {
		$this->set_error( $mailer->getErrors( false ) );
		return false;
	}

	$this->set_msg( $mailer->getSuccess( false ) );
	return true;
}

function get_valid_mail_addr( $addr ) 
{
	return $this->_mail_class->get_valid_addr( $addr );
}

//---------------------------------------------------------
// msg
//---------------------------------------------------------
function clear_msg_array()
{
	$this->_msg_array = array();
}

function get_msg_array()
{
	return $this->_msg_array;
}

function set_msg( $msg, $flag_highlight=false )
{
// array type
	if ( is_array($msg) ) {
		$arr = $msg;

// string type
	} else {
		$arr = $this->str_to_array( $msg, "\n" );
		if ( $flag_highlight ) {
			$arr2 = array();
			foreach ( $arr as $m ) {
				$arr2[] = $this->highlight( $m );
			}
			$arr = $arr2;
		}
	}

	foreach ( $arr as $m ) 
	{
		$m = trim($m);
		if ( $m ) {
			$this->_msg_array[] = $m;
		}
	}
}

//---------------------------------------------------------
// XOOPS system
//---------------------------------------------------------
function get_xoops_sitename()
{
	global $xoopsConfig;
	return $xoopsConfig['sitename'];
}

function get_xoops_adminmail()
{
	global $xoopsConfig;
	return $xoopsConfig['adminmail'];
}

// --- class end ---
}

?>