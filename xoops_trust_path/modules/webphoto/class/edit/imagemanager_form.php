<?php
// $Id: imagemanager_form.php,v 1.4 2009/05/17 08:58:59 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-05-05 K.OHWADA
// build_form_param() -> build_form_base_param()
// 2009-04-19 K.OHWADA
// print_form_imagemanager() -> build_form_imagemanager()
// 2009-01-10 K.OHWADA
// webphoto_imagemanager_form -> webphoto_edit_imagemanager_form
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_imagemanager_form
//=========================================================
class webphoto_edit_imagemanager_form extends webphoto_edit_form
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_imagemanager_form( $dirname, $trust_dirname )
{
	$this->webphoto_edit_form( $dirname, $trust_dirname );
	$this->init_preload();
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_imagemanager_form( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// imagemanager
//---------------------------------------------------------
function build_form_imagemanager( $row, $param )
{
	$arr = array_merge(
		$this->build_form_base_param() ,
		$this->build_form_submit_imagemanager( $row, $param )
	);
	return $arr;
}

function build_form_submit_imagemanager( $row, $param )
{
	$has_resize    = $param['has_resize'];
	$allowed_exts  = $param['allowed_exts'];

	$this->set_row( $row );

	$arr = array(
		'max_file_size'        => $this->_cfg_fsize ,
		'ele_maxpixel'         => $this->ele_maxpixel( $has_resize ) ,
		'ele_maxsize'          => $this->ele_maxsize() ,
		'ele_allowed_exts'     => $this->ele_allowed_exts( $allowed_exts ) ,
		'ele_item_description' => $this->item_description_dhtml() ,
		'item_cat_id_options'  => $this->item_cat_id_options() ,
	);
	return $arr ;
}

// --- class end ---
}

?>