<?php
// $Id: mail_pop3.php,v 1.3 2011/11/04 04:01:48 ohwada Exp $

//=========================================================
// mail pop3 woth pear
// 2011-05-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-03 K.OHWADA
// Function ereg() is deprecated
//---------------------------------------------------------

//=========================================================
// class pear_mail_pop3
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

function set_max_mail( $val )
{
	$this->_MAX_MAIL = intval($val);
}

//---------------------------------------------------------
// pop mail
//---------------------------------------------------------
function recv_mails()
{
	$this->clear_mails();
	$this->clear_errors();

	if ( empty($this->_HOST) || empty($this->_USER) || empty($this->_PASS) ) {
		$this->set_error( 'not set param' );
		return false;
	}

// Function ereg() is deprecated
// example.com:110
	if(preg_match('/^(.+):([0-9]+)$/', $this->_HOST, $hostinfo)) {

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
		return false;
	}

	$ret = $pop->login($this->_USER, $this->_PASS);
	if ( $ret !== true ) {
		$this->set_error( $ret );
		$pop->disconnect();
		return false;
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
		$this->set_mail( $pop->getMsg( $i ) );
		$pop->deleteMsg( $i );
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