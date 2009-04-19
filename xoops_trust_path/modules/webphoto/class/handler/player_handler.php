<?php
// $Id: player_handler.php,v 1.2 2009/04/19 11:39:45 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-04-19 K.OHWADA
// build_row_options()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_player_handler
//=========================================================
class webphoto_player_handler extends webphoto_lib_handler
{
	var $_WIDTH_DEFAULT  = _C_WEBPHOTO_PLAYER_WIDTH_DEFAULT ;
	var $_HEIGHT_DEFAULT = _C_WEBPHOTO_PLAYER_HEIGHT_DEFAULT ;

	var $_THIS_TITLE_NAME = 'player_title';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_player_handler( $dirname )
{
	$this->webphoto_lib_handler( $dirname );
	$this->set_table_prefix_dirname( 'player' );
	$this->set_id_name( 'player_id' );
	$this->set_title_name( $this->_THIS_TITLE_NAME );

	$constpref = strtoupper( '_P_' . $dirname. '_' ) ;
	$this->set_debug_sql_by_const_name(   $constpref.'DEBUG_SQL' );
	$this->set_debug_error_by_const_name( $constpref.'DEBUG_ERROR' );

}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_player_handler( $dirname );
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
		'player_id'             => 0,
		'player_time_create'    => $time_create,
		'player_time_update'    => $time_update,
		'player_pid'            => 0,
		'player_style'          => 0 ,
		'player_title'          => '',
		'player_width'          => $this->_WIDTH_DEFAULT ,
		'player_height'         => $this->_HEIGHT_DEFAULT ,
		'player_displaywidth'   => 0 ,
		'player_displayheight'  => 0 ,
		'player_screencolor'    => '',
		'player_backcolor'      => '',
		'player_frontcolor'     => '',
		'player_lightcolor'     => '',
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

	$sql .= 'player_time_create, ';
	$sql .= 'player_time_update, ';
	$sql .= 'player_style, ';
	$sql .= 'player_title, ';
	$sql .= 'player_width, ';
	$sql .= 'player_height, ';
	$sql .= 'player_displaywidth, ';
	$sql .= 'player_displayheight, ';
	$sql .= 'player_screencolor, ';
	$sql .= 'player_backcolor, ';
	$sql .= 'player_frontcolor, ';
	$sql .= 'player_lightcolor ';

	$sql .= ') VALUES ( ';

	if ( $player_id > 0 ) {
		$sql .= intval($player_id).', ';
	}

	$sql .= intval($player_time_create).', ';
	$sql .= intval($player_time_update).', ';
	$sql .= intval($player_style).', ';
	$sql .= $this->quote($player_title).', ';
	$sql .= intval($player_width).', ';
	$sql .= intval($player_height).', ';
	$sql .= intval($player_displaywidth).', ';
	$sql .= intval($player_displayheight).', ';
	$sql .= $this->quote($player_screencolor).', ';
	$sql .= $this->quote($player_backcolor).', ';
	$sql .= $this->quote($player_frontcolor).', ';
	$sql .= $this->quote($player_lightcolor).' ';

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
	$sql .= 'player_time_create='.intval($player_time_create).', ';
	$sql .= 'player_time_update='.intval($player_time_update).', ';
	$sql .= 'player_style='.intval($player_style).', ';
	$sql .= 'player_title='.$this->quote($player_title).', ';
	$sql .= 'player_width='.intval($player_width).', ';
	$sql .= 'player_height='.intval($player_height).', ';
	$sql .= 'player_displaywidth='.intval($player_displaywidth).', ';
	$sql .= 'player_displayheight='.intval($player_displayheight).', ';
	$sql .= 'player_screencolor='.$this->quote($player_screencolor).', ';
	$sql .= 'player_backcolor='.$this->quote($player_backcolor).', ';
	$sql .= 'player_frontcolor='.$this->quote($player_frontcolor).', ';
	$sql .= 'player_lightcolor='.$this->quote($player_lightcolor).' ';
	$sql .= 'WHERE player_id='.intval($player_id);

	return $this->query( $sql );
}

//---------------------------------------------------------
// get rows
//---------------------------------------------------------
function get_rows_list( $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->_table;
	$sql .= ' WHERE player_id > 0 ';
	$sql .= ' ORDER BY player_title' ;
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function get_rows_by_title( $title, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->_table;
	$sql .= ' WHERE player_title = '.$this->quote($title);
	$sql .= ' ORDER BY player_id' ;
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

//---------------------------------------------------------
// option
//---------------------------------------------------------
function get_style_options()
{
	$arr = array(
		'0' => _WEBPHOTO_PLAYER_STYLE_MONO ,
//		'1' => _WEBPHOTO_PLAYER_STYLE_THEME ,
		'2' => _WEBPHOTO_PLAYER_STYLE_PLAYER ,
//		'3' => _WEBPHOTO_PLAYER_STYLE_PAGE ,
	);
	return $arr;
}

//---------------------------------------------------------
// selbox
//---------------------------------------------------------
function build_row_options()
{
	$rows = $this->get_rows_by_orderby( $this->_THIS_TITLE_NAME );
	return  $this->build_form_select_options( $rows, $this->_THIS_TITLE_NAME );
}

// --- class end ---
}

?>