<?php
// $Id: mime_handler.php,v 1.9 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// _C_WEBPHOTO_MIME_KIND_AUDIO_FFMPEG
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
// perm_int_with_like_separetor()
// 2009-10-25 K.OHWADA
// mime_kind
// 2008-10-01 K.OHWADA
// get_rows_by_exts()
// 2008-08-24 K.OHWADA
// build_perms_array_to_str()
// 2008-07-01 K.OHWADA
// added mime_ffmpeg
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_mime_handler
//=========================================================
class webphoto_mime_handler extends webphoto_handler_base_ini
{
	var $_cached_ext_array = array();

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_mime_handler( $dirname, $trust_dirname )
{
	$this->webphoto_handler_base_ini( $dirname, $trust_dirname );

	$this->set_table_prefix_dirname( 'mime' );
	$this->set_id_name( 'mime_id' );

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_mime_handler( $dirname, $trust_dirname );
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
		'mime_id'        => 0,
		'mime_time_create'  => $time_create,
		'mime_time_update'  => $time_update,
		'mime_name'     => '',
		'mime_medium'   => '',
		'mime_ext'      => '',
		'mime_type'     => '',
		'mime_perms'    => '',
		'mime_ffmpeg'   => '',
		'mime_kind'     => 0,
		'mime_option'   => '',
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

	$sql .= 'mime_time_create, ';
	$sql .= 'mime_time_update, ';
	$sql .= 'mime_name, ';
	$sql .= 'mime_ext, ';
	$sql .= 'mime_medium, ';
	$sql .= 'mime_type, ';
	$sql .= 'mime_perms, ';
	$sql .= 'mime_ffmpeg, ';
	$sql .= 'mime_kind, ';
	$sql .= 'mime_option ';

	$sql .= ') VALUES ( ';

	$sql .= intval($mime_time_create).', ';
	$sql .= intval($mime_time_update).', ';
	$sql .= $this->quote($mime_name).', ';
	$sql .= $this->quote($mime_ext).', ';
	$sql .= $this->quote($mime_medium).', ';
	$sql .= $this->quote($mime_type).', ';
	$sql .= $this->quote($mime_perms).', ';
	$sql .= $this->quote($mime_ffmpeg).', ';
	$sql .= intval($mime_kind).', ';
	$sql .= $this->quote($mime_option).' ';
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

	$sql .= 'mime_time_create='.intval($mime_time_create).', ';
	$sql .= 'mime_time_update='.intval($mime_time_update).', ';
	$sql .= 'mime_name='.$this->quote($mime_name).', ';
	$sql .= 'mime_ext='.$this->quote($mime_ext).', ';
	$sql .= 'mime_medium='.$this->quote($mime_medium).', ';
	$sql .= 'mime_type='.$this->quote($mime_type).', ';
	$sql .= 'mime_perms='.$this->quote($mime_perms).', ';
	$sql .= 'mime_ffmpeg='.$this->quote($mime_ffmpeg).', ';
	$sql .= 'mime_kind='.intval($mime_kind).', ';
	$sql .= 'mime_option='.$this->quote($mime_option).' ';

	$sql .= 'WHERE mime_id='.intval($mime_id);

	return $this->query( $sql );
}

function update_admin_all( $mime_admin )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'mime_admin='.intval($mime_admin);

	return $this->query( $sql );
}

function update_user_all( $mime_user )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'mime_user='.intval($mime_user);

	return $this->query( $sql );
}

//---------------------------------------------------------
// get row
//---------------------------------------------------------
function get_row_by_ext( $ext )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' WHERE mime_ext='.$this->quote( $ext );
	return $this->get_row_by_sql( $sql );
}

function get_cached_row_by_ext( $ext )
{
	if ( isset( $this->_cached_ext_array[ $ext ] ) ) {
		return  $this->_cached_ext_array[ $ext ];
	}

	$row = $this->get_row_by_ext( $ext );
	if ( !is_array($row) ) {
		return false;
	}

	$this->_cached_ext_array[ $ext ] = $row ;
	return $row ;
}

//---------------------------------------------------------
// get rows
//---------------------------------------------------------
function get_rows_all_orderby_ext( $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' ORDER BY mime_ext ASC, mime_id ASC';
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function get_rows_by_mygroups( $groups, $limit=0, $offset=0 )
{
	$arr = array();
	foreach ( $groups as $group ) {
		$like  = $this->perm_str_with_like_separetor( intval($group) );
		$arr[] = 'mime_perms LIKE '. $this->quote($like) ;
	}
	$where = implode( ' OR ', $arr );
	return $this->get_rows_by_where( $where, $limit, $offset );
}

function get_rows_by_exts( $exts, $limit=0, $offset=0 )
{
	$arr = array();
	foreach ( $exts as $ext ) {
		$arr[] = 'mime_ext='. $this->quote($ext) ;
	}
	$where = implode( ' OR ', $arr );
	return $this->get_rows_by_where( $where, $limit, $offset );
}

//---------------------------------------------------------
// get option
//---------------------------------------------------------
function get_kind_options()
{
	$arr = array(
		_C_WEBPHOTO_MIME_KIND_GENERAL       => _WEBPHOTO_MIME_KIND_GENERAL ,
		_C_WEBPHOTO_MIME_KIND_IMAGE         => _WEBPHOTO_MIME_KIND_IMAGE ,

// v2.30
		_C_WEBPHOTO_MIME_KIND_IMAGE_JPEG    => _WEBPHOTO_MIME_KIND_IMAGE_JPEG ,

		_C_WEBPHOTO_MIME_KIND_IMAGE_CONVERT => _WEBPHOTO_MIME_KIND_IMAGE_CONVERT ,
		_C_WEBPHOTO_MIME_KIND_VIDEO         => _WEBPHOTO_MIME_KIND_VIDEO ,

// v2.30
		_C_WEBPHOTO_MIME_KIND_VIDEO_FLV     => _WEBPHOTO_MIME_KIND_VIDEO_FLV ,

		_C_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG  => _WEBPHOTO_MIME_KIND_VIDEO_FFMPEG ,
		_C_WEBPHOTO_MIME_KIND_AUDIO         => _WEBPHOTO_MIME_KIND_AUDIO ,
		_C_WEBPHOTO_MIME_KIND_AUDIO_MID     => _WEBPHOTO_MIME_KIND_AUDIO_MID ,
		_C_WEBPHOTO_MIME_KIND_AUDIO_WAV     => _WEBPHOTO_MIME_KIND_AUDIO_WAV ,

// v2.30
		_C_WEBPHOTO_MIME_KIND_AUDIO_MP3     => _WEBPHOTO_MIME_KIND_AUDIO_MP3 ,
		_C_WEBPHOTO_MIME_KIND_AUDIO_FFMPEG  => _WEBPHOTO_MIME_KIND_AUDIO_FFMPEG ,

		_C_WEBPHOTO_MIME_KIND_OFFICE        => _WEBPHOTO_MIME_KIND_OFFICE ,
		_C_WEBPHOTO_MIME_KIND_OFFICE_DOC    => _WEBPHOTO_MIME_KIND_OFFICE_DOC ,
		_C_WEBPHOTO_MIME_KIND_OFFICE_XLS    => _WEBPHOTO_MIME_KIND_OFFICE_XLS ,
		_C_WEBPHOTO_MIME_KIND_OFFICE_PPT    => _WEBPHOTO_MIME_KIND_OFFICE_PPT ,
		_C_WEBPHOTO_MIME_KIND_OFFICE_PDF    => _WEBPHOTO_MIME_KIND_OFFICE_PDF ,
	);
	return $arr;
}

//---------------------------------------------------------
// build
//---------------------------------------------------------
function build_perms_array_to_str( $arr )
{
	if ( !is_array($arr) || !count($arr) ) {
		return null;
	}

// array -> &1&2&3&
	$str = $this->parm_array_to_str( $arr );
	$ret = $this->perm_str_with_separetor( $str ) ;
	return $ret ;
}

function build_perms_row_to_array( $row )
{
	return $this->perm_str_to_array( $row['mime_perms'] );
}

// --- class end ---
}

?>