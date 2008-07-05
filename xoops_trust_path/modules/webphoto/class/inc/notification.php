<?php
// $Id: notification.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
function notify( $dirname, $category, $item_id )
{
	$this->_init( $dirname );

	$item = array();

	switch ( $category )
	{
		case 'global':
			$item['name'] = '';
			$item['url']  = '';
			break;

		case 'category':
			$item['name'] = $this->_get_cat_title( $item_id );
			$item['url']  = $this->_get_url( $category, $item_id ) ;
			break;

		case 'photo':
			$item['name'] = $this->_get_photo_title( $item_id );
			$item['url']  = $this->_get_url( $category, $item_id ) ;
			break;
	}

	return $item;
}

function _init( $dirname )
{
	$this->init_handler( $dirname );
	$this->_init_xoops_config( $dirname );

	$this->_INDEX_URL = $this->_MODULE_URL .'/index.php';
}

function _get_url( $category, $item_id )
{
	if ( $this->_cfg_use_pathinfo ) {
		$url = $this->_MODULE_URL .'/index.php/'. $category .'/'. $item_id .'/' ;
	} else {
		$url = $this->_MODULE_URL .'/index.php?fct='. $category .'&amp;p='. $item_id ;
	}
	return $url;
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