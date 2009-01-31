<?php
// $Id: gmap_api.php,v 1.1 2009/01/31 19:14:12 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_ROOT_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// happy_linux_build_once_gmap_api
//=========================================================
// === function begin ===
if( !function_exists( 'happy_linux_build_once_gmap_api' ) ) 
{

function happy_linux_build_once_gmap_api( $apikey, $langcode=null )
{
	if ( happy_linux_check_once_gmap_api() ) {
		return happy_linux_build_gmap_api( $apikey, $langcode ) ;
	}
	return null;
}

function happy_linux_check_once_gmap_api()
{
	$const_name = "_C_HAPPY_LINUX_LOADED_GMAP_APIKEY" ;
	if ( ! defined( $const_name ) ) {
		define( $const_name, 1 );
		return true ;
	}
	return false ;
}

function happy_linux_build_gmap_api( $apikey, $langcode=null )
{
	if ( empty($langcode) ) {
		$langcode = _LANGCODE ;
	}

	$src = 'http://maps.google.com/maps?file=api&amp;hl='. $langcode .'&amp;v=2&amp;key='. $apikey ;
	$str = '<script src="'. $src .'" type="text/javascript" charset="utf-8"></script>'."\n";
	return $str;
}

// === function end ===
}

?>