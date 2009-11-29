<?php
// $Id: flash_player.php,v 1.11 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// webphoto_base_ini
// auto adjust
// 2009-10-25 K.OHWADA
// _C_WEBPHOTO_FILE_KIND_MP3
// 2009-02-20 K.OHWADA
// item_page_width
// 2009-01-25 K.OHWADA
// build_movie_by_item_row()
// 2009-01-10 K.OHWADA
// is_color_style()
// BUG : not define set_variable_buffer_color()
// 2008-11-29 K.OHWADA
// BUG: not show external swf 
// 2008-11-16 K.OHWADA
// load_movie() -> build_movie()
// build_embedlink()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_flash_player
//=========================================================

//---------------------------------------------------------
// http://blog.deconcept.com/swfobject/
// http://www.jeroenwijering.com/?item=JW_FLV_Media_Player
// http://www.jeroenwijering.com/?item=JW_Image_Rotator
// http://code.jeroenwijering.com/trac/wiki/Playlists3
// http://code.jeroenwijering.com/trac/wiki/Flashvars3
//---------------------------------------------------------

class webphoto_flash_player extends webphoto_base_ini
{
	var $_config_class;
	var $_item_handler;
	var $_file_handler;
	var $_player_handler;
	var $_flashvar_handler;

	var $_cfg_use_callback;

// result
	var $_report = null;

// local
	var $_item_row     = null;
	var $_flashvar_row = null;
	var $_item_id      = 0 ;
	var $_kind         = null ;

	var $_flashplayer = null;
	var $_screencolor = null;
	var $_width       = 0;
	var $_height      = 0;

	var $_PLAYLISTS_DIR ;
	var $_PLAYLISTS_URL ;
	var $_LOGOS_DIR ;
	var $_LOGOS_URL ;

	var $_CALLBACK_URL = null;

	var $_CODEBASE = 'http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0';
	var $_CLASSID  = 'clsid:d27cdb6e-ae6d-11cf-96b8-444553540000';

// show black if change the font size in web brawser
	var $_SCREENCOLOR_DEFAULT = '#ffffff' ;	// white

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_flash_player( $dirname , $trust_dirname )
{
	$this->webphoto_base_ini( $dirname, $trust_dirname );

	$this->_xoops_class      =& webphoto_xoops_base::getInstance();
	$this->_utility_class    =& webphoto_lib_utility::getInstance();
	$this->_config_class     =& webphoto_config::getInstance( $dirname );

	$this->_item_handler     
		=& webphoto_item_handler::getInstance( $dirname , $trust_dirname );
	$this->_file_handler     
		=& webphoto_file_handler::getInstance( $dirname , $trust_dirname  );
	$this->_player_handler   
		=& webphoto_player_handler::getInstance( $dirname , $trust_dirname  );
	$this->_flashvar_handler 
		=& webphoto_flashvar_handler::getInstance( $dirname , $trust_dirname  );

	$uploads_path             = $this->_config_class->get_uploads_path();
	$this->_cfg_use_callback  = $this->_config_class->get_by_name( 'use_callback' );

	$playlists_path = $uploads_path.'/playlists' ;
	$logos_path     = $uploads_path.'/logos' ;

	$this->_PLAYLISTS_DIR = XOOPS_ROOT_PATH . $playlists_path ;
	$this->_LOGOS_DIR     = XOOPS_ROOT_PATH . $logos_path ;
	$this->_PLAYLISTS_URL = XOOPS_URL       . $playlists_path ;
	$this->_LOGOS_URL     = XOOPS_URL       . $logos_path ;

	$this->_CALLBACK_URL = $this->_MODULE_URL.'/callback.php';
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_flash_player( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function build_movie_by_item_row( $item_row, $player_row=null )
{
	$param = $this->build_movie_param_by_item_row( $item_row, $player_row );
	return $this->build_movie( $param );
}

function build_code_embed_by_item_row( $item_row )
{
	$param = $this->build_movie_param_by_item_row( $item_row );
	return $this->build_code_embed( $param );
}

function build_movie( $param )
{
	if ( ! is_array($param) ) {
		return false ;
	}

	$item_row   = $param['item_row']; 
	$player_row = $param['player_row']; 

	if ( ! is_array($item_row) ) {
		return false ;
	}

	$param_movie = $param ;
	$param_movie['player_style'] = $player_row['player_style'] ;

	return $this->build_movie_js( $param_movie );
}

function build_mplay( $param )
{
	if ( ! is_array($param) ) {
		return false ;
	}

	$item_row     = $param['item_row']; 
	$flashvar_row = $param['flashvar_row'];

	if ( ! is_array($item_row) ) {
		return false ;
	}

	$item_id  = $item_row['item_id'] ;
	$enablejs = $flashvar_row['flashvar_enablejs'];

	if ( $enablejs != 0 ) {
		return $this->build_mplay_js( $item_id );
	}

	return null ;
}

function build_code_embed( $param )
{
	$embed   = null ;
	$embedjs = null ;

	if ( ! is_array($param) ) {
		return array( $embed, $embedjs );
	}

	$item_row       = $param['item_row']; 
	$cont_row       = $param['cont_row']; 
	$flash_row      = $param['flash_row']; 
	$swf_row        = $param['swf_row']; 
	$mp3_row        = $param['mp3_row']; 
	$player_row     = $param['player_row']; 
	$flashvar_row   = $param['flashvar_row'];

	if ( ! is_array($item_row) ) {
		return array( $embed, $embedjs );
	}

	$item_id     = $item_row['item_id'];
	$player_id   = $item_row['item_player_id'];
	$flashvar_id = $item_row['item_flashvar_id'];

	$config_url = $this->_MODULE_URL.'/index.php?fct=flash_config&item_id='.$item_id;

	list( $player_sel, $flashplayer ) = 
		$this->get_player( $item_row, $cont_row, $swf_row, $mp3_row );

	if ( $player_sel == 0 ) {
		return false ;
	}

	list( $width, $height ) = 
		$this->get_width_height( $item_row, $flashvar_row, $flash_row, $player_row ) ;

	$embed   = $this->build_embed(   $item_id, $flashplayer, $width, $height, $config_url );
	$embedjs = $this->build_embedjs( $item_id, $flashplayer, $width, $height, $config_url );

	return array( $embed, $embedjs );
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function build_movie_param_by_item_row( $item_row, $player_row=null )
{
	if ( ! is_array($item_row) ) {
		return false ;
	}

	$flashvar_row = null;

	$flashvar_id    = $item_row['item_flashvar_id'] ;
	$player_id      = $item_row['item_player_id'] ;
	$playlist_cache = $item_row['item_playlist_cache'] ;

	$cont_row     = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_CONT ) ; 
	$thumb_row    = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_THUMB ) ; 
	$middle_row   = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_MIDDLE ) ; 
	$flash_row    = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH ) ;
	$swf_row      = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_SWF ) ;
	$mp3_row      = $this->get_cached_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_MP3 ) ;

	if ( $flashvar_id > 0 ) {
		$flashvar_row = $this->_flashvar_handler->get_cached_row_by_id( $flashvar_id ) ;
	}

// default if not specify
	if ( !is_array($flashvar_row) ) {
		$flashvar_row = $this->_flashvar_handler->create() ;
	}

// default if not specify
	if ( !is_array($player_row) || !$player_row['player_id'] ) {
		if ( $player_id > 0 ) {
			$player_row = $this->_player_handler->get_cached_row_by_id( $player_id ) ; 
		}
		if ( !is_array($player_row) ) {
			$player_row = $this->_player_handler->create();
		}
	}

	$param = array(
		'item_row'       => $item_row , 
		'cont_row'       => $cont_row , 
		'thumb_row'      => $thumb_row , 
		'middle_row'     => $middle_row , 
		'flash_row'      => $flash_row ,
		'swf_row'        => $swf_row ,
		'mp3_row'        => $mp3_row ,
		'flashvar_row'   => $flashvar_row , 
		'player_row'     => $player_row , 
		'playlist_cache' => $playlist_cache ,
	);
	
	return $param ;
}

function build_movie_js( $param )
{
	$ret = $this->set_variables_in_buffer( $param );
	if ( !$ret ) {
		return false;
	}

	$item_row = $param['item_row']; 
	$item_id  = $item_row['item_id'];
	$div_id   = 'webphoto_play'.$item_id;
	$swf_id   = 'webphoto_swf'.$item_id;

	$movie  = $this->build_script_swfobject( $div_id ) ;
	$movie .= $this->build_script_begin();
	$movie .= $this->build_var_swfobject( $this->_flashplayer, $swf_id, $this->_width, $this->_height );

	$movie .= $this->build_add_parame( 'allowfullscreen', 'true' );

	if ( $this->_screencolor ) {
		$movie .= $this->build_add_parame( 'bgcolor', $this->_screencolor );
	}

	$movie .= $this->render_variable_buffers() ;
	$movie .= $this->build_write( $div_id );
	$movie .= $this->build_script_end();

	return $movie ;
}

function set_variables_in_buffer( $param )
{
	$item_row       = $param['item_row']; 
	$cont_row       = $param['cont_row']; 
	$thumb_row      = $param['thumb_row']; 
	$middle_row     = $param['middle_row']; 
	$flash_row      = $param['flash_row']; 
	$swf_row        = $param['swf_row']; 
	$mp3_row        = $param['mp3_row']; 
	$player_row     = $param['player_row']; 
	$flashvar_row   = $param['flashvar_row'];
	$playlist_cache = $param['playlist_cache'] ; 

	$item_id       = $item_row['item_id'];
	$item_title    = $item_row['item_title'];
	$ext           = $item_row['item_ext'];
	$kind          = $item_row['item_kind'];
	$siteurl       = $item_row['item_siteurl'];
	$showinfo      = $item_row['item_showinfo'];
	$perm_down     = $item_row['item_perm_down'];
	$external_url  = $item_row['item_external_url'];
	$embed_type    = $item_row['item_embed_type'];
	$embed_src     = $item_row['item_embed_src'];

	$width         = $player_row['player_width'];
	$height        = $player_row['player_height'];
	$displaywidth  = $player_row['player_displaywidth'];
	$displayheight = $player_row['player_displayheight'];
	$player_title       = $player_row['player_title'];
	$player_style       = $player_row['player_style'] ; 
	$player_screencolor = $player_row['player_screencolor'];
	$player_backcolor   = $player_row['player_backcolor'];
	$player_frontcolor  = $player_row['player_frontcolor'];
	$player_lightcolor  = $player_row['player_lightcolor'];

	$image_show        = $flashvar_row['flashvar_image_show'];
	$searchbar         = $flashvar_row['flashvar_searchbar'];
	$showeq            = $flashvar_row['flashvar_showeq'];
	$showicons         = $flashvar_row['flashvar_showicons'];
	$shownavigation    = $flashvar_row['flashvar_shownavigation'];
	$showstop          = $flashvar_row['flashvar_showstop'];
	$showdigits        = $flashvar_row['flashvar_showdigits'];
	$showdownload      = $flashvar_row['flashvar_showdownload'];
	$usefullscreen     = $flashvar_row['flashvar_usefullscreen'];
	$autoscroll        = $flashvar_row['flashvar_autoscroll'];
	$thumbsinplaylist  = $flashvar_row['flashvar_thumbsinplaylist'];
	$autostart         = $flashvar_row['flashvar_autostart'];
	$repeat            = $flashvar_row['flashvar_repeat'];
	$shuffle           = $flashvar_row['flashvar_shuffle'];
	$smoothing         = $flashvar_row['flashvar_smoothing'];
	$enablejs          = $flashvar_row['flashvar_enablejs'];
	$linkfromdisplay   = $flashvar_row['flashvar_linkfromdisplay'];
	$link_type         = $flashvar_row['flashvar_link_type'];
	$type              = $flashvar_row['flashvar_type'];
	$logo              = $flashvar_row['flashvar_logo'];
	$link              = $flashvar_row['flashvar_link'];
	$audio             = $flashvar_row['flashvar_audio'];
	$captions          = $flashvar_row['flashvar_captions'];
	$fallback          = $flashvar_row['flashvar_fallback'];
	$javascriptid      = $flashvar_row['flashvar_javascriptid'];
	$recommendations   = $flashvar_row['flashvar_recommendations'];
	$searchlink        = $flashvar_row['flashvar_searchlink'];
	$streamscript      = $flashvar_row['flashvar_streamscript'];
	$bufferlength      = $flashvar_row['flashvar_bufferlength'];
	$rotatetime        = $flashvar_row['flashvar_rotatetime'];
	$volume            = $flashvar_row['flashvar_volume'];
	$linktarget        = $flashvar_row['flashvar_linktarget'];
	$overstretch       = $flashvar_row['flashvar_overstretch'];
	$transition        = $flashvar_row['flashvar_transition'];
	$enablejs          = $flashvar_row['flashvar_enablejs'];
	$flashvar_width         = $flashvar_row['flashvar_width'];
	$flashvar_height        = $flashvar_row['flashvar_height'];
	$flashvar_displaywidth  = $flashvar_row['flashvar_displaywidth'];
	$flashvar_displayheight = $flashvar_row['flashvar_displayheight'];
	$flashvar_screencolor   = $flashvar_row['flashvar_screencolor'];
	$flashvar_backcolor     = $flashvar_row['flashvar_backcolor'];
	$flashvar_frontcolor    = $flashvar_row['flashvar_frontcolor'];
	$flashvar_lightcolor    = $flashvar_row['flashvar_lightcolor'];

	$file_cont_url  = $this->get_file_url( $cont_row ) ;
	$file_flash_url = $this->get_file_url( $flash_row ) ;
	$file_swf_url   = $this->get_file_url( $swf_row ) ;
	$file_mp3_url   = $this->get_file_url( $mp3_row ) ;

// overwrite by flashvar
	list( $width, $height ) = 
		$this->get_width_height( $item_row, $flashvar_row, $flash_row, $player_row ) ;

	if (( $flashvar_displaywidth > 0 )&&( $flashvar_displayheight > 0 )) {
		$displaywidth   = $flashvar_displaywidth;
		$displayheight  = $flashvar_displayheight;
	}

// color
	$screencolor = $this->_SCREENCOLOR_DEFAULT ;
	$backcolor   = null ;
	$frontcolor  = null ;
	$lightcolor  = null ;

	if ( $this->is_color_style( $player_style ) ) {
		$screencolor = $player_screencolor ;
		$backcolor   = $player_backcolor  ;
		$frontcolor  = $player_frontcolor ;
		$lightcolor  = $player_lightcolor ;
	}

	if ( $flashvar_screencolor ) {
		$screencolor = $flashvar_screencolor ;
	}
	if ( $flashvar_backcolor ) {
		$backcolor = $flashvar_backcolor ;
	}
	if ( $flashvar_frontcolor ) {
		$frontcolor = $flashvar_frontcolor ;
	}
	if ( $flashvar_lightcolor ) {
		$lightcolor = $flashvar_lightcolor ;
	}

	$src_url       = null ;
	$movie_file    = null ;
	$playlist_url  = null ;
	$playlist_path = null ;
	$flag_file     = false ;
	$flag_title    = false ;
	$flag_type     = false ;
	$flag_image    = false ;
	$is_swfobject    = false ;
	$is_mediaplayer  = false ;
	$is_imagerotator = false ;

	$this->_item_row     = $item_row ;
	$this->_flashvar_row = $flashvar_row ;
	$this->_item_id      = $item_id;
	$this->_kind         = $kind ;
	$this->_player_style = $player_style ;

	list( $player_sel, $flashplayer ) =
		$this->get_player( $item_row, $cont_row, $swf_row, $mp3_row ) ;

	switch ( $player_sel )
	{
		case _C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT :
			$is_swfobject = true;
			$flag_title   = true ;
			$flag_type    = true ;
			break;

		case _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER :
			$is_mediaplayer = true;
			$flag_file      = true;
			$flag_title     = true ;
			$flag_type      = true ;
			$flag_image     = true ;
			break;

		case _C_WEBPHOTO_DISPLAYTYPE_IMAGEROTATOR :
			$is_imagerotator = true;
			$flag_file       = true;
			break;

		default;
			if ( $this->_is_module_admin ) {
				echo "NOT flash player type <br />\n";
			}
			return false;
	}

	$this->_flashplayer = $flashplayer;
	$this->_width       = $width;
	$this->_height      = $height;
	$this->_screencolor = $screencolor;

// flash video
	if ( $file_flash_url ) {
		$src_url   = $file_flash_url;
		$flag_type = false ;

// flash swf
	} elseif ( $file_swf_url ) {
		$src_url   = $file_swf_url;
		$flag_type = false ;

// mp3
	} elseif ( $file_mp3_url ) {
		$src_url   = $file_mp3_url;
		$flag_type = false ;

// external
	} elseif ( $external_url ) {
		$src_url = $external_url ;

// others
	} elseif ( $file_cont_url ) {
		$src_url = $file_cont_url ;
	}

	if ( $playlist_cache ) {
		$playlist_url  = $this->_PLAYLISTS_URL .'/'. $playlist_cache ;
		$playlist_path = $this->_PLAYLISTS_DIR .'/'. $playlist_cache ;
	}

// playlist
	if ( $this->is_playlist_kind() && $playlist_path && file_exists($playlist_path) ) {
		$movie_file = $playlist_url ;
		$flag_image = false ;

// others
	} else {
		$movie_file = $src_url ;
	}

	$movie_image = $this->get_movie_image( $thumb_row, $middle_row ) ; 
	$flag_down   = $this->check_perm_down( $item_row );

// basics
	$this->set_variable_buffer( 'width',  $width );
	$this->set_variable_buffer( 'height', $height );

	if ( $flag_file && $movie_file ) {
		$this->set_variable_buffer( 'file', $movie_file, true );
	}
	
	if ( ( $image_show == 1 ) && $flag_image && $movie_image ) {
		$this->set_variable_buffer( 'image', $movie_image, true );
	}

	$this->set_variable_buffer( 'id',     $item_id );

	if ( $searchbar == 1 ) {
		$this->set_variable_buffer( 'searchbar', 'true' );
	}

// colors
	if ( $screencolor ) {
		$this->set_variable_buffer_color( 'screencolor', $screencolor );
	}
	if ( $backcolor ) {
		$this->set_variable_buffer_color( 'backcolor',   $backcolor );
	}
	if ( $frontcolor ) {
		$this->set_variable_buffer_color( 'frontcolor',  $frontcolor );
	}
	if ( $lightcolor ) {
		$this->set_variable_buffer_color( 'lightcolor',  $lightcolor );
	}

// Display appearance 
	$movie_logo = $this->get_movie_logo( $logo );
	if ( $movie_logo ) {
		$this->set_variable_buffer( 'logo', $movie_logo, true );
	}

	if ( $overstretch && ( $overstretch != _C_WEBPHOTO_FLASHVAR_OVERSTRETCH_DEFAULT ) ) {
		$this->set_variable_buffer( 'overstretch', $overstretch );
	}
	if ( $showeq == 1 ) {  
		$this->set_variable_buffer( 'showeq', 'true' );
	}
	if ( $showicons == 0 ) {  
		$this->set_variable_buffer( 'showicons', 'false' );
	}
	if ( $transition && ( $transition != _C_WEBPHOTO_FLASHVAR_TRANSITION_DEFAULT ) ) {
		$this->set_variable_buffer( 'transition', $transition );
	}

// Controlbar appearance
	if ( $shownavigation == 0 ) { 
		$this->set_variable_buffer( 'shownavigation', 'false' ); 
	}
	if ( $showstop == 1 ) { 
		$this->set_variable_buffer( 'showstop', 'true' );
	}
	if ( $showdigits == 0 ) {
		$this->set_variable_buffer( 'showdigits', 'false' );
	}
	if ( $usefullscreen == 0 ) {   
		$this->set_variable_buffer( 'usefullscreen', 'false' );
	}

// Playlist appearance
	if ( $autoscroll == 1 ) {        
		$this->set_variable_buffer( 'autoscroll', 'true' );
	}
	if ( $displaywidth > 0 ) {
		$this->set_variable_buffer( 'displaywidth',  $displaywidth );
	}
	if ( $displayheight > 0 ) {
		$this->set_variable_buffer( 'displayheight', $displayheight );
	}
	if ( $thumbsinplaylist == 0 ) {  
		$this->set_variable_buffer( 'thumbsinplaylist', 'false' );
	}

// Playback behaviour
	if ( $audio != '' ) {  
		$this->set_variable_buffer( 'audio', $audio, true );
	}

	if ( $autostart != _C_WEBPHOTO_FLASHVAR_AUTOSTART_DEFAULT ) {
		$this->set_variable_buffer( 'autostart', 
			$this->get_movie_autostart( $autostart ) );
	}

	if ( $bufferlength && ( $bufferlength != _C_WEBPHOTO_FLASHVAR_BUFFERLENGTH_DEFAULT ) ) {
		$this->set_variable_buffer( 'bufferlength',  $bufferlength );
	}
	if( $captions != '' ) {
		$this->set_variable_buffer( 'captions', $captions, true );
	} 
	if( $fallback != '' ) {
		$this->set_variable_buffer( 'fallback', $fallback, true );
	} 
	if ( $repeat == 1 ) {  
		$this->set_variable_buffer( 'repeat', 'true' );
	}
	if ( $rotatetime && ( $rotatetime != _C_WEBPHOTO_FLASHVAR_ROTATETIME_DEFAULT ) ) {
		$this->set_variable_buffer( 'rotatetime', $rotatetime );
	}
	if ($shuffle == 1) {  
		$this->set_variable_buffer( 'shuffle', 'true' );
	}
	if ( $smoothing == 0 ) {  
		$this->set_variable_buffer( 'smoothing', 'false' );
	}
	if ( $volume && ($volume != _C_WEBPHOTO_FLASHVAR_VOLUME_DEFAULT ) ) {  
		$this->set_variable_buffer( 'volume', $volume );
	}

// External communication
	if ( $is_mediaplayer && $this->_cfg_use_callback ) {
		$this->set_variable_buffer( 'callback', $this->_CALLBACK_URL, true );
	}

// for futrue
//	if ( $enablejs == 1 ) {          
//		$this->set_variable_buffer( 'enablejs', 'true' );   
//		$this->set_variable_buffer( 'javascriptid', 'play'.$item_id );
//	}

	$movie_link = $this->get_movie_link( $src_url ) ;
	if ( $movie_link ) {
		$this->set_variable_buffer( 'link', $movie_link, true );

		if ( $showdownload == 1 ) {
			$this->set_variable_buffer( 'showdownload', 'true' );
		}
		if ( $linkfromdisplay == 1 ) { 
			$this->set_variable_buffer( 'linkfromdisplay', 'true' );
		}
		if ( $linktarget && ( $linktarget != _C_WEBPHOTO_FLASHVAR_LINKTARGET_DEFAULT ) ) {
			$this->set_variable_buffer( 'linktarget', $linktarget );
		}
	}

	if ( $recommendations != '' ) {
		$this->set_variable_buffer( 'recommendations', $recommendations );
	}
	if ( $searchlink != '' ) {
		$this->set_variable_buffer( 'searchlink', $searchlink );
	}
	if ( $streamscript != '' ) {
		$this->set_variable_buffer( 'streamscript', $streamscript );
	}
	if ( $flag_type && $this->check_type( $movie_file, $ext ) && $ext ) {
		$this->set_variable_buffer( 'type', $ext ); 
	}
	if ( $flag_title && $item_title ) {
		$this->set_variable_buffer( 'title', $item_title );
	}

	return true;
}

function set_variable_buffer( $name, $value, $flag_urlencode=false )
{
	$this->_variable_buffers[ $name ] = array( $value, $flag_urlencode ) ;
}

function set_variable_buffer_color( $name, $value )
{
	$this->_variable_buffers[ $name ] = array( $this->convert_color( $value ), false ) ;
}

function get_variable_buffers()
{
	return $this->_variable_buffers ;
}

function render_variable_buffers()
{
	$str = '';
	foreach ( $this->_variable_buffers as $k => $v ) {
		if ( $v[1] ) {
			$val = urlencode( $v[0] );
		} else {
			$val = $v[0];
		}
		$str .= $this->build_add_variable( $k, $val );
	}
	return $str;
}

//---------------------------------------------------------
// embed
//---------------------------------------------------------
function build_embed( $item_id, $flashplayer, $width, $height, $config )
{
	$flashvars = 'config='. urlencode($config) ;

	$str  = '<object codebase="'. $this->_CODEBASE .'" width="'.$width.'" height="'.$height.'" classid="'. $this->_CLASSID .'">';
	$str .= '<param name="movie" value="'.$flashplayer.'" ></param>';
	$str .= '<param name="flashvars" value="'.$flashvars.'" ></param>';
	$str .= '<embed src="'.$flashplayer.'" width="'.$width.'" height="'.$height.'" flashvars="'.$flashvars.'" type="application/x-shockwave-flash" ></embed>';
	$str .= '</object>';
	return $str;
}

function build_embedjs( $item_id, $flashplayer, $width, $height, $config )
{
	$this->_item_id = $item_id;

	$div_id = 'play'.$item_id;
	$swf_id = 'swf'.$item_id;

	$str  = $this->build_script_swfobject( $div_id );
	$str .= $this->build_script_begin();
	$str .= $this->build_var_swfobject( $flashplayer, $swf_id, $width, $height );
	$str .= $this->build_add_parame( 'allowfullscreen', 'true' );
	$str .= $this->build_add_variable( 'config', urlencode($config) );
	$str .= $this->build_write( $div_id );
	$str .= $this->build_script_end();

// remove newline code
	$ret = str_replace( "\n", '', $str );
	return $ret;
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function get_file_url( $file_row )
{
	$url = null ;
	if ( is_array($file_row) ) {
		$url    = $file_row['file_url'] ;
		$path   = $file_row['file_path'] ;
		if ( $path ) {
			$url = XOOPS_URL .'/'. $path ;
		}
	}
	return $url ;
}

// BUG: not show external swf 
function get_player( $item_row, $cont_row, $swf_row, $mp3_row )
{
	$sel    = 0 ;
	$player = null;
	$file   = null ;

	$displaytype   = $item_row['item_displaytype'];
	$external_url  = $item_row['item_external_url'];
	$file_cont_url = $this->get_file_url( $cont_row ) ;
	$file_swf_url  = $this->get_file_url( $swf_row ) ;
	$file_mp3_url  = $this->get_file_url( $mp3_row ) ;

	if (  $external_url ) {
		$file = $external_url ;
	} elseif (  $file_swf_url ) {
		$file = $file_swf_url ;
	} elseif (  $file_mp3_url ) {
		$file = $file_mp3_url ;
	} elseif ( $file_cont_url ) {
		$file = $file_cont_url ;
	}

	switch ( $displaytype )
	{
		case _C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT :
			$sel    = $displaytype ;
			$player = $file ;
			break;

		case  _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER :
			$sel    = $displaytype ;
			$player = $this->_MODULE_URL.'/libs/mediaplayer.swf';
			break;

		case _C_WEBPHOTO_DISPLAYTYPE_IMAGEROTATOR :
			$sel    = $displaytype ;
			$player = $this->_MODULE_URL.'/libs/imagerotator.swf';
			break;
	}

	return array( $sel, $player );
}

function get_movie_image( $thumb_row, $middle_row )
{
	$image = null ;

// middle
	if ( is_array($middle_row) && $middle_row['file_url'] ) {
		$image = $middle_row['file_url'] ;

// thumb
	} elseif ( is_array($thumb_row) && $thumb_row['file_url'] ) {
		$image = $thumb_row['file_url'] ;
	}

	return $image ;
}

function build_script_swfobject( $div_id )
{
	$str  = '<script type="text/javascript" src="'.$this->_MODULE_URL.'/libs/swfobject.js">';
	$str .= '</script>'."\n";
	$str .= '<div id="'. $div_id .'">';
	$str .= '<a href="http://www.macromedia.com/go/getflashplayer">';
	$str .= 'Get the Flash Player</a> to see this player.';
	$str .= '</div>'."\n";
	return $str;
}

function build_script_begin()
{
	$str = '<script type="text/javascript"> '."\n";
	return $str;
}

function build_script_end()
{
	$str = '</script>'."\n";
	return $str;
}

function build_var_swfobject( $flashplayer, $swf_id, $width, $height )
{
	$str = 'var s'.$this->_item_id.' = new SWFObject("'.$flashplayer.'","'.$swf_id.'","'.$width.'","'.$height.'","'. _C_WEBPHOTO_FLASH_VERSION .'"); '."\n";
	return $str;
}

function build_add_parame( $name, $value )
{
	$str = 's'.$this->_item_id.'.addParam("'. $name .'","'. $value .'"); '."\n";
	return $str;
}

function build_write( $div_id )
{
	$str = 's'.$this->_item_id.'.write("'. $div_id .'"); '."\n";
	return $str;
}

function build_add_variable( $name, $value )
{
	$str = 's'.$this->_item_id.'.addVariable("'. $name .'","'. $value .'"); '."\n";
	return $str;
}

function build_add_variable_color( $name, $value )
{
	if ( $value ) {
		return $this->build_add_variable( $name,  $this->convert_color( $value ) );
	}
	return '' ;
}

function convert_color( $str )
{
	$ret= '0x'.str_replace ( '#', '', $str );
	return $ret ;
}

function is_color_style( $style )
{
	if ( $style == _C_WEBPHOTO_PLAYER_STYLE_PLAYER ) {
		return true;
	}
	if ( $style == _C_WEBPHOTO_PLAYER_STYLE_PAGE ) {
		return true;
	}
	return false;
}

function get_width_height( $item_row, $flashvar_row, $flash_row, $player_row )
{
	$item_width      = $item_row['item_page_width'];
	$item_height     = $item_row['item_page_height'];
	$flashvar_width  = $flashvar_row['flashvar_width'];
	$flashvar_height = $flashvar_row['flashvar_height'];
	$flash_width     = $flash_row['file_width'];
	$flash_height    = $flash_row['file_height'];
	$player_width    = $player_row['player_width'];
	$player_height   = $player_row['player_height'];

// item
	if (( $item_width > 0 )&&( $item_height > 0 )) {
		return array( $item_width, $item_height );
	}

// flashvar
	if (( $flashvar_width > 0 )&&( $flashvar_height > 0 )) {
		return array( $flashvar_width, $flashvar_height );
	}

// auto adjust
	if (( $flash_width > 0 )&&( $flash_height > 0 )) {
		return $this->_utility_class->adjust_image_size( 
			$flash_width, $flash_height, $player_width, $player_height );
	}

	return array( $player_width, $player_height );
}

function check_perm_down( $item_row )
{
	$showinfo      = $item_row['item_showinfo'];
	$perm_down     = $item_row['item_perm_down'];
	$showinfo_arr  = explode('|', $showinfo);
	$perm_down_arr = explode('|', $perm_down);

	if ( !is_array($showinfo_arr) ) {
		return false;
	}

	if ( !in_array( _C_WEBPHOTO_SHOWINFO_DOWNLOAD , $showinfo_arr ) ) {
		return false;
	}

// all perm
	if ( $perm_down == '*' ) {
		return true;

// in xoops_group
	} elseif ( is_array($perm_down_arr) &&
		( count( array_intersect( $this->_xoops_groups, $perm_down_arr ) ) > 0 ) ) {
		return true ;
	}

	return false;
}

function get_movie_link( $src_url )
{
	$item_id   = $this->_item_row['item_id'];
	$siteurl   = $this->_item_row['item_siteurl'];
	$link_type = $this->_flashvar_row['flashvar_link_type'];

	$link = null ;
	switch ( $link_type ) 
	{
		case _C_WEBPHOTO_FLASHVAR_LINK_TYPE_SITE :
			$link = $siteurl ;
			break;

		case _C_WEBPHOTO_FLASHVAR_LINK_TYPE_PAGE :
			$link = $this->_MODULE_URL.'/index.php?fct=photo&photo_id='.$item_id ;
			break;

		case _C_WEBPHOTO_FLASHVAR_LINK_TYPE_FILE :
			$link = $src_url ;
			break;
	}
	return $link;
}

function get_movie_autostart( $autostart )
{
	if ( $autostart == 0 ) {  
		$movie_autostart = 'false';
	} else { 
		$movie_autostart = 'true';
	}
	return $movie_autostart ;
}

function get_movie_logo( $logo )
{
	$logo_file = $this->_LOGOS_DIR .'/'. $logo ;
	$logo_url  = $this->_LOGOS_URL .'/'. $logo ;

	$movie_logo = null;
	if ( $logo && file_exists( $logo_file ) ) {          
		$movie_logo = $logo_url ;
	}
	return $movie_logo ;
}

function check_type( $file, $ext )
{
	if ( $this->is_type_kind() ) {
		$file_ext = $this->parse_ext( $file );
		if ( $file_ext != $ext ) {  
			return true ;
		}
	}
	return false;
}

function is_playlist_kind()
{
	switch ( $this->_kind )
	{
		case _C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED :
		case _C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR :
			return true;
			break;
	}
	return false;
}

function is_type_kind()
{
	switch ( $this->_kind )
	{
		case _C_WEBPHOTO_ITEM_KIND_GENERAL :
		case _C_WEBPHOTO_ITEM_KIND_IMAGE :
		case _C_WEBPHOTO_ITEM_KIND_VIDEO :
		case _C_WEBPHOTO_ITEM_KIND_AUDIO :
		case _C_WEBPHOTO_ITEM_KIND_OFFICE :
		case _C_WEBPHOTO_ITEM_KIND_IMAGE_OTHER :
		case _C_WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL :
		case _C_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE :
			return true ;
			break;
	}
	return false;
}

function is_player_style_mono()
{
	if ( $this->_player_style == _C_WEBPHOTO_PLAYER_STYLE_MONO ) {
		return true;
	}
	return false;
}

function get_report()
{
	return $this->_report;
}

function build_mplay_js( $item_id )
{

$str = '
<script type="text/javascript">
	var currentPosition;
	var currentVolume;
	var currentItem;
	function sendEvent(typ,prm) { 
		thisMovie("'. $item_id .'").sendEvent(typ,prm); 
	}
	function getUpdate(typ,pr1,pr2) {
		if(typ == "time") { currentPosition = pr1; }
		else if(typ == "volume") { currentVolume = pr1; }
		else if(typ == "item") { getItemData(pr1); }
		var id = document.getElementById(typ);
		id.innerHTML = typ+ ": "+Math.round(pr1);
		pr2 == undefined ? null: id.innerHTML += ", "+Math.round(pr2);
	}
	function loadFile(obj) { 
		thisMovie("'. $item_id .'").loadFile(obj); 
	}
	function addItem(obj,idx) { 
		thisMovie("'. $item_id .'").addItem(obj,idx); 
	}
	function removeItem(idx) { 
		thisMovie("'. $item_id .'").removeItem(idx); 
	}
	function getItemData(idx) {
		var obj = thisMovie("'. $item_id .'").itemData(idx);
		var nodes = "";
		for(var i in obj) { 
			nodes += "<li>"+i+": "+obj[i]+"</li>"; 
		}
		document.getElementById("data").innerHTML = nodes;
	}
	function thisMovie(movieId) {
		var movieName = "webphoto_play" + movieId ;
	    if(navigator.appName.indexOf("Microsoft") != -1) {
			return window[movieName];
		} else {
			return document[movieName];
		}
	}
</script>
';

	return $str;
}

//---------------------------------------------------------
// file handler
//---------------------------------------------------------
function get_cached_file_row_by_kind( $row, $kind )
{
	$file_id = $this->_item_handler->build_value_fileid_by_kind( $row, $kind );
	if ( $file_id > 0 ) {
		return $this->_file_handler->get_cached_row_by_id( $file_id );
	}
	return null;
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function parse_ext( $file )
{
	return $this->_utility_class->parse_ext( $file );
}

// --- class end ---
}

?>