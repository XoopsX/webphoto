<?php
// $Id: mail_retrieve.php,v 1.7 2008/11/11 06:53:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-11-08 K.OHWADA
// TMP_DIR -> MAIL_DIR
// 2008-08-24 K.OHWADA
// added set_flag_chmod()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_mail_retrieve
//=========================================================
class webphoto_mail_retrieve extends webphoto_mail_photo
{
	var $_pop_class ;

	var $_flag_retrive_chmod = false ;

	var $_is_set_mail = false;
	var $_has_mail    = false;

	var $_mail_count = 0;
	var $_mail_array = null;

	var $_MAX_MAILLOG = 1000;

	var $_FILE_ACCESS = null;
	var $_TIME_ACCESS = 60; // 60 sec ( 1 min )

	var $_DEBUG_MAIL_FILE = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_mail_retrieve( $dirname , $trust_dirname )
{
	$this->webphoto_mail_photo( $dirname , $trust_dirname );
	$this->set_mail_groups( XOOPS_GROUP_USERS );
	$this->set_flag_chmod( true );

	$this->_pop_class =& webphoto_lib_mail_pop::getInstance();

	$cfg_mail_host        = $this->get_config_by_name( 'mail_host' );
	$cfg_mail_user        = $this->get_config_by_name( 'mail_user' );
	$cfg_mail_pass        = $this->get_config_by_name( 'mail_pass' );
	$this->_cfg_makethumb = $this->get_config_by_name( 'makethumb' );

	$this->_pop_class->set_host( $cfg_mail_host );
	$this->_pop_class->set_user( $cfg_mail_user );
	$this->_pop_class->set_pass( $cfg_mail_pass );

	$this->_is_set_mail = $this->_config_class->is_set_mail();
	$this->_has_mail    = $this->_perm_class->has_mail();

	$this->_FILE_ACCESS = $this->_MAIL_DIR .'/mail_access';

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_mail_retrieve( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// check
//---------------------------------------------------------
function check_perm()
{
	if ( ! $this->_is_set_mail ) {
		return _C_WEBPHOTO_ERR_NO_PERM;
	}

	if ( ! $this->_has_mail ) {
		return _C_WEBPHOTO_ERR_NO_PERM;
	}

	return 0;
}

function is_set_mail()
{
	return $this->_is_set_mail ;
}

function has_mail()
{
	return $this->_has_mail ;
}

function set_flag_chmod( $val )
{
	$this->set_image_video_flag_chmod( $val );
	$this->set_flag_mail_chmod( $val );
	$this->_flag_retrive_chmod = (bool)$val;
}

//---------------------------------------------------------
// retrieve
//---------------------------------------------------------
function retrieve()
{
	if ( ! $this->check_access_time() ) {
		$msg  = $this->get_constant('TEXT_MAIL_ACCESS_TIME') ;
		$msg .= "<br>\n";
		$msg .= $this->get_constant('TEXT_MAIL_RETRY') ;
		$msg .= "<br>\n";
		$this->print_msg_level_user( $msg );
		return _C_WEBPHOTO_RETRIEVE_CODE_ACCESS_TIME ;
	}

// set time before execute
	$this->renew_access_time();

	$ret = $this->retrieve_exec();

// set time after execute
	$this->renew_access_time();

	return $ret;
}

function check_access_time()
{
// if passing access interval time
	if ( file_exists( $this->_FILE_ACCESS ) ) {
		$time = intval( trim( file_get_contents( $this->_FILE_ACCESS ) ) );
		if ( ( $time > 0 ) && 
		     ( time() > ( $time + $this->_TIME_ACCESS ) ) ) {
			return true;
		}

// if not exists file ( at first time )
	} else {
		return true;
	}

	return false;
}

function renew_access_time()
{
	$this->_utility_class->write_file( 
		$this->_FILE_ACCESS, time(), 'w', $this->_flag_retrive_chmod );
}

function retrieve_exec()
{
	if ( $this->_DEBUG_MAIL_FILE ) {
		$this->print_msg_level_user( 'DEBUG MODE', false, true );
		$this->_mail_count = 1 ;
		$this->_mail_array = array( 
			$this->build_mail_file( $this->_DEBUG_MAIL_FILE ) ) ;

	} else {
		$ret = $this->mail_pop();
		if ( $ret < 0 ) {
			return $ret;
		}

		if ( !is_array($this->_mail_array) || !count($this->_mail_array) ) {
			return _C_WEBPHOTO_RETRIEVE_CODE_NO_NEW ;
		}
	}

	$this->clear_maillog( $this->_MAX_MAILLOG );

	$ret_arr = $this->mail_parse( $this->_mail_array );
	if ( !is_array($ret_arr) || !count($ret_arr) ) {
		return 0;
	}

	$this->add_photos( $ret_arr );
	return 0;
}

function mail_pop()
{
	$this->_mail_count = 0;
	$this->_mail_array = null;
	$file_arr = array();

	$msg = "<h4>".$this->get_constant('SUBTITLE_MAIL_ACCESS')."</h4>\n";
	$this->print_msg_level_user( $msg );

	$ret = $this->_pop_class->recv_mails();
	if ( $ret == -1 ) {
		$errors = $this->_pop_class->get_errors();
		$msg = $this->array_to_str( $errors, "\n" );
		$msg = nl2br( $this->sanitize($msg) );
		$this->print_msg_level_admin( 'POP Error', true, true );
		$this->print_msg_level_admin( $msg, false, true );
		$this->print_msg_level_user( $this->get_constant('TEXT_MAIL_NOT_RETRIEVE'), true );
		return _C_WEBPHOTO_RETRIEVE_CODE_NOT_RETRIEVE ;
	}

	$mail_arr = $this->_pop_class->get_mails();
	$count = count($mail_arr);

	if ( !is_array($mail_arr) || !$count ) {
		$this->print_msg_level_user( $this->get_constant('TEXT_MAIL_NO_NEW') );
		return _C_WEBPHOTO_RETRIEVE_CODE_NO_NEW ;
	}

	$msg = sprintf( $this->get_constant('TEXT_MAIL_RETRIEVED_FMT'), $count );
	$this->print_msg_level_user( $msg, false, true );

	foreach ($mail_arr as $mail )
	{
		$file_name = uniqid('mail_').'.txt';
		$file_path = $this->_MAIL_DIR.'/'.$file_name ;

		$this->print_msg_level_admin(  $file_name, false, true );

		$this->_utility_class->write_file( 
			$file_path, $mail, 'w', $this->_flag_retrive_chmod );
		$file_arr[] = $this->build_mail_file( $file_name );
	}

	$this->_mail_count = $count;
	$this->_mail_array = $file_arr;
	return 0;
}

function get_mail_count()
{
	return $this->_mail_count ;
}

function build_mail_file( $file )
{
	$arr = array(
		'maillog_id' => $this->add_maillog( $file ) ,
		'file'       => $file ,
	);
	return $arr;
}

function mail_parse( $file_arr )
{
	$msg = "<h4>".$this->get_constant('SUBTITLE_MAIL_PARSE')."</h4>\n";
	$this->print_msg_level_user( $msg );

	$param_arr = $this->parse_mails( $file_arr );

	if ( !is_array($param_arr) || !count($param_arr) ) {
		$msg = $this->get_constant('TEXT_MAIL_NO_VALID');
		$this->print_msg_level_user( $msg, false, true );
	}

	return $param_arr;
}

function add_photos( $file_arr )
{
	$msg = "<h4>".$this->get_constant('SUBTITLE_MAIL_PHOTO')."</h4>\n";
	$this->print_msg_level_user( $msg );

	$count = $this->add_photos_from_mail( $file_arr );

	$msg  = "<br />\n";
	$msg .= sprintf( $this->get_constant('TEXT_MAIL_SUBMITED_FMT'), $count );
	$this->print_msg_level_user( $msg, false, true );

}

// --- class end ---
}

?>