<?php
// $Id: flash_player.php,v 1.1 2008/10/30 00:25:51 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

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

class webphoto_flash_player extends webphoto_lib_base
{
	var $_config_class;
	var $_item_handler;
	var $_file_handler;
	var $_player_handler;
	var $_flashvar_handler;

	var $_cfg_use_callback ;

// result
	var $_report = null;

// local
	var $_item_row     = null;
	var $_flashvar_row = null;
	var $_item_id      = 0 ;
	var $_kind         = null ;

	var $_PLAYLISTS_DIR ;
	var $_PLAYLISTS_URL ;
	var $_LOGOS_DIR ;
	var $_LOGOS_URL ;

	var $_CALLBACK_URL = null;

	var $_FLASH_VERSION        = _C_WEBPHOTO_FLASH_VERSION ;
	var $_BUFFERLENGTH_DEFAULT = _C_WEBPHOTO_FLASHVAR_BUFFERLENGTH_DEFAULT ;
	var $_ROTATETIME_DEFAULT   = _C_WEBPHOTO_FLASHVAR_ROTATETIME_DEFAULT ;
	var $_VOLUME_DEFAULT       = _C_WEBPHOTO_FLASHVAR_VOLUME_DEFAULT ;
	var $_LINKTARGET_DEFAULT   = _C_WEBPHOTO_FLASHVAR_LINKTARGET_DEFAULT ;
	var $_OVERSTRETCH_DEFAULT  = _C_WEBPHOTO_FLASHVAR_OVERSTRETCH_DEFAULT ;
	var $_TRANSITION_DEFAULT   = _C_WEBPHOTO_FLASHVAR_TRANSITION_DEFAULT ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_flash_player( $dirname, $trust_dirname )
{
	$this->webphoto_lib_base( $dirname, $trust_dirname );

	$this->_config_class     =& webphoto_config::getInstance( $dirname );
	$this->_item_handler     =& webphoto_item_handler::getInstance( $dirname );
	$this->_file_handler     =& webphoto_file_handler::getInstance( $dirname );
	$this->_player_handler   =& webphoto_player_handler::getInstance( $dirname );
	$this->_flashvar_handler =& webphoto_flashvar_handler::getInstance( $dirname );
	$this->_playlist_class   =& webphoto_playlist::getInstance( $dirname, $trust_dirname );

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

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_flash_player( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function load_movie( $item_id, $player_id, $player_style )
{
	$movie = null ;
	$mplay = null ;

	$item_row = $this->_item_handler->get_row_by_id( $item_id );
	if ( ! is_array($item_row) ) {
		return false;
	}

	$flashvar_id  = $item_row['item_flashvar_id'] ;

	if ( empty($player_id) ) {
		$player_id = $item_row['item_player_id'] ;
	}

	$file_cont_url  = $this->get_file_url( $item_row, _C_WEBPHOTO_FILE_KIND_CONT );
	$file_flash_url = $this->get_file_url( $item_row, _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH );
	$playlist_cache = $this->get_playlist_cache( $item_row );
	$movie_image    = $this->get_movie_image( $item_row );

	$player_row = $this->_player_handler->get_row_by_id( $player_id );
	if ( ! is_array($player_row) ) {
		$player_row = $this->_player_handler->create();
	}

	if ( empty($player_style) ) {
		$player_style = $player_row['player_style'] ;
	}

	$flashvar_row = $this->_flashvar_handler->get_row_by_id( $flashvar_id );
	if ( ! is_array($flashvar_row) ) {
		$flashvar_row = $this->_flashvar_handler->create();
	}

// VIEW HIT  Adds 1 if not submitter or admin.
	if ( $this->check_not_owner( $item_row['item_uid'] ) ) {
		$this->_item_handler->countup_views( $item_id, true );
	}

	$param = array(
		'file_cont_url'  => $file_cont_url , 
		'file_flash_url' => $file_flash_url ,
		'playlist_cache' => $playlist_cache ,
		'player_style'   => $player_style ,
		'movie_image'    => $movie_image , 
	);

// Make movie Link
	$movie = $this->build_movie_js( $item_row, $player_row, $flashvar_row, $param );

	if ( $flashvar_row['flashvar_enablejs'] != 0 ) {
		$mplay = $this->build_mplay_js( $item_id );
	}

	return array( $movie, $mplay );
}

function get_movie_image( $item_row )
{
	if ( ! is_array($item_row) ) {
		return false;
	}

	$movie_image     = null ;
	$file_thumb_url  = $this->get_file_url( $item_row, _C_WEBPHOTO_FILE_KIND_THUMB );
	$file_middle_url = $this->get_file_url( $item_row, _C_WEBPHOTO_FILE_KIND_MIDDLE );

	if ( $file_middle_url ) {
		$movie_image = $file_middle_url ;
	}
	if ( $file_thumb_url ) {
		$movie_image = $file_thumb_url ;
	}

	return $movie_image ;
}

function get_file_url( $item_row, $file_kind )
{
	if ( ! is_array($item_row) ) {
		return false;
	}

	$file_url = null ;
	$file_id  = $item_row['item_file_id_'.$file_kind ] ;

	if ( $file_id > 0 ) {
		$file_row = $this->_file_handler->get_row_by_id( $file_id );
		if ( is_array($file_row) ) {
			$file_url = $file_row['file_url'] ;
		}
	}
	
	return $file_url ;
}

function get_playlist_cache( $item_row )
{
	$item_id = $item_row['item_id'] ;
	$kind    = $item_row['item_kind'] ;
	$cache   = $item_row['item_playlist_cache'] ;
	$time    = $item_row['item_playlist_time'] ;

	$this->_report = null;

// Check PLAYLIST CACHE
	$check = $this->_playlist_class->check_expired( $cache, $time );
	if ( $check ) {
		return $cache ;
	}

	$ret = $this->_playlist_class->create_cache_by_item_row( $item_row );
	if ( $ret ) {
		$this->_report = $this->_playlist_class->get_report();

	} else {
		$this->set_error( $this->_playlist_class->get_errors() );
	}

	return $cache ;
}

function build_movie_js( $item_row, $player_row, $flashvar_row, $param )
{

	$item_id       = $item_row['item_id'];
	$item_title    = $item_row['item_title'];
	$ext           = $item_row['item_ext'];
	$kind          = $item_row['item_kind'];
	$siteurl       = $item_row['item_siteurl'];
	$showinfo      = $item_row['item_showinfo'];
	$perm_down     = $item_row['item_perm_down'];
	$displaytype   = $item_row['item_displaytype'];
	$external_url  = $item_row['item_external_url'];
	$embed_type    = $item_row['item_embed_type'];
	$embed_src     = $item_row['item_embed_src'];

	$player_title  = $player_row['player_title'];
	$screencolor   = $player_row['player_screencolor'];
	$backcolor     = $player_row['player_backcolor'];
	$frontcolor    = $player_row['player_frontcolor'];
	$lightcolor    = $player_row['player_lightcolor'];
	$width         = $player_row['player_width'];
	$height        = $player_row['player_height'];
	$displaywidth  = $player_row['player_displaywidth'];
	$displayheight = $player_row['player_displayheight'];

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

	$file_cont_url  = $param['file_cont_url'] ;  
	$file_flash_url = $param['file_flash_url'] ; 
	$playlist_cache = $param['playlist_cache'] ; 
	$player_style   = $param['player_style'] ; 
	$movie_image    = $param['movie_image'] ; 

// overwrite by flashvar
	if (( $flashvar_width > 0 )&&( $flashvar_height > 0 )) {
		$width   = $flashvar_width;
		$height  = $flashvar_height;
	}

	if (( $flashvar_displaywidth > 0 )&&( $flashvar_displayheight > 0 )) {
		$displaywidth   = $flashvar_displaywidth;
		$displayheight  = $flashvar_displayheight;
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

// Pick the Player - pick a Jeroen Wijering Flash script according to file type and transition 
	if ( ( $displaytype == _C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT ) && $file_cont_url ) {
		$is_swfobject = true;
		$flag_title   = true ;
		$flag_type    = true ;
		$flashplayer  = $file_cont_url ;

	} elseif ( $displaytype == _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ) {
		$is_mediaplayer = true;
		$flag_file   = true;
		$flag_title  = true ;
		$flag_type   = true ;
		$flag_image  = true ;
		$flashplayer = $this->_MODULE_URL.'/libs/mediaplayer.swf';

	} elseif ( $displaytype == _C_WEBPHOTO_DISPLAYTYPE_IMAGEROTATOR ) {
		$is_imagerotator = true;
		$flag_file   = true;
		$flashplayer = $this->_MODULE_URL.'/libs/imagerotator.swf';

	} else {
		echo "NOT flash player type <br />\n";
		return false;
	}

// flash video
	if ( $file_flash_url ) {
		$src_url   = $file_flash_url;
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

	$flag_down = $this->check_perm_down( $item_row );

// Make movie Link
	$div_id = 'webphoto_play'.$item_id;

	$movie  = '<script type="text/javascript" src="'.$this->_MODULE_URL.'/libs/swfobject.js">';
	$movie .= '</script>'."\n";
	$movie .= '<div id="'. $div_id .'">';
	$movie .= '<a href="http://www.macromedia.com/go/getflashplayer">';
	$movie .= 'Get the Flash Player</a> to see this player.';
	$movie .= '</div>'."\n";

	$movie .= '<script type="text/javascript">'."\n";
	$movie .= 'var s'.$item_id.' = new SWFObject("'. $flashplayer.'","'.$div_id.'","'.$width.'","'.$height.'","'. $this->_FLASH_VERSION .'"); '."\n";

	$movie .= $this->build_add_parame( 'allowfullscreen', 'true' );

	if ( $screencolor ) {
		$movie .= $this->build_add_parame( 'bgcolor', $screencolor );
	}

// basics
	$movie .= $this->build_add_variable( 'width',  $width );
	$movie .= $this->build_add_variable( 'height', $height );

	if ( $flag_file && $movie_file ) {
		$movie .= $this->build_add_variable( 'file', urlencode($movie_file) );
	}

	if ( ( $image_show == 1 ) && $flag_image && $movie_image ) {
		$movie .= $this->build_add_variable( 'image', urlencode($movie_image) );
	}

	$movie .= $this->build_add_variable( 'id',     $item_id );

	if ( $searchbar == 1 ) {
		$movie .= $this->build_add_variable( 'searchbar', 'true' );
	}

// colors
	if ( $screencolor ) {
		$movie .= $this->build_add_variable_color( 'screencolor', $screencolor );
	}
	if ( $backcolor ) {
		$movie .= $this->build_add_variable_color( 'backcolor',   $backcolor );
	}
	if ( $frontcolor ) {
		$movie .= $this->build_add_variable_color( 'frontcolor',  $frontcolor );
	}
	if ( $lightcolor ) {
		$movie .= $this->build_add_variable_color( 'lightcolor',  $lightcolor );
	}

// Display appearance 
	$movie_logo = $this->get_movie_logo( $logo );
	if ( $movie_logo ) {
		$movie .= $this->build_add_variable( 'logo', urlencode($movie_logo) );
	}

	if ( $overstretch && ( $overstretch != $this->_OVERSTRETCH_DEFAULT ) ) {
		$movie .= $this->build_add_variable( 'overstretch', $overstretch );
	}
	if ( $showeq == 1 ) {  
		$movie .= $this->build_add_variable( 'showeq', 'true' );
	}
	if ( $showicons == 0 ) {  
		$movie .= $this->build_add_variable( 'showicons', 'false' );
	}
	if ( $transition && ( $transition != $this->_TRANSITION_DEFAULT ) ) {
		$movie .= $this->build_add_variable( 'transition', $transition );
	}

// Controlbar appearance
	if ( $shownavigation == 0 ) { 
		$movie .= $this->build_add_variable( 'shownavigation', 'false' ); 
	}
	if ( $showstop == 1 ) { 
		$movie .= $this->build_add_variable( 'showstop', 'true' );
	}
	if ( $showdigits == 0 ) {
		$movie .= $this->build_add_variable( 'showdigits', 'false' );
	}
	if ( $usefullscreen == 0 ) {   
		$movie .= $this->build_add_variable( 'usefullscreen', 'false' );
	}

// Playlist appearance
	if ( $autoscroll == 1 ) {        
		$movie .= $this->build_add_variable( 'autoscroll', 'true' );
	}
	if ( $displaywidth > 0 ) {
		$movie .= $this->build_add_variable( 'displaywidth',  $displaywidth );
	}
	if ( $displayheight > 0 ) {
		$movie .= $this->build_add_variable( 'displayheight', $displayheight );
	}
	if ( $thumbsinplaylist == 0 ) {  
		$movie .= $this->build_add_variable( 'thumbsinplaylist', 'false' );
	}

// Playback behaviour
	if ( $audio != '' ) {  
		$movie .= $this->build_add_variable( 'audio', urlencode($audio) );
	}

	if ( $autostart != _C_WEBPHOTO_FLASHVAR_AUTOSTART_DEFAULT ) {
		$movie .= $this->build_add_variable( 'autostart', 
			$this->get_movie_autostart( $autostart ) );
	}

	if ( $bufferlength && ( $bufferlength != $this->_BUFFERLENGTH_DEFAULT ) ) {
		$movie .= $this->build_add_variable( 'bufferlength',  $bufferlength );
	}
	if( $captions != '' ) {
		$movie .= $this->build_add_variable( 'captions', urlencode($captions) );
	} 
	if( $fallback != '' ) {
		$movie .= $this->build_add_variable( 'fallback', $fallback );
	} 
	if ( $repeat == 1 ) {  
		$movie .= $this->build_add_variable( 'repeat', 'true' );
	}
	if ( $rotatetime && ( $rotatetime != $this->_ROTATETIME_DEFAULT ) ) {
		$movie .= $this->build_add_variable( 'rotatetime', $rotatetime );
	}
	if ($shuffle == 1) {  
		$movie .= $this->build_add_variable( 'shuffle', 'true' );
	}
	if ( $smoothing == 0 ) {  
		$movie .= $this->build_add_variable( 'smoothing', 'false' );
	}
	if ( $volume && ($volume != $this->_VOLUME_DEFAULT ) ) {  
		$movie .= $this->build_add_variable( 'volume', $volume );
	}

// External communication
	if ( $is_mediaplayer && $this->_cfg_use_callback ) {
		$movie .= $this->build_add_variable( 'callback', urlencode($this->_CALLBACK_URL) );
	}

//	if ( $enablejs == 1 ) {          
//		$movie .= $this->build_add_variable( 'enablejs', 'true' );   
//		$movie .= $this->build_add_variable( 'javascriptid', 'play'.$item_id );
//	}

	$movie_link = $this->get_movie_link( $src_url ) ;
	if ( $movie_link ) {
		$movie .= $this->build_add_variable( 'link', urlencode($movie_link) );

		if ( $showdownload == 1 ) {
			$movie .= $this->build_add_variable( 'showdownload', 'true' );
		}
		if ( $linkfromdisplay == 1 ) { 
			$movie .= $this->build_add_variable( 'linkfromdisplay', 'true' );
		}
		if ( $linktarget && ( $linktarget != $this->_LINKTARGET_DEFAULT ) ) {
			$movie .= $this->build_add_variable( 'linktarget', $linktarget );
		}
	}

	if ( $recommendations != '' ) {
		$movie .= $this->build_add_variable( 'recommendations', $recommendations );
	}
	if ( $searchlink != '' ) {
		$movie .= $this->build_add_variable( 'searchlink', $searchlink );
	}
	if ( $streamscript != '' ) {
		$movie .= $this->build_add_variable( 'searchlink', $streamscript );
	}
	if ( $flag_type && $this->check_type( $movie_file, $ext ) && $ext ) {
		$movie .= $this->build_add_variable( 'type', $ext ); 
	}
	if ( $flag_title && $item_title ) {
		$movie .= $this->build_add_variable( 'title', $item_title );
	}

	$movie .= 's'.$item_id.'.write("'. $div_id .'"); '."\n";
	$movie .= '</script>'."\n";

	return $movie ;
}

function build_add_parame( $name, $value )
{
	$str = 's'.$this->_item_id.'.addParam("'. $name .'","'. $value .'");'."\n";
	return $str;
}

function build_add_variable( $name, $value )
{
	$str = 's'.$this->_item_id.'.addVariable("'. $name .'","'. $value .'");'."\n";
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

// --- class end ---
}

?>