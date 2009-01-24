<?php
// $Id: embed_build.php,v 1.1 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_embed_build
//=========================================================
class webphoto_edit_embed_build extends webphoto_edit_base
{
	var $_embed_class ;

	var $_item_row = null;

	var $_THUMB_EXT_DEFAULT = 'embed';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_embed_build( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base( $dirname , $trust_dirname );

	$this->_embed_class  =& webphoto_embed::getInstance( $dirname, $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_embed_build( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// public 
//---------------------------------------------------------
function is_type( $row )
{
	if ( $row['item_embed_type'] ) {
		return true ;
	}
	return false;
}

function build( $row )
{
	$this->_item_row = $row ;

	$item_title          = $row['item_title'] ;
	$item_embed_type     = $row['item_embed_type'] ;
	$item_embed_src      = $row['item_embed_src'] ;
	$item_embed_text     = $row['item_embed_text'] ;
	$item_external_thumb = $row['item_external_thumb'] ;

	if ( ! $this->is_type( $row ) ) {
		return 1 ;	// no action
	}

	if ( $item_embed_src || $item_embed_text ) {
		$row['item_kind'] = _C_WEBPHOTO_ITEM_KIND_EMBED ;

	} else {
		return _C_WEBPHOTO_ERR_EMBED;
	}

	$row['item_displaytype'] = _C_WEBPHOTO_DISPLAYTYPE_EMBED ;

	if ( empty($item_title) ) {
		$row['item_title'] = $this->build_title( $row ) ;
	}

// plugin thumb
	if ( empty($item_external_thumb) ) {
		$row['item_external_thumb'] = $this->build_thumb( $row ) ;
	}

	$row = $this->build_item_row_icon_if_empty( $row, $this->_THUMB_EXT_DEFAULT );

	$this->_item_row = $row ;
	return 0 ;	// OK
}

function build_title( $row )
{
	$title = null;

	$embed_type = $row['item_embed_type'];
	$embed_src  = $row['item_embed_src'];

	if ( empty( $embed_type ) ) {
		return null ;
	}
	if ( empty( $embed_src ) ) {
		return null ;
	}

	$title  = $embed_type ;
	$title .= ' : ';
	$title .= $embed_src ;
	return $title ;
}

function build_thumb( $row )
{
	$embed_type = $row['item_embed_type'];
	$embed_src  = $row['item_embed_src'];
	return $this->_embed_class->build_thumb( $embed_type, $embed_src );
}

function get_item_row()
{
	return $this->_item_row ;
}

// --- class end ---
}

?>