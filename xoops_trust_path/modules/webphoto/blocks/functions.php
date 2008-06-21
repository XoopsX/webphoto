<?php
// $Id: functions.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

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

?>