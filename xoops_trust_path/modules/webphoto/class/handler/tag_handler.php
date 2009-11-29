<?php
// $Id: tag_handler.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_tag_handler
//=========================================================
class webphoto_tag_handler extends webphoto_handler_base_ini
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_tag_handler( $dirname, $trust_dirname )
{
	$this->webphoto_handler_base_ini( $dirname, $trust_dirname );
	$this->set_table_prefix_dirname( 'tag' );
	$this->set_id_name( 'tag_id' );

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_tag_handler( $dirname, $trust_dirname );
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
		'tag_id'        => 0,
		'tag_time_create'  => $time_create,
		'tag_time_update'  => $time_update,
		'tag_name'     => '',
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

	$sql .= 'tag_time_create, ';
	$sql .= 'tag_time_update, ';
	$sql .= 'tag_name ';

	$sql .= ') VALUES ( ';

	$sql .= intval($tag_time_create).', ';
	$sql .= intval($tag_time_update).', ';
	$sql .= $this->quote($tag_name).' ';

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

	$sql .= 'tag_time_create='.intval($tag_time_create).', ';
	$sql .= 'tag_time_update='.intval($tag_time_update).', ';
	$sql .= 'tag_name='.$this->quote($tag_name).' ';

	$sql .= 'WHERE tag_id='.intval($tag_id);

	return $this->query( $sql );
}

//---------------------------------------------------------
// get row
//---------------------------------------------------------
function get_row_by_name( $name )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' WHERE tag_name='.$this->quote($name);
	return $this->get_row_by_sql( $sql );
}

// --- class end ---
}

?>