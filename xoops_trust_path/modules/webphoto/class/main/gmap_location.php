<?php
// $Id: gmap_location.php,v 1.2 2008/07/08 20:31:22 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// added _build_list_location()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// myself:     default
// new window: mode=opener
// inline:     mode=parent
//---------------------------------------------------------

//=========================================================
// class webphoto_main_gmap_location
//=========================================================
class webphoto_main_gmap_location extends webphoto_base_this
{
	var $_gmap_class;
	var $_multibyte_class;

	var $_TEMPLATE     = null;
	var $_GMAP_HEIGHT  = 300;
	var $_OPNER_MODE   = 'parent';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_gmap_location( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_TEMPLATE = 'db:'. $dirname .'_main_gmap_location.html';

	$this->_gmap_class      =& webphoto_gmap::getInstance( $dirname , $trust_dirname );
	$this->_multibyte_class =& webphoto_lib_multibyte::getInstance();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_gmap_location( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$cfg_gmap_apikey = $this->get_config_by_name( 'gmap_apikey' );

	if ( $cfg_gmap_apikey ) {
		$this->_assign_template( $cfg_gmap_apikey );
	} else {
		$this->_show_error();
	}
}

function _assign_template( $cfg_gmap_apikey )
{
	$get_photo_id = $this->_post_class->get_get_int('photo_id');

	$cfg_gmap_latitude  = $this->get_config_by_name( 'gmap_latitude' );
	$cfg_gmap_longitude = $this->get_config_by_name( 'gmap_longitude' );
	$cfg_gmap_zoom      = $this->get_config_by_name( 'gmap_zoom' );

	$flag_set_location = false;

	$show_gmap = false;
	$gmap_list = null;

	if ( ( $cfg_gmap_latitude != 0 )  ||
	     ( $cfg_gmap_longitude != 0 ) ||
	     ( $cfg_gmap_zoom != 0 ) ) 
	{
		$flag_set_location = true;
		$gmap_latitude     = $cfg_gmap_latitude;
		$gmap_longitude    = $cfg_gmap_longitude;
		$gmap_zoom         = $cfg_gmap_zoom;
	}

	if ( $get_photo_id > 0 ) {
		$row = $this->_photo_handler->get_row_by_id( $get_photo_id );
		if ( is_array($row) && $this->_gmap_class->exist_gmap( $row ) ) { 
			$flag_set_location = true;
			$gmap_latitude     = $row['photo_gmap_latitude'];
			$gmap_longitude    = $row['photo_gmap_longitude'];
			$gmap_zoom         = $row['photo_gmap_zoom'];

			list( $show_gmap, $gmap_list ) 
				= $this->_build_list_location( $row );
		}
	}

	$this->_http_output( 'pass' );
	header ('Content-Type:text/html; charset=UTF-8');

	$tpl = new XoopsTpl();

	$tpl->assign('xoops_dirname',      $this->_DIRNAME );
	$tpl->assign('mydirname',          $this->_DIRNAME );
	$tpl->assign('gmap_opener_mode',   $this->_OPNER_MODE );
	$tpl->assign('gmap_height',        $this->_GMAP_HEIGHT );
	$tpl->assign('gmap_apikey',        $cfg_gmap_apikey );

	if ( $flag_set_location ) {
		$tpl->assign('gmap_latitude',   $gmap_latitude );
		$tpl->assign('gmap_longitude',  $gmap_longitude );
		$tpl->assign('gmap_zoom',       $gmap_zoom );
	}

	$tpl->assign('show_gmap',    $show_gmap );
	$tpl->assign('gmap_list',    $gmap_list );

	$tpl->assign('gmap_lang_latitude',       $this->_constant('GMAP_LATITUDE') );
	$tpl->assign('gmap_lang_longitude',      $this->_constant('GMAP_LONGITUDE') );
	$tpl->assign('gmap_lang_zoom',           $this->_constant('GMAP_ZOOM') );
	$tpl->assign('gmap_lang_no_match_place', $this->_constant('GMAP_NO_MATCH_PLACE') );
	$tpl->assign('gmap_lang_not_compatible', $this->_constant('GMAP_NOT_COMPATIBLE') );

	$tpl->assign('lang_title',            $this->_constant('TITLE_GET_LOCATION') );
	$tpl->assign('lang_address',          $this->_constant('GMAP_ADDRESS') );
	$tpl->assign('lang_get_location',     $this->_constant('GMAP_GET_LOCATION') );
	$tpl->assign('lang_search_list',      $this->_constant('GMAP_SEARCH_LIST') );
	$tpl->assign('lang_current_location', $this->_constant('GMAP_CURRENT_LOCATION') );
	$tpl->assign('lang_current_address',  $this->_constant('GMAP_CURRENT_ADDRESS') );
	$tpl->assign('lang_js_invalid',       $this->_constant('JS_INVALID') );
	$tpl->assign('lang_search',           $this->_constant('SR_SEARCH') );

	$tpl->display( $this->_TEMPLATE );
}

function _build_list_location( $row )
{
	$show_gmap = false;
	$gmap_list = null;

	$list = $this->_gmap_class->build_list_location( $row );
	if ( !is_array($list) || !count($list) ) {
		return array( $show_gmap, $gmap_list );
	}

// convert to UTF-8
	$gmap_list = array();
	foreach ( $list as $loc ) 
	{
		$temp         = $loc;
		$temp['info'] = $this->_utf8( $loc['info'] );
		$gmap_list[]  = $temp ;
	}

	if ( is_array($gmap_list) && count($gmap_list)) {
		$show_gmap = true;
	}

	return array( $show_gmap, $gmap_list );
}

function _constant( $name )
{
	return $this->_utf8( $this->get_constant( $name ) );
}

//---------------------------------------------------------
// show error
//---------------------------------------------------------
function _show_error()
{
	header ('Content-Type:text/html; charset='._CHARSET);

// --- raw HTML begin ---
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="content-type" content="text/html; charset='. _CHARSET .'"/>
<title>weblinks - <?php echo $this->get_constant('TITLE_GET_LOCATION') ; ?></title>
</head>
<body>
<h3><?php echo $this->get_constant('TITLE_GET_LOCATION') ; ?></h3>
<h4 style="color: #ff0000;">not set google map api key</h4>
</body>
</html>
<?php
// --- raw HTML end ---
}

//---------------------------------------------------------
// multibyte
//---------------------------------------------------------
function _http_output( $encoding )
{
	return $this->_multibyte_class->m_mb_http_output( $encoding );
}

function _utf8( $str )
{
	return $this->_multibyte_class->convert_to_utf8( $str );
}

// --- class end ---
}

?>