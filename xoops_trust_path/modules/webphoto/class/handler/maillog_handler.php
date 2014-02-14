<?php
// $Id: maillog_handler.php,v 1.4 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
// info_str_to_array( $str )
// 2008-08-24 K.OHWADA
// added get_rows_by_photoid()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_maillog_handler
//=========================================================
class webphoto_maillog_handler extends webphoto_handler_base_ini
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_maillog_handler( $dirname, $trust_dirname )
{
	$this->webphoto_handler_base_ini( $dirname, $trust_dirname );
	$this->set_table_prefix_dirname( 'maillog' );
	$this->set_id_name( 'maillog_id' );

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_maillog_handler( $dirname, $trust_dirname );
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
		'maillog_id'        => 0,
		'maillog_time_create'  => $time_create,
		'maillog_time_update'  => $time_update,
		'maillog_photo_ids' => '',
		'maillog_status'    => '',
		'maillog_from'      => '',
		'maillog_subject'   => '',
		'maillog_body'      => '',
		'maillog_file'      => '',
		'maillog_attach'    => '',
		'maillog_comment'   => '',
	);

	return $arr;
}

//---------------------------------------------------------
// insert
//---------------------------------------------------------
function insert( $row, $force=false )
{
	extract( $row ) ;

	$sql  = 'INSERT INTO '.$this->_table.' (';

	$sql .= 'maillog_time_create, ';
	$sql .= 'maillog_time_update, ';
	$sql .= 'maillog_photo_ids, ';
	$sql .= 'maillog_status, ';
	$sql .= 'maillog_subject, ';
	$sql .= 'maillog_from, ';
	$sql .= 'maillog_body, ';
	$sql .= 'maillog_file, ';
	$sql .= 'maillog_attach, ';
	$sql .= 'maillog_comment ';

	$sql .= ') VALUES ( ';

	$sql .= intval($maillog_time_create).', ';
	$sql .= intval($maillog_time_update).', ';
	$sql .= $this->quote($maillog_photo_ids).', ';
	$sql .= intval($maillog_status).', ';
	$sql .= $this->quote($maillog_subject).', ';
	$sql .= $this->quote($maillog_from).', ';
	$sql .= $this->quote($maillog_body).', ';
	$sql .= $this->quote($maillog_file).', ';
	$sql .= $this->quote($maillog_attach).', ';
	$sql .= $this->quote($maillog_comment).' ';

	$sql .= ')';

	$ret = $this->query( $sql, 0, 0, $force );
	if ( !$ret ) { return false; }

	return $this->_db->getInsertId();
}

//---------------------------------------------------------
// update
//---------------------------------------------------------
function update( $row, $force=false )
{
	extract( $row ) ;

	$sql  = 'UPDATE '.$this->_table.' SET ';

	$sql .= 'maillog_time_create='.intval($maillog_time_create).', ';
	$sql .= 'maillog_time_update='.intval($maillog_time_update).', ';
	$sql .= 'maillog_photo_ids='.$this->quote($maillog_photo_ids).', ';
	$sql .= 'maillog_status='.intval($maillog_status).', ';
	$sql .= 'maillog_subject='.$this->quote($maillog_subject).', ';
	$sql .= 'maillog_from='.$this->quote($maillog_from).', ';
	$sql .= 'maillog_body='.$this->quote($maillog_body).', ';
	$sql .= 'maillog_file='.$this->quote($maillog_file).', ';
	$sql .= 'maillog_attach='.$this->quote($maillog_attach).', ';
	$sql .= 'maillog_comment='.$this->quote($maillog_comment).' ';

	$sql .= ' WHERE maillog_id='.intval($maillog_id);

	return $this->query( $sql, 0, 0, $force );
}

//---------------------------------------------------------
// get count
//---------------------------------------------------------
function get_count_by_status( $status )
{
	$sql  = 'SELECT COUNT(*) FROM '.$this->_table;
	$sql .= ' WHERE maillog_status='.intval($status);
	return $this->get_count_by_sql( $sql  );
}

//---------------------------------------------------------
// get rows
//---------------------------------------------------------
function get_rows_desc_by_status( $status, $limit=0, $start=0 )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' WHERE maillog_status='.intval($status);
	$sql .= ' ORDER BY maillog_id DESC';
	return $this->get_rows_by_sql( $sql, $limit, $start  );
}

function get_rows_by_photoid( $photo_id, $limit=0, $start=0 )
{
// %|123|%
	$like = $this->build_like_separetor( $photo_id );
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' WHERE maillog_photo_ids LIKE '. $this->quote($like) ;
	$sql .= ' ORDER BY maillog_id DESC';
	return $this->get_rows_by_sql( $sql, $limit, $start  );
}

function build_like_separetor( $id )
{
// %|123|%
	$like  = '%'. _C_WEBPHOTO_INFO_SEPARATOR ;
	$like .= intval($id) ;
	$like .= _C_WEBPHOTO_INFO_SEPARATOR . '%';
	return $like;
}

//---------------------------------------------------------
// get id array
//---------------------------------------------------------
function get_id_array_older( $limit=0, $offset=0 )
{
	$sql  = 'SELECT maillog_id FROM '.$this->_table;
	$sql .= ' ORDER BY maillog_id ASC';
	return $this->get_first_rows_by_sql( $sql, $limit, $offset );
}

//---------------------------------------------------------
// build
//---------------------------------------------------------
function build_photo_ids_array_to_str( $arr )
{
	if ( !is_array($arr) || !count($arr) ) {
		return null;
	}

// array -> |1|2|3|
	$str = $this->info_array_to_str( $arr );
	$ret = $this->build_photo_ids_with_separetor( $str ) ;
	return $ret ;
}

function build_photo_ids_with_separetor( $str )
{
// str -> |1|
	$ret = _C_WEBPHOTO_INFO_SEPARATOR . $str . _C_WEBPHOTO_INFO_SEPARATOR ;
	return $ret ;
}

function build_photo_ids_row_to_array( $row )
{
	return $this->info_str_to_array( $row['maillog_photo_ids'] );
}

function build_attach_array_to_str( $arr )
{
	if ( !is_array($arr) || !count($arr) ) {
		return null;
	}
	return $this->info_array_to_str( $arr );
}

function build_attach_row_to_array( $row )
{
	return $this->info_str_to_array( $row['maillog_attach'] );
}

function info_str_to_array( $str )
{
	$utility_class =& webphoto_lib_utility::getInstance();
	return $utility_class->str_to_array( $str, _C_WEBPHOTO_INFO_SEPARATOR );
}

function info_array_to_str( $arr )
{
	$utility_class =& webphoto_lib_utility::getInstance();
	return $utility_class->array_to_str( $arr, _C_WEBPHOTO_INFO_SEPARATOR );
}

//---------------------------------------------------------
// show
//---------------------------------------------------------
function build_show_comment( $row )
{
	return nl2br( $row['maillog_comment'] );
}

// --- class end ---
}

?>