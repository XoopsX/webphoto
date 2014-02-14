<?php
// $Id: vote_handler.php,v 1.4 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
// 2008-12-07 K.OHWADA
// get_count_by_photoid()
// 2008-10-01 K.OHWADA
// BUG : Undefined variable: yesterdaytname
// calc_rating_by_photoid()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_vote_handler
//=========================================================
class webphoto_vote_handler extends webphoto_handler_base_ini
{
	var $_ONE_DAY_SEC = 86400;	// 1 day ( 86400 sec )
	var $_WAIT_DAYS   = 1;	// 1 day

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_vote_handler( $dirname, $trust_dirname )
{
	$this->webphoto_handler_base_ini( $dirname, $trust_dirname );
	$this->set_table_prefix_dirname( 'vote' );
	$this->set_id_name( 'vote_id' );

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_vote_handler( $dirname, $trust_dirname );
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
function get_count_by_photoid( $photo_id )
{
	$where = 'vote_photo_id='.intval($photo_id);
	return $this->get_count_by_where( $where );
}

function get_count_by_photoid_uid( $photo_id, $uid )
{
	$where  = 'vote_photo_id='.intval($photo_id);
	$where .= ' AND vote_uid='.intval($uid);
	return $this->get_count_by_where( $where );
}

function get_count_anonymous_by_photoid_hostname( $photo_id, $hostname )
{
	$yesterday = time() - $this->get_ini( 'vote_anonymous_interval' ) ;

	$where  = 'vote_uid=0 ';
	$where .= ' AND vote_photo_id='.intval($photo_id);
	$where .= ' AND vote_hostname='.$this->quote($hostname);
	$where .= ' AND vote_time_update > '.intval($yesterday);
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

function get_rows_by_uid( $uid )
{
	$where = 'vote_uid='.intval($uid);
	return $this->get_rows_by_where( $where );
}

function get_rows_user( )
{
	$where = 'vote_uid>0';
	return $this->get_rows_by_where( $where );
}

function get_rows_guest( )
{
	$where = 'vote_uid=0';
	return $this->get_rows_by_where( $where );
}

function get_rows_user_by_photoid( $photo_id )
{
	$where  = 'vote_uid>0 ';
	$where .= 'AND vote_photo_id='.intval($photo_id);
	return $this->get_rows_by_where( $where );
}

function get_rows_guest_by_photoid( $photo_id )
{
	$where  = 'vote_uid=0 ';
	$where .= 'AND vote_photo_id='.intval($photo_id);
	return $this->get_rows_by_where( $where );
}

//---------------------------------------------------------
// calc
//---------------------------------------------------------
function calc_rating_by_photoid( $photo_id, $decimals=4 )
{
	return $this->calc_rating_by_rows( 
		$this->get_rows_by_photoid( $photo_id ) );
}

function calc_rating_by_uid( $uid, $decimals=1 )
{
	return $this->calc_rating_by_rows( 
		$this->get_rows_by_uid( $uid ) ) ;
}

function calc_rating_by_rows( $rows, $decimals=0 )
{
	$votes  = 0;
	$total  = 0;
	$rating = 0;

	if ( is_array($rows) ) {
		$votes = count($rows);
		if ( $votes > 0 ) {
			foreach( $rows as $row ) {
				$total += $row['vote_rating'] ;
			}
			$rating = $total / $votes ;
			if ( $decimals > 0 ) {
				$rating = $this->format_rating( $rating, $decimals );
			}
		}
	}

	return array( $votes, $total, $rating ) ;
}

function format_rating( $rating, $decimals=1 )
{
	return number_format( $rating , $decimals ) ;
}

// --- class end ---
}

?>