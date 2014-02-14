<?php
// $Id: aoluncut.php,v 1.1 2008/10/30 00:24:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_embed_aoluncut
//
// http://uncutvideo.aol.com/videos/80d646d2bf149c6d04aa5989fcc85d6d
//
// <object width="415" height="347">
// <param name="wmode" value="opaque" />
// <param name="movie" value="http://uncutvideo.aol.com/v6.334/en-US/uc_videoplayer.swf" />
// <param name="FlashVars" value="aID=180d646d2bf149c6d04aa5989fcc85d6d&site=http://uncutvideo.aol.com/" />
// <embed src="http://uncutvideo.aol.com/v6.334/en-US/uc_videoplayer.swf" wmode="opaque" FlashVars="aID=180d646d2bf149c6d04aa5989fcc85d6d&site=http://uncutvideo.aol.com/" width="415" height="347" type="application/x-shockwave-flash"></embed>
// </object>
//=========================================================
class webphoto_embed_aoluncut extends webphoto_embed_base
{

function webphoto_embed_aoluncut()
{
	$this->webphoto_embed_base( 'aoluncut' );
	$this->set_url( 'http://uncutvideo.aol.com/videos/' );
	$this->set_sample( '180d646d2bf149c6d04aa5989fcc85d6d' );
}

function embed( $src, $width, $height )
{
	$movie = 'http://uncutvideo.aol.com/v6.334/en-US/uc_videoplayer.swf';
	$flash_vars = 'aID='.$src.'&amp;site=http://uncutvideo.aol.com/';
	$wmode = 'opaque';
	$extra = 'wmode="'.$wmode.'" FlashVars="'.$flash_vars.'"';

	$str  = $this->build_object_begin( $width, $height );
	$str .= $this->build_param( 'movie', $movie );
	$str .= $this->build_param( 'wmode', $wmode );
	$str .= $this->build_param( 'FlashVars', $flash_vars );
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