<?php
// $Id: photo_navi.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_navi
//=========================================================
class webphoto_photo_navi extends webphoto_lib_error
{
	var $_item_handler;
	var $_image_class;

	var $_script   = null ;
	var $_id_array = null ;

	var $_MARK_ID_FIRST = '<b>[&lt;</b>';
	var $_MARK_ID_LAST  = '<b>&gt;]</b>';
	var $_MARK_ID_PREV  = '<b>Prev</b>';
	var $_MARK_ID_NEXT  = '<b>Next</b>';

	var $_max_small_width    = _C_WEBPHOTO_SMALL_WIDTH ;
	var $_max_small_height   = _C_WEBPHOTO_SMALL_HEIGHT ;
	var $_max_current_width  = _C_WEBPHOTO_SMALL_CURRENT_WIDTH ;
	var $_max_current_height = _C_WEBPHOTO_SMALL_CURRENT_HEIGHT ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_navi( $dirname , $trust_dirname )
{
	$this->webphoto_lib_error();

	$this->_item_handler =& webphoto_item_handler::getInstance( 
		$dirname , $trust_dirname );
	$this->_image_class  =& webphoto_show_image::getInstance( 
		$dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo_navi( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// set parameter
//---------------------------------------------------------
function set_mark_id_prev( $val )
{
	$this->_MARK_ID_PREV = $val;
}

function set_mark_id_next( $val )
{
	$this->_MARK_ID_NEXT = $val;
}

//---------------------------------------------------------
// build pagenavi
// use id (1.2.3...)
//---------------------------------------------------------
function build_navi( $script, $id_array, $id_current, $window=7 )
{
	if ( !is_array($id_array) ) {
		return '';
	}

	$total = count( $id_array ) ;	
	if ( $total <= 1 ) {
		return '';
	}

	$pos = array_search( $id_current , $id_array ) ;

	$this->_script   = $script;
	$this->_id_array = $id_array;

	$half = $window / 2 ;

	$navi  = '';
	$navi .= $this->build_link_id( 0, $this->_MARK_ID_FIRST );
	$navi .= ' &nbsp; ';

	// prev mark
	if ( $this->get_id_from_array( 0 ) != $id_current ) {
		$navi .= $this->build_link_id( ($pos - 1), $this->_MARK_ID_PREV );
	}

	if ( $total > $window ) {
		if ( $pos > $half ) {
			if( $pos > round( $total - $half - 1 ) ) {
				$start = $total - $window + 1 ;
			} else {
				$start = round( $pos - $half ) + 1 ;
			}
		} else {
			$start = 1 ;
		}
	} else {
		$start = 1 ;
	}

	for ( $i = $start; $i < $total + 1 && $i < $start + $window ; $i++ ) 
	{
		$flag_current = false ;
		if ( $this->get_id_from_array( $i - 1 ) == $id_current ) {
			$flag_current = true ;
		}
		$navi .= $this->build_link_photo( ($i - 1), $flag_current );
	}

	// next mark
	if ( $this->get_id_from_array( $total - 1 ) != $id_current ) {
		$navi .= $this->build_link_id( ($pos + 1), $this->_MARK_ID_NEXT );
	}

	$navi .= ' &nbsp; ';
	$navi .= $this->build_link_id( ($total - 1), $this->_MARK_ID_LAST );

	return $navi;
}

function build_link_photo( $num, $flag_current )
{
	$title = null;

	$item_id  = $this->get_id_from_array( $num );
	$item_row = $this->_item_handler->get_cached_row_by_id( $item_id ) ;
	if ( is_array($item_row) ) {
		$title = $item_row['item_title'] ;
		$param = $this->_image_class->build_image_by_item_row( $item_row, false );
		if ( is_array($param) ) {
			$img = $this->build_img( $param, $title, $flag_current );
		}
	}

	if ( empty($img) ) {
		$img = $num + 1 ;
		if ( $flag_current ) {
			$str = ' (<b>'. $img .'</b>) ';
			return $str;
		}
	}

	

	return $this->build_link( $item_id, $img, $title );
}

function build_img( $param, $title, $flag_current )
{
	$thumb_src    = $param['img_thumb_src'] ;
	$thumb_width  = $param['img_thumb_width'] ;
	$thumb_height = $param['img_thumb_height'] ;

	if ( empty($thumb_src) ) {
		return false ;
	}

	list( $width, $height )
		= $this->adjust_size( $thumb_width, $thumb_height, $flag_current );

	$title_s = $this->sanitize( $title );
	$src_s   = $this->sanitize( $thumb_src );

	if ( $width && $height ) {
		$img = '<img src="'. $src_s.'" alt="'. $title_s .'" width="'. $width .'" height="'. $height .'" />';
	} else {
		$img = '<img src="'. $src_s.'" alt="'. $title_s .'" width="'. $width .'" />';
	}

	return $img ;
}

function build_link_id( $num, $name )
{
	$title = null;

	$item_id  = $this->get_id_from_array( $num );
	$item_row = $this->_item_handler->get_cached_row_by_id( $item_id ) ;
	if ( is_array($item_row) ) {
		$title = $item_row['item_title'] ;
	}

	return $this->build_link( $item_id, $name, $title );
}

function build_link( $extra, $name, $title )
{
	$href = $this->_script. $extra;

 	if ( $title ) {
		$str = '<a href="'. $href. '" title="'. $this->sanitize($title) .'">';
	} else {
		$str = '<a href="'. $href. '" >';
	}

	$str .= $name ."</a> \n";
	return $str;
}

function get_id_from_array( $num )
{
	if ( isset( $this->_id_array[ $num ] ) ) {
		return  $this->_id_array[ $num ];
	}
	return false;
}

//---------------------------------------------------------
// adjust
//---------------------------------------------------------
function adjust_size( $width, $height, $flag_current )
{
	if ( $flag_current ) {
		return $this->adjust_current_size( $width, $height );
	}
	return $this->adjust_small_size( $width, $height );
}

function adjust_small_size( $width, $height )
{
	list( $new_width, $new_height ) = 
		$this->_image_class->adjust_image_size(
		$width, $height, $this->_max_small_width, $this->_max_small_height );

	if ( $new_width && $new_height ) {
		return array( $new_width, $new_height );
	}

	return array( $this->_max_small_width, 0 );
}

function adjust_current_size( $width, $height )
{
	list( $new_width, $new_height ) = 
		$this->_image_class->adjust_image_size( 
		$width, $height, $this->_max_current_width, $this->_max_current_height );

	if ( $new_width && $new_height ) {
		return array( $new_width, $new_height );
	}

	return array( $this->_max_current_width, 0 );
}

// --- class end ---
}

?>