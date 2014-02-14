<?php
// $Id: mail_check.php,v 1.2 2008/08/25 19:28:05 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// supported i-phone
//---------------------------------------------------------

//=========================================================
// class webphoto_mail_check
// base on mailbbs's pop.php
//=========================================================
class webphoto_mail_check
{
	var $_config_class;
	var $_user_handler;
	var $_mime_class;
	var $_utility_class;

	var $_reject_msg_arr = array();
	var $_result;

	var $_BODY_REJECT_MAXBYTE = 200; 	// 200 char
	var $_BODY_MAXBYTE   = 1000;	// 1000 char
	var $_ATTACH_MAXBYTE = 1049000;	// 1 MB

	var $_DENY_MAILER_PREG = '/(Oshirase|Microsoft\s*CDO|Mail\s*Magic|Easy\s*DM|Friend\s*Mailer|Extra\s*Japan|The\s*Bat|BSMTP|magmag|Blat|Douhou|DM\s*Mailer|IM2001|=\?ISO\-2202\-JP\?Q\?Q`dsV!0Ji;]Id9\?=)/i';

	var $_DENY_MAIL_FROM_ARRAY = array('163.com','bigfoot.com','boss.com','mine.nu','51444.tv','nyan_nyan_cat_2004@yahoo.co.jp','motto.zapto.org','i-towns.net','ori-g.net','jewelry.polty.cc','birabira4u.com','wecl-online.com');

	var $_REMOVE_UNDERLINE_EREG = "[_]{25,}";

	var $_AD_WORD_ARRAY = array(
		"http://auction.msn.co.jp/" ,
		"Do You Yahoo!?" ,
		"Yahoo! BB is Broadband by Yahoo!" ,
		"http://bb.yahoo.co.jp/" ,
		"http://messenger.msn.co.jp" ,
	);

// for i-phone
	var $_ALLOW_BODY_US_ASCII = true;
	var $_US_ASCII = 'us-ascii';

	var $_FLAG_STRICT       = true;

	var $_MAILTO            = null;
	var $_DENY_TITLE_PREG   = null;
	var $_DENY_BODY_PREG    = null;
	var $_REMOVE_WORD_ARRAY = null;

	var $_allowed_mimes    = null;
	var $_allowed_exts     = null;
	var $_allowed_charsets = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_mail_check( $dirname )
{
	$this->set_deny_title_preg( _WEBPHOTO_MAIL_DENY_TITLE_PREG );
	$this->set_deny_body_preg(  _WEBPHOTO_MAIL_DENY_TITLE_PREG );
	$this->push_ad_word_array( _WEBPHOTO_MAIL_AD_WORD_1 );
	$this->push_ad_word_array( _WEBPHOTO_MAIL_AD_WORD_2 );

	$this->_config_class  =& webphoto_config::getInstance( $dirname );
	$this->_user_handler  =& webphoto_user_handler::getInstance( $dirname );
	$this->_mime_class    =& webphoto_mime::getInstance( $dirname );
	$this->_utility_class =& webphoto_lib_utility::getInstance();

	$cfg_fsize        = $this->_config_class->get_by_name( 'fsize' );
	$cfg_mail_addr    = $this->_config_class->get_by_name( 'mail_addr' );
	$cfg_mail_charset = $this->_config_class->get_by_name( 'mail_charset' );

	$this->set_attach_maxbyte( $cfg_fsize ) ;
	$this->set_mailto( $cfg_mail_addr );
	$this->set_allowed_charset_list( $cfg_mail_charset ) ;

}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_mail_check( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_flag_strict( $val )
{
	$this->_FLAG_STRICT = (bool)$val;
}

function set_body_maxbyte( $val ) 
{
	$this->_BODY_MAXBYTE = intval($val);
}

function set_attach_maxbyte( $val ) 
{
	$this->_ATTACH_MAXBYTE = intval($val);
}

function set_charset_local( $val ) 
{
	$this->_CHARSET_LOCAL = $val;
}

function set_mailto( $val ) 
{
	$this->_MAILTO = $val;
}

function set_deny_title_preg( $val ) 
{
	$this->_DENY_TITLE_PREG = $val;
}

function set_deny_body_preg( $val ) 
{
	$this->_DENY_BODY_PREG = $val;
}

function push_ad_word_array( $str ) 
{
	if ( $str ) {
		array_push( $this->_AD_WORD_ARRAY , $str );
	}
}

function set_remove_word_array( $arr ) 
{
	$this->_REMOVE_WORD_ARRAY = $arr;
}

function set_allowed_mimes( $val ) 
{
	$this->_allowed_mimes = $val;
}

function set_allowed_exts( $val ) 
{
	$this->_allowed_exts = $val;
}

function set_allowed_charset_list( $val ) 
{
	$list = explode( '|', $val );
	$arr  = array();
	foreach ( $list as $v ) {
		$v = trim($v);
		if ( $v ) {
			$arr[] = strtolower( $v );
		}
	}
	$this->_allowed_charsets = $arr;
}

//---------------------------------------------------------
// parse_mail
//---------------------------------------------------------
function set_mail_groups( $groups )
{
	if ( !is_array(  $groups ) ) {
		$groups = array( $groups );
	}

	list ( $allowed_mimes, $allowed_exts ) 
		= $this->_mime_class->get_allowed_mimes_by_groups( $groups );

	$this->set_allowed_mimes( $allowed_mimes ) ;
	$this->set_allowed_exts(  $allowed_exts ) ;

}

function check_mail( $param ) 
{
	$this->clear_reject_msg() ;

	$this->_result = $param;
	$return_code   = true;

	$mail_to      = $param['mail_to'] ;
	$mail_from    = $param['mail_from'] ;
	$reply_to     = $param['reply_to'] ;
	$return_path  = $param['return_path'] ; 
	$mailer       = $param['mailer'] ;
	$charset      = $param['charset'] ;
	$date         = $param['date'] ;
	$subject      = $param['subject'] ;
	$attaches     = $param['attaches'] ;
	$bodies       = $param['bodies'] ;

	if ( ! $this->check_mailto( $mail_to ) ) {
		$return_code = false;
		if ( $this->_FLAG_STRICT ) {
			return false;
		}
	}

	if ( ! $this->check_mailer( $mailer ) ) {
		$return_code = false;
		if ( $this->_FLAG_STRICT ) {
			return false;
		}
	}

	if ( ! $this->check_charset( $charset ) ) {
		$return_code = false;
		if ( $this->_FLAG_STRICT ) {
			return false;
		}
	}

	if ( ! $this->check_subject( $subject ) ) {
		$return_code = false;
		if ( $this->_FLAG_STRICT ) {
			return false;
		}
	}

	if ( ! $this->check_mail_from( $mail_from ) ) {
		$return_code = false;
		if ( $this->_FLAG_STRICT ) {
			return false;
		}
	}

	if ( ! $this->check_mail_from( $reply_to ) ) {
		$return_code = false;
		if ( $this->_FLAG_STRICT ) {
			return false;
		}
	}

	if ( ! $this->check_mail_from( $return_path ) ) {
		$return_code = false;
		if ( $this->_FLAG_STRICT ) {
			return false;
		}
	}

	if ( ! $this->check_bodies( $bodies ) ) {
		$return_code = false;
		if ( $this->_FLAG_STRICT ) {
			return false;
		}
	}

	if ( !is_array($attaches) ) {
		$this->set_reject_msg( 'no attach file' );
		$return_code = false;
		if ( $this->_FLAG_STRICT ) {
			return false;
		}
	}

	$mail_from = $this->select_mail_from( $param );

	if ( ! $this->check_exists_email( $mail_from ) ) {
		$return_code = false;
		if ( $this->_FLAG_STRICT ) {
			return false;
		}
	}

	list( $subject, $rotate ) = $this->parse_subject_rotate( $subject ) ;

	$param['mail_from'] = $mail_from ;
	$param['subject']   = $subject ;
	$param['rotate']    = $rotate ;
	$param['body']      = $this->proofread_bodies( $bodies ) ;
	$param['attaches']  = $this->check_attaches( $attaches );

	$this->_result = $param;

	return $return_code ;
}

function get_result() 
{
	return $this->_result;
}

function check_mailto( $mailto ) 
{
	$pattern = "/".quotemeta( $this->_MAILTO )."/";
	if ( $mailto && !preg_match( $pattern, $mailto ) ) {
		$this->set_reject_msg('not allow mailto : '. $mailto );
		return false;
	}
	return true;
}

function check_mailer( $mailer ) 
{
	if ( $mailer && $this->_DENY_MAILER_PREG ){
		if ( preg_match( $this->_DENY_MAILER_PREG, $mailer ) ) {
			$this->set_reject_msg('not allow mailer : '. $mailer );
			return false;
		}
	}
	return true;
}

function check_charset( $charset, $flag_us_ascii=false ) 
{
// no check if not detect charset
	if ( empty($charset) ){
		return true;
	}

// no check if not set config
	if ( !is_array($this->_allowed_charsets) || !count($this->_allowed_charsets) ) {
		return true;
	}

// ok if in config
	if ( in_array( strtolower($charset), $this->_allowed_charsets ) ) {
		return true;
	}

// ok if us_ascii
	if ( $flag_us_ascii && ( strtolower($charset) == $this->_US_ASCII ) ) {
		return true;
	}

	$this->set_reject_msg('not allow charset : '. $charset );
	return false;
}

function check_subject( $subject ) 
{
	if ( $subject && $this->_DENY_TITLE_PREG ) {
		if (preg_match( $this->_DENY_TITLE_PREG, $subject )) {
			$this->set_reject_msg('not allow word in subject : '. $subject );
			return false;
		}
	}
	return true;
}

function check_mail_from( $mail_from ) 
{
	if ( empty($mail_from) ) {
		return true;
	}
	for ($i=0; $i<count( $this->_DENY_MAIL_FROM_ARRAY ); $i++)
	{
		if (eregi( $this->_DENY_MAIL_FROM_ARRAY[$i], $mail_from )) {
			$this->set_reject_msg('not allow from mail : '. $mail_from );
			return false;
		}
	}
	return true;
}

function check_exists_email( $email )
{
	$row = $this->_user_handler->get_cached_row_by_email( $email );
	if ( !is_array($row) ) {
		$this->set_reject_msg('not allow from mail : '. $email );
		return false;
	}
	return true;
}

function parse_subject_rotate( $subject ) 
{
	$rotate = null;

	if ( $subject && preg_match("/(.*)(?:(r|l)@)$/i", $subject, $match )) {
		$subject = rtrim($match[1]);
		$rl      = strtolower($match[2]);
		if ( $rl == 'r' ) {
			$rotate = 'rot90';
		} elseif ( $rl == 'l' ) {	
			$rotate = 'rot270';
		}
	}

	return array( $subject, $rotate );
}

function select_mail_from( $param ) 
{
	if ( $param['mail_from'] ) {
		return $param['mail_from'] ;
	} elseif ( $param['reply_to'] ) {
		return $param['reply_to'] ;
	} elseif ( $param['return_path'] ) {
		return $param['return_path'] ;
	}
	return null;
}

//---------------------------------------------------------
// check body
//---------------------------------------------------------
function check_bodies( $bodies ) 
{
	if ( !is_array($bodies) || !count($bodies) ) {
		return true;
	}

	foreach ( $bodies as $body) {
		$ret = $this->check_single_body( $body );
		if ( !$ret ) {
			return false;
		}
	}

	return true;
}

function check_single_body( $body ) 
{
	$text    = $body['text'] ;
	$html    = $body['html'] ;
	$plane   = $body['plane'] ;
	$charset = $body['charset'] ;
	$type    = $body['type'] ;

	if ( ! $this->check_charset( $charset, $this->_ALLOW_BODY_US_ASCII ) ) {
		return false;
	}

	if ( ! $this->check_body_text( $text ) ) {
		return false;
	}

	if ( ! $this->check_body_text( $html ) ) {
		return false;
	}

	if ( ! $this->check_body_text( $plane ) ) {
		return false;
	}

	return true;
}

function check_body_text( $text ) 
{
	if ( $text && isset( $this->_DENY_BODY_PREG ) && $this->_DENY_BODY_PREG ) {
		if ( preg_match( $this->_DENY_BODY_PREG, $text )) {
			$msg  = 'not allow word in body';
			$msg .= $this->shorten_text( $text, $this->_BODY_REJECT_MAXBYTE );
			$this->set_reject_msg( $msg );
			return false;
		}
	}
	return true;
}

function shorten_text( $text, $max ) 
{
	if (strlen($text) > $max) {
		$text = substr($text, 0, $max)."...";
	}
	return $text;
}

//---------------------------------------------------------
// proofread body
//---------------------------------------------------------
function proofread_bodies( $bodies ) 
{
	if ( !is_array($bodies) || !count($bodies) ) {
		return null;
	}

	$str = '';
	foreach ( $bodies as $body) {
		$str .= $this->proofread_single_body( $body ) ;
	}

	return $str;
}

function proofread_single_body( $body ) 
{
	if ( $body['plane'] ) {
		$text = $body['plane'];
	} else {
		$text = $body['text'] ;
	}

	$text = $this->replace_return_code( $text );
	$text = $this->remove_tel( $text );
	$text = $this->remove_underline( $text );
	$text = $this->remove_word( $text );
	$text = $this->remove_del_reg( $text );
	$text = $this->shorten_text( $text, $this->_BODY_MAXBYTE ) ;
	return $text;
}

function replace_return_code( $text ) 
{
	$text = str_replace("\r\n", "\r",$text);
	$text = str_replace("\r", "\n",$text);
	$text = preg_replace("/\n{2,}/", "\n\n", $text);
	return $text;
}

function remove_tel( $text ) 
{
	$TEL_FORMAT_EREG = "([[:digit:]]{11})|([[:digit:]\-]{13})";
	return eregi_replace( $TEL_FORMAT_EREG, "", $text );
}

function remove_underline( $text ) 
{
	return eregi_replace( $this->_REMOVE_UNDERLINE_EREG, "", $text);
}

function remove_word( $text ) 
{
	if ( is_array( $this->_AD_WORD_ARRAY) ) {
		foreach (  $this->_AD_WORD_ARRAY as $delstr ) {
			$text = str_replace($delstr, "", $text);
		}
	}
	return $text;
}

function remove_del_reg( $text ) 
{
	if ( is_array( $this->_REMOVE_WORD_ARRAY ) ) {
		foreach (  $this->_REMOVE_WORD_ARRAY as $delstr)
		{
			if ($delstr) {
				$text = preg_replace($delstr, "", $text);
			}
		}
	}
	return $text;
}

//---------------------------------------------------------
// attach
//---------------------------------------------------------
function check_attaches( $attaches ) 
{
	$arr = array();

	foreach ( $attaches as $attach ) 
	{
		$filename = $attach['filename'] ;
		$content  = $attach['content'] ;
		$charset  = $attach['charset'] ;
		$type     = $attach['type'] ;

		if ( $filename ) {
			$ext = $this->_utility_class->parse_ext( $filename );
		} else {
			list($main, $ext) = explode("/", $type);
			$filename = time() .'.'. $ext;
		}

		$msg = array();
		if ( ! $this->check_attach_ext( $ext ) ) {
			$msg[] = 'not allow ext : '. $ext ;
		}
		if ( ! $this->check_attach_mimetype( $type ) ) {
			$msg[] = 'not allow mime : '. $type ;
		}
		if ( ! $this->check_attach_maxbyte( $content ) ) {
			$msg[] = 'over maxbyte';
		}

		$attach['ext']    = $ext;
		$attach['reject'] = implode( $msg, "\n" );
		$arr[] = $attach ;
	}

	return $arr;
}

function check_attach_ext( $ext ) 
{
	if ( $ext && in_array( $ext, $this->_allowed_exts ) ) {
		return true;
	}
	return false;
}

function check_attach_mimetype( $type ) 
{
	if ( $type && in_array( $type, $this->_allowed_mimes ) ) {
		return true;
	}
	return false;
}

function check_attach_maxbyte( $content ) 
{
	if ( strlen( $content ) > $this->_ATTACH_MAXBYTE ) {
		return false;
	}
	return true;
}

//---------------------------------------------------------
// msg
//---------------------------------------------------------
function clear_reject_msg() 
{
	$this->_reject_msg_arr = array();
}

function set_reject_msg( $mail ) 
{
	$this->_reject_msg_arr[] = $mail;
}

function get_reject_msgs() 
{
	return $this->_reject_msg_arr;
}

// --- class end ---
}

?>