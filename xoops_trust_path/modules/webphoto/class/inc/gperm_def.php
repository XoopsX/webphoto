<?php
// $Id: gperm_def.php,v 1.1 2009/12/16 13:36:20 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_gperm_def
//=========================================================
class webphoto_inc_gperm_def
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_gperm_def()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_gperm_def();
	}
	return $instance;
}

//---------------------------------------------------------
// group
//---------------------------------------------------------
function get_perms_admin()
{
	$arr = array(
		_B_WEBPHOTO_GPERM_INSERTABLE ,
		_B_WEBPHOTO_GPERM_SUPERINSERT | _B_WEBPHOTO_GPERM_INSERTABLE ,
		_B_WEBPHOTO_GPERM_SUPEREDIT   | _B_WEBPHOTO_GPERM_EDITABLE ,
		_B_WEBPHOTO_GPERM_SUPERDELETE | _B_WEBPHOTO_GPERM_DELETABLE ,
		_B_WEBPHOTO_GPERM_RATEVIEW ,
		_B_WEBPHOTO_GPERM_RATEVOTE    | _B_WEBPHOTO_GPERM_RATEVIEW ,
		_B_WEBPHOTO_GPERM_TELLAFRIEND ,
		_B_WEBPHOTO_GPERM_TAGEDIT ,
		_B_WEBPHOTO_GPERM_MAIL ,
		_B_WEBPHOTO_GPERM_FILE ,
		_B_WEBPHOTO_GPERM_HTML ,
	) ;
	return $arr;
}

function get_perms_user()
{
	$arr = array(
		_B_WEBPHOTO_GPERM_INSERTABLE ,
//		_B_WEBPHOTO_GPERM_SUPERINSERT | _B_WEBPHOTO_GPERM_INSERTABLE ,
		_B_WEBPHOTO_GPERM_SUPEREDIT   | _B_WEBPHOTO_GPERM_EDITABLE ,
		_B_WEBPHOTO_GPERM_SUPERDELETE | _B_WEBPHOTO_GPERM_DELETABLE ,
//		_B_WEBPHOTO_GPERM_RATEVIEW ,
//		_B_WEBPHOTO_GPERM_RATEVOTE    | _B_WEBPHOTO_GPERM_RATEVIEW ,
//		_B_WEBPHOTO_GPERM_TELLAFRIEND ,
//		_B_WEBPHOTO_GPERM_TAGEDIT ,
//		_B_WEBPHOTO_GPERM_MAIL ,
//		_B_WEBPHOTO_GPERM_FILE ,
//		_B_WEBPHOTO_GPERM_HTML ,
	) ;
	return $arr;
}

function get_perm_list()
{
	$arr = array(
		_B_WEBPHOTO_GPERM_INSERTABLE => _AM_WEBPHOTO_GPERM_INSERTABLE ,
		_B_WEBPHOTO_GPERM_SUPERINSERT | _B_WEBPHOTO_GPERM_INSERTABLE 
			=> _AM_WEBPHOTO_GPERM_SUPERINSERT ,

//		_B_WEBPHOTO_GPERM_EDITABLE => _AM_WEBPHOTO_GPERM_EDITABLE ,
		_B_WEBPHOTO_GPERM_SUPEREDIT | _B_WEBPHOTO_GPERM_EDITABLE 
			=> _AM_WEBPHOTO_GPERM_SUPEREDIT ,

//		_B_WEBPHOTO_GPERM_DELETABLE => _AM_WEBPHOTO_GPERM_DELETABLE ,
		_B_WEBPHOTO_GPERM_SUPERDELETE | _B_WEBPHOTO_GPERM_DELETABLE 
			=> _AM_WEBPHOTO_GPERM_SUPERDELETE ,

		_B_WEBPHOTO_GPERM_RATEVIEW => _AM_WEBPHOTO_GPERM_RATEVIEW ,
		_B_WEBPHOTO_GPERM_RATEVOTE | _B_WEBPHOTO_GPERM_RATEVIEW 
			=> _AM_WEBPHOTO_GPERM_RATEVOTE ,

		_B_WEBPHOTO_GPERM_TELLAFRIEND => _AM_WEBPHOTO_GPERM_TELLAFRIEND ,
		_B_WEBPHOTO_GPERM_TAGEDIT     => _AM_WEBPHOTO_GPERM_TAGEDIT ,
		_B_WEBPHOTO_GPERM_MAIL    => _AM_WEBPHOTO_GPERM_MAIL ,
		_B_WEBPHOTO_GPERM_FILE    => _AM_WEBPHOTO_GPERM_FILE ,
		_B_WEBPHOTO_GPERM_HTML    => _AM_WEBPHOTO_GPERM_HTML ,
	) ;
	return $arr;
}

// --- class end ---
}

?>