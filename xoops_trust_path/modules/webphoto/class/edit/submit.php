<?php
// $Id: submit.php,v 1.6 2009/03/23 12:42:00 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-03-15 K.OHWADA
// create_small_param_by_photo()
// 2009-01-25 K.OHWADA
// create_swf_param()
// 2009-01-10 K.OHWADA
// webphoto_photo_submit -> webphoto_edit_submit
// webphoto_edit_external_build etc
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_submit
//=========================================================
class webphoto_edit_submit extends webphoto_edit_imagemanager_submit
{
	var $_editor_class ;
	var $_tag_class;
	var $_show_image_class;
	var $_external_build_class ;
	var $_embed_build_class ;
	var $_playlist_build_class ;

	var $_cfg_addposts ;
	var $_cfg_makethumb ;
	var $_cfg_thumb_width ;
	var $_cfg_thumb_height ;
	var $_cfg_allownoimage ;

// post
	var $_checkbox_array = array();
	var $_form_action    = null;
	var $_post_rotate    = null;
	var $_rotate_angle   = 0;

	var $_is_video_thumb_form = false;

	var $_row_current  = null;
	var $_row_update   = null ;

	var $_URL_DAFAULT_IMAGE;
	var $_URL_PIXEL_IMAGE ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_submit( $dirname , $trust_dirname )
{
	$this->webphoto_edit_imagemanager_submit( $dirname , $trust_dirname );

	$this->_show_image_class =& webphoto_show_image::getInstance( $dirname );
	$this->_external_build_class =& webphoto_edit_external_build::getInstance( $dirname );
	$this->_playlist_build_class =& webphoto_edit_playlist_build::getInstance( $dirname );

	$this->_embed_build_class    =& webphoto_edit_embed_build::getInstance( 
		$dirname, $trust_dirname );

	$this->_editor_class =& webphoto_editor::getInstance( 
		$dirname, $trust_dirname );

	$this->_tag_class  =& webphoto_tag::getInstance( $dirname );
	$this->_tag_class->set_is_japanese( $this->_is_japanese );

	$this->_cfg_addposts       = $this->get_config_by_name( 'addposts' );
	$this->_cfg_makethumb      = $this->get_config_by_name( 'makethumb' ) ;
	$this->_cfg_thumb_width    = $this->get_config_by_name( 'thumb_width' ) ;
	$this->_cfg_thumb_height   = $this->get_config_by_name( 'thumb_height' ) ;
	$this->_cfg_allownoimage   = $this->get_config_by_name( 'allownoimage' ) ;

	$this->_URL_DAFAULT_IMAGE = $this->_MODULE_URL .'/images/exts/default.png' ;
	$this->_URL_PIXEL_IMAGE   = $this->_MODULE_URL .'/images/icons/pixel_trans.png' ;

	$this->_FLAG_FETCH_ALLOW_ALL = true ;

}

// for admin_photo_manage admin_catmanager
function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_submit( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// post param
//---------------------------------------------------------
function get_post_param()
{
	$this->_post_item_id = $this->get_post_item_id();

	$this->_post_form_embed    = $this->get_post_int( 'form_embed' );
	$this->_post_form_playlist = $this->get_post_int( 'form_playlist' );
	$this->_post_form_editor   = $this->get_post_int( 'form_editor' );

	$this->_preview_name = $this->get_post_text( 'preview_name' ) ;

	$this->_post_rotate  = $this->_post_class->get_post( 'rotate' ) ;
	$this->_rotate_angle = $this->conv_rotate( $this->_post_rotate );

	$this->set_checkbox_by_post( 'item_time_update_checkbox' );
	$this->set_checkbox_by_post( 'item_datetime_checkbox' );

	$post_tags = $this->get_post_text( 'tags' );
	$this->set_tag_name_array( $this->_tag_class->str_to_tag_name_array( $post_tags ) );
}

//---------------------------------------------------------
// creta itme row
//---------------------------------------------------------
function create_item_row_default()
{
	$item_kind          = $this->get_post_int(  'item_kind' );
	$item_embed_type    = $this->get_post_text( 'item_embed_type' );
	$item_playlist_type = $this->get_post_int(  'item_playlist_type' );

	$row = $this->_item_handler->create( true );
	$row['item_cat_id']        = $this->get_post_cat_id() ;
	$row['item_editor']        = $this->get_post_text( 'item_editor' );
	$row['item_embed_type']    = $item_embed_type ;
	$row['item_playlist_type'] = $item_playlist_type ;

// datetime
	$row['item_datetime'] = $this->get_mysql_date_today();
	$this->set_checkbox_by_name( 'item_datetime_checkbox', _C_WEBPHOTO_NO );

// description option
	$options = $this->_editor_class->display_options( $row['item_editor'] );

	if ( $this->_has_html ) {
		$row['item_description_html'] = _C_WEBPHOTO_YES  ;
	}

	if ( is_array($options) ) {
		$row['item_description_smiley'] = $options['smiley']  ;
		$row['item_description_xcode']  = $options['xcode']  ;
		$row['item_description_image']  = $options['image']  ;
		$row['item_description_br']     = $options['br']  ;
	}

	if ( $item_embed_type ) {
		$row['item_kind'] = _C_WEBPHOTO_ITEM_KIND_EMBED ;

	} elseif ( $this->_FLAG_ADMIN && $item_playlist_type ) {
		$row['item_kind'] = $item_kind ;
	}

	return $row ;
}

function create_item_row_preview()
{
	$row = $this->create_item_row_by_post();

	$row['item_uid'] = $this->_xoops_uid;

// datetime
	if ( $row['item_datetime'] ) {
		$this->set_checkbox_by_name( 'item_datetime_checkbox', _C_WEBPHOTO_YES );
	} else {
		$row['item_datetime'] = $this->get_mysql_date_today();
	}

	return $row;
}

function create_item_row_by_post()
{
	$checkbox = $this->get_checkbox_by_name( 'item_datetime_checkbox' );
	$row = $this->_item_handler->create( true );
	$row['item_cat_id'] = $this->get_post_cat_id() ;
	$row = $this->_factory_create_class->build_row_submit_by_post( $row, $checkbox );
	return $row;
}

//---------------------------------------------------------
// checkbox
//---------------------------------------------------------
function set_checkbox_by_post( $name )
{
	$this->set_checkbox_by_name( $name, $this->_post_class->get_post_int( $name ) );
}

function set_checkbox_by_name( $name, $value )
{
	$this->_checkbox_array[ $name ] = $value;
}

function get_checkbox_by_name( $name )
{
	if ( isset( $this->_checkbox_array[ $name ] ) ) {
		 return $this->_checkbox_array[ $name ];
	}
	return null;
}

//---------------------------------------------------------
// tag
//---------------------------------------------------------
function set_tag_name_array( $val )
{
	if ( is_array($val) ) {
		$this->_tag_name_array = $val;
	}
}

function get_tag_name_array()
{
	return $this->_tag_name_array;
}

//---------------------------------------------------------
// is type
//---------------------------------------------------------
function is_flashvar_form()
{
	if ( $this->_form_action == 'flashvar_form' ) {
		return true;
	}
	return false;
}

function is_show_form_embed_playlisy_admin( $item_row )
{
// from photomanager
	if ( $item_row['item_cat_id'] > 0 ) {
		return false ;
	}

	if ( ! $this->is_show_form_embed() ) {
		return false ;
	}
	if ( ! $this->is_show_form_playlist() ) {
		return false ;
	}
	return true ;
}

function is_show_form_editor_option( $options )
{
	if ( ! $this->is_show_form_editor() ) {
		return false ;
	}
	return $this->is_show_form_editor_admin( $options );
}

function is_show_form_editor_admin( $options )
{
// true if options
	if ( is_array($options) && count($options) ) {
		return true;
	}
	return false;
}

function is_show_form_embed()
{
	return $this->_is_show_form( 'form_embed' );
}

function is_show_form_playlist()
{
	return $this->_is_show_form( 'form_playlist' );
}

function is_show_form_editor()
{
	return $this->_is_show_form( 'form_editor' );
}

function _is_show_form( $name )
{
// false if set form
	$form = $this->get_post_int( $name );
	if ( $form ) {
		return false ;
	}
	return true ;
}

//---------------------------------------------------------
// submit form
//---------------------------------------------------------
function build_form_param( $mode )
{
	list ( $types, $allowed_exts ) = $this->get_my_allowed_mimes();

	$param = array(
		'mode'            => $mode,
		'rotate'          => $this->_post_rotate,
		'preview_name'    => $this->_preview_name,
		'tag_name_array'  => $this->_tag_name_array,
		'checkbox_array'  => $this->_checkbox_array,
		'has_resize'      => $this->_has_image_resize,
		'has_rotate'      => $this->_has_image_rotate,
		'allowed_exts'    => $allowed_exts ,
	);

	return $param;
}

//---------------------------------------------------------
// submit
//---------------------------------------------------------
function submit_main()
{
	$this->get_post_param();
	$ret1 = $this->submit_exec();

	if ( $this->_is_video_thumb_form ) {
		return _C_WEBPHOTO_RET_VIDEO_FORM ;
	}

	$ret2 = $this->build_failed_msg( $ret1 );
	if ( !$ret2 ) {
		return _C_WEBPHOTO_RET_ERROR ;
	}

	return _C_WEBPHOTO_RET_SUCCESS ;
}

function submit_exec()
{
	$this->clear_msg_array();

	$item_row = $this->create_item_row_by_post();

	$ret = $this->submit_exec_check( $item_row );
	if ( $ret < 0 ) {
		return $ret ;
	}

	$ret = $this->submit_exec_fetch( $item_row );
	if ( $ret < 0 ) {
		return $ret ;
	}

	$item_row    = $this->_row_fetch ;
	$photo_name  = $this->_photo_tmp_name ;
	$middle_name = $this->_middle_tmp_name ;
	$thumb_name  = $this->_thumb_tmp_name ;
	$small_name  = $this->_small_tmp_name ;

// --- insert item ---
	$item_row = $this->build_item_row_submit_insert( $item_row );
	$item_id  = $this->_item_handler->insert( $item_row );
	if ( !$item_id ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB ;
	}

	$item_row['item_id'] = $item_id;
	$this->_row_create = $item_row;

// uploaded photo
	if ( $photo_name || $middle_name || $thumb_name || $small_name ) {

// --- insert files
		$ret = $this->insert_media_files( 
			$item_row, $photo_name, $middle_name, $thumb_name, $small_name );
		if ( $ret < 0 ) {
			return $ret;
		}

// --- update item ---
		$item_row = $this->build_item_row_submit_update( $item_row);
		$ret = $this->_item_handler->update( $item_row );
		if ( !$ret ) {
			$this->set_error( $this->_item_handler->get_errors() );
			return _C_WEBPHOTO_ERR_DB;
		}
		$this->_row_create = $item_row;

		$this->unlink_uploaded_files();
	}

	$this->set_factory_error();
	$this->submit_exec_tag_save( $item_row );
	$this->submit_exec_post_count();
	$this->submit_exec_notify( $item_row );
}

function submit_exec_fetch( $row )
{
	$this->_row_fetch = $row ;

	$ret = $this->_external_build_class->build( $row ) ;
	if ( $ret <= 0 ) {
		$this->_row_fetch = $this->_external_build_class->get_item_row() ;
		return $ret;
	}

	$ret = $this->_embed_build_class->build( $row );
	if ( $ret <= 0 ) {
		$this->_row_fetch = $this->_embed_build_class->get_item_row() ;
		return $ret;
	}

	if ( $this->_FLAG_ADMIN ) {
		$ret = $this->_playlist_build_class->build( $row );
		if ( $ret <= 0 ) {
			$this->_row_fetch = $this->_playlist_build_class->get_item_row() ;
			return $ret;
		}
	}

// Check if upload file name specified
	if ( ! $this->check_xoops_upload_file( $flag_thumb=true ) ) {
		return _C_WEBPHOTO_ERR_NO_SPECIFIED;
	}

// fetch photo
	$ret = $this->submit_exec_fetch_photo( $row );
	if ( $ret < 0 ) { 
		return $ret;	// failed
	}

// fetch thumb middle
	$this->upload_fetch_thumb();
	$this->upload_fetch_middle();
	$this->upload_fetch_small();

// upload
	if ( $this->_photo_tmp_name ) {
		return 0;
	}

	$row = $this->_row_fetch ;

// check title
	if ( empty( $row['item_title'] ) ) {
		return _C_WEBPHOTO_ERR_NO_TITLE;
	}

// check allow no image mode
	if ( $this->_cfg_allownoimage ) {
		$row['item_kind'] = _C_WEBPHOTO_ITEM_KIND_NONE ;
		$this->_row_fetch = $row ;
		return 0;
	}

	return _C_WEBPHOTO_ERR_NO_IMAGE;
}

function submit_exec_tag_save( $item_row )
{
	$ret = $this->_tag_class->add_tags( 
		$item_row['item_id'], $this->_xoops_uid, $this->get_tag_name_array() );
	if ( !$ret ) { 
		return _C_WEBPHOTO_ERR_DB; 
	}
	return 0;
}

function submit_exec_post_count()
{
	$xoops_user_class =& webphoto_xoops_user::getInstance();
	$xoops_user_class->increment_post_by_num_own( $this->_cfg_addposts );
}

function submit_exec_notify( $row )
{
	if ( ! $this->_has_superinsert ) {
		return;
	}

// Trigger Notification when supper insert
	$notification_class =& webphoto_notification_event::getInstance(
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$notification_class->notify_new_photo( 
		$row['item_id'],  $row['item_cat_id'],  $row['item_title'] );
}

function notify_new_photo( $item_row )
{
	$notification_class =& webphoto_notification_event::getInstance(
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$notification_class->notify_new_photo( 
		$item_row['item_id'],  $item_row['item_cat_id'],  $item_row['item_title'] );
}

//---------------------------------------------------------
// media files 
//---------------------------------------------------------
function insert_media_files( $item_row )
{
	$ret = $this->create_media_file_params( $item_row );
	if ( $ret < 0 ) {
		return $ret;
	}

// --- insert file ---
	$this->_file_id_array = $this->insert_media_files_from_params( $item_row );
	return 0;
}

function create_media_file_params( $item_row, $is_submit=true )
{
	$photo_name  = $this->_photo_tmp_name ;
	$thumb_name  = $this->_thumb_tmp_name ;
	$middle_name = $this->_middle_tmp_name ;
	$small_name  = $this->_small_tmp_name ;

	$item_id   = $item_row['item_id'] ;
	$item_kind = $item_row['item_kind'] ;

	$thumb_param  = null;
	$middle_param = null;
	$small_param  = null;
	$flash_param  = null ;
	$docomo_param = null ;
	$pdf_param    = null ;
	$swf_param    = null ;
	$middle_thumb_param = null;

	$this->init_photo_create();

// -- photo tmp
// rotate tmp file
	$this->rotate_tmp_image( $photo_name, $this->_rotate_angle, true );

// -- cont 
// resize cont file
	$photo_param = $this->build_photo_param( $item_row );

	list( $ret, $cont_param ) = $this->create_cont_param( $photo_param );
	if ( $ret < 0 ) {
		return $ret ;
	}

// -- docomo, flash, pdf, video images
	if ( is_array($cont_param) ) {
		$docomo_param = $this->create_docomo_param( $photo_param, $cont_param  );
		$flash_param  = $this->create_flash_param(  $photo_param );
		$pdf_param    = $this->create_pdf_param(    $photo_param );
		$swf_param    = $this->create_swf_param(    $photo_param );

		$middle_thumb_param = $this->create_image_for_middle_thumb(
			$photo_param, $pdf_param, $flag_video=false );
		$this->create_video_plural_images( $photo_param );
	}

// -- thmub 
	if ( $thumb_name ) {
		$thumb_param = $this->create_thumb_param_by_tmp( $item_row, $thumb_name );

	} elseif ( $is_submit && $this->is_external_embed_playlist_kind( $item_kind ) ) {
		//	dummy

	} elseif ( is_array($cont_param) ) {
		$thumb_param = $this->create_thumb_param_by_photo( $middle_thumb_param );
	}

// -- middle 
	if ( $middle_name ) {
		$middle_param = $this->create_middle_param_by_tmp( $item_row, $middle_name );

	} elseif ( is_array($cont_param) ) {
		$middle_param = $this->create_middle_param_by_photo( $middle_thumb_param );
	}

// -- middle 
	if ( $small_name ) {
		$small_param = $this->create_small_param_by_tmp( $item_row, $small_name );

	} elseif ( is_array($cont_param) ) {
		$small_param = $this->create_small_param_by_photo( $middle_thumb_param );
	}

	$this->_media_file_params = array(
		'cont'   => $cont_param ,
		'thumb'  => $thumb_param ,
		'middle' => $middle_param ,
		'small'  => $small_param ,
		'flash'  => $flash_param ,
		'docomo' => $docomo_param ,
		'pdf'    => $pdf_param ,
		'swf'    => $swf_param ,
	);

	return 0;
}

function conv_rotate( $rotate )
{
	$rot = 0 ;
	switch( $rotate ) 
	{
		case 'rot270' :
			$rot = 270 ;
			break ;

		case 'rot180' :
			$rot = 180 ;
			break ;

		case 'rot90' :
			$rot = 90 ;
			break ;

		case 'rot0' :
		default :
			break ;
	}
	return $rot;
}

function rotate_tmp_image( $src_name, $rotate, $flag_rename=false )
{
	if ( empty($src_name) || empty($rotate) ) {
		return $src_name;	// no action
	}

	$dst_name = str_replace( 
		_C_WEBPHOTO_UPLOADER_PREFIX , 
		_C_WEBPHOTO_UPLOADER_PREFIX_ROT , 
		$src_name 
	) ;

	$src_file = $this->_TMP_DIR .'/'. $src_name;
	$dst_file = $this->_TMP_DIR .'/'. $dst_name ;
	$name     = $src_name ;

	$this->_factory_create_class->rotate_image( $src_file, $dst_file, $rotate );

	if ( is_file($dst_file) ) {
		if ( $flag_rename ) {
			unlink( $src_file );
			rename( $dst_file , $src_file ) ;

		} else {
			$name = $dst_name ;
		}
	}

	return $name ;
}

//---------------------------------------------------------
// create flash docomo
//---------------------------------------------------------
function create_docomo_param( $photo_param, $cont_param )
{
	return $this->_factory_create_class->create_docomo_param( $photo_param, $cont_param );
}

function create_flash_param( $photo_param )
{
	$flash_param = $this->_factory_create_class->create_flash_param( $photo_param );
	if ( $this->_factory_create_class->get_flag_flash_failed() ) {
		$this->set_msg_array( $this->get_constant('ERR_VIDEO_FLASH') ) ;
	}
	return $flash_param;
}

function create_pdf_param( $param )
{
	$pdf_param = $this->_factory_create_class->create_pdf_param( $param );
	if ( $this->_factory_create_class->get_flag_pdf_failed() ) {
		$this->set_msg_array( $this->get_constant('ERR_PDF') ) ;
	}
	return $pdf_param ;
}

function create_swf_param( $param )
{
	$swf_param = $this->_factory_create_class->create_swf_param( $param );
	if ( $this->_factory_create_class->get_flag_swf_failed() ) {
		$this->set_msg_array( $this->get_constant('ERR_SWF') ) ;
	}
	return $swf_param ;
}

function create_image_for_middle_thumb( $photo_param, $pdf_param, $flag_video )
{
	$img_param = $this->_factory_create_class->create_image_for_middle_thumb( 
		$photo_param, $pdf_param, $flag_video );
	$this->_image_tmp_file = $this->_factory_create_class->get_image_tmp_file();
	if ( $this->_factory_create_class->get_flag_image_ext_failed() ) {
		$this->set_msg_array( 'cannot create image ext' ) ;
	}
	return $img_param ; 
}

function create_video_plural_images( $param )
{
	$ret = $this->_factory_create_class->create_video_plural_images( $param );
	if ( $this->_factory_create_class->get_flag_video_image_created() ) {
		$this->_is_video_thumb_form = true;
	}
	if ( $this->_factory_create_class->get_flag_video_image_failed() ) {
		$this->set_msg_array( $this->get_constant('ERR_VIDEO_THUMB') ) ;
	}
	return $ret;
}

function set_factory_error()
{
	if ( $this->_is_module_admin && error_reporting() ) {
		$this->set_msg_array( $this->_factory_create_class->get_errors() );
	}
}

//---------------------------------------------------------
// video thumb
//---------------------------------------------------------
function video_thumb( $item_row )
{
	return $this->_factory_create_class->video_thumb( $item_row );
}

//---------------------------------------------------------
// print form video thumb
//---------------------------------------------------------
function print_form_video_thumb( $mode, $item_row )
{
	if ( $this->has_msg_array() ) {
		echo $this->get_format_msg_array() ;
		echo "<br />\n";
	}

	$form_class =& webphoto_edit_misc_form::getInstance(
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$form_class->print_form_video_thumb( $mode, $item_row );
}

//---------------------------------------------------------
// preview 
//---------------------------------------------------------
function create_preview_new( $photo_name )
{
	$preview_name = str_replace( 
		_C_WEBPHOTO_UPLOADER_PREFIX , 
		_C_WEBPHOTO_UPLOADER_PREFIX_PREV , 
		$photo_name 
	) ;

	$this->set_preview_name( $preview_name );

	$src_file = $this->_TMP_DIR .'/'. $photo_name;
	$dst_file = $this->_TMP_DIR .'/'. $preview_name;
	rename( $src_file , $dst_file ) ;

	return $this->build_preview( $preview_name ) ;
}

function build_preview( $preview_name )
{
	$rotate_name = $this->rotate_tmp_image( $preview_name, $this->_rotate_angle );

	$thumb_width     = 0;
	$thumb_height    = 0;
	$is_normal_image = false;

	$ext = $this->parse_ext( $rotate_name );

	$path_photo = $this->_TMP_DIR .'/'. $rotate_name ;
	$media_url  = $this->_MODULE_URL.'/index.php?fct=image_tmp&name='. rawurlencode( $rotate_name ) ;
	$img_thumb_src = $media_url;

// image type
	if ( $this->is_normal_ext( $ext ) ) {
		$is_normal_image = true;

		$size = GetImageSize( $path_photo ) ;
		if ( is_array($size) ) {
			$photo_width  = $size[0];
			$photo_height = $size[1];

			list ( $thumb_width, $thumb_height )
				= $this->_show_image_class->adjust_thumb_size( $photo_width, $photo_height );
		}

// other type
	} else {
		$img_thumb_src = $this->_URL_DAFAULT_IMAGE;
	}

	$arr = array(
		'media_url'        => $this->sanitize( $media_url ) ,
		'img_thumb_src_s'  => $this->sanitize( $img_thumb_src ) ,
		'img_thumb_width'  => $thumb_width ,
		'img_thumb_height' => $thumb_height ,
		'is_normal_image'  => $is_normal_image ,
	);
	return $arr;

}

function build_no_image_preview()
{
	$arr = array(
		'media_url_s'      => '' ,
		'img_thumb_src_s'  => $this->sanitize( $this->_URL_PIXEL_IMAGE ) ,
		'img_thumb_width'  => $this->_cfg_thumb_width ,
		'img_thumb_height' => $this->_cfg_thumb_height ,
		'is_normal_image'  => false,
	);
	return $arr;
}

function build_preview_template( $row )
{
	$tpl = new XoopsTpl() ;
	$tpl->assign( 'xoops_dirname' , $this->_DIRNAME ) ;
	$tpl->assign( 'mydirname' ,     $this->_DIRNAME ) ;
	$tpl->assign( $this->get_photo_globals() ) ;
	$tpl->assign( 'photo' , $row ) ;

// BUG: not show description in preview
	$tpl->assign( 'show_photo_desc' , true ) ;

// BUG: not show img alt
	$tpl->assign( $this->get_lang_array() ) ;

	$template = 'db:'. $this->_DIRNAME .'_inc_photo_in_list.html';
	return $tpl->fetch( $template ) ;
}

function set_preview_name( $val )
{
	$this->_preview_name = $val;
}

function get_preview_name()
{
	return $this->_preview_name;
}

// --- class end ---
}

?>