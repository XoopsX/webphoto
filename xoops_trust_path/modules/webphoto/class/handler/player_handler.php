<?php
// $Id: player_handler.php,v 1.4 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
// player_title_default
// 2009-10-25 K.OHWADA
// BUG: player id is not correctly selected 
// 2009-04-19 K.OHWADA
// build_row_options()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_player_handler
//=========================================================
class webphoto_player_handler extends webphoto_handler_base_ini
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_player_handler( $dirname, $trust_dirname )
{
	$this->webphoto_handler_base_ini( $dirname, $trust_dirname );
	$this->set_table_prefix_dirname( 'player' );
	$this->set_id_name( 'player_id' );
	$this->set_title_name( $this->get_ini( 'player_title_name' ) );

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_player_handler( $dirname, $trust_dirname );
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
		'player_title'          => $this->get_ini('player_title_default') ,
		'player_width'          => $this->get_ini('player_width_default') ,
		'player_height'         => $this->get_ini('player_height_default') ,
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
// BUG: player id is not correctly selected 
function build_row_options( $preset_id, $flag_undefined=false )
{
	$player_title_name = $this->get_ini( 'player_title_name' );

	$rows = $this->get_rows_by_orderby( $player_title_name );

	if ( $flag_undefined ) {
		array_unshift( $rows, $this->create() );
	}

	return  $this->build_form_select_options( $rows, $player_title_name, $preset_id );
}

// --- class end ---
}

?>