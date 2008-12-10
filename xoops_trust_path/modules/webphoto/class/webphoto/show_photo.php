<?php
// $Id: show_photo.php,v 1.17 2008/12/10 19:08:56 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-07 K.OHWADA
// get_text_type_array()
// 2008-11-29 K.OHWADA
// remove get_show_file_url()
// 2008-11-16 K.OHWADA
// webphoto_show_image
// perm_download()
// get_file_url_by_kind() -> get_show_file_url()
// 2008-11-08 K.OHWADA
// item_external_middle
// 2008-10-01 K.OHWADA
// build_media_player() -> build_flash_player()
// build_external_link()
// 2008-09-15 K.OHWADA
// added build_media_player()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// 2008-08-01 K.OHWADA
// typo summry -> summary
// 2008-07-01 K.OHWADA
// added build_show_is_video()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_show_photo
//=========================================================
class webphoto_show_photo extends webphoto_base_this
{
	var $_tag_class;
	var $_highlight_class;
	var $_image_class;
	var $_multibyte_class;

	var $_cfg_sort;
	var $_cfg_newdays;
	var $_cfg_popular;
	var $_cfg_nameoruname;
	var $_cfg_thumb_width;
	var $_cfg_middle_width;

	var $_item_text_type_array;
	var $_time_newdays;
	var $_usereal;

	var $_flag_highlight = false;
	var $_keyword_array  = null;

	var $_URL_DEFAULT_IMAGE;
	var $_URL_PIXEL_IMAGE;
	var $_URL_CATEGORY_IMAGE;

	var $_DEFAULT_IMAGE_WIDTH  = 64;
	var $_DEFAULT_IMAGE_HEIGHT = 64;

	var $_WINDOW_MERGIN = 16;
	var $_MAX_SUMMARY   = 100;
	var $_SUMMARY_TAIL  = ' ...';
	var $_RATING_DECIMALS    = 2;
	var $_FILESIZE_PRECISION = 1;

	var $_SHOW_DESC_ARRAY = array(
		'description_disp', 'siteurl', 'artist', 'album', 'label' );

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_show_photo( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );

	$this->_image_class =& webphoto_show_image::getInstance( $dirname );

	$this->_tag_class =& webphoto_tag::getInstance( $dirname );
	$this->_tag_class->set_is_japanese( $this->_is_japanese );

	$this->_highlight_class =& webphoto_lib_highlight::getInstance();
	$this->_highlight_class->set_replace_callback( 'webphoto_highlighter_by_class' );
	$this->_highlight_class->set_class( 'webphoto_highlight' );

	$this->_multibyte_class =& webphoto_lib_multibyte::getInstance();
	$this->_multibyte_class->set_ja_kuten(   _WEBPHOTO_JA_KUTEN );
	$this->_multibyte_class->set_ja_dokuten( _WEBPHOTO_JA_DOKUTEN );
	$this->_multibyte_class->set_ja_period(  _WEBPHOTO_JA_PERIOD );
	$this->_multibyte_class->set_ja_comma(   _WEBPHOTO_JA_COMMA );

	$this->_cfg_newdays      = $this->get_config_by_name('newdays');
	$this->_cfg_popular      = $this->get_config_by_name('popular');
	$this->_cfg_nameoruname  = $this->get_config_by_name('nameoruname');
	$this->_cfg_thumb_width  = $this->get_config_by_name('thumb_width' ) ;
	$this->_cfg_middle_width = $this->get_config_by_name('middle_width' ) ;

	$this->_item_text_type_array = $this->_item_handler->get_text_type_array();

	$this->_time_newdays = time() - 86400 * $this->_cfg_newdays ;
	$this->_usereal = ( $this->_cfg_nameoruname == 'name' ) ? 1 : 0 ;

	$this->_URL_DEFAULT_IMAGE  = $this->_MODULE_URL .'/images/exts/default.png';
	$this->_URL_PIXEL_IMAGE    = $this->_MODULE_URL .'/images/icons/pixel_trans.png';
	$this->_URL_CATEGORY_IMAGE = $this->_MODULE_URL .'/images/icons/category.png';

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_show_photo( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// photo show
//---------------------------------------------------------
// Get photo's array to assign into template (light version)
function build_photo_show_basic( $row, $tag_name_array=null )
{
	extract( $row ) ;

	$show_arr = array();
	foreach ( $row as $k => $v )
	{
		$name = str_replace( 'item_', '', $k );
		$show_arr[ $name ] = $v;
		if ( in_array( $k, $this->_item_text_type_array ) ) {
			$show_arr[ $name.'_s' ] = $this->sanitize( $v );
		}
	}

	list($desc_disp, $summary) = $this->build_show_desc_summary( 
		$row, $this->_flag_highlight, $this->_keyword_array ) ;

	$datetime_disp = $this->mysql_datetime_to_str( $item_datetime );

	$show_arr['photo_id']            = $item_id ;
	$show_arr['uname_s']             = $this->build_show_uname( $item_uid ) ;
	$show_arr['time_update_m']       = $this->format_timestamp( $item_time_update , 'm' ) ;
	$show_arr['datetime_disp']       = $datetime_disp ;
	$show_arr['datetime_urlencode']  = $this->rawurlencode_uri_encode_str( $datetime_disp ) ;
	$show_arr['place_urlencode']     = $this->rawurlencode_uri_encode_str( $item_place ) ;
	$show_arr['equipment_urlencode'] = $this->rawurlencode_uri_encode_str( $item_equipment ) ;
	$show_arr['description_disp']    = $desc_disp ;
	$show_arr['summary']             = $summary ;
	$show_arr['cont_exif_disp']      = $this->_item_handler->build_show_exif_disp( $row ) ;
	$show_arr['tags']                = $this->build_show_tags_from_tag_name_array( $tag_name_array ) ;
	$show_arr['is_owner']            = $this->is_photo_owner( $item_uid ) ;
	$show_arr['is_video']            = $this->is_video_kind( $item_kind ) ;
	$show_arr['perm_download']       = $this->perm_download( $row ) ;
	$show_arr['can_download']        = $this->can_download( $row ) ;

	$show_desc = false;
	foreach ( $this->_SHOW_DESC_ARRAY as $key ) 
	{
		if ( $show_arr[ $key ] ) {
			$show_desc = true;
		}
	}

	$arr2 = array();
	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; $i++ ) 
	{
		$name_i      = 'text_'.$i;
		$item_name_i = 'item_'.$name_i;
		$text_i      = $row[ $item_name_i ];
		$text_i_s    = $this->sanitize( $text_i );

		if ( $text_i ) {
			$show_desc = true;
		}

		$show_arr[ $name_i ]      = $text_i ;
		$show_arr[ $name_i.'_s' ] = $text_i_s ;

		$arr2[ $i ] = array(
			'lang'   => $this->get_constant( $item_name_i ) ,
			'text'   => $text_i,
			'text_s' => $text_i_s,
		);
	}

	if ( is_array($arr2) && count($arr2) ) {
		$show_arr['texts'] = $arr2;
	}

	$show_arr['show_desc'] = $show_desc;

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; $i++ ) 
	{
		$name_i = 'file_row_'.$i;
		$show_arr[ $name_i ] = $this->get_cached_file_row_by_kind( $row, $i );
	}

	list( $cont_size , $cont_duration ) =
		$this->get_show_file_size_duration( $show_arr, _C_WEBPHOTO_FILE_KIND_CONT ) ;

	$show_arr['cont_size']           = $cont_size ;
	$show_arr['cont_duration']       = $cont_duration ;
	$show_arr['cont_size_disp']      = $this->build_show_filesize( $cont_size ) ;
	$show_arr['cont_duration_disp']  = $this->format_time( $cont_duration ) ;

	return $show_arr;
}

// Get photo's array to assign into template (light version)
function build_photo_show_light( $row, $tag_name_array=null )
{
	$arr1 = $this->build_photo_show_basic( $row, $tag_name_array );
	$arr2 = $this->build_show_imgsrc( $row, $arr1 );

	return array_merge( $arr1, $arr2 );
}

// Get photo's array to assign into template (heavy version)
function build_photo_show( $row )
{
	$tag_name_array = $this->get_tag_name_array_by_photoid( $row['item_id'] );
	$arr1 = $this->build_photo_show_light( $row, $tag_name_array );

	extract( $row ) ;

	list( $is_newphoto, $is_updatedphoto )
		= $this->build_show_is_new_updated( $item_time_update, $item_status );

	$arr2 = array(
		'cat_title_s'      => $this->get_cached_cat_value_by_id( $item_cat_id, 'cat_title', true ),
		'cat_text1_s'      => $this->get_cached_cat_value_by_id( $item_cat_id, 'cat_text1', true ),
		'cat_text2_s'      => $this->get_cached_cat_value_by_id( $item_cat_id, 'cat_text2', true ),
		'cat_text3_s'      => $this->get_cached_cat_value_by_id( $item_cat_id, 'cat_text3', true ),
		'cat_text4_s'      => $this->get_cached_cat_value_by_id( $item_cat_id, 'cat_text4', true ),
		'cat_text5_s'      => $this->get_cached_cat_value_by_id( $item_cat_id, 'cat_text5', true ),

		'info_votes'       => $this->build_show_info_vote( $item_rating, $item_votes ) ,
		'rank'             => $this->build_show_rank( $item_rating ) ,
		'can_edit'         => $this->has_editable_by_uid( $item_uid ) ,

		'is_newphoto'      => $is_newphoto ,
		'is_updatedphoto'  => $is_updatedphoto ,
		'is_popularphoto'  => $this->build_show_is_popularphoto( $item_hits ),
		'taf_target_uri'   => $this->build_show_taf_target_uri( $item_id ),
		'taf_mailto'       => $this->build_show_taf_mailto( $item_id ) ,
		'info_morephotos'  => $this->build_show_info_morephotos( $item_uid ),

		'window_x'         => $arr1['img_photo_width']  + $this->_WINDOW_MERGIN ,
		'window_y'         => $arr1['img_photo_height'] + $this->_WINDOW_MERGIN ,
		
	) ;

	$arr = array_merge( $arr1, $arr2 );
	return $arr;
}

function build_show_filesize( $size )
{
	if ( $size > 0 ) {
		return $this->_utility_class->format_filesize(
			$size, $this->_FILESIZE_PRECISION ) ;
	}
	return null;
}

function build_show_desc_summary( $row, $flag_highlight=false, $keyword_array=null )
{
	$desc = $this->_item_handler->build_show_description_disp( $row );
	$summary= $this->_multibyte_class->build_summary( 
		$desc, $this->_MAX_SUMMARY, $this->_SUMMARY_TAIL, $this->_is_japanese );

	if ( $flag_highlight ) {
		$desc = $this->_highlight_class->build_highlight_keyword_array( $desc, $keyword_array );
	}

	return array($desc, $summary);
}

function build_show_rank( $rating )
{
	return floor( $rating - 0.001 );
}

function build_show_info_vote( $rating, $votes )
{
	if ( $rating > 0 ) {
		if( $votes == 1 ) {
			$votestring = $this->get_constant('ONEVOTE') ;
		} else {
			$votestring = sprintf( $this->get_constant('S_NUMVOTES') , $votes ) ;
		}
		$info_votes  = $this->build_show_rating( $rating );
		$info_votes .= ' ('. $votestring .')';
	} else {
		$info_votes  = $this->build_show_rating( 0 );
		$info_votes .= ' ('.sprintf( $this->get_constant('S_NUMVOTES') , 0 ) . ')' ;
	}
	return $info_votes;
}


function build_show_rating( $rating )
{
	return number_format( $rating , $this->_RATING_DECIMALS ) ;
}

function build_show_is_new_updated( $time_update, $status )
{
	$is_newphoto     = false;
	$is_updatedphoto = false;

	if ( $this->_cfg_newdays && ( $time_update > $this->_time_newdays ) ) {
		if ( $status == 1 ) {
			$is_newphoto = true;
		}
		if ( $status == 2 ) {
			$is_updatedphoto = true;
		}
	}

	return array( $is_newphoto, $is_updatedphoto );
}

function build_show_is_popularphoto( $hits )
{
	if ( $this->_cfg_popular && ( $hits >= $this->_cfg_popular ) ) { 
		return true;
	}
	return false;
}

function build_show_info_morephotos( $uid )
{
	return sprintf( $this->get_constant('S_MOREPHOTOS') , $this->build_show_uname( $uid ) );
}

function build_show_uname( $uid )
{
	return $this->get_xoops_uname_by_uid( $uid, $this->_usereal );
}

function build_show_taf_target_uri( $photo_id )
{
	$str = $this->_INDEX_PHP.'/photo/'. $photo_id .'/subject='. $this->get_constant('SUBJECT4TAF');
	return urlencode( $str );
}

function build_show_taf_mailto( $photo_id )
{
	$subject  = $this->get_constant('SUBJECT4TAF');
	$body     = $this->get_constant('SUBJECT4TAF');
	$body    .= $this->_INDEX_PHP.'/photo/'. $photo_id.'/';

// --- effective only in Japanese environment ---
// convert EUC-JP to SJIS
//	$subject = $this->_lang->convert_telafriend_subject($subject);
//	$body    = $this->_lang->convert_telafriend_body($body);

	$subject = rawurlencode($subject);
	$body    = rawurlencode($body);

	$str = 'subject='. $subject .'&amp;body='. $body;
	return $str;
}

function format_time( $time )
{
	return $this->_utility_class->format_time( $time, 
		$this->get_constant('HOUR'), $this->get_constant('MINUTE'), $this->get_constant('SECOND') ) ;
}

function perm_download( $row )
{
	$perm = $row['item_perm_down'];
	return $this->_item_handler->check_perm( $perm, $this->_xoops_groups );
}

function can_download( $row )
{
	$kind = $row['item_kind'];

	switch ($kind)
	{
		case _C_WEBPHOTO_ITEM_KIND_GENERAL :
		case _C_WEBPHOTO_ITEM_KIND_IMAGE :
		case _C_WEBPHOTO_ITEM_KIND_VIDEO :
		case _C_WEBPHOTO_ITEM_KIND_AUDIO :
			return true;
			break;

		case _C_WEBPHOTO_ITEM_KIND_UNDEFINED :
		case _C_WEBPHOTO_ITEM_KIND_NONE :
		case _C_WEBPHOTO_ITEM_KIND_EMBED :
		case _C_WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL :
		case _C_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE :
		case _C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED :
		case _C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR :
		default :
			break;
	}

	return false;
}

//---------------------------------------------------------
// image
//---------------------------------------------------------
function build_show_imgsrc( $item_row, $show_arr )
{
	$cont_row   = $this->get_show_file_row( $show_arr, _C_WEBPHOTO_FILE_KIND_CONT ) ;
	$thumb_row  = $this->get_show_file_row( $show_arr, _C_WEBPHOTO_FILE_KIND_THUMB ) ;
	$middle_row = $this->get_show_file_row( $show_arr, _C_WEBPHOTO_FILE_KIND_MIDDLE ) ;

	$param = array(
		'item_row'       => $item_row ,
		'cont_row'       => $cont_row ,
		'thumb_row'      => $thumb_row ,
		'middle_row'     => $middle_row ,
		'photo_default'  => true ,
		'thumb_default'  => true ,
		'middle_default' => true ,
	);

	$param_image = $this->_image_class->build_image_by_param( $param );
	if ( ! is_array($param_image) ) {
		return array() ;
	}

	$arr = $param_image ;
	$arr['cont_url_s']        = $this->sanitize( $param_image['cont_url'] ) ;
	$arr['thumb_url_s']       = $this->sanitize( $param_image['thumb_url'] ) ;
	$arr['middle_url_s']      = $this->sanitize( $param_image['middle_url'] ) ;
	$arr['media_url_s']       = $this->sanitize( $param_image['media_url'] ) ;
	$arr['img_photo_src_s']   = $this->sanitize( $param_image['img_photo_src'] ) ;
	$arr['img_middle_src_s']  = $this->sanitize( $param_image['img_middle_src'] ) ;
	$arr['img_thumb_src_s']   = $this->sanitize( $param_image['img_thumb_src'] ) ;
	return $arr ;
}

//---------------------------------------------------------
// file utility
//---------------------------------------------------------
function get_show_file_row( $show_arr, $kind )
{
	return $show_arr[ 'file_row_'. $kind ];
}

function get_show_file_size_duration( $show_arr, $kind )
{
	$size     = 0 ;
	$duration = 0 ;

	$file_row = $this->get_show_file_row( $show_arr, $kind ) ;
	if ( is_array($file_row) ) {
		$size     = $file_row['file_size'] ;
		$duration = $file_row['file_duration'] ;
	}

	return array( $size, $duration );
}

function has_file_url( $url )
{
	if ( $url ) {
		return true ;
	}
	return false ;
}

//---------------------------------------------------------
// tag class
//---------------------------------------------------------
function build_show_tags_from_tag_name_array( $tag_name_array )
{
	return $this->_tag_class->build_show_tags_from_tag_name_array( $tag_name_array );
}

function get_tag_name_array_by_photoid( $photo_id )
{
	return $this->_tag_class->get_tag_name_array_by_photoid( $photo_id );
}

//---------------------------------------------------------
// set
//---------------------------------------------------------
function set_flag_highlight( $val )
{
	$this->_flag_highlight = (bool)$val;
}

function set_keyword_array( $arr )
{
	if ( is_array($arr) ) {
		$this->_keyword_array = $arr;
	}
}

function set_keyword_array_by_get()
{
	$get_keywords = $this->_pathinfo_class->get_text( 'keywords' );
	$this->set_keyword_array( $this->str_to_array( $get_keywords, ' ' ) );
}

// --- class end ---
}

?>