<?php
// $Id: base.php,v 1.3 2008/07/11 20:19:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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

// xoops param
	var $_xoops_language;
	var $_xoops_sitename;
	var $_xoops_uid    = 0 ;
	var $_xoops_uname  = null ;
	var $_xoops_groups = null ;
	var $_is_module_admin = false;
	var $_is_login_user   = false;

	var $_token_error_flag = false;
	var $_token_errors     = null;

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

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_base( $dirname, $trust_dirname )
{
	$this->webphoto_lib_error();

	$this->_xoops_class    =& webphoto_xoops_base::getInstance();
	$this->_utility_class  =& webphoto_lib_utility::getInstance();

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
	return $menu_class->build_menu();
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

function mysql_datetime_to_str( $date )
{
	return $this->_utility_class->mysql_datetime_to_str( $date );
}

function str_to_mysql_date( $str )
{
	return $this->_utility_class->str_to_mysql_date( $str );
}

function get_mysql_date_today()
{
	return $this->_utility_class->get_mysql_date_today();
}

function rename_file( $old, $new )
{
	return $this->_utility_class->rename_file( $old, $new );
}

function copy_file( $src, $dst )
{
	return $this->_utility_class->copy_file( $src, $dst );
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

function build_error_msg( $msg, $title='', $flag_sanitize=true )
{
	return $this->_utility_class->build_error_msg( $msg, $title, $flag_sanitize );
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

//---------------------------------------------------------
// xoops param
//---------------------------------------------------------
function _init_xoops_param()
{
	$this->_xoops_language = $this->_xoops_class->get_config_by_name( 'language' );
	$this->_xoops_sitename = $this->_xoops_class->get_config_by_name( 'sitename' );

	$this->_MODULE_ID         = $this->_xoops_class->get_my_module_id();
	$this->_MODULE_NAME       = $this->_xoops_class->get_my_module_name( 'n' );
	$this->_MODULE_HAS_CONFIG = $this->_xoops_class->get_my_module_value_by_name( 'hasconfig' );

	$this->_xoops_uid         = $this->_xoops_class->get_my_user_uid();
	$this->_xoops_uname       = $this->_xoops_class->get_my_user_uname( 'n' );
	$this->_xoops_user_groups = $this->_xoops_class->get_my_user_groups();
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

function get_xoops_module_by_dirname( $dirname )
{
	return $this->_xoops_class->get_module_by_dirname( $dirname );
}

function get_xoops_group_objs()
{
	return $this->_xoops_class->get_group_obj();
}

function get_xoops_group_name( $id, $format='s' )
{
	return $this->_xoops_class->get_group_by_id_name( $id, 'name', $format );
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