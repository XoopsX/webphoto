<?php
// $Id: item_form.php,v 1.2 2008/10/30 13:02:36 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_item_form
//=========================================================
class webphoto_admin_item_form extends webphoto_photo_edit_form
{
	var $_sort_class ;

	var $_sort_array = null ;

	var $_THIS_FCT = 'item_manager' ;
	var $_THIS_URL ;
	var $_URL_ADMIN_INDEX ;

	var $_PLAYLIST_FEED_SIZE = 80;
	var $_PLAYLIST_TYPE_DEFAULT = _C_WEBPHOTO_PLAYLIST_TYPE_AUDIO ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_item_form( $dirname, $trust_dirname )
{
	$this->webphoto_photo_edit_form( $dirname, $trust_dirname );

	$this->_sort_class =& webphoto_photo_sort::getInstance( $dirname, $trust_dirname );
	$this->_sort_array = $this->_sort_class->photo_sort_array_admin();
	$this->_sort_class->set_photo_sort_array( $this->_sort_array );

	$this->_THIS_URL = $this->_MODULE_URL .'/admin/index.php?fct='.$this->_THIS_FCT ;

	$this->_URL_ADMIN_INDEX = $this->_MODULE_URL .'/admin/index.php';

	$this->init_preload();
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_item_form( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// submit edit form
//---------------------------------------------------------
function print_form_admin( $row, $param )
{
	$mode          = $param['mode'];
	$preview_name  = $param['preview_name'];
	$has_resize    = $param['has_resize'];
	$has_rotate    = $param['has_rotate'];
	$allowed_exts  = $param['allowed_exts'];
	$type          = isset($param['type']) ? $param['type'] : null ;

	$this->_param_type = $type;

	$this->_set_checkbox( $param['checkbox_array'] );

	$is_submit = false ;
	$is_edit   = false ;
	$cont_row  = null ;
	$thumb_row = null ;

	switch ($mode)
	{
		case 'admin_modify':
			$is_edit = true;
			$op      = 'modify';
			break;

		case 'admin_submit':
		default:
			$is_submit = true;
			$op        = 'submit';
			break;
	}

	$cfg_gmap_apikey = $this->_config_class->get_by_name( 'gmap_apikey' );

	$this->set_row( $row );

	if ( $cfg_gmap_apikey ) {
		echo $this->_build_gmap_iframe();
	}

	echo $this->build_form_upload( 'uploadphoto', $this->_THIS_URL );
	echo $this->build_html_token();

	echo $this->build_input_hidden( 'op',           $op );
	echo $this->build_input_hidden( 'fct',          $this->_THIS_FCT );
	echo $this->build_input_hidden( 'type',         $type );
	echo $this->build_input_hidden( 'fieldCounter', $this->_FILED_COUNTER_2 );
	echo $this->_build_input_hidden_max_file_size();

	echo $this->build_row_hidden( 'item_id' );
	echo $this->build_row_hidden( 'item_flashvar_id' );
	echo $this->build_input_hidden( 'photo_id', $row['item_id'] );

	if ( $is_submit ) {
		echo $this->build_input_hidden( 'preview_name', $preview_name, true );
	}

	if ( $is_edit ) {
		$cont_row  = $this->_file_handler->get_row_by_id( $row['item_file_id_1'] );
		$thumb_row = $this->_file_handler->get_row_by_id( $row['item_file_id_2'] );
	}

	echo $this->build_table_begin();
	echo $this->build_line_title( $this->get_constant('TITLE_PHOTOUPLOAD') );

	echo $this->build_row_label( $this->get_constant('ITEM_ID'), 'item_id' );
	echo $this->build_row_label( $this->get_constant('ITEM_FLASHVAR_ID'), 'item_flashvar_id' );

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

	if ( $this->_is_in_array( 'item_datetime' ) ) {
		echo $this->build_line_ele( $this->get_constant( 'item_datetime' ), 
			$this->_build_ele_datetime() );
	}

	$this->_print_row_text_is_in_array( 'item_place' );
	$this->_print_row_text_is_in_array( 'item_equipment' );
	$this->_print_row_text_is_in_array( 'item_duration' );
	$this->_print_row_text_is_in_array( 'item_siteurl' );
	$this->_print_row_text_is_in_array( 'item_artist' );
	$this->_print_row_text_is_in_array( 'item_album' );
	$this->_print_row_text_is_in_array( 'item_label' );

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; $i++ ) 
	{
		$name = 'item_text_'.$i;
		if ( is_array($this->_ARRAY_PHOTO_TEXT) && in_array( $name, $this->_ARRAY_PHOTO_TEXT) ) {
			echo $this->build_row_text( $this->get_constant( $name ), $name );
		}
	}

	echo $this->build_row_dhtml( $this->get_constant('ITEM_DESCRIPTION'), 'item_description' );

	if ( $is_edit ) {
		echo $this->build_row_textarea( $this->get_constant('ITEM_EXIF'), 
			'item_exif' );

	} else {
		$this->set_row_hidden_buffer( 'item_exif' ) ;
	}

	echo $this->build_line_ele(  $this->get_constant('TAGS'), 
		$this->_build_ele_tags( $param ) );

	if ( $cfg_gmap_apikey ) {
		echo $this->build_row_text_id( $this->get_constant('ITEM_GMAP_LATITUDE'),
			'item_gmap_latitude',  'webphoto_gmap_latitude'  );

		echo $this->build_row_text_id( $this->get_constant('ITEM_GMAP_LONGITUDE'),
			'item_gmap_longitude', 'webphoto_gmap_longitude' );

		echo $this->build_row_text_id( $this->get_constant('ITEM_GMAP_ZOOM'),
			'item_gmap_zoom',      'webphoto_gmap_zoom'      );

		echo $this->build_line_ele(
			$this->get_constant('GMAP_ICON'), $this->_build_ele_gicon() );
	}

	if ( $is_edit || $this->_is_playlist_type() ) {
		echo $this->build_line_ele( $this->get_constant('ITEM_KIND'),
			$this->_build_ele_kind() ) ;

	} else {
		$this->set_row_hidden_buffer( 'item_kind' ) ;
	}

	if ( $is_edit ) {
		echo $this->build_line_ele( $this->get_constant('ITEM_DISPLAYTYPE'),
			$this->_build_ele_displaytype() ) ;

		echo $this->build_line_ele( $this->get_constant('ITEM_ONCLICK'),
			$this->_build_ele_onclick() ) ;

		echo $this->build_line_ele( $this->get_constant('PLAYER'),
			$this->_build_ele_player() ) ;

	} else {
		$this->set_row_hidden_buffer( 'item_displaytype' ) ;
		$this->set_row_hidden_buffer( 'item_onclick' ) ;
		$this->set_row_hidden_buffer( 'item_player_id' ) ;
	}

	if ( $this->_is_embed_type() ) {
		echo $this->build_line_ele( $this->get_constant('ITEM_EMBED_TYPE'), 
			$this->_build_ele_embed_type() );

		echo $this->build_line_ele( $this->get_constant('ITEM_EMBED_SRC'), 
			$this->_build_ele_embed_src() );

	} else {
		$this->set_row_hidden_buffer( 'item_embed_type' ) ;
		$this->set_row_hidden_buffer( 'item_embed_src' ) ;
	}

	if ( $this->_is_playlist_type() ) {
		echo $this->build_line_ele( $this->get_constant('ITEM_PLAYLIST_TYPE'), 
			$this->_build_ele_playlist_type() );

		if ( $this->_is_playlist_feed_kind() ) {
			echo $this->build_line_ele( $this->get_constant('ITEM_PLAYLIST_FEED'), 
				$this->_build_ele_playlist_feed() );
		} else {
			$this->set_row_hidden_buffer( 'item_playlist_feed' ) ;
		}

		if ( $this->_is_playlist_dir_kind() ) {
			echo $this->build_line_ele( $this->get_constant('ITEM_PLAYLIST_DIR'), 
				$this->_build_ele_playlist_dir() );
		} else {
			$this->set_row_hidden_buffer( 'item_playlist_dir' ) ;
		}

		echo $this->build_row_text( $this->get_constant('ITEM_PLAYLIST_CACHETIME'),
			'item_playlist_time' );

	} else {
		$this->set_row_hidden_buffer( 'item_playlist_type' ) ;
		$this->set_row_hidden_buffer( 'item_playlist_feed' ) ;
		$this->set_row_hidden_buffer( 'item_playlist_dir' ) ;
		$this->set_row_hidden_buffer( 'item_playlist_time' ) ;
	}

	if ( $this->_is_admin_upload_type() ) {
		echo $this->build_line_ele( $this->get_constant('CAP_PHOTO_SELECT'), 
			$this->_build_ele_photo_file_external( $cont_row ) );

		if ( $has_rotate ) {
			echo $this->build_line_ele( $this->get_constant('RADIO_ROTATETITLE'), 
				$this->_build_ele_rotate() );
		}
	}

	echo $this->build_line_ele( $this->get_constant('CAP_THUMB_SELECT'), 
		$this->_build_ele_thumb_file_external( $thumb_row ) );

	if ( $is_edit && $this->_is_valid() ) {
		echo $this->build_line_ele( $this->get_constant('CAP_VALIDPHOTO'), 
			$this->_build_ele_valid() ) ;

	} else {
		$this->set_row_hidden_buffer( 'item_status' ) ;
	}

	if ( $is_edit ) {
		echo $this->build_line_ele( $this->get_constant('ITEM_TIME_UPDATE'),
			$this->_build_ele_time_update() ) ;

	} else {
		$this->set_row_hidden_buffer( 'item_time_update' ) ;
	}

	echo $this->build_line_ele( '', $this->_build_ele_button( $mode ) );

	echo $this->build_table_end();
	echo $this->render_hidden_buffers();
	echo $this->build_form_end();
	echo "<br />\n";
}

function _is_admin_upload_type()
{
	if ( $this->_is_embed_type() ) {
		return false;
	}
	if ( $this->_is_playlist_type() ) {
		return false;
	}
	return true;
}

function _is_playlist_type()
{
	$kind = $this->get_row_by_key( 'item_kind' );

	if ( $this->_param_type == 'playlist' ) {
		return true;
	}
	if ( $this->_is_playlist_feed_kind() ) {
		return true;
	}
	if ( $this->_is_playlist_dir_kind() ) {
		return true;
	}
	return false;
}

function _is_playlist_feed_kind()
{
	$kind = $this->get_row_by_key( 'item_kind' );
	if ( $this->_kind_class->is_playlist_feed_kind( $kind ) ) {
		return true;
	}
	return false ;
}

function _is_playlist_dir_kind()
{
	$kind = $this->get_row_by_key( 'item_kind' );
	if ( $this->_kind_class->is_playlist_dir_kind( $kind ) ) {
		return true;
	}
	return false ;
}

function _is_valid()
{
	$status = $this->get_row_by_key( 'item_status' );
	if ( $status <= 0 ) {
		return true;
	}
	return false;
}

function _build_ele_valid()
{
	$value = intval( $this->_is_valid() ) ;
	$text  = $this->build_input_checkbox_yes( 'valid', $value );
	$text .= ' '.$this->get_constant('CAP_VALIDPHOTO') ;
	return $text;
}

function _build_ele_time_update( $size=50 )
{
	$name = 'item_time_update';
	$name_checkbox  = $name.'_checkbox';
	$value_checkbox = $this->_get_checkbox_by_name( $name_checkbox );

	$value = intval( $this->get_row_by_key( $name ) );
	if ( empty($value) ) {
		$value = time();
	}
	$date = formatTimestamp( $value, $this->get_constant('DTFMT_YMDHI') );
	$now  = formatTimestamp( time(), $this->get_constant('DTFMT_YMDHI') );

	$text  = $this->build_input_checkbox_yes( $name_checkbox, $value_checkbox );
	$text .= $this->get_constant('DSC_SET_TIME_UPDATE') ."<br />\n";
	$text .= $this->build_input_text( $name, $date, $size );
	$text .= "<br />\n";
	$text .= _AM_WEBPHOTO_TIME_NOW .' : ';
	$text .= $now ;

	return $text;
}

function _build_ele_playlist_feed( $src )
{
	$name  = 'item_playlist_feed';
	$value = $this->get_row_by_key( $name );

	$ele  = $this->build_input_text( $name, $value, $this->_PLAYLIST_FEED_SIZE );
	$ele .= "<br />\n";
	$ele .= _AM_WEBPHOTO_PLAYLIST_FEED_DSC ;

	if ( $value ) {
		$ele .= "<br />\n";
		$ele .= $this->_build_link( $value );
	}

	return $ele;
}

function _build_ele_playlist_dir()
{
	$name    = 'item_playlist_dir';
	$value   = $this->get_row_by_key( $name );
	$options = $this->_utility_class->get_dirs_in_dir( $this->_MEDIAS_DIR, false, true, true );

	$ele  = _AM_WEBPHOTO_PLAYLIST_DIR_DSC ;
	$ele .= "<br />\n";
	$ele .= $this->build_form_select( $name, $value, $options, 1 );

	return $ele;
}

function _build_ele_playlist_cache()
{
	// dummy
}

function _build_ele_playlist_chain()
{
	// dummy
}

//---------------------------------------------------------
// playlist
//---------------------------------------------------------
function print_form_playlist( $mode, $row )
{
	switch ($mode)
	{
		case 'admin_submit':
		default:
			$url = $this->_URL_ADMIN_INDEX ;
			$fct = $this->_THIS_FCT ;
			break;
	}

	$this->set_row( $row );

	echo $this->build_form_tag( 'playlist', $url );
	echo $this->build_html_token();

	echo $this->build_input_hidden( 'fct',   $fct );
	echo $this->build_input_hidden( 'op',   'submit_form' );
	echo $this->build_input_hidden( 'type', 'playlist' );
	echo $this->build_input_hidden( 'item_displaytype', _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER );

	echo $this->build_table_begin();
	echo $this->build_line_title( _AM_WEBPHOTO_PLAYLIST_ADD );

	echo $this->build_line_ele( _AM_WEBPHOTO_PLAYLIST_TYPE , 
		$this->_build_ele_playlist_type() );

	echo $this->build_line_ele( $this->get_constant('ITEM_PLAYLIST_TYPE'), 
		$this->_build_ele_playlist_kind() );

	echo $this->build_line_ele( '', 
		$this->build_input_submit( 'submit', $this->get_constant('BUTTON_SELECT') ) );

	echo $this->build_table_end();
	echo $this->build_form_end();
	echo "<br />\n";
}

function _build_ele_playlist_kind()
{
	$value   = $this->_get_embed_type( false );
	$options = $this->_item_handler->get_kind_options( 'playlist' );

	return $this->build_form_select( 'item_kind', $value, $options, 1 );
}

function _build_ele_playlist_type()
{
	$value   = $this->_get_playlist_type( true ) ; 
	$options = $this->_item_handler->get_playlist_type_options();

	return $this->build_form_select( 'item_playlist_type', $value, $options, 1 );
}

function _get_playlist_type( $flag )
{
	$value = $this->get_row_by_key( 'item_playlist_type' );
	if ( $flag && empty($value) ) {
		$value = $this->_PLAYLIST_TYPE_DEFAULT ;
	}
	return $value;
}

//---------------------------------------------------------
// refresh playlist cache
//---------------------------------------------------------
function print_form_refresh_cache()
{
	echo $this->build_form_tag( 'playlist_refresh', $this->_URL_ADMIN_INDEX );
	echo $this->build_html_token();

	echo $this->build_input_hidden( 'fct', $this->_THIS_FCT );
	echo $this->build_input_hidden( 'op',  'refresh_cache' );
	echo $this->build_input_submit( 'submit', _AM_WEBPHOTO_PLAYLIST_REFRESH );

	echo $this->build_form_end();
}

//---------------------------------------------------------
// refresh playlist cache
//---------------------------------------------------------
function print_form_select_item( $item_id, $sort )
{
	echo '<form style="left; width: 60%;" name="sortform" id="sortform">'."\n";      
	echo $this->_build_sort_select( $sort );
	echo $this->_build_button( 'submit_form', _AM_WEBPHOTO_ITEM_ADD );
	echo $this->_build_button( 'view_log',    _AM_WEBPHOTO_LOG_VIEW );
	echo $this->build_form_end();

}

function _build_sort_select( $sort_in )
{
	$url = $this->_THIS_URL.'&sort=' ;

	$str  = '<select name="sort" onChange="location=this.options[this.selectedIndex].value;">'."\n";
	$str .= '<option value="">';
	$str .= $this->_sort_class->get_lang_sortby( $sort_in ) ;
	$str .= "</option>\n";

	foreach ( $this->_sort_array as $k => $v ) 
	{
		$str .= '<option value="'. $url.$k .'">';
		$str .= $v[1] ;
		$str .= "</option>\n";
	}

	$str .= "</select>\n";
	return $str;
}

function _build_button( $op, $value )
{
	$onclick = "location='".$this->_THIS_URL."&amp;op=".$op."'" ;
	$str = '<input type="button" value="'. $value .'" onClick="'. $onclick .'" />'."\n";   
	return $str;
} 

// --- class end ---
}

?>