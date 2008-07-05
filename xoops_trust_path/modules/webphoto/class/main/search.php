<?php
// $Id: search.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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

	$query_param = $this->_search_class->get_query_param();
	$query = $query_param['search_query'];
	$this->set_param_out( $query );

	$init_param = $this->list_build_init_param( true );
	$param      = $this->list_build_detail_common( _SR_SEARCH, $total, $rows );
	$navi_param = $this->list_build_navi( $total, $limit );

	$this->list_assign_xoops_header();

	$arr = array(
		'show_search' => true ,
		'show_search_lang_keytooshort' => $error ,
	);

	$ret = array_merge( $arr, $param, $init_param, $navi_param, $query_param );
	return $this->add_box_list( $ret );
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

	$where  = $this->_photo_handler->build_where_public();
	$where .= ' AND '.$this->_search_class->build_sql_query( 'photo_search' );
	$total = $this->_photo_handler->get_count_by_where( $where );

	if ( $total > 0 ) {
		$rows = $this->_photo_handler->get_rows_by_where_orderby( $where, $orderby, $limit, $start );
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