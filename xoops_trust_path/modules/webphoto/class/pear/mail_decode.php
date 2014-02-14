<?php
// $Id: mail_decode.php,v 1.3 2011/11/12 17:17:47 ohwada Exp $

//=========================================================
// mail decode with pear
// 2011-05-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// webphoto_lib_mail
//---------------------------------------------------------

//=========================================================
// class pear_mail_decode
//=========================================================
class webphoto_pear_mail_decode
{
	var $_mail_class;

	var $_CHARSET_LOCAL = 'utf-8';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_pear_mail_decode()
{
	$this->_mail_class =& webphoto_lib_mail::getInstance();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_pear_mail_decode();
	}
	return $instance;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_charset_local( $val) 
{
	$this->_CHARSET_LOCAL = strtolower( $val );
}

//---------------------------------------------------------
// decode
//---------------------------------------------------------
function decode( $input )
{
	$ctype_primary   = '';
	$ctype_secondary = '';
	$charset  = '';
	$headers  = null;
	$parts    = null;
	$body     = null;
	$attaches = null;

	$params['include_bodies'] = true;
	$params['decode_bodies']  = true;
	$params['decode_headers'] = true;

	$decoder = new Mail_mimeDecode($input);
	$structure = $decoder->decode($params);

	if( isset( $structure->ctype_parameters['charset'] ) ) {
		$charset = $structure->ctype_parameters['charset'];
	}

	if ( isset($structure->ctype_primary) ) {
		$ctype_primary = $structure->ctype_primary ;
	}

	if ( isset($structure->ctype_secondary) ) {
		$ctype_secondary = $structure->ctype_secondary;
	}

	if ( isset($structure->headers) ) {
		$headers = $structure->headers ;
	}

	if ( isset($structure->parts) ) {
		$parts = $structure->parts ;
	}

	switch( $ctype_primary )
	{
		case 'text':
			$body = $this->decode_single( $structure, $charset );
			break;

		case 'multipart':
			list( $body, $attaches ) =
				$this->decode_multipart( $parts );
			if ( empty($charset) && isset($body['charset']) ) {
				$charset = $body['charset'];
			}
			break;

		default:
	} 

// header encoding
	$headers = $this->decode_headers( $headers, $charset );

	$param = array(
		'charset'  => $charset,
		'headers'  => $headers,
		'body'     => $body,
		'attaches' => $attaches,
	);

	return $param;
}

function decode_headers( $headers, $charset )
{
	if ( !is_array($headers) || !is_array($headers) ) {
		return false;
	}

	$from_name = '';
	$from_addr = '';
	$to_name = '';
	$to_addr = '';

	$param = array();

	foreach ( $headers as $k => $v )
	{
// Received, etc
		if ( is_array($v) ) {
			continue;
		}

		$name = str_replace( '-', '_', $k );
		$param[ $name ] = $this->convert_encoding( $v, $charset );
	}

	if ( isset($param['from']) ) {
		list( $param['from_name'], $param['from_addr'] )
			 = $this->parse_name_addr( $param['from'] );
	}

	if ( isset($param['to']) ) {
		list( $param['to_name'], $param['to_addr'] )
			 = $this->parse_name_addr( $param['to'] );
	}

	if ( isset($param['reply_to']) ) {
		list( $reply_name, $param['reply_to'] )
			 = $this->parse_name_addr( $param['reply_to'] );
	}

	if ( isset($param['return_path']) ) {
		list( $return_name, $param['return_path'] )
			 = $this->parse_name_addr( $param['return_path'] );
	}

	return $param;
}

function parse_name_addr( $str ) 
{
	return $this->_mail_class->parse_name_addr( $str );
}

function decode_single( $structure, $charset )
{
	$ctype_secondary = '';
	$body_source     = '';
	$body_converted  = '';
	$html_charset    = '';

	if ( isset($structure->ctype_secondary) ) {
		$ctype_secondary = $structure->ctype_secondary;
	}

	if ( isset($structure->body) ) {
		$body_source = $structure->body ;
	}

	switch ( $ctype_secondary )
	{
		case 'plain':
			$body_converted = $this->convert_encoding( $body_source, $charset );
			break;

		case 'html':
			list ( $body_converted, $html_charset ) =			
				$this->convert_encoding_html( $body_source, $charset );
			break;

		default:
	}

	$param = array(
    	'ctype_primary'   => 'text',
    	'ctype_secondary' => $ctype_secondary ,
		'charset'         => $charset ,
		'html_charset'    => $html_charset ,
		'body'            => $body_source,
		'body_converted'  => $body_converted,
	);
	return $param;
}

function decode_multipart( $parts )
{
	$first    = false;
	$body     = null;
	$attaches = array();

	if ( !is_array($parts) || !is_array($parts) ) {
		return array( $body, $texts, $attaches );
	}

	foreach( $parts as $part )
	{
// remove garbage
// Mail_mimeDecode might interpret the last blank lines as plane text
		if ( !isset($part->headers) || !count($part->headers) ) {
			continue;
		}

		if ( isset($part->ctype_primary) ) {
			$ctype_primary = strtolower( $part->ctype_primary );
		} else {
			continue;
		}

		switch( $ctype_primary )
		{
			case "text":
				$text = $this->decode_part_text( $part );
				if ( is_array($text) ) {
// assume as the text body in first
					if ( $first ) {
						$attaches[] = $text;
					} else {
						$body  = $text;
						$first = true;
					}
				}
				break;

// multipart/alternative
// android gmail
			case 'multipart':
				continue;
				break;

			default:
				$attaches[] = $this->decode_part_attach( $part );
				break;
		}
	}

	return array( $body, $attaches );
}

function decode_part_text( $part )
{
	$param   = $this->decode_part_common( $part );
	$body    = $param['body'];
	$charset = $param['charset'] ;

// for iPhone : no context text/plane
	if ( ! $this->check_text( $body ) ) {
		return false;
	}

	switch ( $param['ctype_secondary'] )
	{
		case 'plain':
			$param['body_converted'] = $this->convert_encoding( $body, $charset );
			break;

		case 'html':
			list ( $param['body_converted'], $param['html_charset'] ) =			
				$this->convert_encoding_html( $body, $charset );
			break;
	}

	return $param;
}

function decode_part_attach( $part )
{
	return $this->decode_part_common( $part );
}

function decode_part_common( $part )
{
	$ctype_primary   = '';
	$ctype_secondary = '';
	$name            = '';
	$charset         = '';
	$body            = '';

	if ( isset($part->ctype_primary) ) {
		$ctype_primary = $part->ctype_primary ;
	}

	if ( isset($part->ctype_secondary) ) {
		$ctype_secondary = $part->ctype_secondary;
	}

	if ( isset( $part->ctype_parameters['name'] ) ) {
		$name = $part->ctype_parameters['name'];
	}

	if ( isset( $part->ctype_parameters['charset'] ) ) {
		$charset = $part->ctype_parameters['charset'];
	}

	if ( isset($part->body) ) {
		$body = $part->body;
	}

	$param = array(
    	'ctype_primary'   => $ctype_primary ,
    	'ctype_secondary' => $ctype_secondary ,
		'name'            => $name ,
		'charset'         => $charset ,
		'body'            => $body,
	);
	return $param;
}

function check_text( $text )
{
	$text = str_replace("\n", '', $text );
	$text = str_replace("\r", '', $text );
	if ( strlen($text) > 0 ) {
		return true;
	}
	return false;
}

function convert_encoding_html( $source, $charset )
{
	$charset_html = '';
	$converted    = '';

// <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	$pattern = "/<meta.*Content-Type.*charset=(.*)['|\"]>/i";

	if ( preg_match($pattern, $source, $matches ) ) {
		$charset_html = strtolower( $matches[1] );
		$converted    = $this->convert_encoding( $source, $charset_html );
	} else {
		$converted = $this->convert_encoding( $source, $charset );
	}

	return array( $converted, $charset_html );
}

//---------------------------------------------------------
// multibyte
//---------------------------------------------------------
function set_internal_encoding()
{
	if ( function_exists('mb_internal_encoding') ) {

		$current = mb_internal_encoding();
		$ret = mb_internal_encoding( $this->_CHARSET_LOCAL );
		if ( $ret === false ) {
			mb_internal_encoding( $current );
		}
	}
}

function convert_encoding( $str, $charset ) 
{
	$charset = strtolower($charset);

// no action when same charset
	if ( $charset == $this->_CHARSET_LOCAL ) {
		return $str;
	}

	$flag_iconv = true;

	switch ( $charset )
	{
		case 'iso-2022-jp':
			$charset = 'iso-2022-jp-ms';
			break;

		case 'shift_jis':
			$charset = 'sjis-win';
			break;

		case 'euc-jp':
			$charset = 'eucjp-win';
			break;

		case '':
			$charset = 'auto';
			$flag_iconv = false;
			break;
	}

	if (function_exists('mb_convert_encoding')) {
		return mb_convert_encoding( $str, $this->_CHARSET_LOCAL, $charset );
	}
	if ( $flag_iconv && function_exists('iconv') ) {
		return iconv( $charset, $this->_CHARSET_LOCAL.'//TRANSLIT' , $str );
	}

	return $str;
}

// === claaa end ===
}
?>