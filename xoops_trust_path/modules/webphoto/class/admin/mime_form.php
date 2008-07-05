<?php
// $Id: mime_form.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// added mime_ffmpeg
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_mime_form
//=========================================================
class webphoto_admin_mime_form extends webphoto_form_this
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_mime_form( $dirname , $trust_dirname )
{
	$this->webphoto_form_this( $dirname , $trust_dirname );
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

	echo $this->build_row_text( _WEBPHOTO_MIME_EXT,    'mime_ext' );
	echo $this->build_row_text( _WEBPHOTO_MIME_NAME,   'mime_name' );
	echo $this->build_row_text( _WEBPHOTO_MIME_TYPE,   'mime_type' );
	echo $this->build_row_text( _WEBPHOTO_MIME_FFMPEG, 'mime_ffmpeg' );
	echo $this->build_line_ele( _WEBPHOTO_MIME_PERMS,  $this->_build_ele_perms() );
	echo $this->build_line_ele( '', $button );

	echo $this->build_table_end();
	echo $this->build_form_end();
}

function _build_ele_perms()
{
	$group_objs = $this->get_xoops_group_objs();
	$perm_array = $this->str_to_array( $this->get_row_by_key('mime_perms', null, false), '&' );

	$text = '';
	foreach ( $group_objs as $obj )
	{
		$groupid = $obj->getVar('groupid');
		$name  = 'perms['. $groupid .']';
		$value = intval( in_array( $groupid, $perm_array ) );
		$text .= $this->build_input_checkbox_yes( $name, $value );
		$text .= $obj->getVar('name', 's');
	}
	return $text;
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