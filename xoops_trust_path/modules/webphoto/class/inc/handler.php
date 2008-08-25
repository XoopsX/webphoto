<?php
// $Id: handler.php,v 1.6 2008/08/25 19:28:05 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-24 K.OHWADA
// added is_image_kind() get_cat_cached_row_by_id()
// 2008-07-01 K.OHWADA
// added exists_column()
// added is_video_mime()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_handler
//=========================================================
class webphoto_inc_handler
{
	var $_db;
	var $_db_error;

	var $_DIRNAME;
	var $_MODULE_URL;
	var $_MODULE_DIR;

	var $_NORMAL_EXTS;

	var $_DEBUG_SQL   = false;
	var $_DEBUG_ERROR = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_handler()
{
	$this->_db =& Database::getInstance();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_handler();
	}
	return $instance;
}

function init_handler( $dirname )
{
	$this->_DIRNAME = $dirname;
	$this->_MODULE_URL = XOOPS_URL       .'/modules/'.$dirname;
	$this->_MODULE_DIR = XOOPS_ROOT_PATH .'/modules/'.$dirname;

	$constpref = strtoupper( '_P_' . $dirname. '_' ) ;
	$this->set_debug_sql_by_const_name(   $constpref.'DEBUG_INC_SQL' );
	$this->set_debug_error_by_const_name( $constpref.'DEBUG_INC_ERROR' );
}

//---------------------------------------------------------
// cat handler
//---------------------------------------------------------
function get_cat_row_by_id( $cat_id )
{
	$sql  = 'SELECT * FROM '. $this->prefix_dirname( 'cat' );
	$sql .= ' WHERE cat_id='.intval($cat_id);
	return $this->get_row_by_sql( $sql );
}

//---------------------------------------------------------
// item handler
//---------------------------------------------------------
function get_item_row_by_id( $item_id )
{
	$sql  = 'SELECT * FROM '. $this->prefix_dirname( 'item' );
	$sql .= ' WHERE item_id='. intval($item_id);
	return $this->get_row_by_sql( $sql );
}

//---------------------------------------------------------
// file handler
//---------------------------------------------------------
function get_file_row_by_kind( $item_row, $kind )
{
	$id = $this->get_file_id_by_kind( $item_row, $kind );
	if ( $id > 0 ) {
		return $this->get_file_row_by_id( $id );
	}
	return false ;
}

function get_file_id_by_kind( $item_row, $kind )
{
	$name = 'item_file_id_'.$kind;
	if ( isset( $item_row[ $name ] ) ) {
		return  $item_row[ $name ] ;
	}
	return false ;
}

function get_file_row_by_id( $file_id )
{
	$sql  = 'SELECT * FROM '. $this->prefix_dirname( 'file' );
	$sql .= ' WHERE file_id='. intval($file_id) ;
	return $this->get_row_by_sql( $sql );
}

//---------------------------------------------------------
// get
//---------------------------------------------------------
function get_count_by_sql( $sql )
{
	return intval( $this->get_first_row_by_sql( $sql ) );
}

function get_first_row_by_sql( $sql )
{
	$res = $this->query($sql);
	if ( !$res ) { return false; }

	$row = $this->_db->fetchRow( $res );
	if ( isset( $row[0] ) ) {
		return $row[0];
	}

	return false;
}

function get_row_by_sql( $sql )
{
	$res = $this->query( $sql );
	if ( !$res ) { return false; }

	$row = $this->_db->fetchArray($res);
	return $row; 
}

function get_rows_by_sql( $sql, $limit=0, $offset=0, $key=null )
{
	$arr = array();

	$res = $this->query( $sql, $limit, $offset );
	if ( !$res ) { return false; }

	while ( $row = $this->_db->fetchArray($res) ) 
	{
		if ( $key && isset( $row[ $key ] ) ) {
			$arr[ $row[ $key ] ] = $row;
		} else {
			$arr[] = $row;
		}
	}
	return $arr; 
}

//---------------------------------------------------------
// update
//---------------------------------------------------------
function exists_table( $table )
{
	$sql = "SHOW TABLES LIKE ". $this->quote($table);

	$res = $this->query($sql); 
	if ( !$res ) {
		return false;
	}

	while ( $row = $this->_db->fetchRow( $res ) ) {
		if ( strtolower( $row[0] ) == strtolower( $table ) ) {
			return true;
		}
	}

	return false;
}

function exists_column( $table, $column )
{
	$row = $this->get_column_row( $table, $column );
	if ( is_array($row) ) {
		return true;
	}
	return false;
}

function get_column_row( $table, $column )
{
	$sql = "SHOW COLUMNS FROM ". $table. " LIKE ". $this->quote($column);

	$res =& $this->query($sql); 
	if ( !$res ) {
		return false;
	}

	while ( $row = $this->_db->fetchArray( $res ) )
	{
		if ( $row['Field'] == $column ) {
			return $row;
		}
	}

	return false;
}

//---------------------------------------------------------
// handler
//---------------------------------------------------------
function query( $sql, $limit=0, $offset=0 )
{
	if ( $this->_DEBUG_SQL ) {
		echo $this->sanitize( $sql ) .': limit='. $limit .' :offset='. $offset. "<br />\n";
	}

	$res = $this->_db->query( $sql, intval($limit), intval($offset) );
	if ( !$res  ) {
		$this->_db_error = $this->_db->error();
		if ( $this->_DEBUG_ERROR ) {
			echo $this->highlight( $this->_db_error )."<br />\n";
		}
	}

	return $res;
}

function prefix_dirname( $name )
{
	return $this->_db->prefix( $this->_DIRNAME.'_'.$name ) ;
}

function quote( $str )
{
	$str = "'". addslashes($str) ."'";
	return $str;
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function is_normal_ext( $ext )
{
	if ( in_array( strtolower( $ext ) , $this->_NORMAL_EXTS ) ) {
		return true;
	}
	return false;
}

function set_normal_exts( $val )
{
	if ( is_array($val) ) {
		$this->_NORMAL_EXTS = $val;
	} else {
		$this->_NORMAL_EXTS = explode( '|', $val );
	}
}

function is_video_mime( $mime )
{
	if ( preg_match('/^video/', $mime ) ) {
		return true;
	}
	return false;
}

function is_image_kind( $kind )
{
	if ( $kind == _C_WEBPHOTO_ITEM_KIND_IMAGE ) {
		return true;
	}
	return false;
}

function is_video_kind( $kind )
{
	if ( $kind == _C_WEBPHOTO_ITEM_KIND_VIDEO ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// error
//---------------------------------------------------------
function get_db_error( $flag_sanitize=true, $flag_highlight=true )
{
	$str = $this->_db_error;
	if ( $flag_sanitize ) {
		$str = $this->sanitize( $str );
	}
	if ( $flag_highlight ) {
		$str = $this->highlight( $str );
	}
	return $str;
}

function sanitize( $str )
{
	return htmlspecialchars( $str, ENT_QUOTES );
}

function highlight( $str )
{
	$val = '<span style="color:#ff0000;">'. $str .'</span>';
	return $val;
}

//---------------------------------------------------------
// debug
//---------------------------------------------------------
function set_debug_sql_by_const_name( $name )
{
	$name = strtoupper( $name );
	if ( defined($name) ) {
		$this->set_debug_sql( constant($name) );
	}
}

function set_debug_error_by_const_name( $name )
{
	$name = strtoupper( $name );
	if ( defined($name) ) {
		$this->set_debug_error( constant($name) );
	}
}

function set_debug_sql( $val )
{
	$this->_DEBUG_SQL = (bool)$val;
}

function set_debug_error( $val )
{
	$this->_DEBUG_ERROR = (bool)$val;
}

// --- class end ---
}

?>