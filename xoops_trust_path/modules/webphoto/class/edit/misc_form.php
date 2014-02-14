<?php
// $Id: misc_form.php,v 1.7 2011/04/30 23:30:20 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// Fatal error: Call to undefined method get_cached_file_row_by_kind()
// 2010-11-11 K.OHWADA
// build_file_url_by_file_row()
// 2010-10-01 K.OHWADA
// item_kind_list_video
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_ffmpeg
// move item_embed_type_select_options()
// 2009-05-05 K.OHWADA
// use build_form_mode_param()
// 2009-04-19 K.OHWADA
// print_form_editor() -> build_form_editor_with_template()
// 2009-01-10 K.OHWADA
// webphoto_photo_misc_form -> webphoto_edit_misc_form
// webphoto_ffmpeg
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_misc_form
//=========================================================
class webphoto_edit_misc_form extends webphoto_edit_form
{
	var $_editor_class ;
	var $_ffmpeg_class;
	var $_icon_build_class ;
	var $_kind_class;

	var $_ini_kind_list_video = null;

	var $_VIDEO_THUMB_WIDTH = 120;
	var $_VIDEO_THUMB_MAX   = _C_WEBPHOTO_VIDEO_THUMB_PLURAL_MAX ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_misc_form( $dirname, $trust_dirname )
{
	$this->webphoto_edit_form( $dirname, $trust_dirname );

	$this->_editor_class 
		=& webphoto_editor::getInstance( $dirname, $trust_dirname );
	$this->_ffmpeg_class 
		=& webphoto_ffmpeg::getInstance( $dirname, $trust_dirname );
	$this->_icon_build_class 
		=& webphoto_edit_icon_build::getInstance( $dirname );
	$this->_kind_class   =& webphoto_kind::getInstance();

	$this->_ini_kind_list_video = $this->explode_ini('item_kind_list_video');
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_misc_form( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// editor
//---------------------------------------------------------
function build_form_editor_with_template( $mode, $item_row )
{
	$template = 'db:'. $this->_DIRNAME .'_form_editor.html';

	list( $show_editor, $param_editor ) =
		$this->build_form_editor( $item_row, $mode );

	if ( ! $show_editor ) {
		return '';
	}

	$arr = array_merge( 
		$this->build_form_select_param( $mode ) ,
		$this->build_form_base_param() ,
		$this->build_item_row( $item_row ) ,
		$param_editor 
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	return $tpl->fetch( $template ) ;
}

function build_form_editor( $item_row, $mode=null )
{
	$options  = $this->_editor_class->build_list_options( true );

	if ( ! $this->is_show_form_editor_option( $options ) ) {
		return array( false, array() );
	}

	$param = array(
		'mode'    => $this->get_form_mode_default( $mode ) ,
		'options' => $options
	);

	$arr = array( 
		true, 
		$this->build_form_editor_with_param( $item_row, $param )
	);
	return $arr; 
}

function build_form_editor_with_param( $row, $param )
{
	$mode    = $param['mode'] ;
	$options = $param['options'] ;

	switch ($mode)
	{
		case 'bulk':
			$op  = 'bulk_form';
			break;

		case 'file':
			$op  = 'file_form';
			break;

		case 'admin_modify':
			$op  = 'modify_form';
			break;

		case 'user_submit':
		case 'admin_submit':
		case 'admin_batch':
		default:
			$op  = 'submit_form';
			break;
	}

	$this->set_row( $row );

	$arr = array(
		'op_editor' => $op ,
		'item_editor_select_options' => $this->item_editor_select_options( $options ) ,
	);

	return $arr;
}

function item_editor_select_options( $options )
{
	$value = $this->get_item_editor( true );
	return $this->build_form_options( $value, $options );
}

//---------------------------------------------------------
// embed
//---------------------------------------------------------
function build_form_embed_with_template( $mode, $item_row )
{
	$template = 'db:'. $this->_DIRNAME .'_form_embed.html';

	if ( !$this->is_show_form_admin( $item_row ) ) {
		return '';
	}

	$arr = array_merge( 
		$this->build_form_select_param( $mode ) ,
		$this->build_form_base_param() ,
		$this->build_form_embed_with_row( $item_row ) ,
		$this->build_item_row( $item_row ) 
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	return $tpl->fetch( $template ) ;
}

function build_form_embed( $item_row )
{
	if ( ! $this->is_show_form_embed() ) {
		return array( false, array() );
	}

	$arr = array( 
		true, 
		$this->build_form_embed_with_row( $item_row )
	);
	return $arr; 
}

function build_form_embed_with_row( $item_row )
{
	$this->set_row( $item_row );

	$arr = array(
		'item_embed_type_select_options' => $this->item_embed_type_select_options() 
	);
	return $arr;
}

//---------------------------------------------------------
// video thumb
//---------------------------------------------------------
function build_form_video_thumb_with_template( $mode, $row )
{
	$template = 'db:'. $this->_DIRNAME .'_form_video_thumb.html';

	$arr = array_merge( 
		$this->build_form_mode_param( $mode ) ,
		$this->build_form_base_param() ,
		$this->build_form_video_thumb( $row, true )
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	return $tpl->fetch( $template ) ;
}

function build_form_video_thumb( $row, $flag_row )
{
	$param = array(
		'video_thumb_array'    => $this->build_video_thumb_array( $row ) ,
		'colspan_video_submit' => $this->_VIDEO_THUMB_MAX + 1,
	);

	if ( $flag_row ) {
		$arr = array_merge( $param, $this->build_item_row( $row ) );
	} else {
		$arr = $param;
	}

	return $arr;
}

function build_video_thumb_array( $row )
{
	$item_id = $row['item_id'];
	$ext     = $row['item_ext'];

	$arr = array();
	for ( $i = 0; $i <= $this->_VIDEO_THUMB_MAX; $i ++ ) 
	{
		$src   = null ;
		$width = 0 ;

// default icon
		if ( $i == 0 ) {
			list( $name, $width, $height ) = 
				$this->_icon_build_class->build_icon_image( $ext );
			if ( $name ) {
				$src = $this->_ROOT_EXTS_URL .'/'. $name ;
			}

// created thumbs
		} else {
		 	$name  = $this->_ffmpeg_class->build_thumb_name( $item_id, $i );
			$file  = $this->_TMP_DIR .'/'. $name ;
			$width = $this->_VIDEO_THUMB_WIDTH ;

			if ( is_file($file) ) {
				$name_encode = rawurlencode( $name );
				$src = $this->_MODULE_URL.'/index.php?fct=image_tmp&name='. $name_encode ;
			}
		}

		$arr[] =array(
			'src_s' => $this->sanitize( $src ) ,
			'width' => $width ,
			'num'   => $i ,
		);
	}
	return $arr;
}

//---------------------------------------------------------
// redo
//---------------------------------------------------------
function build_form_redo_with_template( $mode, $item_row, $flash_row )
{
	$template = 'db:'. $this->_DIRNAME .'_form_redo.html';

	if ( ! $this->is_show_form_redo( $item_row ) ) {
		return '';
	}

	$arr = array_merge( 
		$this->build_form_mode_param( $mode ) ,
		$this->build_form_base_param() ,
		$this->build_form_redo_by_flash_row( $flash_row ) ,
		$this->build_item_row( $item_row )
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	return $tpl->fetch( $template ) ;
}

function build_form_redo_by_item_row( $item_row )
{
	if ( ! $this->is_show_form_redo( $item_row ) ) {
		return array( false, array() ) ;
	}

// Fatal error: Call to undefined method get_cached_file_row_by_kind()
	$flash_row = $this->get_cached_file_extend_row_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH ) ;

	$arr = array(
		true ,
		$this->build_form_redo_by_flash_row( $flash_row )
	);
	return $arr;
}

function build_form_redo_by_flash_row( $flash_row )
{
	$arr = array(
		'flash_url_s' => $this->build_flash_url_s( $flash_row )
	);
	return $arr;
}

function build_flash_url_s( $flash_row )
{
	return $this->sanitize( 
		$this->build_file_url_by_file_row( $flash_row ) );
}

function is_show_form_redo( $item_row )
{
	if ( $this->is_video_kind( $item_row['item_kind'] ) ) {
		return true;
	}
	return false ;
}

function is_video_kind( $kind )
{
	if ( in_array( $kind, $this->_ini_kind_list_video ) ) {
		return true;
	}
	return false;
}

// --- class end ---
}

?>