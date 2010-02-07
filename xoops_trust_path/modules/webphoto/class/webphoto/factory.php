<?php
// $Id: factory.php,v 1.4 2010/02/07 12:20:02 ohwada Exp $

//=========================================================
// webphoto module
// 2010-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_factory
//=========================================================
class webphoto_factory extends webphoto_base_this
{
	var $_photo_class;
	var $_catrgory_class;
	var $_header_class;
	var $_page_class;
	var $_gmap_class;
	var $_qr_class;
	var $_pathinfo_class;
	var $_sort_class;
	var $_public_class;
	var $_multibyte_class;
	var $_pagenavi_class;
	var $_timeline_class;
	var $_auto_publish_class;
	var $_tagcloud_class;

// config
	var $_cfg_cat_summary;
	var $_cfg_newphotos;
	var $_cfg_tags;

// GET param
	var $_get_page  = null;
	var $_get_sort  = null;	// allow empty
	var $_get_kind  = null;	// allow empty
	var $_param     = null;
	var $_param_out = null;
	var $_sort      = null;	// set default in empty
	var $_orderby   = null;
	var $_start     = null;
	var $_mode      = null;
	var $_mode_orig = null;

	var $_cat_id = 0;

	var $_xoops_header_array = array();
	var $_show_array     = array();
	var $_templete_param = array();
	var $_template_main  = null;

	var $_MODE_DEFAULT;
	var $_PHOTO_LIMIT;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_factory( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );

	$this->_photo_class 
		=& webphoto_photo::getInstance( $dirname , $trust_dirname );
	$this->_category_class 
		=& webphoto_category::getInstance( $dirname , $trust_dirname );
	$this->_header_class
		=& webphoto_xoops_header::getInstance( $dirname, $trust_dirname );
	$this->_page_class
		=& webphoto_page::getInstance( $dirname , $trust_dirname );
	$this->_gmap_class
		=& webphoto_gmap::getInstance( $dirname , $trust_dirname );
	$this->_qr_class
		=& webphoto_qr::getInstance( $dirname , $trust_dirname );
	$this->_public_class
		=& webphoto_photo_public::getInstance( $dirname, $trust_dirname );
	$this->_pagenavi_class 
		=& webphoto_pagenavi::getInstance( $dirname, $trust_dirname );
	$this->_timeline_class 
		=& webphoto_timeline::getInstance( $dirname, $trust_dirname );
	$this->_notification_select_class 
		=& webphoto_notification_select::getInstance( $dirname );
	$this->_sort_class 
		=& webphoto_photo_sort::getInstance( $dirname, $trust_dirname );
	$this->_tagcloud_class 
		=& webphoto_inc_tagcloud::getSingleton( $dirname, $trust_dirname );

	$this->_auto_publish_class 
		=& webphoto_inc_auto_publish::getSingleton( $dirname, $trust_dirname  );
	$this->_auto_publish_class->set_workdir( $this->_WORK_DIR );

	$this->_multibyte_class =& webphoto_multibyte::getInstance();
	$this->_pathinfo_class  =& webphoto_lib_pathinfo::getInstance();

	$this->_cfg_cat_summary = $this->_config_class->get_by_name('cat_summary');
	$this->_cfg_newphotos   = $this->_config_class->get_by_name('newphotos');
	$this->_cfg_tags        = $this->_config_class->get_by_name('tags');

	$this->_MODE_DEFAULT = $this->get_ini('view_mode_default');
	$this->_PHOTO_LIMIT  = $this->_cfg_newphotos;

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_factory( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// init
//---------------------------------------------------------
function init_factory()
{
	$this->_auto_publish_class->auto_publish();

	$this->get_page_mode();
	$this->get_pathinfo_param( $this->_mode_orig );
	$this->set_cat_id( $this->_mode, $this->_param );

	$this->init_preload();
}

function init_preload()
{
	$this->preload_init();
	$this->preload_error( $this->get_ini('debug_preload') );
	$this->preload_constant();
}

//---------------------------------------------------------
// mode
//---------------------------------------------------------
function set_mode( $val )
{
	$this->_mode = $val;
}

function set_mode_orig( $val )
{
	$this->_mode_orig = $val;
}

//---------------------------------------------------------
// get pathinfo param
//---------------------------------------------------------
function get_pathinfo_param( $mode_orig )
{
	$this->_get_page = $this->_pathinfo_class->get_page() ;

	$this->_get_sort = $this->_sort_class->get_photo_sort_name(
		$this->_pathinfo_class->get_text('sort') );
	$this->_get_kind = $this->_sort_class->get_photo_kind_name( 
		$this->_pathinfo_class->get_text('kind') );

	$this->_param    = $this->_uri_class->get_pathinfo_param( $mode_orig );
	$this->set_param_out( $this->_param );

	$this->_sort    = $this->_sort_class->get_photo_sort_name( $this->_get_sort, true );
	$this->_orderby = $this->_sort_class->sort_to_orderby( $this->_sort );

	$this->_start    = $this->_pagenavi_class->calc_navi_start( 
		$this->_get_page, $this->_PHOTO_LIMIT ); 
}

function set_param_out( $val )
{
	$this->_param_out = $val;
}

function get_page_mode()
{
	$mode_orig = $this->_pathinfo_class->get_fct_op_0();
	switch ( $mode_orig )
	{
		case 'latest':
		case 'popular':
		case 'highrate':
		case 'random':
		case 'map':
		case 'timeline':
//		case 'new':
		case 'picture':
		case 'video':
		case 'audio':
		case 'office':
		case 'category':
		case 'date':
		case 'place':
		case 'tag':
		case 'user':
		case 'search':
		case 'photo':
			$mode      = $mode_orig;
			break;

		case 'myphoto':
			$mode      = 'user';
			break;

		default:
			$mode      = $this->_MODE_DEFAULT;
			$mode_orig = $this->_MODE_DEFAULT;
			break;
	}

	$this->set_mode( $mode );
	$this->set_mode_orig( $mode_orig );
	return $mode;
}

function set_cat_id( $mode, $param )
{
	switch ( $mode )
	{
		case 'category':
			$this->_cat_id = intval($param);
			break;

		default:
			break;
	}
}

//---------------------------------------------------------
// xoops header
//---------------------------------------------------------
function xoops_header_array_set_by_mode( $mode ) 
{
	$this->xoops_header_array_set_by_ini( $this->get_header_ini( $mode ) );
}

function get_header_ini( $mode ) 
{
	$name1 = $mode.'_header_list';
	$name2 = 'main_header_list';

	if ( $this->isset_ini( $name1 ) ) {
		return $name1;
	}

	return $name2;
}

function xoops_header_array_set_by_ini( $name )
{
	$this->xoops_header_array_set( $this->explode_ini( $name ) );
}

function xoops_header_array_set( $val )
{
	$this->_xoops_header_array = $val;
}

function xoops_header_check( $key )
{
	if ( in_array( $key, $this->_xoops_header_array ) ) {
		return true;
	}
	return false;
}

function xoops_header_param()
{
	if ( $this->xoops_header_check('css') ) { 
		$this->_header_class->set_flag_css( true );
	}
	if ( $this->xoops_header_check('box') ) { 
		$this->_header_class->set_flag_box( true );
	}
	if ( $this->xoops_header_check('popbox') ) { 
		$this->_header_class->set_flag_popbox( true );
	}
	if ( $this->xoops_header_check('timeline') ) { 
		$this->_header_class->set_flag_timeline( true );
	}
}

function xoops_header_gmap_with_check( $flag )
{
	if ( $this->xoops_header_check('gmap') ) { 
		$this->_header_class->set_flag_gmap( $flag );
	}
}

function xoops_header_lightbox_with_check( $flag )
{
	if ( $this->xoops_header_check('lightbox') ) { 
		$this->_header_class->set_flag_lightbox( $flag );
	}
}

function xoops_header_rss_with_check( $mode, $rss_param )
{
	if ( $this->xoops_header_check('rss') ) { 
		$this->_header_class->set_rss(
			true, $mode, $rss_param, $this->get_ini('view_rss_limit') );
	}
}

function xoops_header_assign()
{
	$this->_header_class->assign_for_main();
}

//---------------------------------------------------------
// template
//---------------------------------------------------------
function set_template_main_by_mode( $mode )
{
	$this->set_template_main( 
		$this->get_template_name_by_mode( $mode ) );
}

function set_template_main( $val )
{
	$this->_template_main = $val;
}

function get_template_main()
{
	return $this->build_template_main() ;
}

function build_template_main()
{
	$str = $this->_DIRNAME .'_'. $this->_template_main ;
	return $str;
}

function get_template_name_by_mode( $mode )
{
	$name1 = 'main_'. $mode .'.html' ;
	$name2 = 'main_index.html' ;

	$file = $this->_TRUST_DIR .'/templates/'. $name1 ;
	if ( file_exists($file) ) {
		return $name1;
	}
	return $name2;
}

//---------------------------------------------------------
// templete param
//---------------------------------------------------------
function show_array_set_list_by_mode( $mode )
{
	$this->show_array_set_by_ini( $this->get_show_list_ini( $mode ) );
}

function show_array_set_detail_by_mode( $mode )
{
	$this->show_array_set_by_ini( $this->get_show_detail_ini( $mode ) );
}

function get_show_list_ini( $mode ) 
{
	$name1 = $mode.'_show_list_list';
	$name2 = 'main_show_list';

	if ( $this->isset_ini( $name1 ) ) {
		return $name1;
	}

	return $name2;
}

function get_show_detail_ini( $mode ) 
{
	$name1 = $mode.'_show_detail_list';
	$name2 = $mode.'_show_list';
	$name3 = 'main_show_list';

	if ( $this->isset_ini( $name1 ) ) {
		return $name1;
	}
	if ( $this->isset_ini( $name2 ) ) {
		return $name2;
	}
	return $name3;
}

function get_show_array( $mode )
{
	$name  = $mode.'_show_detail_list';

	$arr = $this->explode_ini( $name );
	if ( is_array($arr) && count($arr) ) {
		return $arr;
	}

	$name  = 'main_show_list';
	$name1 = $mode.'_show_list';
	$name2 = $mode.'_show_add_list';
	$name3 = $mode.'_show_remove_list';

	return $this->build_array( $name, $name1, $name2, $name3 );
}

function show_array_set_by_ini( $name )
{
	$this->show_array_set( $this->explode_ini( $name ) );
}

function show_array_set( $val )
{
	$this->_show_array = $val;
}

function show_check( $key )
{
	if ( in_array( $key, $this->_show_array ) ) {
		return true;
	}
	return false;
}

function show_param()
{
	$this->show_param_common();
	$this->show_param_with_check();
}

function show_param_common()
{
	$this->tpl_set( 'show_photo_edit_link',  true );
	$this->tpl_set( 'show_photo_title_link', true );
	$this->tpl_set( 'show_photo_uid_link',   true );
	$this->tpl_set( 'show_photo_rate_link',  true );
	$this->tpl_set( 'show_photo_taf_link',   true );

	if ( $this->get_ini('show_photo_category_link') ) {
		$this->tpl_set( 'show_photo_category_link', true );
	}
	if ( $this->get_ini('show_photo_equipment_link') ) {
		$this->tpl_set( 'show_photo_equipment_link', true );
	}
	if ( $this->get_ini('show_photo_tag_link') ) {
		$this->tpl_set( 'show_photo_tag_link', true );
	}
	if ( $this->get_ini('show_photo_user_link') ) {
		$this->tpl_set( 'show_photo_user_link', true );
	}
	if ( $this->get_ini('show_photo_date_link') ) {
		$this->tpl_set( 'show_photo_date_link', true );
	}
	if ( $this->get_ini('show_photo_place_link') ) {
		$this->tpl_set( 'show_photo_place_link', true );
	}
}

function show_param_with_check()
{
	if ( $this->show_check('title') ) {
		$this->tpl_set( 'show_title', true );
	}
	if ( $this->show_check('menu') ) {
		$this->tpl_set( 'show_menu', true );
	}
	if ( $this->show_check('search') ) {
		$this->tpl_set( 'show_search', true );
	}
	if ( $this->show_check('comment') ) {
		$this->tpl_set( 'show_comment', true );
	}
	if ( $this->show_check('index_desc') ) {
		$this->tpl_set( 'show_index_desc', true );
	}
	if ( $this->show_check('random_more') ) {
		$this->tpl_set( 'show_random_more', true );
	}
	if ( $this->show_check('photo_desc') ) {
		$this->tpl_set( 'show_photo_desc', true );
	}
	if ( $this->show_check('photo_summary') ) {
		$this->tpl_set( 'show_photo_summary', true );
	}
	if ( $this->show_check('photo_misc') ) {
		$this->tpl_set( 'show_photo_misc', true );
	}
	if ( $this->show_check('photo_exif') ) {
		$this->tpl_set( 'show_photo_exif', true );
	}
	if ( $this->show_check('photo_content') ) {
		$this->tpl_set( 'show_photo_content', true );
	}
	if ( $this->show_check('box_js') ) {
		$this->tpl_set( 'use_box_js', true );
	}
	if ( $this->show_check('popbox_js') ) {
		$this->tpl_set( 'use_popbox_js', true );
	}
	if ( $this->show_check('lightbox_js') ) {
		$this->tpl_set( 'use_lightbox_js', true );
	}
}

function set_tpl_show_page_detail( $val )
{
	$this->tpl_set( 'show_page_detail', $val );
}

function set_tpl_common()
{
	$this->set_tpl_get_param();
	$this->set_tpl_is_taf_module();
	$this->set_tpl_photo_total_all();

	$this->tpl_merge( $this->_page_class->build_main_param() );
	$this->tpl_merge( $this->_page_class->build_qrs_param() );
}

function set_tpl_get_param()
{
	$this->tpl_set( 'page', $this->_get_page );
	$this->tpl_set( 'sort', $this->_get_sort );
	$this->tpl_set( 'kind', $this->_get_kind );

	$this->tpl_set( 'lang_cursortedby' , 
		$this->_sort_class->get_lang_sortby( $this->_sort ) );
}

function set_tpl_is_taf_module()
{
	$this->tpl_set( 'is_taf_module' , $this->_page_class->get_is_taf_module()  );
}

function set_tpl_photo_total_all()
{
	$total = $this->_public_class->get_count();
	$lang  = sprintf( $this->get_constant('S_THEREARE') , $total );

	$this->tpl_set( 'photo_total_all', $total );
	$this->tpl_set( 'lang_thereare'  , $lang );
}

function set_tpl_mode( $mode )
{
	$this->tpl_set( 'mode', $mode );
	$this->tpl_set( 'param_sort' , 
		$this->_uri_class->build_param_sort( $mode, $this->_param_out, $this->_get_kind ) );

	if ( $mode == 'random' ) {
		$this->tpl_set( 'show_random_more', true );
	}
}

function set_tpl_title_for_list( $mode )
{
	$const = 'title_'. $mode .'_list';
	$title = $this->sanitize( $this->get_constant( $const ) );

	$this->set_tpl_title( $title );
}

function set_tpl_title( $title )
{
	$pagetitle = $title .' - '.$this->sanitize( $this->_MODULE_NAME );

	$this->tpl_set( 'xoops_pagetitle',   $pagetitle );
	$this->tpl_set( 'title_bread_crumb', $title );
	$this->tpl_set( 'sub_title_s',       $title );
}

function set_tpl_photo_list( $val )
{
	$this->tpl_set( 'photo_list', $val );
}

function set_tpl_photo_sum( $val )
{
	$this->tpl_set( 'photo_sum', $val );
}

function set_tpl_category_photo_list( $val )
{
	$this->tpl_set( 'category_photo_list', $val );
}

function set_tpl_cat_id( $cat_id )
{
	$this->tpl_set( 'cat_id', $cat_id );
}

function set_tpl_photo_nav( $photo_id, $cat_id )
{
	$this->tpl_set( 'photo_nav', 
		$this->_photo_class->build_photo_navi( $photo_id, $cat_id ) );
}

function set_tpl_photo_tags( $photo_id )
{
	$this->tpl_merge( 
		$this->_photo_class->build_photo_tags_param( $photo_id ) );
}

function set_tpl_catpath_with_check( $cat_id )
{
	if ( $this->show_check('catpth') ) {
		$catpath = $this->_category_class->build_catpath( $cat_id );
		$this->tpl_set( 'catpath', $catpath );
		$this->tpl_set( 'show_catpath', true ); 
	}
}

function set_tpl_catlist_with_check( $cat_id )
{
	if ( $this->show_check('catlist') ) {
		if ( $cat_id > 0 ) {
			$cols    = $this->get_ini('view_cat_catlist_cols');
			$delmita = $this->get_ini('view_cat_catlist_delmita');
		} else {
			$cols    = $this->get_ini('view_top_catlist_cols');
			$delmita = $this->get_ini('view_top_catlist_delmita');
		}
	
		$param = $this->_category_class->build_catlist(
			$cat_id, $cols, $delmita );
		$this->tpl_merge( $param );
	}
}

function set_tpl_qr_with_check( $photo_id )
{
	if ( $this->show_check('qr') ) {
		$this->_qr_class->create_mobile_qr( $photo_id );
		$this->tpl_merge(
			$this->_qr_class->build_mobile_param( $photo_id ) );
		$this->tpl_set( 'show_qr', true ); 
	}
}

function set_tpl_tagcloud_with_check( $limit )
{
	if ( $this->show_check('tagcloud') ) {
		$tagcloud = $this->_tagcloud_class->build_tagcloud( $limit );
		if ( is_array($tagcloud) && count($tagcloud) ) {
			$this->tpl_set( 'show_tagcloud', true );
			$this->tpl_set( 'tagcloud', $tagcloud );
		}
	}
}

function set_tpl_gmap_for_list_with_check( $rows )
{
	$gmap_large = $this->show_check('gmap_large');
	if ( $this->show_check('gmap') || $gmap_large ) {
		$param = $this->_gmap_class->build_for_main( $rows, $gmap_large );
		$this->tpl_merge( $param );
		return $param['show_gmap'];
	}
	return false;
}

function set_tpl_gmap_for_detail_with_check( $mode, $rows, $cat_id )
{
	$gmap_large = $this->show_check('gmap_large');
	if ( $this->show_check('gmap') || $gmap_large ) {
		if ( $mode == 'category' ) {
			$param = $this->_gmap_class->build_for_category( $rows, $cat_id, $gmap_large );
		} else {
			$param = $this->_gmap_class->build_for_main( $rows, $gmap_large );
		}
		$this->tpl_merge( $param );
		return $param['show_gmap'];
	}
	return false;
}

function set_tpl_gmap_for_photo_with_check( $row )
{
	if ( $this->show_check('gmap') ) {
		$param = $this->_gmap_class->build_for_photo( $row );
		$this->tpl_merge( $param );
		return $param['show_gmap'];
	}
	return false;
}

function set_tpl_timeline_with_check( $rows )
{
	if ( $this->show_check('timeline') || $this->show_check('timeline_large') ) {
		$unit  = $this->_post_class->get_get_text('unit');
		$date  = $this->_post_class->get_get_text('date');
		$param = $this->_timeline_class->build_timeline_by_rows(
			$unit, $date, $rows , $this->show_check('timeline_large') );
		$this->tpl_merge( $param );
	}
}

function set_tpl_notification_select_with_check( $cat_id=0 )
{
	if ( $this->show_check('notification') ) {
		$param = $this->_notification_select_class->build_notification_select( $cat_id );
		if ( is_array($param) && count($param) ) {
			$this->tpl_set( 'show_notification_select', true );
			$this->tpl_set( 'notification_select', $param );
		}
	}
}

function set_tpl_total_for_detail( $mode, $total )
{
	$this->tpl_set( 'total_bread_crumb', $total );
	$this->tpl_set( 'photo_total',       $total );

	if ( $total > 0 ) {
		$this->tpl_set( 'show_photo_list', true );
	} else {
		$this->tpl_set( 'show_nomatch', true );
	}

	if ( $this->show_check('sort') ) {
		if ( $total > 1 ) {
			$this->tpl_set( 'show_sort', true );
		}
	}

	if ( $this->show_check('navi') ) {
		if ( $total > $this->_PHOTO_LIMIT ) {
			$this->tpl_merge( 
				$this->build_navi_param( $mode, $total ) );
			$this->tpl_set( 'show_navi', true );
		}
	}
}

function set_tpl_photo_for_detail( $row )
{
	$this->tpl_set( 'photo', $this->build_photo_for_photo( $row ) );
	$this->tpl_set( 'show_photo', true );

	return $this->show_ligthtbox( $row );
}

function set_tpl_error( $val )
{
	$this->tpl_set( 'error', $val );
}

function set_tpl_show_js_windows()
{
	$this->tpl_merge( 
		$this->_page_class->build_show_js_windows( $this->tpl_get() ) );
}

function tpl_set( $key, $val )
{
	$this->_templete_param[ $key ] = $val ;
}

function tpl_merge( $arr )
{
	if ( is_array($arr) ) {
		$this->_templete_param = array_merge( $this->_templete_param , $arr );
	} else {
//		echo 'system error :'. $arr;
//		debug_print_backtrace();
	}
}

function tpl_get()
{
	return $this->_templete_param ;
}

//---------------------------------------------------------
// build component
//---------------------------------------------------------
function category_build_rows_for_detail( $cat_id )
{
	$cat_param = $this->_category_class->build_rows_for_detail( 
		$cat_id, $this->_orderby, $this->_PHOTO_LIMIT, $this->_start ) ;

	$title = $this->sanitize( $cat_param['cat_title'] );
	$total = $cat_param['photo_total'] ;
	$rows  = $cat_param['photo_rows'] ;
	$sum   = $cat_param['photo_sum'] ;

	return array( $title, $total, $rows, $sum );
}

function build_navi_param( $mode, $total )
{
	return $this->_pagenavi_class->build_navi( 
		$mode, $total, $this->_param_out, $this->_get_sort, 
		$this->_get_kind, $this->_get_page, $this->_PHOTO_LIMIT );
}

//---------------------------------------------------------
// show
//---------------------------------------------------------
function build_photo_list_for_detail( $rows )
{
	if ( !is_array($rows) || !count($rows) ) {
		return false;
	}

	$arr = array();
	foreach ( $rows as $row ) {
		$arr[] = $this->build_photo_by_row( $row ) ;
	}
	return $arr;
}

function build_photo_by_row( $row )
{
	return $this->_photo_class->build_photo_by_row( $row );
}

function build_photo_for_photo( $row )
{
	return $this->_photo_class->build_photo_for_photo( $row );
}

function show_ligthtbox( $row )
{
	$show = false;
	if (( $row['item_displaytype'] == _C_WEBPHOTO_DISPLAYTYPE_IMAGE ) &&
	    ( $row['item_detail_onclick'] == _C_WEBPHOTO_DETAIL_ONCLICK_LIGHTBOX )) {
		$show = true;
	}
	return $show;
}

// --- class end ---
}

?>