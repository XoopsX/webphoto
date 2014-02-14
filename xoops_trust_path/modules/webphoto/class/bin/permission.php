<?php
// $Id: permission.php,v 1.2 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// has_html()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_permission
// substitute for clsss/webphoto/permission.php
//=========================================================
class webphoto_permission
{
	var $_has_insertable       = true ;
	var $_has_superinsert      = true ;
	var $_has_editable         = true ;
	var $_has_supereditable    = true ;
	var $_has_deletable        = true ;
	var $_has_superdeletable   = true ;
	var $_has_touchothers      = true ;
	var $_has_supertouchothers = true ;
	var $_has_rateview         = true ;
	var $_has_ratevote         = true ;
	var $_has_tellafriend      = true ;
	var $_has_tagedit          = true ;
	var $_has_mail             = true ;
	var $_has_file             = true ;
	var $_has_html             = true ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_permission( $dirname )
{
	// dummy
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_permission( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// has permit
//---------------------------------------------------------
function has_insertable()
{
	return $this->_has_insertable ;
}

function has_superinsert()
{
	return $this->_has_superinsert ;
}

function has_editable()
{
	return $this->_has_editable ;
}

function has_superedit()
{
	return $this->_has_superedit ;
}

function has_deletable()
{
	return $this->_has_deletable ;
}

function has_superdelete()
{
	return $this->_has_superdelete ;
}

function has_touchothers()
{
	return $this->_has_touchothers ;
}

function has_supertouchothers()
{
	return $this->_has_supertouchothers ;
}

function has_rateview()
{
	return $this->_has_rateview ;
}

function has_ratevote()
{
	return $this->_has_ratevote ;
}

function has_tellafriend()
{
	return $this->_has_tellafriend ;
}

function has_tagedit()
{
	return $this->_has_tagedit ;
}

function has_mail()
{
	return $this->_has_mail ;
}

function has_file()
{
	return $this->_has_file ;
}

function has_html()
{
	return $this->_has_html ;
}

// --- class end ---
}

?>