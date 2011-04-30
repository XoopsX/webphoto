<?php
// $Id: item_form.php,v 1.33 2011/04/30 23:30:20 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// Fatal error: Call to undefined method get_cached_file_row_by_kind()
// 2010-11-11 K.OHWADA
// show_item_icon_name()
// 2010-10-01 K.OHWADA
// item_uid_options()
// item_displayfile_select_options()
// 2010-05-08 K.OHWADA
// show_admin_gmap()
// 2010-02-15 K.OHWADA
// print_list_table()
// 2010-01-10 K.OHWADA
// init_for_admin()
// 2009-12-06 K.OHWADA
// item_perm_level
// build_form_common()
// 2009-11-11 K.OHWADA
// item_detail_onclick
// 2009-10-25 K.OHWADA
// BUG: player id is not correctly selected 
// 2009-06-28 K.OHWADA
// set_default_item_row()
// 2009-05-30 K.OHWADA
// $is_edit in show_err_invalid_cat()
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

	var $_THIS_FCT = 'item_manager' ;
	var $_THIS_URL;
	var $_URL_ADMIN_INDEX ;

	var $_PLAYLIST_FEED_SIZE = 80;
	var $_PLAYLIST_TYPE_DEFAULT = _C_WEBPHOTO_PLAYLIST_TYPE_AUDIO ;

	var $_CAP_ARRAY = array(
		'maxpixel','maxsize','allowed_exts',
		'desc_options','rotate','tags','text','file',
		'file_photo','file_jpeg'
	);

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_item_form( $dirname, $trust_dirname )
{
	$this->webphoto_edit_photo_form( $dirname, $trust_dirname );

	$this->_sort_class 
		=& webphoto_photo_sort::getInstance( $dirname, $trust_dirname );

	$this->_sort_class->init_for_admin();

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

	$item_row = $this->set_default_item_row( $item_row );

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
	$cont_row   = $this->get_cached_file_extend_row_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_CONT ) ; 
	$jpeg_row   = $this->get_cached_file_extend_row_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_JPEG ) ; 
	$flash_row  = $this->get_cached_file_extend_row_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH );
	$pdf_row    = $this->get_cached_file_extend_row_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_PDF );
	$swf_row    = $this->get_cached_file_extend_row_by_kind( 
		$item_row, _C_WEBPHOTO_FILE_KIND_SWF );

// for futue
//	$docomo_row = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_DOCOMO );
	$docomo_row = null ;

	$files = array(
		'item_row'   => $item_row , 
		'cont_row'   => $cont_row , 
		'jpeg_row'   => $jpeg_row , 
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
	$jpeg_row      = $files['jpeg_row']; 
	$flash_row     = $files['flash_row']; 
	$docomo_row    = $files['docomo_row']; 
	$pdf_row       = $files['pdf_row']; 
	$swf_row       = $files['swf_row']; 

	$allowed_exts   = $this->_allowed_exts ;
	$max_photo_file = $this->_MAX_PHOTO_FILE ;
	$delete         = _DELETE ;

	$userstart = $this->_post_class->get_get('userstart'); 

	$this->_xoops_db_groups = $this->get_cached_xoops_db_groups();

	$is_submit  = false ;
	$is_edit    = false ;

	switch ($mode)
	{
		case 'admin_modify':
			$is_edit = true;
			$op_form = 'modify_form';
			$op_edit = 'modify';
			$submit  = _EDIT;
			break;

		case 'admin_submit':
		default:
			$is_submit = true;
			$op_form   = 'submit_form';
			$op_edit  = 'submit';
			$submit    = _ADD;
			break;
	}

	$this->set_flag_admin( true );
	$this->set_row( $item_row );
	$this->init_editor();

	$show_valid = $this->show_valid();
	if ( $show_valid ) {
		$submit .= ' ('. _AM_WEBPHOTO_BUTTON_ADMIT .') ';
		$delete .= ' ('. _AM_WEBPHOTO_BUTTON_REFUSE .') ';
	}

	list ( $show_item_embed_type, $show_item_embed_text, $show_item_embed_src )
		= $this->show_item_embed();

	$show_item_kind  = $this->show_item_kind( $is_edit ) ;
	$show_file_photo = $this->show_admin_file_photo() ;

	list( $show_item_playlist_type, $show_item_playlist_time, 
	      $show_item_playlist_feed, $show_item_playlist_dir )
		= $this->show_item_playlist();

	list( $show_item_uid_list, $item_uid_list, $item_uid_options )
		= $this->item_user_param( $userstart );

	$arr1 = $this->build_form_common( $is_edit );

	$arr2 = array( 
		'op_form'         => $op_form ,
		'op_edit'         => $op_edit ,
		'is_submit'       => $is_submit ,
		'is_edit'         => $is_edit ,
		'max_file_size'   => $this->_cfg_fsize ,

		'button_submit'      => $submit ,
		'button_conf_delete' => $delete ,

// for admin
		'cap_style'                => $this->cap_style(),
		'show_file_photo'          => $show_file_photo,
		'show_rotate'              => $this->show_rotate( $show_file_photo ) ,
		'show_valid'               => $this->show_valid(),
		'show_gmap'                => $this->show_admin_gmap(),
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

		'show_item_icon_name'          => $this->show_item_icon_name() ,

		'time_now'               => $this->time_now() ,
		'item_time_create_disp'  => $this->build_time_disp( 'item_time_create',  true ) ,
		'item_time_publish_disp' => $this->build_time_disp( 'item_time_publish', false ) ,
		'item_time_expire_disp'  => $this->build_time_disp( 'item_time_expire',  false ) ,

		'item_time_update_checkbox_checked'  => $this->build_checkbox_checked( 'item_time_update_checkbox' ), 
		'item_time_publish_checkbox_checked' => $this->build_checkbox_checked( 'item_time_publish_checkbox' ), 
		'item_time_expire_checkbox_checked'  => $this->build_checkbox_checked( 'item_time_update_checkbox' ), 

		'item_status_select_options'         => $this->item_status_select_options(),
		'item_kind_select_options'           => $this->item_kind_select_options(),
		'item_displaytype_select_options'    => $this->item_displaytype_select_options(),
		'item_displayfile_select_options'    => $this->item_displayfile_select_options(),
		'item_onclick_select_options'        => $this->item_onclick_select_options(),
		'item_detail_onclick_select_options' => $this->item_detail_onclick_select_options(),
		'item_player_id_select_options'      => $this->item_player_id_select_options(),
		'item_playlist_type_select_options'  => $this->item_playlist_type_select_options(),
		'item_playlist_dir_select_options'   => $this->item_playlist_dir_select_options(),
		'item_playlist_time_select_options'  => $this->item_playlist_time_select_options(),
		'item_embed_type_select_options'     => $this->item_embed_type_select_options(),
		'show_err_invalid_cat'               => $this->show_err_invalid_cat( $is_edit ) ,

		'show_item_uid_list' => $show_item_uid_list ,
		'item_uid_list'      => $item_uid_list ,
		'item_uid_options'   => $item_uid_options ,

	);

	return array_merge( $arr1, $arr2 );
}

function cap_style()
{
	$arr = array();

	$style_default   = $this->get_ini('style_cap_default');
	$style_highlight = $this->get_ini('style_cap_highlight');
	$highlight_array = $this->explode_ini('item_manager_highlight_list');

	$row = $this->get_row();
	foreach ( $row as $k => $v ) {
		$arr[ $k ] = $style_default;
	}
	foreach ( $this->_CAP_ARRAY as $k ) {
		$arr[ $k ] = $style_default;
	}
	foreach ( $highlight_array as $k ) {
		$arr[ $k ] = $style_highlight;
	}
	return $arr;
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

// BUG: NOT show gmap
function show_admin_gmap()
{
	if ( $this->_cfg_gmap_apikey ) {
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

function item_displayfile_select_options()
{
	$value   = $this->get_row_by_key( 'item_displayfile' ) ; 
	$options = $this->_item_handler->get_displayfile_options();
	return $this->build_form_options( $value, $options );
}

function item_onclick_select_options()
{
	$value   = $this->get_row_by_key( 'item_onclick' ) ; 
	$options = $this->_item_handler->get_onclick_options();
	return $this->build_form_options( $value, $options );
}

function item_detail_onclick_select_options()
{
	$value   = $this->get_row_by_key( 'item_detail_onclick' ) ; 
	$options = $this->_item_handler->get_detail_onclick_options();
	return $this->build_form_options( $value, $options );
}

function item_player_id_select_options()
{
// BUG: player id is not correctly selected 
	$value = $this->get_row_by_key( 'item_player_id' );
	return $this->_player_handler->build_row_options( $value, true );
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
// form
		'lang_playlist_feed_dsc' => _AM_WEBPHOTO_PLAYLIST_FEED_DSC ,
		'lang_playlist_dir_dsc'  => _AM_WEBPHOTO_PLAYLIST_DIR_DSC ,
		'lang_time_now'          => _AM_WEBPHOTO_TIME_NOW ,
		'lang_vote_stats'        => _AM_WEBPHOTO_VOTE_STATS ,

// list
		'lang_item_listing' => _AM_WEBPHOTO_ITEM_LISTING ,
		'lang_player_mod'   => _AM_WEBPHOTO_PLAYER_MOD ,
		'lang_vote_stats'   => _AM_WEBPHOTO_VOTE_STATS ,
		'lang_label_admit'  => _AM_WEBPHOTO_LABEL_ADMIT ,
		'lang_button_admit' => _AM_WEBPHOTO_BUTTON_ADMIT ,
	);
	return $arr;
}

//---------------------------------------------------------
// uid
//---------------------------------------------------------
function item_user_param( $userstart )
{
	$uid  = $this->get_row_by_key( 'item_uid' );
	return $this->get_user_param( $uid, $userstart );
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

	$sort_array = $this->_sort_class->get_sort_to_order_array();
	foreach ( $sort_array as $k => $v ) 
	{
		$str .= '<option value="'. $url.$k .'">';
		$str .= $this->_sort_class->sort_to_lang( $k ) ;
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

//---------------------------------------------------------
// list
//---------------------------------------------------------
function print_list_table( $mode, $item_rows )
{
	$template = 'db:'. $this->_DIRNAME .'_form_admin_item_list.html';

	$is_waiting = ( $mode == 'waiting' ) ? true : false;

	$arr = array_merge( 
		$this->build_form_base_param(),
		$this->build_admin_language()
	);

	$arr['show_waiting']    = $this->build_list_show_waiting( $mode );
	$arr['show_perm_level'] = $this->use_item_perm_level();
	$arr['item_list']       = $this->build_item_list( $item_rows );

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	echo $tpl->fetch( $template ) ;
}

function build_list_show_waiting( $mode )
{
	if ( $mode == 'waiting' ) {
		return true;
	}
	return false;
}

function build_item_list( $item_rows )
{
	$this->_cat_handler->set_path_separator( ' ' );
	$kind_options = $this->_item_handler->get_kind_options();

	$arr = array();
	foreach ( $item_rows as $row )
	{
		$temp = $row;

		list( $is_online, $status_report, $status_link, $status_icon )
			= $this->build_list_status( $row );
		$temp['status_report'] = $status_report;
		$temp['status_link']   = $status_link;
		$temp['status_icon']   = $status_icon;

		$temp['kind_options'] = $kind_options[ $row['item_kind'] ];
		$temp['photo_url']    = $this->build_list_photo_url( $row, $is_online );
		$temp['cat_title']    = $this->build_list_cat_title( $row['item_cat_id'] );
		$temp['player_title'] = $this->build_list_player_title( $row['item_player_id'] );
		$temp['uname']        = $this->get_xoops_user_name( $row['item_uid'] );
		$temp['perm_level']   = $this->item_perm_level_value( $row['item_perm_level'] );

		$arr[] = $temp;
	}
	return $arr;
}

function build_list_status( $row )
{
	$item_id = $row['item_id'];
	$status  = $row['item_status'];
	$publish = $row['item_time_publish'];
	$expire  = $row['item_time_expire'];

	$is_online = false ;
	$report = '';
	$link   = '';
	$icon   = '';

	$photo_url  = $this->_MODULE_URL.'/index.php?fct=photo&amp;photo_id='.$item_id;
	$modify_url = $this->_THIS_URL.'&amp;op=modify_form&amp;item_id='.$item_id;

// online
	switch ( $status )
	{
	case _C_WEBPHOTO_STATUS_WAITING :
		$report = _WEBPHOTO_ITEM_STATUS_WAITING;
		$link   = $this->_THIS_URL.'&amp;op=list_waiting';
		$icon   = 'waiting.png';
		break;

	case _C_WEBPHOTO_STATUS_OFFLINE :
// Entry will Auto-Publish
		if ( ($publish > 0) && ($publish < time()) ) {
			$is_online = true ;
			$report = _AM_WEBPHOTO_STATUS_CHANGE.' : '. $this->format_timestamp($publish,'m');
			$link   = $photo_url ;
			$icon   = 'online.png';
			$this->_item_handler->update_status( $item_id, _C_WEBPHOTO_STATUS_UPDATED, true ) ;

		} else {
			$report = _AM_WEBPHOTO_STATUS_OFFLINE ;
			$link   = $this->_THIS_URL.'&amp;op=list_offline';
			$icon   = 'offline.png';   	           
		}
		break;

	case _C_WEBPHOTO_STATUS_EXPIRED :
		$report = _WEBPHOTO_ITEM_STATUS_EXPIRED.' : '. $this->format_timestamp($expire,'m');
		$link   = $this->_THIS_URL.'&amp;op=list_expired';
		$icon   = 'offline.png'; 
		break;

	case _C_WEBPHOTO_STATUS_APPROVED :
	case _C_WEBPHOTO_STATUS_UPDATED  :
	default :
// Entry has Expired
		if ( ($expire > 0) && ($expire < time()) ) {
			$report = _AM_WEBPHOTO_STATUS_CHANGE.' : '. $this->format_timestamp($expire,'m');
			$link   = $this->_THIS_URL.'&amp;op=list_expired';
			$icon   = 'offline.png';   
			$this->_item_handler->update_status( $item_id, _C_WEBPHOTO_STATUS_EXPIRED, true ) ;

// online
		} else {
			$is_online = true ;
			$report = _AM_WEBPHOTO_STATUS_ONLINE;
			$link   = $photo_url ;
			$icon   = 'online.png';
		}
		break;
	}

	return array( $is_online, $report, $link, $icon );
}

function build_list_photo_url( $item_row, $is_online )
{
	$item_id      = $item_row['item_id'] ;
	$external_url = $item_row['item_external_url'] ;

	if ( $is_online ) {
		$url = $this->_MODULE_URL.'/index.php?fct=photo&photo_id='.$item_id ;
	} else {
		$url = $this->build_list_cont_url( $item_row );
		if ( empty( $url ) ) {
			$url = $external_url ;
		}
	}
	return $url;
}

function build_list_cont_url( $item_row )
{
// Fatal error: Call to undefined method get_cached_file_row_by_kind()

	return $this->build_file_url_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_CONT );
}

function build_list_cat_title( $cat_id )
{
	return $this->_cat_handler->get_cached_value_by_id_name( $cat_id, 'cat_title' );
}

function build_list_player_title( $player_id )
{
	return $this->_player_handler->get_cached_value_by_id_name( $player_id, 'player_title' );
}

// --- class end ---
}

?>