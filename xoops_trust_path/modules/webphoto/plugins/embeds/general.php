<?php
// $Id: general.php,v 1.1 2008/11/19 10:26:45 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_embed_general
//=========================================================
class webphoto_embed_general extends webphoto_embed_base
{

function webphoto_embed_general()
{
	$this->webphoto_embed_base( 'general' );
}

// --- class end ---
}
?>