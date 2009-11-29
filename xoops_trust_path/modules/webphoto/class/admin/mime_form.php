<?php
// $Id: mime_form.php,v 1.7 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_mime_handler
// 2009-10-25 K.OHWADA
// mime_kind
// 2009-04-27 K.OHWADA
// _build_script() -> build_script_edit_js()
// 2009-01-10 K.OHWADA
// webphoto_form_this -> webphoto_edit_form
// 2008-12-12 K.OHWADA
// build_ele_group_perms_by_key()
// 2008-07-01 K.OHWADA
// added mime_ffmpeg
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_mime_form
//=========================================================
class webphoto_admin_mime_form extends webphoto_edit_form
{
	var $_mime_handler;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_mime_form( $dirname , $trust_dirname )
{
	$this->webphoto_edit_form( $dirname , $trust_dirname );

	$this->_mime_handler =& webphoto_mime_handler::getInstance(
		$dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_mime_form( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// print form
//---------------------------------------------------------
function print_form_mimetype( $row )
{
	$this->set_row( $row );

	$mime_id = $row['mime_id'];

	$extra_submit  = 'onclick="this.form.elements.op.value=\'save\'" ';
	$extra_delete  = 'onclick="this.form.elements.op.value=\'delete\'" ';
	$extra_cancel  = 'onclick="history.go(-1)" ';
	$button_cancel = $this->build_input_button( 'cancel', _CANCEL, $extra_cancel );

	echo $this->build_script_edit_js();

	echo $this->build_form_tag( 'mimetype' );
	echo $this->build_html_token();
	echo $this->build_input_hidden( 'fct', 'mimetypes' );
	echo $this->build_input_hidden( 'op',  'save' );

	if ( $mime_id > 0 )
	{
		echo $this->build_input_hidden( 'mime_id', $mime_id );
		echo $this->build_table_begin();
		echo $this->build_line_title( _AM_WEBPHOTO_MIME_MODIFYF );

		$button  = $this->build_input_submit( 'submit', _EDIT,   $extra_submit );
		$button .= $this->build_input_submit( 'delete', _DELETE, $extra_delete  );
		$button .= $button_cancel;

	} else {
		echo $this->build_table_begin();
		echo $this->build_line_title( _AM_WEBPHOTO_MIME_CREATEF );

		$button  = $this->build_input_submit( 'submit', _ADD, $extra_submit  );
		$button .= $this->build_input_reset(  'reset',  _WEBPHOTO_BUTTON_CLEAR );
		$button .= $button_cancel;

	}

	$this->_mime_handler->get_kind_options();

	echo $this->build_row_text( _WEBPHOTO_MIME_EXT,    'mime_ext' );
	echo $this->build_row_text( _WEBPHOTO_MIME_NAME,   'mime_name' );
	echo $this->build_row_text( _WEBPHOTO_MIME_TYPE,   'mime_type' );
//	echo $this->build_row_text( _WEBPHOTO_MIME_FFMPEG, 'mime_ffmpeg' );
	echo $this->build_line_ele( _WEBPHOTO_MIME_KIND,   $this->_build_ele_kind() );
	echo $this->build_row_text( _WEBPHOTO_MIME_OPTION, 'mime_option' );
	echo $this->build_line_ele( _WEBPHOTO_MIME_PERMS,  $this->_build_ele_perms() );
	echo $this->build_line_ele( '', $button );

	echo $this->build_table_end();
	echo $this->build_form_end();
}

function _build_ele_kind()
{
	$name    = 'mime_kind';
	$value   = $this->get_row_by_key( $name );
	$options = $this->_mime_handler->get_kind_options();
	return $this->build_form_select( $name, $value, $options, 1 );
}

function _build_ele_perms()
{
	return $this->build_ele_group_perms_by_key( 'mime_perms' );
}

function _build_script()
{
	return $this->build_js_envelop( $this->build_js_check_all() );
}

//---------------------------------------------------------
// print form
//---------------------------------------------------------
function print_form_mimefind()
{
	$extra = 'onclick="this.form.elements.op.value=\'openurl\'"';

	echo $this->build_form_tag( 'mimefind' );
	echo $this->build_html_token();
	echo $this->build_input_hidden( 'fct', 'mimetypes' );
	echo $this->build_input_hidden( 'op',  'openurl' );

	echo $this->build_table_begin();
	echo $this->build_line_title( _AM_WEBPHOTO_MIME_FINDMIMETYPE );

	echo $this->build_line_ele( 
		_AM_WEBPHOTO_MIME_EXTFIND, $this->build_input_text( 'fileext', '' ) );

	echo $this->build_line_ele( 
		'', $this->build_input_submit( 'submit', _AM_WEBPHOTO_MIME_FINDIT, $extra ) );

	echo $this->build_table_end();
	echo $this->build_form_end();
}

// --- class end ---
}

?>