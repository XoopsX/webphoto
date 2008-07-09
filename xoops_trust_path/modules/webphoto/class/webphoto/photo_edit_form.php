<?php
// $Id: photo_edit_form.php,v 1.4 2008/07/09 06:13:20 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
	var $_preload_class;
	var $_tag_class;

	var $_checkbox_array   = array();

	var $_TAGS_SIZE = 80;
	var $_ICON_ROTATE_URL;

	var $_ARRAY_PHOTO_ITEM = array(
		'photo_datetime', 'photo_place', 'photo_equipment', 'photo_cont_duration' );
	var $_ARRAY_PHOTO_TEXT = null;

	var $_FLAG_PERM = true;

	var $_VIDEO_THUMB_WIDTH = 120;
	var $_VIDEO_ICON_WIDTH  = 64;
	var $_FLASH_EXT         = 'flv';

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

	$this->_preload_class   =& webphoto_d3_preload::getInstance();
	$this->_preload_class->init( $dirname , $trust_dirname );
	$this->_preload_constant();

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
function _preload_constant()
{
	$arr = $this->_preload_class->get_preload_const_array();

	if ( !is_array($arr) || !count($arr) ) {
		return true;	// no action
	}

	foreach( $arr as $k => $v )
	{
		$local_name = strtoupper( '_' . $k );

// array type
		if ( strpos($k, 'array_') === 0 ) {
			$temp = $this->str_to_array( $v, '|' );
			if ( is_array($temp) && count($temp) ) {
				$this->$local_name = $temp;
			}

// string type
		} else {
			$this->$local_name = $v;
		}
	}

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

	$is_submit = false;
	$is_edit   = false;

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
		echo $this->build_iframe_gmap( $row['photo_id'] );
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
		echo $this->build_input_hidden( 'photo_id', $row['photo_id'] );
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
		$this->get_constant('PHOTO_TITLE'), $this->_build_ele_title() );

	if ( $this->_is_in_array( 'photo_datetime' ) ) {
		echo $this->build_line_ele( $this->get_constant( 'photo_datetime' ), 
			$this->_build_ele_datetime() );
	}

	$this->_print_row_text_is_in_array( 'photo_place' );
	$this->_print_row_text_is_in_array( 'photo_equipment' );

	if ( $this->_is_in_array( 'photo_cont_duration' ) ) {
		echo $this->build_line_ele( $this->_build_cap_duration() , 
			$this->_build_ele_duration() );
	}

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_PHOTO_TEXT; $i++ ) 
	{
		$name = 'photo_text'.$i;
		if ( is_array($this->_ARRAY_PHOTO_TEXT) && in_array( $name, $this->_ARRAY_PHOTO_TEXT) ) {
			echo $this->build_row_text( $this->get_constant( $name ), $name );
		}
	}

	echo $this->build_row_dhtml( $this->get_constant('PHOTO_DESCRIPTION'), 'photo_description' );

	if ( $is_edit ) {
		echo $this->build_row_textarea( $this->get_constant('PHOTO_CONT_EXIF'), 
			'photo_cont_exif' );
	}

	echo $this->build_line_ele(  $this->get_constant('TAGS'), 
		$this->_build_ele_tags( $param ) );

	if ( $cfg_gmap_apikey ) {
		echo $this->build_row_text_id( $this->get_constant('PHOTO_GMAP_LATITUDE'),
			'photo_gmap_latitude',  'webphoto_gmap_latitude'  );

		echo $this->build_row_text_id( $this->get_constant('PHOTO_GMAP_LONGITUDE'),
			'photo_gmap_longitude', 'webphoto_gmap_longitude' );

		echo $this->build_row_text_id( $this->get_constant('PHOTO_GMAP_ZOOM'),
			'photo_gmap_zoom',      'webphoto_gmap_zoom'      );

		echo $this->build_line_ele(
			$this->get_constant('GMAP_ICON'), $this->_build_ele_gicon() );
	}

	echo $this->build_line_ele( $this->get_constant('CAP_PHOTO_SELECT'), 
		$this->_build_ele_photo_file() );

	if ( $has_rotate ) {
		echo $this->build_line_ele( $this->get_constant('RADIO_ROTATETITLE'), 
			$this->_build_ele_rotate() );
	}

	echo $this->build_line_ele( $this->get_constant('CAP_THUMB_SELECT'), 
		$this->_build_ele_thumb_file() );

	if ( $is_edit && $this->_is_module_admin ) {
		echo $this->build_line_ele( $this->get_constant('CAP_VALIDPHOTO'), 
			$this->_build_ele_valid( $row['photo_status'] ) ) ;

		echo $this->build_line_ele( $this->get_constant('PHOTO_TIME_UPDATE'),
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
	$value = $this->get_row_by_key( 'photo_title' );
	$ele  = $this->build_input_text( 'photo_title', $value, $size );
	$ele .= "<br />\n";
	$ele .= $this->get_constant('DSC_TITLE_BLANK');
	return $ele;
}

function _build_ele_datetime( $size=50 )
{
	$name           = 'photo_datetime';
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
	$name = 'photo_time_update';
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
	$cap  = $this->get_constant( 'photo_cont_duration' ); 
	$cap .= ' ( ';
	$cap .= $this->get_constant( 'second' ); 
	$cap .= ' ) ';
	return $cap;
}

function _build_ele_duration( $size=50 )
{
	$value = $this->get_row_by_key( 'photo_cont_duration' );
	$ele  = $this->build_input_text( 'photo_cont_duration', $value, $size );
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

function _build_ele_photo_file()
{
	$photo_url_s = $this->get_row_by_key( 'photo_cont_url' );

	$text  = $this->build_form_file( $this->_PHOTO_FIELD_NAME );
	$text .= "<br />\n";

	if ( $photo_url_s ) {
		$text .= '<a href="'. $photo_url_s .'" target="_blank">'. $photo_url_s .'</a>'."<br />\n";
	}
	return $text;
}

function _build_ele_thumb_file()
{
	$cfg_makethumb = $this->_config_class->get_by_name( 'makethumb' );
	$thumb_url_s   = $this->get_row_by_key( 'photo_thumb_url' );

	$text  = '';
//	$text .= $this->build_input_text('thumb_url', 'http://');
//	$text .= "<br />\n";
//	$text .= 'or'."<br />\n";
	$text .= $this->build_form_file( $this->_THUMB_FIELD_NAME );
	$text .= "<br />\n";

	if ( $thumb_url_s ) {
		$text .= '<a href="'. $thumb_url_s .'" target="_blank">'. $thumb_url_s .'</a>'."<br />\n";
	}
	if ( $cfg_makethumb ) {
		$text .= $this->get_constant('DSC_THUMB_SELECT') ."<br />\n";
	}
	return $text;
}

function _build_ele_gicon()
{
	$gicon_id = $this->get_row_by_key( 'photo_gicon_id' );

	return $this->build_form_select(
		'photo_gicon_id',  $gicon_id, $this->_gicon_handler->get_sel_options(), 1 );
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
		$this->get_row_by_key( 'photo_cat_id' ) , 'photo_cat_id' );
}

//---------------------------------------------------------
// imagemanager
//---------------------------------------------------------
function print_form_imagemanager( $row, $param )
{
	$has_resize    = $param['has_resize'];
	$allowed_exts  = $param['allowed_exts'];

	$cfg_gmap_apikey = $this->_config_class->get_by_name( 'gmap_apikey' );

	$this->set_row( $row );

	if ( $cfg_gmap_apikey ) {
		echo $this->build_iframe_gmap( $row['photo_id'] );
	}

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
	echo $this->build_line_ele( $this->get_constant('PHOTO_TITLE'), 
		$this->_build_ele_title() );
	echo $this->build_line_ele( $this->get_constant('CAP_PHOTO_SELECT'), 
		$this->_build_ele_photo_file() );

	echo $this->build_line_ele( '', $this->build_input_submit( 'submit', _ADD ) );

	echo $this->build_table_end();
	echo $this->build_form_end();
}

//---------------------------------------------------------
// delete confirm
//---------------------------------------------------------
function print_form_delete_confirm( $photo_id )
{
	$hiddens = array(
		'fct'      => 'edit' ,
		'op'       => 'delete' ,
		'photo_id' => $photo_id ,
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

	$mode     = $param['mode'];
	$photo_id = $row['photo_id'];

	switch ($mode)
	{
		case 'edit':
			$fct = 'edit';
			break;

		case 'submit':
		default:
			$fct = 'submit';
			break;
	}

	$max = $video_class->get_thumb_plural_max();

	echo $this->build_form_begin();
	echo $this->build_html_token();
	echo $this->build_input_hidden( 'op',       'video' );
	echo $this->build_input_hidden( 'fct',      $fct );
	echo $this->build_input_hidden( 'photo_id', $photo_id );

	echo $this->build_table_begin();
	echo $this->build_line_title( $this->get_constant('TITLE_VIDEO_THUMB_SEL'), $max );
	echo "<tr>\n";

	for ( $i=0; $i<=$max; $i++ ) 
	{
		$width = $this->_VIDEO_THUMB_WIDTH ;
		if ( $i == 0 ) {
			$width = $this->_VIDEO_ICON_WIDTH ;
		}

	 	$name = $video_class->build_thumb_name( $photo_id, $i, true );
		$file = $this->_TMP_DIR .'/'. $name ;
		$src  = $this->_TMP_URL .'/'. $name ;

		if ( is_file($file) ) {
			echo '<td align="center" class="odd">';
			echo '<img src="'. $src .'" width="'. $width .'"><br />';
			echo '<input type="radio" name="num" value="'.$i.'" />';
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

	$photo_id  = $row['photo_id'];

	$is_image  = $param['is_image'] ;
	$is_video  = $param['is_video'] ;

	if ( !$is_video ) {
		return ;
	}

	$this->set_row( $row );

	echo $this->build_form_begin();
	echo $this->build_html_token();
	echo $this->build_input_hidden( 'op',       'redo' );
	echo $this->build_input_hidden( 'fct',      'edit' );
	echo $this->build_input_hidden( 'photo_id', $photo_id );

	echo $this->build_table_begin();
	echo $this->build_line_title( $this->get_constant('TITLE_VIDEO_REDO') );

	echo $this->build_line_ele( $this->get_constant('CAP_REDO_FLASH'), 
		$this->_build_ele_redo_flash() );

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

function _build_ele_redo_flash()
{
	$file_url_s = $this->get_row_by_key( 'photo_file_url' );
	$file_ext   = $this->get_row_by_key( 'photo_file_ext' );

	$text  = $this->build_input_checkbox_yes( 'redo_flash', 1 );
	$text .= ' '.$this->get_constant('CAP_REDO_FLASH') ;

	if ( $file_url_s && ( $file_ext == $this->_FLASH_EXT ) ) {
		$text .= "<br />\n";
		$text .= '<a href="'. $file_url_s .'" target="_blank">'. $file_url_s .'</a>'."<br />\n";
	}

	return $text;
}

//---------------------------------------------------------
// xoops param
//---------------------------------------------------------
function build_form_user_select( $sel_name, $sel_value, $none=false )
{
	$list = $this->get_xoops_user_list();

	$opt = '';

	if ( $none ) {
		$opt .= '<option value="0">';
		$opt .= _AM_WEBPHOTO_OPT_NOCHANGE;
		$opt .= "</option>\n" ;
	}

	foreach ( $list as $uid => $uname_s )
	{
		$selected = $this->build_form_selected( $uid, $sel_value );
		$opt .= '<option value="'. $uid .'" '. $selected .' ">';
		$opt .= $uname_s;
		$opt .= "</option>\n";
	}

	$text  = '<select name="'. $sel_name .'">';
	$text .= $opt;
	$text .= "</select>\n";
	return $text;

}

// --- class end ---
}

?>