<?php
// $Id: auto_publish.php,v 1.1 2008/11/30 10:37:07 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-29 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_auto_publish
//=========================================================
class webphoto_inc_auto_publish extends webphoto_inc_handler
{
	var $_table_item ;

	var $_DIRNAME ;
	var $_MODULE_URL ;
	var $_MODULE_DIR ;

	var $_FILE_AUTO_PUBLISH ;
	var $_TIME_AUTO_PUBLISH = 3600 ; // 1 hour
	var $_FLAG_AUTO_PUBLISH_CHMOD = true ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_auto_publish()
{
	$this->webphoto_inc_handler();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_auto_publish();
	}
	return $instance;
}

//---------------------------------------------------------
// init
//---------------------------------------------------------
function init( $dirname )
{
	$this->_DIRNAME    = $dirname;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'.$dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'.$dirname;

	$this->_table_item = $this->prefix_dirname( 'item' ) ;
}

function set_workdir( $workdir )
{
	$this->_FILE_AUTO_PUBLISH = $workdir .'/tmp/auto_publish' ;
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function auto_publish()
{
	if ( $this->check_auto_publish_time() ) {
		$this->item_auto_publish( true ) ;
		$this->item_auto_expire(  true ) ;
	}

// set time before execute
	$this->renew_auto_publish_time();
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function check_auto_publish_time()
{
	return $this->check_file_time( 
		$this->_FILE_AUTO_PUBLISH, $this->_TIME_AUTO_PUBLISH );
}

function renew_auto_publish_time()
{
	$this->write_file( 
		$this->_FILE_AUTO_PUBLISH, time(), 'w', $this->_FLAG_AUTO_PUBLISH_CHMOD );
}

function check_file_time( $file, $interval )
{
// if passing interval time
	if ( file_exists( $file ) ) {
		$time = intval( trim( file_get_contents( $file ) ) );
		if ( ( $time > 0 ) && 
		     ( time() > ( $time + $interval ) ) ) {
			return true;
		}

// if not exists file ( at first time )
	} else {
		return true;
	}

	return false;
}

function write_file( $file, $data, $mode='w', $flag_chmod=false )
{
	$fp = fopen( $file , $mode ) ;
	if ( !$fp ) { return false ; }

	$byte = fwrite( $fp , $data ) ;
	fclose( $fp ) ;

// the user can delete this file which apache made.
	if (( $byte > 0 )&& $flag_chmod ) {
		chmod( $file, 0777 );
	}

	return $byte;
}

//---------------------------------------------------------
// item handler
//---------------------------------------------------------
function item_auto_publish( $force=false )
{
	$rows = $this->get_item_rows_until_publish() ;
	if ( is_array($rows) && count($rows) ) {
		foreach ( $rows as $row ) {
			$this->update_item_status( $item_id, _C_WEBPHOTO_STATUS_APPROVED, $force ) ;
		}
	}
}

function item_auto_expire( $force=false )
{
	$rows = $this->get_item_rows_until_expire() ;
	if ( is_array($rows) && count($rows) ) {
		foreach ( $rows as $row ) {
			$this->update_item_status( $item_id, _C_WEBPHOTO_STATUS_EXPIRED, $force ) ;
		}
	}
}

function get_item_rows_until_publish( $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->_table_item;
	$sql .= ' WHERE item_status = '. _C_WEBPHOTO_STATUS_OFFLINE ;
	$sql .= ' AND item_time_publish > 0 ' ;
	$sql .= ' AND item_time_publish > '. time() ;
	$sql .= ' ORDER BY item_id' ;
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function get_item_rows_until_expire( $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->_table_item;
	$sql .= ' WHERE item_status > 0 ' ;
	$sql .= ' AND item_time_expire > 0 ' ;
	$sql .= ' AND item_time_expire < '. time() ;
	$sql .= ' ORDER BY item_id' ;
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function update_item_status( $item_id, $status, $force=false )
{
	$sql  = 'UPDATE '.$this->_table_item.' SET ';
	$sql .= ' item_status = '. intval($status) ;
	$sql .= ' WHERE item_id='.intval($item_id);

	return $this->query( $sql, 0, 0, $force );
}

// --- class end ---
}

?>