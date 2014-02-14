<?php
// $Id: embed_build.php,v 1.2 2010/06/16 22:24:47 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-06-06 K.OHWADA
// get_xml_params()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_embed_build
//=========================================================
class webphoto_edit_embed_build extends webphoto_edit_base
{
	var $_embed_class ;

	var $_item_row    = null;
	var $_title       = null ;
	var $_description = null ;
	var $_url         = null ;
	var $_thumb       = null ;
	var $_duration    = null ;
	var $_tags        = null ;
	var $_script      = null ;

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
	$item_description    = $row['item_description'] ;
	$item_duration       = $row['item_duration'] ;
	$item_embed_type     = $row['item_embed_type'] ;
	$item_embed_src      = $row['item_embed_src'] ;
	$item_embed_text     = $row['item_embed_text'] ;
	$item_external_thumb = $row['item_external_thumb'] ;
	$item_siteurl        = $row['item_siteurl'];

	if ( ! $this->is_type( $row ) ) {
		return 1 ;	// no action
	}

	if ( $item_embed_src || $item_embed_text ) {
		$row['item_kind'] = _C_WEBPHOTO_ITEM_KIND_EMBED ;

	} else {
		return _C_WEBPHOTO_ERR_EMBED;
	}

	$row['item_displaytype'] = _C_WEBPHOTO_DISPLAYTYPE_EMBED ;

	$this->clear_params();
	$this->set_params( $this->get_xml_params( $row ) );

// title
	if ( empty($item_title) ) {
		$row['item_title'] = $this->build_title( $row ) ;
	}

// duration
	if ( empty($item_duration) ) {
		$row['item_duration'] = $this->build_duration( $row ) ;
	}

// external thumb
	if ( empty($item_external_thumb) ) {
		$row['item_external_thumb'] = $this->build_thumb( $row ) ;
	}

// embed text
	if ( empty($item_embed_text) ) {
		$row['item_embed_text'] = $this->build_script( $row ) ;
	}

// siteurl
	if ( empty($item_siteurl) ) {
		$row['item_siteurl'] = $this->build_url( $row ) ;
	}

// description
	$description = $this->build_description( $row ) ;
	if ( $item_description && $description ) {
		$row['item_description'] .= "\n\n".$description;
	} elseif ( empty($item_description) ) {
		$row['item_description'] = $description;
	}

	$row = $this->build_item_row_icon_if_empty( $row, $this->_THUMB_EXT_DEFAULT );

	$this->_item_row = $row ;
	return 0 ;	// OK
}

function clear_params()
{
	$this->_title       = null ;
	$this->_description = null ;
	$this->_url         = null ;
	$this->_thumb       = null ;
	$this->_duration    = null ;
	$this->_tags        = null ;
	$this->_script      = null ;
}

function set_params( $params )
{
	if ( isset( $params['title'] ) ) {
		$this->_title = $params['title'] ;
	}
	if ( isset( $params['description'] ) ) {
		$this->_description = $params['description'] ;
	}
	if ( isset( $params['url'] ) ) {
		$this->_url = $params['url'] ;
	}
	if ( isset( $params['thumb'] ) ) {
		$this->_thumb = $params['thumb'] ;
	}
	if ( isset( $params['duration'] ) ) {
		$this->_duration = $params['duration'] ;
	}
	if ( isset( $params['tags'] ) ) {
		$this->_tags = $params['tags'] ;
	}
	if ( isset( $params['script'] ) ) {
		$this->_script = $params['script'] ;
	}
}

function get_xml_params( $row )
{
	$embed_type = $row['item_embed_type'];
	$embed_src  = $row['item_embed_src'];
	return $this->_embed_class->get_xml_params( $embed_type, $embed_src );
}

function build_title( $row )
{
	if ( $this->_title ) {
		return $this->_title;
	}

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
	if ( $this->_thumb ) {
		return $this->_thumb;
	}

	$embed_type = $row['item_embed_type'];
	$embed_src  = $row['item_embed_src'];
	return $this->_embed_class->build_thumb( $embed_type, $embed_src );
}

function build_description( $row )
{
	if ( $this->_description ) {
		return $this->_description;
	}
	return null;
}

function build_url( $row )
{
	if ( $this->_url ) {
		return $this->_url;
	}
	return null;
}

function build_duration( $row )
{
	if ( $this->_duration ) {
		return $this->_duration;
	}
	return null;
}

function build_script( $row )
{
	if ( $this->_script ) {
		return $this->_script;
	}
	return null;
}

function get_tags()
{
	return $this->_tags;
}

function get_item_row()
{
	return $this->_item_row ;
}

// --- class end ---
}

?>