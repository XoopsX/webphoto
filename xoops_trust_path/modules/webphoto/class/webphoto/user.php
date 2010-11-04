<?php
// $Id: user.php,v 1.3 2010/11/04 02:23:19 ohwada Exp $

//=========================================================
// webphoto module
// 2010-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-03 K.OHWADA
// build_rows_for_rss()
// 2010-05-10 K.OHWADA
// build_total_for_detail()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_user
//=========================================================
class webphoto_user extends webphoto_base_this
{
	var $_public_class;

	var $_PHOTO_LIST_USER_ORDER = 'item_uid ASC, item_id DESC';
	var $_PHOTO_LIST_USER_GROUP = 'item_uid';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_user( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_public_class
		=& webphoto_photo_public::getInstance( $dirname, $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_user( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function page_sel( $param )
{
	if (( $param != _C_WEBPHOTO_UID_DEFAULT ) &&
	    ( $param >= 0 )) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// list
//---------------------------------------------------------
function build_rows_for_list()
{
	$list_rows = $this->_item_handler->get_rows_by_groupby_orderby( 
		$this->_PHOTO_LIST_USER_GROUP , $this->_PHOTO_LIST_USER_ORDER );
	if ( !is_array($list_rows) || !count($list_rows) ) {
		return false;
	}

	$arr = array();
	foreach ( $list_rows as $row )
	{
		$uid = intval( $row['item_uid'] );

		$photo_row = null;

		$title = $this->build_show_uname( $uid );
		$link  = 'index.php/user/'. $uid .'/';

		$total = $this->_public_class->get_count_by_uid( $uid );
		$photo_rows = $this->_public_class->get_rows_by_uid_orderby(
			$uid, $this->_PHOTO_LIST_UPDATE_ORDER, $this->_PHOTO_LIST_LIMIT );

		if ( isset($photo_rows[0]) ) {
			$photo_row = $photo_rows[0] ;
		}

		$arr[] = array( $title, $uid, $total, $photo_row );
	}

	return $arr;
}

//---------------------------------------------------------
// detail
//---------------------------------------------------------
function build_total_for_detail( $uid )
{
	$title = $this->build_show_info_morephotos( $uid );
	$total = $this->_public_class->get_count_by_uid( $uid );

	return array( $title, $total );
}

function build_rows_for_detail( $uid, $orderby, $limit=0, $start=0 )
{
	return $this->_public_class->get_rows_by_uid_orderby( 
		$uid, $orderby, $limit, $start );
}

//---------------------------------------------------------
// rss
//---------------------------------------------------------
function build_rows_for_rss( $uid, $orderby, $limit=0, $start=0 )
{
	return $this->build_rows_for_detail( 
		$uid, $orderby, $limit, $start );
}

// --- class end ---
}

?>