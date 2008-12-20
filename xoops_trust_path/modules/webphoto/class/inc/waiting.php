<?php
// $Id: waiting.php,v 1.3 2008/12/20 06:11:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-08-24 K.OHWADA
// table_photo -> table_item
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_waiting
//=========================================================
class webphoto_inc_waiting extends webphoto_inc_handler
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_waiting( $dirname )
{
	$this->webphoto_inc_handler();
	$this->init_handler( $dirname );
}

function &getSingleton( $dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_waiting( $dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function waiting()
{
	$ret = array();
	$ret['adminlink']  = $this->_MODULE_URL .'/admin/index.php?fct=admission';
	$ret['pendingnum'] = $this->_get_item_count();

// this constant is defined in wating module
	$ret['lang_linkname'] = _PI_WAITING_WAITINGS ;

	return $ret;
}

function _get_item_count()
{
	$sql  = "SELECT COUNT(*) FROM ". $this->prefix_dirname( 'item' );
	$sql .= " WHERE item_status=0";
	return $this->get_count_by_sql( $sql );
}

// --- class end ---
}

?>