<?php
// $Id: item_cat_handler.php,v 1.3 2009/04/11 14:23:34 ohwada Exp $

//=========================================================
// webphoto module
// 2008-12-12 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-04-10 K.OHWADA
// add key in get_rows_item_cat_by_where_orderby()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_item_cat_handler
//=========================================================
class webphoto_item_cat_handler extends webphoto_lib_handler
{
	var $_item_table;
	var $_cat_table;
	var $_tag_table;
	var $_p2t_table;

	var $_cfg_perm_item_read = false;

	var $_PERM_ALLOW_ALL  = _C_WEBPHOTO_PERM_ALLOW_ALL;
	var $_PERM_DENOY_ALL  = _C_WEBPHOTO_PERM_DENOY_ALL;
	var $_PERM_SEPARATOR  = _C_WEBPHOTO_PERM_SEPARATOR;

	var $_AREA_NS = 1.0;
	var $_AREA_EW = 1.0;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_item_cat_handler( $dirname )
{
	$this->webphoto_lib_handler( $dirname );

	$this->_item_table = $this->prefix_dirname( 'item' );
	$this->_cat_table  = $this->prefix_dirname( 'cat' );
	$this->_tag_table  = $this->prefix_dirname( 'tag' );
	$this->_p2t_table  = $this->prefix_dirname( 'p2t' );

	$constpref = strtoupper( '_P_' . $dirname. '_' ) ;
	$this->set_debug_sql_by_const_name(   $constpref.'DEBUG_SQL' );
	$this->set_debug_error_by_const_name( $constpref.'DEBUG_ERROR' );

}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_item_cat_handler( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_perm_item_read( $val )
{
	$this->_cfg_perm_item_read = (bool)$val ;
}

//---------------------------------------------------------
// get count
//---------------------------------------------------------
function get_count_item_cat_by_name_param( $name, $param )
{
	$where = $this->build_where_item_cat_by_name_param( $name, $param );
	return $this->get_count_item_cat_by_where( $where );
}

function get_count_item_by_name_param( $name, $param )
{
	$where = $this->build_where_by_name_param( $name, $param );
	return $this->get_count_item_by_where( $where );
}

//---------------------------------------------------------
// get rows
//---------------------------------------------------------
function get_rows_item_cat_by_name_param_orderby( 
	$name, $param, $orderby, $limit=0, $offset=0, $key=null )
{
	$where = $this->build_where_item_cat_by_name_param( $name, $param );
	return $this->get_rows_item_cat_by_where_orderby( $where, $orderby, $limit, $offset, $key );
}

function get_rows_item_by_name_param_orderby( $name, $param, $orderby, $limit=0, $offset=0, $key=null )
{
	$where = $this->build_where_by_name_param( $name, $param );
	return $this->get_rows_item_by_where_orderby( $where, $orderby, $limit, $offset, $key );
}

//---------------------------------------------------------
// get id array
//---------------------------------------------------------
function get_id_array_item_by_name_param_orderby( $name, $param, $orderby, $limit=0, $offset=0 )
{
	$where = $this->build_where_by_name_param( $name, $param );
	return $this->get_id_array_item_by_where_orderby( $where, $orderby, $limit, $offset );
}

//---------------------------------------------------------
// item cat where
//---------------------------------------------------------
function build_where_item_cat_by_name_param( $name, $param )
{
	$where  = $this->convert_item_field( 
		$this->build_where_by_name_param( $name, $param ) ) ;
	$where .= ' AND '. $this->build_where_cat_groups();
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
		case 'public' :
			$where = $this->build_where_public();
			break;

		case 'imode' :
			$where = $this->build_where_imode();
			break;

		case 'photo' :
			$where = $this->build_where_photo();
			break;

		case 'photo_catid' :
			$where = $this->build_where_photo_by_catid( $param );
			break;

		case 'catid' :
			$where = $this->build_where_by_catid( $param );
			break;

		case 'catid_array' :
			$where = $this->build_where_by_catid_array( $param );
			break;

		case 'datetime' :
			$where = $this->build_where_by_datetime( $param );
			break;

		case 'like_datetime' :
			$where = $this->build_where_by_like_datetime( $param );
			break;

		case 'gmap_latest' :
			$where = $this->build_where_by_gmap_latest( $param );
			break;

		case 'gmap_catid_array' :
			$where = $this->build_where_by_gmap_catid_array( $param );
			break;

		case 'gmap_area' :
			$where = $this->build_where_by_gmap_area( $param );
			break;

		case 'place' :
			$where = $this->build_where_by_place( $param );
			break;

		case 'place_array' :
			$where = $this->build_where_by_place_array( $param );
			break;

		case 'search' :
			$where = $this->build_where_by_search( $param );
			break;

		case 'uid' :
			$where = $this->build_where_by_uid( $param );
			break;

		default:
//			xoops_error( "$name $param" );
			break;
	}

	return $where;
}

function build_where_public()
{
	$where = ' item_status > 0 ';
	if ( $this->_cfg_perm_item_read > 0 ) {
		$where .= ' AND '. $this->build_where_item_groups() ;
	}
	return $where;
}

function build_where_imode()
{
	$where  = $this->build_where_public();
	$where .= ' AND '. $this->build_where_item_imode();
	return $where;
}

function build_where_photo()
{
	$where  = $this->build_where_public();
	$where .= ' AND '. $this->build_where_item_photo();
	return $where;
}

function build_where_photo_by_catid( $cat_id )
{
	$where  = $this->build_where_public();
	$where .= ' AND '. $this->build_where_item_photo();
	$where .= ' AND item_cat_id='. intval($cat_id);
	return $where;
}

function build_where_by_catid( $cat_id )
{
//echo " build_where_by_catid( $cat_id ) ";

	$where  = $this->build_where_public();
	$where .= ' AND item_cat_id='.intval($cat_id);
	return $where;
}

function build_where_by_catid_array( $catid_array )
{
	$where  = $this->build_where_public() ;
	$where .= ' AND '. $this->build_where_item_catid_array( $catid_array );
	return $where;
}

function build_where_by_datetime( $datetime )
{
	$where  = $this->build_where_public();
	$where .= ' AND item_datetime ='. $this->quote($datetime);
	return $where;
}

function build_where_by_like_datetime( $datetime )
{
	$where  = $this->build_where_public();
	$where .= ' AND item_datetime LIKE '. $this->quote( $datetime.'%' );
	return $where;
}

function build_where_by_place( $place )
{
	$where  = $this->build_where_public();
	$where .= ' AND item_place ='. $this->quote($place);
	return $where;
}

function build_where_by_place_array( $place_array )
{
	$where  = $this->build_where_public();
	$where .= ' AND '.$this->build_where_item_place_array( $place_array );
	return $where;
}

function build_where_by_search( $sql_query )
{
	$where  = $this->build_where_public();
	$where .= ' AND '.$sql_query;
	return $where;
}

function build_where_by_uid( $uid )
{
	$where  = $this->build_where_public();
	$where .= ' AND item_uid='.intval($uid);
	return $where;
}

function build_where_item_imode()
{
	$where  = " ( item_ext='gif' ";
	$where .= "OR item_ext='jpg' ";
	$where .= "OR item_ext='jpeg' ";
	$where .= "OR item_ext='3gp' ";
	$where .= "OR item_ext='3g2' )";
	return $where;
}

function build_where_item_photo()
{
	$where  = " ( item_ext='gif' ";
	$where .= "OR item_ext='png' ";
	$where .= "OR item_ext='jpg' ";
	$where .= "OR item_ext='jpeg' ) ";
	return $where;
}

function build_where_item_catid_array( $catid_array )
{
	$where  = ' item_cat_id IN ( ' ;
	foreach( $catid_array as $id ) {
		$where .= intval($id) .', ';
	}

// 0 means to belong no category	
	$where .= ' 0 )';
	return $where;
}

function build_where_item_place_array( $place_array )
{
	return $this->build_where_by_keyword_array( $place_array, 'AND', 'item_place' );
}

function build_where_by_keyword_array_catid( $keyword_array, $cat_id )
{
	$where_key = $this->build_where_by_keyword_array( $keyword_array );

	$where_cat = null;
	if ( $cat_id > 0 ) {
		$where_cat = "item_cat_id=".intval($cat_id);
	}

	if ( $where_key && $where_cat ) {
		$where = $where_key .' AND '. $where_cat ;
		return $where;
	} elseif ( $where_key ) {
		return $where_key;
	} elseif ( $where_cat ) {
		return $where_cat;
	}

	return null;
}

function build_where_by_keyword_array( $keyword_array, $andor='AND', $name='item_search' )
{
	if ( !is_array($keyword_array) || !count($keyword_array) ) {
		return null;
	}

	switch ( strtolower($andor) )
	{
		case 'exact':
			$where = $this->build_where_keyword_single( $keyword_array[0], $name );
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
			$arr[] = $this->build_where_keyword_single( $keyword, $name ) ;
		}
	}

	if ( is_array( $arr ) && count( $arr ) ) {
		$glue  = ' '. $andor_glue .' ';
		$where = ' ( '. implode( $glue , $arr ) .' ) ' ;
		return $where;
	}

	return null;
}

function build_where_keyword_single( $str, $name='item_search' )
{
	$text = $name ." LIKE '%" . addslashes( $str ) . "%'" ;
	return $text;
}

function build_where_by_itemid_array( $id_array )
{
	$where = '';
	foreach( $id_array as $id ) {
		$where .= 'item_id='. intval($id) .' OR ';
	}

// 0 means to belong no category
	$where .= '0';
	return $where;
}

function build_where_item_groups()
{
	return $this->build_where_groups( 'item_perm_read' );
}

function build_where_cat_groups()
{
	return $this->build_where_groups( 'c.cat_perm_read' );
}

function build_where_groups( $name )
{
	$groups = $this->_xoops_groups ;

	$pre  = '%'. $this->_PERM_SEPARATOR ; 
	$post = $this->_PERM_SEPARATOR . '%' ;

	$where = $name .'='. $this->quote( $this->_PERM_ALLOW_ALL ) ;

	if ( is_array($groups) && count($groups) ) {
		foreach ( $groups as $group ) 
		{
			$where .= ' OR '. $name .' LIKE ';
			$where .= $this->quote( $pre . intval($group) . $post ) ;
		}
	}

	return ' ( '. $where .' ) ';
}

//---------------------------------------------------------
// build gmap
//---------------------------------------------------------
function build_where_by_gmap_latest()
{
	$where  = $this->build_where_public();
	$where .= ' AND '. $this->build_where_item_gmap();
	return $where;
}

function build_where_by_gmap_catid_array( $catid_array )
{
	$where  = $this->build_where_public();
	$where .= ' AND '. $this->build_where_item_catid_array( $catid_array );
	$where .= ' AND '. $this->build_where_item_gmap();
	return $where;
}

function build_where_by_gmap_area( $param )
{
	if ( ! is_array($param) ) {
		return null;
	}

	list( $id, $lat, $lon ) = $param ;

	$where   = $this->build_where_public();
	$where  .= ' AND '. $this->build_where_item_gmap();
	$where  .= ' AND '. $this->build_where_item_gmap_area( $lat, $lon );
	$where  .= ' AND item_id <> '. intval($id);

	return $where;
}

function build_where_item_gmap()
{
	$where  = ' ( item_gmap_latitude <> 0 ';
	$where .= 'OR item_gmap_longitude <> 0 ';
	$where .= 'OR item_gmap_zoom <> 0 ) ';
	return $where;
}

function build_where_item_gmap_area( $lat, $lon )
{
	$north = $this->adjust_latitude(  $lat + $this->_AREA_NS );
	$south = $this->adjust_latitude(  $lat - $this->_AREA_NS );
	$east  = $this->adjust_longitude( $lon + $this->_AREA_EW );
	$west  = $this->adjust_longitude( $lon - $this->_AREA_EW );

	$where  = ' item_gmap_latitude > '.floatval($south);
	$where .= ' AND item_gmap_latitude  < '.floatval($north);
	$where .= ' AND item_gmap_longitude > '.floatval($west);
	$where .= ' AND item_gmap_longitude < '.floatval($east);
	return $where;
}

function adjust_latitude( $lat )
{
// north pole
	if ( $lat > 90 ) {
		$lat = 90;

// south pole
	} elseif ( $lat < -90 ) {
		$lat = -90;
	}

	return $lat;
}

function adjust_longitude( $lon )
{
// international date line
	if ( $lon > 180 ) {
		$lon = -360 + $lon;
	} elseif ( $lon < -180 ) {
		$lon = 360 + $lon;
	}
	return $lon;
}

//---------------------------------------------------------
// sql
//---------------------------------------------------------
function get_count_item_cat_by_where( $where )
{
	$sql  = 'SELECT COUNT(*) FROM ';
	$sql .= $this->_item_table .' i ';
	$sql .= ' INNER JOIN '. $this->_cat_table .' c ';
	$sql .= ' ON i.item_cat_id = c.cat_id ';
	$sql .= ' WHERE '. $where;
	return $this->get_count_by_sql( $sql );
}

function get_count_item_by_where( $where )
{
	$sql  = 'SELECT COUNT(*) FROM ';
	$sql .= $this->_item_table ;
	$sql .= ' WHERE '. $where;
	return $this->get_count_by_sql( $sql );
}

function get_rows_item_cat_by_where_orderby( $where, $orderby, $limit=0, $offset=0, $key=null )
{
	$sql  = 'SELECT i.* FROM ';
	$sql .= $this->_item_table .' i ';
	$sql .= ' INNER JOIN '. $this->_cat_table .' c ';
	$sql .= ' ON i.item_cat_id = c.cat_id ';
	$sql .= ' WHERE '. $where;
	$sql .= ' ORDER BY '. $orderby;
	return $this->get_rows_by_sql( $sql, $limit, $offset, $key );
}

function get_rows_item_by_where_orderby( $where, $orderby, $limit=0, $offset=0, $key=null )
{
	$sql  = 'SELECT * FROM ';
	$sql .= $this->_item_table ;
	$sql .= ' WHERE '. $where;
	$sql .= ' ORDER BY '. $orderby;
	return $this->get_rows_by_sql( $sql, $limit, $offset, $key );
}

function get_id_array_item_by_where_orderby( $where, $orderby, $limit=0, $offset=0 )
{
	$sql  = 'SELECT item_id';
	$sql .= ' FROM '.$this->_item_table;
	$sql .= ' WHERE '. $where;
	$sql .= ' ORDER BY '. $orderby;
	return $this->get_first_rows_by_sql( $sql, $limit, $offset );
}

// --- class end ---
}

?>