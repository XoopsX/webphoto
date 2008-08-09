<?php
// $Id: help.php,v 1.3 2008/08/09 09:19:08 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-01 K.OHWADA
// added main()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_help
//=========================================================
class webphoto_main_help extends webphoto_base_this
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_help( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_help( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// function
//---------------------------------------------------------
function main()
{
	$this->_assign_xoops_header();

	$param = array(
		'lang_help_mail_perm'   => $this->_build_mail_perm() ,
		'lang_help_mail_text'   => $this->_build_mail_text() ,
		'lang_help_file_perm'   => $this->_build_file_perm() ,
		'lang_help_file_text_1' => $this->_build_file_text_1() ,
		'lang_help_file_text_2' => $this->_build_file_text_2() ,
	);
	return $param;
}

function _build_mail_perm()
{
	return $this->_build_perm( $this->_check_mail_perm() ) ;
}

function _build_mail_text()
{
	if ( $this->_check_mail_perm() ) {
		$mail_addr  = $this->sanitize( $this->get_config_by_name('mail_addr') );
		$mail_guest = null;
	} else {
		$mail_addr  = 'user@exsample.com';
		$mail_guest = '<br />' . $this->get_constant('HELP_MAIL_GUEST');
	}

	$str = $this->get_constant('HELP_MAIL_TEXT_FMT');
	$str = str_replace('{MODULE_URL}', $this->_MODULE_URL, $str );
	$str = str_replace('{MAIL_ADDR}',  $mail_addr,  $str );
	$str = str_replace('{MAIL_GUEST}', $mail_guest, $str );
	return $str;
}

function _check_mail_perm()
{
	$cfg_is_set_mail = $this->_config_class->is_set_mail() ;
	$has_mail        = $this->_perm_class->has_mail() ;

	if ( $cfg_is_set_mail && $has_mail ) {
		return true;
	}
	return false;
}

function _build_file_perm()
{
	return $this->_build_perm( $this->_check_file_perm() ) ;
}

function _build_file_text_1()
{
	$str = $this->get_constant('HELP_FILE_TEXT_FMT');
	$str = str_replace('{MODULE_URL}', $this->_MODULE_URL, $str );
	return $str;
}

function _build_file_text_2()
{
	if ( $this->_check_file_perm() ) {
		$str = $this->get_config_by_name('file_desc') ;
	} else {
		$str = null ;
	}
	return $str;
}

function _check_file_perm()
{
	$cfg_file_dir  = $this->get_config_by_name('file_dir') ;
	$has_file      = $this->_perm_class->has_file() ;

	if ( $cfg_file_dir && $has_file ) {
		return true;
	}
	return false;
}

function _build_perm( $perm )
{
	if ( $perm ) {
		$str = null ;
	} elseif ( $this->_is_login_user ) {
		$str = $this->get_constant('HELP_NOT_PERM');
	} else {
		$str = $this->get_constant('HELP_MUST_LOGIN');
	}
	return $str;
}

function _assign_xoops_header()
{
	$param = array(
		'dirname'     => $this->_DIRNAME ,
		'flag_css'    => true ,
	);

	$header_class =& webphoto_inc_xoops_header::getInstance();
	$header_class->assign_for_main( $param );
}

// --- class end ---
}

?>