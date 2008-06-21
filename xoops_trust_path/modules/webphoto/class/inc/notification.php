<?php
// $Id: notification.php,v 1.1 2008/06/21 12:22:26 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_notification
//=========================================================
class webphoto_inc_notification extends webphoto_inc_handler
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_notification()
{
	$this->webphoto_inc_handler();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_notification();
	}
	return $instance;
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function notify( $dirname, $category, $item_id )
{
	$this->init_handler( $dirname );

	$item = array();

	switch ( $category )
	{
		case 'global':
			$item['name'] = '';
			$item['url']  = '';
			break;

		case 'category':
			$item['name'] = $this->_get_cat_title( $item_id );
			$item['url']  = $this->_MODULE_URL .'/index.php/category/'. $item_id .'/' ;
			break;

		case 'photo':
			$item['name'] = $this->_get_photo_title( $item_id );
			$item['url']  = $this->_MODULE_URL .'/index.php/photo/'. $item_id .'/' ;
			break;
	}

	return $item;
}

//---------------------------------------------------------
// photo handler
//---------------------------------------------------------
function _get_photo_title( $photo_id )
{
	$row = $this->_get_photo_row( $photo_id );
	if ( isset( $row['photo_title'] ) ) {
		return $row['photo_title'];
	}
	return false;
}

function _get_photo_row( $photo_id )
{
	$sql  = 'SELECT * FROM '.$this->prefix_dirname( 'photo' );
	$sql .= ' WHERE photo_id='. intval($photo_id);
	return $this->get_row_by_sql( $sql );
}

//---------------------------------------------------------
// cat handler
//---------------------------------------------------------
function _get_cat_title( $cat_id )
{
	$row = $this->_get_cat_row( $cat_id );
	if ( isset( $row['cat_title'] ) ) {
		return $row['cat_title'];
	}
	return false;
}

function _get_cat_row( $cat_id )
{
	$sql  = 'SELECT * FROM '.$this->prefix_dirname( 'cat' );
	$sql .= ' WHERE cat_id='. intval($cat_id);
	return $this->get_row_by_sql( $sql );
}

// --- class end ---
}

?>