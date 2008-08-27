<?php
// $Id: base.php,v 1.2 2008/08/27 03:58:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// php xxx.php  pass
// php xxx.php -pass=pass [ -limit=0 -offset=0 -abc ]
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_bin_base
// base on happy_linux_bin_base
//=========================================================
class webphoto_bin_base
{
	var $_preload_class ;

	var $_DIRNAME;
	var $_TRUST_DIRNAME;

// constant
	var $_X_MAILER = 'XOOPS';

// test parameter
	var $_mode       = '';
	var $_flag_print = false;
	var $_flag_write = true;
	var $_flag_chmod = false;

// command option
	var $_pass   = null;
	var $_limit  = 10;
	var $_offset = 0;

	var $_FLAG_PRINT_WEB = true;
	var $_FLAG_WRITE_WEB = true;
	var $_FLAG_CHMOD_WEB = true;
	var $_LIMIT_WEB      = 10;

	var $_FLAG_PRINT_COMMAND = false;
	var $_FLAG_WRITE_COMMAND = true;
	var $_FLAG_CHMOD_COMMAND = false;
	var $_LIMIT_COMMAND      = 0;	// unlimited

// xoops parameter
	var $_sitename  = null;
	var $_adminmail = null;
	var $_CHARSET   = null ;

// command parameter
	var $_opt_arr = null;

// result file
	var $_SUB_DIR    = 'cache';
	var $_GOTO_ADMIN = 'goto admin index';

	var $_filename = null;
	var $_file_admin_index;

	var $_mail_to    = null;
	var $_mail_title = null;
	var $_mail_level = 0;

	var $_DEBUG_D3 = false ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_bin_base( $dirname , $trust_dirname )
{
	$this->_init( $dirname , $trust_dirname );

	$this->_DIRNAME       = $dirname;
	$this->_TRUST_DIRNAME = $trust_dirname;

}

function _init( $dirname , $trust_dirname )
{
	$xoops_class =& webphoto_xoops_base::getInstance();
	$LANGUAGE = $xoops_class->get_language();

// read lang file
	$d3_class =& webphoto_d3_optional::getInstance();
	$d3_class->init( $dirname , $trust_dirname );
	$d3_class->set_language( $LANGUAGE );
	$d3_class->include_once_language( 'main.php',  $this->_DEBUG_D3 );
	$d3_class->include_once_language( 'extra.php', $this->_DEBUG_D3 );

// set db charset
	$xoops_class->set_db_charset();
	$xoops_conf = $xoops_class->get_system_config();

// mail param
	$this->set_sitename(  $xoops_conf['sitename'] );
	$this->set_adminmail( $xoops_conf['adminmail'] );

	if ( defined('_CHARSET') ) {
		$this->set_charset( _CHARSET );
		$this->set_internal_encoding( _CHARSET );
	}

	if ( defined('_WEBPHOTO_MB_LANGUAGE') ) {
		$this->set_mail_language( _WEBPHOTO_MB_LANGUAGE );
	}

	$this->_file_admin_index = 'modules/'. $dirname .'/admin/index.php';
}

//---------------------------------------------------------
// preload class
//---------------------------------------------------------
function preload_init()
{
	$this->_preload_class =& webphoto_d3_preload::getInstance();
	$this->_preload_class->init( $this->_DIRNAME , $this->_TRUST_DIRNAME );
}

function preload_constant()
{
	$arr = $this->_preload_class->get_preload_const_array();
	if ( !is_array($arr) || !count($arr) ) {
		return true;	// no action
	}

	foreach( $arr as $k => $v )
	{
		$local_name = strtoupper( '_' . $k );

// array type
		if ( strpos($k, 'array_') === 0 ) {
			$temp = $this->str_to_array( $v, '|' );
			if ( is_array($temp) && count($temp) ) {
				$this->$local_name = $temp;
			}

// string type
		} else {
			$this->$local_name = $v;
		}
	}

}

function str_to_array( $str, $pattern )
{
	$arr1 = explode( $pattern, $str );
	$arr2 = array();
	foreach ( $arr1 as $v )
	{
		$v = trim($v);
		if ($v == '') { continue; }
		$arr2[] = $v;
	}
	return $arr2;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_sitename($val)
{
	$this->_sitename = $val;
}

function set_adminmail($val)
{
	$this->_adminmail = $val;
}

function set_charset($val)
{
	$this->_CHARSET = $val;
}

//---------------------------------------------------------
// env_param
//---------------------------------------------------------
function set_env_param()
{
// web
// in whtasnew, set REQUEST_METHOD, because suppress notice
	if ( isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] ) {
		$this->set_env_param_web();

// command line
	} else {
		$this->set_env_param_cmd();
	}
}

function check_pass($pass)
{
	if ( $pass && ( $pass == $this->_pass ) ) {
		return true;
	}
	return false;
}

function set_env_param_web()
{
	$this->_mode       = 'web';
	$this->_flag_print = $this->_FLAG_PRINT_WEB;
	$this->_flag_write = $this->_FLAG_WRITE_WEB;
	$this->_flag_chmod = $this->_FLAG_CHMOD_WEB;
	$this->_limit      = $this->_LIMIT_WEB;

	$this->_opt_arr =& $_GET;

	if ( $this->isset_opt('pass') ) {
		$this->_pass = $this->get_opt('pass');
	}

	if ( $this->isset_opt('limit') ) {
		$this->_limit = $this->get_opt('limit');
	}
}

function set_env_param_cmd()
{
	$this->_mode       = 'command';
	$this->_flag_print = $this->_FLAG_PRINT_COMMAND;
	$this->_flag_write = $this->_FLAG_WRITE_COMMAND;
	$this->_flag_chmod = $this->_FLAG_CHMOD_COMMAND;
	$this->_limit      = $this->_LIMIT_COMMAND;

	$this->set_cmd_option();

	if ( $this->isset_opt('pass') ) {
		$this->_pass = $this->get_opt('pass');
	} elseif ( isset($_SERVER['argv'][1]) ) {
		$this->_pass = $_SERVER['argv'][1];
	}

	if ( $this->isset_opt('limit') ) {
		$this->_limit = $this->get_opt('limit');
	}

	if ( $this->isset_opt('offset') ) {
		$this->_offset = $this->get_opt('offset');
	}
}

function set_cmd_option()
{
	$arr = array();

	if ( $_SERVER['argc'] > 1 ) {
		for( $i=1; $i<$_SERVER['argc']; $i++ )
		{
			if ( preg_match('/\-(.*)=(.*)/', $_SERVER['argv'][$i], $matches) ) {
				$arr[ $matches[1] ] = $matches[2];
			} elseif ( preg_match('/\-(.*)/', $_SERVER['argv'][$i], $matches) ) {
				$arr[ $matches[1] ] = true;
			}
		}
	}

	$this->_opt_arr =& $arr;
	return $arr;
}

function isset_opt($key)
{
	if ( isset($this->_opt_arr[$key]) ) {
		return true;
	}
	return false;
}

function get_opt($key)
{
	if ( isset($this->_opt_arr[$key]) ) {
		return $this->_opt_arr[$key];
	}
	return false;
}

//---------------------------------------------------------
// html header & footer
//---------------------------------------------------------
function print_write_html_header()
{
	$this->print_write_data( $this->print_write_html_header() );
}

function print_write_html_footer()
{
	$this->print_write_data( $this->print_write_html_footer() );
}

function get_html_header()
{
	$text = <<<END_OF_TEXT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=$this->_CHARSET">
<title> $this->_TITLE </title>
</head><body>
<h3> $this->_TITLE </h3>
<hr />
END_OF_TEXT;

	return $text;
}

function get_html_footer()
{
	$url_admin = XOOPS_URL.'/'.$this->_file_admin_index;

	$text = <<<END_OF_TEXT
<br />
<hr />
<a href="$url_admin">$this->_GOTO_ADMIN</a><br />
</head></html>
END_OF_TEXT;

	return $text;
}

function print_write_data($data)
{
	$this->print_data($data);
	$this->write_data($data);
}

function print_data($data)
{
	if ($this->_flag_print) {
		echo $data;
	}
}

function write_data($data)
{
	// dummy
}

//---------------------------------------------------------
// mail
//---------------------------------------------------------
function send_mail_content_by_level( $content, $level )
{
	if ( $this->_mail_level >= $level ) {
		return $this->send_mail_content( $content );
	}
	return true;	// no action
}

function send_mail_content( $content )
{
	return $this->send_mail($this->_mail_to, $this->_mail_title, $content);
}

function send_mail($mailto, $title, $content)
{
	$subject = '['. $this->_sitename .'] '. $title;
	$body    = $this->build_mail_body($title, $content);
	$header  = 'From: '. $this->_adminmail ." \n";
	$header .= 'X-Mailer: '. $this->_X_MAILER ." \n"; 

	return $this->send_mail_mb( $mailto, $subject, $body, $header );
}

function build_mail_body($title, $body)
{
	$siteurl = XOOPS_URL .'/';

	$msg  = '';
	if ( $this->_flag_write && $this->_filename ) {
		$msg  = "You can view detail here:\n";
		$msg .= XOOPS_URL .'/'. $this->_filename ."\n";
	}

	$text = <<<END_OF_TEXT
$title

$body

$msg
-----------
$this->_sitename ( $siteurl )
webmaster
$this->_adminmail
-----------
END_OF_TEXT;

	return $text;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_mailer($val)
{
	$this->_X_MAILER = $val;
}

function set_mail_to($val)
{
	$this->_mail_to = $val;
}

function set_mail_title($val)
{
	$this->_mail_title = $val;
}

function set_mail_level($val)
{
	$this->_mail_level = intval($val);
}

// not include XOOPS_URL
function set_filename($file)
{
	$this->_filename = $file;
}

function get_filename()
{
	return $this->_filename;
}

//---------------------------------------------------------
// multibyte
//---------------------------------------------------------
function set_internal_encoding( $encoding=null )
{
	if ( function_exists('mb_internal_encoding') ) {
		if ( $encoding ) {
			return mb_internal_encoding( $encoding );
		} else {
			return mb_internal_encoding();
		}
	}
	return true;	// dummy
}

function set_mail_language( $language=null )
{
	if ( function_exists('mb_language') ) {
		if ( $language ) {
			return mb_language( $language );
		} else {
			return mb_language();
		}
	}
}

function send_mail_mb( $mailto, $subject, $message, $headers=null, $parameter=null )
{
	if ( function_exists('mb_send_mail') ) {
		if ( $parameter ) {
			return mb_send_mail($mailto, $subject, $message, $headers, $parameter);
		} elseif ( $headers ) {
			return mb_send_mail($mailto, $subject, $message, $headers);
		} else {
			return mb_send_mail($mailto, $subject, $message);
		}
	}
	if ( $parameter ) {
		return mail($mailto, $subject, $message, $headers, $parameter);
	} elseif ( $headers ) {
		return mail($mailto, $subject, $message, $headers);
	}
	return mail($mailto, $subject, $message);
}

// --- class end ---
}

?>