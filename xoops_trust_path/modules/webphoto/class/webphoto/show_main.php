<?php
// $Id: show_main.php,v 1.3 2008/07/08 21:07:32 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// used build_uri_category() build_main_navi_url() etc
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_show_main
//=========================================================
class webphoto_show_main extends webphoto_show_photo
{
	var $_pathinfo_class;
	var $_gmap_class;
	var $_header_class;
	var $_pagenavi_class;
	var $_d3_notification_select_class;
	var $_sort_class;
	var $_preload_class;

	var $_sort_name;

// pathinfo param
	var $_get_op;
	var $_get_catid;
	var $_get_tag;
	var $_get_place;
	var $_get_date;
	var $_get_query;
	var $_get_sort;
	var $_get_page;
	var $_get_viewtype = null;

	var $_cfg_gmap_apikey = null;
	var $_cfg_use_popbox  = false;

	var $_SORT_ARRAY = array();

	var $_PAGE_DEFAULT  = 1;

	var $_get_uid     = -1;	// not set
	var $_UID_DEFAULT = -1;	// not set

	var $_ACTION_DEFAULT  = 'latest';
	var $_MAX_TAG_CLOUD   = 100;
	var $_MAX_GMAPS       = 100;

	var $_TOP_CATLIST_COLS    = 3;
	var $_TOP_CATLIST_DELMITA = '<br />';
	var $_CAT_CATLIST_COLS    = 3;
	var $_CAT_CATLIST_DELMITA = '<br />';

	var $_PHOTO_LIST_LIMIT = 1;
	var $_PHOTO_LIST_ORDER = 'photo_time_update DESC, photo_id DESC';
	var $_PHOTO_LIST_DATE_ORDER = 'photo_datetime DESC, photo_id DESC';
	var $_MODE_DEFAULT = 'latest';
	var $_RSS_LIMIT    = 100;

// set by config
	var $_MAX_PHOTOS       = 10;
	var $_VIEWTYPE_DEFAULT = 'list';
	var $_USE_POPBOX_JS    = false;

// check show
	var $_USE_BOX_JS      = true;
	var $_SHOW_RSS        = true;
	var $_SHOW_SUBCAT_IMG = true;

	var $_ARRAY_DENY_CATLIST      = array();	
	var $_ARRAY_DENY_TAGCLOUD     = array();
	var $_ARRAY_DENY_GMAP         = array();
	var $_ARRAY_DENY_DESC         = array();
	var $_ARRAY_DENY_NOTIFICATION = array();
	var $_ARRAY_DENY_NAVI         = array( 'random' );	// except random
	var $_ARRAY_DENY_NAVI_SORT    = array( 'random' );

	var $_ARRAY_ALLOW_CATLIST      = '*' ;	// all
	var $_ARRAY_ALLOW_TAGCLOUD     = '*' ;	// all
	var $_ARRAY_ALLOW_GMAP         = '*' ;	// all
	var $_ARRAY_ALLOW_DESC         = array( 'latest' );
	var $_ARRAY_ALLOW_NOTIFICATION = array( 'latest' );
	var $_ARRAY_ALLOW_NAVI         = '*';	// all
	var $_ARRAY_ALLOW_NAVI_SORT    = '*';	// all

	var $_ARRAY_CHECKSORT_NAVI     = array();

	var $_DEBUG = false ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_show_main( $dirname, $trust_dirname )
{
	$this->webphoto_show_photo( $dirname, $trust_dirname );

	$this->_gmap_class =& webphoto_gmap::getInstance( $dirname , $trust_dirname );

	$this->_notification_select_class =& webphoto_d3_notification_select::getInstance();
	$this->_notification_select_class->init( $dirname ); 

	$this->_pagenavi_class =& webphoto_lib_pagenavi::getInstance();
	$this->_pagenavi_class->set_mark_id_prev( '<b>'. $this->get_constant('NAVI_PREVIOUS') .'</b>' );
	$this->_pagenavi_class->set_mark_id_next( '<b>'. $this->get_constant('NAVI_NEXT') .'</b>' );

	$this->_pathinfo_class  =& webphoto_lib_pathinfo::getInstance();

	$this->_preload_class   =& webphoto_d3_preload::getInstance();
	$this->_preload_class->init( $dirname , $trust_dirname );

	$cfg_newphotos           = $this->get_config_by_name('newphotos');
	$cfg_viewcattype         = $this->get_config_by_name('viewcattype');
	$cfg_sort                = $this->get_config_by_name('sort');
	$cfg_use_popbox          = $this->get_config_by_name('use_popbox');
	$this->_cfg_gmap_apikey  = $this->get_config_by_name('gmap_apikey');
	$this->_cfg_use_pathinfo = $this->get_config_by_name('use_pathinfo');

	$this->_MAX_PHOTOS         = $cfg_newphotos;
	$this->_VIEWTYPE_DEFAULT   = $cfg_viewcattype;
	$this->_USE_POPBOX_JS      = $cfg_use_popbox;

	$this->_sort_class =& webphoto_photo_sort::getInstance( $dirname, $trust_dirname );
	$this->_sort_class->set_photo_sort_default( $cfg_sort );

// separator
	if ( $this->_cfg_use_pathinfo ) {
		$this->_pagenavi_class->set_separator_path(  '/' );
		$this->_pagenavi_class->set_separator_query( '/' );
	}

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_show_main( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// preload
//---------------------------------------------------------
function init_preload()
{
	$this->_preload_error();
	$this->_preload_constant();
	$this->_preload_photo_sort_array();
}

function _preload_error()
{
	$errors = $this->_preload_class->get_errors();
	if ( is_array($errors) && count($errors) ) {
		$this->set_error( $errors );
		if ( $this->_DEBUG ) {
			print_r( $errors );
		}
	}
}

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

function _preload_photo_sort_array()
{
	if ( $this->_preload_class->exists_class( 'show_main' ) ) {
		$arr = $this->_preload_class->exec_class_method(
			'show_main', 'get_photo_sort_array_extend' );
		if ( is_array($arr) && count($arr) ) {
			$this->_sort_class->set_photo_sort_array( $arr );
		}
	}
}

//---------------------------------------------------------
// build list
//---------------------------------------------------------
function build_list_common( $const_name, $show_photo_desc=false )
{
	$title_s   = $this->sanitize( $this->get_constant( $const_name ) );
	$total_all = $this->_photo_handler->get_count_public();

	$arr = array(
		'xoops_pagetitle'   => $title_s ,
		'title_bread_crumb' => $title_s,
		'sub_title_s'       => $title_s ,
		'show_photo_desc'   => $show_photo_desc ,
		'use_popbox_js'     => $this->_USE_POPBOX_JS ,
		'use_box_js'        => $this->_USE_BOX_JS ,
		'photo_total_all'   => $total_all ,
		'lang_thereare'     => sprintf( $this->get_constant('S_THEREARE') , $total_all ),
		'photo_list'        => $this->get_photo_list() ,
	);

	return $arr;
}

// for overwrite
function get_photo_list()
{
	// dummy
}

//---------------------------------------------------------
// globals
//---------------------------------------------------------
function get_photo_show_globals()
{
	$arr = $this->get_photo_globals();
	$arr['is_taf_module'] = $this->_get_is_taf_module();
	return $arr;
}

function _get_is_taf_module()
{
	$file = XOOPS_ROOT_PATH .'/modules/tellafriend/index.php';
	if ( file_exists($file) ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// show
//---------------------------------------------------------
function build_photo_show_from_rows( $rows )
{
	$arr = array();
	foreach ( $rows as $row ) {
		$arr[] = $this->build_photo_show( $row ) ;
	}
	return $arr;
}

function build_photo_show_from_id_array( $id_array )
{
	$arr = array();
	foreach ( $id_array as $id )
	{
		$arr[] = $this->build_photo_show( 
			$this->_photo_handler->get_row_by_id( $id ) ) ;
	}
	return $arr;
}

function build_show_sort( $total )
{
	if ( $total > 1 ) {
		return true;
	}
	return false;
}

function build_show_nomatch( $total )
{
	if ( $total == 0 ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// cat handler
//---------------------------------------------------------
function build_catlist( $cat_id, $catlist_cols, $catlist_delmita )
{
	$show = false;

	$cats = $this->get_categories_by_pid( $cat_id );
	if ( is_array($cats) && count($cats) ) {
		$show = true;
	}

	$catlist_width = intval( 100 / $catlist_cols ) - 1;
	if ( $catlist_width <= 0 ) {
		 $catlist_width = 1;
	}

	$arr = array(
		'show_subcat_img'   => $this->_SHOW_SUBCAT_IMG ,
		'show_catlist'      => $show,
		'catlist_cats'      => $cats,
		'catlist_cols'      => $catlist_cols ,
		'catlist_width'     => $catlist_width,
		'catlist_delmita'   => $catlist_delmita,
	);
	return $arr;
}

function build_show_cat( $row )
{
	$imgurl = $this->_cat_handler->build_show_imgurl( $row );

	$show = $row;
	$show['cat_title_s'] = $this->sanitize( $row['cat_title'] ) ;
	$show['imgurl']      = $imgurl ;
	$show['imgurl_s']    = $this->sanitize( $imgurl ) ;

	return $show;
}

// get list of categories in header space
function get_categories_by_pid( $parent_id )
{
	$ret = array() ;

	$orderby = 'cat_weight ASC, cat_title ASC';
	$rows = $this->_cat_handler->get_rows_by_pid_orderby( $parent_id, $orderby );
	foreach( $rows as $row )
	{
		$cat_id = $row['cat_id'];

		// Show first child of this category
		$subcat = array() ;

		$child_arr = $this->_cat_handler->get_first_child( $cat_id , $orderby ) ;
		foreach( $child_arr as $row_child ) 
		{
			$child_id = $row_child['cat_id'] ;

			$sub_arr = $this->build_show_cat( $row_child );
			$sub_arr['photo_small_sum']  
				= $this->_photo_handler->get_count_public_by_catid( $child_id ) ;
			$sub_arr['photo_total_sum'] 
				= $this->build_photo_total_in_parent_all_children( $child_id ) ;
			$sub_arr['number_of_subcat'] 
				= count( $this->_cat_handler->get_first_child_id( $child_id ) ) ;

			$subcat[] = $sub_arr;
		}

		// Total sum of photos
		$catid_arr = $this->_cat_handler->get_all_child_id( $cat_id ) ;
		array_push( $catid_arr , $cat_id ) ;
		$photo_total_sum = $this->_photo_handler->get_count_public_by_catid_array( $catid_arr ) ;

		$imgurl = $this->_cat_handler->build_show_imgurl( $row );

		$main_arr = $this->build_show_cat( $row );
		$main_arr['photo_small_sum'] 
			= $this->_photo_handler->get_count_public_by_catid( $cat_id ) ;
		$main_arr['photo_total_sum'] 
			= $this->build_photo_total_in_parent_all_children( $cat_id ) ;
		$main_arr['subcategories'] = $subcat ;

		$ret[] = $main_arr;
	}

	return $ret ;
}

function build_photo_total_in_parent_all_children( $cat_id )
{
	$catid_arr = $this->_cat_handler->get_all_child_id( $cat_id ) ;
	array_push( $catid_arr , $cat_id ) ;
	return $this->_photo_handler->get_count_public_by_catid_array( $catid_arr ) ;
}

function build_cat_path( $cat_id )
{
	$rows = $this->_cat_handler->get_parent_path_array( $cat_id );
	if ( !is_array($rows) || !count($rows) ) {
		return false;
	}

	$arr   = array();
	$count = count($rows);
	$last  = $count - 1;

	for ( $i = $last ; $i >= 0; $i-- ) {
		$arr[] = $this->build_show_cat( $rows[ $i ] );
	}

	$ret = array();
	$ret['list']  = $arr;
	$ret['first'] = $arr[ 0 ];
	$ret['last']  = $arr[ $last ];

	return $ret;
}

function build_cat_sub_title( $cat_id )
{
	$rows = $this->_cat_handler->get_parent_path_array( $cat_id );
	if ( !is_array($rows) || !count($rows) ) {
		return '';
	}

	$img = '<img src="'. $this->_URL_CATEGORY_IMAGE .'" border="0" alt="folder" />';

	$str   = '';
	$start = count($rows) - 1;

	for ( $i=$start; $i >= 0; $i-- )
	{
		$row  = $rows[$i];
		$url  = $this->build_uri_category( $row['cat_id'] ) ;

		$str .= '<a href="'. $url .'">';
		if ( $i == $start ) {
			$str .= ' '.$img.' ';
		}
		$str .= $this->sanitize( $row['cat_title'] );
		$str .= "</a> : \n";
	}

	return $str;
}

//---------------------------------------------------------
// random more
//---------------------------------------------------------
function build_random_more_url_with_check_sort( $url, $total, $get_sort=null, $flag_sanitize=true )
{
	if ( $total == 0 ) {
		return null;
	}

	if ( empty($get_sort) ) {
		$get_sort = $this->_get_sort;
	}

	if ( $get_sort != 'random' ) {
		return null;
	}

	$url .= 'sort=random/';
	$ret  = $this->add_viewtype( $url );

	if ( $flag_sanitize ) {
		$ret = $this->sanitize( $ret );
	}

	return $ret;
}

function add_viewtype( $url )
{
	if ( $this->_get_viewtype ) {
		$url .= 'viewtype='.$this->_get_viewtype.'/';
	}
	return $url;
}

//---------------------------------------------------------
// sort class
//---------------------------------------------------------
function get_orderby_by_post()
{
	return $this->_sort_class->sort_to_orderby( $this->_get_sort );
}

function get_orderby_default()
{
	return $this->_sort_class->sort_to_orderby( null );
}

function get_lang_sortby( $name )
{
	return $this->_sort_class->get_lang_sortby( $name );
}

//---------------------------------------------------------
// pagenavi class
//---------------------------------------------------------
function build_main_navi( $mode, $total, $limit, $get_page=null )
{
	$url = $this->build_uri_main_navi_url( $mode );
	return $this->build_navi( $url, $total, $limit, $get_page );
}

function build_navi( $url, $total, $limit, $get_page=null )
{
	if ( empty($get_page) ) {
		$get_page = $this->_get_page;
	}

	$show      = false ;
	$navi_page = '' ;
	$navi_info = '' ;

	if ( $total > $limit ) {
		$show      = true ;
		$navi_page = $this->build_navi_page( $url, $get_page, $limit, $total ) ;
		$navi_info = $this->build_navi_info( $get_page, $limit, $total );
	}

	$arr = array(
		'show_navi'  => $show ,
		'navi_page'  => $navi_page ,
		'navi_info'  => $navi_info ,
	);
	return $arr;
}

function build_navi_page( $url, $page, $limit, $total )
{
	return $this->_pagenavi_class->build( $url, $page, $limit, $total );
}

function build_navi_info( $page, $limit, $total )
{
	$start = $this->pagenavi_calc_start( $limit, $page );
	$end   = $this->pagenavi_calc_end( $start, $limit, $total );

	return sprintf( $this->get_constant('S_NAVINFO') , $start + 1 , $end , $total ) ;
}

function pagenavi_calc_start( $limit, $page=null )
{
	if ( empty($page) ) {
		$page = $this->_get_page;
	}

	return $this->_pagenavi_class->calc_start( $page, $limit );
}

function pagenavi_calc_end( $start, $limit, $total )
{
	return $this->_pagenavi_class->calc_end( $start, $limit, $total );
}

//---------------------------------------------------------
// gmap class
//---------------------------------------------------------
function build_gmap( $cat_id=0, $limit )
{
	$show  = false;
	$icons = null;

	$cfg_gmap_latitude  = $this->_config_class->get_by_name('gmap_latitude');
	$cfg_gmap_longitude = $this->_config_class->get_by_name('gmap_longitude');
	$cfg_gmap_zoom      = $this->_config_class->get_by_name('gmap_zoom');

	$photos = $this->_gmap_class->build_photo_list_by_catid( $cat_id, $limit );
	if ( is_array($photos) && count($photos) ) {
		$show  = true;
		$icons = $this->_gmap_class->build_icon_list();
	}

	$arr = array(
		'show_gmap'         => $show ,
		'gmap_photos'       => $photos ,
		'gmap_icons'        => $icons ,
		'gmap_latitude'     => $cfg_gmap_latitude,
		'gmap_longitude'    => $cfg_gmap_longitude,
		'gmap_zoom'         => $cfg_gmap_zoom,
		'gmap_lang_not_compatible' => $this->get_constant('GMAP_NOT_COMPATIBLE') ,
	);
	return $arr;
}

//---------------------------------------------------------
// notification select class
//---------------------------------------------------------
function build_notification_select()
{
	$show  = false;
	$param = null;

// for core's notificationSubscribableCategoryInfo
	$_SERVER['PHP_SELF'] = $this->_notification_select_class->get_new_php_self();
	if ( $this->_get_catid > 0 ) {
		$_GET['cat_id'] = $this->_get_catid;
	}

	$param = $this->_notification_select_class->build( $this->_cfg_use_pathinfo );
	if ( is_array($param) && count($param) ) {
		$show  = true;
	}

	$arr = array(
		'show_notification_select' => $show ,
		'notification_select'      => $param ,
	);
	return $arr;
}

//---------------------------------------------------------
// uri class
//---------------------------------------------------------
function build_uri_main_navi_url( $mode )
{
	return $this->_uri_class->build_main_navi_url( $mode, $this->_get_sort );
}

function build_uri_main_sort( $mode )
{
	return $this->_uri_class->build_main_sort( $mode );
}

//---------------------------------------------------------
// get pathinfo param
//---------------------------------------------------------
function get_pathinfo_param()
{
	$this->_get_op   = $this->get_pathinfo_op() ;
	$this->_get_page = $this->get_pathinfo_page() ;
	$this->_get_sort = $this->get_photo_sort_name_by_pathinfo();
}

function get_pathinfo_op()
{
	$op = $this->_pathinfo_class->get('op');
	if ( $op ) { return $op ; }

	return $this->_pathinfo_class->get_path( 0 ) ;
}

function get_pathinfo_page()
{
	$page = $this->_pathinfo_class->get_int('page');
	if ( $page < $this->_PAGE_DEFAULT ) {
		 $page = $this->_PAGE_DEFAULT ;
	}
	return $page ;
}

function get_photo_sort_name_by_pathinfo()
{
	return $this->_sort_class->get_photo_sort_name(
		$this->_pathinfo_class->get_text( 'sort' ) );
}

//---------------------------------------------------------
// build param
//---------------------------------------------------------
function add_box_list( $param )
{
	$param['box_list'] = $this->build_box_list( $param );
	return $param;
}

function build_box_list( $param )
{
	$arr = array();
	if ( isset($param['use_box_js']) && $param['use_box_js'] ) {
		if ( isset($param['show_catlist']) && $param['show_catlist'] ) {
			$arr[] = 'webphoto_box_catlist';
		}
		if ( isset($param['show_tagcloud']) && $param['show_tagcloud'] ) {
			$arr[] = 'webphoto_box_tagcloud';
		}
		if ( isset($param['show_gmap']) && $param['show_gmap'] ) {
			$arr[] = 'webphoto_box_gmap';
		}
		if ( isset($param['show_photo']) && $param['show_photo'] ) {
			$arr[] = 'webphoto_box_photo';
		}
		if ( count($arr) ) {
			return implode( ',', $arr );
		}
	}
	return '';
}

function build_init_param( $mode, $show_photo_desc=false )
{
	$total_all = $this->_photo_handler->get_count_public();

	$arr = array(
		'use_popbox_js'   => $this->_USE_POPBOX_JS ,
		'use_box_js'      => $this->_USE_BOX_JS ,
		'show_photo_desc' => $show_photo_desc ,
		'photo_total_all' => $total_all ,
		'lang_thereare'   => $this->build_lang_thereare( $total_all ) ,
	);

	return array_merge( $arr, $this->build_get_param( $mode ) );
}

function build_lang_thereare( $total_all )
{
	return sprintf( $this->get_constant('S_THEREARE') , $total_all ) ;
}

function build_get_param( $mode )
{
	$arr = array(
		'mode'              => $mode,
		'op'                => $this->_get_op,
		'page'              => $this->_get_page,
		'sort'              => $this->_get_sort,
//		'viewtype'          => $this->_get_viewtype,
//		'param_viewtype'    => $this->build_param_viewtype( $mode ) ,
		'param_sort'        => $this->build_uri_main_sort( $mode ) ,
		'lang_cursortedby'  => $this->get_lang_sortby( $this->_get_sort ),
	);
	return $arr;
}

function build_param_viewtype( $mode )
{
	return null ;	// dummy
}

//---------------------------------------------------------
// check show
//---------------------------------------------------------
function check_show_catlist( $mode )
{
	return $this->check_show_common( $mode, 'catlist' );
}

function check_show_tagcloud( $mode )
{
	return $this->check_show_common( $mode, 'tagcloud' );
}

function check_show_gmap( $mode )
{
	return $this->check_show_common( $mode, 'gmap' );
}

function check_show_notification( $mode )
{
	return $this->check_show_common( $mode, 'notification' );
}

function check_show_navi( $mode, $sort )
{
	if ( $this->check_show_common( $mode, 'navi' ) ) {
		if ( $this->is_in_array( $mode, $this->_ARRAY_CHECKSORT_NAVI ) ) {
			if ( $this->check_show_navi_sort( $sort ) ) {
				return true;
			}
		} else {
			return true;
		}
	}
	return false;
}

function check_show_navi_sort( $sort )
{
	return $this->check_show_common( $sort, 'navi_sort' );
}

function check_show_common( $mode, $name )
{
	$allow_name = strtoupper( '_ARRAY_ALLOW_'.$name );
	$deny_name  = strtoupper( '_ARRAY_DENY_'. $name );
	$allow_arr  = $this->$allow_name;
	$deny_arr   = $this->$deny_name;

	if ( $this->is_in_array( $mode, $deny_arr ) ) {
		return false;
	}
	if ( $this->is_in_array( $mode, $allow_arr ) ) {
		return true;
	}
	return false;
}

function is_in_array( $needle, $haystack )
{
	if ( is_array($haystack) ) {
		if ( in_array( $needle, $haystack ) ) {
			return true;
		}
	} else {
		if ( $haystack == '*' ) {
			return true;
		}
	}
	return false;
}

//---------------------------------------------------------
// xoops header
//---------------------------------------------------------
function assign_xoops_header_default()
{
	$this->assign_xoops_header( $this->_MODE_DEFAULT );
}

function assign_xoops_header( $mode, $rss_param=null, $flag_gmap=false )
{
	$param = array(
		'dirname'     => $this->_DIRNAME ,
		'flag_css'    => true ,
		'flag_popbox' => $this->_USE_POPBOX_JS ,
		'flag_box'    => $this->_USE_BOX_JS ,
		'flag_gmap'   => $flag_gmap ,
		'gmap_apikey' => $this->_cfg_gmap_apikey ,
		'flag_rss'    => true ,
		'rss_mode'    => $mode ,
		'rss_param'   => $rss_param ,
		'rss_limit'   => $this->_RSS_LIMIT ,
		'lang_popbox_revert' => $this->get_constant('POPBOX_REVERT') ,
	);

	$header_class =& webphoto_inc_xoops_header::getInstance();
	$header_class->assign_for_main( $param );
}

// --- class end ---
}

?>