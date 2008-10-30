<?php
// $Id: livevideo.php,v 1.1 2008/10/30 00:24:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_embed_livevideo
//
// http://www.livevideo.com/video/SillyLeslie/75A082A560464FAEB411D06417E495C4/lvo-ii-10-thank-you-all.aspx
//
// <embed src="http://www.livevideo.com/flvplayer/embed/75A082A560464FAEB411D06417E495C4" type="application/x-shockwave-flash" quality="high" WIDTH="445" HEIGHT="369" wmode="transparent"></embed>
//=========================================================
class webphoto_embed_livevideo extends webphoto_embed_base
{

function webphoto_embed_livevideo()
{
	$this->webphoto_embed_base( 'livevideo' );
}

function embed( $src, $width, $height )
{
	$movie = 'http://www.livevideo.com/flvplayer/embed/'.$src;
	$embed = '<embed src="'.$movie.'" type="application/x-shockwave-flash" quality="high" WIDTH="'.$width.'" HEIGHT="'.$height.'" wmode="transparent" />';
	return $embed;
}

function desc()
{
	return $this->build_desc_span( 'http://www.livevideo.com/video/mrmercedesman/', 'F6D925B31BAB4DF080B176AABD5AFD17', '/surrounding-your-internets-.aspx' );
}

// --- class end ---
}
?>