<?php
// $Id: flash_config.php,v 1.4 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_file_handler
// 2008-12-12 K.OHWADA
// webphoto_item_public
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_flash_config
//=========================================================
class webphoto_main_flash_config extends webphoto_item_public
{
	var $_file_handler;
	var $_player_handler;
	var $_flashvar_handler;
	var $_player_clss;
	var $_post_class;
	var $_xml_class;
	var $_multibyte_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_flash_config( $dirname , $trust_dirname )
{
	$this->webphoto_item_public( $dirname, $trust_dirname );

	$this->_file_handler     
		=& webphoto_file_handler::getInstance( $dirname, $trust_dirname );
	$this->_player_handler   
		=& webphoto_player_handler::getInstance( $dirname, $trust_dirname );
	$this->_flashvar_handler 
		=& webphoto_flashvar_handler::getInstance( $dirname, $trust_dirname );
	$this->_player_clss      
		=& webphoto_flash_player::getInstance( $dirname, $trust_dirname );
	$this->_playlist_class   
		=& webphoto_playlist::getInstance( $dirname, $trust_dirname );

	$this->_post_class       =& webphoto_lib_post::getInstance();
	$this->_xml_class        =& webphoto_lib_xml::getInstance();
	$this->_multibyte_class  =& webphoto_lib_multibyte::getInstance();

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_flash_config( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$item_id  = $this->_post_class->get_get_int('item_id') ;
	$item_row = $this->get_item_row( $item_id );
	if ( ! is_array($item_row ) ) {
		exit();
	}

	$player_id   = $item_row['item_player_id'] ;
	$flashvar_id = $item_row['item_flashvar_id'] ;
	$player_row  = $this->_player_handler->get_row_by_id_or_default( $player_id ) ; 

	$param = array(
		'item_row'       => $item_row , 
		'cont_row'       => $this->_get_file_row_by_name( $item_row, _C_WEBPHOTO_ITEM_FILE_CONT ) , 
		'thumb_row'      => $this->_get_file_row_by_name( $item_row, _C_WEBPHOTO_ITEM_FILE_THUMB ) , 
		'middle_row'     => $this->_get_file_row_by_name( $item_row, _C_WEBPHOTO_ITEM_FILE_MIDDLE ) , 
		'flash_row'      => $this->_get_file_row_by_name( $item_row, _C_WEBPHOTO_ITEM_FILE_VIDEO_FLASH ) ,
		'player_row'     => $player_row , 
		'flashvar_row'   => $this->_flashvar_handler->get_row_by_id_or_default( $flashvar_id ) , 
		'playlist_cache' => $this->_playlist_class->refresh_cache_by_item_row( $item_row ) ,
		'player_style'   => $player_row['player_style'] ,
	);

	$this->_player_clss->set_variables_in_buffer( $param );

	$buffers = $this->_player_clss->get_variable_buffers();
	if ( ! is_array($buffers) ) {
		exit();
	}

// VIEW HIT  Adds 1 if not submitter or admin.
	if ( $this->check_not_owner( $item_row['item_uid'] ) ) {
		$this->_item_handler->countup_views( $item_id, true );
	}

	$var  = '<?xml version="1.0" ?>'."\n";
	$var .= '<config>'."\n";

	foreach ( $buffers as $k => $v )
	{
		$var .= '<'. $k .'>';
		$var .= $this->_xml_utf8( $v[0] );
		$var .= '</'. $k .'>'."\n";
	}

	$var .= '</config>'."\n";

	$this->_http_output('pass');
	header("Content-Type:text/xml; charset=utf-8");
	echo $var;

}

function _get_file_row_by_name( $item_row, $item_name )
{
	if ( isset(    $item_row[ $item_name ] ) ) {
		$file_id = $item_row[ $item_name ] ;
	} else {
		return false;
	}

	if ( $file_id > 0 ) {
		return $this->_file_handler->get_row_by_id( $file_id );
	}

	return false ;
}

function _http_output( $encoding )
{
	return $this->_multibyte_class->m_mb_http_output( $encoding );
}

function _xml_utf8( $str )
{
	return $this->_xml_class->xml_text( 
		$this->_multibyte_class->convert_to_utf8( $str ) );
}

// --- class end ---
}
?>