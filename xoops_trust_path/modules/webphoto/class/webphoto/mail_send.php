<?php
// $Id: mail_send.php,v 1.2 2010/02/08 01:42:02 ohwada Exp $

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
	$from_email = $this->_xoops_adminmail ;
	$subject    = $this->build_subject( $this->get_constant('MAIL_SUBMIT_WAITING') ) ;
	$body       = $this->build_waiting_common_body( $row, 'global_waiting_notify.tpl' ) ;

	$users = $this->build_waiting_users( $row );
	foreach ( $users as $user )
	{
		$param = array(
			'to_emails'  => $user->getVar('email','n') ,
			'from_email' => $from_email ,
			'subject'    => $subject ,
			'body'       => $this->build_waiting_body( $user, $body ),
			'debug'      => true,
		);

		$this->send_by_param( $param );
	}
}

function build_waiting_users( $item_row )
{
	$users = null;

// module admin
	$groupid_admin = $this->get_config_by_name('groupid_admin');
	if ( $groupid_admin ) {
		$users = $this->get_users_by_groupid( $groupid_admin );
	}

// xoops admin
	if ( !is_array($users) || !count($users) ) {
		$users = $this->get_users_by_groupid( XOOPS_GROUP_ADMIN );
	}

	return $users;
}

function get_users_by_groupid( $group_id )
{
	return $this->_xoops_class->get_member_users_by_group( $group_id, true );
}

function build_waiting_common_body( $row, $template )
{
	$url = $this->_MODULE_URL .'/admin/index.php?fct=item_manager&op=modify_form&item_id='. $row['item_id'] ;
	$tags = array( 
		'PHOTO_TITLE' => $row['item_title'] ,
		'WAITING_URL' => $url ,
	 );

	return $this->build_body_by_tags( $tags, $template );
}

function build_waiting_body( $user, $str )
{
	$tags = array( 
		'X_UNAME' => $user->getVar('uname') ,
	 );

	return $this->_mail_template_class->replace_str_by_tags( $str, $tags );
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