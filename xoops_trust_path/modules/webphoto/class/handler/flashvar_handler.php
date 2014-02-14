<?php
// $Id: flashvar_handler.php,v 1.3 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// JW Player 5.6
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
// flashvar_autostart_default
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_flashvar_handler
//=========================================================
class webphoto_flashvar_handler extends webphoto_handler_base_ini
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_flashvar_handler( $dirname, $trust_dirname )
{
	$this->webphoto_handler_base_ini( $dirname, $trust_dirname );
	$this->set_table_prefix_dirname( 'flashvar' );
	$this->set_id_name( 'flashvar_id' );

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_flashvar_handler( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create
//---------------------------------------------------------
function create( $flag_new=false )
{
	$time_create  = 0 ;
	$time_update  = 0 ;
	$bufferlength = 0 ;
	$rotatetime   = 0 ;
	$volume       = 0 ;
	$bufferlength = 0;
	$shuffle      = -1;
	$autostart    = -1;
	$linktarget   = '' ;
	$overstretch  = '' ;
	$transition   = '' ;

// JW Player 5.6
	$dock       = -1;
	$icons      = -1;
	$smoothing  = -1;
	$mute       = -1;
	$stretching    = '';
	$player_repeat = '';
	$controlbar_position = '';
	$controlbar_idlehide = -1;
	$display_showmute    = -1;
	$playlist_size     = 0;
	$playlist_position = '';
	$logo_hide       = -1;
	$logo_margin     = 0;
	$logo_timeout    = 0;
	$logo_over       = -1;
	$logo_out        = -1;
	$logo_position   = '';
	$logo_linktarget = '';

	if ( $flag_new ) {
		$time = time();
		$time_create  = $time;
		$time_update  = $time;
		$bufferlength = $this->get_ini('flashvar_bufferlength_default') ;
		$rotatetime   = $this->get_ini('flashvar_rotatetime_default') ;
		$volume       = $this->get_ini('flashvar_volume_default') ;
		$linktarget   = $this->get_ini('flashvar_linktarget_default') ;
		$overstretch  = $this->get_ini('flashvar_overstretch_default') ;
		$transition   = $this->get_ini('flashvar_transition_default') ;
		$autostart    = $this->get_ini('flashvar_autostart_default') ;
		$smoothing    = $this->get_ini('flashvar_smoothing_default') ;
		$shuffle      = $this->get_ini('flashvar_shuffle_default') ;

// JW Player 5.6
		$dock       = $this->get_ini('flashvar_dock_default') ;
		$icons      = $this->get_ini('flashvar_icons_default') ;
		$mute       = $this->get_ini('flashvar_mute_default') ;
		$stretching = $this->get_ini('flashvar_stretching_default') ;
		$player_repeat = $this->get_ini('flashvar_player_repeat_default') ;
		$controlbar_idlehide = $this->get_ini('flashvar_controlbar_idlehide_default') ;
		$controlbar_position = $this->get_ini('flashvar_controlbar_position_default') ;
		$display_showmute  = $this->get_ini('flashvar_display_showmute_default') ;
		$playlist_size     = $this->get_ini('flashvar_playlist_size_default') ;
		$playlist_position = $this->get_ini('flashvar_playlist_position_default') ;
		$logo_hide       = $this->get_ini('flashvar_logo_hide_default') ;
		$logo_margin     = $this->get_ini('flashvar_logo_margin_default') ;
		$logo_timeout    = $this->get_ini('flashvar_logo_timeout_default') ;
		$logo_over       = $this->get_ini('flashvar_logo_over_default') ;
		$logo_out        = $this->get_ini('flashvar_logo_out_default') ;
		$logo_position   = $this->get_ini('flashvar_logo_position_default') ;
		$logo_linktarget = $this->get_ini('flashvar_logo_linktarget_default') ;
	}

	$arr = array(
		'flashvar_id'               => 0,
		'flashvar_time_create'      => $time_create,
		'flashvar_time_update'      => $time_update,
		'flashvar_item_id'          => 0,
		'flashvar_width'            => 0 , 
		'flashvar_height'           => 0 ,
		'flashvar_displaywidth'     => 0 ,
		'flashvar_displayheight'    => 0 ,
		'flashvar_image_show'       => 1,	// true
		'flashvar_searchbar'        => 0,
		'flashvar_showeq'           => 0,
		'flashvar_showicons'        => 1,	// true
		'flashvar_shownavigation'   => 1,	// true
		'flashvar_showstop'         => 0,
		'flashvar_showdigits'       => 1 ,	// true
		'flashvar_showdownload'     => 0,
		'flashvar_usefullscreen'    => 1 ,	// true
		'flashvar_autoscroll'       => 0,
		'flashvar_thumbsinplaylist' => 1 ,	// true
		'flashvar_autostart'        => $autostart ,
		'flashvar_repeat'           => 0,
		'flashvar_enablejs'         => 0,
		'flashvar_linkfromdisplay'  => 0,
		'flashvar_link_type'        => 0,
		'flashvar_screencolor'      => '',
		'flashvar_backcolor'        => '',
		'flashvar_frontcolor'       => '',
		'flashvar_lightcolor'       => '',
		'flashvar_type'             => '',
		'flashvar_file'             => '',
		'flashvar_image'            => '',
		'flashvar_logo'             => '',
		'flashvar_link'             => '',
		'flashvar_audio'            => '',
		'flashvar_captions'         => '',
		'flashvar_fallback'         => '',
		'flashvar_callback'         => '',
		'flashvar_javascriptid'     => '',
		'flashvar_recommendations'  => '',
		'flashvar_searchlink'       => '',
		'flashvar_streamscript'     => '',
		'flashvar_bufferlength'     => $bufferlength ,
		'flashvar_rotatetime'       => $rotatetime ,
		'flashvar_volume'           => $volume ,
		'flashvar_linktarget'       => $linktarget ,
		'flashvar_overstretch'      => $overstretch ,
		'flashvar_transition'       => $transition ,
		'flashvar_smoothing'        => $smoothing,
		'flashvar_shuffle'          => $shuffle,

// JW Player 5.6
		'flashvar_duration' => '0',
		'flashvar_start'    => '0',
		'flashvar_item'     => '0',
		'flashvar_playlistfile' => '',
		'flashvar_mediaid'      => '',
		'flashvar_provider'     => '',
		'flashvar_streamer'     => '',
		'flashvar_netstreambasepath' => '',
		'flashvar_skin'          => '',
		'flashvar_playerready'   => '',
		'flashvar_plugins'       => '',
		'flashvar_logo_file'     => '',
		'flashvar_logo_link'     => '',
		'flashvar_dock'  => $dock,
		'flashvar_icons' => $icons,
		'flashvar_mute'  => $mute,
		'flashvar_stretching'    => $stretching,
		'flashvar_player_repeat' => $player_repeat,
		'flashvar_controlbar_position' => $controlbar_position,
		'flashvar_controlbar_idlehide' => $controlbar_idlehide,
		'flashvar_display_showmute'    => $display_showmute,
		'flashvar_playlist_size'       => $playlist_size,
		'flashvar_playlist_position'   => $playlist_position,
		'flashvar_logo_hide'       => $logo_hide,
		'flashvar_logo_margin'     => $logo_margin,
		'flashvar_logo_timeout'    => $logo_timeout,
		'flashvar_logo_over'       => $logo_over,
		'flashvar_logo_out'        => $logo_out,
		'flashvar_logo_linktarget' => $logo_linktarget,
		'flashvar_logo_position'   => $logo_position,
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

	$sql .= 'flashvar_time_create, ';
	$sql .= 'flashvar_time_update, ';
	$sql .= 'flashvar_item_id, ';
	$sql .= 'flashvar_width, ';
	$sql .= 'flashvar_height, ';
	$sql .= 'flashvar_displaywidth, ';
	$sql .= 'flashvar_displayheight, ';
	$sql .= 'flashvar_image_show, ';
	$sql .= 'flashvar_searchbar, ';
	$sql .= 'flashvar_showeq, ';
	$sql .= 'flashvar_showicons, ';
	$sql .= 'flashvar_shownavigation, ';
	$sql .= 'flashvar_showstop, ';
	$sql .= 'flashvar_showdigits, ';
	$sql .= 'flashvar_showdownload, ';
	$sql .= 'flashvar_usefullscreen, ';
	$sql .= 'flashvar_autoscroll, ';
	$sql .= 'flashvar_thumbsinplaylist, ';
	$sql .= 'flashvar_autostart, ';
	$sql .= 'flashvar_repeat, ';
	$sql .= 'flashvar_shuffle, ';
	$sql .= 'flashvar_smoothing, ';
	$sql .= 'flashvar_enablejs, ';
	$sql .= 'flashvar_linkfromdisplay, ';
	$sql .= 'flashvar_link_type, ';
	$sql .= 'flashvar_bufferlength, ';
	$sql .= 'flashvar_rotatetime, ';
	$sql .= 'flashvar_volume, ';
	$sql .= 'flashvar_screencolor, ';
	$sql .= 'flashvar_backcolor, ';
	$sql .= 'flashvar_frontcolor, ';
	$sql .= 'flashvar_lightcolor, ';
	$sql .= 'flashvar_linktarget, ';
	$sql .= 'flashvar_overstretch, ';
	$sql .= 'flashvar_transition, ';
	$sql .= 'flashvar_type, ';
	$sql .= 'flashvar_file, ';
	$sql .= 'flashvar_image, ';
	$sql .= 'flashvar_logo, ';
	$sql .= 'flashvar_link, ';
	$sql .= 'flashvar_audio, ';
	$sql .= 'flashvar_captions, ';
	$sql .= 'flashvar_fallback, ';
	$sql .= 'flashvar_callback, ';
	$sql .= 'flashvar_javascriptid, ';
	$sql .= 'flashvar_recommendations, ';
	$sql .= 'flashvar_streamscript, ';
	$sql .= 'flashvar_searchlink, ';

// JW Player 5.6
	$sql .= 'flashvar_dock, ';
	$sql .= 'flashvar_icons, ';
	$sql .= 'flashvar_mute, ';
	$sql .= 'flashvar_controlbar_idlehide, ';
	$sql .= 'flashvar_display_showmute, ';
	$sql .= 'flashvar_logo_hide, ';
	$sql .= 'flashvar_duration, ';
	$sql .= 'flashvar_start, ';
	$sql .= 'flashvar_item, ';
	$sql .= 'flashvar_playlist_size, ';
	$sql .= 'flashvar_logo_margin, ';
	$sql .= 'flashvar_logo_timeout, ';
	$sql .= 'flashvar_logo_over, ';
	$sql .= 'flashvar_logo_out, ';
	$sql .= 'flashvar_playlistfile, ';
	$sql .= 'flashvar_mediaid, ';
	$sql .= 'flashvar_provider, ';
	$sql .= 'flashvar_streamer, ';
	$sql .= 'flashvar_netstreambasepath, ';
	$sql .= 'flashvar_skin, ';
	$sql .= 'flashvar_player_repeat, ';
	$sql .= 'flashvar_playerready, ';
	$sql .= 'flashvar_plugins, ';
	$sql .= 'flashvar_stretching, ';
	$sql .= 'flashvar_controlbar_position, ';
	$sql .= 'flashvar_playlist_position, ';
	$sql .= 'flashvar_logo_file, ';
	$sql .= 'flashvar_logo_link, ';
	$sql .= 'flashvar_logo_linktarget, ';
	$sql .= 'flashvar_logo_position ';

	$sql .= ') VALUES ( ';

	if ( $flashvar_id > 0 ) {
		$sql .= intval($flashvar_id).', ';
	}

	$sql .= intval($flashvar_time_create).', ';
	$sql .= intval($flashvar_time_update).', ';
	$sql .= intval($flashvar_item_id).', ';
	$sql .= intval($flashvar_width).', ';
	$sql .= intval($flashvar_height).', ';
	$sql .= intval($flashvar_displaywidth).', ';
	$sql .= intval($flashvar_displayheight).', ';
	$sql .= intval($flashvar_image_show).', ';
	$sql .= intval($flashvar_searchbar).', ';
	$sql .= intval($flashvar_showeq).', ';
	$sql .= intval($flashvar_showicons).', ';
	$sql .= intval($flashvar_shownavigation).', ';
	$sql .= intval($flashvar_showstop).', ';
	$sql .= intval($flashvar_showdigits).', ';
	$sql .= intval($flashvar_showdownload).', ';
	$sql .= intval($flashvar_usefullscreen).', ';
	$sql .= intval($flashvar_autoscroll).', ';
	$sql .= intval($flashvar_thumbsinplaylist).', ';
	$sql .= intval($flashvar_autostart).', ';
	$sql .= intval($flashvar_repeat).', ';
	$sql .= intval($flashvar_shuffle).', ';
	$sql .= intval($flashvar_smoothing).', ';
	$sql .= intval($flashvar_enablejs).', ';
	$sql .= intval($flashvar_linkfromdisplay).', ';
	$sql .= intval($flashvar_link_type).', ';
	$sql .= intval($flashvar_bufferlength).', ';
	$sql .= intval($flashvar_rotatetime).', ';
	$sql .= intval($flashvar_volume).', ';
	$sql .= $this->quote($flashvar_screencolor).', ';
	$sql .= $this->quote($flashvar_backcolor).', ';
	$sql .= $this->quote($flashvar_frontcolor).', ';
	$sql .= $this->quote($flashvar_lightcolor).', ';
	$sql .= $this->quote($flashvar_linktarget).', ';
	$sql .= $this->quote($flashvar_overstretch).', ';
	$sql .= $this->quote($flashvar_transition).', ';
	$sql .= $this->quote($flashvar_type).', ';
	$sql .= $this->quote($flashvar_file).', ';
	$sql .= $this->quote($flashvar_image).', ';
	$sql .= $this->quote($flashvar_logo).', ';
	$sql .= $this->quote($flashvar_link).', ';
	$sql .= $this->quote($flashvar_audio).', ';
	$sql .= $this->quote($flashvar_captions).', ';
	$sql .= $this->quote($flashvar_fallback).', ';
	$sql .= $this->quote($flashvar_callback).', ';
	$sql .= $this->quote($flashvar_javascriptid).', ';
	$sql .= $this->quote($flashvar_recommendations).', ';
	$sql .= $this->quote($flashvar_streamscript).', ';
	$sql .= $this->quote($flashvar_searchlink).', ';

// JW Player 5.6
	$sql .= intval($flashvar_dock).', ';
	$sql .= intval($flashvar_icons).', ';
	$sql .= intval($flashvar_mute).', ';
	$sql .= intval($flashvar_controlbar_idlehide).', ';
	$sql .= intval($flashvar_display_showmute).', ';
	$sql .= intval($flashvar_logo_hide).', ';
	$sql .= intval($flashvar_duration).', ';
	$sql .= intval($flashvar_start).', ';
	$sql .= intval($flashvar_item).', ';
	$sql .= intval($flashvar_playlist_size).', ';
	$sql .= intval($flashvar_logo_margin).', ';
	$sql .= intval($flashvar_logo_timeout).', ';
	$sql .= floatval($flashvar_logo_over).', ';
	$sql .= floatval($flashvar_logo_out).', ';
	$sql .= $this->quote($flashvar_playlistfile).', ';
	$sql .= $this->quote($flashvar_mediaid).', ';
	$sql .= $this->quote($flashvar_provider).', ';
	$sql .= $this->quote($flashvar_streamer).', ';
	$sql .= $this->quote($flashvar_netstreambasepath).', ';
	$sql .= $this->quote($flashvar_skin).', ';	
	$sql .= $this->quote($flashvar_player_repeat).', ';
	$sql .= $this->quote($flashvar_playerready).', ';
	$sql .= $this->quote($flashvar_plugins).', ';
	$sql .= $this->quote($flashvar_stretching).', ';
	$sql .= $this->quote($flashvar_controlbar_position).', ';
	$sql .= $this->quote($flashvar_playlist_position).', ';
	$sql .= $this->quote($flashvar_logo_file).', ';
	$sql .= $this->quote($flashvar_logo_link).', ';
	$sql .= $this->quote($flashvar_logo_linktarget).', ';
	$sql .= $this->quote($flashvar_logo_position).' ';

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
	$sql .= 'flashvar_time_create='.intval($flashvar_time_create).', ';
	$sql .= 'flashvar_time_update='.intval($flashvar_time_update).', ';
	$sql .= 'flashvar_item_id='.intval($flashvar_item_id).', ';
	$sql .= 'flashvar_width='.intval($flashvar_width).', ';
	$sql .= 'flashvar_height='.intval($flashvar_height).', ';
	$sql .= 'flashvar_displaywidth='.intval($flashvar_displaywidth).', ';
	$sql .= 'flashvar_displayheight='.intval($flashvar_displayheight).', ';
	$sql .= 'flashvar_image_show='.intval($flashvar_image_show).', ';
	$sql .= 'flashvar_searchbar='.intval($flashvar_searchbar).', ';
	$sql .= 'flashvar_showeq='.intval($flashvar_showeq).', ';
	$sql .= 'flashvar_showicons='.intval($flashvar_showicons).', ';
	$sql .= 'flashvar_shownavigation='.intval($flashvar_shownavigation).', ';
	$sql .= 'flashvar_showstop='.intval($flashvar_showstop).', ';
	$sql .= 'flashvar_showdigits='.intval($flashvar_showdigits).', ';
	$sql .= 'flashvar_showdownload='.intval($flashvar_showdownload).', ';
	$sql .= 'flashvar_usefullscreen='.intval($flashvar_usefullscreen).', ';
	$sql .= 'flashvar_autoscroll='.intval($flashvar_autoscroll).', ';
	$sql .= 'flashvar_thumbsinplaylist='.intval($flashvar_thumbsinplaylist).', ';
	$sql .= 'flashvar_autostart='.intval($flashvar_autostart).', ';
	$sql .= 'flashvar_repeat='.intval($flashvar_repeat).', ';
	$sql .= 'flashvar_shuffle='.intval($flashvar_shuffle).', ';
	$sql .= 'flashvar_smoothing='.intval($flashvar_smoothing).', ';
	$sql .= 'flashvar_enablejs='.intval($flashvar_enablejs).', ';
	$sql .= 'flashvar_linkfromdisplay='.intval($flashvar_linkfromdisplay).', ';
	$sql .= 'flashvar_link_type='.intval($flashvar_link_type).', ';
	$sql .= 'flashvar_bufferlength='.intval($flashvar_bufferlength).', ';
	$sql .= 'flashvar_rotatetime='.intval($flashvar_rotatetime).', ';
	$sql .= 'flashvar_volume='.intval($flashvar_volume).', ';
	$sql .= 'flashvar_linktarget='.$this->quote($flashvar_linktarget).', ';
	$sql .= 'flashvar_overstretch='.$this->quote($flashvar_overstretch).', ';
	$sql .= 'flashvar_transition='.$this->quote($flashvar_transition).', ';
	$sql .= 'flashvar_screencolor='.$this->quote($flashvar_screencolor).', ';
	$sql .= 'flashvar_backcolor='.$this->quote($flashvar_backcolor).', ';
	$sql .= 'flashvar_frontcolor='.$this->quote($flashvar_frontcolor).', ';
	$sql .= 'flashvar_lightcolor='.$this->quote($flashvar_lightcolor).', ';
	$sql .= 'flashvar_type='.$this->quote($flashvar_type).', ';
	$sql .= 'flashvar_file='.$this->quote($flashvar_file).', ';
	$sql .= 'flashvar_image='.$this->quote($flashvar_image).', ';
	$sql .= 'flashvar_logo='.$this->quote($flashvar_logo).', ';
	$sql .= 'flashvar_link='.$this->quote($flashvar_link).', ';
	$sql .= 'flashvar_audio='.$this->quote($flashvar_audio).', ';
	$sql .= 'flashvar_captions='.$this->quote($flashvar_captions).', ';
	$sql .= 'flashvar_fallback='.$this->quote($flashvar_fallback).', ';
	$sql .= 'flashvar_callback='.$this->quote($flashvar_callback).', ';
	$sql .= 'flashvar_javascriptid='.$this->quote($flashvar_javascriptid).', ';
	$sql .= 'flashvar_recommendations='.$this->quote($flashvar_recommendations).', ';
	$sql .= 'flashvar_streamscript='.$this->quote($flashvar_streamscript).', ';
	$sql .= 'flashvar_searchlink='.$this->quote($flashvar_searchlink).', ';

// JW Player 5.6
	$sql .= 'flashvar_dock='.intval($flashvar_dock).', ';
	$sql .= 'flashvar_icons='.intval($flashvar_icons).', ';
	$sql .= 'flashvar_mute='.intval($flashvar_mute).', ';
	$sql .= 'flashvar_controlbar_idlehide='.intval($flashvar_controlbar_idlehide).', ';
	$sql .= 'flashvar_display_showmute='.intval($flashvar_display_showmute).', ';
	$sql .= 'flashvar_logo_hide='.intval($flashvar_logo_hide).', ';
	$sql .= 'flashvar_duration='.intval($flashvar_duration).', ';
	$sql .= 'flashvar_start='.intval($flashvar_start).', ';
	$sql .= 'flashvar_item='.intval($flashvar_item).', ';
	$sql .= 'flashvar_playlist_size='.intval($flashvar_playlist_size).', ';
	$sql .= 'flashvar_logo_margin='.intval($flashvar_logo_margin).', ';
	$sql .= 'flashvar_logo_timeout='.intval($flashvar_logo_timeout).', ';
	$sql .= 'flashvar_logo_over='.floatval($flashvar_logo_over).', ';
	$sql .= 'flashvar_logo_out='.floatval($flashvar_logo_out).', ';
	$sql .= 'flashvar_playlistfile='.$this->quote($flashvar_playlistfile).', ';
	$sql .= 'flashvar_mediaid='.$this->quote($flashvar_mediaid).', ';
	$sql .= 'flashvar_provider='.$this->quote($flashvar_provider).', ';
	$sql .= 'flashvar_streamer='.$this->quote($flashvar_streamer).', ';
	$sql .= 'flashvar_netstreambasepath='.$this->quote($flashvar_netstreambasepath).', ';
	$sql .= 'flashvar_skin='.$this->quote($flashvar_skin).', ';
	$sql .= 'flashvar_player_repeat='.$this->quote($flashvar_player_repeat).', ';
	$sql .= 'flashvar_playerready='.$this->quote($flashvar_playerready).', ';
	$sql .= 'flashvar_plugins='.$this->quote($flashvar_plugins).', ';
	$sql .= 'flashvar_stretching='.$this->quote($flashvar_stretching).', ';

	$sql .= 'flashvar_controlbar_position='.$this->quote($flashvar_controlbar_position).', ';
	$sql .= 'flashvar_playlist_position='.$this->quote($flashvar_playlist_position).', ';
	$sql .= 'flashvar_logo_file='.$this->quote($flashvar_logo_file).', ';
	$sql .= 'flashvar_logo_link='.$this->quote($flashvar_logo_link).', ';
	$sql .= 'flashvar_logo_linktarget='.$this->quote($flashvar_logo_linktarget).', ';
	$sql .= 'flashvar_logo_position='.$this->quote($flashvar_logo_position).' ';

	$sql .= 'WHERE flashvar_id='.intval($flashvar_id);

	return $this->query( $sql, 0, 0, $force );
}

//---------------------------------------------------------
// get rows
//---------------------------------------------------------
function get_rows_by_itemid( $item_id, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->_table;
	$sql .= ' WHERE flashvar_item_id = '.intval($item_id);
	$sql .= ' ORDER BY flashvar_id' ;
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

//---------------------------------------------------------
// option
//---------------------------------------------------------
function get_autostart_options()
{
	$arr = array(
		'0'  => 'false' ,
		'1'  => 'true' ,
		'2'  => 'default' ,
	);
	return $arr;
}

function get_link_type_options( $flag_down=false )
{
	$arr = array(
		'0'  => _WEBPHOTO_FLASHVAR_LINK_TYPE_NONE ,
		'1'  => _WEBPHOTO_FLASHVAR_LINK_TYPE_SITE ,
		'2'  => _WEBPHOTO_FLASHVAR_LINK_TYPE_PAGE ,
	);
	if ( $flag_down ) {
		$arr['3'] = _WEBPHOTO_FLASHVAR_LINK_TYPE_FILE ;
	}
	return $arr;
}

function get_linktarget_options()
{
	$arr = array(
		'_self'  => _WEBPHOTO_FLASHVAR_LINKTREGET_SELF ,
		'_blank' => _WEBPHOTO_FLASHVAR_LINKTREGET_BLANK ,
	);
	return $arr;
}

function get_overstretch_options()
{
	$arr = array(
		'false' => _WEBPHOTO_FLASHVAR_OVERSTRETCH_FALSE ,
		'fit'   => _WEBPHOTO_FLASHVAR_OVERSTRETCH_FIT ,
		'true'  => _WEBPHOTO_FLASHVAR_OVERSTRETCH_TRUE ,
		'none'  => _WEBPHOTO_FLASHVAR_OVERSTRETCH_NONE ,
	);
	return $arr;
}

function get_transition_options()
{
	$arr = array(
		'0'        => _WEBPHOTO_FLASHVAR_TRANSITION_OFF ,
		'fade'     => _WEBPHOTO_FLASHVAR_TRANSITION_FADE ,
		'slowfade' => _WEBPHOTO_FLASHVAR_TRANSITION_SLOWFADE ,
		'bgfade'   => _WEBPHOTO_FLASHVAR_TRANSITION_BGFADE ,
		'blocks'   => _WEBPHOTO_FLASHVAR_TRANSITION_BLOCKS ,
		'bubbles'  => _WEBPHOTO_FLASHVAR_TRANSITION_BUBBLES ,
		'circles'  => _WEBPHOTO_FLASHVAR_TRANSITION_CIRCLES ,
		'fluids'   => _WEBPHOTO_FLASHVAR_TRANSITION_FLUIDS ,
		'lines'    => _WEBPHOTO_FLASHVAR_TRANSITION_LINES ,
		'random'   => _WEBPHOTO_FLASHVAR_TRANSITION_RANDOM ,	
	);
	return $arr;
}

function get_player_repeat_options()
{
	$arr = array(
		'none'   => _WEBPHOTO_FLASHVAR_PLAYER_REPEAT_NONE ,
		'list'   => _WEBPHOTO_FLASHVAR_PLAYER_REPEAT_LIST ,
		'always' => _WEBPHOTO_FLASHVAR_PLAYER_REPEAT_ALWAYS ,
		'single' => _WEBPHOTO_FLASHVAR_PLAYER_REPEAT_SINGLE ,
	);
	return $arr;
}

function get_stretching_options()
{
	$arr = array(
		'none'     => _WEBPHOTO_FLASHVAR_STRETCHING_NONE ,
		'exactfit' => _WEBPHOTO_FLASHVAR_STRETCHING_EXACTFIT ,
		'uniform'  => _WEBPHOTO_FLASHVAR_STRETCHING_UNIFORM ,
		'fill'     => _WEBPHOTO_FLASHVAR_STRETCHING_FILL ,
	);
	return $arr;
}

function get_controlbar_position_options()
{
	$arr = array(
		'bottom' => _WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION_BOTTOM,
		'top'    => _WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION_TOP,
		'over'   => _WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION_OVER,
		'none'   => _WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION_NONE,
	);
	return $arr;
}

function get_playlist_position_options()
{
	$arr = array(
		'bottom' => _WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_BOTTOM,
		'top'    => _WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_TOP,
		'right'  => _WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_RIGHT,
		'left'   => _WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_LEFT,
		'over'   => _WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_OVER,
		'none'   => _WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_NONE,
	);
	return $arr;
}

function get_logo_position_options()
{
	$arr = array(
		'bottom-left'  => _WEBPHOTO_FLASHVAR_LOGO_POSITION_BOTTOM_LEFT,
		'bottom-right' => _WEBPHOTO_FLASHVAR_LOGO_POSITION_BOTTOM_RIGHT,
		'top-left'     => _WEBPHOTO_FLASHVAR_LOGO_POSITION_TOP_LEFT,
		'top-right'    => _WEBPHOTO_FLASHVAR_LOGO_POSITION_TOP_RIGHT,
	);
	return $arr;
}
// --- class end ---
}

?>