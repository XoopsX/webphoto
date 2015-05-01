<?php
// $Id: photo_misc_form.php,v 1.1 2009/01/06 09:42:30 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_misc_form
//=========================================================
class webphoto_photo_misc_form extends webphoto_form_this
{
	var $_embed_class ;
	var $_editor_class ;
	var $_create_class ;

	var $_VIDEO_THUMB_WIDTH = 120;
	var $_VIDEO_THUMB_MAX   = _C_WEBPHOTO_VIDEO_THUMB_PLURAL_MAX ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_misc_form( $dirname, $trust_dirname )
{
	$this->webphoto_form_this( $dirname, $trust_dirname );

	$this->_embed_class  =& webphoto_embed::getInstance( $dirname, $trust_dirname );
	$this->_editor_class =& webphoto_editor::getInstance( $dirname, $trust_dirname );
	$this->_create_class =& webphoto_photo_create::getInstance( $dirname, $trust_dirname ); 

}

public static function &getInstance( $dirname = null, $trust_dirname = null )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo_misc_form( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// editor
//---------------------------------------------------------
function print_form_editor( $row, $param )
{
	$mode    = $param['mode'] ;
	$type    = $param['type'] ;
	$options = $param['options'] ;

	switch ($mode)
	{
		case 'admin_submit':
			$url = $this->_MODULE_URL .'/admin/index.php';
			$fct = 'item_manager';
			$op  = 'submit_form';
			break;

		case 'admin_modify':
			$url = $this->_MODULE_URL .'/admin/index.php';
			$fct = 'item_manager';
			$op  = 'modify_form';
			break;

		case 'user_submit':
		default:
			$url = $this->_MODULE_URL .'/index.php';
			$fct = 'submit';
			$op  = 'submit_form';
			break;
	}

	$this->set_row( $row );

	echo $this->build_form_tag( 'editor', $url );
	echo $this->build_html_token();

	echo $this->build_input_hidden( 'fct',  $fct );
	echo $this->build_input_hidden( 'op',   $op );
	echo $this->build_input_hidden( 'type', $type );
	echo $this->build_input_hidden( 'editor_form', 1 );

	echo $this->build_row_hidden( 'item_id' );
	echo $this->build_row_hidden( 'item_kind' );
	echo $this->build_row_hidden( 'item_embed_type' );
	echo $this->build_row_hidden( 'item_playlist_type' );

	echo $this->build_table_begin();
	echo $this->build_line_title( $this->get_constant('TITLE_EDITOR_SELECT') );

	echo $this->build_line_ele( $this->get_constant('ITEM_EDITOR'), 
		$this->_build_ele_editor_option( $options ) );

	echo $this->build_line_ele( '', 
		$this->build_input_submit( 'submit', $this->get_constant('BUTTON_SELECT') ) );

	echo $this->build_table_end();
	echo $this->build_form_end();
	echo "<br />\n";
}

function _build_ele_editor_option( $options )
{
	$value = $this->get_item_editor();
	return $this->build_form_select( 'item_editor', $value, $options, 1 );
}

//---------------------------------------------------------
// embed
//---------------------------------------------------------
function print_form_embed( $mode, $row )
{
	$editor_form = $this->_post_class->get_post_int('editor_form');

	switch ($mode)
	{
		case 'admin_submit':
			$url = $this->_MODULE_URL .'/admin/index.php';
			$fct = 'item_manager';
			break;

		case 'user_submit':
		default:
			$url = $this->_MODULE_URL .'/index.php';
			$fct = 'submit';
			break;
	}

	$this->set_row( $row );

	echo $this->build_form_tag( 'external', $url );
	echo $this->build_html_token();

	echo $this->build_input_hidden( 'fct',   $fct );
	echo $this->build_input_hidden( 'op',   'submit_form' );
	echo $this->build_input_hidden( 'type', 'embed' );
	echo $this->build_input_hidden( 'editor_form', $editor_form );

	echo $this->build_row_hidden( 'item_editor' );

	echo $this->build_table_begin();
	echo $this->build_line_title( $this->get_constant('EMBED_ADD') );

	echo $this->build_line_ele( $this->get_constant('ITEM_EMBED_TYPE'), 
		$this->_build_ele_embed_type_option() );

	echo $this->build_line_ele( '', 
		$this->build_input_submit( 'submit', $this->get_constant('BUTTON_SELECT') ) );

	echo $this->build_table_end();
	echo $this->build_form_end();
	echo "<br />\n";
}

function _build_ele_embed_type_option()
{
	$value   = $this->get_item_embed_type( true );
	$options = $this->_embed_class->build_type_options( $this->_is_module_admin );

	return $this->build_form_select( 'item_embed_type', $value, $options, 1 );
}

//---------------------------------------------------------
// delete confirm
//---------------------------------------------------------
function print_form_delete_confirm( $mode, $item_id )
{
	switch ($mode)
	{
		case 'admin':
			$url = $this->_MODULE_URL .'/admin/index.php';
			$fct = 'item_manager';
			break;

		case 'user':
		default:
			$url = $this->_MODULE_URL .'/index.php';
			$fct = 'edit';
			break;
	}

	$hiddens = array(
		'fct'      => $fct ,
		'op'       => 'delete' ,
		'item_id'  => $item_id ,
		'photo_id' => $item_id ,
	);

	echo $this->build_form_confirm( 
		$hiddens, $url, $this->get_constant('CONFIRM_PHOTODEL'), _YES, _NO );

}

//---------------------------------------------------------
// video thumb
//---------------------------------------------------------
function print_form_video_thumb( $mode, $row )
{
	$item_id = $row['item_id'];
	$ext     = $row['item_ext'];

	switch ($mode)
	{
		case 'admin_submit':
		case 'admin_modify':
			$fct = $this->_THIS_ADMIN_FCT ;
			break;

		case 'edit':
			$fct = $this->_THIS_EDIT_FCT ;
			break;

		case 'submit_file':
			$fct = $this->_THIS_FILE_FCT ;
			break;

		case 'submit':
		default:
			$fct = $this->_THIS_SUBMIT_FCT ;
			break;
	}

	$MAX = $this->_VIDEO_THUMB_MAX;
	$colspan = $MAX + 1 ;

	echo $this->build_form_begin();
	echo $this->build_input_hidden( 'op',       'video' );
	echo $this->build_input_hidden( 'fct',      $fct );
	echo $this->build_input_hidden( 'mode',     $mode );
	echo $this->build_input_hidden( 'item_id',  $item_id );
	echo $this->build_input_hidden( 'photo_id', $item_id );

	echo $this->build_table_begin();
	echo $this->build_line_title( $this->get_constant('TITLE_VIDEO_THUMB_SEL'), $colspan );
	echo "<tr>\n";

	for ( $i = 0; $i <= $MAX; $i ++ ) 
	{

// default icon
		if ( $i == 0 ) {
			list( $name, $width, $height ) = 
				$this->_create_class->build_icon_image( $ext );
			if ( $name ) {
				$url = $this->_ROOT_EXTS_URL .'/'. $name ;
				$this->print_form_video_thumb_single( $url, $width, $i );
			}

// created thumbs
		} else {
		 	$name  = $this->_create_class->build_video_thumb_name( $item_id, $i );
			$file  = $this->_TMP_DIR .'/'. $name ;
			$width = $this->_VIDEO_THUMB_WIDTH ;

			if ( is_file($file) ) {
				$name_encode = rawurlencode( $name );
				$url = $this->_MODULE_URL.'/index.php?fct=image_tmp&name='. $name_encode ;
				$this->print_form_video_thumb_single( $url, $width, $i );
			}
		}
	}

	echo "</tr>\n";
	echo '<tr><td align="center" class="head" colspan="'. $colspan .'">';
	echo '<input type="submit" name="submit" value="'.$this->get_constant('BUTTON_SELECT').'" />';
	echo "</td></tr>\n";

	echo $this->build_table_end();
	echo $this->build_form_end();

}

function print_form_video_thumb_single( $url, $width, $num )
{
	echo '<td align="center" class="odd">';
	echo '<img src="'. $this->sanitize($url) .'" width="'. $width .'"><br />';
	echo '<input type="radio" name="num" value="'. $num .'" />';
	echo "</td>\n";
}

//---------------------------------------------------------
// redo
//---------------------------------------------------------
function print_form_redo( $mode, $item_row, $flash_row )
{
	$item_id = $item_row['item_id'];

	switch ($mode)
	{
		case 'admin':
			$fct = $this->_THIS_ADMIN_FCT ;
			break;

		case 'edit':
		default:
			$fct = $this->_THIS_EDIT_FCT ;
			break;
	}

	$this->set_row( $item_row );

	echo $this->build_form_begin( 'webphoto_redo' );
	echo $this->build_input_hidden( 'op',       'redo' );
	echo $this->build_input_hidden( 'fct',      $fct );
	echo $this->build_input_hidden( 'item_id',  $item_id );
	echo $this->build_input_hidden( 'photo_id', $item_id );

	echo $this->build_table_begin();
	echo $this->build_line_title( $this->get_constant('TITLE_VIDEO_REDO') );

	echo $this->build_line_ele( $this->get_constant('CAP_REDO_FLASH'), 
		$this->_build_ele_redo_flash( $flash_row ) );

	if ( $this->_cfg_makethumb ) {
		echo $this->build_line_ele( $this->get_constant('CAP_REDO_THUMB'), 
			$this->_build_ele_redo_thumb() );
	}

	echo $this->build_line_ele( '', $this->build_input_submit( 'submit', _EDIT ) );

	echo $this->build_table_end();
	echo $this->build_form_end();

}

function _build_ele_redo_thumb()
{
	$text  = $this->build_input_checkbox_yes( 'redo_thumb', 1 );
	$text .= ' '.$this->get_constant('CAP_REDO_THUMB') ;
	return $text;
}

function _build_ele_redo_flash( $flash_row )
{
	$url = '' ;
	if ( is_array($flash_row) ) {
		$url = $flash_row['file_url'] ;
	}

	$ele  = $this->build_input_checkbox_yes( 'redo_flash', 1 );
	$ele .= ' ';
	$ele .= $this->get_constant('CAP_REDO_FLASH') ;

	if ( $url ) {
		$ele .= "<br />\n";
		$ele .= $this->build_link_blank( $url );
		$ele .= $this->build_photo_delete_button( 'flash_delete' );
	}

	return $ele ;
}

//---------------------------------------------------------
// form file
//---------------------------------------------------------
function print_form_file( $param )
{
	$has_resize    = $param['has_resize'];
	$allowed_exts  = $param['allowed_exts'];

	echo $this->build_form_begin();
	echo $this->build_input_hidden( 'fct', $this->_THIS_FILE_FCT );
	echo $this->build_input_hidden( 'op',  'submit' );

	echo $this->build_table_begin();
	echo $this->build_line_title( $this->get_constant('TITLE_SUBMIT_FILE') );

	echo $this->build_line_maxpixel( $has_resize ) ;

	echo $this->build_line_ele( $this->get_constant('CAP_MAXSIZE'), 
		$this->_build_ele_file_maxsize() );

	echo $this->build_line_allowed_exts( $allowed_exts ) ;
	echo $this->build_line_category() ;
	echo $this->build_line_item_title() ;

	echo $this->build_row_dhtml( $this->get_constant('ITEM_DESCRIPTION'), 'item_description' );

	echo $this->build_line_ele( $this->get_constant('CAP_FILE_SELECT'), 
		$this->_build_ele_file_file() );

	echo $this->build_line_add();

	echo $this->build_table_end();
	echo $this->build_form_end();

}

function _build_ele_file_maxsize()
{
	return $this->format_filesize( $this->_cfg_file_size );
}

function _build_ele_file_file()
{
	$options = $this->_utility_class->get_files_in_dir( 
		$this->_FILE_DIR, null, false, true, true );

	if ( !is_array($options) || !count($options) ) {
		return '---';
	}
	return $this->build_form_select( 'file', null, $options );
}

// --- class end ---
}

?>