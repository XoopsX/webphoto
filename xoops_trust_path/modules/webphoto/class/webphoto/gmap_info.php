<?php
// $Id: gmap_info.php,v 1.3 2008/08/25 19:28:05 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// 2008-07-01 K.OHWADA
// used build_uri_photo()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_gmap_info
//=========================================================
class webphoto_gmap_info extends webphoto_base_this
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_gmap_info( $dirname , $trust_dirname  )
{
	$this->webphoto_base_this( $dirname , $trust_dirname  );

	$this->_IMG_EDIT = '<img src="'. $this->_ICONS_URL.'/edit.png" width="18" height="15" border="0" alt="' ._WEBPHOTO_TITLE_EDIT .'" title="'. _WEBPHOTO_TITLE_EDIT .'" />';
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_gmap_info( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// gmap
//---------------------------------------------------------
function build_info( $param )
{
	return $this->build_info_default( $param );
}

function build_info_default( $param )
{
	$info  = '<div style="text-align:center; font-size: 80%; ">';
	$info .= $this->build_info_thumb(    $param );
	$info .= $this->build_info_title(    $param );
	$info .= $this->build_info_author(   $param );
	$info .= $this->build_info_datetime( $param );
	$info .= $this->build_info_place(    $param );
	$info .= '</div>';

	return $info;
}

function build_info_thumb( $param )
{
	$a_photo   = $this->build_a_photo(   $param );
	$img_thumb = $this->build_img_thumb( $param );

	$str = null;
	if ( $img_thumb && $a_photo ) {
		$str = $a_photo . $img_thumb .'</a><br />';
	} elseif ( $img_thumb ) {
		$str = $img_thumb .'<br />';
	}
	return $str;
}

function build_info_title( $param )
{
	$str = '';

	$title_s = $this->sanitize( $param['item_title'] );
	$a_photo = $this->build_a_photo( $param );

	if ( $this->has_editable_by_uid( $param['item_uid'] ) ) {
		$href = $this->_MODULE_URL.'/index.php?fct=edit&amp;photo_id='.intval($param['item_id']);
		$str .= '<a href="'. $href .'" target="_top" >';
		$str .= $this->_IMG_EDIT;
		$str .= '</a> ';
	}

	if ( $title_s && $a_photo ) {
		$str .= $a_photo . $title_s .'</a><br />';
	} elseif ( $title_s ) {
		$str .= $title_s .'<br />';
	}
	return $str;
}

function build_info_author( $param )
{
	$uid   = intval( $param['item_uid'] );
	$href  = $this->build_uri_user( $uid ) ;
	$uname = $this->get_xoops_uname_by_uid( $uid );
	if ( $uid > 0 ) {
		$str  = '<a href="'. $href .'">';
		$str .= $uname .'</a><br />';
	} else {
		$str = $uname .'<br />';
	}
	return $str;
}

function build_info_datetime( $param )
{
	$datetime_disp = $this->mysql_datetime_to_str( $param['item_datetime'] );
	if ( $datetime_disp ) {
		$str = $datetime_disp .'<br />';
		return $str;
	}
	return null;
}

function build_info_place( $param )
{
	$place_s = $this->sanitize( $param['item_place'] );
	if ( $place_s ) {
		$str = $place_s .'<br />';
		return $str;
	}
	return null;
}

function build_img_thumb( $param )
{
	$title_s = $this->sanitize( $param['item_title'] );
	$url_s   = $this->sanitize( $param['thumb_url'] );
	$width   = intval( $param['thumb_width'] );
	$height  = intval( $param['thumb_height'] );

	$img = null;
	if ( $url_s && $width && $height ) {
		$img = '<img src="'. $url_s .'" width="'. $width .'"  height="'. $height .'" alt="'. $title_s .' "border="0" />';
	} elseif ( $url_s ) {
		$img = '<img src="'. $url_s .'" alt="'. $title_s .'" border="0" />';
	}

	return $img;
}

function build_a_photo( $param )
{
	$href   = $this->build_href_photo(   $param );
	$target = $this->build_target_photo( $param );
	if ( $href && $target ) {
		$str = '<a href="'. $href .'" target="'. $target .'">';
		return $str;
	}
	return null;
}

function build_href_photo( $param )
{
	return $this->build_uri_photo( $param['item_id'] ) ;
}

function build_target_photo( $param )
{
	$str = '_top';
	if ( ! $this->check_normal_ext( $param ) ) {
		$str = '_blank';
	}
	return $str;
}

function check_normal_ext( $param )
{
	return $this->is_normal_ext( $param['item_ext'] );
}

// --- class end ---
}

?>