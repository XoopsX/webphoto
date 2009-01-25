<?php
// $Id: mail_retrieve.php,v 1.6 2009/01/25 10:25:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// webphoto_mail_retrieve -> webphoto_edit_mail_retrieve
// 2008-08-24 K.OHWADA
// preload
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_mail_retrieve
//=========================================================
class webphoto_main_mail_retrieve extends webphoto_edit_mail_retrieve
{
	var $_TIME_FAIL     = 5;
	var $_REDIRECT_THIS_URL;

	var $_DEBUG_MAIL_FILE = null ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_mail_retrieve( $dirname , $trust_dirname )
{
	$this->webphoto_edit_mail_retrieve( $dirname , $trust_dirname );

// preload
	$this->preload_init();
	$this->preload_constant();

	if ( $this->_DEBUG_MAIL_FILE ) {
		$this->_TIME_ACCESS = 1;
		$this->_FLAG_UNLINK_FILE = false;
	}
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_mail_retrieve( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// check
//---------------------------------------------------------
function check()
{
	switch ( $this->check_perm() )
	{
		case _C_WEBPHOTO_ERR_NO_PERM:
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAIL , _NOPERM ) ;
			exit ;
	}

	return true;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$title = $this->get_constant('TITLE_MAIL_RETRIEVE');
	$url   = $this->_MODULE_URL .'/index.php?fct=mail_retrieve';

	echo $this->build_bread_crumb( $title, $url );
	echo '<h3>'. $title ."</h3>\n";

	$post_submit = $this->_post_class->get_post('submit');

	if ( $post_submit ) {
		$this->submit();

	} else {
		$this->print_form();
	}
}

function submit()
{
	$this->set_flag_print_first_msg( true );

	if ( $this->_is_module_admin ) {
		$this->set_msg_level( _C_WEBPHOTO_MSG_LEVEL_ADMIN );
	} else {
		$this->set_msg_level( _C_WEBPHOTO_MSG_LEVEL_USER );
	}

	$this->retrieve();
	echo $this->get_msg();

	$this->print_goto_index();
}

function print_goto_index()
{
	echo "<br /><br />\n";
	echo '<a href="index.php">';
	echo $this->get_constant('GOTO_INDEX');
	echo "</a><br />\n";
}

function print_form()
{

	echo $this->get_constant('DSC_MAIL_RETRIEVE');
	echo "<br /><br />\n";

	$param = array(
		'title'        => $this->get_constant('TITLE_MAIL_RETRIEVE') ,
		'submit_value' => $this->get_constant('BUTTON_RETRIEVE') ,
	);

	$hidden_array = array(
		'fct' => 'mail_retrieve',
		'op'  => 'retrieve',
	);

	$form_class =& webphoto_lib_element::getInstance();
	echo $form_class->build_form_box_with_style( $param, $hidden_array );
}

// --- class end ---
}

?>