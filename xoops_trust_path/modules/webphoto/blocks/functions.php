<?php
// $Id: functions.php,v 1.3 2009/01/31 19:12:49 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-25 K.OHWADA
// b_webphoto_topnews_p_edit()
// 2008-11-29 K.OHWADA
// b_webphoto_catlist_show()
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// topnews
//---------------------------------------------------------
function b_webphoto_topnews_show( $options )
{
	$inc_class =& webphoto_inc_blocks::getInstance();
	return $inc_class->topnews_show( $options );
}

function b_webphoto_topnews_p_show( $options )
{
	$inc_class =& webphoto_inc_blocks::getInstance();
	return $inc_class->topnews_p_show( $options );
}

function b_webphoto_topnews_edit( $options )
{
	$inc_class =& webphoto_inc_blocks::getInstance();
	return $inc_class->topnews_edit( $options );
}

function b_webphoto_topnews_p_edit( $options )
{
	$inc_class =& webphoto_inc_blocks::getInstance();
	return $inc_class->topnews_p_edit( $options );
}

function b_webphoto_tophits_show( $options )
{
	$inc_class =& webphoto_inc_blocks::getInstance();
	return $inc_class->tophits_show( $options );
}

function b_webphoto_tophits_p_show( $options )
{
	$inc_class =& webphoto_inc_blocks::getInstance();
	return $inc_class->tophits_p_show( $options );
}

function b_webphoto_tophits_edit( $options )
{
	$inc_class =& webphoto_inc_blocks::getInstance();
	return $inc_class->tophits_edit( $options );
}

function b_webphoto_tophits_p_edit( $options )
{
	$inc_class =& webphoto_inc_blocks::getInstance();
	return $inc_class->tophits_p_edit( $options );
}

function b_webphoto_rphoto_show( $options )
{
	$inc_class =& webphoto_inc_blocks::getInstance();
	return $inc_class->rphoto_show( $options );
}

function b_webphoto_rphoto_edit( $options )
{
	$inc_class =& webphoto_inc_blocks::getInstance();
	return $inc_class->rphoto_edit( $options );
}

function b_webphoto_catlist_show( $options )
{
	$inc_class =& webphoto_inc_blocks::getInstance();
	return $inc_class->catlist_show( $options );
}

function b_webphoto_catlist_edit( $options )
{
	$inc_class =& webphoto_inc_blocks::getInstance();
	return $inc_class->catlist_edit( $options );
}

function b_webphoto_tagcloud_show( $options )
{
	$inc_class =& webphoto_inc_blocks::getInstance();
	return $inc_class->tagcloud_show( $options );
}

function b_webphoto_tagcloud_edit( $options )
{
	$inc_class =& webphoto_inc_blocks::getInstance();
	return $inc_class->tagcloud_edit( $options );
}

?>