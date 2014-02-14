<?php
// $Id: ustream.php,v 1.1 2010/06/16 22:46:22 ohwada Exp $

//=========================================================
// webphoto module
// 2010-06-06 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_embed_ustream
//
// http://www.ustream.tv/recorded/6501293
//
// <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="320" height="260" id="utv605411" name="utv_n_329299">
// <param name="flashvars" value="autoplay=false" />
// <param name="allowfullscreen" value="true" />
// <param name="allowscriptaccess" value="always" />
// <param name="src" value="http://www.ustream.tv/flash/video/6501293" />
// <embed flashvars="autoplay=false" width="320" height="260" allowfullscreen="true" allowscriptaccess="always" id="utv605411" name="utv_n_329299" src="http://www.ustream.tv/flash/video/6501293" type="application/x-shockwave-flash" />
// </object>

//=========================================================
class webphoto_embed_ustream extends webphoto_embed_base
{

function webphoto_embed_ustream()
{
	$this->webphoto_embed_base( 'ustream' );
	$this->set_url( 'http://www.ustream.tv/recorded/' );
	$this->set_sample( '6996774' );
}

function embed( $src, $width, $height )
{
	$movie = 'http://www.ustream.tv/flash/video/'.$src;

	$flashvars         = 'autoplay=false';
	$allowfullscreen   = 'true';
	$allowscriptaccess = 'always';

	$obj_extra = 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ';

	$embed_extra  = 'flashvars="'. $flashvars .'" ';
	$embed_extra .= 'allowfullscreen="'. $allowfullscreen .'" ';
	$embed_extra .= 'allowscriptaccess="'. $allowscriptaccess .'" ';

	$str  = $this->build_object_begin( $width, $height, $obj_extra );
	$str .= $this->build_param( 'flashvars',         $flashvars );
	$str .= $this->build_param( 'allowfullscreen',   $allowfullscreen );
	$str .= $this->build_param( 'allowscriptaccess', $allowscriptaccess );
	$str .= $this->build_embed_flash( $movie, $width, $height, $embed_extra );
	$str .= $this->build_object_end();
	return $str;
}

function link( $src )
{
	return $this->build_link( $src );
}

function width()
{
	return 320;
}

function height()
{
	return 260;
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
	$url = 'http://api.ustream.tv/xml/video/'.$src.'/getinfo';
	$cont = $this->get_remote_file( $url );
	if ( empty($cont) ) {
		return false;
	}

	$xml   = $this->get_simplexml( $cont );
	$error = trim( $this->get_obj_property( $xml, 'error' ) );
	if ( $error ) {
		return false;
	}

	$results = $this->get_obj_property( $xml, 'results' );
	if ( !is_object($results) ) {
		return false;
	}

	$arr = array(
		'title'       => $this->get_xml_title(       $results ),
		'description' => $this->get_xml_description( $results ),
		'url'         => $this->get_xml_url(         $results ),
		'thumb'       => $this->get_xml_thumb(       $results ),
		'duration'    => $this->get_xml_duration(    $results ),
		'tags'        => $this->get_xml_tags(        $results ),
		'script'      => $this->get_xml_script(      $results ),

	);
	return $arr;
}

function get_xml_title( $results )
{
	$str = $this->get_obj_property( $results, 'title' );
	$str = $this->convert_from_utf8( strval($str) );
	return $str;
}

function get_xml_description( $results )
{
	$str = $this->get_obj_property( $results, 'description' );
	$str = $this->convert_from_utf8( strval($str) );
	return $str;
}

function get_xml_url( $results )
{
	$str = $this->get_obj_property( $results, 'url' );
	$str = strval($str);
	return $str;
}

function get_xml_thumb( $results )
{
	$url = $this->get_obj_property( $results, 'imageUrl' );
	$str = $this->get_obj_property( $url,     'small' );
	$str = strval($str);
	return $str;
}

function get_xml_duration( $results )
{
	$str = $this->get_obj_property( $results, 'lengthInSecond' );
	$str = floor($str);
	return $str;
}

function get_xml_tags( $results )
{
	$tags = $this->get_obj_property( $results, 'tags' );
	$arr  = $this->get_obj_property( $tags,    'array' );
	$arr  = $this->obj_array_to_str_array( $arr );
	$arr  = $this->convert_array_from_utf8( $arr );
	return $arr;
}

function get_xml_script( $results )
{
	$str = $this->get_obj_property( $results, 'embedTag' );
	$str = $this->replace_width_height( $str );
	return $str;
}

// --- class end ---
}

?>