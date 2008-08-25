<?php
// $Id: photo_edit_form.php,v 1.8 2008/08/25 19:28:05 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
	var $_perm_class;
	var $_tag_class;

	var $_checkbox_array   = array();

	var $_TAGS_SIZE = 80;
	var $_ICON_ROTATE_URL;

	var $_ARRAY_PHOTO_ITEM = array(
		'item_datetime', 'item_place', 'item_equipment', 'item_duration' );
	var $_ARRAY_PHOTO_TEXT = null;

	var $_FLAG_PERM = true;

	var $_VIDEO_THUMB_WIDTH = 120;
	var $_VIDEO_ICON_WIDTH  = 64;
	var $_FLASH_EXT         = _C_WEBPHOTO_VIDEO_FLASH_EXT ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_edit_form( $dirname, $trust_dirname )
{
	$this->webphoto_form_this( $dirname, $trust_dirname );

	$this->_gicon_handler =& webphoto_gicon_handler::getInstance( $dirname );
	$this->_perm_class    =& webphoto_permission::getInstance( $dirname );

	$this->_tag_class =& webphoto_tag::getInstance( $dirname );
	$this->_tag_class->set_is_japanese( $this->_is_japanese );

	$this->_ICON_ROTATE_URL = $this->_MODULE_URL .'/images/uploader';

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
function print_form_common( $row, $param )
{
	$mode          = $param['mode'];
	$preview_name  = $param['preview_name'];
	$has_resize    = $param['has_resize'];
	$has_rotate    = $param['has_rotate'];
	$allowed_exts  = $param['allowed_exts'];
	$is_video      = isset($param['is_video']) ? (bool)$param['is_video'] : false ;

	$this->_set_checkbox( $param['checkbox_array'] );

	$is_submit = false ;
	$is_edit   = false ;
	$cont_row  = null ;
	$thumb_row = null ;

	switch ($mode)
	{
		case 'edit':
			$is_edit = true;
			$fct     = 'edit';
			break;

		case 'submit':
		default:
			$is_submit = true;
			$fct       = 'submit';
			break;
	}

	$cfg_gmap_apikey = $this->_config_class->get_by_name( 'gmap_apikey' );

	$this->set_row( $row );

	if ( $cfg_gmap_apikey ) {
		echo $this->build_iframe_gmap( $row['item_id'] );
	}

	echo $this->build_form_upload( 'uploadphoto', $this->_THIS_URL );
	echo $this->build_html_token();

	echo $this->build_input_hidden( 'op',           'submit' );
	echo $this->build_input_hidden( 'fct',          $fct );
	echo $this->build_input_hidden( 'fieldCounter', $this->_FILED_COUNTER_2 );
	echo $this->_build_input_hidden_max_file_size();

	if ( $is_submit ) {
		echo $this->build_input_hidden( 'preview_name', $preview_name, true );
	}

	if ( $is_edit ) {
		echo $this->build_input_hidden( 'photo_id', $row['item_id'] );
		$cont_row  = $this->_file_handler->get_row_by_id( $row['item_file_id_1'] );
		$thumb_row = $this->_file_handler->get_row_by_id( $row['item_file_id_2'] );
	}

	echo $this->build_table_begin();
	echo $this->build_line_title( $this->get_constant('TITLE_PHOTOUPLOAD') );

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

	if ( $this->_is_in_array( 'item_duration' ) ) {
		echo $this->build_line_ele( $this->_build_cap_duration() , 
			$this->_build_ele_duration( $cont_row ) );
	}

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

	echo $this->build_line_ele( $this->get_constant('CAP_PHOTO_SELECT'), 
		$this->_build_ele_photo_file( $cont_row ) );

	if ( $has_rotate ) {
		echo $this->build_line_ele( $this->get_constant('RADIO_ROTATETITLE'), 
			$this->_build_ele_rotate() );
	}

	echo $this->build_line_ele( $this->get_constant('CAP_THUMB_SELECT'), 
		$this->_build_ele_thumb_file( $thumb_row ) );

	if ( $is_edit && $this->_is_module_admin ) {
		echo $this->build_line_ele( $this->get_constant('CAP_VALIDPHOTO'), 
			$this->_build_ele_valid( $row['item_status'] ) ) ;

		echo $this->build_line_ele( $this->get_constant('ITEM_TIME_UPDATE'),
			$this->_build_ele_time_update() ) ;
	}

	echo $this->build_line_ele( '', $this->_build_ele_button( $mode ) );

	echo $this->build_table_end();
	echo $this->build_form_end();
	echo "<br />\n";
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
	$cfg_width  = $this->_config_class->get_by_name( 'width' );
	$cfg_height = $this->_config_class->get_by_name( 'height' );

	$text = $cfg_width .' x '. $cfg_height ."<br />\n" ;

	if ( $has_resize ) {
		$text .= $this->get_constant('DSC_PIXCEL_RESIZE');
	} else {
		$text .= $this->get_constant('DSC_PIXCEL_REJECT');
	}

	return $text;
}

function _build_ele_maxsize()
{
	$cfg_fsize  = $this->_config_class->get_by_name( 'fsize' );

	$size_desc = '';
	if( ! ini_get( 'file_uploads' ) ) {
		$size_desc = ' &nbsp; <b>"file_uploads" off</b>';
	}

	$text  = $this->format_filesize( $cfg_fsize );
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

	$text  = $this->build_input_checkbox_yes( $name_checkbox, $value_checkbox );
	$text .= $this->get_constant('DSC_SET_TIME_UPDATE') ."<br />\n";
	$text .= $this->build_input_text( $name, $date, $size );

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
	$cfg_fsize = $this->_config_class->get_by_name( 'fsize' );
	return $this->build_input_hidden( 'max_file_size', $cfg_fsize );
}

function _build_ele_photo_file( $cont_row )
{
	$url_s = '' ;
	if ( isset($cont_row['file_url']) ) {
		$url_s = $this->sanitize( $cont_row['file_url'] ) ;
	}

	$text  = $this->build_form_file( $this->_PHOTO_FIELD_NAME );
	$text .= "<br />\n";

	if ( $url_s ) {
		$text .= '<a href="'. $url_s .'" target="_blank">'. $url_s .'</a>'."<br />\n";
	}
	return $text;
}

function _build_ele_thumb_file( $thumb_row )
{
	$cfg_makethumb = $this->_config_class->get_by_name( 'makethumb' );

	$url_s = '' ;
	if ( isset($thumb_row['file_url']) ) {
		$url_s = $this->sanitize( $thumb_row['file_url'] ) ;
	}

	$text  = '';
//	$text .= $this->build_input_text('thumb_url', 'http://');
//	$text .= "<br />\n";
//	$text .= 'or'."<br />\n";
	$text .= $this->build_form_file( $this->_THUMB_FIELD_NAME );
	$text .= "<br />\n";

	if ( $url_s ) {
		$text .= '<a href="'. $url_s .'" target="_blank">'. $url_s .'</a>'."<br />\n";
	}
	if ( $cfg_makethumb ) {
		$text .= $this->get_constant('DSC_THUMB_SELECT') ."<br />\n";
	}
	return $text;
}

function _build_ele_gicon()
{
	$gicon_id = $this->get_row_by_key( 'item_gicon_id' );

	return $this->build_form_select(
		'item_gicon_id',  $gicon_id, $this->_gicon_handler->get_sel_options(), 1 );
}

function _build_ele_tags( $param )
{
	$value = $this->_tag_class->tag_name_array_to_str( $param['tag_name_array'] );
	$text  = $this->build_input_text( 'tags', $value, $this->_TAGS_SIZE );
	$text .= "<br />\n";
	$text .= $this->get_constant('DSC_TAG_DIVID') ;
	return $text;
}

function _build_ele_valid( $row_status )
{
	$value = empty( $row_status ) ? 0 : 1 ;
	$text  = $this->build_input_checkbox_yes( 'valid', $value );
	$text .= ' '.$this->get_constant('CAP_VALIDPHOTO') ;
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
	$has_deletable = $this->_perm_class->has_deletable();

	$is_submit = false;
	$is_edit   = false;

	switch ($mode)
	{
		case 'edit':
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

	if ( $is_edit && $has_deletable ) {
		$button .= $this->build_input_submit( 'conf_delete', _DELETE );
	}

	return $button;
}

function _build_ele_category()
{
	return $this->_cat_handler->build_selbox_with_perm_post(
		$this->get_row_by_key( 'item_cat_id' ) , 'item_cat_id' );
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
	echo $this->build_input_hidden( 'fct',          'submit_imagemanager' );
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
function print_form_delete_confirm( $item_id )
{
	$hiddens = array(
		'fct'      => 'edit' ,
		'op'       => 'delete' ,
		'item_id' => $item_id ,
	);

	echo $this->build_form_confirm( $hiddens, $this->_THIS_URL, $this->get_constant('CONFIRM_PHOTODEL'), _YES, _NO );
}

function build_iframe_gmap( $photo_id=null )
{
	$WIDTH  = '100%';
	$HEIGHT = '650px';

	$src = $this->_MODULE_URL .'/index.php?fct=gmap_location';
	if ( $photo_id ) {
		$src .= '&amp;photo_id='.intval($photo_id);
	}

	$text  = '<iframe src="'. $src .'" width="'. $WIDTH .'" height="'. $HEIGHT .'" frameborder="0" scrolling="yes" >'."\n";
	$text .= $this->get_constant('IFRAME_NOT_SUPPORT') ."\n";
	$text .= '</iframe>'."\n";
	return $text;
}

//---------------------------------------------------------
// video
//---------------------------------------------------------
function print_form_video_thumb( $row, $param )
{
	$video_class =& webphoto_video::getInstance( $this->_DIRNAME );

	$mode    = $param['mode'];
	$item_id = $row['item_id'];

	switch ($mode)
	{
		case 'edit':
			$fct = 'edit';
			break;

		case 'submit_file':
			$fct = 'submit_file';
			break;

		case 'submit':
		default:
			$fct = 'submit';
			break;
	}

	$max = $video_class->get_thumb_plural_max();

	echo $this->build_form_begin();
	echo $this->build_input_hidden( 'op',       'video' );
	echo $this->build_input_hidden( 'fct',      $fct );
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
			$url = $this->_MODULE_URL.'/index.php?fct=image&amp;name='. $name_encode ;
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
function print_form_redo( $row, $param )
{
	$cfg_makethumb = $this->_config_class->get_by_name( 'makethumb' );

	$item_id  = $row['item_id'];

	$is_image  = $param['is_image'] ;
	$is_video  = $param['is_video'] ;

	if ( !$is_video ) {
		return ;
	}

	$flash_row = $this->_file_handler->get_row_by_id( $row['item_file_id_4'] );

	$this->set_row( $row );

	echo $this->build_form_begin();
	echo $this->build_input_hidden( 'op',       'redo' );
	echo $this->build_input_hidden( 'fct',      'edit' );
	echo $this->build_input_hidden( 'photo_id', $item_id );

	echo $this->build_table_begin();
	echo $this->build_line_title( $this->get_constant('TITLE_VIDEO_REDO') );

	echo $this->build_line_ele( $this->get_constant('CAP_REDO_FLASH'), 
		$this->_build_ele_redo_flash( $flash_row ) );

	if ( $cfg_makethumb ) {
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
	$url_s = '' ;
	if ( is_array($flash_row) ) {
		$url_s = $this->sanitize( $flash_row['file_url'] ) ;
	}

	$text  = $this->build_input_checkbox_yes( 'redo_flash', 1 );
	$text .= ' '.$this->get_constant('CAP_REDO_FLASH') ;

	if ( $url_s ) {
		$text .= "<br />\n";
		$text .= '<a href="'. $url_s .'" target="_blank">'. $url_s .'</a>'."<br />\n";
	}

	return $text;
}

//---------------------------------------------------------
// form file
//---------------------------------------------------------
function print_form_file( $param )
{
	$has_resize    = $param['has_resize'];
	$allowed_exts  = $param['allowed_exts'];

	echo $this->build_form_begin();
	echo $this->build_input_hidden( 'fct', 'submit_file' );
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
	$cfg_file_size  = $this->_config_class->get_by_name( 'file_size' );
	$text = $this->format_filesize( $cfg_file_size );
	return $text;
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