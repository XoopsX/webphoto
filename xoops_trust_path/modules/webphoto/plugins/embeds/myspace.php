<?php
// $Id: myspace.php,v 1.1 2008/10/30 00:24:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

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

function webphoto_embed_myspace()
{
	$this->webphoto_embed_base( 'myspace' );
	$this->set_url( 'http://vids.myspace.com/index.cfm?fuseaction=vids.individual&amp;videoid=' );
	$this->set_sample( '2096626' );
}

function embed( $src, $width, $height )
{
	$movie = 'http://mediaservices.myspace.com/services/media/embed.aspx/m='.$src;
	$extra = 'allowFullScreen="true"';

	$str  = $this->build_object_begin( $width, $height );
	$str .= $this->build_param( 'allowFullScreen', 'true' );
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