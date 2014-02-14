<?php
// $Id: mail_pop.php,v 1.1 2008/08/08 04:39:14 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//=========================================================
// class webphoto_lib_mail_pop
// base on mailbbs's pop.php
//=========================================================
class webphoto_lib_mail_pop
{
// set param
	var $_HOST = null;
	var $_USER = null;
	var $_PASS = null;

	var $_PORT     = '110';	// pop3
	var $_TIMEOUT  = 10;
	var $_MAX_MAIL = 10;

	var $_fp ;
	var $_mail_arr  = array();
	var $_msg_arr   = array();
	var $_error_arr = array();

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_mail_pop()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_mail_pop();
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

	$fp = fsockopen( $this->_HOST, $this->_PORT, $err, $errno, $this->_TIMEOUT );
	if ( !$fp ) {
		$this->set_error( $err );
		return -1;
	}
	$this->_fp = $fp;

	$ret = $this->recv();
	if ( !$ret ) {
		fclose( $this->_fp );
		return -1;
	}

	$ret = $this->send_recv("USER ".$this->_USER);
	if ( !$ret ) {
		fclose( $this->_fp );
		return -1;
	}

	$ret = $this->send_recv("PASS ".$this->_PASS);
	if ( !$ret ) {
		fclose( $this->_fp );
		return -1;
	}

	$data = $this->send_recv("STAT");
	if ( !$data ) {
		fclose( $this->_fp );
		return -1;
	}

	sscanf($data, '+OK %d %d', $num, $size);
	$num = intval($num);

// no mail
	if ( $num == 0 ) {
		$this->send_recv("QUIT");
		fclose( $this->_fp );
		return 0;
	}

// set limit
	if ( $num > $this->_MAX_MAIL ) {
		 $num = $this->_MAX_MAIL;
	}

// get mails
	for ( $i=1; $i<=$num; $i++ ) 
	{
		$this->send("RETR $i");
		$body = $this->recv_body();
		if ( !$body ) {
			fclose( $this->_fp );
			return -1;
		}

		$this->set_mail( $body );
		$ret = $this->send_recv("DELE $i");
		if ( !$ret ) {
			fclose( $this->_fp );
			return -1;
		}
	}

	$this->send_recv("QUIT");

	fclose( $this->_fp );
	return $num;
}

function send_recv( $cmd ) 
{
	$this->send( $cmd );
	return $this->recv();
}

function send( $cmd ) 
{
	$this->set_msg( $cmd );
	fputs($this->_fp, $cmd."\r\n");
}

function recv() 
{
	$buf = fgets($this->_fp, 512);
	$this->set_msg( $buf );
	if (substr($buf, 0, 3) == '+OK') {
		return $buf;
	}
	$this->set_error( $buf );
	return false;
}

function recv_body() 
{
	$line = fgets($this->_fp, 512);
	$dat = '';

	// read until '.' 
	while ( !ereg("^\.\r\n",$line) ) 
	{
		$line = fgets( $this->_fp, 512 );
		$dat .= $line;
	}

	$this->set_msg( $dat );
	return $dat;
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