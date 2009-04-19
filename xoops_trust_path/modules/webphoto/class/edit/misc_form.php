<?php
// $Id: misc_form.php,v 1.2 2009/04/19 11:39:45 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
	var $_embed_class ;
	var $_editor_class ;
	var $_ffmpeg_class;
	var $_icon_build_class ;

	var $_VIDEO_THUMB_WIDTH = 120;
	var $_VIDEO_THUMB_MAX   = _C_WEBPHOTO_VIDEO_THUMB_PLURAL_MAX ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_misc_form( $dirname, $trust_dirname )
{
	$this->webphoto_edit_form( $dirname, $trust_dirname );

	$this->_embed_class  =& webphoto_embed::getInstance( $dirname, $trust_dirname );
	$this->_editor_class =& webphoto_editor::getInstance( $dirname, $trust_dirname );
	$this->_ffmpeg_class =& webphoto_ffmpeg::getInstance( $dirname );
	$this->_icon_build_class =& webphoto_edit_icon_build::getInstance( $dirname );

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
function build_form_editor_with_template( $item_row, $param )
{
	$template = 'db:'. $this->_DIRNAME .'_form_editor.html';

	$param_1 = array(
		'action'        => $param['action'] ,
		'fct'           => $param['fct'] ,
		'form_embed'    => $param['form_embed'] ,
		'form_editor'   => $param['form_editor'] ,
		'form_playlist' => $param['form_playlist'] ,
	);

	$arr = array_merge( 
		$this->build_form_param() ,
		$this->build_form_editor( $item_row, $param ) ,
		$this->build_item_row( $item_row ) ,
		$param_1
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	return $tpl->fetch( $template ) ;
}

function build_form_editor( $row, $param )
{
	$mode    = $param['mode'] ;
	$options = $param['options'] ;

	switch ($mode)
	{
		case 'admin_submit':
			$op  = 'submit_form';
			break;

		case 'admin_modify':
			$op  = 'modify_form';
			break;

		case 'user_submit':
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
function build_form_embed_with_template( $item_row, $param )
{
	$template = 'db:'. $this->_DIRNAME .'_form_embed.html';

	$param_1 = array(
		'action'        => $param['action'] ,
		'fct'           => $param['fct'] ,
		'form_embed'    => $param['form_embed'] ,
		'form_editor'   => $param['form_editor'] ,
		'form_playlist' => $param['form_playlist'] ,
	);

	$arr = array_merge( 
		$this->build_form_param() ,
		$this->build_form_embed( $item_row ) ,
		$this->build_item_row( $item_row ) ,
		$param_1
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	return $tpl->fetch( $template ) ;
}

function build_form_embed( $row )
{
	$this->set_row( $row );

	$arr = array(
		'item_embed_type_select_options' => $this->item_embed_type_select_options() 
	);
	return $arr;
}

function item_embed_type_select_options()
{ 
	$value   = $this->get_item_embed_type( true );
	$options = $this->_embed_class->build_type_options( $this->_is_module_admin );
	return $this->build_form_options( $value, $options );
}

//---------------------------------------------------------
// video thumb
//---------------------------------------------------------
function build_form_video_thumb_with_template( $row, $param )
{
	$template = 'db:'. $this->_DIRNAME .'_form_video_thumb.html';

	$param_1 = array(
		'action'  => $param['action'] ,
		'fct'     => $param['fct'] ,
	);

	$arr = array_merge( 
		$this->build_form_param() ,
		$this->build_form_video_thumb( $row, false ) ,
		$this->build_item_row( $row ) ,
		$param_1
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
function build_form_redo_with_template( $item_row,$flash_row, $param )
{
	$template = 'db:'. $this->_DIRNAME .'_form_redo.html';

	$param_1 = array(
		'action'  => $param['action'] ,
		'fct'     => $param['fct'] ,
	);

	$arr = array_merge( 
		$this->build_form_param() ,
		$this->build_form_redo( $flash_row ) ,
		$this->build_item_row( $item_row ) ,
		$param_1
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	return $tpl->fetch( $template ) ;
}

function build_form_redo( $flash_row )
{
	$arr = array(
		'flash_url_s' => $this->build_flash_url_s( $flash_row )
	);
	return $arr;
}

function build_flash_url_s( $flash_row )
{
	return $this->sanitize( 
		$this->build_file_url_size( $flash_row ) );
}

// --- class end ---
}

?>