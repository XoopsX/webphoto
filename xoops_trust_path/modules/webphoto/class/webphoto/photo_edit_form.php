<?php
// $Id: photo_edit_form.php,v 1.14 2008/11/19 10:26:00 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
// class webphoto_photo_edit_form
//=========================================================
class webphoto_photo_edit_form extends webphoto_form_this
{
	var $_gicon_handler;
	var $_player_handler;
	var $_perm_class;
	var $_tag_class;
	var $_embed_class;
	var $_kind_class;

	var $_cfg_gmap_apikey ;
	var $_cfg_width ;
	var $_cfg_height ;
	var $_cfg_fsize ;
	var $_cfg_makethumb ;
	var $_cfg_file_size ;

	var $_has_deletable ;

	var $_param_type         = null;
	var $_checkbox_array     = array();
	var $_xoops_db_groups    = null;

	var $_URL_SIZE          = 80;
	var $_TAGS_SIZE         = 80;
	var $_EMBED_SRC_SIZE    = 80;
	var $_SELECT_PERM_SIZE  = 3;
	var $_SELECT_INFO_SIZE  = 5;

	var $_ICON_ROTATE_URL;

	var $_ARRAY_PHOTO_ITEM = array(
		'item_datetime', 'item_place', 'item_equipment', 'item_duration',
		'item_siteurl', 'item_artist', 'item_album', 'item_label' );

	var $_ARRAY_PHOTO_TEXT = null;
	var $_FLAG_PERM = true;

	var $_TD_LEFT_WIDTH = '20%';
	var $_EMBED_TYPE_DEFAULT = 'youtube';
	var $_VIDEO_THUMB_WIDTH = 120;
	var $_VIDEO_ICON_WIDTH  = 64;
	var $_FLASH_EXT         = _C_WEBPHOTO_VIDEO_FLASH_EXT ;

	var $_PHOTO_FIELD_NAME  = _C_WEBPHOTO_UPLOAD_FIELD_PHOTO ;
	var $_THUMB_FIELD_NAME  = _C_WEBPHOTO_UPLOAD_FIELD_THUMB ;
	var $_MIDDLE_FIELD_NAME = _C_WEBPHOTO_UPLOAD_FIELD_MIDDLE ;

	var $_DETAIL_DIV_NAME = 'webphoto_detail';
	var $_GMAP_DIV_NAME   = 'webphoto_gmap_iframe';
	var $_GMAP_STYLE      = 'background-color: #ffffff; ';
	var $_GMAP_WIDTH      = '100%';
	var $_GMAP_HEIGHT     = '650px';

	var $_THIS_SUBMIT_FCT       = 'submit';
	var $_THIS_FILE_FCT         = 'submit_file';
	var $_THIS_IMAGEMANEGER_FCT = 'submit_imagemanager';
	var $_THIS_EDIT_FCT         = 'edit';
	var $_THIS_ADMIN_FCT        = 'item_manager';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_edit_form( $dirname, $trust_dirname )
{
	$this->webphoto_form_this( $dirname, $trust_dirname );

	$this->_embed_class    =& webphoto_embed::getInstance( $dirname, $trust_dirname );
	$this->_gicon_handler  =& webphoto_gicon_handler::getInstance( $dirname );
	$this->_player_handler =& webphoto_player_handler::getInstance( $dirname );
	$this->_perm_class     =& webphoto_permission::getInstance( $dirname );
	$this->_kind_class     =& webphoto_kind::getInstance();

	$this->_tag_class =& webphoto_tag::getInstance( $dirname );
	$this->_tag_class->set_is_japanese( $this->_is_japanese );

	$this->_cfg_gmap_apikey = $this->_config_class->get_by_name( 'gmap_apikey' );
	$this->_cfg_width       = $this->_config_class->get_by_name( 'width' );
	$this->_cfg_height      = $this->_config_class->get_by_name( 'height' );
	$this->_cfg_fsize       = $this->_config_class->get_by_name( 'fsize' );
	$this->_cfg_makethumb   = $this->_config_class->get_by_name( 'makethumb' );
	$this->_cfg_file_size   = $this->_config_class->get_by_name( 'file_size' );

	$this->_has_deletable   = $this->_perm_class->has_deletable();

	$this->_ICON_ROTATE_URL = $this->_MODULE_URL .'/images/uploader';

	$this->_LIBS_URL   = $this->_MODULE_URL .'/libs';

	$this->init_preload();
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo_edit_form( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// preload
//---------------------------------------------------------
function init_preload()
{
	$this->preload_init();
	$this->preload_constant();
}

//---------------------------------------------------------
// submit edit form
//---------------------------------------------------------
function print_form_common( $item_row, $param )
{
	$mode          = $param['mode'];
	$preview_name  = $param['preview_name'];
	$has_resize    = $param['has_resize'];
	$has_rotate    = $param['has_rotate'];
	$allowed_exts  = $param['allowed_exts'];
	$type          = isset($param['type'])     ? $param['type']           : null ;
	$is_video      = isset($param['is_video']) ? (bool)$param['is_video'] : false ;

	$this->_param_type = $type;
	$this->_xoops_db_groups = $this->get_cached_xoops_db_groups();

	$this->_set_checkbox( $param['checkbox_array'] );

	$is_submit    = false ;
	$is_edit      = false ;
	$show_siteurl = true;
	$cont_row     = null ;
	$thumb_row    = null ;
	$middle_row   = null ;

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

	if ( $is_edit ) {
		$cont_row   = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_CONT );
		$thumb_row  = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_THUMB );
		$middle_row = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_MIDDLE );
	}

	$this->set_td_left_width( $this->_TD_LEFT_WIDTH );

	echo $this->_build_script();

	echo $this->build_form_upload( 'uploadphoto', $this->_THIS_URL );
	echo $this->build_html_token();

	echo $this->build_input_hidden( 'op',           $op );
	echo $this->build_input_hidden( 'fct',          $fct );
	echo $this->build_input_hidden( 'type',         $type );
	echo $this->build_input_hidden( 'fieldCounter', $this->_FILED_COUNTER_2 );
	echo $this->_build_input_hidden_max_file_size();

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

	echo $this->build_line_ele( $this->get_constant('CAP_MAXPIXEL'), 
		$this->_build_ele_maxpixel( $has_resize ) );

	echo $this->build_line_ele( $this->get_constant('CAP_MAXSIZE'), 
		$this->_build_ele_maxsize() );

	echo $this->build_line_ele( $this->get_constant('CAP_ALLOWED_EXTS'), 
		$this->_build_ele_allowed_exts( $allowed_exts ) );

	echo $this->build_line_ele( 
		$this->get_constant('CATEGORY'), $this->_build_ele_category() );

	echo $this->build_line_ele(
		$this->get_constant('ITEM_TITLE'), $this->_build_ele_title() );

	echo $this->build_row_dhtml( $this->get_constant('ITEM_DESCRIPTION'), 'item_description' );

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
				$this->_build_ele_rotate() );
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

	echo $this->build_line_ele( $this->get_constant('CAP_THUMB_SELECT'), 
		$this->_build_ele_thumb_file_external( $thumb_row ) );

	echo $this->build_line_ele( $this->get_constant('CAP_MIDDLE_SELECT'), 
		$this->_build_ele_middle_file_external( $middle_row ) );

// for future
//	echo $this->build_line_ele(
//		$this->get_constant('ITEM_PERM_READ'), $this->_build_ele_perm_read() );
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
	$type = $this->get_row_by_key( 'item_embed_type' );
	if ( $this->_param_type == 'embed' ) {
		return true;
	}
	if ( $type ) {
		return true;
	}
	return false;
}

function _is_embed_general_type( )
{
	$type = $this->get_row_by_key( 'item_embed_type' );
	if ( $this->_is_embed_type() && ( $type == 'general' ) ) {
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

function _build_ele_maxpixel( $has_resize )
{
	$text = $this->_cfg_width .' x '. $this->_cfg_height ."<br />\n" ;

	if ( $has_resize ) {
		$text .= $this->get_constant('DSC_PIXCEL_RESIZE');
	} else {
		$text .= $this->get_constant('DSC_PIXCEL_REJECT');
	}

	return $text;
}

function _build_ele_maxsize()
{
	$size_desc = '';
	if( ! ini_get( 'file_uploads' ) ) {
		$size_desc = ' &nbsp; <b>"file_uploads" off</b>';
	}

	$text  = $this->format_filesize( $this->_cfg_fsize );
	$text .= $size_desc;

	return $text;
}

function _build_ele_allowed_exts( $allowed_exts )
{
	$text = implode( ' ', $allowed_exts );
	return $text;
}

function _build_ele_title( $size=50 )
{
	$value = $this->get_row_by_key( 'item_title' );
	$ele  = $this->build_input_text( 'item_title', $value, $size );
	$ele .= "<br />\n";
	$ele .= $this->get_constant('DSC_TITLE_BLANK');
	return $ele;
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

function _build_ele_rotate()
{
	$arr = array(
		'rot0'   => $this->get_constant('RADIO_ROTATE0') ,
		'rot90'  => $this->_build_ele_img_rot( '90'  ),
		'rot180' => $this->_build_ele_img_rot( '180' ),
		'rot270' => $this->_build_ele_img_rot( '270' ),
	);

	return $this->build_form_radio( 'rotate', 'rot0', array_flip($arr), ' &nbsp; ' );
}

function _build_ele_img_rot( $rot )
{
	$src = $this->_ICON_ROTATE_URL.'/icon_rotate'. $rot .'.png';
	$alt = $this->get_constant( 'RADIO_ROTATE'.$rot );
	$text = '<img src="'. $src .'" alt="'. $alt .'" title="'. $alt .'" />';
	return $text;
}

function _build_input_hidden_max_file_size()
{
	return $this->build_input_hidden( 'max_file_size', $this->_cfg_fsize );
}

function _build_ele_photo_file( $cont_row )
{
	$url = '' ;
	if ( isset($cont_row['file_url']) ) {
		$url = $cont_row['file_url'] ;
	}

	$ele  = $this->build_form_file( $this->_PHOTO_FIELD_NAME );
	$ele .= "<br />\n";

	if ( $url ) {
		$ele .= $this->_build_link( $url );
	}

	return $ele;
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
	$name = 'item_external_thumb';

	$ele = $this->_build_file_external( 
		$name, $this->_THUMB_FIELD_NAME, $thumb_row );

	$desc = null;
	$value_type = $this->_get_embed_type( false );
	if ( $value_type ) {
		$thumb = $this->_embed_class->build_thumb( $value_type, 'example' );
		if ( $thumb ) {
			$desc = $this->get_constant('EMBED_THUMB') ."<br />\n";
		}
	}

	if ( $desc ) {
		$ele .= $desc ;
	} elseif ( empty($desc) && $this->_cfg_makethumb ) {
		$ele .= $this->get_constant('DSC_THUMB_SELECT') ."<br />\n";
	}

	$ele .= $this->_build_file_link( $name, $this->_THUMB_FIELD_NAME, $thumb_row );

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

function _build_file_external( $name, $field, $row )
{
	$url = '' ;
	if ( isset($row['file_url']) ) {
		$url = $row['file_url'] ;
	}

	$value = $this->get_row_by_key( $name );

	$ele  = '';
	$ele .= $this->build_form_file( $field );
	$ele .= "<br /><br />\n";

	if ( empty($url) ) {
		$ele .= $this->get_constant('OR')." ";
		$ele .= $this->get_constant( $name )."<br />\n";
		$ele .= $this->build_input_text( $name, $value, $this->_URL_SIZE );
		$ele .= "<br /><br />\n";
	}

	return $ele;
}

function _build_file_link( $name, $field, $row )
{
	$url = '' ;
	if ( isset($row['file_url']) ) {
		$url = $row['file_url'] ;
	}

// BUG: sanitize twice
	$value = $this->get_row_by_key( $name, null, false );

	$ele = '';

	if ( $url ) {
		$ele .= $this->_build_link( $url );
		if ( $field ) {
			$ele .= $this->_build_delete_button( $field.'_delete' );
		}

	} elseif ( $value ) {
		$ele  = $this->_build_link( $value );
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
		$button .= $this->_build_delete_button( 'conf_delete' );
	}

	return $button;
}

function _build_delete_button( $name )
{
	if ( $this->_has_deletable ) {
		return $this->build_input_submit( $name, _DELETE );
	}
	return null;
}

function _build_ele_category()
{
	return $this->_cat_handler->build_selbox_with_perm_post(
		$this->get_row_by_key( 'item_cat_id' ) , 'item_cat_id', $this->_xoops_groups );
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

function _build_ele_embed_src()
{
	$value_src  = $this->get_row_by_key( 'item_embed_src' );
	$value_type = $this->_get_embed_type( true );

	$text  = $this->build_input_text( 'item_embed_src', $value_src, $this->_EMBED_SRC_SIZE );

	if ( $value_type ) {
		$text .= "<br />\n";
		$text .= $this->_embed_class->build_src_desc( $value_type, $value_src );
	}

	return $text;
}

function _build_ele_perm_read()
{
	$name    = 'item_perm_read' ;
	$values  = $this->_build_perm( $name );
	return $this->build_form_select_multiple(
		$name, $values, $this->_xoops_db_groups, $this->_SELECT_PERM_SIZE );
}

function _build_ele_perm_down()
{
	$name    = 'item_perm_down' ;
	$values  = $this->_build_perm( $name );
	return $this->build_form_select_multiple(
		$name, $values, $this->_xoops_db_groups, $this->_SELECT_PERM_SIZE );
}

function _build_perm( $name )
{
	$value = $this->get_row_by_key( $name, null, false );
	if ( $value == _C_WEBPHOTO_PERM_ALLOW_ALL ) {
		return array_keys( $this->_xoops_db_groups ) ;
	}
	return $this->_item_handler->get_perm_array( $value );
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

function _build_link( $url )
{
	if ( empty($url) ) {
		return '';
	}

	$url_s = $this->sanitize( $url );
	$str   = '<a href="'. $url_s .'" target="_blank">'. $url_s .'</a>'."<br />\n";
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
	$js  = $this->_build_detail_js();
	$js .= $this->_build_iframe_js();
	return $this->_build_envelop_script( $js );
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

function _build_envelop_script( $content )
{
	$text = <<< END_OF_TEXT
<script type="text/javascript">
//<![CDATA[
$content
//]]>
</script>
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

//---------------------------------------------------------
// embed
//---------------------------------------------------------
function print_form_embed( $mode, $row )
{
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

	echo $this->build_table_begin();
	echo $this->build_line_title( $this->get_constant('EMBED_ADD') );

	echo $this->build_line_ele( $this->get_constant('ITEM_EMBED_TYPE'), 
		$this->_build_ele_embed_type() );

	echo $this->build_line_ele( '', 
		$this->build_input_submit( 'submit', $this->get_constant('BUTTON_SELECT') ) );

	echo $this->build_table_end();
	echo $this->build_form_end();
	echo "<br />\n";
}

function _build_ele_embed_type()
{
	$value   = $this->_get_embed_type( true );
	$options = $this->_embed_class->build_type_options( $this->_is_module_admin );

	return $this->build_form_select( 'item_embed_type', $value, $options, 1 );
}

function _get_embed_type( $flag )
{
	$value = $this->get_row_by_key( 'item_embed_type' );
	if ( $flag && empty($value) ) {
		$value = $this->_EMBED_TYPE_DEFAULT;
	}
	return $value;
}

//---------------------------------------------------------
// imagemanager
//---------------------------------------------------------
function print_form_imagemanager( $row, $param )
{
	$has_resize    = $param['has_resize'];
	$allowed_exts  = $param['allowed_exts'];

	$this->set_row( $row );

	echo $this->build_form_upload( 'uploadphoto', $this->_THIS_URL );
	echo $this->build_html_token();

	echo $this->build_input_hidden( 'op',           'submit' );
	echo $this->build_input_hidden( 'fct',          $this->_THIS_IMAGEMANEGER_FCT );
	echo $this->build_input_hidden( 'fieldCounter', $this->_FILED_COUNTER_1 );

	echo $this->_build_input_hidden_max_file_size();

	echo $this->build_table_begin();
	echo $this->build_line_title( $this->get_constant('TITLE_PHOTOUPLOAD') );

	echo $this->build_line_ele( $this->get_constant('CAP_MAXPIXEL'), 
		$this->_build_ele_maxpixel( $has_resize ) );
	echo $this->build_line_ele( $this->get_constant('CAP_MAXSIZE'), 
		$this->_build_ele_maxsize() );
	echo $this->build_line_ele( $this->get_constant('CAP_ALLOWED_EXTS'), 
		$this->_build_ele_allowed_exts( $allowed_exts ) );
	echo $this->build_line_ele( $this->get_constant('CATEGORY') , 
		$this->_build_ele_category() );
	echo $this->build_line_ele( $this->get_constant('ITEM_TITLE'), 
		$this->_build_ele_title() );
	echo $this->build_line_ele( $this->get_constant('CAP_PHOTO_SELECT'), 
		$this->_build_ele_photo_file( null ) );

	echo $this->build_line_ele( '', $this->build_input_submit( 'submit', _ADD ) );

	echo $this->build_table_end();
	echo $this->build_form_end();
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

	echo $this->build_form_confirm( $hiddens, $url, $this->get_constant('CONFIRM_PHOTODEL'), _YES, _NO );

}

//---------------------------------------------------------
// video thumb
//---------------------------------------------------------
function print_form_video_thumb( $mode, $row )
{
	$video_class =& webphoto_video::getInstance( $this->_DIRNAME );

	$item_id = $row['item_id'];

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

	$max = $video_class->get_thumb_plural_max();

	echo $this->build_form_begin();
	echo $this->build_input_hidden( 'op',       'video' );
	echo $this->build_input_hidden( 'fct',      $fct );
	echo $this->build_input_hidden( 'mode',     $mode );
	echo $this->build_input_hidden( 'item_id',  $item_id );
	echo $this->build_input_hidden( 'photo_id', $item_id );

	echo $this->build_table_begin();
	echo $this->build_line_title( $this->get_constant('TITLE_VIDEO_THUMB_SEL'), $max );
	echo "<tr>\n";

	for ( $i=0; $i<=$max; $i++ ) 
	{
		$width = $this->_VIDEO_THUMB_WIDTH ;
		if ( $i == 0 ) {
			$width = $this->_VIDEO_ICON_WIDTH ;
		}

	 	$name = $video_class->build_thumb_name( $item_id, $i, true );
		$file = $this->_TMP_DIR .'/'. $name ;

		if ( is_file($file) ) {
			$name_encode = rawurlencode( $name );
			$url = $this->_MODULE_URL.'/index.php?fct=image_tmp&amp;name='. $name_encode ;
			echo '<td align="center" class="odd">';
			echo '<img src="'. $url .'" width="'. $width .'"><br />';
			echo '<input type="radio" name="name" value="'. $name_encode .'" />';
			echo "</td>\n";
		}
	}

	echo "</tr>\n";
	echo '<tr><td align="center" class="head" colspan="'. ($max + 1) .'">';
	echo '<input type="submit" name="submit" value="'.$this->get_constant('BUTTON_SELECT').'" />';
	echo "</td></tr>\n";

	echo $this->build_table_end();
	echo $this->build_form_end();

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
	$ele .= ' '.$this->get_constant('CAP_REDO_FLASH') ;

	if ( $url ) {
		$ele .= "<br />\n";
		$ele .= $this->_build_link( $url );
		$ele .= $this->_build_delete_button( 'flash_delete' );
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

	echo $this->build_line_ele( $this->get_constant('CAP_MAXPIXEL'), 
		$this->_build_ele_maxpixel( $has_resize ) );

	echo $this->build_line_ele( $this->get_constant('CAP_MAXSIZE'), 
		$this->_build_ele_file_maxsize() );

	echo $this->build_line_ele( $this->get_constant('CAP_ALLOWED_EXTS'), 
		$this->_build_ele_allowed_exts( $allowed_exts ) );

	echo $this->build_line_ele( 
		$this->get_constant('CATEGORY'), $this->_build_ele_category() );

	echo $this->build_line_ele(
		$this->get_constant('ITEM_TITLE'), $this->_build_ele_title() );

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
	$options = $this->_utility_class->get_files_in_dir( $this->_FILE_DIR, null, false, true, true );
	if ( !is_array($options) || !count($options) ) {
		return '---';
	}
	return $this->build_form_select( 'file', null, $options );
}


// --- class end ---
}

?>