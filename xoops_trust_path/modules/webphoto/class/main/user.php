<?php
// $Id: user.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

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
// class webphoto_main_user
//=========================================================
class webphoto_main_user extends webphoto_show_list
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_user( $dirname , $trust_dirname )
{
	$this->webphoto_show_list( $dirname , $trust_dirname );
	$this->init_preload();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_user( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
// overwrite
function list_sel()
{
	if (( $this->_param != $this->_UID_DEFAULT ) &&
	    ( $this->_param >= 0 )) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// list
//---------------------------------------------------------
// overwrite
function list_get_photo_list()
{
	$groupby = 'photo_uid';
	$orderby = 'photo_uid ASC, photo_id DESC';
	$list_rows = $this->_photo_handler->get_rows_by_groupby_orderby( $groupby , $orderby );
	if ( !is_array($list_rows) || !count($list_rows) ) {
		return false;
	}

	$arr = array();
	foreach ( $list_rows as $row )
	{
		$uid = intval( $row['photo_uid'] );

		$photo_row = null;

		$title = $this->build_show_uname( $uid );
		$link    = 'index.php/user/'. $uid .'/';

		$total = $this->_photo_handler->get_count_public_by_uid( $uid );
		$photo_rows = $this->_photo_handler->get_rows_public_by_uid_orderby(
			$uid, $this->_PHOTO_LIST_ORDER, $this->_PHOTO_LIST_LIMIT );
		if ( isset($photo_rows[0]) ) {
			$photo_row = $photo_rows[0] ;
		}

		$arr[] = $this->list_build_photo_array(
			$title, $uid, $total, $photo_row );

	}
	
	return $arr;
}

//---------------------------------------------------------
// detail list
//---------------------------------------------------------
// overwrite
function list_build_detail( $uid )
{
	$rows    = null ;
	$limit   = $this->_MAX_PHOTOS;
	$start   = $this->pagenavi_calc_start( $limit );
	$orderby = $this->get_orderby_by_post();

	$init_param = $this->list_build_init_param( true );

	$title = $this->build_show_info_morephotos( $uid ) ;
	$where = $this->_photo_handler->build_where_public_by_uid( $uid );
	$total = $this->_photo_handler->get_count_by_where( $where );

	if ( $total > 0 ) {
		$rows = $this->_photo_handler->get_rows_by_where_orderby(
			$where, $orderby, $limit, $start );
	}

	$param      = $this->list_build_detail_common( $title, $total, $rows );
	$navi_param = $this->list_build_navi( $total, $limit );

	$this->list_assign_xoops_header();

	$ret = array_merge( $param, $init_param, $navi_param );
	return $this->add_box_list( $ret );
}

// --- class end ---
}

?>