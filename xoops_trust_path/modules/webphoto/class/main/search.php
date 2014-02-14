<?php
// $Id: search.php,v 1.6 2009/03/20 04:18:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-03-15 K.OHWADA
// add_box_list() -> add_show_js_windows()
// 2009-01-10 K.OHWADA
// set_keyword_array()
// 2008-12-12 K.OHWADA
// public_class
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// 2008-07-01 K.OHWADA
// used set_mode()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_search
//=========================================================
class webphoto_main_search extends webphoto_show_list
{
	var $_search_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_search( $dirname , $trust_dirname )
{
	$this->webphoto_show_list( $dirname , $trust_dirname );
	$this->set_mode( 'search' );

	$this->_search_class =& webphoto_lib_search::getInstance();
	$this->_search_class->set_lang_zenkaku( $this->get_constant('SR_ZENKAKU') );
	$this->_search_class->set_lang_hankaku( $this->get_constant('SR_HANKAKU') );
	$this->_search_class->set_min_keyword( 
		$this->_search_class->get_xoops_config_search_keyword_min() );
	$this->_search_class->set_is_japanese( $this->_is_japanese );

	$this->init_preload();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_search( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
// overwrite
function list_sel()
{
	return true;
}

//---------------------------------------------------------
// detail list
//---------------------------------------------------------
// overwrite
function list_build_detail( $query_in )
{

	$rows    = null ;
	$limit   = $this->_MAX_PHOTOS;
	$start   = $this->pagenavi_calc_start( $limit );
	$orderby = $this->get_orderby_by_post();

	$query_in = $this->_utility_class->decode_slash( $query_in );
	$photo_param = $this->_get_photos( $query_in, $orderby, $limit, $start );
	$total = $photo_param['total'];
	$rows  = $photo_param['rows'];
	$error = $photo_param['error'];

	$query_param  = $this->_search_class->get_query_param();
	$query        = $query_param['search_query'];
	$query_array  = $query_param['search_query_array'];

	$this->set_param_out( $query );

	$this->set_flag_highlight( true );
	$this->set_keyword_array( $query_array );

	$init_param = $this->list_build_init_param( true );
	$param      = $this->list_build_detail_common( _SR_SEARCH, $total, $rows );
	$navi_param = $this->list_build_navi( $total, $limit );

	$this->list_assign_xoops_header();

	$arr = array(
		'show_search' => true ,
		'show_search_lang_keytooshort' => $error ,
	);

	$ret = array_merge( $arr, $param, $init_param, $navi_param, $query_param );
	return $this->add_show_js_windows( $ret );
}

function _get_photos( $query, $orderby, $limit, $start )
{
	$rows = null;

	$this->_search_class->get_post_get_param();
	$this->_search_class->set_query( $query );

	$ret = $this->_search_class->parse_query();
	if ( !$ret ) {
		$arr = array(
			'total' => 0 ,
			'rows'  => null ,
			'error' => true ,
		);
		return $arr;
	}

	$sql_query = $this->_search_class->build_sql_query( 'item_search' );
	$total     = $this->_public_class->get_count_by_search( $sql_query );

	if ( $total > 0 ) {
		$rows = $this->_public_class->get_rows_by_search_orderby( $sql_query, $orderby, $limit, $start );
	}

	$arr = array(
		'total' => $total ,
		'rows'  => $rows ,
		'error' => false ,
	);

	return $arr; 
}

// --- class end ---
}

?>