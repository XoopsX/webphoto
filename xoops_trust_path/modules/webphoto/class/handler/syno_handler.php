<?php
// $Id: syno_handler.php,v 1.1 2008/06/21 12:22:24 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_syno_handler
//=========================================================
class webphoto_syno_handler extends webphoto_lib_handler
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_syno_handler( $dirname )
{
	$this->webphoto_lib_handler( $dirname );
	$this->set_table_prefix_dirname( 'syno' );
	$this->set_id_name( 'syno_id' );

	$constpref = strtoupper( '_C_' . $dirname. '_' ) ;
	$this->set_debug_sql_by_const_name(   $constpref.'DEBUG_SQL' );
	$this->set_debug_error_by_const_name( $constpref.'DEBUG_ERROR' );

}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_syno_handler( $dirname );
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
		'syno_id'        => 0,
		'syno_time_create'  => $time_create,
		'syno_time_update'  => $time_update,
		'syno_weight'  => 0,
		'syno_key'     => '',
		'syno_value'   => '',
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

	$sql .= 'syno_time_create, ';
	$sql .= 'syno_time_update, ';
	$sql .= 'syno_weight, ';
	$sql .= 'syno_key, ';
	$sql .= 'syno_value ';

	$sql .= ') VALUES ( ';

	$sql .= intval($syno_time_create).', ';
	$sql .= intval($syno_time_update).', ';
	$sql .= intval($syno_weight).', ';
	$sql .= $this->quote($syno_key).', ';
	$sql .= $this->quote($syno_value).' ';

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

	$sql .= 'syno_time_create='.intval($syno_time_create).', ';
	$sql .= 'syno_time_update='.intval($syno_time_update).', ';
	$sql .= 'syno_weight='.intval($syno_weight).', ';
	$sql .= 'syno_key='.$this->quote($syno_key).', ';
	$sql .= 'syno_value='.$this->quote($syno_value).' ';

	$sql .= 'WHERE syno_id='.intval($syno_id);

	return $this->query( $sql );
}

//---------------------------------------------------------
// rows
//---------------------------------------------------------
function get_rows_orderby_weight_asc( $limit=0, $offset=0 )
{
	$orderby = 'syno_weight ASC, syno_id ASC';
	return $this->get_rows_by_orderby( $orderby, $limit=0, $offset=0 );
}

function get_rows_orderby_weight_desc( $limit=0, $offset=0 )
{
	$orderby = 'syno_weight DESC, syno_id DESC';
	return $this->get_rows_by_orderby( $orderby, $limit=0, $offset=0 );
}

// --- class end ---
}

?>