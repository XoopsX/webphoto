<?php
// $Id: mail_send.php,v 1.1 2010/02/07 12:22:11 ohwada Exp $

//=========================================================
// webphoto module
// 2010-02-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_mail_send
//=========================================================
class webphoto_mail_send extends webphoto_base_this
{
	var $_mail_template_class;
	var $_mail_send_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_mail_send( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_mail_template_class 
		=& webphoto_d3_mail_template::getInstance( $dirname , $trust_dirname );

	$this->_mail_send_class  =& webphoto_lib_mail_send::getInstance();
}

// for admin_photo_manage admin_catmanager
function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_mail_send( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// submit
//---------------------------------------------------------
function send_waiting( $row )
{
	$param = array(
		'to_emails'  => $this->build_waiting_emails( $row ) ,
		'from_email' => $this->_xoops_adminmail ,
		'subject'    => $this->build_subject( $this->get_constant('MAIL_SUBMIT_WAITING') ) ,
		'body'       => $this->build_waiting_body( $row, 'global_waiting_notify.tpl' ),
		'debug'      => true,
	);

	return $this->send_by_param( $param );
}

function build_waiting_emails( $item_row )
{
	$emails = null;

// cat group
	$cat_row = $this->_cat_handler->get_cached_row_by_id( $item_row['item_cat_id'] );
	if ( isset($cat_row['cat_group_id']) && $cat_row['cat_group_id'] ) {
		$emails = $this->get_emails_by_groupid( $cat_row['cat_group_id'] );
	}

// admin group
	if ( !is_array($emails) || !count($emails) ) {
		$emails = $this->get_emails_by_groupid( XOOPS_GROUP_ADMIN );
	}

	return $emails;
}

function get_emails_by_groupid( $group_id )
{
	$users = $this->_xoops_class->get_member_users_by_group( $group_id, true );
	if ( !is_array($users) || !count($users) ) {
		return false;
	}

	$emails = array();
	foreach ( $users as $user ) {
		$emails[] = $user->getVar('email', 'n');
	}

	return $emails;
}

function build_waiting_body( $row, $template )
{
	$url = $this->_MODULE_URL .'/admin/index.php?fct=item_manager&op=modify_form&item_id='. $row['item_id'] ;
	$tags = array( 
		'PHOTO_TITLE' => $row['item_title'] ,
		'WAITING_URL' => $url ,
	 );

	return $this->build_body_by_tags( $tags, $template );
}

//---------------------------------------------------------
// admin
//---------------------------------------------------------
function send_approve( $row )
{
	return $this->send_approve_common( 
		$row, 
		$this->get_constant('MAIL_SUBMIT_APPROVE') , 
		'submit_approve_notify.tpl' );
}

function send_refuse( $row )
{
	return $this->send_approve_common( 
		$row, 
		$this->get_constant('MAIL_SUBMIT_REFUSE') , 
		'submit_refuse_notify.tpl' );
}

function send_approve_common( $row, $subject, $template )
{
	$email = $this->get_xoops_email_by_uid( $row['item_uid'] );
	if ( empty($email) ) {
		return true;	// no mail
	}

	$param = array(
		'to_emails'  => $email ,
		'from_email' => $this->_xoops_adminmail ,
		'subject'    => $this->build_subject( $subject ) ,
		'body'       => $this->build_approve_body( $row, $template ),
		'debug'      => true,
	);

	return $this->send_by_param( $param );
}

function build_approve_body( $row, $template )
{
	$tags = array(
		'PHOTO_TITLE' => $row['item_title'] ,
		'PHOTO_URL'   => $this->build_uri_photo( $row['item_id'] ),
		'PHOTO_UNAME' => $this->get_xoops_uname_by_uid( $row['item_uid'] ),
	);

	return $this->build_body_by_tags( $tags, $template );
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function send_by_param( $param )
{
	$ret = $this->_mail_send_class->send( $param );
	if ( !$ret ) {
		$this->set_error( $this->_mail_send_class->get_errors() );
		return false;
	}
	return true;
}

function build_subject( $subject )
{
	$str  = $subject ;
	$str .= ' ['. $this->_xoops_sitename .'] ';
	$str .= $this->_MODULE_NAME ;
	return $str;
}

function build_body_by_tags( $tags, $template )
{
	$this->_mail_template_class->init_tag_array();
	$this->_mail_template_class->assign( $tags );
	return $this->_mail_template_class->replace_tag_array_by_template( $template );
}

// --- class end ---
}

?>