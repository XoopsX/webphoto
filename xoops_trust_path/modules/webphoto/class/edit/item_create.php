<?php
// $Id: item_create.php,v 1.1 2010/03/19 00:23:38 ohwada Exp $

//=========================================================
// webphoto module
// 2010-03-18 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_item_create
//=========================================================
class webphoto_edit_item_create extends webphoto_item_handler
{
	var $_xoops_class;
	var $_multibyte_class;

	var $_is_japanese;
	var $_ini_item_datetime_default;
	var $_ini_item_exif_length;
	var $_ini_item_content_length;
	var $_ini_item_search_length;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_item_create( $dirname , $trust_dirname )
{
	$this->webphoto_item_handler( $dirname, $trust_dirname );

	$this->_xoops_class     =& webphoto_xoops_base::getInstance();
	$this->_multibyte_class =& webphoto_lib_multibyte::getInstance();

	$this->_is_japanese = $this->_xoops_class->is_japanese( _C_WEBPHOTO_JPAPANESE ) ;

	$this->_ini_item_datetime_default = $this->get_ini('item_datetime_default') ;
	$this->_ini_item_exif_length    = $this->get_ini('item_exif_length') ;
	$this->_ini_item_content_length = $this->get_ini('item_content_length') ;
	$this->_ini_item_search_length  = $this->get_ini('item_search_length') ;
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_item_create( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// item handler
//---------------------------------------------------------
function format_and_insert( $row, $flag_force=false )
{
	return $this->insert( 
		$this->format_row( $row ), $flag_force );
}

function format_and_update( $row, $flag_force=false )
{
	return $this->update( 
		$this->format_row( $row ), $flag_force );
}

function format_row( $row )
{
	$row['item_datetime'] = $this->format_datetime( $row['item_datetime'] );
	$row['item_exif']     = $this->format_exif( $row['item_exif'] );
	$row['item_content']  = $this->format_content( $row['item_content'] );
	$row['item_search']   = $this->format_search( $row['item_search'] );
	return $row;
}

function format_datetime( $str )
{
	if ( $str ) {
		return $str;
	}
	return $this->_ini_item_datetime_default ;
}

function format_exif( $str )
{
	if ( strlen($str) < $this->_ini_item_exif_length ) {
		return $str;
	}

	$str = $this->_multibyte_class->convert_encoding( $str, 'ASCII', 'UTF-8' );
	$str = substr( $str, 0, $this->_ini_item_exif_length );
	return $str;
}

function format_content( $str )
{
	return $this->format_text_common( $str, $this->_ini_item_content_length );
}

function format_search( $str )
{
	return $this->format_text_common( $str, $this->_ini_item_search_length );
}

function format_text_common( $str, $length )
{
	if ( strlen($str) < $length ) {
		return $str;
	}

	$str = substr( $str, 0, $length );
	if ( $this->_is_japanese ) {
		$str = $this->format_text_japanese( $str );
	}
	return $str;
}

function format_text_japanese( $str )
{
	switch ( _CHARSET )
	{
	case 'EUC-JP';
		$str = $this->convert_encoding_text( $str, 'UTF-8' );
		break;

	case 'UTF-8';
		$str = $this->convert_encoding_text( $str, 'EUC-JP' );
		break;
	}
	return $str;
}

function convert_encoding_text( $str, $encode )
{
	$str = $this->_multibyte_class->convert_encoding( $str, $encode,  _CHARSET );
	$str = $this->_multibyte_class->convert_encoding( $str, _CHARSET, $encode );
	return $str;
}

// --- class end ---
}

?>