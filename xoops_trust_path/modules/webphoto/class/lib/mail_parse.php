<?php
// $Id: mail_parse.php,v 1.3 2008/08/25 19:28:05 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// supported i-phone
// supported docomo gps
//---------------------------------------------------------

//=========================================================
// class webphoto_lib_mail_parse
// base on mailbbs's pop.php
//=========================================================
class webphoto_lib_mail_parse
{
	var $_CHARSET_LOCAL = null;
	var $_CHARSET_FROM  = null;

	var $_result    = null;
	var $_bodies    = null;
	var $_attach    = null;
	var $_gps       = null;

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
	$this->_result    = null; 
	$this->_bodies    = array();
	$this->_gps       = null ;

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
		'gps'          => $this->_gps ,
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
// normal
//   Content-Type: text/plain; charset="iso-2022-jp"
// i-phone
//   Content-Type: text/plain; charset=us-ascii; format=flowed

	if ( preg_match("/charset[\s]*=[\s]*(['\"]?)([^;\r\n]+)\\1/", $head, $match) ) {
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
		list( $subject, $flag_b ) = $this->decode_if_mime_b( $subject );
		list( $subject, $flag_q ) = $this->decode_if_mime_q( $subject );
		if ( !$flag_b && !$flag_q ) {
			$subject = $this->convert_to_local( $subject );
		}
		return trim( $subject );
	}
	return null ;
}

function decode_if_mime_b( $str ) 
{
	$flag = false ;
	$MIME_B_FORMAT_EREG = "(.*)=\?(iso-[^\?]+)\?B\?([^\?]+)\?=(.*)";
	while (eregi( $MIME_B_FORMAT_EREG, $str, $regs )) {
		$flag   = true ;
		$decode = $this->convert_to_local( base64_decode( $regs[3] ), $regs[2] );
		$str    = $regs[1] . $decode . $regs[4];
	}
	return array( $str, $flag );
}

function decode_if_mime_q( $str ) 
{
	$flag = false ;
	$MIME_Q_FORMAT_EREG = "(.*)=\?(iso-[^\?]+)\?Q\?([^\?]+)\?=(.*)";
	while (eregi( $MIME_Q_FORMAT_EREG, $str, $regs )) {
		$flag   = true ;
		$decode = $this->convert_to_local( quoted_printable_parse( $regs[3] ), $regs[2] );
		$str    = $regs[1] . $decode . $regs[3];
	}
	return array( $str, $flag );
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

	$gps = $this->parse_gps_docomo( $text );
	if ( isset($gps['flag']) && $gps['flag'] ) {
		$this->_gps = $gps ;
	}

	$this->_bodies[] = array(
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
// gps
//---------------------------------------------------------
function parse_gps_docomo( $data ) 
{
// http://www.nttdocomo.co.jp/service/imode/make/content/gps/index.html
// http://www.docomo.co.jp/gps.cgi?lat=%2B35.00.35.600&lon=%2B135.41.35.600&geo=wgs84&x-acc=3
// http://docomo.ne.jp/cp/map.cgi?lat=%2B35.00.35.600&lon=%2B135.41.35.600&geo=wgs84 

	$flag       = false ;
	$gmap_lat   = null ;
	$gmap_lon   = null ;
	$docomo_lat = null ;
	$docomo_lon = null ;

	$pattern = '/http:\/\/.*?docomo.*jp\/.*lat=(%2B[\d\.]+)&lon=(%2B[\d\.]+)/i';

	if ( preg_match( $pattern, $data, $match ) ) {
		$flag = true ;
		$docomo_lat = rawurldecode( $match[1] );
		$docomo_lon = rawurldecode( $match[2] );
		$gmap_lat   = $this->parse_gps_docomo_lonlat( $docomo_lat );
		$gmap_lon   = $this->parse_gps_docomo_lonlat( $docomo_lon );
	}

	$arr = array(
		'flag'             => $flag ,
		'gmap_latitude'    => $gmap_lat,
		'gmap_longitude'   => $gmap_lon,
		'docomo_latitude'  => $docomo_lat,
		'docomo_longitude' => $docomo_lon,
	);

	return $arr;
}

function parse_gps_docomo_lonlat( $str )
{
	$arr = explode( '.', $str );
	$fig = 0;
	if ( isset( $arr[0] ) ) {
		$fig += floatval( $arr[0] );
	}
	if ( isset( $arr[1] ) ) {
		$fig += floatval( $arr[1] ) / 60 ;
	}
	if ( isset( $arr[2] ) ) {
		$fig += floatval( $arr[2] ) / 3600 ;
	}
	if ( isset( $arr[3]) ) {
		$fig += floatval( $arr[3] ) / 3600000 ;
	}
	return $fig;
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
		list( $filename, $flag_b ) = $this->decode_if_mime_b( $filename );
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

function convert_to_local( $str, $charset=null ) 
{
	$charset_iconv = null ;
	$charset_mb    = null ;

	if ( $charset ) {
		$charset_iconv = $charset ;
	} elseif ( empty($charset) && $this->_CHARSET_FROM ) {
		$charset_iconv = $this->_CHARSET_FROM;
	}

	if ( $charset ) {
		$charset_mb = $charset ;
	} elseif ( $this->_CHARSET_FROM ) {
		$charser_mb = $this->_CHARSET_FROM;
	} else {
		$charser_mb = 'auto' ;
	}

	if ( $charset_iconv && function_exists('iconv') ) {
		return iconv( $charset_iconv, $this->_CHARSET_LOCAL.'//IGNORE' , $str );

	} elseif (function_exists('mb_convert_encoding')) {
		return mb_convert_encoding( $str, $this->_CHARSET_LOCAL, $charser_mb );

	}

	return $str;
}

// --- class end ---
}

?>