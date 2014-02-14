<?php
// $Id: vimeo.php,v 1.1 2008/10/30 00:24:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_embed_vimeo
//
// http://www.vimeo.com/192696
//
// <object width="400" height="300">
// <param name="allowfullscreen" value="true" />
// <param name="allowscriptaccess" value="always" />
// <param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=192696&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" />
// <embed src="http://vimeo.com/moogaloop.swf?clip_id=192696&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="400" height="300"></embed>
// </object>
//=========================================================
class webphoto_embed_vimeo extends webphoto_embed_base
{

function webphoto_embed_vimeo()
{
	$this->webphoto_embed_base( 'vimeo' );
	$this->set_url( 'http://www.vimeo.com/' );
	$this->set_sample( '192696' );
}

function embed( $src, $width, $height )
{
	$movie = 'http://www.vimeo.com/moogaloop.swf?clip_id='.$src.'&amp;server=www.vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1';
	$extra = 'allowfullscreen="true" allowscriptaccess="always"';
 
	$str  = $this->build_object_begin( $width, $height );
	$str .= $this->build_param( 'allowfullscreen',   'true' );
	$str .= $this->build_param( 'allowscriptaccess', 'always' );
	$str .= $this->build_param( 'movie', $movie );
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
	return $this->build_desc();
}

// --- class end ---
}

?>