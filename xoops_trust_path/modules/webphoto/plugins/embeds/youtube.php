<?php
// $Id: youtube.php,v 1.2 2008/11/19 10:26:00 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-11-16 K.OHWADA
// width()
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_embed_youtube
//
// http://www.youtube.com/watch?v=xFnwzdKNtpI
//
// <object width="425" height="373">
// <param name="movie" value="http://www.youtube.com/v/xFnwzdKNtpI&rel=0&border=1"></param>
// <param name="wmode" value="transparent"></param>
// <embed src="http://www.youtube.com/v/lGVwm326rnk&rel=0&border=1" type="application/x-shockwave-flash" wmode="transparent" width="425" height="373"></embed>
// </object>
//=========================================================
class webphoto_embed_youtube extends webphoto_embed_base
{

function webphoto_embed_youtube()
{
	$this->webphoto_embed_base( 'youtube' );
	$this->set_url( 'http://www.youtube.com/watch?v=' );
	$this->set_sample( 'xFnwzdKNtpI' );
}

function embed( $src, $width, $height )
{
	$movie = 'http://www.youtube.com/v/'.$src.'&amp;rel=0&amp;border=0';
	$wmode = 'transparent';
	$extra = 'wmode="'.$wmode.'"';

	$str  = $this->build_object_begin( $width, $height );
	$str .= $this->build_param( 'movie', $movie );
	$str .= $this->build_param( 'wmode', $wmode );
	$str .= $this->build_embed_flash( $movie, $width, $height, $extra );
	$str .= $this->build_object_end();
	return $str;
}

function link( $src )
{
	return $this->build_link( $src );
}

function thumb( $src )
{
	$str = 'http://img.youtube.com/vi/'.$src.'/2.jpg'; 
	return $str;
}

function width()
{
	return 425;
}

function height()
{
	return 344;
}

function desc()
{
	return $this->build_desc();
}

// --- class end ---
}
?>