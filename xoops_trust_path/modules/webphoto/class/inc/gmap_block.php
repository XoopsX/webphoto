<?php
// $Id: gmap_block.php,v 1.2 2009/02/01 11:02:38 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_gmap_block
//=========================================================
class webphoto_inc_gmap_block
{
	var $_header_class ;

	var $_DIRNAME ;

	var $_TIMEOUT = 1000;	// 1000 msec
	var $_GMAP_CONTROL      = 1 ;
	var $_GMAP_TYPE_CONTROL = 0 ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_gmap_block( $dirname )
{
	$this->_DIRNAME = $dirname ;

	$this->_header_class =& webphoto_inc_xoops_header::getSingleton( $dirname );
}

function &getSingleton( $dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_gmap_block( $dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// gmap
//---------------------------------------------------------
function build_gmap( $param )
{
	$block_mode = $param['block_mode'];
	$gmap_mode  = $param['gmap_mode'];
	$apikey     = $param['apikey'];
	$height     = $param['height'];
	$photos     = $param['photos'];

	$show = false ;
	$map  = null ;

	if ( $gmap_mode == 0 ) {
		return array( $show, $map );
	}
	if ( empty($apikey) ) {
		return array( $show, $map );
	}
	if ( !is_array($photos) || !count($photos) ) {
		return array( $show, $map );
	}

	$show     = true ;
	$template = 'db:'. $this->_DIRNAME .'_inc_gmap_block.html';
	$name     = $this->_DIRNAME . '_b_g_' . $block_mode;

	$api_js     = $this->_header_class->assign_or_get_gmap_api_js( $apikey );
	$block_js   = $this->_header_class->assign_or_get_gmap_block_js();
	$api_load   = empty($api_js)   ? false : true ;
	$block_load = empty($block_js) ? false : true ;

	$gmap_param = $this->build_center( $param );

	$template_param = array(
		'xoops_url'         => XOOPS_URL ,
		'block_dirname'     => $this->_DIRNAME ,
		'photos'            => $photos ,
		'gmap_apikey'       => $apikey ,
		'gmap_height'       => $height ,
		'gmap_name'         => $name ,
		'gmap_api_load'     => $api_load ,
		'gmap_block_load'   => $block_load ,
		'gmap_api_js'       => $api_js ,
		'gmap_block_js'     => $block_js ,
		'gmap_latitude'     => $gmap_param['latitude'] ,
		'gmap_longitude'    => $gmap_param['longitude'] ,
		'gmap_zoom'         => $gmap_param['zoom'] ,
		'gmap_timeout'      => $this->_TIMEOUT ,
		'gmap_control'      => $this->_GMAP_CONTROL ,
		'gmap_type_control' => $this->_GMAP_TYPE_CONTROL ,
	);

	$map = $this->fetch_template( $template, $template_param );
	return array( $show, $map ) ;
}

function build_center( $param ) 
{
	$gmap_mode = $param['gmap_mode'];
	$latitude  = $param['default_latitude'] ;
	$longitude = $param['default_longitude'] ;
	$zoom      = $param['default_zoom'] ;

// set option value
	if ( $gmap_mode == 2 ) {
		$latitude  = $param['option_latitude'];
		$longitude = $param['option_longitude'];
		$zoom      = $param['option_zoom'];
	}

	$arr = array(
		'latitude'    => $latitude,
		'longitude'   => $longitude,
		'zoom'        => $zoom,
	);

	return $arr;
}

function fetch_template( $template, $param ) 
{
	$tpl = new XoopsTpl();
	$tpl->assign( $param );
	$text = $tpl->fetch( $template );
	return $text;
}

// --- class end ---
}

?>