<?php
// $Id: mail_unlink.php,v 1.1 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// webphoto_mail_unlink -> webphoto_edit_mail_unlink
// 2008-11-08 K.OHWADA
// TMP_DIR -> MAIL_DIR
// 2008-08-24 K.OHWADA
// added unlink_attaches()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_mail_unlink
//=========================================================
class webphoto_edit_mail_unlink
{
	var $_config_class;
	var $_utility_class;

	var $_WORK_DIR;
	var $_MAIL_DIR;
	var $_SEPARATOR = '|';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_mail_unlink( $dirname )
{
	$this->_config_class  =& webphoto_config::getInstance( $dirname );
	$this->_utility_class =& webphoto_lib_utility::getInstance();

	$this->_WORK_DIR  = $this->_config_class->get_by_name( 'workdir' );
	$this->_MAIL_DIR  = $this->_WORK_DIR .'/mail' ;

}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_mail_unlink( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// unlink
//---------------------------------------------------------
function unlink_by_maillog_row( $row )
{
	$this->unlink_file( $row );
	$this->unlink_attaches( $row );
}

function unlink_file( $row )
{
	$this->unlink_by_filename( $row['maillog_file'] );
}

function unlink_attaches( $row )
{
	$attach_array = $this->_utility_class->str_to_array( $row['maillog_attach'], $this->_SEPARATOR );
	if ( !is_array($attach_array) ) {
		return;	// no action
	}
	foreach( $attach_array as $attach ) {
		$this->unlink_by_filename( $attach );
	}
}

function unlink_by_filename( $file )
{
	if ( $file ) {
		$this->_utility_class->unlink_file( $this->_MAIL_DIR.'/'.$file );
	}
}

// --- class end ---
}

?>