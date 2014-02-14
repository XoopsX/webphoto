<?php
// $Id: import_form.php,v 1.2 2009/01/24 07:10:39 ohwada Exp $

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
// class webphoto_admin_import_form
//=========================================================
class webphoto_admin_import_form extends webphoto_edit_form
{
	var $_SUBMIT_EXTRA;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_import_form( $dirname , $trust_dirname )
{
	$this->webphoto_edit_form( $dirname , $trust_dirname );

	$this->_SUBMIT_EXTRA = ' onclick="return confirm('. _AM_WEBPHOTO_IMPORTCONFIRM .');" ';

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_import_form( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// select myalbum 
//---------------------------------------------------------
function print_form_sel_myalbum( $title, $myalbum_options )
{
	$op     = 'import_category';
	$submit = 'GO STEP 2';

	echo $this->build_div_tag();

	echo $this->build_span_tag();
	echo $title;
	echo $this->build_span_end();
	echo "<br /><br />\n";

	echo $this->build_form_begin( 'myalbums' );
	echo $this->build_input_hidden( 'fct', 'import_myalbum' );
	echo $this->build_input_hidden( 'op',  $op );

	echo $this->build_form_select( 'src_dirname', null, $myalbum_options, 1 );
	echo "<br />\n";

	echo $this->build_input_submit( 'submit', $submit );
	echo $this->build_form_end();
	echo $this->build_div_end();
}

//---------------------------------------------------------
// import image
//---------------------------------------------------------
function print_form_image( $cat_rows )
{
	$selbox  = '<select name="imgcat_id">'."\n";
	$selbox .=  $this->_build_cat_options( $cat_rows );
	$selbox .= '</select>'."\n";

	$this->print_form_common( 'ImageManager', 'image', $selbox );
}

function _build_cat_options( $cat_rows )
{
	$options = '' ;
	foreach ( $cat_rows as $row )
	{
		$imgcat_id     = intval( $row['imgcat_id'] );
		$image_sum     = intval( $row['image_sum'] );
		$imgcat_name_s = $this->sanitize( $row['imgcat_name'] );

		$options .= '<option value="'. $imgcat_id .'">';
		$options .= $imgcat_name_s .' ('. $image_sum .')';
		$options .= '</option>'."\n" ;
	}

	return $options;
}

//---------------------------------------------------------
// import myalbum
//---------------------------------------------------------
function print_form_myalbum( $src_dirname, $selbox )
{
	$form = 'myalbum_'.$src_dirname;
	$this->print_form_common( $form, 'myalbum', $selbox, $src_dirname, true );
}

//---------------------------------------------------------
// import webphoto
//---------------------------------------------------------
function print_form_webphoto( $src_dirname, $selbox )
{
	$form = 'webphoto_'.$src_dirname;

	$this->print_form_common( $form, 'webphoto', $selbox, $src_dirname, true );
}

//---------------------------------------------------------
// coommon
//---------------------------------------------------------
function print_form_common( $form, $op, $selbox, $src_dirname=null, $flag_comment=false )
{
	echo $this->build_div_tag();
	echo $this->build_form_begin( $form );
	echo $this->build_input_hidden( 'fct', 'import' );
	echo $this->build_input_hidden( 'op',  $op );
	echo $this->build_input_hidden( 'src_dirname', $src_dirname );

	if ( $flag_comment ) {
		echo $this->build_input_radio( 'copy_comment', 1, $this->_CHECKED );
		echo _AM_WEBPHOTO_IMPORT_COMMENT_YES.' &nbsp; ';
		echo $this->build_input_radio( 'copy_comment', 0 );
		echo _AM_WEBPHOTO_IMPORT_COMMENT_NO.' &nbsp; ';
		echo "<br /><br />\n";
	}

	echo $selbox;

	echo $this->build_input_submit( 'submit', _GO, $this->_SUBMIT_EXTRA );
	echo $this->build_form_end();
	echo $this->build_div_end();
}

// --- class end ---
}

?>