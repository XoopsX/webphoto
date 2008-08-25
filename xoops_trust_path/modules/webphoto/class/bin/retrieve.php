<?php
// $Id: retrieve.php,v 1.1 2008/08/25 19:30:22 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-24 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_bin_retrieve
//=========================================================
class webphoto_bin_retrieve extends webphoto_bin_base
{
	var $_config_class;
	var $_retrieve_class;

	var $_TITLE = 'webphoto mail retrieve';

	var $_FLAG_MAIL_SEND = true;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_bin_retrieve( $dirname , $trust_dirname )
{
	$this->webphoto_bin_base( $dirname , $trust_dirname );

	$this->_config_class =& webphoto_config::getInstance( $dirname );

	$this->_retrieve_class =& webphoto_mail_retrieve::getInstance( $dirname , $trust_dirname );
	$this->_retrieve_class->set_flag_force_db( true );
	$this->_retrieve_class->set_flag_print_first_msg( true );

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_bin_retrieve( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$pass = $this->_config_class->get_by_name( 'bin_pass' );

	$this->set_env_param();

	if ( !$this->check_pass($pass) ) {
		return false;
	}

	if ( $this->_flag_print ) {
		$this->_retrieve_class->set_msg_level( _C_WEBPHOTO_MSG_LEVEL_ADMIN );
	}

	$this->print_write_data( $this->get_html_header() );

	$this->_retrieve_class->retrieve();
	$count = $this->_retrieve_class->get_mail_count();

	$this->print_write_data( $this->get_html_footer() );

	if ( $this->_FLAG_MAIL_SEND && $count ) {
		$text = "mail count: $count ";
		$this->send_mail( $this->_adminmail, $this->_TITLE, $text );
	}
}

// --- class end ---
}

?>