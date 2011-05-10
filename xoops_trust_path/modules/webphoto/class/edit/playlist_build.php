<?php
// $Id: playlist_build.php,v 1.5 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// create_cache()
// 2009-11-11 K.OHWADA
// webphoto_lib_error -> webphoto_base_ini
// 2009-04-19 K.OHWADA
// BUG: Notice [PHP]: Undefined variable: laylist_feed
// 2009-01-25 K.OHWADA
// not use webphoto_edit_base()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_playlist_build
//=========================================================
class webphoto_edit_playlist_build extends webphoto_base_ini
{
	var $_playlist_class ;
	var $_icon_build_class;
	var $_msg_class;

	var $_item_row = null;

	var $_THUMB_EXT_DEFAULT = 'playlist';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_playlist_build( $dirname , $trust_dirname )
{
	$this->webphoto_base_ini( $dirname, $trust_dirname );

	$this->_playlist_class   =& webphoto_playlist::getInstance( 
		$dirname , $trust_dirname );
	$this->_icon_build_class =& webphoto_edit_icon_build::getInstance( $dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_playlist_build( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// public 
//---------------------------------------------------------
function is_type( $row )
{
	if ( $row['item_playlist_type'] ) {
		return true ;
	}
	return false;
}

// called before crete item
function build_row( $row )
{
	if ( ! $this->is_type( $row ) ) {
		return 1 ;	// no action
	}

	$this->_item_row = $row ;

	$item_title          = $row['item_title'] ;
	$item_playlist_type  = $row['item_playlist_type'] ;
	$item_playlist_feed  = $row['item_playlist_feed'] ;
	$item_playlist_dir   = $row['item_playlist_dir'] ;

	if ( $item_playlist_feed ) {
		$row['item_kind'] = _C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED ;

	} elseif( $item_playlist_dir ) {
		$row['item_kind'] = _C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR ;

	} else {
		return _C_WEBPHOTO_ERR_PLAYLIST;
	}

	if ( empty($item_title) ) {
		$row['item_title'] = $this->build_title( $row ) ;
	}

	switch ( $item_playlist_type )
	{
// general
		case _C_WEBPHOTO_PLAYLIST_TYPE_AUDIO :
		case _C_WEBPHOTO_PLAYLIST_TYPE_VIDEO :
		case _C_WEBPHOTO_PLAYLIST_TYPE_FLASH :
			$row['item_displaytype'] = _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ;
			$row['item_player_id']   = _C_WEBPHOTO_PLAYER_ID_PLAYLIST ;
			break;

// image
		case _C_WEBPHOTO_PLAYLIST_TYPE_IMAGE :
			$row['item_displaytype'] = _C_WEBPHOTO_DISPLAYTYPE_IMAGEROTATOR ;
			break;
	}

	$row = $this->build_row_icon_if_empty( $row, $this->_THUMB_EXT_DEFAULT );

	$this->_item_row = $row ;
	return 0 ;	// OK
}

// called after crete item
function create_cache( $row )
{
	if ( ! $this->is_type( $row ) ) {
		return 1 ;	// no action
	}

	$this->_item_row = $row ;
	$item_id = $row['item_id'] ;

// playlist cache
	$row['item_playlist_cache'] = $this->_playlist_class->build_name( $item_id ) ;

	$ret = $this->_playlist_class->create_cache_by_item_row( $row );
	if ( !$ret ) {
		$this->set_error( $this->_playlist_class->get_errors() );
		return _C_WEBPHOTO_ERR_PLAYLIST;
	}

	$this->_item_row = $row ;
	return 0 ;	// OK
}

function build_title( $row )
{
	$playlist_dir  = $row['item_playlist_dir'];
	$playlist_feed = $row['item_playlist_feed'];

	$title = null ;

	if ( $playlist_dir ) {
		$title = $playlist_dir ;

	} elseif ( $playlist_feed ) {

// BUG: Notice [PHP]: Undefined variable: laylist_feed
		$param = parse_url( $playlist_feed );

		if ( isset($param['host']) ) {
			$title = $param['host'] ;
		} else {
			$title = date( "YmdHis" );
		}
	}

	if ( $title ) {
		$title = 'playlist: '.$title;
	}

	return $title ;
}

function get_item_row()
{
	return $this->_item_row ;
}

//---------------------------------------------------------
// icon
//---------------------------------------------------------
function build_row_icon_if_empty( $row, $ext=null )
{
	return $this->_icon_build_class->build_row_icon_if_empty( $row, $ext );
}

// --- class end ---
}

?>