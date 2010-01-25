<?php
// $Id: category.php,v 1.1 2010/01/25 10:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2010-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_category
//=========================================================
class webphoto_category extends webphoto_show_photo
{
	var $_public_class;
	var $_catlist_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_category( $dirname , $trust_dirname )
{
	$this->webphoto_show_photo( $dirname , $trust_dirname );

	$this->_public_class
		=& webphoto_photo_public::getInstance( $dirname, $trust_dirname );
	$this->_catlist_class  
		=& webphoto_inc_catlist::getSingleton( $dirname , $trust_dirname );

	$this->_cfg_cat_child     = $this->_config_class->get_by_name('cat_child');
	$this->_cfg_perm_cat_read = $this->_config_class->get_by_name('perm_cat_read');
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_category( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// list
//---------------------------------------------------------
function build_photo_list_for_list()
{
	$cat_rows = $this->_public_class->get_cat_all_tree_array();
	if ( !is_array($cat_rows) || !count($cat_rows) ) {
		return false;
	}

	$photo_list = array();
	foreach ( $cat_rows as $cat_row )
	{
		$cat_id = $cat_row['cat_id'];

		$show_catpath = false;
		$photo        = null;

		$catpath = $this->_public_class->build_cat_path( $cat_id );
		if ( is_array($catpath) && count($catpath) ) {
			$show_catpath = true;
		}

		list( $photo_row, $total, $this_sum ) = $this->get_photo_for_list( $cat_id );

		if ( is_array($photo_row) && count($photo_row) ) {
			$photo = $this->build_photo_show( $photo_row );
			$photo_rows[ $photo_row['item_id'] ] = $photo_row ;
		}

		$cat_desc_disp = $this->_cat_handler->build_show_desc_disp( $cat_row ) ; 

		$photo_list[] = array(
			'title'            => '' ,
			'title_s'          => '' ,
			'link'             => '' ,
			'link_s'           => '' ,
			'total'            => $total ,
			'photo'            => $photo ,
			'sum'              => $this_sum ,
			'show_catpath'     => $show_catpath ,
			'catpath'          => $catpath ,
			'cat_desc_disp'    => $cat_desc_disp , 
			'cat_summary_disp' => $this->build_cat_summary_disp( $cat_desc_disp )
		);
	}

	return array($photo_list, $photo_rows);
}

function get_photo_for_list( $cat_id )
{
	$photo_row = null;

	list( $rows, $total, $this_sum ) =
		$this->get_rows_total_by_catid( 
			$cat_id, $this->_PHOTO_LIST_UPDATE_ORDER, $this->_PHOTO_LIST_LIMIT ) ;

	if ( is_array($rows) && count($rows) ) {
		$photo_row = $rows[0];
	}

	return array( $photo_row, $total, $this_sum );
}

function build_cat_summary_disp( $desc )
{
	return $this->_multibyte_class->build_summary( $desc, $this->_cfg_cat_summary );
}

//---------------------------------------------------------
// detail
//---------------------------------------------------------
function build_rows_for_detail( $cat_id, $orderby, $limit, $start )
{
	$row = $this->_public_class->get_cat_row( $cat_id );

	if ( !is_array( $row ) ) {
		$arr = array(
			'cat_title'   => '',
			'photo_total' => 0,
			'photo_rows'  => null,
			'photo_sum'   => 0,
		);
		return $arr;
	}

	$cat_title = $row['cat_title'];

	list( $photo_rows, $total, $this_sum ) =
		$this->get_rows_total_by_catid( $cat_id, $orderby, $limit, $start );

	$arr = array(
		'cat_title'   => $cat_title,
		'photo_total' => $total,
		'photo_rows'  => $photo_rows,
		'photo_sum'   => $this_sum,
	);

	return $arr;

}

function get_rows_total_by_catid( $cat_id, $orderby, $limit=0, $offset=0 )
{
	$rows     = null ; 
	$total    = 0;
	$this_sum = 0;
	$name     = 'catid_array';

	if ( ! $this->check_cat_perm_read_by_catid( $cat_id ) ) {
		return array( $rows, $total, $this_sum );
	}

	$array_cat_id = array( $cat_id );
	$catid_array  = $this->_catlist_class->get_cat_parent_all_child_id_by_id( $cat_id );

	$this_sum = $this->_public_class->get_count_by_name_param( $name, $array_cat_id );
	$total    = $this->_public_class->get_count_by_name_param( $name, $catid_array );

	switch( $this->_cfg_cat_child ) 
	{
		case _C_WEBPHOTO_CAT_CHILD_EMPTY :
			if ( $this_sum > 0 ) {
				$param = $array_cat_id;
			} else {
				$param = $catid_array;
			}
			break;

		case _C_WEBPHOTO_CAT_CHILD_ALWAYS :
			$param = $catid_array;
			break;

		case _C_WEBPHOTO_CAT_CHILD_NON :
		default:
			$param = $array_cat_id;
			break;
	}

	$rows = $this->_public_class->get_rows_by_name_param_orderby( 
		$name, $param, $orderby, $limit, $offset ) ;

	return array( $rows, $total, $this_sum );
}

function check_cat_perm_read_by_catid( $cat_id )
{
	if ( $this->_cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		return true;
	}
	if ( $this->_catlist_class->check_cat_perm_by_catid( $cat_id ) ) {
		return true ;
	}
	return false;
}

// --- class end ---
}

?>