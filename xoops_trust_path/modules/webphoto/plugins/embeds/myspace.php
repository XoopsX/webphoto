<?php
// $Id: myspace.php,v 1.2 2010/06/16 22:24:47 ohwada Exp $

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
// class webphoto_embed_myspace
//
// http://vids.myspace.com/index.cfm?fuseaction=vids.individual&videoid=2096626
//
// <object width="425px" height="360px" >
// <param name="allowFullScreen" value="true"/>
// <param name="movie" value="http://mediaservices.myspace.com/services/media/embed.aspx/m=2096626,t=1,mt=video,searchID=,primarycolor=,secondarycolor="/>
// <embed src="http://mediaservices.myspace.com/services/media/embed.aspx/m=2096626,t=1,mt=video,searchID=,primarycolor=,secondarycolor=" width="425" height="360" allowFullScreen="true" type="application/x-shockwave-flash" />
// </object>
//=========================================================

class webphoto_embed_myspace extends webphoto_embed_base
{
	var $_TITLE_REMOVE = "\s+ - MySpace Video";
	var $_DESCRIPTION_REMOVE = "\r|\n";

// this word is written by UTF-8
	var $_TITLE_SPLIT  = "さんが投稿した動画";

function webphoto_embed_myspace()
{
	$this->webphoto_embed_base( 'myspace' );
	$this->set_url( 'http://vids.myspace.com/index.cfm?fuseaction=vids.individual&videoid=' );
	$this->set_sample( '57094809' );
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

function desc()
{
	return $this->build_desc();
}

//---------------------------------------------------------
// xml
//---------------------------------------------------------
function build_support_params()
{
	$arr = array(
		'title'       => true ,
		'description' => true ,
		'url'         => true ,
		'thumb'       => true ,
//		'duration'    => false ,
		'tags'        => true ,
		'script'      => true ,
	);
	return $arr;
}

function get_xml_params( $src )
{
	$url  = 'http://vids.myspace.com/index.cfm?fuseaction=oembed&format=xml&';
	$url .= 'url=http%3a%2f%2fvids.myspace.com%2findex.cfm%3ffuseaction%3dvids.individual%26videoid%3d'.$src;
	$cont = $this->get_remote_file( $url );
	if ( empty($cont) ) {
		return false;
	}

	$xml = $this->get_simplexml( $cont );
	if ( !is_object($xml) ) {
		return false;
	}

	$meta_url  = $this->build_link( $src );
	$meta_tags = $this->get_remote_meta_tags( $meta_url );

	$title = $this->get_xml_title( $xml );

	$arr = array(
		'title'       => $title ,
		'description' => $this->get_xml_description( $meta_tags, $title ),
		'url'         => $this->build_link(    $src ),
		'thumb'       => $this->get_xml_thumb( $xml ),
		'tags'        => $this->get_xml_tags(  $meta_tags ),
//		'duration'    => 0 ,
		'script'      => $this->get_xml_script( $xml ),
	);
	return $arr;
}

function get_xml_title( $xml )
{
	$str1 = $this->get_obj_property( $xml, 'title');
	$arr  = $this->str_to_array( $str1, $this->_TITLE_SPLIT );
	if ( !isset($arr[1]) ) {
		return false;
	}

	$str2 = preg_replace( '/'.$this->_TITLE_REMOVE.'/', '', $arr[1] );
	$str2 = $this->convert_from_utf8( $str2 );
	return $str2;
}

function get_xml_description( $tags, $title )
{
	if ( !isset($tags['description']) ) {
		return false;
	}

	$pat  = '. '.$title.' by ';
	$str1 = ereg_replace( $this->_DESCRIPTION_REMOVE, '', $tags['description'] );
	$arr  = $this->str_to_array( $str1, $pat );
	if ( !isset($arr[0]) ) {
		return false;
	}

	$str2 = $arr[0];
	$str2 = $this->convert_from_utf8( $str2 );
	return $str2;
}

function get_xml_thumb( $xml )
{
	$str = $this->get_obj_property( $xml, 'thumbnail_url');
	$str = strval($str);
	return $str;
}

function get_xml_tags( $tags )
{
	if ( !isset($tags['keywords']) ) {
		return false;
	}

	$arr  = $this->str_to_array( $tags['keywords'], ',');
	$arr  = $this->convert_array_from_utf8( $arr );
	return $arr;
}

function get_xml_script( $xml )
{
	$str = $this->get_obj_property( $xml, 'html');
	$str = $this->replace_width_height( $str );
	return $str;
}

function build_embed_script( $src, $width, $height )
{
	$movie = 'http://mediaservices.myspace.com/services/media/embed.aspx/m='.$src;
	$extra = 'allowFullScreen="true" wmode="transparent"';

	$str  = $this->build_object_begin( $width, $height );
	$str .= $this->build_param( 'movie', $movie );
	$str .= $this->build_param( 'allowFullScreen', 'true' );
	$str .= $this->build_param( 'wmode', "transparent" );
	$str .= $this->build_embed_flash( $movie, $width, $height, $extra );
	$str .= $this->build_object_end();
	return $str;
}

// --- class end ---
}

?>