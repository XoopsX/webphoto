<?php
// $Id: photo_form.php,v 1.3 2009/03/20 04:18:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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

	var $_checkbox_array   = array();
	var $_xoops_db_groups  = null;

	var $_editor_show = false ;
	var $_editor_js   = null ;
	var $_editor_desc = null ;

	var $_URL_SIZE          = 80;
	var $_TAGS_SIZE         = 80;
	var $_EMBED_SRC_SIZE    = 80;
	var $_SELECT_SIZE       = 1;
	var $_SELECT_PERM_SIZE  = 3;
	var $_SELECT_INFO_SIZE  = 5;
	var $_DESCRIPTION_ROWS  = 5;
	var $_DESCRIPTION_COLS  = 50;

	var $_ICON_ROTATE_URL;

	var $_ARRAY_PHOTO_ITEM = array(
		'item_datetime', 'item_place', 'item_equipment', 'item_duration',
		'item_siteurl', 'item_artist', 'item_album', 'item_label' );

	var $_ARRAY_PHOTO_TEXT = null;

	var $_TD_LEFT_WIDTH = '20%';

	var $_DETAIL_DIV_NAME = 'webphoto_detail';
	var $_GMAP_DIV_NAME   = 'webphoto_gmap_iframe';
	var $_GMAP_STYLE      = 'background-color: #ffffff; ';
	var $_GMAP_WIDTH      = '100%';
	var $_GMAP_HEIGHT     = '650px';
	var $_ICON_DIV_STYLE  = 'border: #808080 1px solid; padding: 1px; width:80%; ' ;

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

	$this->_ICON_ROTATE_URL = $this->_MODULE_URL .'/images/uploader';
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
// submit edit form
//---------------------------------------------------------
function print_form_common( $item_row, $param )
{
	$this->init_preload();

	$mode          = $param['mode'];
	$rotate        = $param['rotate'];
	$preview_name  = $param['preview_name'];
	$has_resize    = $param['has_resize'];
	$has_rotate    = $param['has_rotate'];
	$allowed_exts  = $param['allowed_exts'];
	$is_video      = isset($param['is_video']) ? (bool)$param['is_video'] : false ;

	$this->_xoops_db_groups = $this->get_cached_xoops_db_groups();

	$this->_set_checkbox( $param['checkbox_array'] );

	$is_submit    = false ;
	$is_edit      = false ;
	$show_siteurl = true ;
	$cont_row     = null ;
	$thumb_row    = null ;
	$middle_row   = null ;
	$small_row    = null ;
	$flash_row    = null ;
	$docomo_row   = null ;
	$pdf_row      = null ;
	$swf_row      = null ;

	switch ($mode)
	{
		case 'edit':
			$is_edit = true;
			$fct     = $this->_THIS_EDIT_FCT ;
			$op      = 'modify';
			break;

		case 'submit':
		default:
			$is_submit = true;
			$fct       = $this->_THIS_SUBMIT_FCT ;
			$op        = 'submit';
			break;
	}

	$this->set_row( $item_row );
	$this->_init_editor();

	if ( $is_edit ) {
		$cont_row   = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_CONT );
		$thumb_row  = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_THUMB );
		$middle_row = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_MIDDLE );
		$small_row  = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_SMALL );
		$flash_row  = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH );
		$pdf_row    = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_PDF );
		$swf_row    = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_SWF );

// for futue
//		$docomo_row = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_DOCOMO );
	}

	$this->set_td_left_width( $this->_TD_LEFT_WIDTH );

	echo $this->_build_script();

	echo $this->build_form_upload( 'uploadphoto', $this->_THIS_URL );
	echo $this->build_html_token();

	echo $this->build_input_hidden( 'op',           $op );
	echo $this->build_input_hidden( 'fct',          $fct );
	echo $this->build_input_hidden( 'fieldCounter', $this->_FILED_COUNTER_4 );
	echo $this->build_input_hidden_max_file_size();

	echo $this->build_input_hidden( 'item_id',  $item_row['item_id'] );
	echo $this->build_input_hidden( 'photo_id', $item_row['item_id'] );

	if ( $is_submit ) {
		echo $this->build_input_hidden( 'preview_name', $preview_name, true );
	}

// -- basic --
	echo $this->build_table_begin();
	echo $this->build_line_title( $this->get_constant('TITLE_PHOTOUPLOAD') );

	if ( $this->_is_module_admin ) { 
		echo $this->build_row_label( $this->get_constant('ITEM_ID'), 'item_id' );
	}

	echo $this->build_line_maxpixel( $has_resize ) ;
	echo $this->build_line_maxsize() ;
	echo $this->build_line_allowed_exts( $allowed_exts ) ;
	echo $this->build_line_category() ;
	echo $this->build_line_item_title() ;

	echo $this->build_line_ele( $this->get_constant('ITEM_EDITOR'), 
		$this->_build_ele_editor() );

	echo $this->build_line_ele(
		$this->get_constant('ITEM_DESCRIPTION'), $this->_editor_desc );

	if ( $this->_editor_show ) {
		echo $this->build_line_ele(
			$this->get_constant('CAP_DESCRIPTION_OPTION'), $this->_build_ele_description_options() );

	} else {
		$this->set_row_hidden_buffer( 'item_description_html' ) ;
		$this->set_row_hidden_buffer( 'item_description_smiley' ) ;
		$this->set_row_hidden_buffer( 'item_description_xcode' ) ;
		$this->set_row_hidden_buffer( 'item_description_image' ) ;
		$this->set_row_hidden_buffer( 'item_description_br' ) ;
	}

	if ( $this->_is_embed_type() ) {
		echo $this->build_line_ele( $this->get_constant('ITEM_EMBED_TYPE'), 
			$this->_build_ele_embed_type() );

		if ( $this->_is_embed_general_type() ) {
			$this->_print_row_text_is_in_array( 'item_siteurl' );
			echo $this->build_row_textarea( 
				$this->get_constant('ITEM_EMBED_TEXT'), 'item_embed_text' );
			$this->set_row_hidden_buffer( 'item_embed_src' ) ;
			$show_siteurl = false ;

		} else {
			echo $this->build_line_ele( $this->get_constant('ITEM_EMBED_SRC'), 
				$this->_build_ele_embed_src() );
			$this->set_row_hidden_buffer( 'item_embed_text' ) ;	
		}

	} else {
		$this->set_row_hidden_buffer( 'item_embed_type' ) ;
		$this->set_row_hidden_buffer( 'item_embed_src' ) ;
		$this->set_row_hidden_buffer( 'item_embed_text' ) ;
	}

	if ( $this->_is_upload_type() ) {
		echo $this->build_line_ele( $this->get_constant('CAP_PHOTO_SELECT'), 
			$this->_build_ele_photo_file_external( $cont_row ) );

		if ( $has_rotate ) {
			echo $this->build_line_ele( $this->get_constant('RADIO_ROTATETITLE'), 
				$this->_build_ele_rotate( $rotate ) );
		}
	}

	if ( $is_submit ) {
		echo $this->build_line_ele( $this->get_constant('CAP_DETAIL'), 
			$this->_build_ele_detail_onoff() );
	}

	echo $this->build_table_end();

// -- detail table --
	if ( $is_submit ) {
		echo $this->_build_detail_div() ;
	}

	echo $this->build_table_begin();

	if ( $this->_is_in_array( 'item_datetime' ) ) {
		echo $this->build_line_ele( $this->get_constant( 'item_datetime' ), 
			$this->_build_ele_datetime() );
	}

	$this->_print_row_text_is_in_array( 'item_place' );
	$this->_print_row_text_is_in_array( 'item_equipment' );
	$this->_print_row_text_is_in_array( 'item_duration' );
	$this->_print_row_text_is_in_array( 'item_artist' );
	$this->_print_row_text_is_in_array( 'item_album' );
	$this->_print_row_text_is_in_array( 'item_label' );

	if ( $show_siteurl ) {
		$this->_print_row_text_is_in_array( 'item_siteurl' );
	}

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; $i++ ) 
	{
		$name = 'item_text_'.$i;
		if ( is_array($this->_ARRAY_PHOTO_TEXT) && in_array( $name, $this->_ARRAY_PHOTO_TEXT) ) {
			echo $this->build_row_text( $this->get_constant( $name ), $name );
		}
	}

	echo $this->build_row_text(  $this->get_constant('ITEM_PAGE_WIDTH'),  'item_page_width' );
	echo $this->build_row_text(  $this->get_constant('ITEM_PAGE_HEIGHT'), 'item_page_height' );

	echo $this->build_line_ele(  $this->get_constant('TAGS'), 
		$this->_build_ele_tags( $param ) );

	if ( $is_edit ) {
		echo $this->build_row_textarea( $this->get_constant('ITEM_EXIF'), 
			'item_exif' );

	} else {
		$this->set_row_hidden_buffer( 'item_exif' ) ;
	}

	if ( $is_edit ) {
		echo $this->build_row_textarea( $this->get_constant('ITEM_CONTENT'), 
			'item_content' );

	} else {
		$this->set_row_hidden_buffer( 'item_content' ) ;
	}

	echo $this->build_line_ele( $this->get_constant('CAP_THUMB_SELECT'), 
		$this->_build_ele_thumb_file_external( $thumb_row ) );

	echo $this->build_line_ele( $this->get_constant('CAP_MIDDLE_SELECT'), 
		$this->_build_ele_middle_file_external( $middle_row ) );

	echo $this->build_line_ele( $this->get_constant('CAP_SMALL_SELECT'), 
		$this->_build_ele_small_file( $small_row ) );

	if ( $flash_row ) {
		echo $this->build_line_ele( $this->get_constant('FILE_KIND_4'), 
			$this->_build_ele_file( $flash_row ) );
	}

	if ( $docomo_row ) {
		echo $this->build_line_ele( $this->get_constant('FILE_KIND_5'), 
			$this->_build_ele_file( $docomo_row ) );
	}

	if ( $pdf_row ) {
		echo $this->build_line_ele( $this->get_constant('FILE_KIND_6'), 
			$this->_build_ele_file( $pdf_row ) );
	}

	if ( $swf_row ) {
		echo $this->build_line_ele( $this->get_constant('FILE_KIND_7'), 
			$this->_build_ele_file( $swf_row ) );
	}

	if ( $this->_cfg_perm_item_read > 0 ) {
		echo $this->build_line_ele(
			$this->get_constant('ITEM_PERM_READ'), $this->_build_ele_perm_read() );
	}

// for future
//	echo $this->build_line_ele(
//		$this->get_constant('ITEM_SHOWINFO'), $this->_build_ele_showinfo() );

	echo $this->build_line_ele(
		$this->get_constant('ITEM_PERM_DOWN'), $this->_build_ele_perm_down() );

	echo $this->build_line_ele(
		$this->get_constant('ITEM_CODEINFO'), $this->_build_ele_codeinfo() );

	if ( $this->_cfg_gmap_apikey ) {
		echo $this->build_row_text_id( $this->get_constant('ITEM_GMAP_LATITUDE'),
			'item_gmap_latitude',  'webphoto_gmap_latitude'  );

		echo $this->build_row_text_id( $this->get_constant('ITEM_GMAP_LONGITUDE'),
			'item_gmap_longitude', 'webphoto_gmap_longitude' );

		echo $this->build_row_text_id( $this->get_constant('ITEM_GMAP_ZOOM'),
			'item_gmap_zoom',      'webphoto_gmap_zoom'      );

		echo $this->build_line_ele(
			$this->get_constant('GMAP_ICON'), $this->_build_ele_gicon() );

		if ( $is_submit ) {
			echo $this->build_line_ele( 'google map', 
				$this->_build_ele_gmap_onoff() );
		}

	}

	echo $this->build_table_end();

// -- gmap table --
	if ( $this->_cfg_gmap_apikey ) {
	
		echo $this->build_table_begin();
		echo '<tr><td style="'. $this->_GMAP_STYLE .'" >'."\n";

		if ( $is_submit ) {
			echo $this->_build_gmap_div();
		}
		if ( $is_edit ) {
			echo $this->_build_gmap_iframe();
		}

		echo "</td></tr>\n";
		echo $this->build_table_end();
	}

// -- detail table end --
	if ( $is_submit ) {
		echo $this->build_div_end();
	}


// -- footer --
	echo $this->build_table_begin();
	echo $this->build_line_ele( '', $this->_build_ele_button( $mode ) );
	echo $this->build_table_end();

	echo $this->render_hidden_buffers();
	echo $this->build_form_end();
	echo "<br />\n";
}

function _is_upload_type()
{
	if ( $this->_is_embed_type() ) {
		return false;
	}
	return true;
}

function _is_embed_type( )
{
	$kind = $this->get_row_by_key( 'item_kind' );
	if ( $this->_kind_class->is_embed_kind( $kind ) ) {
		return true;
	}
	return false;
}

function _is_embed_general_type( )
{
	$type = $this->get_row_by_key( 'item_embed_type' );
	if ( $this->_is_embed_type() && ( $type == _C_WEBPHOTO_EMBED_NAME_GENERAL ) ) {
		return true;
	}
	return false;
}

function _is_in_array( $name )
{
	if ( is_array($this->_ARRAY_PHOTO_ITEM) && in_array( $name, $this->_ARRAY_PHOTO_ITEM) ) {
		return true;
	}
	return false;
}

function _print_row_text_is_in_array( $name )
{
	if ( $this->_is_in_array( $name ) ) {
		echo $this->build_row_text( $this->get_constant( $name ), $name );
	}
}

function _build_ele_datetime( $size=50 )
{
	$name           = 'item_datetime';
	$name_checkbox  = $name.'_checkbox';
	$value_checkbox = $this->_get_checkbox_by_name( $name_checkbox );

	$datetime = $this->mysql_datetime_to_str( $this->get_row_by_key( $name ) );
	$value    = $this->sanitize( $datetime );

	$text  = $this->build_input_checkbox_yes( $name_checkbox, $value_checkbox );
	$text .= $this->get_constant('DSC_SET_DATETIME') ."<br />\n";
	$text .= $this->build_input_text( $name, $value, $size );

	return $text;
}

function _build_cap_duration()
{
	$cap  = $this->get_constant( 'FILE_DURATION' ); 
	$cap .= ' ( ';
	$cap .= $this->get_constant( 'second' ); 
	$cap .= ' ) ';
	return $cap;
}

function _build_ele_duration( $cont_row, $size=50 )
{
	$value = 0 ;
	if ( isset($cont_row['file_duration']) ) {
		$value = $cont_row['file_duration'] ;
	}
	$ele  = $this->build_input_text( 'photo_duration', $value, $size );
	return $ele;
}

function _build_ele_rotate( $rotate )
{
	$arr = array(
		'rot0'   => $this->get_constant('RADIO_ROTATE0') ,
		'rot90'  => $this->_build_ele_img_rot( '90'  ),
		'rot180' => $this->_build_ele_img_rot( '180' ),
		'rot270' => $this->_build_ele_img_rot( '270' ),
	);

	return $this->build_form_radio( 'rotate', $rotate, array_flip($arr), ' &nbsp; ' );
}

function _build_ele_img_rot( $rot )
{
	$src = $this->_ICON_ROTATE_URL.'/icon_rotate'. $rot .'.png';
	$alt = $this->get_constant( 'RADIO_ROTATE'.$rot );
	$text = '<img src="'. $src .'" alt="'. $alt .'" title="'. $alt .'" />';
	return $text;
}

function _build_ele_photo_file_external( $cont_row )
{
	$name = 'item_external_url';

	$ele  = $this->_build_file_external( 
		$name, $this->_PHOTO_FIELD_NAME, $cont_row );

	$ele .= $this->_build_file_link( $name, null, $cont_row );

	return $ele;
}

function _build_ele_thumb_file_external( $thumb_row )
{
	$name_external = 'item_external_thumb';
	$name_icon     = 'item_icon_name';
	$value_icon    = $this->get_row_by_key( $name_icon );
	$url_icon      = $this->_ROOT_EXTS_URL .'/'. $value_icon ;

	$desc      = $this->_build_embed_thumb_desc();
	$link_file = $this->_build_file_link( $name_external, $this->_THUMB_FIELD_NAME, $thumb_row );

	$ele  = $this->_build_file_external( 
		$name_external, $this->_THUMB_FIELD_NAME, $thumb_row );

// icon name
	if ( empty($link_file) && $value_icon ) {
		$ele .= $this->get_constant('OR')." ";
		$ele .= $this->get_constant('ITEM_ICON_NAME')."<br />\n" ;
		$ele .= '<div style="'. $this->_ICON_DIV_STYLE .'">';
		$ele .= $value_icon ;
		$ele .= "</div><br />\n" ;
	}

	if ( $desc ) {
		$ele .= $desc ;
	} elseif ( empty($desc) && $this->_cfg_makethumb ) {
		$ele .= $this->get_constant('DSC_THUMB_SELECT') ."<br />\n";
	}

	if ( $link_file ) {
		$ele .= $link_file ;

	} elseif ( $value_icon ) {
		$ele .= $this->build_link_blank( $url_icon ) ;
	}

	return $ele;
}

function _build_ele_middle_file_external( $middle_row )
{
	$name = 'item_external_middle';

	$ele = $this->_build_file_external( 
		$name, $this->_MIDDLE_FIELD_NAME, $middle_row );

	if ( $this->_cfg_makethumb ) {
		$ele .= $this->get_constant('DSC_THUMB_SELECT') ."<br />\n";
	}

	$ele .= $this->_build_file_link( $name, $this->_MIDDLE_FIELD_NAME, $middle_row );

	return $ele;
}

function _build_ele_small_file( $small_row )
{
	$ele = $this->_build_file_row( $this->_SMALL_FIELD_NAME, $small_row );

	if ( $this->_cfg_makethumb ) {
		$ele .= $this->get_constant('DSC_THUMB_SELECT') ."<br />\n";
	}

	$ele .= $this->_build_file_link_row( $this->_SMALL_FIELD_NAME, $small_row );

	return $ele;
}

function _build_file_external( $name, $field, $file_row )
{
	$ele = $this->_build_file_row( $field, $file_row );

	if ( empty($url) ) {
		$value = $this->get_row_by_key( $name );

		$ele .= $this->get_constant('OR')." ";
		$ele .= $this->get_constant( $name )."<br />\n";
		$ele .= $this->build_input_text( $name, $value, $this->_URL_SIZE );
		$ele .= "<br /><br />\n";
	}

	return $ele;
}

function _build_file_row( $field, $file_row )
{
	$url  = $this->build_file_url_size( $file_row ) ;
	$ele  = $this->build_form_file( $field );
	$ele .= "<br /><br />\n";
	return $ele;
}

function _build_file_link( $name, $field, $file_row )
{
// BUG: sanitize twice
	$value = $this->get_row_by_key( $name, null, false );

	$ele = $this->_build_file_link_row( $field, $file_row );

	if ( empty($ele) && $value ) {
		$ele  = $this->build_link_blank( $value );
	}

	return $ele;
}

function _build_file_link_row( $field, $file_row )
{
	$url = $this->build_file_url_size( $file_row ) ;

	$ele = '';

	if ( $url ) {
		$ele .= $this->build_link_blank( $url );
		if ( $field ) {
			$ele .= $this->build_photo_delete_button( $field.'_delete' );
		}
	}

	return $ele;
}

function _build_ele_file( $file_row )
{
	$url = $this->build_file_url_size( $file_row ) ;
	$ele = '';
	if ( $url ) {
		$ele = $this->build_link_blank( $url );
	}
	return $ele;
}

function _build_ele_tags( $param )
{
	$value = $this->_tag_class->tag_name_array_to_str( $param['tag_name_array'] );
	$text  = $this->build_input_text( 'tags', $value, $this->_TAGS_SIZE );
	$text .= "<br />\n";
	$text .= $this->get_constant('DSC_TAG_DIVID') ;
	return $text;
}

function _build_input_checkbox_by_post( $name )
{
	return $this->build_input_checkbox_yes( $name, $this->_get_checkbox_by_name( $name ) );
}

function _set_checkbox( $val )
{
	$this->_checkbox_array = $val;
}

function _get_checkbox_by_name( $name )
{
	if ( isset( $this->_checkbox_array[ $name ] ) ) {
		 return $this->_checkbox_array[ $name ];
	}
	return null;
}

function _build_ele_button( $mode )
{
	$is_submit = false;
	$is_edit   = false;

	switch ($mode)
	{
		case 'admin_submit':
			$submit    = _ADD;
			break;

		case 'edit':
		case 'admin_modify':
			$is_edit = true;
			$submit  = _EDIT;
			break;

		case 'submit':
		default:
			$is_submit = true;
			$submit    = _ADD;
			break;
	}

	$button  = $this->build_input_submit( 'submit',  $submit ).' ';

	if ( $is_submit ) {
		$button .= $this->build_input_submit( 'preview', _PREVIEW ).' ';
	}

	$button .= $this->build_input_reset( 'reset', _CANCEL ).' ';

	if ( $is_edit ) {
		$button .= $this->build_photo_delete_button( 'conf_delete' );
	}

	return $button;
}

function _build_ele_gicon()
{
	$name    = 'item_gicon_id' ;
	$value   = $this->get_row_by_key( $name );
	$options = $this->_gicon_handler->get_sel_options();
	return $this->build_form_select( $name,  $value, $options, $this->_SELECT_SIZE );
}

function _build_ele_kind()
{
	$name    = 'item_kind' ;
	$value   = $this->get_row_by_key( $name ) ; 
	$options = $this->_item_handler->get_kind_options();
	return $this->build_form_select( $name, $value, $options, $this->_SELECT_SIZE );
}

function _build_ele_displaytype()
{
	$name    = 'item_displaytype' ;
	$value   = $this->get_row_by_key( $name ) ; 
	$options = $this->_item_handler->get_displaytype_options();
	return $this->build_form_select( $name, $value, $options, $this->_SELECT_SIZE );
}

function _build_ele_onclick()
{
	$name    = 'item_onclick' ;
	$value   = $this->get_row_by_key( $name ) ; 
	$options = $this->_item_handler->get_onclick_options();
	return $this->build_form_select( $name, $value, $options, $this->_SELECT_SIZE );
}

function _build_ele_player()
{
	$name  = 'item_player_id';
	$value = $this->get_row_by_key( 'item_player_id' );
	return $this->_player_handler->build_form_selbox( $name, $value, $this->_SELECT_SIZE );
}

function _build_ele_perm_read()
{
	return $this->build_ele_group_perms_by_key( 'item_perm_read' );
}

function _build_ele_perm_down()
{
	return $this->build_ele_group_perms_by_key( 'item_perm_down' );
}

function _build_ele_showinfo()
{
	$name    = 'item_showinfo' ;
	$values  = $this->_item_handler->get_showinfo_array( $this->get_row() );
	$options = $this->_item_handler->get_showinfo_options();
	return $this->build_form_select_multiple(
		$name, $values, $options, $this->_SELECT_INFO_SIZE );
}

function _build_ele_codeinfo()
{
	$name    = 'item_codeinfo' ;
	$values  = $this->_item_handler->get_codeinfo_array( $this->get_row() );
	$options = $this->_item_handler->get_codeinfo_options();
	return $this->build_form_select_multiple(
		$name, $values, $options, $this->_SELECT_INFO_SIZE );
}

//---------------------------------------------------------
// embed
//---------------------------------------------------------
function _build_ele_embed_type()
{
	$val  = $this->get_item_embed_type( true );
	$str  = $val.' ';
	$str .= $this->build_input_hidden( 'item_embed_type', $val );
	return $str;
}

function _build_ele_embed_src()
{
	$value_src  = $this->get_row_by_key( 'item_embed_src' );
	$value_type = $this->get_item_embed_type( true );

	$text  = $this->build_input_text( 'item_embed_src', $value_src, $this->_EMBED_SRC_SIZE );

	if ( $value_type ) {
		$text .= "<br />\n";
		$text .= $this->_embed_class->build_src_desc( $value_type, $value_src );
	}

	return $text;
}

function _build_embed_thumb_desc()
{
	$desc = null;
	$type = $this->get_item_embed_type( false );
	if ( $type ) {
		$thumb = $this->_embed_class->build_thumb( $type, 'example' );
		if ( $thumb ) {
			$desc = $this->get_constant('EMBED_THUMB') ."<br />\n";
		}
	}
	return $desc ;
}

//---------------------------------------------------------
// editor
//---------------------------------------------------------
function _init_editor()
{
	$name   = 'item_description';
	$value  = $this->get_row_by_key( $name );
	$editor = $this->get_item_editor();
	$arr    = $this->_editor_class->init_form( 
		$editor, $name, $name, $value, $this->_DESCRIPTION_ROWS, $this->_DESCRIPTION_COLS );

	if ( is_array($arr) ) {
		$this->_editor_show = $arr['show'];
		$this->_editor_js   = $arr['js'];
		$this->_editor_desc = $arr['desc'];
	}
}

function _build_ele_editor()
{
	$val  = $this->get_item_editor();
	$str  = $val.' ';
	$str .= $this->build_input_hidden( 'item_editor', $val );
	return $str;
}

function _build_ele_description_options()
{
	$str  = $this->_build_ele_description_option_single( 
		'item_description_html', $this->get_constant('CAP_HTML' ) );
	$str .= $this->_build_ele_description_option_single( 
		'item_description_smiley', $this->get_constant('CAP_SMILEY' ) );
	$str .= $this->_build_ele_description_option_single( 
		'item_description_xcode', $this->get_constant('CAP_XCODE' ) );
	$str .= $this->_build_ele_description_option_single( 
		'item_description_image', $this->get_constant('CAP_IMAGE' ) );
	$str .= $this->_build_ele_description_option_single( 
		'item_description_br', $this->get_constant('CAP_BR' ) );
	return $str;
}

function _build_ele_description_option_single( $name, $cap )
{
	$value = $this->get_row_by_key( $name );
	$str   = $this->build_input_checkbox_yes( $name, $value );
	$str  .= ' ' ;
	$str  .= $cap ;
	$str  .= "<br /\n>";
	return $str;
}

//---------------------------------------------------------
// java script
//---------------------------------------------------------
function _build_ele_detail_onoff()
{
	$str = '<input type="checkbox" id="webphoto_form_detail_onoff" onclick="webphoto_detail_disp_onoff(this)" />'."\n";
	$str .= $this->get_constant('CAP_DETAIL_ONOFF');
	return $str;
}

function _build_detail_div()
{
	$str = '<div id="'. $this->_DETAIL_DIV_NAME .'" style="display:none;">'."\n";
	return $str;
}

function _build_ele_gmap_onoff()
{
	$str = '<input type="checkbox" id="webphoto_form_gmap_onoff" checked="checked" onclick="webphoto_gmap_disp_onoff(this)" />'."\n";
	$str .= $this->get_constant('CAP_DETAIL_ONOFF');
	return $str;
}

function _build_gmap_div()
{
	$str = '<div id="'. $this->_GMAP_DIV_NAME .'"></div>';
	return $str;
}

function _build_script()
{
	$js  = $this->build_js_check_all();
	$js .= $this->_build_detail_js();
	$js .= $this->_build_iframe_js();
	$str  = $this->build_js_envelop( $js );
	$str .= $this->_editor_js ;
	return $str;
}

function _build_detail_js()
{
	$DIV_NAME = $this->_DETAIL_DIV_NAME;

	$gmap_disp_on = '' ;
	if ( $this->_cfg_gmap_apikey ) {
		$gmap_disp_on = 'webphoto_gmap_disp_on();' ;
	}

	$text = <<< END_OF_TEXT
/* edit form */
function webphoto_detail_disp_onoff( onoff ) 
{
	if ( onoff.checked ) {
		document.getElementById("$DIV_NAME").style.display = "block";
		$gmap_disp_on
	} else{
		document.getElementById("$DIV_NAME").style.display = "none";
	}
}
END_OF_TEXT;

	return $text."\n";
}

function _build_iframe_js()
{
	$DIV_NAME = $this->_GMAP_DIV_NAME;
	$iframe   = $this->_build_gmap_iframe();

	$text = <<< END_OF_TEXT
/* google map */
function webphoto_gmap_disp_onoff( onoff ) 
{
	if ( onoff.checked ) {
		webphoto_gmap_disp_on();
	} else{
		document.getElementById("$DIV_NAME").innerHTML = '';
	}
}
function webphoto_gmap_disp_on() 
{
	document.getElementById("$DIV_NAME").innerHTML = '$iframe';
}

END_OF_TEXT;

	return $text."\n";
}

function _build_gmap_iframe()
{
	$item_id = $this->get_row_by_key( 'item_id' );

	$src = $this->_MODULE_URL .'/index.php?fct=gmap_location';
	if ( $item_id ) {
		$src .= '&amp;photo_id='.intval($item_id);
	}

	$str  = '<iframe src="'. $src .'" width="'. $this->_GMAP_WIDTH .'" height="'. $this->_GMAP_HEIGHT .'" frameborder="0" scrolling="yes" >' ;
	$str .= $this->get_constant('IFRAME_NOT_SUPPORT') ;
	$str .= '</iframe>';
	return $str;
}

// --- class end ---
}

?>