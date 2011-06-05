<?php
// $Id: notification.php,v 1.7 2011/06/05 07:23:40 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-06-04 K.OHWADA
// webphoto_inc_uri
// 2009-12-06 K.OHWADA
// Fatal error: Call to undefined method webphoto_inc_base_ini()
// 2009-11-11 K.OHWADA
// webphoto_inc_handler -> webphoto_inc_base_ini
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-08-24 K.OHWADA
// table_photo -> table_item
// 2008-07-01 K.OHWADA
// used use_pathinfo
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_notification
//=========================================================
// Fatal error: Call to undefined method webphoto_inc_base_ini()
class webphoto_inc_notification extends webphoto_inc_base_ini
{
	var $_uri_class;

	var $_INDEX_URL ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_notification( $dirname , $trust_dirname )
{
	$this->webphoto_inc_base_ini();
	$this->init_base_ini( $dirname , $trust_dirname );
	$this->init_handler( $dirname );

	$this->_INDEX_URL = $this->_MODULE_URL .'/index.php';

	$this->_uri_class =& webphoto_inc_uri::getSingleton( $dirname );
}

function &getSingleton( $dirname , $trust_dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_notification( $dirname , $trust_dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function notify( $category, $id )
{
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

function _get_url( $category, $id )
{
	return $this->_uri_claas->build_full_uri_mode_param( $category, $id );
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

// --- class end ---
}

?>