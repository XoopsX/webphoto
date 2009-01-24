<?php
// $Id: export_form.php,v 1.2 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// webphoto_form_this -> webphoto_edit_form
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_export_form
//=========================================================
class webphoto_admin_export_form extends webphoto_edit_form
{
	var $_SUBMIT_EXTRA;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_export_form( $dirname , $trust_dirname )
{
	$this->webphoto_edit_form( $dirname , $trust_dirname );

	$this->_SUBMIT_EXTRA = ' onclick="return confirm('. _AM_WEBPHOTO_EXPORTCONFIRM .');" ';

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_export_form( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// export image
//---------------------------------------------------------
function print_form_image( $src_selbox, $dst_selbox )
{
	$this->print_form_common( 'ImageManager', 'image', $src_selbox, $dst_selbox, null, true );
}

//---------------------------------------------------------
// common
//---------------------------------------------------------
function print_form_common( $form, $op, $src_selbox, $dst_selbox, $dst_dirname=null, $flag_thumb=false )
{
	echo $this->build_div_tag();
	echo $this->build_form_begin( $form );
	echo $this->build_input_hidden( 'fct', 'export' );
	echo $this->build_input_hidden( 'op',  $op );
	echo $this->build_input_hidden( 'dst_dirname', $dst_dirname );

	echo $src_selbox;
	echo _AM_WEBPHOTO_FMT_EXPORTIMSRCCAT;
	echo ' &nbsp; -> &nbsp; ';

	echo $dst_selbox;
	echo _AM_WEBPHOTO_FMT_EXPORTIMDSTCAT;
	echo "<br /><br />\n";

	if ( $flag_thumb ) {
		echo $this->build_input_checkbox( 'use_thumb', '1', $this->_CHECKED);
		echo _AM_WEBPHOTO_CB_EXPORTTHUMB;
		echo "<br /><br />\n";
	}

	echo $this->build_input_submit( 'submit', _GO, $this->_SUBMIT_EXTRA );
	echo $this->build_form_end();
	echo $this->build_div_end();

}

// --- class end ---
}

?>