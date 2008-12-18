<?php
// $Id: public.php,v 1.1 2008/12/18 13:24:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-12-12 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_public
//=========================================================
class webphoto_inc_public extends webphoto_inc_handler
{
	var $_cfg_use_pathinfo   = false;
	var $_cfg_workdir        = null;
	var $_cfg_perm_cat_read  = false ;
	var $_cfg_perm_item_read = false ;

	var $_cat_cached = array();

	var $_ITEM_ORDERBY = 'item_time_update DESC, item_id DESC';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_public()
{
	$this->webphoto_inc_handler();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_public();
	}
	return $instance;
}

//---------------------------------------------------------
// item rows
//---------------------------------------------------------
function get_item_rows_for_block( $options, $orderby, $limit=0, $offset=0 )
{
	return $this->get_item_rows_by_name_param_orderby( 
		'block', $options, $orderby, $limit, $offset ) ;
}

function get_item_rows_for_imagemanager( $cat_id, $limit=0, $offset=0 )
{
	return $this->get_item_rows_by_name_param_orderby( 
		'imagemanager_catid', $cat_id, $this->_ITEM_ORDERBY, $limit, $offset ) ;
}

function get_item_rows_for_search( $query_array, $andor, $uid, $limit=0, $offset=0 )
{
	return $this->get_item_rows_by_name_param_orderby( 
		'search', array($query_array, $andor, $uid), $this->_ITEM_ORDERBY, $limit, $offset ) ;
}

function get_item_rows_for_whatsnew( $limit=0, $offset=0 )
{
	return $this->get_item_rows_by_name_param_orderby( 
		'whatsnew', null, $this->_ITEM_ORDERBY, $limit, $offset ) ;
}

function get_item_rows_by_name_param_orderby( $name, $param, $orderby, $limit=0, $offset=0 )
{
	if ( $this->_cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		return $this->get_item_rows_item_by_name_param_orderby( 
			$name, $param, $orderby, $limit, $offset );

	} else {
		return $this->get_item_rows_item_cat_by_name_param_orderby( 
			$name, $param, 
			$this->convert_item_field( $orderby ), 
			$limit, $offset );
	}
}

function get_item_rows_item_cat_by_name_param_orderby( 
	$name, $param, $orderby, $limit=0, $offset=0 )
{
	$where = $this->build_where_item_cat_by_name_param( $name, $param );
	return $this->get_item_rows_by_where_orderby_with_cat( $where, $orderby, $limit, $offset );
}

function get_item_rows_item_by_name_param_orderby( $name, $param, $orderby, $limit=0, $offset=0 )
{
	$where = $this->build_where_by_name_param( $name, $param );
	return $this->get_item_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

//---------------------------------------------------------
// item count
//---------------------------------------------------------
function get_item_count_for_imagemanager( $cat_id )
{
	return $this->get_item_count_by_name_param( 
		'imagemanager_catid', $cat_id ) ;
}

function get_item_count_by_name_param( $name, $param )
{
	if ( $this->_cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		return $this->get_item_count_item_by_name_param( 
			$name, $param );

	} else {
		return $this->get_item_count_item_cat_by_name_param( 
			$name, $param ) ;
	}
}

function get_item_count_item_cat_by_name_param( $name, $param )
{
	$where = $this->build_where_item_cat_by_name_param( $name, $param );
	return $this->get_item_count_by_where_with_cat( $where );
}

function get_item_count_item_by_name_param_orderby( $name, $param )
{
	$where = $this->build_where_by_name_param( $name, $param );
	return $this->get_item_count_by_where( $where );
}

//---------------------------------------------------------
// imagemanager
//---------------------------------------------------------
function get_item_catlist_for_imagemanager( $limit=0, $offset=0 )
{
	if ( $this->_cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		return $this->get_item_catlist_for_imagemanager_with_item( $limit, $offset );

	} else {
		return $this->get_item_catlist_for_imagemanager_with_item_cat( $limit, $offset );
	}
}

function get_item_catlist_for_imagemanager_with_item_cat( $limit=0 , $offset=0 )
{
	$where = $this->build_where_item_cat_by_name_param( 'imagemanager_catlist', null );

	$sql  = 'SELECT i.item_cat_id, COUNT(i.item_id) AS photo_sum ';
	$sql .= ' FROM ' ;
	$sql .= $this->prefix_dirname( 'item' ) .' i ';
	$sql .= ' INNER JOIN ';
	$sql .= $this->prefix_dirname( 'cat' ) .' c ';
	$sql .= ' ON i.item_cat_id = c.cat_id ';
	$sql .= ' WHERE '. $where ;
	$sql .= ' GROUP BY i.item_cat_id' ;
	$sql .= ' ORDER BY i.item_cat_id' ;
	return $this->get_rows_by_sql( $sql, $limit , $offset );
}

function get_item_catlist_for_imagemanager_with_item( $limit=0 , $offset=0 )
{
	$where = $this->build_where_by_name_param( 'imagemanager_catlist', null );

	$sql  = 'SELECT item_cat_id, COUNT(item_id) AS photo_sum ';
	$sql .= ' FROM ' ;
	$sql .= $this->prefix_dirname( 'item' ) ;
	$sql .= ' WHERE '. $where ;
	$sql .= ' GROUP BY item_cat_id' ;
	$sql .= ' ORDER BY item_cat_id' ;
	return $this->get_rows_by_sql( $sql, $limit , $offset );
}

//---------------------------------------------------------
// item cat where
//---------------------------------------------------------
function build_where_item_cat_by_name_param( $name, $param )
{
	$where  = $this->convert_item_field( 
		$this->build_where_by_name_param( $name, $param ) ) ;
	$where .= ' AND '. $this->build_where_cat_perm_read() ;
	return $where;
}

function convert_item_field( $str )
{
	return str_replace( 'item_', 'i.item_', $str );
}

//---------------------------------------------------------
// item where
//---------------------------------------------------------
function build_where_by_name_param( $name, $param )
{
	$where = null ;

	switch( $name )
	{
		case 'block' :
			$where = $this->build_where_for_block( $param );
			break;

		case 'imagemanager_catlist' :
			$where = $this->build_where_for_imagemanager_catlist();
			break;

		case 'imagemanager_catid' :
			$where = $this->build_where_for_imagemanager_catid( $param );
			break;

		case 'search' :
			$where = $this->build_where_for_search( $param );
			break;

		case 'whatsnew' :
			$where = $this->build_where_for_whatsnew();
			break;

		default:
			xoops_error( "$name $param" );
			break;
	}

	return $where;
}

function build_where_for_whatsnew()
{
	return $this->build_where_public_with_item() ;
}

function build_where_for_block( $options )
{
// defined in block.php
	$where_limitation = $this->build_where_block_cat_limitation( $options );

	$where = $this->build_where_public_with_item();
	if ( $where_limitation ) {
		$where .= ' AND '. $where_limitation ;
	}

	return $where ;
}

function build_where_for_imagemanager_catlist()
{
	return $this->build_where_for_imagemanager_image();
}

function build_where_for_imagemanager_catid( $cat_id )
{
	$where  = $this->build_where_for_imagemanager_image();
	$where .= ' AND item_cat_id='.intval($cat_id);
	return $where ;
}

function build_where_for_imagemanager_image()
{
	$where  = $this->build_where_public_with_item() ;
	$where .= ' AND item_kind='.intval( _C_WEBPHOTO_ITEM_KIND_IMAGE ) ;
	$where .= ' AND item_file_id_1 > 0' ;
	$where .= ' AND item_file_id_2 > 0' ;
	return $where ;
}

//---------------------------------------------------------
// item where search
//---------------------------------------------------------
function build_where_for_search( $param )
{
	if ( ! is_array($param)) {
		return null;
	}

	list( $query_array, $andor, $uid ) = $param ;

	$where_search = $this->build_where_for_search_query( $query_array, $andor, $uid );

	$where = $this->build_where_public_with_item() ;
	if ( $where_search ) {
		$where .= ' AND '. $where_search ;
	}

	return $where ;
}

function build_where_for_search_query( $keyword_array, $andor, $uid )
{
	$where_key = $this->build_where_by_keyword_array( $keyword_array, $andor );

	$where_uid = null;
	if ( $uid != 0 ) {
		$where_uid = 'item_uid='. intval($uid);
	}

	$where = null;
	if ( $where_key && $where_uid ) {
		$where = $where_key .' AND '. $where_uid ;
	} elseif ( $where_key ) {
		$where = $where_key;
	} elseif ( $where_uid ) {
		$where = $where_uid;
	}

	return $where;
}

function build_where_by_keyword_array( $keyword_array, $andor='AND' )
{
	if ( !is_array($keyword_array) || !count($keyword_array) ) {
		return null;
	}

	switch ( strtolower($andor) )
	{
		case 'exact':
			$where = $this->_build_where_search_single( $keyword_array[0] );
			return $where;

		case 'or':
			$andor_glue = 'OR';
			break;

		case 'and':
		default:
			$andor_glue = 'AND';
			break;
	}

	$arr = array();

	foreach( $keyword_array as $keyword ) 
	{
		$keyword = trim($keyword);
		if ( $keyword ) {
			$arr[] = $this->build_where_search_single( $keyword ) ;
		}
	}

	if ( is_array( $arr ) && count( $arr ) ) {
		$glue  = ' '. $andor_glue .' ';
		$where = ' ( '. implode( $glue , $arr ) .' ) ' ;
		return $where;
	}

	return null;
}

function build_where_search_single( $str )
{
	$text = "item_search LIKE '%" . addslashes( $str ) . "%'" ;
	return $text;
}

//---------------------------------------------------------
// item filed
//---------------------------------------------------------
function build_item_description( $row )
{
	$myts =& MyTextSanitizer::getInstance();
	return $myts->displayTarea( $row['item_description'] , 0 , 1 , 1 , 1 , 1 , 1 );
}

//---------------------------------------------------------
// cat handler
//---------------------------------------------------------
function get_cat_cached_row_by_id( $id )
{
	if ( isset( $this->_cat_cached[ $id ] ) ) {
		return  $this->_cat_cached[ $id ];
	}

	$row = $this->get_cat_row_by_id( $id );
	if ( is_array($row) ) {
		$this->_cat_cached[ $id ] = $row;
		return $row;
	}

	return null;
}

//---------------------------------------------------------
// auto publish
//---------------------------------------------------------
function auto_publish( $dirname )
{
	$publish_class =& webphoto_inc_auto_publish::getInstance();
	$publish_class->init( $dirname );
	$publish_class->set_workdir( $this->_cfg_workdir );

	$publish_class->auto_publish();
}

//---------------------------------------------------------
// xoops config
//---------------------------------------------------------
function init_xoops_config( $dirname )
{
	$config_handler =& webphoto_inc_config::getInstance();
	$config_handler->init( $dirname );

	$this->_cfg_use_pathinfo   = $config_handler->get_by_name( 'use_pathinfo' );
	$this->_cfg_workdir        = $config_handler->get_by_name( 'workdir' );
	$this->_cfg_perm_cat_read  = $config_handler->get_by_name( 'perm_cat_read' ) ;
	$this->_cfg_perm_item_read = $config_handler->get_by_name( 'perm_item_read' ) ;
}

// --- class end ---
}

?>