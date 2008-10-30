<?php
// $Id: dailymotion.php,v 1.1 2008/10/30 00:24:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

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

// --- class end ---
}

?>