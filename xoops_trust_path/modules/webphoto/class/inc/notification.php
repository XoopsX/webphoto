<?php
// $Id: notification.php,v 1.3 2008/08/25 19:28:05 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// table_photo -> table_item
// 2008-07-01 K.OHWADA
// used use_pathinfo
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_notification
//=========================================================
class webphoto_inc_notification extends webphoto_inc_handler
{
	var $_cfg_use_pathinfo = false;

	var $_INDEX_URL ;

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
function notify( $dirname, $category, $id )
{
	$this->_init( $dirname );

	$info = array();

	switch ( $category )
	{
		case 'global':
			$info['name'] = '';
			$info['url']  = '';
			break;

		case 'category':
			$info['name'] = $this->_get_cat_title( $id );
			$info['url']  = $this->_get_url( $category, $id ) ;
			break;

		case 'photo':
			$info['name'] = $this->_get_item_title( $id );
			$info['url']  = $this->_get_url( $category, $id ) ;
			break;
	}

	return $info;
}

function _init( $dirname )
{
	$this->init_handler( $dirname );
	$this->_init_xoops_config( $dirname );

	$this->_INDEX_URL = $this->_MODULE_URL .'/index.php';
}

function _get_url( $category, $id )
{
	if ( $this->_cfg_use_pathinfo ) {
		$url = $this->_MODULE_URL .'/index.php/'. $category .'/'. $id .'/' ;
	} else {
		$url = $this->_MODULE_URL .'/index.php?fct='. $category .'&amp;p='. $id ;
	}
	return $url;
}

//---------------------------------------------------------
// handler
//---------------------------------------------------------
function _get_item_title( $item_id )
{
	$row = $this->get_item_row_by_id( $item_id );
	if ( isset( $row['item_title'] ) ) {
		return  $row['item_title'];
	}
	return false;
}

function _get_cat_title( $cat_id )
{
	$row = $this->get_cat_row_by_id( $cat_id );
	if ( isset( $row['cat_title'] ) ) {
		return  $row['cat_title'];
	}
	return false;
}

//---------------------------------------------------------
// xoops_config
//---------------------------------------------------------
function _init_xoops_config( $dirname )
{
	$config_handler =& webphoto_inc_config::getInstance();
	$config_handler->init( $dirname );

	$this->_cfg_use_pathinfo = $config_handler->get_by_name('use_pathinfo');
}

// --- class end ---
}

?>