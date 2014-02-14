<?php
// $Id: base.php,v 1.20 2011/12/26 06:51:31 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// remove get_mysql_date_today()
// 2010-10-01 K.OHWADA
// is_image_cmyk()
// 2010-01-10 K.OHWADA
// is_system_group()
// 2009-12-06 K.OHWADA
// get_system_groups()
// 2009-11-11 K.OHWADA
// get_files_in_dir()
// 2009-01-10 K.OHWADA
// build_random_file_name()
// 2008-12-12 K.OHWADA
// array_to_perm()
// 2008-11-29 K.OHWADA
// user_to_server_time()
// 2008-11-16 K.OHWADA
// get_cached_xoops_db_groups()
// 2008-10-10 K.OHWADA
// set_error_in_head_with_admin_info()
// 2008-10-01 K.OHWADA
// BUG : not set xoops_group
// 2008-09-01 K.OHWADA
// added build_set_msg()
// 2008-08-01 K.OHWADA
// added set_msg_array() check_token_and_redirect()
// 2008-07-01 K.OHWADA
// added build_error_msg()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_base
//=========================================================
class webphoto_lib_base extends webphoto_lib_error
{
	var $_utility_class;
	var $_language_class;
	var $_xoops_class;
	var $_msg_class;

// xoops param
	var $_xoops_language  = null ;
	var $_xoops_sitename  = null ;
	var $_xoops_adminmail = null ;
	var $_xoops_anonymous = null ;
	var $_xoops_uname     = null ;
	var $_xoops_groups    = null ;
	var $_xoops_uid       = 0 ;
	var $_is_module_admin = false;
	var $_is_login_user   = false;

	var $_token_error_flag = false;
	var $_token_errors     = null;

	var $_msg_array       = array();
	var $_msg_level_array = array() ;
	var $_msg_level       = 0 ;

	var $_DIRNAME       = null;
	var $_TRUST_DIRNAME = null;
	var $_MODULE_URL;
	var $_MODULE_DIR;
	var $_TRUST_DIR;

	var $_INDEX_PHP;
	var $_ADMIN_INDEX_PHP;

	var $_MODULE_NAME = null;
	var $_MODULE_ID   = 0;
	var $_MODULE_HAS_CONFIG = false;

	var $_FLAG_ADMIN_SUB_MENU = true;

	var $_PERM_ALLOW_ALL = '*' ;
	var $_PERM_DENOY_ALL = 'x' ;
	var $_PERM_SEPARATOR = '&' ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_base( $dirname, $trust_dirname )
{
	$this->webphoto_lib_error();

	$this->_xoops_class   =& webphoto_xoops_base::getInstance();
	$this->_utility_class =& webphoto_lib_utility::getInstance();

// each msg box
	$this->_msg_class = new webphoto_lib_msg();

	$this->_DIRNAME    = $dirname ;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'. $dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'. $dirname;

	$this->_INDEX_PHP       = $this->_MODULE_URL .'/index.php';
	$this->_ADMIN_INDEX_PHP = $this->_MODULE_URL .'/admin/index.php';

	$this->set_trust_dirname( $trust_dirname );
	$this->_init_d3_language( $dirname, $trust_dirname );
	$this->_init_xoops_param();

}

//---------------------------------------------------------
// check
//---------------------------------------------------------
function check_not_owner( $uid )
{
	if ( $this->_is_module_admin ) {
		return false ;
	} elseif ( $this->_is_login_user ) { 
		if ( $this->_xoops_uid != $uid ) { 
			return true;
		} 
	} else { 
		return true;
	}
	return false;
}

//---------------------------------------------------------
// header
//---------------------------------------------------------
function build_bread_crumb( $title, $url )
{
	$text  = '<a href="'. $this->_MODULE_URL .'/index.php">';
	$text .= $this->sanitize( $this->_MODULE_NAME );
	$text .= '</a>';
	$text .= ' &gt;&gt; ';
	$text .= '<a href="'. $url .'">';
	$text .= $this->sanitize( $title );
	$text .= '</a>';
	$text .= "<br /><br />\n";
	return $text;
}

//---------------------------------------------------------
// for admin
//---------------------------------------------------------
function build_admin_bread_crumb( $title, $url )
{
	$text  = '<a href="'. $this->_MODULE_URL .'/admin/index.php">';
	$text .= $this->sanitize( $this->_MODULE_NAME );
	$text .= '</a>';
	$text .= ' &gt;&gt; ';
	$text .= '<a href="'. $url .'">';
	$text .= $this->sanitize( $title );
	$text .= '</a>';
	$text .= "<br /><br />\n";
	return $text;
}

function build_admin_menu()
{
	$menu_class =& webphoto_lib_admin_menu::getInstance(
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

	return $menu_class->build_menu_with_sub( $this->_FLAG_ADMIN_SUB_MENU );
}

function build_admin_title( $name, $format=true )
{
	$str = $this->get_admin_title( $name );
	if ( $format ) {
		$str = "<h3>". $str ."</h3>\n";
	}
	return $str;
}

function get_admin_title( $name )
{
	$const_name_1 = strtoupper( '_MI_'. $this->_DIRNAME       .'_ADMENU_'. $name ) ;
	$const_name_2 = strtoupper( '_AM_'. $this->_TRUST_DIRNAME .'_TITLE_'.  $name ) ;

	if ( defined($const_name_1) ) {
		return constant($const_name_1);
	} elseif ( defined($const_name_2) ) {
		return constant($const_name_2);
	}
	return $const_name_2;
}

function print_admin_msg( $msg, $flag_highlight=false, $flag_br=false )
{
	echo $this->build_admin_msg( $msg, $flag_highlight, $flag_br ) ;
}

function build_admin_msg( $msg, $flag_highlight=false, $flag_br=false )
{
	if ( !$this->_is_module_admin ) {
		return null;
	}
	if ( $flag_highlight ) {
		$msg = $this->highlight( $msg );
	}
	if ( $flag_br ) {
		$msg .= "<br />\n";
	}
	return $msg ;
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function array_to_perm( $arr, $glue )
{
	return $this->_utility_class->array_to_perm( $arr, $glue );
}

function str_to_array( $str, $pattern )
{
	return $this->_utility_class->str_to_array( $str, $pattern );
}

function array_to_str( $arr, $glue )
{
	return $this->_utility_class->array_to_str( $arr, $glue );
}

function add_slash_to_head( $str )
{
	return $this->_utility_class->add_slash_to_head( $str );
}

function strip_slash_from_head( $str )
{
	return $this->_utility_class->strip_slash_from_head( $str );
}

function strip_slash_from_tail( $dir )
{
	return $this->_utility_class->strip_slash_from_tail( $dir );
}

function parse_ext( $file )
{
	return $this->_utility_class->parse_ext( $file );
}

function strip_ext( $file )
{
	return $this->_utility_class->strip_ext( $file );
}

function rename_file( $old, $new )
{
	return $this->_utility_class->rename_file( $old, $new );
}

function copy_file( $src, $dst, $flag=false )
{
	return $this->_utility_class->copy_file( $src, $dst, $flag );
}

function unlink_file( $file )
{
	return $this->_utility_class->unlink_file( $file );
}

function check_http_start( $str )
{
	return $this->_utility_class->check_http_start( $str );
}

function check_http_null( $str )
{
	return $this->_utility_class->check_http_null( $str );
}

function adjust_image_size( $width, $height, $max_width, $max_height )
{
	return $this->_utility_class->adjust_image_size( $width, $height, $max_width, $max_height );
}

function is_image_cmyk( $file )
{
	return $this->_utility_class->is_image_cmyk( $file );
}

function build_error_msg( $msg, $title='', $flag_sanitize=true )
{
	return $this->_utility_class->build_error_msg( $msg, $title, $flag_sanitize );
}

function build_random_file_name( $id, $ext, $extra=null )
{
	return $this->_utility_class->build_random_file_name( $id, $ext, $extra );
}

function build_random_file_node( $id, $extra=null )
{
	return $this->_utility_class->build_random_file_node( $id, $extra );
}

function parse_url_to_filename( $url )
{
	return $this->_utility_class->parse_url_to_filename( $url );
}

function get_files_in_dir( $path, $ext=null, $flag_dir=false, $flag_sort=false, $id_as_key=false )
{
	return $this->_utility_class->get_files_in_dir( $path, $ext, $flag_dir, $flag_sort, $id_as_key );
}

//---------------------------------------------------------
// sanitize
//---------------------------------------------------------
//---------------------------------------------------------
// TAB \x09 \t
// LF  \xOA \n
// CR  \xOD \r
//---------------------------------------------------------
function str_replace_control_code( $str, $replace=' ' )
{
	$str = preg_replace('/[\x00-\x08]/', $replace, $str);
	$str = preg_replace('/[\x0B-\x0C]/', $replace, $str);
	$str = preg_replace('/[\x0E-\x1F]/', $replace, $str);
	$str = preg_replace('/[\x7F]/',      $replace, $str);
	return $str;
}

function str_replace_tab_code( $str, $replace=' ' )
{
	return preg_replace("/\t/", $replace, $str);
}

function str_replace_return_code( $str, $replace=' ' )
{
	$str = preg_replace("/\n/", $replace, $str);
	$str = preg_replace("/\r/", $replace, $str);
	return $str;
}

function sanitize_array_int( $arr_in )
{
	if ( !is_array($arr_in) || !count($arr_in) ) {
		return null;
	}

	$arr_out = array();
	foreach ( $arr_in as $in ) {
		$arr_out[] = intval($in);
	}
	return $arr_out;
}

//---------------------------------------------------------
// msg class
//---------------------------------------------------------
function build_set_msg( $msg, $flag_highlight=false, $flag_br=false )
{
	$this->set_msg(
		$this->build_msg( $msg, $flag_highlight, $flag_br ) );
}

function set_msg_level( $val )
{
	$this->_msg_level = intval( $val );
}

function check_msg_level( $level )
{
	if (( $this->_msg_level > 0 )&&( $this->_msg_level >= $level )) {
		return true ;
	}
	return false ;
}

function build_msg_level( $level, $msg, $flag_highlight=false, $flag_br=false )
{
	if ( $this->check_msg_level( $level ) ) {
		return $this->build_msg( $msg, $flag_highlight, $flag_br );
	}
	return null;
}

function build_msg( $msg, $flag_highlight=false, $flag_br=false )
{
	if ( $flag_highlight ) {
		$msg = $this->highlight( $msg );
	}
	if ( $flag_br ) {
		$msg .= "<br />\n";
	}
	return $msg ;
}

function set_error_in_head_with_admin_info( $msg )
{
	$arr = $this->get_errors();
	$this->clear_errors();
	$this->set_error( $msg );
	if ( $this->_is_module_admin ) {
		$this->set_error( $arr );
	}
}

//---------------------------------------------------------
// msg class
//---------------------------------------------------------
function clear_msg_array()
{
	$this->_msg_class->clear_msg_array() ;
}

function get_msg_array()
{
	return $this->_msg_class->get_msg_array() ;
}

function has_msg_array()
{
	return $this->_msg_class->has_msg_array() ;
}

function set_msg_array( $msg, $flag_highlight=false )
{
	return $this->_msg_class->set_msg( $msg, $flag_highlight ) ;
}

function set_msg( $msg, $flag_highlight=false )
{
	return $this->_msg_class->set_msg( $msg, $flag_highlight ) ;
}

function get_format_msg_array( $flag_sanitize=true, $flag_highlight=true, $flag_br=true )
{
	return $this->_msg_class->get_format_msg_array( $flag_sanitize, $flag_highlight, $flag_br ) ;
}

//---------------------------------------------------------
// head
//---------------------------------------------------------
function build_html_head( $title=null, $charset=null )
{
	if ( empty($charset) ) {
		$charset = _CHARSET;
	}

	$text  = '<html><head>'."\n";
	$text .= '<meta http-equiv="Content-Type" content="text/html; charset='. $this->sanitize( $charset ) .'" />'."\n";
	$text .= '<title>'. $this->sanitize( $title ) .'</title>'."\n";
	$text .= '</head>'."\n";
	return $text;
}

function build_html_body_begin()
{
	$text = '<body>'."\n";
	return $text;
}

function build_html_body_end()
{
	$text = '</body></html>'."\n";
	return $text;
}

//---------------------------------------------------------
// token
//---------------------------------------------------------
function get_token_name()
{
	return 'XOOPS_G_TICKET';
}

function get_token()
{
	global $xoopsGTicket;
	if ( is_object($xoopsGTicket) ) {
		return $xoopsGTicket->issue();
	}
	return null;
}

function check_token( $allow_repost=false )
{
	global $xoopsGTicket;
	if ( is_object($xoopsGTicket) ) {
		if ( ! $xoopsGTicket->check( true , '',  $allow_repost ) ) {
			$this->_token_error_flag  = true;
			$this->_token_errors = $xoopsGTicket->getErrors();
			return false;
		}
	}
	$this->_token_error_flag = false;
	return true;
}

function get_token_errors()
{
	return $this->_token_errors;
}

function check_token_with_print_error()
{
	$ret = $this->check_token();
	if ( !$ret ) {
		echo $this->build_error_msg( "Token Error" );
	}
	return $ret;
}

function check_token_and_redirect( $url, $time=5 )
{
	if ( ! $this->check_token() )  {
		$msg = 'Token Error';
		if ( $this->_is_module_admin ) {
			$msg .= '<br />'.$this->get_token_errors();
		}
		redirect_header( $url, $time , $msg );
		exit();
	}
	return true;
}

function set_token_error()
{
	$this->set_error( 'Token Error' );
	if ( $this->_is_module_admin ) {
		$this->set_error( $this->get_token_errors() );
	}
}

//---------------------------------------------------------
// xoops param
//---------------------------------------------------------
function _init_xoops_param()
{
	$this->_xoops_language  = $this->_xoops_class->get_config_by_name( 'language' );
	$this->_xoops_sitename  = $this->_xoops_class->get_config_by_name( 'sitename' );
	$this->_xoops_adminmail = $this->_xoops_class->get_config_by_name( 'adminmail' );
	$this->_xoops_anonymous = $this->_xoops_class->get_config_by_name( 'anonymous' );

	$this->_MODULE_ID         = $this->_xoops_class->get_my_module_id();
	$this->_MODULE_NAME       = $this->_xoops_class->get_my_module_name( 'n' );
	$this->_MODULE_HAS_CONFIG = $this->_xoops_class->get_my_module_value_by_name( 'hasconfig' );

	$this->_xoops_uid         = $this->_xoops_class->get_my_user_uid();
	$this->_xoops_uname       = $this->_xoops_class->get_my_user_uname( 'n' );
	$this->_xoops_groups      = $this->_xoops_class->get_my_user_groups();
	$this->_is_login_user     = $this->_xoops_class->get_my_user_is_login();
	$this->_is_module_admin   = $this->_xoops_class->get_my_user_is_module_admin();
}

function has_xoops_config_this_module()
{
	return $this->_xoops_class->has_my_module_config();
}

function get_xoops_uname_by_uid( $uid, $usereal=0 )
{
	return $this->_xoops_class->get_user_uname_from_id( $uid, $usereal );
}

function get_xoops_email_by_uid( $uid )
{
	return $this->_xoops_class->get_user_email_from_id( $uid );
}

function get_xoops_module_by_dirname( $dirname )
{
	return $this->_xoops_class->get_module_by_dirname( $dirname );
}

function get_xoops_group_objs()
{
	return $this->_xoops_class->get_group_obj();
}

function get_cached_xoops_db_groups( $none=false, $none_name='---', $format='s' )
{
	return $this->_xoops_class->get_cached_groups( $none, $none_name, $format );
}

function get_xoops_group_name( $id, $format='s' )
{
	return $this->_xoops_class->get_cached_group_by_id_name( $id, 'name', $format );
}

function get_system_groups()
{
	return $this->_xoops_class->get_system_groups();
}

function is_system_group( $id )
{
	return $this->_xoops_class->is_system_group( $id );
}

//---------------------------------------------------------
// timestamp
//---------------------------------------------------------
function user_to_server_time( $time )
{
	return $this->_xoops_class->user_to_server_time( $time );
}

function format_timestamp( $time, $format="l", $timeoffset="" )
{
	return formatTimestamp( $time, $format, $timeoffset );
}

//---------------------------------------------------------
// d3 language
//---------------------------------------------------------
function _init_d3_language( $dirname, $trust_dirname )
{
	$this->_language_class =& webphoto_d3_language::getInstance();
	$this->_language_class->init( $dirname , $trust_dirname );
}

function get_lang_array()
{
	return $this->_language_class->get_lang_array();
}

function get_constant( $name )
{
	return $this->_language_class->get_constant( $name );
}

function set_trust_dirname( $trust_dirname )
{
	$this->_TRUST_DIRNAME = $trust_dirname;
	$this->_TRUST_DIR     = XOOPS_TRUST_PATH .'/modules/'. $trust_dirname;
}

// --- class end ---
}

?>