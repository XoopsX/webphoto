<?php
// $Id: permission.php,v 1.1 2008/06/21 12:22:23 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_permission
//=========================================================
class webphoto_permission
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

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_permission( $dirname )
{
	$this->_init( $dirname );
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_permission( $dirname );
	}
	return $instance;
}

function _init( $dirname )
{
	$perm_handler =& webphoto_inc_group_permission::getInstance();
	$perm_handler->init( $dirname );

	$this->_has_insertable       = $perm_handler->has_perm( 'insertable' );
	$this->_has_superinsert      = $perm_handler->has_perm( 'superinsert' );
	$this->_has_editable         = $perm_handler->has_perm( 'editable' );
	$this->_has_superedit        = $perm_handler->has_perm( 'superedit' );
	$this->_has_deletable        = $perm_handler->has_perm( 'deletable' );
	$this->_has_superdelete      = $perm_handler->has_perm( 'superdelete' );
	$this->_has_touchothers      = $perm_handler->has_perm( 'touchothers' );
	$this->_has_supertouchothers = $perm_handler->has_perm( 'supertouchothers' );
	$this->_has_rateview         = $perm_handler->has_perm( 'rateview' );
	$this->_has_ratevote         = $perm_handler->has_perm( 'ratevote' );
	$this->_has_tellafriend      = $perm_handler->has_perm( 'tellafriend' );
	$this->_has_tagedit          = $perm_handler->has_perm( 'tagedit' );
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

// --- class end ---
}

?>