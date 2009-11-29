<?php
// $Id: permission.php,v 1.5 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname
// 2009-01-04 K.OHWADA
// has_html()
// 2008-12-12 K.OHWADA
// extends webphoto_inc_group_permission
// 2008-08-01 K.OHWADA
// added has_mail() has_file()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_permission
//=========================================================
class webphoto_permission extends webphoto_inc_group_permission
{
	var $_has_insertable ; 
	var $_has_superinsert ; 
	var $_has_editable ; 
	var $_has_supereditable ;
	var $_has_deletable ;  
	var $_has_superdeletable ; 
	var $_has_touchothers  ; 
	var $_has_supertouchothers ;
	var $_has_rateview ; 
	var $_has_ratevote ;  
	var $_has_tellafriend  ; 
	var $_has_tagedit ;
	var $_has_mail ;
	var $_has_file ;
	var $_has_html ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_permission( $dirname , $trust_dirname )
{
	$this->webphoto_inc_group_permission( $dirname , $trust_dirname );

	$this->_has_insertable       = $this->has_perm( 'insertable' );
	$this->_has_superinsert      = $this->has_perm( 'superinsert' );
	$this->_has_editable         = $this->has_perm( 'editable' );
	$this->_has_superedit        = $this->has_perm( 'superedit' );
	$this->_has_deletable        = $this->has_perm( 'deletable' );
	$this->_has_superdelete      = $this->has_perm( 'superdelete' );
	$this->_has_touchothers      = $this->has_perm( 'touchothers' );
	$this->_has_supertouchothers = $this->has_perm( 'supertouchothers' );
	$this->_has_rateview         = $this->has_perm( 'rateview' );
	$this->_has_ratevote         = $this->has_perm( 'ratevote' );
	$this->_has_tellafriend      = $this->has_perm( 'tellafriend' );
	$this->_has_tagedit          = $this->has_perm( 'tagedit' );
	$this->_has_mail             = $this->has_perm( 'mail' );
	$this->_has_file             = $this->has_perm( 'file' );
	$this->_has_html             = $this->has_perm( 'html' );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_permission( $dirname , $trust_dirname );
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