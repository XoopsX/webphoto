<?php
// $Id: mail_pop3.php,v 1.1 2011/05/10 03:02:30 ohwada Exp $

//=========================================================
// webphoto module
// 2011-05-01 K.OHWADA
//=========================================================

//=========================================================
// class webphoto_pear_mail_pop3
//=========================================================
class webphoto_pear_mail_pop3
{
// set param
	var $_HOST = null;
	var $_USER = null;
	var $_PASS = null;

	var $_PORT = '110';	// pop3
	var $_MAX_MAIL = 10;

	var $_mail_arr  = array();
	var $_msg_arr   = array();
	var $_error_arr = array();

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_pear_mail_pop3()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_pear_mail_pop3();
	}
	return $instance;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_host( $val )
{
	$this->_HOST = $val;
}

function set_user( $val )
{
	$this->_USER = $val;
}

function set_pass( $val )
{
	$this->_PASS = $val;
}

//---------------------------------------------------------
// pop mail
//---------------------------------------------------------
function recv_mails()
{
	$this->clear_mails();
	$this->clear_msgs();
	$this->clear_errors();

	if ( empty($this->_HOST) || empty($this->_USER) || empty($this->_PASS) ) {
		$this->set_error( 'not set param' );
		return -1;
	}

	$hostinfo = array();
	if(eregi('^(.+):([0-9]+)$', $this->_HOST, $hostinfo)) {
		$host = $hostinfo[1];
		$port = $hostinfo[2];
	} else {
		$host = $this->_HOST;
		$port = $this->_PORT;
	}

	$pop = new Net_POP3();
	$ret = $pop->connect($host, $port);
	if ( !$ret ) {
		$this->set_error( 'not connect' );
		return -1;
	}

	$ret = $pop->login($this->_USER, $this->_PASS);
	if ( $ret !== true ) {
		$this->set_error( $ret );
		$pop->disconnect();
		return -1;
	}

	$num = $pop->numMsg();

// no mail
	if ( $num == 0 ) {
		$pop->disconnect();
		return 0;
	}

// set limit
	if ( $num > $this->_MAX_MAIL ) {
		 $num = $this->_MAX_MAIL;
	}

// get mails
	for ( $i=1; $i<=$num; $i++ ) 
	{
		$this->set_mail( $pop->getMsg( 1 ) );
		$pop->deleteMsg( 1 );
	}

	$pop->disconnect();
	return $num;
}

//---------------------------------------------------------
// msg
//---------------------------------------------------------
function clear_mails() 
{
	$this->_mail_arr = array();
}

function set_mail( $mail ) 
{
	$this->_mail_arr[] = $mail;
}

function get_mails() 
{
	return $this->_mail_arr;
}

function clear_msgs() 
{
	$this->_msg_arr = array();
}

function set_msg( $msg ) 
{
	$this->_msg_arr[] = $msg;
}

function get_msgs() 
{
	return $this->_msg_arr;
}

function clear_errors() 
{
	$this->_error_arr = array();
}

function set_error( $err ) 
{
	$this->_error_arr[] = $err;
}

function get_errors() 
{
	return $this->_error_arr;
}

// --- class end ---
}

?>