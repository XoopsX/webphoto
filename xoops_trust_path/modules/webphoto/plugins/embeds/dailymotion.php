<?php
// $Id: dailymotion.php,v 1.2 2010/06/16 22:24:47 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-06-06 K.OHWADA
// get_xml_params()
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_embed_dailymotion
//
// http://www.dailymotion.com/cluster/tech/video/x3y6yk_no-more-keyboardsmicrosoft_tech
//
// <object width="420" height="253">
// <param name="movie" value="http://www.dailymotion.com/swf/x3y6yk"></param>
// <param name="allowFullScreen" value="true"></param>
// <param name="allowScriptAccess" value="always"></param>
// <embed src="http://www.dailymotion.com/swf/x3y6yk" type="application/x-shockwave-flash" width="420" height="253" allowFullScreen="true" allowScriptAccess="always"></embed>
// </object>
//=========================================================
class webphoto_embed_dailymotion extends webphoto_embed_base
{

function webphoto_embed_dailymotion()
{
	$this->webphoto_embed_base( 'dailymotion' );
	$this->set_url( 'http://www.dailymotion.com/cluster/tech/video/' );
}

function embed( $src, $width, $height )
{
	$movie = 'http://www.dailymotion.com/swf/'.$src;
	$extra = 'allowFullScreen="true" allowScriptAccess="always"';

	$str  = $this->build_object_begin( $width, $height );
	$str .= $this->build_param( 'movie', $movie );
	$str .= $this->build_param( 'allowFullScreen',   'true' );
	$str .= $this->build_param( 'allowScriptAccess', 'always' );
	$str .= $this->build_embed_flash( $movie, $width, $height, $extra );
	$str .= $this->build_object_end();
	return $str;
}

function link( $src )
{
	return $this->build_link( $src );
}

function desc()
{
	return $this->build_desc_span( $this->_url_head, 'x3y6yk', '_no-more-keyboardsmicrosoft_tech' );
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

	$url  = 'http://www.dailymotion.com/rss/video/'.$src;
	$cont = $this->get_remote_file( $url );
	if ( empty($cont) ) {
		return false;
	}

	$xml     = $this->get_simplexml( $cont );
	$channel = $this->get_obj_property( $xml,     'channel');
	$item    = $this->get_obj_property( $channel, 'item');
	if ( !is_object($item) ) {
		return false;
	}

	$arr = array(
		'title'       => $this->get_xml_title(       $item ),
		'description' => $this->get_xml_description( $item ),
		'url'         => $this->get_xml_url(         $item ),
		'thumb'       => $this->get_xml_thumb(       $item ),
		'duration'    => $this->get_xml_duration(    $item ),
		'tags'        => $this->get_xml_tags(        $item ),
		'script'      => $this->get_xml_script(      $item ),
	);
	return $arr;
}

function get_xml_title( $item )
{
	$str = $this->get_obj_property( $item, 'title');
	$str = $this->convert_from_utf8( strval($str) );
	return $str;
}

function get_xml_description( $item )
{
	$xpath = $this->get_xpath( $item, '//itunes:summary' );
	$str   = strval( $xpath );
	$str   = $this->convert_from_utf8( $str );
	return $str;
}

function get_xml_url( $item )
{
	$str = $this->get_obj_property( $item, 'link');
	$str = strval($str);
	return $str;
}

function get_xml_thumb( $item )
{
	$xpath = $this->get_xpath( $item, '//media:thumbnail' );
	$attr  = $this->get_obj_method(   $xpath, 'attributes' );
	$str   = $this->get_obj_property( $attr,  'url' );
	$str   = strval($str);
	return $str;
}

function get_xml_duration( $item )
{
	$xpath = $this->get_xpath( $item, '//media:content' );
	$attr  = $this->get_obj_method(   $xpath,  'attributes' );
	$str   = $this->get_obj_property( $attr,   'duration' );
	$str   = strval( $str );
	return $str;
}

function get_xml_tags( $item )
{
	$str = $this->get_xpath( $item, '//itunes:keywords' );
	$arr = $this->str_to_array( $str, ',' );
	$arr = $this->convert_array_from_utf8( $arr );
	return $arr;
}

function get_xml_script( $item )
{
	$str = $this->get_xpath( $item, '//media:player' );
	$str = $this->replace_width_height( $str );
	return $str;
}

// --- class end ---
}

?>