<?php
// $Id: maillog_manager.php,v 1.7 2010/09/19 06:43:11 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-17 K.OHWADA
// webphoto_admin_maillog_form
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_maillog_handler
// 2009-01-10 K.OHWADA
// webphoto_edit_mail_photo -> webphoto_edit_mail_photo
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_maillog_table_manage
//=========================================================
class webphoto_admin_maillog_manager extends webphoto_lib_manage
{
	var $_config_class;
	var $_cat_handler;
	var $_item_handler;
	var $_photo_class;
	var $_form_class;
	var $_unlink_class;
	var $_maillog_form_class ;

	var $_SUB_TITLE_ARRAY = array(
		_AM_WEBPHOTO_MAILLOG_STATUS_REJECT, 
		_AM_WEBPHOTO_MAILLOG_STATUS_PARTIAL, 
		_AM_WEBPHOTO_MAILLOG_STATUS_SUBMIT, 
	);

	var $_ADMIN_UID = 1;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_maillog_manager( $dirname , $trust_dirname )
{
	$this->webphoto_lib_manage( $dirname , $trust_dirname );
	$this->set_manage_handler( 
		webphoto_maillog_handler::getInstance( $dirname, $trust_dirname ) );
	$this->set_manage_title_by_name( 'MAILLOG_MANAGER' );

	$this->set_manage_list_column_array(
		array( 'maillog_from', 'maillog_subject' ) );

	$this->set_manage_sub_title_array( $this->_SUB_TITLE_ARRAY );
	$this->set_manage_desc( null );
	$this->set_lang_show_list( _AM_WEBPHOTO_SHOW_LIST );

	$this->_config_class  =& webphoto_config::getInstance( $dirname );
	$this->_unlink_class  =& webphoto_edit_mail_unlink::getInstance( $dirname );

	$this->_item_handler  
		=& webphoto_item_handler::getInstance( $dirname , $trust_dirname );
	$this->_cat_handler   
		=& webphoto_cat_handler::getInstance( $dirname , $trust_dirname );
	$this->_form_class    
		=& webphoto_edit_form::getInstance( $dirname , $trust_dirname );

	$this->_maillog_form_class 
		=& webphoto_admin_maillog_form::getInstance( $dirname , $trust_dirname );

	$this->_photo_class   
		=& webphoto_edit_mail_photo::getInstance( $dirname , $trust_dirname );

	$this->_photo_class->set_mail_groups( XOOPS_GROUP_ADMIN );
	$this->_photo_class->set_msg_level( _C_WEBPHOTO_MSG_LEVEL_ADMIN );
	$this->_photo_class->set_flag_print_first_msg( true );
	$this->_photo_class->set_flag_strict( false );

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_maillog_manager( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	switch ( $this->_get_op() )
	{
		case 'add':
		case 'edit':
		case 'delete':
		case 'edit_all':
		case 'delete_all':
		case 'submit':
			if ( !$this->check_token() ) {
				xoops_cp_header();
				$this->manage_form_with_error( 'Token Error' );
				xoops_cp_footer();
				exit();
			}
			$this->_execute();
			break;

		case 'form':
			xoops_cp_header();
			$this->manage_form();
			break;

		case 'list':
		default:
			xoops_cp_header();
			$this->manage_list();
			break;
	}

	echo $this->build_admin_footer();
	xoops_cp_footer();
	exit();

}

function _execute()
{
	switch ( $this->_get_op() )
	{
		case 'add':
			$this->manage_add();
			break;

		case 'edit':
			$this->manage_edit();
			break;

		case 'delete':
			$this->manage_delete();
			break;

		case 'delete_all':
			$this->manage_delete_all();
			break;
			
		case 'submit':
			$this->_submit();
			break;
	}
}

function _get_op()
{
	if ( $this->_post_class->get_post( 'add' ) ) {
		return 'add';
	} elseif ( $this->_post_class->get_post( 'edit' ) ) {
		return 'edit';
	} elseif ( $this->_post_class->get_post( 'delete' ) ) {
		return 'delete';
	} elseif ( $this->_post_class->get_post( 'delete_all' ) ) {
		return 'delete_all';
	} elseif ( $this->_post_class->get_post( 'submit' ) ) {
		return 'submit';
	}

	return $this->_post_class->get_post_get( 'op' );
}

//---------------------------------------------------------
// submit
//---------------------------------------------------------
function _submit()
{
	$id     = $this->_post_class->get_post_get_int( 'maillog_id' );
	$uid    = $this->_post_class->get_post_int( 'uid' );
	$cat_id = $this->_post_class->get_post_int( 'cat_id' );
	$attach = $this->_post_class->get_post( 'attach' );

// exit if no record
	$row = $this->_manage_get_row_or_exit( $id );

	xoops_cp_header();

	echo $this->build_manage_bread_crumb();
	echo $this->build_show_title();

	echo "<h4>".$this->get_constant('SUBTITLE_MAIL_PHOTO')."</h4>\n";

// select attach file
	if ( is_array($attach) && count($attach) ) {
		$param = $this->_photo_class->parse_single_mail(
			$id, $row['maillog_file'], array_keys($attach) );

		if ( is_array($param) ) {
			$param['uid']    = $uid;
			$param['cat_id'] = $cat_id;
			$this->_photo_class->add_photos_from_single_mail( $param );
		}

// not select
	} else {
		echo $this->highlight( _AM_WEBPHOTO_ERR_MAILLOG_NO_ATTACH )."<br  />\n";
	}

	echo $this->build_admin_footer();
	xoops_cp_footer();
	exit();
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
// list
//---------------------------------------------------------
function _get_count_by_sortid( $sortid )
{
	switch ( $sortid )
	{
		case 1:
			$count = $this->_manage_handler->get_count_by_status(
				_C_WEBPHOTO_MAILLOG_STATUS_PARTIAL );
			break;

		case 2:
			$count = $this->_manage_handler->get_count_by_status( 
				_C_WEBPHOTO_MAILLOG_STATUS_SUBMIT );
			break;

		case 0:
		default:
			$count = $this->_manage_handler->get_count_by_status( 
				_C_WEBPHOTO_MAILLOG_STATUS_REJECT );
			break;
	}

	return $count;
}

function _get_list_rows( $limit, $start )
{
	switch ( $this->pagenavi_get_sortid() )
	{
		case 1:
			$rows = $this->_manage_handler->get_rows_desc_by_status( 
				_C_WEBPHOTO_MAILLOG_STATUS_PARTIAL, $limit, $start );
			break;

		case 2:
			$rows = $this->_manage_handler->get_rows_desc_by_status( 
				_C_WEBPHOTO_MAILLOG_STATUS_SUBMIT, $limit, $start );
			break;

		case 0:
		default:
			$rows = $this->_manage_handler->get_rows_desc_by_status( 
				_C_WEBPHOTO_MAILLOG_STATUS_REJECT, $limit, $start );
			break;
	}

	return $rows;
}

function build_show_add_record()
{
	return null;
}

//---------------------------------------------------------
// form
//---------------------------------------------------------
function _print_form( $row )
{
	echo $this->_maillog_form_class->build_form( $row );
}

function XXX_print_form( $row )
{
	$status = intval( $row['maillog_status'] );

	echo $this->build_manage_form_begin( $row );

	echo $this->build_table_begin();
	echo $this->build_manage_header( );

	echo $this->build_manage_id();

	if ( $status != _C_WEBPHOTO_MAILLOG_STATUS_SUBMIT ) {
		echo $this->build_line_ele( $this->get_constant('SUBMITTER'), 
			$this->_build_ele_submitter() );

		echo $this->build_line_ele( $this->get_constant('CATEGORY'), 
			$this->_build_ele_category() );
	}

	echo $this->build_comp_label_time( 'maillog_time_create' );
	echo $this->build_comp_label_time( 'maillog_time_update' );

	echo $this->build_line_ele( $this->get_constant('maillog_photo_ids'), 
		$this->_build_ele_photo_ids() );

	echo $this->build_line_ele( $this->get_constant('maillog_status'), 
		$this->_build_ele_status() );

	echo $this->build_comp_label( 'maillog_from' );
	echo $this->build_comp_label( 'maillog_subject' );
	echo $this->build_comp_label( 'maillog_body' );

	echo $this->build_line_ele( $this->get_constant('maillog_file'), 
		$this->_build_ele_file() );

	echo $this->build_line_ele( $this->get_constant('maillog_attach'), 
		$this->_build_ele_attach( $status ) );

	echo $this->build_line_ele( $this->get_constant('maillog_comment'), 
		$this->_build_ele_comment() );

	echo $this->_build_line_submit( $status );

	echo "</table></form>\n";
}

function _build_line_submit( $status )
{
	$text = '';
	if ( $status != _C_WEBPHOTO_MAILLOG_STATUS_SUBMIT ) {
		$text .= $this->build_input_submit( 'submit',  _AM_WEBPHOTO_BUTTON_SUBMIT_MAIL );
	}
	$text .= $this->build_input_submit( 'delete', _DELETE );
	return $this->build_line_buttom( $text );
}

function _build_ele_submitter()
{
	$USER_LIMIT = 0;
	$USER_START = 0;
	$list = $this->_form_class->get_xoops_user_list( $USER_LIMIT, $USER_START );
	return $this->_form_class->build_form_user_select(
		$list, 'uid', $this->_ADMIN_UID );
}

function _build_ele_category()
{
	return $this->_cat_handler->build_selbox_catid(
		$this->get_row_by_key( 'cat_id' ) , 'cat_id' );
}

function _build_ele_photo_ids()
{
	$photo_id_arr = $this->_manage_handler->build_photo_ids_row_to_array( $this->get_row() );
	if ( !is_array($photo_id_arr) || !count($photo_id_arr) ) {
		return $this->_TEXT_EMPTY_SUBSUTITUTE ;
	}

	$text = '';
	foreach ( $photo_id_arr as $photo_id )
	{
		$photo_id = intval($photo_id);
		$url = $this->_MODULE_URL .'/index.php?fct=photo&amp;p='. $photo_id ;
		$title_s = $this->_item_handler->get_cached_value_by_id_name(
			$photo_id, 'photo_title', true );

		$text .= '<a href="'. $url .'" target="_blank">';
		$text .= sprintf( '%03d',  $photo_id );
		$text .= ' : ';
		$text .= $title_s;
		$text .= "</a><br />\n";
	}
	return $text;
}

function _build_ele_status()
{
	$status = $this->get_row_by_key( 'maillog_status' );
	return $this->get_sub_title_by_num( $status );
}

function _build_ele_file()
{
	$file = $this->get_row_by_key( 'maillog_file' );
	if ( empty($file) ) {
		return $this->_TEXT_EMPTY_SUBSUTITUTE ;
	}

	$file_s = $this->sanitize( $file );
	$file_r = rawurlencode( $file );
	$url    = $this->_MODULE_URL .'/admin/index.php?fct=text&amp;name='. $file_r ;

	$text  = '<a href="'. $url .'" target="_blank">';
	$text .= $file_s ;
	$text .= '</a> ';
	return $text;
}

function _build_ele_attach( $status )
{
	$attach_arr = $this->_manage_handler->build_attach_row_to_array( $this->get_row() );
	if ( !is_array($attach_arr) || !count($attach_arr) ) {
		return $this->_TEXT_EMPTY_SUBSUTITUTE ;
	}

	$text = '';
	foreach ( $attach_arr as $file ) 
	{
		$file_s = $this->sanitize( $file );
		if ( $status != _C_WEBPHOTO_MAILLOG_STATUS_SUBMIT ) {
			$name  = 'attach['. $file_s .']';
			$text .= $this->build_input_checkbox_yes( $name, _C_WEBPHOTO_YES ) ;
		}
		$text .= $file_s ;
		$text .= "<br />\n";
	}
	return $text;
}

function _build_ele_comment()
{
	$str = $this->_manage_handler->build_show_comment( $this->get_row() );
	$str = $this->substitute_empty( $str );
	return $str;
}

// --- class end ---
}

?>