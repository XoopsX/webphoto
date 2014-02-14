<?php
// $Id: maillog_table_manage.php,v 1.3 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_maillog_handler
// 2009-01-10 K.OHWADA
// webphoto_mail_unlink -> webphoto_edit_mail_unlink
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_maillog_table_manage
//=========================================================
class webphoto_admin_maillog_table_manage extends webphoto_lib_manage
{
	var $_unlink_class ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_maillog_table_manage( $dirname , $trust_dirname )
{
	$this->webphoto_lib_manage( $dirname , $trust_dirname );
	$this->set_manage_handler( 
		webphoto_maillog_handler::getInstance( $dirname , $trust_dirname ) );
	$this->set_manage_title_by_name( 'MAILLOG_TABLE_MANAGE' );

	$this->set_manage_list_column_array(
		array( 'maillog_from', 'maillog_subject' ) );

	$this->_unlink_class =& webphoto_edit_mail_unlink::getInstance( $dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_maillog_table_manage( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$this->_main();
}

//=========================================================
// override for caller
//=========================================================
function _build_row_by_post()
{
	$row = array(
		'maillog_id'           => $this->_post_class->get_post_get_int( 'maillog_id' ),
		'maillog_time_create'  => $this->_post_class->get_post_int(   'maillog_time_create' ),
		'maillog_time_update'  => $this->_post_class->get_post_int(   'maillog_time_update' ),
		'maillog_photo_ids'    => $this->_post_class->get_post_int(   'maillog_photo_ids' ),
		'maillog_status'       => $this->_post_class->get_post_int(   'maillog_status' ),
		'maillog_from'         => $this->_post_class->get_post_text(  'maillog_from' ),
		'maillog_subject'      => $this->_post_class->get_post_text(  'maillog_subject' ),
		'maillog_body'         => $this->_post_class->get_post_int(   'maillog_body' ),
		'maillog_file'         => $this->_post_class->get_post_text(  'maillog_file' ),
		'maillog_attach'       => $this->_post_class->get_post_text(  'maillog_attach' ),
		'maillog_comment'      => $this->_post_class->get_post_text(  'maillog_comment' ),
	);

	return $row;
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function _manage_delete_option( $row )
{
	$this->_unlink_class->unlink_by_maillog_row( $row );
}

function _manage_delete_all_each_option( $row )
{
	$this->_unlink_class->unlink_by_maillog_row( $row );
}

//---------------------------------------------------------
// form
//---------------------------------------------------------
function _print_form( $row )
{
	echo $this->build_manage_form_begin( $row );

	echo $this->build_table_begin();
	echo $this->build_manage_header( );

	echo $this->build_manage_id();
	echo $this->build_comp_text( 'maillog_time_create' );
	echo $this->build_comp_text( 'maillog_time_update' );
	echo $this->build_comp_text( 'maillog_photo_ids' );
	echo $this->build_comp_text( 'maillog_status' );
	echo $this->build_comp_text( 'maillog_from' );
	echo $this->build_comp_text( 'maillog_subject' );
	echo $this->build_comp_text( 'maillog_body' );
	echo $this->build_comp_text( 'maillog_file' );
	echo $this->build_comp_textarea( 'maillog_attach' );
	echo $this->build_comp_textarea( 'maillog_comment' );

	echo $this->build_manage_submit();

	echo "</table></form>\n";
}

// --- class end ---
}

?>