<?php
// $Id: invite.php,v 1.2 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// get_valid_mail_addr()
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_invite
//=========================================================
class webphoto_admin_invite extends webphoto_base_this
{
	var $_mail_template_class;
	var $_mail_send_class;
	var $_msg_class;

	var $_xoops_user_email = null;
	var $_xoops_user_name  = null;

	var $_post_email   = null;
	var $_post_name    = null;
	var $_post_message = null;

	var $_FORM_TEMPLATE = 'form_admin_invite.html';
	var $_MAIL_TEMPLATE = 'invite.tpl';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_invite( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_mail_template_class 
		=& webphoto_d3_mail_template::getInstance( $dirname , $trust_dirname );

	$this->_mail_send_class  =& webphoto_lib_mail_send::getInstance();
	$this->_msg_class        =& webphoto_lib_msg::getInstance();

	$this->_xoops_user_email = $this->_xoops_class->get_my_user_value_by_name( 'email', 'n' );
	$this->_xoops_user_name  = $this->_xoops_class->get_user_uname_from_id( $this->_xoops_uid, 1 );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_invite( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	xoops_cp_header();

	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'INVITE' );

	switch ( $this->_get_op() )
	{
		case 'submit':
			if ( $this->check_token_with_print_error() ) {
				$this->_invite();
			}
			break;

		case 'form':
		default:
			$this->_print_form();
			break;
	}

	xoops_cp_footer();
	exit();
}

function _get_op()
{
	$this->_post_email   = $this->_post_class->get_post_text('email');
	$this->_post_name    = $this->_post_class->get_post_text('name');
	$this->_post_message = $this->_post_class->get_post_text('message');

	$submit = $this->_post_class->get_post_text('submit');
	if ( $submit ) {
		return 'submit';
	}
	return '';
}

//---------------------------------------------------------
// invite
//---------------------------------------------------------
function _invite()
{
	$ret = $this->_invite_exec();
	if ( $ret == 1 ) {
		echo $this->get_format_msg_array( true, false, true );
		return true;
	}

	echo $this->get_format_error();

	if ( $ret == -1 ) {
		echo $this->_print_form();
	}
	return false;
}

function _invite_exec()
{
	$email = $this->_mail_send_class->get_valid_mail_addr( $this->_post_email );
	if ( empty($email) ) {
		$this->set_error( $this->get_constant('ERR_MAIL_ILLEGAL') ) ;
		return -1 ;
	}

	if ( empty( $this->_post_name ) ) {
		$this->set_error( _AM_WEBPHOTO_INVITE_ERR_NO_NAME ) ;
		return -1;
	}

	$param = array(
		'to_emails'  => $email,
		'from_email' => $this->_get_from_email() ,
		'subject'    => $this->_get_subject(),
		'body'       => $this->_get_body(),
		'debug'      => true,
	);

	$ret = $this->_mail_send_class->send( $param );
	if ( !$ret ) {
		$this->set_error( $this->_mail_send_class->get_errors() );
		return -2;
	}

	$this->set_msg( $this->_mail_send_class->get_msg_array() );
	return 1;
}

function _get_from_email()
{
	if ( $this->_xoops_user_email ) {
		return $this->_xoops_user_email ;
	}
	return $this->_xoops_adminmail;
}

function _get_subject()
{
	return sprintf( _AM_WEBPHOTO_INVITE_SUBJECT, $this->_post_name, $this->_MODULE_NAME );
}

function _get_body()
{
	$tags = array(
		'INVITE_NAME'    => $this->_post_name,
		'INVITE_MASSAGE' => $this->_post_message,
	);

	$this->_mail_template_class->init_tag_array();
	$this->_mail_template_class->assign( $tags );
	$str = $this->_mail_template_class->replace_tag_array_by_template( $this->_MAIL_TEMPLATE );
	return $str;
}

//---------------------------------------------------------
// print form
//---------------------------------------------------------
function _print_form()
{
	echo $this->_build_form_invite();
}

function _build_form_invite()
{
	$template = $this->build_form_template( $this->_FORM_TEMPLATE );

	$name = $this->_post_name;
	if ( empty( $name ) ) {
		$name = $this->_xoops_user_name ;
	}

	$arr = array( 
		'xoops_g_ticket' => $this->get_token(),
		'email'   => $this->_post_email,
		'name'    => $name,
		'message' => $this->_post_message,

		'lang_title_invite'   => $this->get_admin_title( 'INVITE' ) ,
		'lang_invite_email'   => _AM_WEBPHOTO_INVITE_EMAIL ,
		'lang_invite_name'    => _AM_WEBPHOTO_INVITE_NAME ,
		'lang_invite_message' => _AM_WEBPHOTO_INVITE_MESSAGE ,
		'lang_invite_submit'  => _AM_WEBPHOTO_INVITE_SUBMIT ,
		'lang_invite_example' => _AM_WEBPHOTO_INVITE_EXAMPLE ,

// for XOOPS 2.0.18
		'xoops_dirname' => $this->_DIRNAME ,
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	return $tpl->fetch( $template ) ;
}

function build_form_template( $name )
{
	$str = 'db:'. $this->_DIRNAME .'_'. $name ;
	return $str;
}

//---------------------------------------------------------
// msg
//---------------------------------------------------------
function clear_msg_array()
{
	$this->_msg_class->clear_msg_array();
}

function set_msg( $msg, $flag_highlight=false )
{
	$this->_msg_class->set_msg( $msg, $flag_highlight );
}

function get_msg_array()
{
	return $this->_msg_class->get_msg_array();
}

function get_format_msg( $flag_sanitize=true )
{
	return $this->_msg_class->get_format_msg( $flag_sanitize );
}

// --- class end ---
}

?>