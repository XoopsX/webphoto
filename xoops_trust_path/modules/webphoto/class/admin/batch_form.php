<?php
// $Id: batch_form.php,v 1.1 2008/06/21 12:22:20 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_batch_form
//=========================================================
class webphoto_admin_batch_form extends webphoto_form_this
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_batch_form( $dirname , $trust_dirname )
{
	$this->webphoto_form_this( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_batch_form( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// batch form
//---------------------------------------------------------
function print_form_batch( $cat_selbox )
{
	$post_catid = $this->_post_class->get_post_int('cat_id');

	echo $this->build_form_begin();
	echo $this->build_input_hidden( 'fct', 'batch' );

	echo $this->build_table_begin();
	echo $this->build_line_title( _AM_WEBPHOTO_PHOTOBATCHUPLOAD );

	echo $this->build_line_ele( _WEBPHOTO_PHOTO_TITLE,        $this->_build_ba_ele_title() );
	echo $this->build_line_ele( _WEBPHOTO_PHOTO_DESCRIPTION,  $this->_build_ba_ele_desc() );
	echo $this->build_line_ele( _WEBPHOTO_CATEGORY,           $cat_selbox );
	echo $this->build_line_ele( _AM_WEBPHOTO_TEXT_DIRECTORY,  $this->_build_ba_ele_dir() );
	echo $this->build_line_ele( _WEBPHOTO_SUBMITTER,          $this->_build_ba_ele_uid() );
	echo $this->build_line_ele( _WEBPHOTO_PHOTO_TIME_UPDATE,  $this->_build_ba_ele_update() );
	echo $this->build_line_submit();

	echo $this->build_table_end();
	echo $this->build_form_end();

}

function _build_ba_ele_title()
{
	$post_title = $this->_post_class->get_post_text('title');

	$ele  = $this->build_input_text( 'title', $this->sanitize($post_title) );
	$ele .= "<br />\n";
	$ele .= _WEBPHOTO_DSC_TITLE_BLANK;
	return $ele;
}

function _build_ba_ele_desc()
{
	$post_desc = $this->_post_class->get_post_text('desc');
	$ele = $this->build_form_dhtml( 'desc', $this->sanitize($post_desc) );
	return $ele;
}

function _build_ba_ele_dir()
{
	$post_dir = $this->_post_class->get_post_text('dir');

	$ele  = _AM_WEBPHOTO_PHOTOPATH.' ' ;
	$ele .= $this->build_input_text( 'dir', $this->sanitize($post_dir) );
	$ele .= "<br />\n";
	$ele .= _AM_WEBPHOTO_DESC_PHOTOPATH;
	return $ele;
}

function _build_ba_ele_update()
{
	return $this->build_input_text( 'update', formatTimestamp( time() , _WEBPHOTO_DTFMT_YMDHI ) );
}

function _build_ba_ele_uid()
{
	$post_uid = $this->_post_class->get_post_int( 'uid', $this->_xoops_uid ) ;
	return $this->build_form_user_select( 'uid', $post_uid );
}

// --- class end ---
}

?>