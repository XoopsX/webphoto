<?php
// $Id: item_form.php,v 1.17 2009/05/23 14:57:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-05-17 K.OHWADA
// show_err_invalid_cat()
// 2009-05-05 K.OHWADA
// print_form_playlist() -> print_form_playlist_with_param()
// 2009-04-19 K.OHWADA
// build_form_admin_by_item_row() -> build_form_admin_with_template()
// 2009-03-15 K.OHWADA
// _build_ele_small_file()
// 2009-01-25 K.OHWADA
// print_form_admin() -> print_form_admin_by_files()
// item_content
// 2009-01-10 K.OHWADA
// webphoto_form_this -> webphoto_edit_form
// post variable form_playlist
// 2009-01-04 K.OHWADA
// _init_editor()
// 2008-12-12 K.OHWADA
// build_ele_perm_read()
// 2008-12-07 K.OHWADA
// _build_ele_votes()
// 2008-11-29 K.OHWADA
// _build_ele_time_publish()
// 2008-11-16 K.OHWADA
// BUG: Warning [PHP]: Missing argument 1
// build_ele_codeinfo()
// 2008-11-08 K.OHWADA
// _build_ele_middle_file_external()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_item_form
//=========================================================
class webphoto_admin_item_form extends webphoto_edit_photo_form
{
	var $_sort_class ;

	var $_sort_array = null ;

	var $_THIS_FCT = 'item_manager' ;
	var $_THIS_URL;
	var $_URL_ADMIN_INDEX ;

	var $_PLAYLIST_FEED_SIZE = 80;
	var $_PLAYLIST_TYPE_DEFAULT = _C_WEBPHOTO_PLAYLIST_TYPE_AUDIO ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_item_form( $dirname, $trust_dirname )
{
	$this->webphoto_edit_photo_form( $dirname, $trust_dirname );

	$this->_sort_class =& webphoto_photo_sort::getInstance( $dirname, $trust_dirname );
	$this->_sort_array = $this->_sort_class->photo_sort_array_admin();
	$this->_sort_class->set_photo_sort_array( $this->_sort_array );

	$this->_show_delete_button = true;

	$this->_URL_ADMIN_INDEX = $this->_MODULE_URL .'/admin/index.php';
	$this->_THIS_URL        = $this->_MODULE_URL .'/admin/index.php?fct='. $this->_THIS_FCT ;

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
// build submit edit form
//---------------------------------------------------------
function build_form_admin_with_template( $mode, $item_row )
{
	$template = 'db:'. $this->_DIRNAME .'_form_admin_item.html';

	$arr = array_merge( 
		$this->build_form_base_param(),
		$this->build_form_admin_by_item_row( $mode, $item_row ),
		$this->build_item_row( $item_row ) ,
		$this->build_admin_language()
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	return $tpl->fetch( $template ) ;
}

function build_form_admin_by_item_row( $mode, $item_row )
{
	$cont_row   = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_CONT ) ; 
	$thumb_row  = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_THUMB ) ; 
	$middle_row = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_MIDDLE ) ; 
	$small_row  = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_SMALL ) ; 
	$flash_row  = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH );
	$pdf_row    = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_PDF );
	$swf_row    = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_SWF );

// for futue
//	$docomo_row = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_DOCOMO );
	$docomo_row = null ;

	$files = array(
		'item_row'   => $item_row , 
		'cont_row'   => $cont_row , 
		'thumb_row'  => $thumb_row , 
		'middle_row' => $middle_row , 
		'small_row'  => $small_row , 
		'flash_row'  => $flash_row ,
		'docomo_row' => $docomo_row ,
		'pdf_row'    => $pdf_row ,
		'swf_row'    => $swf_row ,
	);

	return $this->build_form_admin_by_files( $mode, $files );
}

function build_form_admin_by_files( $mode, $files )
{
	$item_row      = $files['item_row']; 
	$cont_row      = $files['cont_row']; 
	$thumb_row     = $files['thumb_row']; 
	$middle_row    = $files['middle_row']; 
	$small_row     = $files['small_row']; 
	$flash_row     = $files['flash_row']; 
	$docomo_row    = $files['docomo_row']; 
	$pdf_row       = $files['pdf_row']; 
	$swf_row       = $files['swf_row']; 

	$preview_name   = $this->_preview_name ;
	$tag_name_array = $this->_tag_name_array ;
	$rotate         = $this->_rotate ;

	$has_resize     = $this->_has_image_resize ;
	$has_rotate     = $this->_has_image_rotate ;
	$allowed_exts   = $this->_allowed_exts ;
	$max_photo_file = $this->_MAX_PHOTO_FILE ;

	$this->_xoops_db_groups = $this->get_cached_xoops_db_groups();

	$is_submit  = false ;
	$is_edit    = false ;

	switch ($mode)
	{
		case 'admin_modify':
			$is_edit = true;
			$op      = 'modify';
			$submit  = _EDIT;
			break;

		case 'admin_submit':
		default:
			$is_submit = true;
			$op        = 'submit';
			$submit    = _ADD;
			break;
	}

	$this->set_row( $item_row );
	$this->init_editor();

	list ( $show_item_embed_type, $show_item_embed_text, $show_item_embed_src )
		= $this->show_item_embed();

	list ( $show_thumb_dsc_select, $show_thumb_dsc_embed )
		= $this->show_thumb_dsc();

	list( $photo_url, $show_file_photo_delete ) 
		= $this->build_file_url( _C_WEBPHOTO_FILE_KIND_CONT, 'item_external_url' );

	list( $thumb_url, $show_file_thumb_delete ) 
		= $this->build_file_url( _C_WEBPHOTO_FILE_KIND_THUMB, 'item_external_thumb' );

	list( $middle_url, $show_file_middle_delete ) 
		= $this->build_file_url( _C_WEBPHOTO_FILE_KIND_MIDDLE, 'item_external_middle' );

	list( $small_url, $show_file_small_delete ) 
		= $this->build_file_url( _C_WEBPHOTO_FILE_KIND_SMALL, '' );

	$show_item_kind  = $this->show_item_kind( $is_edit ) ;

	list( $show_item_playlist_type, $show_item_playlist_time, $show_item_playlist_feed, $show_item_playlist_dir )
		= $this->show_item_playlist();

	$param = array( 
		'op_edit'         => $op ,
		'preview_name'    => $preview_name ,
		'is_submit'       => $is_submit ,
		'is_edit'         => $is_edit ,
		'max_file_size'   => $this->_cfg_fsize ,
		'has_rotate'      => $has_rotate ,

		'show_desc_options'        => $this->_editor_show ,
		'show_desc_options_hidden' => ! $this->_editor_show ,
//		'show_item_embed_type'        => $show_item_embed_type ,
//		'show_item_embed_text'        => $show_item_embed_text ,
//		'show_item_embed_src'         => $show_item_embed_src ,
//		'show_item_embed_type_hidden' => ! $show_item_embed_type ,
//		'show_item_embed_text_hidden' => ! $show_item_embed_text ,
//		'show_item_embed_src_hidden'  => ! $show_item_embed_src ,
//		'show_item_siteurl_1st'       => $show_item_embed_text ,
//		'show_item_siteurl_2nd'       => ! $show_item_embed_text ,
		'show_item_perm_read'      => $this->show_item_perm_read() ,
//		'show_file_photo'          => $this->is_upload_type(),
		'show_gmap'                => $this->show_gmap() ,
		'show_thumb_dsc_select'    => $show_thumb_dsc_select ,
		'show_thumb_dsc_embed'     => $show_thumb_dsc_embed ,
		'show_file_photo_delete'   => $show_file_photo_delete ,
		'show_file_thumb_delete'   => $show_file_thumb_delete ,
		'show_file_middle_delete'  => $show_file_middle_delete ,
		'show_file_small_delete'   => $show_file_small_delete ,

		'ele_maxpixel'         => $this->ele_maxpixel( $has_resize ) ,
		'ele_maxsize'          => $this->ele_maxsize() ,
		'ele_allowed_exts'     => $this->ele_allowed_exts( $allowed_exts ) ,
		'ele_item_description' => $this->_editor_desc ,

		'item_uid_options'               => $this->item_uid_options() ,
		'item_cat_id_options'            => $this->item_cat_id_options() ,
		'item_gicon_id_select_options'   => $this->item_gicon_id_select_options() ,
		'item_codeinfo_select_options'   => $this->item_codeinfo_select_options() ,
		'item_perm_read_input_checkboxs' => $this->item_perm_read_input_checkboxs() ,
		'item_perm_down_input_checkboxs' => $this->item_perm_down_input_checkboxs() ,

		'item_text_array'     => $this->item_text_array() ,
		'item_file_array'     => $this->item_file_array( $is_edit ) ,
		'item_datetime_val_s' => $this->item_datetime_val_s() ,

		'item_description_html_checked'   => $this->build_row_checked( 'item_description_html' ),
		'item_description_smiley_checked' => $this->build_row_checked( 'item_description_smiley' ),
		'item_description_xcode_checked'  => $this->build_row_checked( 'item_description_xcode' ),
		'item_description_image_checked'  => $this->build_row_checked( 'item_description_image' ),
		'item_description_br_checked'     => $this->build_row_checked( 'item_description_br' ),
		'item_datetime_checkbox_checked'  => $this->build_checkbox_checked( 'item_datetime_checkbox' ) ,
		'rotate_checked'                  => $this->rotate_checked( $rotate ) ,

		'photo_url_s'   => $this->sanitize( $photo_url ), 
		'thumb_url_s'   => $this->sanitize( $thumb_url ), 
		'middle_url_s'  => $this->sanitize( $middle_url ), 
		'small_url_s'   => $this->sanitize( $small_url ), 
		'tags_val_s'    => $this->tags_val_s( $tag_name_array ) ,
		'embed_src_dsc' => $this->embed_src_dsc() ,
		'editor_js'     => $this->_editor_js ,

		'value_submit' => $submit ,

// for admin
		'show_file_photo'          => $this->show_admin_file_photo(),
		'show_valid'               => $this->show_valid(),
		'show_item_kind'           => $show_item_kind ,
		'show_item_kind_hidden'    => ! $show_item_kind ,
		'show_item_embed'          => $show_item_embed_type ,
		'show_item_embed_hidden'   => ! $show_item_embed_type ,
		'show_item_playlist_type'  => $show_item_playlist_type ,
		'show_item_playlist_time'  => $show_item_playlist_time ,
		'show_item_playlist_feed'  => $show_item_playlist_feed ,
		'show_item_playlist_dir'   => $show_item_playlist_dir ,
		'show_item_playlist_type_hidden' => ! $show_item_playlist_type ,
		'show_item_playlist_time_hidden' => ! $show_item_playlist_time ,
		'show_item_playlist_feed_hidden' => ! $show_item_playlist_feed ,
		'show_item_playlist_dir_hidden'  => ! $show_item_playlist_dir ,

		'time_now'               => $this->time_now() ,
		'item_time_create_disp'  => $this->build_time_disp( 'item_time_create',  true ) ,
		'item_time_update_disp'  => $this->build_time_disp( 'item_time_update',  true ) ,
		'item_time_publish_disp' => $this->build_time_disp( 'item_time_publish', false ) ,
		'item_time_expire_disp'  => $this->build_time_disp( 'item_time_expire',  false ) ,

		'item_time_update_checkbox_checked'  => $this->build_checkbox_checked( 'item_time_update_checkbox' ), 
		'item_time_publish_checkbox_checked' => $this->build_checkbox_checked( 'item_time_publish_checkbox' ), 
		'item_time_expire_checkbox_checked'  => $this->build_checkbox_checked( 'item_time_update_checkbox' ), 

		'item_status_select_options'        => $this->item_status_select_options(),
		'item_kind_select_options'          => $this->item_kind_select_options(),
		'item_displaytype_select_options'   => $this->item_displaytype_select_options(),
		'item_onclick_select_options'       => $this->item_onclick_select_options(),
		'item_player_id_select_options'     => $this->item_player_id_select_options(),
		'item_playlist_type_select_options' => $this->item_playlist_type_select_options(),
		'item_playlist_dir_select_options'  => $this->item_playlist_dir_select_options(),
		'item_playlist_time_select_options' => $this->item_playlist_time_select_options(),

		'show_err_invalid_cat'              => $this->show_err_invalid_cat() ,

	);

	return $param ;
}

function show_item_kind( $is_edit )
{
	if ( $is_edit || $this->is_playlist_type() ) {
		return true;
	}
	return false;
}

function show_valid()
{
	$value = $this->get_row_by_key( 'item_status' );
	if ( $value == _C_WEBPHOTO_STATUS_WAITING ) {
		return true;
	}
	return false;
}

function show_item_playlist()
{
	$show_type = false;
	$show_time = false;
	$show_feed = false;
	$show_dir  = false;

	if ( $this->is_playlist_type() ) {
		$show_type = true;
		$show_time = true;
		if ( $this->is_playlist_feed_kind() ) {
			$show_feed = true;
		} elseif ( $this->is_playlist_dir_kind() ) {
			$show_dir = true;
		}
	}

	return array( $show_type, $show_time, $show_feed, $show_dir );
}

function show_admin_file_photo()
{
	if ( $this->is_embed_type() ) {
		return false;
	}
	if ( $this->is_playlist_type() ) {
		return false;
	}
	return true;
}

function item_status_select_options()
{
	$value = $this->get_row_by_key( 'item_status' );
	$options = $this->_item_handler->get_status_options();
	return $this->build_form_options( $value, $options );
}

function item_kind_select_options()
{
	$name    = 'item_kind' ;
	$value   = $this->get_row_by_key( 'item_kind' ) ; 
	$options = $this->_item_handler->get_kind_options();
	return $this->build_form_options( $value, $options );
}

function item_displaytype_select_options()
{
	$value   = $this->get_row_by_key( 'item_displaytype' ) ; 
	$options = $this->_item_handler->get_displaytype_options();
	return $this->build_form_options( $value, $options );
}

function item_onclick_select_options()
{
	$value   = $this->get_row_by_key( 'item_onclick' ) ; 
	$options = $this->_item_handler->get_onclick_options();
	return $this->build_form_options( $value, $options );
}

function item_player_id_select_options()
{
	$value = $this->get_row_by_key( 'item_player_id' );
	return $this->_player_handler->build_row_options( $value );
}

function item_playlist_type_select_options()
{
	$value   = $this->get_item_playlist_type( true ) ; 
	$options = $this->_item_handler->get_playlist_type_options();
	return $this->build_form_options( $value, $options );
}

function item_playlist_dir_select_options()
{
	$value   = $this->get_row_by_key( 'item_playlist_dir' );
	$options = $this->_utility_class->get_dirs_in_dir( $this->_MEDIAS_DIR, false, true, true );
	if ( !is_array($options) || !count($options) ) {
		return null;
	}
	return $this->build_form_options( $value, $options );
}

function item_playlist_time_select_options()
{
	$value   = $this->get_row_by_key( 'item_playlist_time' ) ; 
	$options = $this->_item_handler->get_playlist_time_options();
	return $this->build_form_options( $value, $options );
}

function get_item_playlist_type( $flag )
{
	$value = $this->get_row_by_key( 'item_playlist_type' );
	if ( $flag && empty($value) ) {
		$value = $this->_PLAYLIST_TYPE_DEFAULT ;
	}
	return $value;
}

function is_playlist_type()
{
	$kind = $this->get_row_by_key( 'item_kind' );
	if ( $this->is_playlist_feed_kind() ) {
		return true;
	}
	if ( $this->is_playlist_dir_kind() ) {
		return true;
	}
	return false;
}

function is_playlist_feed_kind()
{
	$kind = $this->get_row_by_key( 'item_kind' );
	if ( $this->_kind_class->is_playlist_feed_kind( $kind ) ) {
		return true;
	}
	return false ;
}

function is_playlist_dir_kind()
{
	$kind = $this->get_row_by_key( 'item_kind' );
	if ( $this->_kind_class->is_playlist_dir_kind( $kind ) ) {
		return true;
	}
	return false ;
}

function build_admin_language()
{
	$arr = array(
		'lang_playlist_feed_dsc' => _AM_WEBPHOTO_PLAYLIST_FEED_DSC ,
		'lang_playlist_dir_dsc'  => _AM_WEBPHOTO_PLAYLIST_DIR_DSC ,
		'lang_time_now'          => _AM_WEBPHOTO_TIME_NOW ,
		'lang_vote_stats'        => _AM_WEBPHOTO_VOTE_STATS ,
	);
	return $arr;
}

//---------------------------------------------------------
// playlist
//---------------------------------------------------------
function print_form_playlist( $mode, $item_row )
{
	if ( ! $this->is_show_form_admin( $item_row ) ) {
		return;
	}

	$this->print_form_playlist_with_param( 
		$item_row, $this->build_form_select_param( $mode ) );
}

function print_form_playlist_with_param( $item_row, $param )
{
	$mode        = $param['mode'];
	$form_embed  = $param['form_embed'];
	$form_editor = $param['form_editor'];

	switch ($mode)
	{
		case 'admin_submit':
		default:
			$url = $this->_URL_ADMIN_INDEX ;
			$fct = $this->_THIS_FCT ;
			break;
	}

	$this->set_row( $item_row );

	echo $this->build_form_tag( 'playlist', $url );
	echo $this->build_html_token();

	echo $this->build_input_hidden( 'fct',   $fct );
	echo $this->build_input_hidden( 'op',   'submit_form' );
	echo $this->build_input_hidden( 'form_playlist', 1 );
	echo $this->build_input_hidden( 'form_embed',    $form_embed );
	echo $this->build_input_hidden( 'form_editor',   $form_editor );

	echo $this->build_row_hidden( 'item_editor' );

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
	$value   = $this->get_item_embed_type( false );
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