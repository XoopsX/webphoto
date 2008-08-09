<?php
// $Id: mail_parse.php,v 1.2 2008/08/09 22:40:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//=========================================================
// class webphoto_lib_mail_parse
// base on mailbbs's pop.php
//=========================================================
class webphoto_lib_mail_parse
{
	var $_CHARSET_LOCAL = null;
	var $_CHARSET_FROM  = null;

	var $_result  = null;
	var $_bodies  = null;
	var $_attach  = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_mail_parse()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_mail_parse();
	}
	return $instance;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_charset_local( $val) 
{
	$this->_CHARSET_LOCAL = $val;
}

//---------------------------------------------------------
// parse_mail
//---------------------------------------------------------
function parse_mail( $mail_text ) 
{
	$this->_result = null; 
	$this->_bodies = null;

	$attach_arr = array();

	list( $head, $body ) = $this->split_mime_part( $mail_text );
	if ( empty($head) || empty($body) ) {
		return false;
	}

	$date = $this->parse_date( $head ) ;

	$multi_part = $this->split_multipart( $head, $body ) ;

// normal text without multipart
	if ( !is_array($multi_part) ) {
		$multi_part[0] = $mail_text;
	}

	foreach ($multi_part as $part ) 
	{
		$ret = $this->parse_multi_part( $part );
		if ( ( $ret == 2 ) && is_array($this->_attach) ) {
			$attach_arr[] = $this->_attach;
		}
	}

	$this->_result = array( 
		'mail_to'      => $this->parse_mail_to( $head ) ,
		'mail_from'    => $this->parse_mail_from( $head ) ,
		'reply_to'     => $this->parse_reply_to( $head ) ,
		'return_path'  => $this->parse_return_path( $head ) , 
		'mailer'       => $this->parse_mailer( $head ) ,
		'charset'      => $this->parse_charset( $head ) ,
		'subject'      => $this->parse_subject( $head ) ,
		'date'         => $date ,
		'datetime'     => $this->build_datetime( $date ) ,
		'attaches'     => $attach_arr ,
		'bodies'       => $this->_bodies ,
	);

	return true;
}

function get_result() 
{
	return $this->_result;
}

function parse_mailer( $head ) 
{
// X-Mailer: XOOPS Cube
	if ( eregi("(X-Mailer|X-Mail-Agent):[ \t]*([^\r\n]+)", $head, $match) ) {
		return $match[2];
	}
	return null;
}

function parse_charset( $head ) 
{
// Content-Type: text/plain; charset="iso-2022-jp"
	if ( preg_match("/charset[\s]*=[\s]*(['\"]?)([^\r\n]+)\\1/", $head, $match) ) {
		$charset = $match[2];
		if ( $charset ) {
			$this->_CHARSET_FROM = $charset;
		}
		return $charset;
	}
	return null ;
}

function parse_date( $head ) 
{
// Date: Fri, 1 Aug 2008 10:44:39 +0900 (JST)
	if ( eregi("Date:[ \t]*([^\r\n]+)", $head, $match) ) {
		return $match[1];
	}
	return null;
}

function build_datetime( $date ) 
{
	$time = strtotime( $date );
	if ( $time <= 0 ) {
		$time = time();
	}
	return $time;
}

function parse_subject( $head ) 
{
// Subject: abc
	if (preg_match("/\nSubject:[ \t]*(.+?)(\n[\w-_]+:|$)/is", $head, $match)) {
		$subject = str_replace(array("\r","\n"),"",$match[1]);
		$subject = $this->remove_space_between_encode( $subject );
		$subject = $this->decode_if_mime_b( $subject );
		$subject = $this->decode_if_mime_q( $subject );
		$subject = $this->convert_to_local( $subject );
		return trim( $subject );
	}
	return null ;
}

function decode_if_mime_b( $str ) 
{
	$MIME_B_FORMAT_EREG = "(.*)=\?iso-[^\?]+\?B\?([^\?]+)\?=(.*)";
	while (eregi( $MIME_B_FORMAT_EREG, $str, $regs )) {
		$str = $regs[1] . base64_decode($regs[2]) . $regs[3];
	}
	return $str;
}

function decode_if_mime_q( $str ) 
{
	$MIME_Q_FORMAT_EREG = "(.*)=\?iso-[^\?]+\?Q\?([^\?]+)\?=(.*)";
	while (eregi( $MIME_Q_FORMAT_EREG, $str, $regs )) {
		$str = $regs[1] . quoted_printable_parse($regs[2]) . $regs[3];
	}
	return $str;
}

function parse_mail_to( $head ) 
{
// To: user@exsample.com
	if (preg_match("/(?:^|\n|\r)To:[ \t]*([^\r\n]+)/i", $head, $match)){
		return $match[1];
	}
	return null ;
}

function parse_mail_from( $head ) 
{
// From: user@exsample.com
	if (eregi("From:[ \t]*([^\r\n]+)", $head, $match)) {
		return $this->parse_mail_addr( $match[1] );
	}
	return null;
}

function parse_reply_to( $head ) 
{
// Reply-To: user@exsample.com
	if (eregi("Reply-To:[ \t]*([^\r\n]+)", $head, $match)) {
		return $this->parse_mail_addr( $match[1] );
	}
	return null;
}

function parse_return_path( $head ) 
{
// Return-Path: user@exsample.com
	if (eregi("Return-Path:[ \t]*([^\r\n]+)", $head, $match)) {
		return $this->parse_mail_addr( $match[1] );
	}
	return null;
}

function parse_mail_addr( $addr ) 
{
	$MAIL_FORMAT_EREG = "[-!#$%&\'*+\\./0-9A-Z^_`a-z{|}~]+@[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+";
	$match = array();
	if (eregi( $MAIL_FORMAT_EREG, $addr, $match )) {
		return $match[0];
	}
	return null;
}

function parse_content_type( $head ) 
{
// Content-Type: image/jpeg;
	if ( eregi("Content-type: *([^;\r\n]+)", $head, $match)) {
		return trim( $match[1] );
	}
	return null;
}

//---------------------------------------------------------
// multipart
//---------------------------------------------------------
function split_multipart( $head, $body ) 
{
	$part = null;
	if (eregi("\nContent-type:.*multipart/",$head)) {
		eregi('boundary="([^"]+)"', $head, $boureg);
		$body = str_replace($boureg[1], urlencode($boureg[1]), $body);
		$part = split("\r\n--".urlencode($boureg[1])."-?-?",$body);

		if (eregi('boundary="([^"]+)"', $body, $boureg2)) {//multipart/altanative
			$body = str_replace($boureg2[1], urlencode($boureg2[1]), $body);
			$body = eregi_replace("\r\n--".urlencode($boureg[1])."-?-?\r\n","",$body);
			$part = split("\r\n--".urlencode($boureg2[1])."-?-?",$body);
		}
	}
	return $part;
}

function parse_multi_part( $part ) 
{
	list( $head, $body ) = $this->parse_multi_head_body( $part );
	if ( empty($head) || empty($body) ) {
		return 0;	// no action
	}

	$charset = $this->parse_charset( $head );
	$type    = $this->parse_content_type( $head ) ;

// maybe boundary
	if ( empty($type) ) {
		return 0;	// no action
	}

	if ( $this->is_multi_text( $type ) ) {
		$this->parse_multi_text( $head, $body, $charset, $type ) ;
		return 1;	// body
	}

	$this->parse_multi_attach( $head, $body, $charset, $type );
	return 2;	// attach
}

function is_multi_text( $type ) 
{
	if ( preg_match("/text/", $type ) ) {
		return true;
	}
	return false;
}

function parse_multi_head_body( $multi ) 
{
	list( $head, $body_1 ) = $this->split_mime_part( $multi );
	$body = ereg_replace( "\r\n\.\r\n$", "", $body_1 );
	return array( $head, $body );
}

function parse_multi_text( $head, $body, $charset, $type ) 
{
	$html  = null ;
	$plane = null ;

	$text = $this->decode_body( $head, $body ) ;
	$text = trim( $this->convert_to_local($text) );
	$text = $this->remove_boundary( $text );

	if ( preg_match("/html/", $type ) || 
	     preg_match('#^<html>.*</html>$#is', $text) ) {

		$html  = $text ;	
		$plane = preg_replace('#<head>.*</head>#is', '', $html);
		$plane = strip_tags( $plane );
	}

	$this->_bodies = array(
		'text'    => $text ,
		'html'    => $html ,
		'plane'   => $plane ,
		'charset' => $charset,
		'type'    => $type ,
	);
	return true;
}

function decode_body( $head, $body ) 
{
	if (eregi("Content-Transfer-Encoding:.*base64", $head)) {
		$body = base64_decode($body);
	}
	if (eregi("Content-Transfer-Encoding:.*quoted-printable", $head)) {
		$body = quoted_printable_parse($body);
	}
	return $body ;
}

function parse_body_html_plane( $text, $type ) 
{
	if ( preg_match("/html/", $type ) || 
	     preg_match('#^<html>.*</html>$#is', $text) ) {

		$this->_text_html = $text ;	
		$text = preg_replace('#<head>.*</head>#is', '', $text);
		$text = strip_tags($text);
		$this->_text_html_plane = $text ;	
	}
	return true;
}

function remove_boundary( $text ) 
{
	return ereg_replace("Content-type: multipart/appledouble;[[:space:]]boundary=(.*)","",$text);
}

function remove_space_between_encode( $text ) 
{
	return preg_replace("/\?=[\s]+?=\?/", "?==?", $text);
}

//---------------------------------------------------------
// attach
//---------------------------------------------------------
function parse_multi_attach( $head, $body, $charset, $type ) 
{
	$this->_attach = null;

	$filename = $this->parse_attach_filename( $head );
	$content  = $this->decode_attach_content( $head, $body ) ;

	if ( empty( $content ) ) {
		return false;
	}

	$this->_attach = array(
		'filename' => $filename,
		'content'  => $content ,
		'charset'  => $charset ,
		'type'     => $type ,
	);

	return true ;
}

function parse_attach_filename( $head ) 
{
	$filename = $this->decode_attach_filename( $head ) ;
	return $this->convert_to_local($filename);
}

function decode_attach_filename( $head ) 
{
	$filename = null ;

	if (eregi("name=\"?([^\"\n]+)\"?",$head, $filereg)) {
		$filename = trim($filereg[1]);
		$filename = $this->remove_space_between_encode( $filename );
		$filename = $this->decode_if_mime_b( $filename );
	}

	return $filename ;
}

function decode_attach_content( $head, $body ) 
{
	$val = null;
	if ( eregi("Content-Transfer-Encoding:.*base64", $head) ) {
		$val = base64_decode($body);
	}
	return $val;
}

function split_mime_part( $data ) 
{
	$head = null;
	$body = null;
	$data = preg_replace("/(\x0D\x0A|\x0D|\x0A)/","\r\n",$data);
	$part = split("\r\n\r\n", $data, 2);

	if ( isset($part[0]) && isset($part[1]) ) {
		$head = ereg_replace("\r\n[\t ]+", " ", $part[0]);
		$body = $part[1];
	}

	return array( $head, $body );
}

//---------------------------------------------------------
// multibyte
//---------------------------------------------------------
function set_internal_encoding()
{
	if ( function_exists('iconv_get_encoding') && 
	     function_exists('iconv_set_encoding') ) {

		$current = iconv_get_encoding( 'internal_encoding' );
		$ret = iconv_set_encoding( 'internal_encoding', $this->_CHARSET_LOCAL );
		if ( $ret === false ) {
			iconv_set_encoding( 'internal_encoding', $current );
		}
	}

	if ( function_exists('mb_internal_encoding') ) {

		$current = mb_internal_encoding();
		$ret = mb_internal_encoding( $this->_CHARSET_LOCAL );
		if ( $ret === false ) {
			mb_internal_encoding( $current );
		}
	}
}

function convert_to_local( $str ) 
{
	if ( $this->_CHARSET_FROM && function_exists('iconv') ) {
		return iconv( $this->_CHARSET_FROM, $this->_CHARSET_LOCAL.'//IGNORE' , $str );

	} elseif (function_exists('mb_convert_encoding')) {
		if ( $this->_CHARSET_FROM ) {
			$charser_from = $this->_CHARSET_FROM;
		} else {
			$charser_from = 'auto' ;
		}
		return mb_convert_encoding($str, $this->_CHARSET_LOCAL, $charser_from );

	}

	return $str;
}

// --- class end ---
}

?>