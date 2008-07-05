<?php
// $Id: gmap_info.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
function build_info( $row )
{
	return $this->build_info_default( $row );
}

function build_info_default( $row )
{
	$info  = '<div style="text-align:center; font-size: 80%; ">';
	$info .= $this->build_info_thumb( $row );
	$info .= $this->build_info_title( $row );
	$info .= $this->build_info_author( $row );
	$info .= $this->build_info_datetime( $row );
	$info .= $this->build_info_place( $row );
	$info .= '</div>';

	return $info;
}

function build_info_thumb( $row )
{
	$a_photo   = $this->build_a_photo( $row );
	$img_thumb = $this->build_img_thumb( $row );

	$str = null;
	if ( $img_thumb && $a_photo ) {
		$str = $a_photo . $img_thumb .'</a><br />';
	} elseif ( $img_thumb ) {
		$str = $img_thumb .'<br />';
	}
	return $str;
}

function build_info_title( $row )
{
	$str = '';

	$title_s = $this->sanitize( $row['photo_title'] );
	$a_photo = $this->build_a_photo( $row );

	if ( $this->has_editable_by_uid( $row['photo_uid'] ) ) {
		$href = $this->_MODULE_URL.'/index.php?fct=edit&amp;photo_id='.intval($row['photo_id']);
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

function build_info_author( $row )
{
	$uid   = intval( $row['photo_uid'] );
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

function build_info_datetime( $row )
{
	$datetime_disp = $this->mysql_datetime_to_str( $row['photo_datetime'] );
	if ( $datetime_disp ) {
		$str = $datetime_disp .'<br />';
		return $str;
	}
	return null;
}

function build_info_place( $row )
{
	$place_s = $this->sanitize( $row['photo_place'] );
	if ( $place_s ) {
		$str = $place_s .'<br />';
		return $str;
	}
	return null;
}

function build_img_thumb( $row )
{
	$title_s = $this->sanitize( $row['photo_title'] );
	$url_s   = $this->sanitize( $row['photo_thumb_url'] );
	$width   = intval( $row['photo_thumb_width'] );
	$height  = intval( $row['photo_thumb_height'] );

	$img = null;
	if ( $url_s && $width && $height ) {
		$img = '<img src="'. $url_s .'" width="'. $width .'"  height="'. $height .'" alt="'. $title_s .' "border="0" />';
	} elseif ( $url_s ) {
		$img = '<img src="'. $url_s .'" alt="'. $title_s .'" border="0" />';
	}

	return $img;
}

function build_a_photo( $row )
{
	$href   = $this->build_href_photo( $row );
	$target = $this->build_target_photo( $row );
	if ( $href && $target ) {
		$str = '<a href="'. $href .'" target="'. $target .'">';
		return $str;
	}
	return null;
}

function build_href_photo( $row )
{
	return $this->build_uri_photo( $row['photo_id'] ) ;
}

function build_target_photo( $row )
{
	$str = '_top';
	if ( ! $this->check_normal_ext( $row ) ) {
		$str = '_blank';
	}
	return $str;
}

function check_normal_ext( $row )
{
	return $this->is_normal_ext( $row['photo_cont_ext'] );
}

// --- class end ---
}

?>