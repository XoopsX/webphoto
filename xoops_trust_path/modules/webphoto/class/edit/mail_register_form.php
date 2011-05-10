<?php
// $Id: mail_register_form.php,v 1.3 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// build_form_register()
// 2010-09-17 K.OHWADA
// build_form_user()
// 2009-01-10 K.OHWADA
// webphoto_mail_register_form -> webphoto_edit_mail_register_form
// 2008-08-24 K.OHWADA
// added print_user_form()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_mail_register_form
//=========================================================
class webphoto_edit_mail_register_form extends webphoto_edit_form
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_mail_register_form( $dirname, $trust_dirname )
{
	$this->webphoto_edit_form( $dirname, $trust_dirname );
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_mail_register_form( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// user form
//---------------------------------------------------------
function print_user_form( $row )
{
	$userstart = $this->_post_class->get_get('userstart'); 

	$template = 'db:'. $this->_DIRNAME .'_form_mail_user.html';

	$this->set_row( $row );

	$arr = array_merge( 
		$this->build_form_base_param(),
		$this->build_form_user( $userstart )
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	echo $tpl->fetch( $template ) ;
}

function build_form_user( $userstart )
{
	$uid = $this->get_row_by_key( 'user_uid' );

	list( $show_user_list, $user_list, $user_uid_options )
		= $this->get_user_param( $uid, $userstart );

	$arr = array(
		'user_uid_options' => $user_uid_options ,
		'show_user_list'   => $show_user_list ,
		'user_list'        => $user_list ,
	);
	return $arr;
}

function _build_ele_user_submitter()
{
	$uid  = $this->get_row_by_key( 'user_uid' );
	$list = $this->get_xoops_user_list( 0, 0 );
	$text = $this->build_form_user_select( $list, 'user_uid', $uid, false );
	return $text;
}

//---------------------------------------------------------
// submit form
//---------------------------------------------------------
function print_submit_form( $row, $param )
{
	$template = 'db:'. $this->_DIRNAME .'_form_mail_register.html';

	$arr = array_merge( 
		$this->build_form_base_param(),
		$this->build_form_register( $row, $param ),
		$this->build_item_row( $row )
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	echo $tpl->fetch( $template ) ;
}

function build_form_register( $row, $param )
{
	$mode = $param['mode'];
	
	switch ($mode)
	{
		case 'edit':
			$submit = _EDIT;
			break;
		
		case 'add':
		default:
			$submit = $this->get_constant('BUTTON_REGISTER');
			break;
	}

	$this->set_row( $row );

	$arr = array(
		'ele_user_cat_id' => $this->_ele_user_cat_id() ,
		'submitter'       => $this->_submitter(),
		'button_submit'   => $submit ,
	);
	return $arr;
}

function _submitter()
{
	$uid   = $this->get_row_by_key( 'user_uid' );
	return $this->_xoops_class->get_user_uname_from_id( $uid );
}

function _ele_user_cat_id()
{
	return $this->_cat_handler->build_selbox_with_perm_post(
		$this->get_row_by_key( 'user_cat_id' ) , 'user_cat_id' );
}

// --- class end ---
}

?>