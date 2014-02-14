<?php
// $Id: metacafe.php,v 1.1 2008/10/30 00:24:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_embed_metacafe
//
// http://www.metacafe.com/watch/714487/amazing_crash_and_crazy_tractor/
//
// <embed src="http://www.metacafe.com/fplayer/877529/incredible_paper.swf" width="400" height="345" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
//=========================================================
class webphoto_embed_metacafe extends webphoto_embed_base
{

function webphoto_embed_metacafe()
{
	$this->webphoto_embed_base( 'metacafe' );
	$this->set_url( 'http://www.metacafe.com/watch/' );
}

function embed( $src, $width, $height )
{
	$movie = 'http://www.metacafe.com/fplayer/'.$src.'.swf';
	$embed = '<embed src="'.$movie.'" width="'.$width.'" height="'.$height.'" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" />';
	return $embed;
}

function link( $src )
{
	return $this->build_link( $src );
}

function thumb( $src )
{
// '714487/amazing_crash_and_crazy_tractor/' -> '714487'
	$thumb = substr($src, 0, strpos($src, '/'));

	$str = 'http://www.metacafe.com/thumb/'. $thumb .'.jpg'; 
	return $str;
}

function desc()
{
	return $this->build_desc_span( $this->_url_head, '714487'. '/amazing_crash_and_crazy_tractor/' );
}

// --- class end ---
}

?>