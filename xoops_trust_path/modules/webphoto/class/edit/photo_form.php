<?php
// $Id: photo_form.php,v 1.20 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// show_batch_uid
// 2010-11-11 K.OHWADA
// show_item_icon_name()
// 2010-10-01 K.OHWADA
// show_file_jpeg()
// 2010-09-17 K.OHWADA
// move item_uid_options() to webphoto_admin_item_form
// 2010-06-06 K.OHWADA
// support_embed_params()
// 2010-02-15 K.OHWADA
// check_edit()
// 2010-01-10 K.OHWADA
// webphoto_tag -> webphoto_tag_build
// item_description_scroll
// 2009-12-06 K.OHWADA
// item_perm_level
// build_form_common()
// BUG: not show maxsize
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_mime
// submit_rotate_default
// 2009-06-28 K.OHWADA
// set_default_item_row()
// 2009-05-05 K.OHWADA
// merge build_form_file() to build_form_photo()
// build_form_photo_with_template()
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
	var $_embed_class;
	var $_editor_class;
	var $_kind_class;
	var $_tag_build_class;
	var $_mime_class;
	var $_image_create_class;
	var $_use_item_class;

	var $_has_image_resize;
	var $_has_image_rotate;
	var $_allowed_exts;

	var $_ini_file_list = null;

	var $_xoops_db_groups  = null;

	var $_editor_show = false ;
	var $_editor_js   = null ;
	var $_editor_desc = null ;

	var $_flag_admin     = false;

	var $_support_embed_params = array();

// constant
	var $_FLAG_ITEM_ROW  = true ;
	var $_MAX_PHOTO_FILE = _C_WEBPHOTO_MAX_PHOTO_FILE ;

	var $_ARRAY_FILE_ID = array(
		_C_WEBPHOTO_FILE_KIND_VIDEO_FLASH, _C_WEBPHOTO_FILE_KIND_PDF, _C_WEBPHOTO_FILE_KIND_SWF
	);

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_photo_form( $dirname, $trust_dirname )
{
	$this->webphoto_edit_form( $dirname, $trust_dirname );

	$this->_gicon_handler  
		=& webphoto_gicon_handler::getInstance( $dirname, $trust_dirname );
	$this->_player_handler 
		=& webphoto_player_handler::getInstance( $dirname, $trust_dirname );

	$this->_embed_class    
		=& webphoto_embed::getInstance( $dirname, $trust_dirname );
	$this->_editor_class   
		=& webphoto_editor::getInstance( $dirname, $trust_dirname );
	$this->_mime_class     
		=& webphoto_mime::getInstance( $dirname, $trust_dirname  );
	$this->_tag_build_class      
		=& webphoto_tag_build::getInstance( $dirname, $trust_dirname );
	$this->_use_item_class      
		=& webphoto_edit_use_item::getInstance( $dirname, $trust_dirname );

	$this->_kind_class     =& webphoto_kind::getInstance();
	$this->_image_create_class =& webphoto_image_create::getInstance( $dirname );

	$this->_has_image_resize  = $this->_image_create_class->has_resize();
	$this->_has_image_rotate  = $this->_image_create_class->has_rotate();

	$this->_item_kind_group_array = $this->_mime_class->get_my_item_kind_group_array();
	$this->_tag_build_class->set_is_japanese( $this->_is_japanese );

	$this->_ini_file_list = $this->explode_ini('edit_file_list');
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
// set param
//---------------------------------------------------------
function set_preview_name( $val )
{
	$this->_preview_name = $val;
}

function set_tag_name_array( $val )
{
	$this->_tag_name_array = $val;
}

function set_rotate( $val )
{
	$this->_rotate = $val ;
}

function set_flag_admin( $val )
{
	$this->_flag_admin = (bool)$val;
	$this->_use_item_class->set_flag_admin( $val );
}

//---------------------------------------------------------
// submit edit form
//---------------------------------------------------------
function build_form_photo_with_template( $item_row )
{
	$template = 'db:'. $this->_DIRNAME .'_form_photo.html';

	$param = array(
		'lang_text_directory' => _AM_WEBPHOTO_TEXT_DIRECTORY ,
		'lang_photopath'      => _AM_WEBPHOTO_PHOTOPATH ,
		'lang_desc_photopath' => _AM_WEBPHOTO_DESC_PHOTOPATH ,
	);

	$arr = array_merge( 
		$this->build_form_base_param() ,
		$this->build_form_photo( $item_row ),
		$param
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	return $tpl->fetch( $template ) ;
}

function build_form_photo( $item_row )
{
	$this->init_preload();

	$mode           = $this->_FORM_MODE ;
	$flag_item_row  = $this->_FLAG_ITEM_ROW ;
	$max_photo_file = $this->_MAX_PHOTO_FILE ;
	$max_file_size  = $this->_cfg_fsize ;

	$is_submit = false ;
	$is_edit   = false ;
	$is_bulk   = false ;
	$is_file   = false ;

	$show_maxsize_mode = false;
	$show_file_photo_mode  = false;
	$show_file_jpeg_mode   = false;

	$show_file_thumb_mode  = false;
	$show_file_middle_mode = false;
	$show_file_small_mode  = false;

	$show_file_ids       = false;
	$show_file_ftp       = false;
	$show_batch_dir      = false;
	$show_batch_uid      = false;
	$show_batch_update   = false;
	$show_gmap_onoff     = false;
	$show_button_preview = false;
	$show_button_delete  = false;
	$show_detail_div_mode = false;

	$file_id_array       = null;
	$field_counter       = 0;

	$show_gmap = $this->show_gmap() ;

	switch ($mode)
	{
		case 'edit':
			$is_edit = true;
			$fct     = $this->_THIS_FCT_EDIT ;
			$op      = 'modify';
			$submit  = _EDIT;
			$show_button_delete  = true;
			break;

		case 'bulk':
			$is_bulk   = true;
			$fct       = $this->_THIS_FCT_SUBMIT ;
			$op        = 'submit_bulk';
			$submit    = _ADD;
			$show_gmap_onoff     = $show_gmap;
			$show_detail_div_mode = true;
			break;

		case 'file':
			$is_file   = true;
			$fct       = $this->_THIS_FCT_SUBMIT ;
			$op        = 'submit_file';
			$submit    = _ADD;
			$show_gmap_onoff     = $show_gmap;
			$show_detail_div_mode = true;
			break;

		case 'admin_batch':
			$is_batch  = true;
			$fct       = $this->_THIS_FCT_ADMIN_BATCH ;
			$op        = 'submit';
			$submit    = _ADD;
			$show_gmap_onoff   = $show_gmap;
			$show_detail_div_mode = true;
			break;

		case 'submit':
		default:
			$is_submit = true;
			$fct       = $this->_THIS_FCT_SUBMIT ;
			$op        = 'submit';
			$submit    = _ADD;
			$show_button_preview = true;
			$show_gmap_onoff     = $show_gmap;
			$show_detail_div_mode = true;
			break;
	}

	$item_row = $this->set_default_item_row( $item_row );
	$this->set_row( $item_row );
	$this->init_editor();

	switch ($mode)
	{
		case 'bulk':
			$show_maxsize_mode  = true;
			$show_file_ids = true;
			$field_counter = $max_photo_file;
			$file_id_array = range( 1, $max_photo_file );
			break;

		case 'file':
			$show_maxsize_mode     = true;
			$show_file_ftp = true;
			$max_file_size = $this->_cfg_file_size ;
			break;

		case 'admin_batch':
			$show_batch_dir    = true;
			$show_batch_update = true;
// for future
//			$show_batch_uid    = true;
			break;

		case 'submit':
		case 'edit':
		default:
			$show_maxsize_mode = true;
			$show_file_photo_mode  = $this->is_upload_type() ;
			$show_file_jpeg_mode   = true;
			$field_counter    = 2;
			break;
	}

	$show_item_cat_id = $this->show_item_cat_id(  $is_edit );

	list ( $show_detail_div, $show_detail_div_on )
		= $this->show_detail_div( $show_detail_div_mode );

	list ( $show_item_embed_type, $show_item_embed_text, $show_item_embed_src )
		= $this->show_item_embed();

	$arr1 = $this->build_form_common( $is_edit );

	$arr2 = array( 
		'op_edit'         => $op ,
		'is_submit'       => $is_submit ,
		'is_edit'         => $is_edit ,
		'is_bulk'         => $is_bulk ,
		'max_file_size'   => $max_file_size ,
		'field_counter'   => $field_counter ,

// BUG: not show maxsize
		'show_maxsize'      => $this->show_maxsize( $show_maxsize_mode, $is_edit ) ,
		'show_allowed_exts' => $this->show_allowed_exts( $is_edit ) ,

		'show_item_cat_id'            =>   $show_item_cat_id ,
		'show_item_cat_id_hidden'     => ! $show_item_cat_id ,
		'show_item_embed_type'        =>   $show_item_embed_type ,
		'show_item_embed_text'        =>   $show_item_embed_text ,
		'show_item_embed_src'         =>   $show_item_embed_src ,
		'show_item_embed_type_hidden' => ! $show_item_embed_type ,
		'show_item_embed_text_hidden' => ! $show_item_embed_text ,
		'show_item_embed_src_hidden'  => ! $show_item_embed_src ,
		'show_item_siteurl_1st'       => $this->show_item_siteurl_1st( $show_item_embed_text ) ,
		'show_item_siteurl_2nd'       => $this->show_item_siteurl_2nd( $show_item_embed_text ) ,

		'show_item_description_scroll' => $this->use_item('description_scroll') ,
		'show_item_editor'             => $this->use_item('editor') ,
		'show_item_codeinfo'           => $this->use_item('codeinfo') ,
		'show_item_external_url'       => $this->use_item('external_url') ,
		'show_item_external_thumb'     => $this->use_item('external_thumb') ,
		'show_item_datetime'           => $this->use_item('datetime') ,
		'show_item_place'              => $this->use_item('place') ,
		'show_item_equipment'          => $this->use_item('equipment') ,
		'show_item_duration'           => $this->use_item('duration') ,
		'show_item_artist'             => $this->use_item('artist') ,
		'show_item_album'              => $this->use_item('album') ,
		'show_item_label'              => $this->use_item('label') ,
		'show_item_page_width'         => $this->use_item('page_width') ,
		'show_item_page_height'        => $this->use_item('page_height') ,
		'show_item_perm_down'          => $this->use_item('perm_down') ,
		'show_item_exif'               => $this->show_item_exif(    $is_edit ) ,
		'show_item_content'            => $this->show_item_content( $is_edit ) ,
		'show_item_icon_name'          => $this->show_item_icon_name() ,

		'show_input_item_perm_down'   => $this->show_input_item_perm_down() ,

		'show_file_photo'         => $this->show_file_photo(  $show_file_photo_mode, $is_submit, $is_edit ),
		'show_file_jpeg'          => $this->show_file_jpeg(   $show_file_jpeg_mode ) ,
		'show_file_ids'           => $show_file_ids ,
		'show_file_ftp'           => $show_file_ftp ,

		'show_rotate'             => $this->show_rotate( $show_file_photo_mode ) ,
		'show_tags'               => $this->check_show('tags') ,
		'show_gmap_ele'           => $show_gmap ,
		'show_gmap_table'         => $show_gmap ,
		'show_gmap_onoff'         => $show_gmap_onoff ,
		'show_batch_dir'          => $show_batch_dir ,
		'show_batch_uid'          => $show_batch_uid ,
		'show_batch_update'       => $show_batch_update ,

		'show_detail_table'       => $this->show_detail_table() ,
		'show_detail_div'         => $show_detail_div ,
		'show_detail_div_on'      => $show_detail_div_on ,

		'show_button_preview'     => $show_button_preview ,
		'show_button_delete'      => $show_button_delete ,

		'show_item_perm_level_options' => $this->show_item_perm_options( $is_submit, $is_edit, $is_bulk ) ,

		'form_title'          => $this->get_form_title( $is_edit ) ,
		'batch_dir_s'         => $this->batch_dir_s() ,
		'file_id_array'       => $file_id_array ,
		'file_select_options' => $this->file_select_options() ,

		'button_submit' => $submit ,
	);

	$arr3 = array_merge( $arr1, $arr2 );

	if ( $flag_item_row ) {
		$arr_ret = array_merge( $arr3, $this->build_item_row( $item_row ) );
	} else {
		$arr_ret = $arr3;
	}

	return $arr_ret ;
}

function build_form_common( $is_edit )
{
	$preview_name   = $this->_preview_name ;
	$tag_name_array = $this->_tag_name_array ;
	$rotate         = $this->_rotate ;
	$has_resize     = $this->_has_image_resize ;

	$this->set_support_embed_params();

	$show_desc_options = $this->show_desc_options();

	list( $photo_url, $show_file_photo_delete ) 
		= $this->build_file_url( _C_WEBPHOTO_FILE_KIND_CONT, 'item_external_url' );

	list( $jpeg_url, $show_file_jpeg_delete ) 
		= $this->build_file_url( _C_WEBPHOTO_FILE_KIND_JPEG, 'item_external_thumb' );

	list ( $show_thumb_dsc_select, $show_thumb_dsc_embed )
		= $this->show_thumb_dsc();

	list ( $item_codeinfo_select_options, $item_codeinfo_hiddens )
		= $this->item_codeinfo_param();

	list ( $item_perm_read_input_checkboxs, $item_perm_read_list, $item_perm_read_hiddens )
		= $this->item_perm_read_param();

	list ( $item_perm_down_input_checkboxs, $item_perm_down_list, $item_perm_down_hiddens )
		= $this->item_perm_down_param();

	$arr = array( 
		'preview_name'    => $preview_name ,

		'show_desc_options'        =>   $show_desc_options ,
		'show_desc_options_hidden' => ! $show_desc_options ,

		'show_file_photo_delete'  => $show_file_photo_delete ,
		'show_file_jpeg_delete'   => $show_file_jpeg_delete ,

		'show_thumb_dsc_select'   => $show_thumb_dsc_select ,
		'show_thumb_dsc_embed'    => $show_thumb_dsc_embed ,

		'show_item_perm_level'        => $this->show_item_perm_level( $is_edit ) ,
		'show_item_perm_read'         => $this->show_item_perm_read() ,
		'show_input_item_perm_read'   => $this->show_input_item_perm_read() ,

		'show_embed_support_title'       => $this->show_embed_support_title() ,
		'show_embed_support_description' => $this->show_embed_support_description() ,
		'show_embed_support_siteurl'     => $this->show_embed_support_siteurl() ,
		'show_embed_support_duration'    => $this->show_embed_support_duration() ,
		'show_embed_support_tags'        => $this->show_embed_support_tags() ,
		'show_embed_support_embed_text'  => $this->show_embed_support_embed_text() ,

		'ele_maxsize'          => $this->ele_maxsize() ,
		'ele_allowed_exts'     => $this->build_allowed_exts() ,
		'ele_item_description' => $this->_editor_desc ,

		'item_cat_id_options'            => $this->item_cat_id_options() ,
		'item_gicon_id_select_options'   => $this->item_gicon_id_select_options() ,
		'item_codeinfo_select_options'   => $item_codeinfo_select_options ,
		'item_codeinfo_hiddens'          => $item_codeinfo_hiddens ,
		'item_perm_read_input_checkboxs' => $item_perm_read_input_checkboxs ,
		'item_perm_read_list'            => $item_perm_read_list ,
		'item_perm_read_hiddens'         => $item_perm_read_hiddens ,
		'item_perm_down_input_checkboxs' => $item_perm_down_input_checkboxs ,
		'item_perm_down_list'            => $item_perm_down_list ,
		'item_perm_down_hiddens'         => $item_perm_down_hiddens ,
		'item_perm_level_options'        => $this->item_perm_level_options() ,
		'item_perm_level_checked'        => $this->item_perm_level_checked() ,
		'item_perm_level_disp'           => $this->item_perm_level_disp() ,

		'item_text_array'     => $this->item_text_array() ,
		'item_file_array'     => $this->item_file_array( $is_edit ) ,
		'item_datetime_val_s' => $this->item_datetime_val_s() ,

		'item_description_html_checked'   => $this->build_row_checked( 'item_description_html' ),
		'item_description_smiley_checked' => $this->build_row_checked( 'item_description_smiley' ),
		'item_description_xcode_checked'  => $this->build_row_checked( 'item_description_xcode' ),
		'item_description_image_checked'  => $this->build_row_checked( 'item_description_image' ),
		'item_description_br_checked'     => $this->build_row_checked( 'item_description_br' ),
		'item_datetime_checkbox_checked'  => $this->build_checkbox_checked( 'item_datetime_checkbox' ) ,

		'photo_url_s'   => $this->sanitize( $photo_url ), 
		'jpeg_url_s'    => $this->sanitize( $jpeg_url ), 
		'tags_val_s'    => $this->tags_val_s( $tag_name_array ) ,
		'embed_src_dsc' => $this->embed_src_dsc() ,
		'editor_js'     => $this->_editor_js ,

		'item_time_update_disp' => $this->build_time_disp( 'item_time_update',  true ) ,
		'batch_dir_s'           => $this->batch_dir_s() ,
		'rotate_checked'        => $this->rotate_checked( $rotate ) ,
	);

	return $arr ;
}

function show_maxsize( $show_maxsize_mode, $is_edit )
{
	if ( $show_maxsize_mode ) {
		if ( $is_edit && $this->check_edit('maxsize') ) {
			return true;
		}
		if ( !$is_edit ) {
			return true;
		}
	}
	return false;
}

function show_allowed_exts( $is_edit )
{
	if ( $is_edit && $this->check_edit('allowed_exts') ) {
		return true;
	}
	if ( !$is_edit ) {
		return true;
	}
	return false;
}

function show_item_perm_level( $is_edit )
{
	if ( $this->use_item_perm_level() ) {
		if ( $is_edit && $this->editable_item_perm_level() ) {
			return true;
		}
		if ( !$is_edit ) {
			return true;
		}
	}
	return false;
}

function show_item_embed()
{
	$show_type = false;
	$show_text = false;
	$show_src  = false;

	if ( $this->is_embed_type() ) {
		$show_type = true;
		$show_text = true;
		if ( ! $this->is_embed_general_type() ) {
			$show_src = true;
		}
	}

	return array( $show_type, $show_text, $show_src );
}

function show_input_item_perm_read()
{
	return !$this->use_item_perm_level();
}

function show_input_item_perm_down()
{
	return !$this->use_item_perm_level();
}

function show_item_perm_options( $is_submit, $is_edit, $is_bulk )
{
	if ( $is_submit || $is_bulk ) {
		return true;
	}
	if ( $is_edit && $this->editable_item_perm_level() ) {
		return true;
	}
	return false;
}

function show_item_siteurl_1st( $show_item_embed_text )
{
	if ( $show_item_embed_text && $this->use_item('siteurl') ) {
		return true;
	}
	return false;
}

function show_item_siteurl_2nd( $show_item_embed_text )
{
	if ( !$show_item_embed_text && $this->use_item('siteurl') ) {
		return true;
	}
	return false;
}

function show_item_cat_id( $is_edit )
{
	if ( $is_edit && $this->check_edit('cat_id') ) {
		return true;
	}
	if ( !$is_edit ) {
		return true;
	}
	return false;
}

function show_item_exif( $is_edit )
{
	if ( $is_edit && $this->use_item('exif') ) {
		return true;
	}
	return false;
}

function show_item_content( $is_edit )
{
	if ( $is_edit && $this->use_item('content') ) {
		return true;
	}
	return false;
}

function show_desc_options()
{
	if ( $this->_editor_show && $this->check_show('desc_options') ) {
		return true;
	}
	return false;
}

function show_thumb_dsc()
{
	$type = $this->get_row_by_key( 'item_embed_type' );
	if ( $type ) {
		if ( $this->is_support_embed_param('thumb') ) {
			return array( false, true );
		}
	}

	if ( $this->_cfg_makethumb ) {
		return array( true, false );
	}

	return array( false, false );
}

function show_detail_table()
{
	return $this->check_show('detail');
}

function show_detail_div( $show_detail_div_mode )
{
	$div = false;
	$on  = false;

	if ( $show_detail_div_mode && $this->check_show('detail') ) {
		$div  = true;
		if ( $this->get_ini('submit_detail_div_onoff') ) {
			$on = true;
		}
	}

	return array( $div, $on );
}

function show_rotate( $show_file_photo )
{
	if ( $show_file_photo && $this->_has_image_rotate && $this->check_show('rotate') ) {
		return true ;
	}
	return false;
}

function show_file_photo( $show_file_photo_mode, $is_submit, $is_edit )
{
	if ( $show_file_photo_mode ) {
		if ( $is_submit ) {
			return true;
		}
		if ( $is_edit && $this->check_edit('file_photo') ) {
			return true;
		}
	}
	return false;
}

function show_file_jpeg( $show_file_jpeg_mode )
{
	if ( $show_file_jpeg_mode && $this->check_show('file_jpeg') ) {
		return true;
	}
	return false;
}

function show_item_icon_name()
{
	$item_row       = $this->get_row();
	$icon_name      = $this->get_row_by_key('item_icon_name');
	$external_thumb = $this->get_row_by_key('item_external_thumb');

	$jpeg_url  = $this->build_file_url_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_JPEG );
	$thumb_url = $this->build_file_url_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_THUMB );

	if ( $icon_name && empty($external_thumb) && empty($jpeg_url) && empty($thumb_url) ) {
		return true;
	}
	return false;
}

function get_form_title( $is_edit )
{
	if ( $is_edit ) {
		return $this->get_constant('TITLE_EDIT');
	} else {
		return $this->get_constant('TITLE_PHOTOUPLOAD');
	}
}

function item_text_array()
{
	$item_text_array = $this->explode_ini('submit_item_text_list');

	$arr = array();
	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; $i++ ) 
	{
		$name    = 'item_text_'.$i;
		$show    = false;
		$title   = null;
		$value_s = null;

		if ( is_array($item_text_array) && in_array( $name, $item_text_array) ) {
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
	foreach ( $this->_ini_file_list as $file ) 
	{
		$kind_name = strtoupper( '_C_WEBPHOTO_FILE_KIND_'.$file );
		if ( !defined($kind_name) ) {
			continue;
		}

		$kind = constant($kind_name);

		$name        = 'file_'. $kind ;
		$name_delete = $name.'_delete';
		$title       = $this->get_constant( 'FILE_KIND_'.$kind );
		$url         = $this->build_file_url_by_kind( $item_row, $kind ) ;

		$arr[ $kind ] = array(
			'name'        => $name ,
			'name_delete' => $name_delete ,
			'title_s'     => $this->sanitize( $title ) ,
			'url_s'       => $this->sanitize( $url ) ,
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

function item_codeinfo_param()
{
	$values  = $this->_item_handler->get_codeinfo_array( $this->get_row() );
	$options = $this->_item_handler->get_codeinfo_options();
	$ret1 = $this->build_form_options_multi( $values, $options );

	$name = "item_codeinfo[]";
	$ret2 = $this->build_form_hiddens_select_multi( $name, $values );
	return array( $ret1, $ret2 );
}

function item_perm_read_param()
{
	return $this->build_group_perms_param_by_key( 'item_perm_read' );
}

function item_perm_down_param()
{
	return $this->build_group_perms_param_by_key( 'item_perm_down' );
}

function item_perm_level_options()
{
	$name    = 'item_perm_level';
	$value   = $this->get_row_by_key( $name );
	$options = array_flip( $this->_item_handler->get_perm_level_options() );
	$delmita = ' &nbsp ';
	return $this->build_form_radio( $name, $value, $options, $delmita );
}

function item_perm_level_checked()
{
	$value = $this->get_row_by_key( 'item_perm_level' );
	$checked = array(
		'0' => '', 
		'1' => '', 
	);
	$checked[ $value ] = $this->_CHECKED ;
	return $checked;
}

function item_perm_level_disp()
{
	$key = $this->get_row_by_key( 'item_perm_level' );
	$str = $this->item_perm_level_value( $key );
	return $this->sanitize($str);
}

function item_perm_level_value( $key )
{
	$options = $this->_item_handler->get_perm_level_options();
	if ( isset($options[ $key ]) ) {
		return $options[ $key ];
	}
	return false;
}

function rotate_checked( $rotate )
{
	if ( empty($rotate) ) {
		$rotate = $this->get_ini('submit_rotate_default') ;
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

function tags_val_s( $tag_name_array )
{
	return $this->sanitize(
		$this->_tag_build_class->tag_name_array_to_str( $tag_name_array ) );
}

function embed_src_dsc()
{
	$type = $this->get_row_by_key( 'item_embed_type' );
	$src  = $this->get_row_by_key( 'item_embed_src' );

	return $this->build_embed_src_dsc( $type, $src );
}

function build_file_url( $kind, $name )
{
	$url = $this->build_file_url_by_kind( $this->get_row(), $kind );
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

function file_select_options()
{
	$options = $this->_utility_class->get_files_in_dir( 
		$this->_FILE_DIR, null, false, true, true );

	if ( !is_array($options) || !count($options) ) {
		return null;
	}
	return $this->build_form_options( null, $options );
}

function batch_dir_s()
{
	return '' ;
}

function batch_update_s()
{
	return $this->sanitize( 
		formatTimestamp( time() , _WEBPHOTO_DTFMT_YMDHI ) ) ;
}

function time_now()
{
	return formatTimestamp( time(), $this->get_constant('DTFMT_YMDHI') ) ;
}

function build_time_disp( $name, $flag_now )
{
	$date  = '';
	$value = intval( $this->get_row_by_key( $name ) );
	if ( $flag_now && empty($value) ) {
		$value = time();
	}
	if ( $value > 0 ) {
		$date = $this->format_timestamp( $value, $this->get_constant('DTFMT_YMDHI') );
	}
	return $date ;
}

function build_allowed_exts()
{
	$str = '';
	foreach ( $this->_item_kind_group_array as $k => $v )
	{
		if ( !is_array($v) || !count($v) ) {
			continue;
		}

		$str .= $this->_mime_class->get_item_kind_group_name( $k );
		$str .= ' : ';
		$str .= $this->_mime_class->get_item_kind_group_value( $v );
		$str .= "<br />\n";
	}
	return $str;
}

//---------------------------------------------------------
// use embed class 
//---------------------------------------------------------
function show_embed_support_title()
{
	return $this->is_support_embed_param('title');
}

function show_embed_support_description()
{
	return $this->is_support_embed_param('description');
}

function show_embed_support_duration()
{
	return $this->is_support_embed_param('duration');
}

function show_embed_support_tags()
{
	return $this->is_support_embed_param('tags');
}

function show_embed_support_siteurl()
{
	return $this->is_support_embed_param('url');
}

function show_embed_support_embed_text()
{
	return $this->is_support_embed_param('script');
}

function set_support_embed_params()
{
	$type = $this->get_row_by_key( 'item_embed_type' );
	$arr  = $this->support_embed_params( $type );
	if ( is_array($arr) ) {
		$this->_support_embed_params = $arr ;
	}
}

function is_support_embed_param( $key )
{
	if ( isset( $this->_support_embed_params[ $key ] ) ) {
		return  $this->_support_embed_params[ $key ] ;
	}
	return false;
}

function support_embed_params( $type )
{
	return $this->_embed_class->build_support_params( $type );
}

function build_embed_src_dsc( $type, $src )
{
	return $this->_embed_class->build_src_desc( $type, $src );
}

//---------------------------------------------------------
// use item class 
//---------------------------------------------------------
function use_item( $key )
{
	return $this->_use_item_class->use_item_or_admin( $key );
}

function check_show( $key )
{
	return $this->_use_item_class->check_show_or_admin( $key );
}

function check_edit( $key )
{
	return $this->_use_item_class->check_edit_or_admin( $key );
}

function show_item_perm_read()
{
	return $this->_use_item_class->use_item_perm_read();
}

function use_item_perm_level()
{
	return $this->_use_item_class->use_item_perm_level();
}

function editable_item_perm_level()
{
	return $this->_use_item_class->editable_item_perm_level();
}

function show_gmap()
{
	return $this->_use_item_class->use_gmap();
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
		$editor, $name1, $name1, $value1, 
		$this->get_ini('submit_item_description_rows'), 
		$this->get_ini('submit_item_description_cols') );

	if ( is_array($arr) ) {
		$this->_editor_show = $arr['show'];
		$this->_editor_js   = $arr['js'];
		$this->_editor_desc = $arr['desc'];
	}
}

// --- class end ---
}

?>