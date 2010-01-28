<?php
// $Id: category.php,v 1.2 2010/01/28 02:08:13 ohwada Exp $

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
	$cat_rows = $this->_catlist_class->get_cat_all_tree_array();
	if ( !is_array($cat_rows) || !count($cat_rows) ) {
		return false;
	}

	$photo_list = array();
	foreach ( $cat_rows as $cat_row )
	{
		$cat_id = $cat_row['cat_id'];

		$show_catpath = false;
		$photo        = null;

		$catpath = $this->build_catpath( $cat_id );
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
	$row = $this->_catlist_class->get_cat_row_by_catid_perm( $cat_id );

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
	$cfg_cat_child      = $this->_config_class->get_by_name('cat_child');

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

	switch( $cfg_cat_child ) 
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
	$cfg_perm_cat_read = $this->_config_class->get_by_name('perm_cat_read');

	if ( $cfg_perm_cat_read == _C_WEBPHOTO_OPT_PERM_READ_ALL ) {
		return true;
	}
	if ( $this->_catlist_class->check_cat_perm_by_catid( $cat_id ) ) {
		return true ;
	}
	return false;
}

//---------------------------------------------------------
// catpath
//---------------------------------------------------------
function build_catpath( $cat_id )
{
	$rows = $this->_cat_handler->get_parent_path_array( $cat_id );
	if ( !is_array($rows) || !count($rows) ) {
		return false;
	}

	$arr   = array();
	$count = count($rows);
	$last  = $count - 1;

	for ( $i = $last ; $i >= 0; $i-- ) {
		$arr[] = $this->_catlist_class->build_cat_show( $rows[ $i ] );
	}

	$ret = array();
	$ret['list']  = $arr;
	$ret['first'] = $arr[ 0 ];
	$ret['last']  = $arr[ $last ];

	return $ret;
}

//---------------------------------------------------------
// catlist
//---------------------------------------------------------
function build_catlist( $cat_id, $cols_in, $delmita )
{
	$main_width = $this->_config_class->get_by_name('cat_main_width');
	$sub_width  = $this->_config_class->get_by_name('cat_sub_width');

	$show_sub      = $this->get_ini('category_show_sub');
	$show_main_img = $this->get_ini('category_show_main_img');
	$show_sub_img  = $this->get_ini('category_show_sub_img');

	$show = false ;

	$cats = $this->_catlist_class->build_catlist( $cat_id, $show_sub ) ;
	list( $cols, $width ) =
		$this->_catlist_class->calc_width( $cols_in ) ;

	if ( is_array($cats) && count($cats) ) {
		$show = true ;
	}

	$catlist = array(
		'cats'            => $cats ,
		'cols'            => $cols ,
		'width'           => $width ,
		'delmita'         => $delmita ,
		'show_sub'        => $show_sub ,
		'show_main_img'   => $show_main_img ,
		'show_sub_img'    => $show_sub_img ,
		'main_width'      => $main_width ,
		'sub_width'       => $sub_width ,
	);

	$arr = array(
		'show_catlist' => $show,
		'catlist'      => $catlist,
	);

	return $arr ;
}

// --- class end ---
}

?>