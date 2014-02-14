<?php
// $Id: soapbox.php,v 1.1 2008/10/30 00:24:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_embed_soapbox
//
// http://video.msn.com/video.aspx?vid=0ba39053-48f2-4a2c-99eb-cb1b5bc9b263
//
// <embed src="http://images.video.msn.com/flash/soapbox1_1.swf" width="432" height="364" id="je4ro1qv" type="application/x-shockwave-flash" allowFullScreen="true" allowScriptAccess="always" pluginspage="http://macromedia.com/go/getflashplayer" flashvars="c=v&v=0ba39053-48f2-4a2c-99eb-cb1b5bc9b263&ifs=true&fr=msnvideo&mkt=ja-JP"></embed>

//=========================================================
class webphoto_embed_soapbox extends webphoto_embed_base
{

function webphoto_embed_soapbox()
{
	$this->webphoto_embed_base( 'soapbox' );
	$this->set_url( 'http://video.msn.com/video.aspx?vid=' );
	$this->set_sample( '0ba39053-48f2-4a2c-99eb-cb1b5bc9b263' );
}

function embed( $src, $width, $height )
{
	$movie = 'http://images.video.msn.com/flash/soapbox1_1.swf';
	$flash_vars = 'c=v&amp;v='.$src.'&amp;ifs=true&amp;fr=msnvideo';

	$embed = '<embed src="http://images.video.msn.com/flash/soapbox1_1.swf" quality="high" width="'.$width.'" height="'.$height.'" type="application/x-shockwave-flash" allowFullScreen="true" allowScriptAccess="always" pluginspage="http://macromedia.com/go/getflashplayer" flashvars="'.$flash_vars.'" />';
	return $embed;
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