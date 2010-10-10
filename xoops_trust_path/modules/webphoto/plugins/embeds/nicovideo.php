<?php
// $Id: nicovideo.php,v 1.2 2010/10/10 11:02:10 ohwada Exp $

//=========================================================
// webphoto module
// 2010-06-06 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// src for nm****
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_embed_nicovideo
//
// http://www.nicovideo.jp/watch/sm7389627
//
// <script type="text/javascript" src="http://ext.nicovideo.jp/thumb_watch/sm7389627">
//
// <iframe width="312" height="176" src="http://ext.nicovideo.jp/thumb/sm7389627" scrolling="no" style="border:solid 1px #CCC;" frameborder="0">
// <a href="http://www.nicovideo.jp/watch/sm7389627">
//=========================================================

class webphoto_embed_nicovideo extends webphoto_embed_base
{

// this word is written by UTF-8
	var $_DESCRIPTION_REMOVE = '前→.*';

function webphoto_embed_nicovideo()
{
	$this->webphoto_embed_base( 'nicovideo' );
	$this->set_url( 'http://www.nicovideo.jp/watch/' );
	$this->set_sample( 'sm7389627' );
}

function embed( $src, $width, $height )
{
	$str = $this->build_embed_script( $src, $width, $height );
	return $str;
}

function link( $src )
{
	return $this->build_link( $src );
}

function width()
{
	return 312;
}

function height()
{
	return 176;
}

function desc()
{
	return $this->build_desc();
}

//---------------------------------------------------------
// xml
//---------------------------------------------------------
function support_params()
{
	return $this->build_support_params();
}

function get_xml_params( $src )
{
	$url  = 'http://www.nicovideo.jp/api/getthumbinfo/'.$src;
	$cont = $this->get_remote_file( $url );
	if ( empty($cont) ) {
		return false;
	}

	$xml    = $this->get_simplexml( $cont );
	$status = $this->get_obj_attributes( $xml, 'status' );
	if ( $status != 'ok' ) {
		return false;
	}

	$thumb = $this->get_obj_property( $xml, 'thumb' );
	if ( !is_object($thumb) ) {
		return false;
	}

	$arr = array(
		'title'       => $this->get_xml_title(       $thumb ),
		'description' => $this->get_xml_description( $thumb ),
		'url'         => $this->get_xml_url(         $thumb ),
		'thumb'       => $this->get_xml_thumb(       $thumb ),
		'duration'    => $this->get_xml_duration(    $thumb ),
		'tags'        => $this->get_xml_tags(        $thumb ),
		'script'      => $this->build_xml_script( $src ),
	);
	return $arr;
}

function get_xml_title( $thumb )
{
	$str = $this->get_obj_property( $thumb, 'title');
	$str = $this->convert_from_utf8( strval($str) );
	return $str;
}

function get_xml_description( $thumb )
{
	$str = $this->get_obj_property( $thumb, 'description');
	$str = preg_replace( '/'.$this->_DESCRIPTION_REMOVE.'/', '', $str );
	$str = $this->convert_from_utf8( strval($str) );
	return $str;
}

function get_xml_url( $thumb )
{
	$str = $this->get_obj_property( $thumb, 'watch_url');
	$str = strval($str);
	return $str;
}

function get_xml_thumb( $thumb )
{
	$str = $this->get_obj_property( $thumb, 'thumbnail_url');
	$str = strval($str);
	return $str;
}

function get_xml_duration( $thumb )
{
	$str = $this->get_obj_property( $thumb, 'length');
	$arr = explode( ':', $str );
	$ret = ( $arr[0] * 60 ) + $arr[1];
	return $ret;
}

function get_xml_tags( $thumb )
{
	$tags = $this->get_obj_property( $thumb, 'tags');
	$arr  = $this->get_obj_property( $tags,  'tag');
	$arr  = $this->obj_array_to_str_array(  $arr );
	$arr  = $this->convert_array_from_utf8( $arr );
	return $arr;
}

function build_xml_script( $src )
{
	$str = $this->build_embed_script_with_repalce( $src );
	return $str;
}

function build_embed_script( $src, $width, $height )
{
	$url  = 'http://ext.nicovideo.jp/thumb_watch/'.$src.'?w='.$width.'&h='.$height;
	$str  = $this->build_script_begin( $url );
	$str .= '<!--so.addParam("wmode", "transparent");-->';
	$str .= $this->build_script_end();
	return $str;
}

// --- class end ---
}
?>