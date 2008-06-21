<?php
// $Id: waiting.php,v 1.1 2008/06/21 12:22:26 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_waiting
//=========================================================
class webphoto_inc_waiting extends webphoto_inc_handler
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_waiting()
{
	$this->webphoto_inc_handler();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_waiting();
	}
	return $instance;
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function waiting( $dirname )
{
	$this->init_handler( $dirname );

	$ret = array();
	$ret['adminlink']  = $this->_MODULE_URL .'/admin/index.php?fct=admission';
	$ret['pendingnum'] = $this->_get_photo_count();

// this constant is defined in wating module
	$ret['lang_linkname'] = _PI_WAITING_WAITINGS ;

	return $ret;
}

function _get_photo_count()
{
	$sql  = "SELECT COUNT(*) FROM ". $this->prefix_dirname( 'photo' );
	$sql .= " WHERE photo_status=0";
	return $this->get_count_by_sql( $sql );
}

// --- class end ---
}

?>