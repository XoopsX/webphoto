<?php
// $Id: functions.php,v 1.5 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// getInstance -> getSingleton 
// b_webphoto_dirname()
// 2009-04-10 K.OHWADA
// b_webphoto_timeline_show()
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
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->topnews_show( $options );
}

function b_webphoto_topnews_p_show( $options )
{
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->topnews_p_show( $options);
}

function b_webphoto_topnews_edit( $options )
{
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->topnews_edit( $options );
}

function b_webphoto_topnews_p_edit( $options )
{
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->topnews_p_edit( $options );
}

function b_webphoto_tophits_show( $options )
{
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->tophits_show( $options );
}

function b_webphoto_tophits_p_show( $options )
{
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->tophits_p_show( $options );
}

function b_webphoto_tophits_edit( $options )
{
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->tophits_edit( $options );
}

function b_webphoto_tophits_p_edit( $options )
{
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->tophits_p_edit( $options );
}

function b_webphoto_rphoto_show( $options )
{
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->rphoto_show( $options );
}

function b_webphoto_rphoto_edit( $options )
{
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->rphoto_edit( $options );
}

function b_webphoto_catlist_show( $options )
{
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->catlist_show( $options );
}

function b_webphoto_catlist_edit( $options )
{
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->catlist_edit( $options );
}

function b_webphoto_tagcloud_show( $options )
{
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->tagcloud_show( $options );
}

function b_webphoto_tagcloud_edit( $options )
{
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->tagcloud_edit( $options );
}

function b_webphoto_timeline_show( $options )
{
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->timeline_show( $options );
}

function b_webphoto_timeline_edit( $options )
{
	$inc_class =& webphoto_inc_blocks::getSingleton( 
		b_webphoto_dirname( $options ) , WEBPHOTO_TRUST_DIRNAME );
	return $inc_class->timeline_edit( $options );
}

function b_webphoto_dirname( $options )
{
	$val = isset( $options[0] ) ? $options[0] : 'webphoto' ;
	return $val;
}

?>