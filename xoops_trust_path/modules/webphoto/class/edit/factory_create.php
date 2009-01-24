<?php
// $Id: factory_create.php,v 1.2 2009/01/24 15:33:44 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_factory_create
//=========================================================
class webphoto_edit_factory_create extends webphoto_edit_base
{
	var $_cont_create_class;
	var $_middle_thumb_create_class;
	var $_flash_create_class;
	var $_docomo_create_class;
	var $_pdf_create_class;
	var $_video_middle_thumb_create_class;
	var $_item_build_class;
	var $_icon_build_class;
	var $_search_build_class;
	var $_ext_class;
	var $_exif_class;
	var $_msg_main_class;
	var $_msg_sub_class;

// config
	var $_cfg_use_pathinfo = false;
	var $_has_image_resize = false;
	var $_has_image_rotate = false;

// set param
	var $_flag_print_first_msg = false;
	var $_flag_force_db = false ;

// result
	var $_item_row     = null;
	var $_flag_resized = false;
	var $_flag_video_image_created = false ;
	var $_flag_video_image_failed  = false ;
	var $_flag_image_ext_created   = false;
	var $_flag_image_ext_failed    = false;
	var $_flag_flash_created       = false ;
	var $_flag_flash_failed        = false ;
	var $_flag_pdf_created         = false ;
	var $_flag_pdf_failed          = false ;

	var $_image_tmp_file     = null ;

	var $_cont_param  = null ;
	var $_video_param = null ;
	var $_msg_item    = null ;

	var $_TITLE_DEFAULT = 'no title';

	var $_FLAG_ADMIN = false ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_factory_create( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base( $dirname , $trust_dirname );

	$this->_cont_create_class   =& webphoto_edit_cont_create::getInstance( $dirname );
	$this->_flash_create_class  =& webphoto_edit_flash_create::getInstance( $dirname );
	$this->_docomo_create_class =& webphoto_edit_docomo_create::getInstance( $dirname  );
	$this->_icon_build_class    =& webphoto_edit_icon_build::getInstance( $dirname );
	$this->_search_build_class  =& webphoto_edit_search_build::getInstance( $dirname );
	$this->_item_build_class    =& webphoto_edit_item_build::getInstance( $dirname );
	$this->_middle_thumb_create_class =& webphoto_edit_middle_thumb_create::getInstance( $dirname );

	$this->_pdf_create_class   =& webphoto_edit_pdf_create::getInstance( 
		$dirname , $trust_dirname );
	$this->_video_middle_thumb_create_class =& webphoto_edit_video_middle_thumb_create::getInstance( 
		$dirname , $trust_dirname );
	$this->_ext_class =& webphoto_ext::getInstance( $dirname , $trust_dirname );
	$this->_exif_class   =& webphoto_exif::getInstance();

	$this->_msg_main_class = new webphoto_lib_msg();
	$this->_msg_sub_class  = new webphoto_lib_msg();

	$this->_cfg_use_pathinfo = $this->get_config_by_name( 'use_pathinfo' );

	$this->_has_image_resize  = $this->_cont_create_class->has_resize();
	$this->_has_image_rotate  = $this->_cont_create_class->has_rotate();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_factory_create( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// set param 
//---------------------------------------------------------
function set_flag_admin( $val )
{
	$this->_FLAG_ADMIN = (bool)$val;
	$this->_item_build_class->set_flag_admin( $val );
}

//---------------------------------------------------------
// create from file
//---------------------------------------------------------
function create_item_from_param( $item_row, $param )
{
	$this->_msg_main_class->clear_msg_array();
	$this->_msg_sub_class->clear_msg_array();

	$this->_item_row = null ;

	$ret = $this->check_item( $item_row, $param );
	if ( $ret < 0 ) {
		if ( $this->check_msg_level_admin() ) {
			$msg = $item_row['item_title'] .' : '. $this->get_msg_sub_str();
			$this->_msg_main_class->set_msg( $msg ) ;
		}
		return $ret;
	}

// --- insert item ---
	$item_row = $this->build_item_row_from_file( $item_row, $param['src_file'] ) ;
	$item_id  = $this->insert_item( $item_row ) ;
	if ( ! $item_id ) {
		if ( $this->check_msg_level_admin() ) {
			$msg = $item_row['item_title'] .' : '. $this->get_msg_sub_str();
			$this->_msg_main_class->set_msg( $msg ) ;
		}
		$this->set_error( $this->_item_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB ;
	}

	$item_row['item_id'] = $item_id ; 
	$this->_item_row     = $item_row ;

	if ( $this->_flag_print_first_msg ) {
		$msg = $this->build_msg_photo_title( $item_id, $item_row['item_title'] );
		$this->_msg_main_class->set_msg( $msg ) ;
	}

	$ret = $this->create_files_from_param( $item_row, $param );
	$this->_item_row = $item_row ;

	if ( $this->check_msg_level_admin() ) {
		$this->_msg_main_class->set_msg( $this->get_msg_sub_str() ) ;
	}

	return $ret ;
}

function create_files_from_param( $item_row, $param )
{
	$src_file          = $param['src_file'] ;
	$flag_video_single = isset($param['flag_video_single']) ? (bool)$param['flag_video_single'] : false ;
	$flag_video_plural = isset($param['flag_video_plural']) ? (bool)$param['flag_video_plural'] : false ;

	if ( empty($src_file) || !is_file($src_file) ) {
		return 0 ;	// no action
	}

// --- insert cont ---
	$cont_param   = null ;
	$flash_param  = null ;
	$docomo_param = null ;
	$pdf_param    = null ;

	$photo_param = $item_row ;
	$photo_param['src_file'] = $src_file ;
	$photo_param['src_ext']  = $item_row['item_ext'] ;
	$photo_param['src_kind'] = $item_row['item_kind'] ;

	$this->create_cont_param( $photo_param );
	$cont_param = $this->_cont_param ;

// -- docomo, flash, pdf, video images
	if ( is_array($cont_param) ) {
		$docomo_param = $this->create_docomo_param( $photo_param, $cont_param ) ;
		$flash_param  = $this->create_flash_param(  $photo_param ) ;
		$pdf_param    = $this->create_pdf_param(    $photo_param ) ;

		$middle_thumb_param = $this->create_image_for_middle_thumb( 
			$photo_param, $pdf_param, $flag_video_single );

		if ( $flag_video_plural ) {
			$this->create_video_plural_images( $photo_param );
		}

		$thumb_param  = $this->create_thumb_param(  $middle_thumb_param );
		$middle_param = $this->create_middle_param( $middle_thumb_param );
	}

	$file_params = array(
		'cont'   => $cont_param ,
		'thumb'  => $thumb_param ,
		'middle' => $middle_param ,
		'flash'  => $flash_param ,
		'docomo' => $docomo_param ,
		'pdf'    => $pdf_param ,
	);

	$file_id_array = $this->insert_files_from_params( 
		$item_row['item_id'], $file_params );

// --- update item ---
	$item_row = $this->build_item_row_submit_update( $item_row, $file_id_array );
	$ret = $this->update_item( $item_row );
	if ( ! $ret ) {
		return _C_WEBPHOTO_ERR_DB ;
	}
	$this->_item_row = $item_row ;

// remove temp file
	if ( is_file( $this->_image_tmp_file ) ) {
		unlink(   $this->_image_tmp_file );
	}

	return 0;
}

function build_msg_photo_title( $item_id, $title=null )
{
	if ( $this->_cfg_use_pathinfo ) {
		$url = $this->_MODULE_URL .'/index.php/photo/'. $item_id .'/';
	} else {
		$url = $this->_MODULE_URL .'/index.php?fct=photo&amp;p='. $item_id ;
	}

	$msg  = ' <a href="'. $url .'" target="_blank">';
	$msg .= $item_id;

	if ( $title ) {
		$msg .= ' : ';
		$msg .= $this->sanitize( $title );
	}

	$msg .= '</a> : ';
	return $msg ;
}

function check_item( $item_row, $param )
{
	if ( ! isset( $param['src_file'] ) ) {
		$this->_msg_sub_class->set_msg( 'Empty file', true );
		return _C_WEBPHOTO_ERR_EMPTY_FILE ;
	}

// TODO
//	if ( ! is_readable( $param['src_file'] ) ) {
//		$this->print_msg_level_admin( ' Cannot read file, ', true );
//		return _C_WEBPHOTO_ERR_FILEREAD;
//	}

	if ( ! isset( $item_row['item_cat_id'] ) ) {
		$this->_msg_sub_class->set_msg( 'Empty cat_id', true );
		return _C_WEBPHOTO_ERR_EMPTY_CAT ;
	}

	return 0;
}

function get_item_row()
{
	return $this->_item_row ;
}

function print_main_msg()
{
	echo $this->get_main_msg() ;
}

function get_main_msg()
{
	return $this->_msg_main_class->get_msg_str( ' ' );
}

//---------------------------------------------------------
// item row
//---------------------------------------------------------
function build_item_row_from_file( $row, $src_file )
{
	$row = $this->build_row_ext_kind( $row, $src_file );
	$row = $this->build_row_exif(     $row, $src_file );
	$row = $this->build_row_duration( $row, $src_file );
	$row = $this->build_row_status_onclick( $row );
	$row = $this->build_row_search( $row );
	return $row;
}

function build_item_row_photo( $row, $photo_name, $media_name )
{
	$file = $this->_TMP_DIR .'/'. $photo_name ;

// ext kind exif duration
	$row = $this->build_row_ext_kind(    $row, $photo_name );
	$row = $this->build_row_title_media( $row, $media_name );
	$row = $this->build_row_exif(     $row, $file );
	$row = $this->build_row_duration( $row, $file );
	return $row;
}

function build_item_row_submit_insert( $row, $tag_name_array=null )
{
// status onclick search
	$row = $this->build_row_status_onclick( $row );
	$row = $this->build_row_search( $row, $tag_name_array );
	return $row;
}

function build_item_row_submit_update( $row, $file_id_array, $tag_name_array=null )
{
// files content icon search
	$row = $this->build_row_content( $row, $file_id_array );
	$row = $this->build_row_files(   $row, $file_id_array );
	$row = $this->build_row_icon_if_empty( $row );
	$row = $this->build_row_search( $row, $tag_name_array );
	return $row;
}

function build_item_row_modify_post( $row, $checkbox )
{
	$row = $this->build_row_submit_by_post( $row, $checkbox );
	$row = $this->build_row_modify_by_post( $row );
	return $row;
}

function build_item_row_modify_update( $row, $file_id_array, $tag_name_array )
{
// files content search
	$row = $this->build_row_content( $row, $file_id_array );
	$row = $this->build_row_files(   $row, $file_id_array );
	$row = $this->build_row_search(  $row, $tag_name_array );
	return $row ;
}

function build_row_submit_by_post( $row, $checkbox )
{
	return $this->_item_build_class->build_row_submit_by_post( $row, $checkbox );
}

function build_row_modify_by_post( $row, $flag_status=true )
{
	return $this->_item_build_class->build_row_modify_by_post( $row, $flag_status );
}

function build_row_status_onclick( $row )
{
	return $this->_item_build_class->build_row_status_onclick( $row );
}

function build_row_files( $row, $file_id_array )
{
	return $this->_item_build_class->build_row_files( $row, $file_id_array );
}

function build_row_ext_kind( $row, $file )
{
	$ext  = $this->parse_ext( $file );
	$kind = $this->ext_to_kind( $ext );
	$row['item_ext']  = $ext ;
	$row['item_kind'] = $kind ;
	return $row;
}

function build_row_title_media( $row, $media_name )
{
	if ( empty( $row['item_title'] ) ) {
		$row['item_title'] = $this->strip_ext( $media_name ) ;
	}
	return $row;
}

//---------------------------------------------------------
// create cont
//---------------------------------------------------------
function build_photo_param( $row, $photo_name, $mime, $rotate=0 )
{
	if ( empty($photo_name) ) {
		return null; 
	}

	$param = $row ;
	$param['src_ext']  = $row['item_ext'] ;
	$param['src_kind'] = $row['item_kind'] ;
	$param['src_file'] = $this->_TMP_DIR .'/'. $photo_name ;
	$param['src_mime'] = $mime ;
	$param['rotate']   = $rotate ;

	return $param ;
}

function create_cont_param( $param )
{
	$ret = $this->_cont_create_class->create_param( $param );
	if ( $ret < 0 ) {
		return $ret ;
	}
	$this->_cont_param   = $this->_cont_create_class->get_param();
	$this->_flag_resized = $this->_cont_create_class->get_flag_resized();
	$this->_msg_sub_class->set_msg(  $this->_cont_create_class->get_msg_array() ) ;
	return 0 ;
}

function get_cont_param()
{
	return $this->_cont_param ;
}

function get_resized()
{
	return $this->_flag_resized ;
}

//---------------------------------------------------------
// create thumb middle
//---------------------------------------------------------
function build_middle_thumb_param( $row, $tmp_name )
{
	if ( empty($tmp_name) ) {
		return null; 
	}

	$param = $row;
	$param['src_file'] = $this->_TMP_DIR .'/'. $tmp_name ;

	return $param ;
}

function create_thumb_param( $param )
{
	$ret = $this->_middle_thumb_create_class->create_thumb_param( $param );
	$this->_msg_sub_class->set_msg( $this->_middle_thumb_create_class->get_msg_array() ) ;
	return $ret ;
}

function create_middle_param( $param )
{
	$ret = $this->_middle_thumb_create_class->create_middle_param( $param );
	$this->_msg_sub_class->set_msg( $this->_middle_thumb_create_class->get_msg_array() ) ;
	return $ret ;
}

//---------------------------------------------------------
// create image ext
//---------------------------------------------------------
function create_image_for_middle_thumb( $photo_param, $pdf_param, $flag_video )
{
// -- create image for thumb & middle
	$image_param = $photo_param;
	$image_param['flag_video'] = $flag_video ;
	$image_param['flag_extra'] = true ;

// if pdf file
	if ( isset( $pdf_param['file'] ) ) {
		$image_param['file_pdf'] = $pdf_param['file'] ;
	}

	$extra_param = $this->create_image_ext( $image_param ) ;
	if ( is_array($extra_param) ) {
		return    $extra_param ;
	}

// return orinal if not create
	return $photo_param ;
}

function create_image_ext( $param )
{
	$this->_image_tmp_file = null ;

	$extra_param = $this->_ext_class->create_image( $param );
	if ( isset( $extra_param['src_file'] ) ) {
		$this->_flag_image_ext_created = true ;
		$this->_image_tmp_file = $extra_param['src_file'] ;
		$this->_msg_sub_class->set_msg( 'create image ' . $param['src_ext'] ) ;
		return $extra_param ;

	} elseif ( isset( $extra_param['errors'] ) ) {
		$this->_flag_image_ext_failed = true;
		$this->set_error( $extra_param['errors'] ) ;
	}

	return null ;
}

function get_image_tmp_file()
{
	return $this->_image_tmp_file ;
}

function get_flag_image_ext_created()
{
	return $this->_flag_image_ext_created ;
}

function get_flag_image_ext_failed()
{
	return $this->_flag_image_ext_failed ;
}

//---------------------------------------------------------
// create video docomo
//---------------------------------------------------------
function create_docomo_param( $photo_param, $cont_param )
{
	if ( ! $this->is_video_kind( $photo_param['src_kind'] ) ) {
		return null;
	}

	$param = array_merge( $photo_param, $cont_param );
	$ret = $this->_docomo_create_class->create_param( $param );
	$this->_msg_sub_class->set_msg( $this->_docomo_create_class->get_msg_array() ) ;
	return $ret ;
}

//---------------------------------------------------------
// create video flash
//---------------------------------------------------------
function create_flash_param( $param )
{
	if ( ! $this->is_video_kind( $param['src_kind'] ) ) {
		return null;
	}

	$flash_param = $this->_flash_create_class->create_param( $param );
	$this->_flag_flash_created = $this->_flash_create_class->get_flag_created() ;
	$this->_flag_flash_failed  = $this->_flash_create_class->get_flag_failed() ;
	$this->_msg_sub_class->set_msg( $this->_flash_create_class->get_msg_array() ) ;
	$this->set_error( $this->_flash_create_class->get_errors() ) ;
	return $flash_param ;
}

function get_flag_flash_created()
{
	return $this->_flag_flash_created ;
}

function get_flag_flash_failed()
{
	return $this->_flag_flash_failed ;
}

//---------------------------------------------------------
// create pdf
//---------------------------------------------------------
function create_pdf_param( $param )
{
	if ( ! $this->is_general_kind( $param['src_kind'] ) ) {
		return null;
	}

	$pdf_param = $this->_pdf_create_class->create_param( $param );
	$this->_flag_pdf_created = $this->_pdf_create_class->get_flag_created() ;
	$this->_flag_pdf_failed  = $this->_pdf_create_class->get_flag_failed() ;
	$this->_msg_sub_class->set_msg( $this->_pdf_create_class->get_msg_array() ) ;
	return $pdf_param ;
}

function get_flag_pdf_created()
{
	return $this->_flag_pdf_created ;
}

function get_flag_pdf_failed()
{
	return $this->_flag_pdf_failed ;
}

//---------------------------------------------------------
// vodeo images
//---------------------------------------------------------
function create_video_plural_images( $param )
{
	$ret = $this->_video_middle_thumb_create_class->create_video_plural_images( $param );
	$this->_flag_video_image_created = $this->_video_middle_thumb_create_class->get_flag_created();
	$this->_flag_video_image_failed  = $this->_video_middle_thumb_create_class->get_flag_failed();
	return $ret;
}

function get_flag_video_image_created()
{
	return $this->_flag_video_image_created ;
}

function get_flag_video_image_failed()
{
	return $this->_flag_video_image_failed ;
}

function video_thumb( $row )
{
	return $this->_video_middle_thumb_create_class->video_thumb( $row );
}

//---------------------------------------------------------
// file extention
//---------------------------------------------------------
function build_row_exif( $row, $src_file )
{
	if ( ! $this->is_image_kind( $row['item_kind'] ) ) {
		return $row ;
	}

	$extra_param = $this->_exif_class->build_row_exif( $row, $src_file );
	if ( isset( $extra_param['row'] ) ) {
		$row =  $extra_param['row'] ;
	}
	if ( isset( $extra_param['flag'] ) ) {
		$flag = $extra_param['flag'] ;
		if ( $flag == 2 ) {
			$this->_msg_sub_class->set_msg( 'get exif' ) ;
		} else {
			$this->_msg_sub_class->set_msg( 'no exif' )  ;
		}
	} 

	return $row ;
}

function build_row_duration( $row, $src_file )
{
	$param = $row ;
	$param['src_file'] = $src_file ;

	$extra_param = $this->_ext_class->get_duration_size( $param );
	if ( is_array($extra_param) ) {
		$this->set_msg( 'get duration' ) ;
		$row['item_duration'] = $extra_param['duration'] ;
		$row['item_width']    = $extra_param['width'] ;
		$row['item_height']   = $extra_param['height'] ;
	}

	return $row ;
}

function build_row_content( $row, $file_id_array )
{
	$file_cont = $this->get_file_full_by_key( $file_id_array, 'cont_id' ) ;
	$file_pdf  = $this->get_file_full_by_key( $file_id_array, 'pdf_id' ) ;

	$param = $row ;
	$param['src_ext']   = $row['item_ext'] ;
	$param['file_cont'] = $file_cont ;
	$param['file_pdf']  = $file_pdf  ;

	$extra_param = $this->_ext_class->get_text_content( $param );
	if ( isset( $extra_param['content'] ) ) {
		$row['item_content'] = $extra_param['content'] ;
		$this->_msg_sub_class->set_msg( 'get content' )  ;

	} elseif ( isset( $extra_param['errors'] ) ) {
		$this->set_error( $extra_param['errors'] );
	}

	return $row ;
}

//---------------------------------------------------------
// icon
//---------------------------------------------------------
function build_row_icon_if_empty( $row, $ext=null )
{
	return $this->_icon_build_class->build_row_icon_if_empty( $row, $ext ) ;
}

//---------------------------------------------------------
// search
//---------------------------------------------------------
function build_row_search( $row, $tag_name_array=null )
{
	return $this->_search_build_class->build_row( $row, $tag_name_array );
}

//---------------------------------------------------------
// item handler
//---------------------------------------------------------
function insert_item( $row )
{
	$newid = $this->_item_handler->insert( $row, $this->_flag_force_db );
	if ( ! $newid ) {
		$this->_msg_sub_class->set_msg( 'DB Error', true ) ;
		$this->set_error( $this->_item_handler->get_errors() );
		return false ;
	}
	return $newid ;
}

function update_item( $row )
{
	$ret = $this->_item_handler->update( $row, $this->_flag_force_db );
	if ( ! $ret ) {
		$this->_msg_sub_class->set_msg( 'DB Error', true );
		$this->set_error( $this->_item_handler->get_errors() );
		return false ;
	}
	return true ;
}

//---------------------------------------------------------
// file handler
//---------------------------------------------------------
function insert_files_from_params( $item_id, $params )
{
	if ( !is_array($params) ) {
		return false;
	}

	$arr = array(
		'cont_id'   => $this->insert_file_by_params( $item_id, $params, 'cont' ) ,
		'thumb_id'  => $this->insert_file_by_params( $item_id, $params, 'thumb' ) ,
		'middle_id' => $this->insert_file_by_params( $item_id, $params, 'middle' ) ,
		'flash_id'  => $this->insert_file_by_params( $item_id, $params, 'flash' ) ,
		'docomo_id' => $this->insert_file_by_params( $item_id, $params, 'docomo' ) ,
		'pdf_id'    => $this->insert_file_by_params( $item_id, $params, 'pdf' ) ,
	);
	return $arr ;
}

function update_files_from_params( $row, $params )
{
	if ( !is_array($params) ) {
		return false;
	}

	$arr = array(
		'cont_id'   => $this->update_file_by_params( $row, $params, 'cont' ) ,
		'thumb_id'  => $this->update_file_by_params( $row, $params, 'thumb' ) ,
		'middle_id' => $this->update_file_by_params( $row, $params, 'middle' ) ,
		'flash_id'  => $this->update_file_by_params( $row, $params, 'flash' ) ,
		'docomo_id' => $this->update_file_by_params( $row, $params, 'docomo' ) ,
	);
	return $arr ;
}

function insert_file_by_params( $item_id, $params, $name )
{
	if ( isset( $params[ $name ] ) && is_array( $params[ $name ] ) ) {
		return $this->insert_file( $item_id,  $params[ $name ] );
	}
	return 0;
}

function update_file_by_params( $row, $params, $name )
{
	$item_id = $row['item_id'] ;

	if ( ! isset( $params[ $name ] ) ) {
		return 0 ;
	}

	$param = $params[ $name ] ;
	if ( ! is_array($param) ) {
		return 0 ;
	}

	$file_row = $this->get_file_row_by_kind( $row, $param['kind'] );

// update if exists
	if ( is_array($file_row) ) {
		$file_id = $file_row['file_id'];

// remove old file
		$this->unlink_file_in_row( $file_row, $param );

		$ret = $this->update_file( $file_row, $param );
		if ( !$ret ) {
			return 0 ;
		}
		return $file_id;

// insert if new
	} else {
		return $this->insert_file( $item_id, $param );
	}
}

function insert_file( $item_id, $param )
{
	$param['item_id'] = $item_id ;

	$row = $this->_file_handler->create( true );
	$row = $this->_file_handler->build_row_by_param( $row, $param );

	$newid = $this->_file_handler->insert( $row, $this->_flag_force_db );
	if ( ! $newid ) {
		$this->_msg_sub_class->set_msg( 'DB Error', true );
		$this->set_error( $this->_file_handler->get_errors() );
		return false ;
	}

	return $newid;
}

function update_file( $row, $param )
{
	$param['time_update'] = time();

	$row = $this->_file_handler->build_row_by_param( $row, $param );

// update
	$ret = $this->_file_handler->update( $row );
	if ( ! $ret ) {
		$this->_msg_sub_class->set_msg( 'DB Error', true );
		$this->set_error( $this->_file_handler->get_errors() );
		return false ;
	}

	return true ;
}

function get_file_full_by_key( $arr, $key )
{
	$file = null;
	$id   = isset( $arr[ $key ] ) ? intval( $arr[ $key ] ) : 0 ;
	if ( $id > 0 ) {
		$file_row = $this->_file_handler->get_row_by_id( $id );
		if ( is_array($file_row) ) {
			$file = XOOPS_ROOT_PATH . $file_row['file_path'] ;
		}
	}
	return $file;
}

function unlink_file_in_row( $file_row, $param )
{
	$file_path = $file_row['file_path'];
	$path      = $param['path'];

	if ( $file_path && ( $file_path != $path ) ) {
		$this->unlink_path($file_path);
	}
}

function unlink_path( $path )
{
	$file = XOOPS_ROOT_PATH . $path;
	if ( $path && $file && file_exists($file) && is_file($file) && !is_dir($file) ) {
		unlink( $file );
	}
}

//---------------------------------------------------------
// msg
//---------------------------------------------------------
function get_msg_sub_str()
{
	return $this->_msg_sub_class->get_msg_str( ', ' );
}

//---------------------------------------------------------
// set & get param
//---------------------------------------------------------
function set_flag_force_db( $val )
{
	$this->_flag_force_db = (bool)$val;
}

function set_flag_print_first_msg( $val )
{
	$this->_flag_print_first_msg = (bool)$val;
}

function has_image_resize()
{
	return $this->_has_image_resize;
}

function has_image_rotate()
{
	return $this->_has_image_rotate;
}

// --- class end ---
}

?>