<?php
// $Id: tagcloud.php,v 1.1 2008/12/18 13:24:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-12-12 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_tagcloud
//=========================================================
class webphoto_inc_tagcloud extends webphoto_inc_handler
{
	var $_item_table;
	var $_cat_table;
	var $_tag_table;
	var $_p2t_table;

	var $_cfg_use_pathinfo   = false ;
	var $_cfg_perm_cat_read  = 0 ;
	var $_cfg_perm_item_read = 0 ;

	var $_PERM_ALLOW_ALL = _C_WEBPHOTO_PERM_ALLOW_ALL;
	var $_PERM_DENOY_ALL = _C_WEBPHOTO_PERM_DENOY_ALL;
	var $_PERM_SEPARATOR = _C_WEBPHOTO_PERM_SEPARATOR;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_tagcloud()
{
	$this->webphoto_inc_handler();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_tagcloud();
	}
	return $instance;
}

//---------------------------------------------------------
// init
//---------------------------------------------------------
function init( $dirname )
{
	$this->_DIRNAME    = $dirname;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'.$dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'.$dirname;

	$this->_item_table = $this->prefix_dirname( 'item' );
	$this->_cat_table  = $this->prefix_dirname( 'cat' );
	$this->_tag_table  = $this->prefix_dirname( 'tag' );
	$this->_p2t_table  = $this->prefix_dirname( 'p2t' );
}

function set_use_pathinfo( $val )
{
	$this->_cfg_use_pathinfo = (bool)$val ;
}

function set_perm_cat_read( $val )
{
	$this->_cfg_perm_cat_read = intval($val) ;
}

function set_perm_item_read( $val )
{
	$this->_cfg_perm_item_read = intval($val) ;
}

//---------------------------------------------------------
// tagcloud
//---------------------------------------------------------
function build_tagcloud( $limit )
{
	$rows = $this->get_tag_rows( $limit );
	if ( !is_array($rows) || !count($rows) ) {
		return array() ;
	}
	return $this->build_tagcloud_by_rows( $rows );
}

function build_tagcloud_by_rows( $rows )
{
	$cloud_class =& new webphoto_lib_cloud();

	$uri_class   =& webphoto_inc_uri::getInstance();
	$uri_class->init( $this->_DIRNAME );
	$uri_class->set_use_pathinfo( $this->_cfg_use_pathinfo );

	ksort($rows);

	foreach ( array_keys($rows) as $i )
	{
		$name  = $rows[$i]['tag_name'];
		$count = $rows[$i]['photo_count'];
		$link  = $uri_class->build_tag( $name );
		$cloud_class->addElement( $name, $link, $count );
	}

	return $cloud_class->build();
}

//---------------------------------------------------------
// get tag rows
//---------------------------------------------------------
function get_tag_rows( $limit=0, $offset=0 )
{
	if (( $this->_cfg_perm_cat_read  == _C_WEBPHOTO_OPT_PERM_READ_ALL )&&
	    ( $this->_cfg_perm_item_read == _C_WEBPHOTO_OPT_PERM_READ_ALL )) {

		return $this->_get_tag_rows_with_count( 
			'tag_id', $limit, $offset );

	} else {
		return $this->_get_tag_rows_with_count_cat( 
			'tag_id', $limit, $offset );
	}
}

//---------------------------------------------------------
// get item count
//---------------------------------------------------------
function get_item_count_by_tag( $tag )
{
	if ( $this->_cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		return $this->_get_item_count_by_tag( $tag );

	} else {
		return $this->_get_item_count_by_tag_for_cat( $tag );
	}
}

//---------------------------------------------------------
// get item rows
//---------------------------------------------------------
function get_item_id_array_by_tag( $tag, $orderby, $limit=0, $offset=0 )
{
	$orderby = $this->convert_item_field( $orderby ) ;

	if ( $this->_cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		return $this->_get_item_id_array_by_tag( 
			$tag, $orderby, $limit, $offset );

	} else {
		return $this->_get_item_id_array_by_tag_for_cat(
			$tag, $orderby, $limit, $offset );
	}
}

//---------------------------------------------------------
// where
//---------------------------------------------------------
function _build_where_by_tag_for_cat( $tag )
{
	$where  = $this->_build_where_by_tag( $tag );
	$where .= ' AND ';
	$where .= $this->build_where_cat_perm_read() ;
	return $where;
}

function _build_where_by_tag( $tag )
{
	$where  = $this->convert_item_field( 
		$this->build_where_public_with_item() ) ;
	$where .= ' AND t.tag_name='.$this->quote($tag);
	return $where;
}

//---------------------------------------------------------
// sql
//---------------------------------------------------------
function _get_item_count_by_tag_for_cat( $tag )
{
	$sql  = 'SELECT COUNT(DISTINCT i.item_id) ';
	$sql .= ' FROM '. $this->_p2t_table .' p2t ';
	$sql .= ' INNER JOIN '. $this->_item_table .' i ';
	$sql .= ' ON i.item_id = p2t.p2t_photo_id ';
	$sql .= ' INNER JOIN '. $this->_tag_table .' t ';
	$sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
	$sql .= ' INNER JOIN '. $this->_cat_table .' c ';
	$sql .= ' ON i.item_cat_id = c.cat_id ';
	$sql .= ' WHERE '. $this->_build_where_by_tag_for_cat( $tag ) ;
	return $this->get_count_by_sql( $sql );
}

function _get_item_count_by_tag( $tag )
{
	$sql  = 'SELECT COUNT(DISTINCT i.item_id) ';
	$sql .= ' FROM '. $this->_p2t_table .' p2t ';
	$sql .= ' INNER JOIN '. $this->_item_table .' i ';
	$sql .= ' ON i.item_id = p2t.p2t_photo_id ';
	$sql .= ' INNER JOIN '. $this->_tag_table .' t ';
	$sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
	$sql .= ' WHERE '. $this->_build_where_by_tag( $tag ) ;
	return $this->get_count_by_sql( $sql );
}

function _get_item_id_array_by_tag_for_cat( $tag, $orderby, $limit=0, $offset=0 )
{
	$sql  = 'SELECT DISTINCT i.item_id ';
	$sql .= ' FROM '. $this->_p2t_table .' p2t ';
	$sql .= ' INNER JOIN '. $this->_item_table .' i ';
	$sql .= ' ON i.item_id = p2t.p2t_photo_id ';
	$sql .= ' INNER JOIN '. $this->_tag_table .' t ';
	$sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
	$sql .= ' INNER JOIN '. $this->_cat_table .' c ';
	$sql .= ' ON i.item_cat_id = c.cat_id ';
	$sql .= ' WHERE '. $this->_build_where_by_tag_for_cat( $tag ) ;
	$sql .= ' ORDER BY '. $orderby;

	return $this->get_first_rows_by_sql( $sql, $limit, $offset );
}

function _get_item_id_array_by_tag( $tag, $orderby, $limit=0, $offset=0 )
{
	$sql  = 'SELECT DISTINCT i.item_id ';
	$sql .= ' FROM '. $this->_p2t_table .' p2t ';
	$sql .= ' INNER JOIN '. $this->_item_table .' i ';
	$sql .= ' ON i.item_id = p2t.p2t_photo_id ';
	$sql .= ' INNER JOIN '. $this->_tag_table .' t ';
	$sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
	$sql .= ' WHERE '. $this->_build_where_by_tag( $tag ) ;
	$sql .= ' ORDER BY '. $orderby;

	return $this->get_first_rows_by_sql( $sql, $limit, $offset );
}

function _get_tag_rows_with_count_cat( $key, $limit=0, $offset=0 )
{
	$sql  = 'SELECT t.*, COUNT(*) AS photo_count ';
	$sql .= ' FROM '. $this->_tag_table.' t ';
	$sql .= ' INNER JOIN '. $this->_p2t_table .' p2t ';
	$sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
	$sql .= ' INNER JOIN '. $this->_item_table .' i ';
	$sql .= ' ON i.item_id = p2t.p2t_photo_id ';
	$sql .= ' INNER JOIN '. $this->_cat_table .' c ';
	$sql .= ' ON i.item_cat_id = c.cat_id ';
	$sql .= ' WHERE '. $this->build_where_public_with_item_cat() ;
	$sql .= ' GROUP BY t.tag_id ';
	$sql .= ' ORDER BY photo_count DESC';
	return $this->get_rows_by_sql( $sql, $limit, $offset, $key );
}

function _get_tag_rows_with_count( $key, $limit=0, $offset=0 )
{
	$sql  = 'SELECT t.*, COUNT(*) AS photo_count ';
	$sql .= ' FROM '. $this->_tag_table.' t ';
	$sql .= ' INNER JOIN '. $this->_p2t_table .' p2t ';
	$sql .= ' ON t.tag_id = p2t.p2t_tag_id ';
	$sql .= ' GROUP BY t.tag_id ';
	$sql .= ' ORDER BY photo_count DESC';
	return $this->get_rows_by_sql( $sql, $limit, $offset, $key );
}

// --- class end ---
}

?>