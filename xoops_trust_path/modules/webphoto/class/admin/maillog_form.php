<?php
// $Id: maillog_form.php,v 1.1 2010/09/19 06:43:11 ohwada Exp $

//=========================================================
// webphoto module
// 2010-09-17 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_maillog_form
//=========================================================
class webphoto_admin_maillog_form extends webphoto_edit_form
{
	var $_maillog_handler ;
	var $_cat_selbox_class;

	var $_SUB_TITLE_ARRAY = array(
		_AM_WEBPHOTO_MAILLOG_STATUS_REJECT, 
		_AM_WEBPHOTO_MAILLOG_STATUS_PARTIAL, 
		_AM_WEBPHOTO_MAILLOG_STATUS_SUBMIT, 
	);

	var $_UID_ADMIN      = 1;
	var $_CAT_ID_DEFAULT = 0;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_maillog_form( $dirname , $trust_dirname )
{
	$this->webphoto_edit_form( $dirname , $trust_dirname );

	$this->_maillog_handler 
		=& webphoto_maillog_handler::getInstance( $dirname, $trust_dirname );

	$this->_cat_selbox_class =& webphoto_cat_selbox::getInstance();
	$this->_cat_selbox_class->init( $dirname , $trust_dirname );

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_maillog_form( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// build_form
//---------------------------------------------------------
function build_form( $row )
{
	$template = 'db:'. $this->_DIRNAME .'_form_admin_maillog.html';

	$this->set_row( $row );

	$arr = array_merge( 
		$this->build_form_base_param(),
		$this->build_item_row( $row ) ,
		$this->build_form_maillog() ,
		$this->build_admin_language()
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	return $tpl->fetch( $template ) ;
}

function build_form_maillog()
{
	$userstart   = $this->_post_class->get_get('userstart'); 
	$show_submit = $this->show_submit();

	list( $show_user_list, $user_list, $uid_options )
		= $this->maillog_user_param( $show_submit, $userstart );

	$arr = array(
		'show_uid'           => $show_submit ,
		'show_cat_id'        => $show_submit ,
		'show_submit_button' => $show_submit ,

		'time_create_disp' => $this->get_row_time( 'maillog_time_create' ) ,
		'time_update_disp' => $this->get_row_time( 'maillog_time_update' ) ,
		'from_disp'        => $this->get_row_label( 'maillog_from' ) ,
		'subject_disp'     => $this->get_row_label( 'maillog_subject' ) ,
		'body_disp'        => $this->get_row_label( 'maillog_body' ) ,
		'photo_ids_disp'   => $this->photo_ids_disp() ,
		'status_disp'      => $this->status_disp() ,
		'file_disp'        => $this->file_disp() ,
		'attach_disp'      => $this->attach_disp() ,
		'comment_disp'     => $this->comment_disp() ,
		'cat_id_options'   => $this->cat_id_options( $show_submit ) ,

		'uid_options'      => $uid_options ,
		'show_user_list'   => $show_user_list ,
		'user_list'        => $user_list ,
	);
	return $arr;
}

function show_submit()
{
	$status = intval( $this->get_row_by_key( 'maillog_status' ) );

	if ( $status != _C_WEBPHOTO_MAILLOG_STATUS_SUBMIT ) {
		return true;
	}
	return false;
}

function photo_ids_disp()
{
	$photo_id_arr = $this->_maillog_handler->build_photo_ids_row_to_array( $this->get_row() );
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

function file_disp()
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

function attach_disp()
{
	$status = intval( $this->get_row_by_key( 'maillog_status' ) );

	$attach_arr = $this->_maillog_handler->build_attach_row_to_array( $this->get_row() );
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

function comment_disp()
{
	$str = $this->_maillog_handler->build_show_comment( $this->get_row() );
	$str = $this->substitute_empty( $str );
	return $str;
}

function status_disp()
{
	$status = $this->get_row_by_key( 'maillog_status' );
	return $this->get_sub_title_by_num( $status );
}

function get_sub_title_by_num( $num )
{
	if ( isset( $this->_SUB_TITLE_ARRAY[ $num ] ) ) {
		return  $this->_SUB_TITLE_ARRAY[ $num ];
	}
	return false;
}

function cat_id_options( $show_submit )
{
	if ( !$show_submit ) {
		return null;
	}

	return $this->_cat_selbox_class->build_selbox_options( 
		'cat_title', $this->_CAT_ID_DEFAULT, '' ) ;
}

function maillog_user_param( $show_submit, $userstart )
{
	if ( !$show_submit ) {
		return array( false, null, null );
	}

	return $this->get_user_param( $this->_UID_ADMIN, $userstart );
}

function build_admin_language()
{
	$arr = array(
		'lang_maillog_manager'    => $this->get_admin_title( 'MAILLOG_MANAGER' ) ,
		'lang_button_submit_mail' => _AM_WEBPHOTO_BUTTON_SUBMIT_MAIL  ,

	);
	return $arr;
}

// --- class end ---
}

?>