<?php
// $Id: vote_handler.php,v 1.1 2008/06/21 12:22:24 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_vote_handler
//=========================================================
class webphoto_vote_handler extends webphoto_lib_handler
{
	var $_ONE_DAY_SEC = 86400;	// 1 day ( 86400 sec )
	var $_WAIT_DAYS   = 1;	// 1 day

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_vote_handler( $dirname )
{
	$this->webphoto_lib_handler( $dirname );
	$this->set_table_prefix_dirname( 'vote' );
	$this->set_id_name( 'vote_id' );

	$constpref = strtoupper( '_P_' . $dirname. '_' ) ;
	$this->set_debug_sql_by_const_name(   $constpref.'DEBUG_SQL' );
	$this->set_debug_error_by_const_name( $constpref.'DEBUG_ERROR' );

}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_vote_handler( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create
//---------------------------------------------------------
function create( $flag_new= false )
{
	$time_create = 0;
	$time_update = 0;

	if ( $flag_new ) {
		$time = time();
		$time_create = $time;
		$time_update = $time;
	}

	$arr = array(
		'vote_id'          => 0,
		'vote_time_create' => $time_create,
		'vote_time_update' => $time_update,
		'vote_photo_id'    => 0,
		'vote_uid'         => 0,
		'vote_rating'      => 0,
		'vote_hostname'    => '',

	);

	return $arr;
}

//---------------------------------------------------------
// insert
//---------------------------------------------------------
function insert( $row )
{
	extract( $row ) ;

	$sql  = 'INSERT INTO '.$this->_table.' (';

	if ( $vote_id > 0 ) {
		$sql .= 'vote_id, ';
	}

	$sql .= 'vote_time_create, ';
	$sql .= 'vote_time_update, ';
	$sql .= 'vote_photo_id, ';
	$sql .= 'vote_uid, ';
	$sql .= 'vote_rating, ';
	$sql .= 'vote_hostname ';

	$sql .= ') VALUES ( ';

	if ( $vote_id > 0 ) {
		$sql .= intval($vote_id).', ';
	}

	$sql .= intval($vote_time_create).', ';
	$sql .= intval($vote_time_update).', ';
	$sql .= intval($vote_photo_id).', ';
	$sql .= intval($vote_uid).', ';
	$sql .= intval($vote_rating).', ';
	$sql .= $this->quote($vote_hostname).' ';

	$sql .= ')';

	$ret = $this->query( $sql );
	if ( !$ret ) { return false; }

	return $this->_db->getInsertId();
}

//---------------------------------------------------------
// update
//---------------------------------------------------------
function update( $row )
{
	extract( $row ) ;

	$sql  = 'UPDATE '.$this->_table.' SET ';

	$sql .= 'vote_time_create='.intval($vote_time_create).', ';
	$sql .= 'vote_time_update='.intval($vote_time_update).', ';
	$sql .= 'vote_photo_id='.intval($vote_photo_id).', ';
	$sql .= 'vote_uid='.intval($vote_uid).', ';
	$sql .= 'vote_rating='.intval($vote_rating).', ';
	$sql .= 'vote_hostname='.$this->quote($vote_hostname).' ';
	$sql .= 'WHERE vote_id='.intval($vote_id);

	return $this->query( $sql );
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function delete_by_photoid( $photo_id )
{
	$sql  = 'DELETE FROM '. $this->_table;
	$sql .= ' WHERE vote_photo_id='. intval($photo_id);
	return $this->query( $sql );
}

//---------------------------------------------------------
// count
//---------------------------------------------------------
function get_count_by_photoid_uid( $photo_id, $uid )
{
	$where  = 'vote_photo_id='.intval($photo_id);
	$where .= ' AND vote_uid='.intval($uid);
	return $this->get_count_by_where( $where );
}

function get_count_anonymous_by_photoid_hostname( $photo_id, $hostname )
{
	$yesterday = ( time() - ($this->_ONE_DAY_SEC * $this->_WAIT_DAYS ) ) ;

	$where  = 'vote_uid=0 ';
	$where  = ' AND vote_photo_id='.intval($photo_id);
	$where .= ' AND vote_hostname='.$this->quote($hostname);
	$where .= ' AND vote_time_update > '.intval($yesterdaytname);
	return $this->get_count_by_where( $where );
}

//---------------------------------------------------------
// rows
//---------------------------------------------------------
function get_rows_by_photoid( $photo_id )
{
	$where = 'vote_photo_id='.intval($photo_id);
	return $this->get_rows_by_where( $where );
}

// --- class end ---
}

?>