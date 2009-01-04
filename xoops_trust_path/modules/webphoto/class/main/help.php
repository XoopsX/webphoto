<?php
// $Id: help.php,v 1.7 2009/01/04 06:05:34 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-04 K.OHWADA
// Fatal error: Call to undefined method webphoto_inc_xoops_header::getinstance()
// getInstance() -> getSingleton()
// 2008-08-01 K.OHWADA
// added main()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_help
//=========================================================
class webphoto_main_help extends webphoto_base_this
{
	var $_cfg_is_set_mail = false ;
	var $_cfg_file_dir    = false ;
	var $_has_perm_mail   = false ;
	var $_has_perm_file   = false ;

	var $_MAIL_RETRIEVE_AUTO_TIME = 0 ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_help( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_cfg_is_set_mail = $this->_config_class->is_set_mail() ;
	$this->_cfg_file_dir    = $this->get_config_by_name('file_dir') ;
	$has_mail               = $this->_perm_class->has_mail() ;
	$has_file               = $this->_perm_class->has_file() ;

	if ( $this->_cfg_is_set_mail && $has_mail ) {
		$this->_has_perm_mail = true;
	}

	if ( $this->_cfg_file_dir && $has_file ) {
		$this->_has_perm_file = true;
	}

	$this->preload_init();
	$this->preload_constant();
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
		'lang_help_mobile_text' => $this->_build_mobile_text() ,
		'show_help_mail'        => $this->_cfg_is_set_mail ,
		'show_help_mail_text'   => $this->_build_show_mail_text() ,
		'lang_help_mail_perm'   => $this->_build_mail_perm() ,
		'lang_help_mail_text'   => $this->_build_mail_text() ,
		'show_help_file'        => $this->_cfg_file_dir ,
		'show_help_file_text'   => $this->_build_show_file_text() ,
		'lang_help_file_perm'   => $this->_build_file_perm() ,
		'lang_help_file_text_1' => $this->_build_file_text_1() ,
		'lang_help_file_text_2' => $this->_build_file_text_2() ,
	);
	return $param;
}

function _build_mobile_text()
{
	$str = $this->get_constant('HELP_MOBILE_TEXT_FMT');
	$str = str_replace('{MODULE_URL}', $this->_MODULE_URL, $str );
	return $str;
}

function _build_show_mail_text()
{
	if ( $this->_has_perm_mail ) {
		return true;
	} elseif ( $this->_is_login_user ) {
		return true;
	}
	return false;
}

function _build_mail_perm()
{
	return $this->_build_perm( $this->_has_perm_mail ) ;
}

function _build_mail_text()
{
	$text  = $this->_build_mail_post();
	$text .= $this->_build_mail_retrieve();
	return $text;
}

function _build_mail_post()
{
	if ( $this->_has_perm_mail ) {
		$mail_addr  = $this->sanitize( $this->get_config_by_name('mail_addr') );
		$mail_guest = null;
	} else {
		$mail_addr  = 'user@exsample.com';
		$mail_guest = '<br />' . $this->get_constant('HELP_MAIL_GUEST');
	}

	$str = $this->get_constant('HELP_MAIL_POST_FMT');
	$str = str_replace('{MODULE_URL}', $this->_MODULE_URL, $str );
	$str = str_replace('{MAIL_ADDR}',  $mail_addr,  $str );
	$str = str_replace('{MAIL_GUEST}', $mail_guest, $str );
	return $str;
}

function _build_mail_retrieve()
{
	$text = $this->get_constant('HELP_MAIL_SUBTITLE_RETRIEVE');

	if ( $this->_MAIL_RETRIEVE_AUTO_TIME > 0 ) {
		$text .= sprintf( $this->get_constant('HELP_MAIL_RETRIEVE_AUTO_FMT'), 
			$this->_MAIL_RETRIEVE_AUTO_TIME );
	} else {
		$str   = $this->get_constant('HELP_MAIL_RETRIEVE_FMT');
		$text .= str_replace('{MODULE_URL}', $this->_MODULE_URL, $str );
	}

	$text .= $this->get_constant('HELP_MAIL_RETRIEVE_TEXT');
	return $text;
}

function _build_show_file_text()
{
	if ( $this->_has_perm_file ) {
		return true;
	} elseif ( $this->_is_login_user ) {
		return true;
	}
	return false;
}

function _build_file_perm()
{
	return $this->_build_perm( $this->_has_perm_file ) ;
}

function _build_file_text_1()
{
	$str = $this->get_constant('HELP_FILE_TEXT_FMT');
	$str = str_replace('{MODULE_URL}', $this->_MODULE_URL, $str );
	return $str;
}

function _build_file_text_2()
{
	if ( $this->_has_perm_file ) {
		$str = $this->get_config_by_name('file_desc') ;
	} else {
		$str = null ;
	}
	return $str;
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
		'flag_css' => true ,
	);

// Fatal error: Call to undefined method webphoto_inc_xoops_header::getinstance()
	$header_class =& webphoto_inc_xoops_header::getSingleton( $this->_DIRNAME );
	$header_class->assign_for_main( $param );
}

// --- class end ---
}

?>