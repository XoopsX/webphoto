<?php
// $Id: photo_form.php,v 1.4 2009/04/19 11:39:45 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-04-19 K.OHWADA
// print_form_common() -> build_form_photo()
// 2009-03-15 K.OHWADA
// _build_ele_small_file()
// 2009-01-25 K.OHWADA
// item_content
// 2009-01-10 K.OHWADA
// webphoto_photo_edit_form -> webphoto_edit_photo_form
// is_embed_kind()
// 2009-01-04 K.OHWADA
// webphoto_editor
// _init_editor()
// 2008-12-12 K.OHWADA
// webphoto_inc_catlist
// _build_ele_perm_read()
// 2008-12-07 K.OHWADA
// build_show_file_image()
// 2008-11-29 K.OHWADA
// _build_file_url()
// 2008-11-16 K.OHWADA
// _build_ele_codeinfo()
// image -> image_tmp
// BUG: sanitize twice
// 2008-11-08 K.OHWADA
// _build_ele_middle_file_external()
// 2008-10-01 K.OHWADA
// build_ele_embed_type() etc
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// used preload_init()
// 2008-08-01 K.OHWADA
// added print_form_file()
// 2008-07-01 K.OHWADA
// added print_form_video()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_photo_form
//=========================================================
class webphoto_edit_photo_form extends webphoto_edit_form
{
	var $_gicon_handler;
	var $_player_handler;
	var $_perm_class;
	var $_tag_class;
	var $_embed_class;
	var $_editor_class;
	var $_kind_class;

	var $_cfg_gmap_apikey ;
	var $_cfg_width ;
	var $_cfg_height ;
	var $_cfg_fsize ;
	var $_cfg_makethumb ;
	var $_cfg_file_size ;
	var $_cfg_perm_item_read ;

	var $_has_deletable ;

	var $_xoops_db_groups  = null;

	var $_editor_show = false ;
	var $_editor_js   = null ;
	var $_editor_desc = null ;

// preload
	var $_ARRAY_PHOTO_TEXT = null;
	var $_SHOW_EXTERNAL_URL    = true;
	var $_SHOW_EXTERNAL_THUMB  = true;
	var $_SHOW_EXTERNAL_MIDDLE = true;

	var $_ARRAY_FILE_ID = array(
		_C_WEBPHOTO_FILE_KIND_VIDEO_FLASH, _C_WEBPHOTO_FILE_KIND_PDF, _C_WEBPHOTO_FILE_KIND_SWF
	);

	var $_DESCRIPTION_ROWS  = 5;
	var $_DESCRIPTION_COLS  = 50;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_photo_form( $dirname, $trust_dirname )
{
	$this->webphoto_edit_form( $dirname, $trust_dirname );

	$this->_embed_class    =& webphoto_embed::getInstance( $dirname, $trust_dirname );
	$this->_editor_class   =& webphoto_editor::getInstance( $dirname, $trust_dirname );
	$this->_gicon_handler  =& webphoto_gicon_handler::getInstance( $dirname );
	$this->_player_handler =& webphoto_player_handler::getInstance( $dirname );
	$this->_kind_class     =& webphoto_kind::getInstance();

	$this->_tag_class =& webphoto_tag::getInstance( $dirname );
	$this->_tag_class->set_is_japanese( $this->_is_japanese );
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_photo_form( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// form file
//---------------------------------------------------------
function build_form_file( $param )
{
	$has_resize    = $param['has_resize'];
	$allowed_exts  = $param['allowed_exts'];

	$param = array(
		'ele_maxpixel'         => $this->ele_maxpixel( $has_resize ) ,
		'ele_maxsize'          => $this->ele_maxsize() ,
		'ele_allowed_exts'     => $this->ele_allowed_exts( $allowed_exts ) ,
		'ele_item_description' => $this->item_description_dhtml() ,
		'item_cat_id_options'  => $this->item_cat_id_options() ,
		'file_select_options'  => $this->file_select_options() ,
	);
	return $param ;
}

function file_select_options()
{
	$options = $this->_utility_class->get_files_in_dir( 
		$this->_FILE_DIR, null, false, true, true );

	if ( !is_array($options) || !count($options) ) {
		return null;
	}
	return $this->build_form_options( null, $options );
}

//---------------------------------------------------------
// submit edit form
//---------------------------------------------------------
function build_form_photo( $item_row, $param )
{
	$this->init_preload();

	$mode          = $param['mode'];
	$rotate        = $param['rotate'];
	$preview_name  = $param['preview_name'];
	$has_resize    = $param['has_resize'];
	$has_rotate    = $param['has_rotate'];
	$allowed_exts  = $param['allowed_exts'];
	$flag_item_row = $param['flag_item_row'];

	$this->_xoops_db_groups = $this->get_cached_xoops_db_groups();

	$this->set_checkbox( $param['checkbox_array'] );

	$is_submit = false ;
	$is_edit   = false ;

	switch ($mode)
	{
		case 'edit':
			$is_edit = true;
			$fct     = $this->_THIS_EDIT_FCT ;
			$op      = 'modify';
			$submit  = _EDIT;
			break;

		case 'submit':
		default:
			$is_submit = true;
			$fct       = $this->_THIS_SUBMIT_FCT ;
			$op        = 'submit';
			$submit    = _ADD;
			break;
	}

	$this->set_row( $item_row );
	$this->init_editor();

	list ( $show_item_embed_type, $show_item_embed_text, $show_item_embed_src )
		= $this->show_item_embed();

	list ( $show_thumb_dsc_select, $show_thumb_dsc_embed )
		= $this->show_thumb_dsc();

	list( $photo_url, $show_file_photo_delete ) 
		= $this->build_file_url( _C_WEBPHOTO_FILE_KIND_CONT, 'item_external_url' );

	list( $thumb_url, $show_file_thumb_delete ) 
		= $this->build_file_url( _C_WEBPHOTO_FILE_KIND_THUMB, 'item_external_thumb' );

	list( $middle_url, $show_file_middle_delete ) 
		= $this->build_file_url( _C_WEBPHOTO_FILE_KIND_MIDDLE, 'item_external_middle' );

	list( $small_url, $show_file_small_delete ) 
		= $this->build_file_url( _C_WEBPHOTO_FILE_KIND_SMALL, '' );

	$param = array( 
		'op_edit'         => $op ,
		'preview_name'    => $preview_name ,
		'is_submit'       => $is_submit ,
		'is_edit'         => $is_edit ,
		'max_file_size'   => $this->_cfg_fsize ,
		'has_rotate'      => $has_rotate ,

		'show_desc_options'           => $this->_editor_show ,
		'show_desc_options_hidden'    => ! $this->_editor_show ,
		'show_item_embed_type'        => $show_item_embed_type ,
		'show_item_embed_text'        => $show_item_embed_text ,
		'show_item_embed_src'         => $show_item_embed_src ,
		'show_item_embed_type_hidden' => ! $show_item_embed_type ,
		'show_item_embed_text_hidden' => ! $show_item_embed_text ,
		'show_item_embed_src_hidden'  => ! $show_item_embed_src ,
		'show_item_siteurl_1st'       => $show_item_embed_text ,
		'show_item_siteurl_2nd'       => ! $show_item_embed_text ,
		'show_item_perm_read'         => $this->show_item_perm_read() ,
		'show_file_photo'         => $this->is_upload_type(),
		'show_gmap'               => $this->show_gmap() ,
		'show_thumb_dsc_select'   => $show_thumb_dsc_select ,
		'show_thumb_dsc_embed'    => $show_thumb_dsc_embed ,
		'show_file_photo_delete'  => $show_file_photo_delete ,
		'show_file_thumb_delete'  => $show_file_thumb_delete ,
		'show_file_middle_delete' => $show_file_middle_delete ,
		'show_file_small_delete'  => $show_file_small_delete ,
		'show_external_url'       => $this->_SHOW_EXTERNAL_URL ,
		'show_external_middle'    => $this->_SHOW_EXTERNAL_MIDDLE ,
		'show_external_thumb'     => $this->_SHOW_EXTERNAL_THUMB ,

		'ele_maxpixel'         => $this->ele_maxpixel( $has_resize ) ,
		'ele_maxsize'          => $this->ele_maxsize() ,
		'ele_allowed_exts'     => $this->ele_allowed_exts( $allowed_exts ) ,
		'ele_item_description' => $this->_editor_desc ,

		'item_cat_id_options'            => $this->item_cat_id_options() ,
		'item_gicon_id_select_options'   => $this->item_gicon_id_select_options() ,
		'item_codeinfo_select_options'   => $this->item_codeinfo_select_options() ,
		'item_perm_read_input_checkboxs' => $this->item_perm_read_input_checkboxs() ,
		'item_perm_down_input_checkboxs' => $this->item_perm_down_input_checkboxs() ,

		'item_text_array'     => $this->item_text_array() ,
		'item_file_array'     => $this->item_file_array( $is_edit ) ,
		'item_datetime_val_s' => $this->item_datetime_val_s() ,

		'item_description_html_checked'   => $this->build_row_checked( 'item_description_html' ),
		'item_description_smiley_checked' => $this->build_row_checked( 'item_description_smiley' ),
		'item_description_xcode_checked'  => $this->build_row_checked( 'item_description_xcode' ),
		'item_description_image_checked'  => $this->build_row_checked( 'item_description_image' ),
		'item_description_br_checked'     => $this->build_row_checked( 'item_description_br' ),
		'item_datetime_checkbox_checked'  => $this->build_checkbox_checked( 'item_datetime_checkbox' ) ,
		'rotate_checked'                  => $this->rotate_checked( $rotate ) ,

		'photo_url_s'   => $this->sanitize( $photo_url ), 
		'thumb_url_s'   => $this->sanitize( $thumb_url ), 
		'middle_url_s'  => $this->sanitize( $middle_url ), 
		'small_url_s'   => $this->sanitize( $small_url ), 
		'tags_val_s'    => $this->tags_val_s( $param ) ,
		'embed_src_dsc' => $this->embed_src_dsc() ,
		'editor_js'     => $this->_editor_js ,

		'value_submit' => $submit ,
	);

	if ( $flag_item_row ) {
		$arr = array_merge( $param, $this->build_item_row( $item_row ) );
	} else {
		$arr = $param;
	}

	return $arr ;
}

function show_item_embed()
{
	$show_type = false;
	$show_text = false;
	$show_src  = false;

	if ( $this->is_embed_type() ) {
		$show_type = true;
		if ( $this->is_embed_general_type() ) {
			$show_text = true;
		} else {
			$show_src = true;
		}
	}

	return array( $show_type, $show_text, $show_src );
}

function show_item_perm_read()
{
	if ( $this->_cfg_perm_item_read > 0 ) {
		return true;
	}
	return false;
}

function show_thumb_dsc()
{
	$type = $this->get_row_by_key( 'item_embed_type' );
	if ( $type ) {
		$thumb = $this->_embed_class->build_thumb( $type, 'example' );
		if ( $thumb ) {
			return array( false, true );
		}
	}

	if ( $this->_cfg_makethumb ) {
		return array( true, false );
	}

	return array( false, false );
}

function show_gmap()
{
	if ( $this->_cfg_gmap_apikey ) {
		return true;
	}
	return false;
}

function item_text_array()
{
	$arr = array();
	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; $i++ ) 
	{
		$name    = 'item_text_'.$i;
		$show    = false;
		$title   = null;
		$value_s = null;

		if ( is_array($this->_ARRAY_PHOTO_TEXT) && in_array( $name, $this->_ARRAY_PHOTO_TEXT) ) {
			$show    = true;
			$title   = $this->get_constant( $name );
			$value_s = $this->get_row_by_key( $name );
		}

		$arr[ $i ] = array(
			'show'    => $show ,
			'name'    => $name ,
			'title_s' => $this->sanitize( $title ) ,
			'value_s' => $value_s ,
		);
	}

	return $arr;
}

function item_file_array( $is_edit )
{
	if ( ! $is_edit ) {
		return null ;
	}

	$item_row = $this->get_row();

	$arr = array();
	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; $i++ ) 
	{
		$title = null;
		$value = null;

		if ( in_array( $i, $this->_ARRAY_FILE_ID ) ) {
			$title    = $this->get_constant( 'FILE_KIND_'.$i );
			$file_row = $this->get_cached_file_row_by_kind( $item_row, $i );
			$url      = $this->build_file_url_size( $file_row ) ;
		}

		$arr[ $i ] = array(
			'title_s' => $this->sanitize( $title ) ,
			'value_s' => $this->sanitize( $value ) ,
		);
	}

	return $arr;
}

function item_datetime_val_s()
{
	return $this->sanitize( 
		$this->mysql_datetime_to_str( 
		$this->get_row_by_key( 'item_datetime' ) ) );
}

function item_gicon_id_select_options()
{
	$value   = $this->get_row_by_key( 'item_gicon_id' );
	$options = $this->_gicon_handler->get_sel_options();
	return $this->build_form_options( $value, $options );
}

function item_codeinfo_select_options()
{
	$values  = $this->_item_handler->get_codeinfo_array( $this->get_row() );
	$options = $this->_item_handler->get_codeinfo_options();
	return $this->build_form_options_multi( $values, $options );
}

function item_perm_read_input_checkboxs()
{
	return $this->build_group_perms_checkboxs_by_key( 'item_perm_read' );
}

function item_perm_down_input_checkboxs()
{
	return $this->build_group_perms_checkboxs_by_key( 'item_perm_down' );
}

function rotate_checked( $rotate )
{
	if ( empty($rotate) ) {
		$rotate = $this->_ROTATE_DEFAULT ;
	}
	$checked = array(
		'rot0'   => '', 
		'rot90'  => '', 
		'rot180' => '', 
		'rot270' => '', 
	);
	$checked[ $rotate ] = $this->_CHECKED ;
	return $checked;
}

function tags_val_s( $param )
{
	return $this->sanitize(
		$this->_tag_class->tag_name_array_to_str( 
		$param['tag_name_array'] ) );
}

function embed_src_dsc()
{
	$type = $this->get_row_by_key( 'item_embed_type' );
	$src  = $this->get_row_by_key( 'item_embed_src' );

	if ( $type ) {
		return $this->_embed_class->build_src_desc( $type, $src );
	}
	return null;
}

function build_file_url( $id, $name )
{
	$file_row = $this->get_cached_file_row_by_kind( $this->get_row(), $id );

	$url = $this->build_file_url_size( $file_row ) ;
	if ( $url ) {
		return array( $url, true );
	}

	if ( $name ) {
		$url = $this->get_row_by_key( $name, null, false );
		return array( $url, false );
	}

	return array( null, false );
}

function is_upload_type()
{
	if ( $this->is_embed_type() ) {
		return false;
	}
	return true;
}

function is_embed_type( )
{
	$kind = $this->get_row_by_key( 'item_kind' );
	if ( $this->_kind_class->is_embed_kind( $kind ) ) {
		return true;
	}
	return false;
}

function is_embed_general_type( )
{
	$type = $this->get_row_by_key( 'item_embed_type' );
	if ( $this->is_embed_type() && ( $type == _C_WEBPHOTO_EMBED_NAME_GENERAL ) ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// editor
//---------------------------------------------------------
function init_editor()
{
	$name1  = 'item_description';
	$name2  = 'item_editor';
	$value1 = $this->get_row_by_key( $name1 );
	$editor = $this->get_row_by_key( $name2 );
	$arr    = $this->_editor_class->init_form( 
		$editor, $name1, $name1, $value1, $this->_DESCRIPTION_ROWS, $this->_DESCRIPTION_COLS );

	if ( is_array($arr) ) {
		$this->_editor_show = $arr['show'];
		$this->_editor_js   = $arr['js'];
		$this->_editor_desc = $arr['desc'];
	}
}

// --- class end ---
}

?>