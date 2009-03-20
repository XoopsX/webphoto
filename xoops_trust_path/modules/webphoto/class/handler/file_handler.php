<?php
// $Id: file_handler.php,v 1.6 2009/03/20 04:18:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-03-15 K.OHWADA
// get_count_by_kind()
// 2009-01-10 K.OHWADA
// build_row_by_param( $row, $param )
// 2008-12-07 K.OHWADA
// not need '/' in build_show_file_image()
// 2008-11-29 K.OHWADA
// build_show_file_image()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_file_handler
//=========================================================
class webphoto_file_handler extends webphoto_lib_handler
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_file_handler( $dirname )
{
	$this->webphoto_lib_handler( $dirname );
	$this->set_table_prefix_dirname( 'file' );
	$this->set_id_name( 'file_id' );

	$constpref = strtoupper( '_P_' . $dirname. '_' ) ;
	$this->set_debug_sql_by_const_name(   $constpref.'DEBUG_SQL' );
	$this->set_debug_error_by_const_name( $constpref.'DEBUG_ERROR' );

}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_file_handler( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create
//---------------------------------------------------------
function create( $flag_new=false )
{
	$time_create = 0;
	$time_update = 0;

	if ( $flag_new ) {
		$time = time();
		$time_create = $time;
		$time_update = $time;
	}

	$arr = array(
		'file_id'          => 0,
		'file_time_create' => $time_create,
		'file_time_update' => $time_update,
		'file_item_id'     => 0,
		'file_kind'        => 0,
		'file_url'         => '',
		'file_path'        => '',
		'file_name'        => '',
		'file_ext'         => '',
		'file_mime'        => '',
		'file_medium'      => '',
		'file_size'        => 0,
		'file_width'       => 0,
		'file_height'      => 0,
		'file_duration'    => 0,
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

	if ( $file_id > 0 ) {
		$sql .= 'file_id, ';
	}

	$sql .= 'file_time_create, ';
	$sql .= 'file_time_update, ';
	$sql .= 'file_item_id, ';
	$sql .= 'file_kind, ';
	$sql .= 'file_url, ';
	$sql .= 'file_path, ';
	$sql .= 'file_name, ';
	$sql .= 'file_ext, ';
	$sql .= 'file_mime, ';
	$sql .= 'file_medium, ';
	$sql .= 'file_size, ';
	$sql .= 'file_width, ';
	$sql .= 'file_height, ';
	$sql .= 'file_duration ';

	$sql .= ') VALUES ( ';

	if ( $file_id > 0 ) {
		$sql .= intval($file_id).', ';
	}

	$sql .= intval($file_time_create).', ';
	$sql .= intval($file_time_update).', ';
	$sql .= intval($file_item_id).', ';
	$sql .= intval($file_kind).', ';
	$sql .= $this->quote($file_url).', ';
	$sql .= $this->quote($file_path).', ';
	$sql .= $this->quote($file_name).', ';
	$sql .= $this->quote($file_ext).', ';
	$sql .= $this->quote($file_mime).', ';
	$sql .= $this->quote($file_medium).', ';
	$sql .= intval($file_size).', ';
	$sql .= intval($file_width).', ';
	$sql .= intval($file_height).', ';
	$sql .= intval($file_duration).' ';

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
	$sql .= 'file_time_create='.intval($file_time_create).', ';
	$sql .= 'file_time_update='.intval($file_time_update).', ';
	$sql .= 'file_item_id='.intval($file_item_id).', ';
	$sql .= 'file_kind='.intval($file_kind).', ';
	$sql .= 'file_url='.$this->quote($file_url).', ';
	$sql .= 'file_path='.$this->quote($file_path).', ';
	$sql .= 'file_name='.$this->quote($file_name).', ';
	$sql .= 'file_ext='.$this->quote($file_ext).', ';
	$sql .= 'file_mime='.$this->quote($file_mime).', ';
	$sql .= 'file_medium='.$this->quote($file_medium).', ';
	$sql .= 'file_size='.intval($file_size).', ';
	$sql .= 'file_width='.intval($file_width).', ';
	$sql .= 'file_height='.intval($file_height).', ';
	$sql .= 'file_duration='.intval($file_duration).' ';
	$sql .= 'WHERE file_id='.intval($file_id);

	return $this->query( $sql, 0, 0, $force );
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function delete_by_itemid( $item_id )
{
	$sql  = 'DELETE FROM '. $this->_table;
	$sql .= ' WHERE file_item_id='. intval($item_id);
	return $this->query( $sql );
}

//---------------------------------------------------------
// get
//---------------------------------------------------------
function get_count_by_kind( $kind )
{
	$sql  = 'SELECT COUNT(*) FROM '.$this->_table;
	$sql .= ' WHERE file_kind='.intval($kind);
	return $this->get_count_by_sql( $sql );
}

function get_rows_by_kind( $kind, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' WHERE file_kind='.intval($kind);
	$sql .= ' ORDER BY file_id ASC';
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

//---------------------------------------------------------
// build
//---------------------------------------------------------
function build_row_by_param( $row, $param )
{
	$item_id     = isset($param['item_id'])     ? intval($param['item_id'])     : 0 ;
	$width       = isset($param['width'])       ? intval($param['width'])       : 0 ;
	$height      = isset($param['height'])      ? intval($param['height'])      : 0 ;
	$duration    = isset($param['duration'])    ? intval($param['duration'])    : 0 ;
	$time_update = isset($param['time_update']) ? intval($param['time_update']) : 0 ;

	$row['file_url']       = $param['url'] ;
	$row['file_path']      = $param['path'] ;
	$row['file_name']      = $param['name'] ;
	$row['file_ext']       = $param['ext'] ;
	$row['file_mime']      = $param['mime'] ;
	$row['file_medium']    = $param['medium'] ;
	$row['file_size']      = $param['size'] ;
	$row['file_kind']      = $param['kind'] ;
	$row['file_width']     = $width ;
	$row['file_height']    = $height ;
	$row['file_duration']  = $duration ;

	if ( $item_id > 0 ) {
		$row['file_item_id'] = $item_id ;
	}

	if ( $time_update > 0 ) {
		$row['file_time_update'] = $time_update ;
	}

	return $row ;
}

//---------------------------------------------------------
// show
//---------------------------------------------------------
function build_show_file_image( $file_row, $flag_exists=false )
{
	$url    = null ;
	$width  = 0 ;
	$height = 0 ;

	if ( is_array($file_row) ) {
		$url    = $file_row['file_url'] ;
		$path   = $file_row['file_path'] ;
		$width  = $file_row['file_width'] ;
		$height = $file_row['file_height'] ;

// not need '/'
		$full_path = XOOPS_ROOT_PATH . $path ;
		$full_url  = XOOPS_URL       . $path ;

		if ( $path ) {
			if ( $flag_exists && file_exists( $full_path ) ) {
				$url = $full_url ;
			} else {
				$url = $full_url ;
			}
		}
	}

	return array( $url, $width, $height );
}

// --- class end ---
}

?>