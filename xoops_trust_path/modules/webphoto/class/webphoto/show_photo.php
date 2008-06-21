<?php
// $Id: show_photo.php,v 1.2 2008/06/21 17:20:29 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

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

	var $_time_newdays;
	var $_usereal;

	var $_flag_highlight = false;
	var $_keyword_array  = null;

	var $_URL_DEFAULT_IMAGE;
	var $_URL_PIXEL_IMAGE;
	var $_URL_CATEGORY_IMAGE;

	var $_WINDOW_MERGIN = 16;
	var $_MAX_SUMMARY   = 100;
	var $_SUMMARY_TAIL  = ' ...';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_show_photo( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );

	$this->_image_class =& webphoto_image_info::getInstance( $dirname, $trust_dirname );

	$this->_tag_class =& webphoto_tag::getInstance( $dirname );
	$this->_tag_class->set_is_japanese( $this->_is_japanese );

	$this->_highlight_class =& webphoto_lib_highlight::getInstance();
	$this->_highlight_class->set_replace_callback( 'webphoto_highlighter_by_class' );
	$this->_highlight_class->set_class( 'webphoto_highlight' );

	$this->_multibyte_class =& webphoto_lib_multibyte::getInstance();
	$this->_multibyte_class->set_is_japanese( $this->_is_japanese );
	$this->_multibyte_class->set_ja_kuten(   _WEBPHOTO_JA_KUTEN );
	$this->_multibyte_class->set_ja_dokuten( _WEBPHOTO_JA_DOKUTEN );
	$this->_multibyte_class->set_ja_period(  _WEBPHOTO_JA_PERIOD );
	$this->_multibyte_class->set_ja_comma(   _WEBPHOTO_JA_COMMA );

	$this->_cfg_newdays     = $this->get_config_by_name('newdays');
	$this->_cfg_popular     = $this->get_config_by_name('popular');
	$this->_cfg_nameoruname = $this->get_config_by_name('nameoruname');

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

	$desc_disp = $this->build_show_desc_disp( 
		$row, $this->_flag_highlight, $this->_keyword_array ) ;

	$datetime_disp = $this->mysql_datetime_to_str( $photo_datetime );

	$arr = array(
		'photo_id'       => $photo_id ,
		'time_cretae'    => $photo_time_create,
		'time_update'    => $photo_time_update,
		'cat_id'         => $photo_cat_id ,
		'uid'            => $photo_uid ,
		'datetime'       => $photo_datetime,
		'title'          => $photo_title ,
		'place'          => $photo_place ,
		'equipment'      => $photo_equipment ,
		'file_url'       => $photo_file_url,
		'file_path'      => $photo_file_path ,
		'file_name'      => $photo_file_name ,
		'file_ext'       => $photo_file_ext ,
		'file_mime'      => $photo_file_mime ,
		'file_medium'    => $photo_file_medium ,
		'file_size'      => $photo_file_size ,
		'cont_url'       => $photo_cont_url,
		'cont_path'      => $photo_cont_path,
		'cont_name'      => $photo_cont_name,
		'cont_ext'       => $photo_cont_ext,
		'cont_mime'      => $photo_cont_mime,
		'cont_medium'    => $photo_cont_medium,
		'cont_ext'       => $photo_cont_ext,
		'cont_size'      => $photo_cont_size,
		'cont_width'     => $photo_cont_width,
		'cont_height'    => $photo_cont_height,
		'cont_duration'  => $photo_cont_duration,
		'cont_exif'      => $photo_cont_exif,
		'middle_width'   => $photo_middle_width,
		'middle_height'  => $photo_middle_height,
		'thumb_url'      => $photo_thumb_url,
		'thumb_path'     => $photo_thumb_path,
		'thumb_name'     => $photo_thumb_name,
		'thumb_ext'      => $photo_thumb_ext,
		'thumb_mime'     => $photo_thumb_mime,
		'thumb_medium'   => $photo_thumb_medium,
		'thumb_size'     => $photo_thumb_size,
		'thumb_width'    => $photo_thumb_width,
		'thumb_height'   => $photo_thumb_height,
		'gmap_latitude'  => $photo_gmap_latitude,
		'gmap_longitude' => $photo_gmap_longitude,
		'gmap_zoom'      => $photo_gmap_zoom,
		'status'         => $photo_status ,
		'hits'           => $photo_hits ,
		'rating'         => $photo_rating ,
		'votes'          => $photo_votes ,
		'comments'       => $photo_comments ,
		'description'    => $photo_description,
		'search'         => $photo_search,

		'title_s'        => $this->sanitize( $photo_title ) ,
		'place_s'        => $this->sanitize( $photo_place ) ,
		'equipment_s'    => $this->sanitize( $photo_equipment ) ,
		'file_url_s'     => $this->sanitize( $photo_file_url ),
		'cont_url_s'     => $this->sanitize( $photo_cont_url ),
		'thumb_ur_sl'    => $this->sanitize( $photo_thumb_url ),		
		'uname_s'        => $this->build_show_uname( $photo_uid ),

		'time_update_m'       => formatTimestamp( $photo_time_update , 'm' ) ,
		'datetime_disp'       => $datetime_disp ,
		'datetime_urlencode'  => $this->url_encode( $datetime_disp ) ,
		'place_urlencode'     => $this->url_encode( $photo_place ),
		'equipment_urlencode' => $this->url_encode( $photo_equipment ),
		'description_disp'    => $desc_disp ,
		'cont_exif_disp'      => $this->_photo_handler->build_show_cont_exif_disp( $row ) ,

		'tags'           => $this->build_show_tags_from_tag_name_array( $tag_name_array ),
		'is_owner'       => $this->is_photo_owner( $photo_uid ),

	);

	$show_desc = false;

	if ( $desc_disp ) {
		$show_desc = true;
	}

	$arr2 = array();
	for ( $i=1; $i <= _C_WEBPHOTO_MAX_PHOTO_TEXT; $i++ ) 
	{
		$name_i       = 'text'.$i;
		$photo_name_i = 'photo_'.$name_i;
		$text_i   = $row[ $photo_name_i ];
		$text_i_s =  $this->sanitize( $text_i );

		if ( $text_i ) {
			$show_desc = true;
		}

		$arr[ $name_i ]      = $text_i ;
		$arr[ $name_i.'_s' ] = $text_i_s ;

		$arr2[ $i ] = array(
			'lang'   => $this->get_constant( $photo_name_i ) ,
			'text'   => $text_i,
			'text_s' => $text_i_s,
		);
	}

	if ( is_array($arr2) && count($arr2) ) {
		$arr['texts'] = $arr2;
	}

	$arr['show_desc'] = $show_desc;

	return $arr;
}

// Get photo's array to assign into template (light version)
function build_photo_show_light( $row, $tag_name_array=null )
{
	$arr1 = $this->build_photo_show_basic( $row, $tag_name_array );
	$arr2 = $this->build_show_imgsrc( $row );

	return array_merge( $arr1, $arr2 );
}

// Get photo's array to assign into template (heavy version)
function build_photo_show( $row )
{
	$tag_name_array = $this->get_tag_name_array_by_photoid( $row['photo_id'] );
	$arr1 = $this->build_photo_show_light( $row, $tag_name_array );

	extract( $row ) ;

	list( $is_newphoto, $is_updatedphoto )
		= $this->build_show_is_new_updated( $photo_time_update, $photo_status );

	$arr2 = array(
		'cat_title_s'      => $this->get_cached_cat_value_by_id( $photo_cat_id, 'cat_title', true ),
		'cat_text1_s'      => $this->get_cached_cat_value_by_id( $photo_cat_id, 'cat_text1', true ),
		'cat_text2_s'      => $this->get_cached_cat_value_by_id( $photo_cat_id, 'cat_text2', true ),
		'cat_text3_s'      => $this->get_cached_cat_value_by_id( $photo_cat_id, 'cat_text3', true ),
		'cat_text4_s'      => $this->get_cached_cat_value_by_id( $photo_cat_id, 'cat_text4', true ),
		'cat_text5_s'      => $this->get_cached_cat_value_by_id( $photo_cat_id, 'cat_text5', true ),

		'summry'            => $this->build_show_summary( $photo_description ) ,
		'info_votes'        => $this->build_show_info_vote( $photo_rating, $photo_votes ) ,
		'rank'              => $this->build_show_rank( $photo_rating ) ,
		'can_edit'          => $this->has_editable_by_uid( $photo_uid ) ,

		'is_newphoto'      => $is_newphoto ,
		'is_updatedphoto'  => $is_updatedphoto ,
		'is_popularphoto'  => $this->build_show_is_popularphoto( $photo_hits ),
		'taf_target_uri'   => $this->build_show_taf_target_uri( $photo_id ),
		'taf_mailto'       => $this->build_show_taf_mailto( $photo_id ) ,
		'info_morephotos'  => $this->build_show_info_morephotos( $photo_uid ),

		'window_x'         => $arr1['photo_width']  + $this->_WINDOW_MERGIN ,
		'window_y'         => $arr1['photo_height'] + $this->_WINDOW_MERGIN ,
	) ;

	return array_merge( $arr1, $arr2 );
}

function build_show_desc_disp( $row, $flag_highlight=false, $keyword_array=null )
{
	$text = $this->_photo_handler->build_show_description_disp( $row );
	if ( $flag_highlight ) {
		$text = $this->_highlight_class->build_highlight_keyword_array( $text, $keyword_array );
	}
	return $text;
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
		$info_votes = number_format( $rating , 2 ).' ('. $votestring .')';
	} else {
		$info_votes = '0.00 ('.sprintf( $this->get_constant('S_NUMVOTES') , 0 ) . ')' ;
	}
	return $info_votes;
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

function build_show_is_owner( $uid )
{
	if ( $this->_xoops_uid == $uid || $this->_is_module_admin ) {
		return true;
	}
	return false;
}

function url_encode( $str )
{
	return rawurlencode( $this->encode_slash( $str ) );
}

function encode_slash( $str )
{
	return $this->_utility_class->encode_slash( $str );
}

//---------------------------------------------------------
// image
//---------------------------------------------------------
function build_show_imgsrc( $row )
{
	extract( $row ) ;

	$ahref_file   = '';
	$imgsrc_photo = '';
	$imgsrc_thumb = '';
	$is_normal_image = false ;

	$photo_file_url_s  = $this->sanitize( $photo_file_url );
	$photo_cont_url_s  = $this->sanitize( $photo_cont_url );
	$photo_thumb_url_s = $this->sanitize( $photo_thumb_url );
	$photo_width       = intval($photo_cont_width);
	$photo_height      = intval($photo_cont_height);
	$middle_width      = intval($photo_middle_width);
	$middle_height     = intval($photo_middle_height);
	$thumb_width       = intval($photo_thumb_width);
	$thumb_height      = intval($photo_thumb_height);

// normal exts
	if ( $photo_cont_url_s && $photo_thumb_url_s ) {
		$ahref_file   = $photo_cont_url_s;
		$imgsrc_photo = $photo_cont_url_s;
		$imgsrc_thumb = $photo_thumb_url_s;

// no thumbnail
	} elseif ( $photo_cont_url_s ) {
		$ahref_file   = $photo_cont_url_s;
		$imgsrc_photo = $photo_cont_url_s;
		$imgsrc_thumb = $photo_cont_url_s;

		if ( $this->is_normal_ext( $photo_cont_ext ) ) {
			list( $thumb_width, $thumb_height )
				= $this->_image_class->adjust_thumb_size( $photo_width, $photo_height );
		}

// icon gif (not normal exts)
	} elseif ( $photo_thumb_url_s ) {
		$ahref_file   = $photo_file_url_s;
		$imgsrc_photo = $photo_thumb_url_s;
		$imgsrc_thumb = $photo_thumb_url_s;

	} else {
		$ahref_file   = $photo_file_url_s;
		$imgsrc_photo = $this->_URL_DEFAULT_IMAGE;
		$imgsrc_thumb = $this->_URL_PIXEL_IMAGE;
		$thumb_width  = 1;
		$thumb_height = 1;
	}

	if ( $photo_cont_url_s && $this->is_normal_ext($photo_cont_ext) ) {
		$is_normal_image = true ;
	}

	$arr = array(
		'ahref_file'       => $ahref_file ,
		'imgsrc_thumb'     => $imgsrc_thumb ,
		'imgsrc_photo'     => $imgsrc_photo ,
		'photo_width'      => $photo_width ,
		'photo_height'     => $photo_height ,
		'middle_width'     => $middle_width ,
		'middle_height'    => $middle_height ,
		'thumb_width'      => $thumb_width ,
		'thumb_height'     => $thumb_height ,
		'is_normal_image'  => $is_normal_image ,
	);
	return $arr;

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
// multibyte class
//---------------------------------------------------------
function build_show_summary( $str )
{
	return $this->_multibyte_class->build_summary( 
		$str, $this->_MAX_SUMMARY, $this->_SUMMARY_TAIL );
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