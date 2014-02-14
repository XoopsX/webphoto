<?php
// $Id: file_handler.php,v 1.11 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// change build_show_file_image()
// 2011-05-01 K.OHWADA
// get_download_image_aux()
// 2010-11-11 K.OHWADA
// build_full_path()
// 2010-09-20 K.OHWADA
// function get_file_full_by_key()
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
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
class webphoto_file_handler extends webphoto_handler_base_ini
{
	var $_cached_extend = array();

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_file_handler( $dirname, $trust_dirname )
{
	$this->webphoto_handler_base_ini( $dirname, $trust_dirname );
	$this->set_table_prefix_dirname( 'file' );
	$this->set_id_name( 'file_id' );

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_file_handler( $dirname, $trust_dirname );
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

function get_extend_row_by_id( $id )
{
	$row = $this->get_row_by_id( $id );
	if ( is_array($row) ) {
		$full_path               = $this->build_full_path_by_row( $row );
		$full_url                = $this->build_full_url_by_row(  $row );
		$row['full_path']        = $full_path;
		$row['full_url']         = $full_url;
		$row['full_path_exists'] = $this->exists_file( $full_path );
		return $row;
	}
	return null;
}

function get_cached_extend_row_by_id( $id )
{
	if ( isset( $this->_cached_extend[ $id ] ) ) {
		return  $this->_cached_extend[ $id ];
	}

	$row = $this->get_extend_row_by_id( $id );
	if ( is_array($row) ) {
		$this->_cached_extend[ $id ] = $row;
		return $row;
	}

	return null;
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
		$file_url = $file_row['file_url'] ;
		$width    = $file_row['file_width'] ;
		$height   = $file_row['file_height'] ;

		$full_url  = $this->build_full_url_by_row(  $file_row );
		$full_path = $this->build_full_path_by_row( $file_row );
		$exists    = $this->exists_file( $full_path );

		if ( $flag_exists && $exists && $full_url) {
			$url = $full_url ;

		} elseif ( $file_url ) {
			$url = $file_url ;
		}
	}

	return array( $url, $width, $height );
}

function get_full_path_by_id( $id )
{
	$row = $this->get_extend_row_by_id( $id );
	return $this->get_full_path_by_row( $row );
}

function exists_full_path_by_id( $id )
{
	$row  = $this->get_extend_row_by_id( $id );
	return $this->exists_full_path_by_row( $row );
}

function exists_full_path_by_row( $row )
{
	$file = $this->get_full_path_by_row( $row );
	return $this->exists_file( $file );
}

function get_full_path_by_row( $row )
{
	if ( is_array($row) && $row['full_path'] ) {
		return $row['full_path'];
	}
	return null;
}

function build_full_path_by_row( $row )
{
	if ( is_array($row) && $row['file_path'] ) {
		return $this->build_full_path( $row['file_path'] );
	}
	return null;
}

function build_full_url_by_row( $row )
{
	if ( is_array($row) && $row['file_path'] ) {
		return $this->build_full_url( $row['file_path'] );
	}
	return null;
}

function build_full_path( $path )
{
	if ( $path ) {
		$str = XOOPS_ROOT_PATH . $path ;
		return $str;
	}
	return null;
}

function build_full_url( $path )
{
	if ( $path ) {
		$str = XOOPS_URL . $path ;
		return $str;
	}
	return null;
}

function exists_file( $file )
{
	if ( $file && file_exists($file) && is_file($file) && !is_dir($file) ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// options
//---------------------------------------------------------
function get_file_kind_all_options()
{
	$arr = array(
		_C_WEBPHOTO_FILE_KIND_CONT   => 'cont',
		_C_WEBPHOTO_FILE_KIND_THUMB  => 'thumb',
		_C_WEBPHOTO_FILE_KIND_MIDDLE => 'middile',
		_C_WEBPHOTO_FILE_KIND_FLASH  => 'flash',
		_C_WEBPHOTO_FILE_KIND_DOCOMO => 'docomo',
		_C_WEBPHOTO_FILE_KIND_PDF    => 'pdf',
		_C_WEBPHOTO_FILE_KIND_MIDDLE => 'middile',
		_C_WEBPHOTO_FILE_KIND_SWF    => 'swf',
		_C_WEBPHOTO_FILE_KIND_JPEG   => 'jpeg',
		_C_WEBPHOTO_FILE_KIND_MP3    => 'mp3',
		_C_WEBPHOTO_FILE_KIND_WAV    => 'wav',
		_C_WEBPHOTO_FILE_KIND_LARGE  => 'large',
	);
	return $arr;
}

function get_file_kind_image_options()
{
	$arr = array(
//		_C_WEBPHOTO_FILE_KIND_CONT   => 'cont',
		_C_WEBPHOTO_FILE_KIND_THUMB  => 'thumb',
		_C_WEBPHOTO_FILE_KIND_MIDDLE => 'middile',
//		_C_WEBPHOTO_FILE_KIND_FLASH  => 'flash',
//		_C_WEBPHOTO_FILE_KIND_DOCOMO => 'docomo',
//		_C_WEBPHOTO_FILE_KIND_PDF    => 'pdf',
		_C_WEBPHOTO_FILE_KIND_MIDDLE => 'middile',
//		_C_WEBPHOTO_FILE_KIND_SWF    => 'swf',
		_C_WEBPHOTO_FILE_KIND_JPEG   => 'jpeg',
//		_C_WEBPHOTO_FILE_KIND_MP3    => 'mp3',
//		_C_WEBPHOTO_FILE_KIND_WAV    => 'wav',
		_C_WEBPHOTO_FILE_KIND_LARGE  => 'large',
	);
	return $arr;
}

//---------------------------------------------------------
// options
//---------------------------------------------------------
function get_download_image_aux( $file_kind )
{
	$options = $this->get_file_kind_image_options();
	if ( isset( $options[ $file_kind ] ) ) {
		return  $options[ $file_kind ];
	}
	return false;
}

// --- class end ---
}

?>