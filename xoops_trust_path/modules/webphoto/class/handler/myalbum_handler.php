<?php
// $Id: myalbum_handler.php,v 1.2 2009/03/15 12:38:00 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-03-15 K.OHWADA
// BUG: wrong table name
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_myalbum_handler
//=========================================================
class webphoto_myalbum_handler extends webphoto_lib_handler
{
	var $_cat_table;
	var $_photos_table;
	var $_text_table;
	var $_votedata_table;

	var $_MYALBUM_MID = 0;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_myalbum_handler()
{
	$this->webphoto_lib_handler();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_myalbum_handler();
	}
	return $instance;
}

//---------------------------------------------------------
// init
//---------------------------------------------------------
function init( $myalbum_dirname )
{
	$module = $this->_get_module_by_dirname( $myalbum_dirname );
	if ( !is_object($module) ) {
		return false;
	}

// BUG: wrong table name
	$number = '' ;
	preg_match( '/^\D+(\d*)$/', $myalbum_dirname, $matches );
	if ( isset($matches[1]) && ($matches[1] !== '') ) {
		$number = intval($matches[1]);
	}

	$table  = 'myalbum'.$number;

	$this->_MYALBUM_MID    = $module->getVar('mid');
	$this->_cat_table      = $this->db_prefix( $table.'_cat' );
	$this->_photos_table   = $this->db_prefix( $table.'_photos' );
	$this->_text_table     = $this->db_prefix( $table.'_text' );
	$this->_votedata_table = $this->db_prefix( $table.'_votedata' );

	return $this->_MYALBUM_MID;
}

//---------------------------------------------------------
// photos thumbs dir
//---------------------------------------------------------
function get_photos_thumbs_dir()
{
	$config     = $this->_get_xoops_config( $this->_MYALBUM_MID );
	$photos_dir = XOOPS_ROOT_PATH . $this->_add_slash_to_head( $config['myalbum_photospath'] );
	$thumbs_dir = XOOPS_ROOT_PATH . $this->_add_slash_to_head( $config['myalbum_thumbspath'] ) ;
	return array( $photos_dir, $thumbs_dir );
}

//---------------------------------------------------------
// cat table
//---------------------------------------------------------
function get_cat_row_by_id( $cid )
{
	$sql  = 'SELECT * FROM '.$this->_cat_table;
	$sql .= ' WHERE cid='. intval($cid);
	return $this->get_row_by_sql( $sql );
}

function get_cat_rows( $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '.$this->_cat_table;
	$sql .= ' ORDER BY cid';
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

//---------------------------------------------------------
// photos table
//---------------------------------------------------------
function get_photos_count_all()
{
	$sql  = 'SELECT count(*) FROM '.$this->_photos_table;
	return $this->get_count_by_sql( $sql );
}

function get_photos_rows( $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '.$this->_photos_table;
	$sql .= ' ORDER BY lid';
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function get_photos_rows_by_cid( $cid, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '.$this->_photos_table;
	$sql .= ' WHERE cid='. intval($cid) ;
	$sql .= ' ORDER BY lid';
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

//---------------------------------------------------------
// text table
//---------------------------------------------------------
function get_text_row_by_id( $lid )
{
	$sql = "SELECT * FROM ".$this->_text_table." WHERE lid=".$lid;
	return $this->get_row_by_sql($sql);
}

//---------------------------------------------------------
// votedata table
//---------------------------------------------------------
function get_votedata_row_by_lid( $lid )
{
	$sql  = 'SELECT * FROM '.$this->_votedata_table;
	$sql .= ' WHERE lid='. intval($lid) ;
	return $this->get_row_by_sql( $sql );
}

function get_votedata_rows( $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '.$this->_votedata_table;
	$sql .= ' ORDER BY ratingid';
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

//---------------------------------------------------------
// selbox
//---------------------------------------------------------
function build_cat_selbox( $number, $sel_name='cid' )
{
	$myalbum_cat_table    = $this->db_prefix( 'myalbum'. $number .'_cat' );
	$myalbum_photos_table = $this->db_prefix( 'myalbum'. $number .'_photos' );

	$options = myalbum_get_cat_options(
		'title' , 0 , '--' , '----' , $myalbum_cat_table , $myalbum_photos_table ) ;

	$selbox  = '<select name="'. $sel_name. '">'."\n";
	$selbox .= $options;
	$selbox .= '</select>'."\n";

	return $selbox;
}

//---------------------------------------------------------
// myalbum module
//---------------------------------------------------------
function get_myalbum_module_array()
{
	$arr1 = $this->_get_modules_myalbum_dirname_array();
	$arr2 = $this->_get_newblocks_myalbum_dirname_array();
	$arr3 = null;

	if ( is_array($arr1) && count($arr1) &&
	     is_array($arr2) && count($arr2) ) {
		$arr3 = array_unique( array_merge( $arr1, $arr2 ) );
	} elseif ( is_array($arr1) && count($arr1) ) {
		$arr3 = $arr1;
	} elseif ( is_array($arr2) && count($arr2) ) {
		$arr3 = $arr2;
	}

	if ( !is_array($arr3) || !count($arr3) ) {
		return null;
	}

	$ret = array();

	foreach( $arr3 as $dirname ) 
	{
		$module =& $this->_get_module_by_dirname( $dirname ) ;
		if ( !is_object( $module ) ) {
			continue ;
		}

		if ( !$this->_is_xoops_user_admin( $module->getVar('mid') ) ) {
			continue ;
		}

		if ( ! preg_match( '/^(\D+)(\d*)$/' , $dirname , $regs ) ) {
			echo ( "invalid dirname: " . $this->sanitize( $dirname ) ) ;
			continue ;
		}

		$number = $regs[2] === '' ? '' : intval( $regs[2] ) ;

		$ret[] = array(
			'dirname' => $dirname,
			'number'  => $number,
			'name'    => $module->name(),
		);

	}

	return $ret;
}

//---------------------------------------------------------
// modules  table
//---------------------------------------------------------
function _get_modules_myalbum_dirname_array( $limit=0, $offset=0 )
{
	$rows = $this->_get_modules_myalbum_rows( $limit, $offset );
	if ( !is_array($rows) || !count($rows) ) {
		return null;
	}

	$arr = array();
	foreach ( $rows as $row ) {
		$arr[] = $row['dirname'];
	}
	return $arr;
}

function _get_modules_myalbum_rows( $limit=0, $offset=0 )
{
// From myalbum*
	$sql  = 'SELECT * FROM '. $this->db_prefix('modules');
	$sql .= ' WHERE dirname LIKE '. $this->quote( 'myalbum%' ) ;
	return $this->get_rows_by_sql( $sql );
}

//---------------------------------------------------------
// newblocks  table
//---------------------------------------------------------
function _get_newblocks_myalbum_dirname_array( $limit=0, $offset=0 )
{
	$rows = $this->_get_newblocks_myalbum_rows( $limit, $offset );
	if ( !is_array($rows) || !count($rows) ) {
		return null;
	}

	$arr = array();
	foreach ( $rows as $row ) {
		$arr[] = $row['dirname'];
	}
	return $arr;
}

function _get_newblocks_myalbum_rows( $limit=0, $offset=0 )
{
// get all instances of TinyD using newblocks table
	$sql  = 'SELECT distinct dirname FROM '. $this->db_prefix('newblocks');
	$sql .= ' WHERE func_file='. $this->quote( 'myalbum_rphoto.php' ) ;
	return $this->get_rows_by_sql( $sql );
}

//---------------------------------------------------------
// module handler
//---------------------------------------------------------
function _get_module_by_dirname( $dirname )
{
	$module_handler =& xoops_gethandler('module');
	return $module_handler->getByDirname( $dirname );
}

//---------------------------------------------------------
// config handler
//---------------------------------------------------------
function _get_xoops_config( $mid )
{
	$config_handler =& xoops_gethandler('config');
	return $config_handler->getConfigsByCat( 0, $mid );
}

//---------------------------------------------------------
// utlity class
//---------------------------------------------------------
function _add_slash_to_head( $str )
{
// ord : the ASCII value of the first character of string
// 0x2f slash

	if( ord( $str ) != 0x2f ) {
		$str = "/". $str;
	}
	return $str;
}

//---------------------------------------------------------
// xoops param
//---------------------------------------------------------
function _is_xoops_user_admin( $mid )
{
	global $xoopsUser;
	if ( is_object($xoopsUser) ) {
		return $xoopsUser->isAdmin( $mid );
	}
	return false;
}

// --- class end ---
}

?>