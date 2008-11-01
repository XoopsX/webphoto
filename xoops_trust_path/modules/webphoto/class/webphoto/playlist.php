<?php
// $Id: playlist.php,v 1.2 2008/11/01 23:53:08 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_playlist
//=========================================================
//---------------------------------------------------------
// http://code.jeroenwijering.com/trac/wiki/Playlists3
//---------------------------------------------------------

class webphoto_playlist extends webphoto_lib_base
{
	var $_config_class;
	var $_item_handler;
	var $_utility_class;
	var $_xml_class;
	var $_remote_class;
	var $_multibyte_class;

	var $_report = null;

	var $_DIRNAME       = null;
	var $_TRUST_DIRNAME = null;
	var $_MODULE_URL;
	var $_MODULE_DIR;

	var $_PLAYLIST_DIR;
	var $_MEDIA_DIR ;
	var $_MEDIA_URL ;

	var $_IMAGE_EXTS ;
	var $_SWFOBJECT_EXTS ;
	var $_MEDIAPLAYER_EXTS ;
	var $_CAPTION_EXTS = array('xml');

	var $_ASCII_LOWER_A = 97; 
	var $_ASCII_LOWER_Z = 122;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_playlist( $dirname, $trust_dirname )
{
	$this->webphoto_lib_base( $dirname, $trust_dirname );

	$this->_config_class    =& webphoto_config::getInstance( $dirname );
	$this->_item_handler    =& webphoto_item_handler::getInstance( $dirname );
	$this->_utility_class   =& webphoto_lib_utility::getInstance();
	$this->_xml_class       =& webphoto_lib_xml::getInstance();
	$this->_remote_class    =& webphoto_lib_remote_file::getInstance();
	$this->_multibyte_class =& webphoto_lib_multibyte::getInstance();

	$uploads_path = $this->_config_class->get_uploads_path();
	$medias_path  = $this->_config_class->get_medias_path();

	$this->_PLAYLIST_DIR = XOOPS_ROOT_PATH . $uploads_path .'/playlists';
	$this->_MEDIA_DIR    = XOOPS_ROOT_PATH . $medias_path ;
	$this->_MEDIA_URL    = XOOPS_URL       . $medias_path ;

	$this->_IMAGE_EXTS     = explode( '|', _C_WEBPHOTO_IMAGE_EXTS );
	$this->_AUDIO_EXTS     = explode( '|', _C_WEBPHOTO_MEDIAPLAYER_AUDIO_EXTS ) ;
	$this->_VIDEO_EXTS     = explode( '|', _C_WEBPHOTO_MEDIAPLAYER_VIDEO_EXTS ) ;
	$this->_SWFOBJECT_EXTS = explode( '|', _C_WEBPHOTO_SWFOBJECT_EXTS ) ;
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_playlist( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// build cache file name
//---------------------------------------------------------
function build_name( $item_id )
{
	$alphabet = chr( rand( $this->_ASCII_LOWER_A, $this->_ASCII_LOWER_Z ) );
	$str  = $alphabet;
	$str .= sprintf( "%05d", $item_id );
	$str .= uniqid( $alphabet );
	$str .= '.xml';
	return $str;
}

//---------------------------------------------------------
// check
//
// http://www.php.net/manual/en/function.date.php
// U = Unix Epoch
//---------------------------------------------------------
function check_expired( $cache, $time )
{
	$filename = $this->_PLAYLIST_DIR.'/'.$cache;
	if ( file_exists($filename) ) {
		$filetime = date( "U", filemtime($filename) );
		if ( time() < ( $filetime + $time ) ) {
			return true ;
		}
	}
	return false ; 
}

//---------------------------------------------------------
// create
//---------------------------------------------------------
function create_cache_by_item_id( $item_id )
{
	$row = $this->_item_handler->get_row_by_id( $item_id );
	return $this->create_cache_by_item_row( $row );
}

function create_cache_by_item_row( $row )
{
	if ( !is_array( $row ) ) {
		return false ;
	}

	if ( empty($row['item_playlist_cache']) ) {
		return false ;
	}

	$ret = false ;

	switch ( $row['item_kind'] )
	{
		case _C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR :
			$ret = $this->_create_list( $row );
			break;

		case _C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED :
			$ret = $this->_fetch_feed( $row );
			break;
	}

	return $ret ;
}

function get_report()
{
	$msg = null ;
	if ( $this->_report ) {
		$msg  = $this->get_constant('PLAYLIST_FETCHED') .'<br />' ;
		$msg .= $this->_report ;
	}
	return $msg ;
}

//---------------------------------------------------------
// fetch
//---------------------------------------------------------
function _fetch_feed( $row )
{
	$feed  = $row['item_playlist_feed'];
	$cache = $row['item_playlist_cache'];

	if ( empty($feed) ) {
		return false ;
	}

// Fetch
	$data = $this->_remote_class->read_file( $feed );

	if ( $data ) {
		$ret = $this->_write_cache( $cache, $data );
		if ( $ret ) {
			$this->_report = $this->get_constant('PLAYLIST_STATUS_FETCHED') ;
			return true;	// fetched
		}

// not fetch
	} else {
		$this->set_error( 
			$this->_build_error( 'PLAYLIST_ERR_FETCH', $feed ) ) ;
	}

	return false;
}

function _write_cache( $cache, $data )
{
	$file = $this->_PLAYLIST_DIR.'/'.$cache;

	$byte = $this->_utility_class->write_file( $file, $data, 'w', true );
	if ( $byte > 0 ) {
		return true;
	}

	$this->set_error( 
		$this->_build_error( 'PLAYLIST_ERR_WRITE', $cache ) ) ;

	return false ;
}

function _build_error( $name, $param )
{
	$msg  = $this->get_constant('PLAYLIST_ERR_CACHE') .'<br />';
	$msg .= $this->get_constant( $name ) .'<br />';
	$msg .= $param ;
	return $msg ; 
}

//---------------------------------------------------------
// create from directory
//---------------------------------------------------------
function _create_list( $row )
{
	$this->_report = '';
	$msg = '';

	$dir   = $row['item_playlist_dir'];
	$type  = $row['item_playlist_type'];
	$cache = $row['item_playlist_cache'];

	if ( empty($dir) ) {
		return false ;
	}

	$media_dir = $this->_MEDIA_DIR.'/'.$dir ;
	$media_url = $this->_MEDIA_URL.'/'.$dir ;

	$params = array();

	if ( !file_exists($media_dir) || !is_dir($media_dir) ) {
		$this->set_error( 
			$this->_build_error( 'PLAYLIST_ERR_NODIR' , $media_dir ) ) ;
		return false;
	}

	$files = $this->_utility_class->get_files_in_dir( $media_dir, null, false, true );
	if ( !is_array($files) || !count($files) ) {
		$this->set_error( 
			$this->_build_error( 'PLAYLIST_ERR_EMPTYDIR' , $cache ) ) ;
		return false;
	}

	foreach ( $files as $file ) 
	{
		$name = $this->_utility_class->strip_ext( $file );

		list( $item, $image, $caption )
			= $this->_parse_entry( $file, $type );

		if ( $item ) {
			$params[ $name ]['item'] = $file; 
		}
		if ( $image ) {
			$params[ $name ]['image'] = $file; 
		}
		if ( $caption ) {
			$params[ $name ]['caption'] = $file; 
		}
	}

	if ( !is_array($params) || !count($params) ) {
		$this->set_error( 
			$this->_build_error( 'PLAYLIST_ERR_EMPTYDIR' , $cache ) ) ;
		return false;
	}

	$data = $this->_build_playlist_xml( $media_url, $row, $params );

	$ret = $this->_write_cache( $cache, $data );
	if ( $ret ) {
		$this->_report  = $this->get_constant('PLAYLIST_STATUS_CREATED') .'<br />';
		$this->_report .= $cache;
		return true;	// created
	}

	return false;
}

function _parse_entry( $file, $type )
{
	$file_ext = strtolower( $this->_utility_class->parse_ext( $file ) );

	$item    = false;
	$image   = false;
	$caption = false;

	$exts = $this->_type_to_exts( $type );
	if ( is_array($exts) && in_array( $file_ext, $exts ) ) {
		$item = true;
	}

	if ( in_array( $file_ext, $this->_IMAGE_EXTS ) ) {
		$image = true;
	}

	if ( in_array( $file_ext, $this->_CAPTION_EXTS ) ) {
		$caption = true;
	}

	return array( $item, $image, $caption );

}

function _type_to_exts( $type )
{
	$exts = null;

	switch ( $type )
	{
		case _C_WEBPHOTO_PLAYLIST_TYPE_IMAGE :
			$exts = $this->_IMAGE_EXTS ;
			break;

		case _C_WEBPHOTO_PLAYLIST_TYPE_AUDIO :
			$exts = $this->_AUDIO_EXTS ;
			break;

		case _C_WEBPHOTO_PLAYLIST_TYPE_VIDEO :
			$exts = $this->_VIDEO_EXTS ;
			break;
	}

	return $exts;
}

//---------------------------------------------------------
// the playlist is built in xspf format
// we'll first add an xml header and the opening tags .. 
// header(\"content-type:text/xml;charset=utf-8\"); //NEEDS WORK
//---------------------------------------------------------
function _build_playlist_xml( $media_url, $row, $params )
{
	$item_id   = $row['item_id'];
	$siteurl   = $row['item_siteurl'];
	$artist    = $row['item_artist'];
	$album     = $row['item_album'];
	$label     = $row['item_label'];
	$top_title = $row['item_title'];

	if ( $siteurl ) {
		$trackinfo = $siteurl;
	} else {
		$trackinfo = $this->_MODULE_URL.'/index.php?fct=photo&photo_id='.$item_id;
	}

	$trackinfo_xml = $this->_xml( $trackinfo );
	$top_title_xml = $this->_xml( $this->_utf8($top_title) );
	$artist_xml    = $this->_xml( $this->_utf8($artist) );
	$album_xml     = $this->_xml( $this->_utf8($album) );
	$label_xml     = $this->_xml( $this->_utf8($label) );

	$data  = '<playlist version="1" xmlns="http://xspf.org/ns/0/">'."\n";
	$data .= '<title>'. $top_title_xml .'</title>'."\n";
	$data .= '<info>'. $trackinfo_xml .'</info>'."\n";
	$data .= '<trackList>'."\n";

//  then we loop through the directory files ...
	foreach ( $params as $name => $param ) 
	{
		$item  = null ;
		$image = null ;
		$title = null;
		if ( isset(  $param['item'] ) ) {
			$item  = $param['item'] ;
			$title = $this->_utf8( $this->_utility_class->strip_ext( $item ) );
		}
		if ( isset(  $param['image'] ) ) {
			$image = $param['image'] ;
		}

		if ( empty($item) ) {
			continue ;
		}

		$data .= '<track>'."\n";
		$data .= '<title>'. $this->_xml($title).'</title>'."\n";
		$data .= '<location>'. $this->_xml_url( $media_url.'/'.$item ).  '</location>'."\n";
		$data .= '<info>'. $trackinfo_xml .'</info>'."\n";
		if ( $artist ) {
			$data .= '<creator>'. $artist_xml .'</creator>'."\n";
		}
		if ( $album ) {
			$data .= '<album>'. $album_xml .'</album>'."\n";
		}
		if ( $label ) {
			$data .= '<label>'. $label_xml .'</label>'."\n";
		}
		if ( $image ){
			$data .= '<image>'. $this->_xml_url( $media_url.'/'.$image ). '</image>'."\n";
		}
		$data .= '</track>'."\n";
	}

// .. and last we add the closing tags
	$data .= '</trackList>'."\n";
	$data .= '</playlist>'."\n";

	return $data;
}

function _xml($str)
{
	return $this->_xml_class->xml_text($str);
}

function _xml_url($str)
{
	return $this->_xml_class->xml_url($str);
}

function _utf8($str)
{
	return $this->_multibyte_class->convert_to_utf8($str);
}

//---------------------------------------------------------
// refresh_all
//---------------------------------------------------------
function refresh_cache_all()
{
	$rows = $this->_item_handler->get_rows_public_by_kind( _C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED );
	if ( is_array($rows) && count($rows) ) {
		foreach( $rows as $row )
		{
			$this->_update_item_cache( $row );
			$this->_fetch_feed( $row );
		}
	}

	$rows = $this->_item_handler->get_rows_public_by_kind( _C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR );
	if ( is_array($rows) && count($rows) ) {
		foreach( $rows as $row )
		{
			$this->_update_item_cache( $row );
			$this->_create_list( $row );
		}
	}
}

function _update_item_cache( $row )
{
	$item_id = $row['item_id'];
	$cache   = $row['item_playlist_cache'];

	if ( empty($cache) ) {
		$this->_item_handler->update_playlist_cache( 
			$item_id, $cache = $this->build_name( $item_id ) );
	}
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function delete( $item_id )
{
	$cache = '';

	$row = $this->_item_handler->get_row_by_id( $item_id );
	if ( isset(  $row['item_playlist_cache'] ) ) {
		$cache = $row['item_playlist_cache'];
	}

	if ( $cache != '' ) {
		$cache = $this->_PLAYLIST_DIR.'/'.$cache;
		if (file_exists($cache)) {
			unlink($cache); 
		}
	}
}

// --- class end ---
}

?>