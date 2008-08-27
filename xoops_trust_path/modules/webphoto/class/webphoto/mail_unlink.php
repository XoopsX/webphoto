<?php
// $Id: mail_unlink.php,v 1.2 2008/08/27 03:58:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// added unlink_attaches()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_mail_delete
//=========================================================
class webphoto_mail_unlink
{
	var $_config_class;
	var $_utility_class;

	var $_TMP_DIR;
	var $_SEPARATOR = '|';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_mail_unlink( $dirname )
{
	$this->_config_class  =& webphoto_config::getInstance( $dirname );
	$this->_utility_class =& webphoto_lib_utility::getInstance();

	$this->_TMP_DIR  = $this->_config_class->get_by_name( 'tmpdir' );

}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_mail_unlink( $dirname );
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
		$this->_utility_class->unlink_file( $this->_TMP_DIR.'/'.$file );
	}
}

// --- class end ---
}

?>