<?php
// $Id: i.php,v 1.10 2009/09/19 20:40:44 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-09-20 K.OHWADA
// webphoto_lib_staticmap
// 2009-01-10 K.OHWADA
// webphoto_show_photo -> webphoto_imode 
// 2008-12-29 K.OHWADA
// Fatal error: Call to undefined method get_row()
// 2008-12-12 K.OHWADA
// webphoto_photo_public
// 2008-12-08 K.OHWADA
// _get_encode_type_array()
// 2008-12-07 K.OHWADA
// $_ARRAY_MOBILE_TEXT
// 2008-09-01 K.OHWADA
// photo_handler -> item_handler
// added _judge()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_i
//=========================================================
class webphoto_main_i extends webphoto_imode
{
	var $_staticmap_class;

	var $_cfg_gmap_apikey ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_i( $dirname , $trust_dirname )
{
	$this->webphoto_imode( $dirname , $trust_dirname );

	$this->_cfg_gmap_apikey = $this->get_config_by_name( 'gmap_apikey' );

	$this->_staticmap_class =& webphoto_lib_staticmap::getInstance();
	$this->_staticmap_class->set_key( $this->_cfg_gmap_apikey );

// preload
	$this->preload_init();
	$this->preload_constant();

	$this->_encode_type_array = $this->get_encode_type_array();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_i( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$this->output_header();

	$op = $this->_post_class->get_get_text('op');
	switch ( $op )
	{
		case 'judge':
			$this->_judge();
			break;

		default:
			$this->_show() ;
			break;
	}

}

//---------------------------------------------------------
// judge modle from user agent
//---------------------------------------------------------
function _judge()
{
	$text  = $this->build_html_head( $this->_TITLE_S, $this->_MOBILE_CHARSET_OUTPUT );
	$text .= $this->build_html_body_begin();
	$text .= $this->_judge_exec();
	$text .= $this->build_goto();
	$text .= $this->build_html_body_end();

	echo $this->conv( $text );
}

function _judge_exec()
{
	$ua      = $this->_agent_class->get_user_agent();
	$carrier = $this->_agent_class->parse_mobile_carrier( $ua );
	$browser = $this->_agent_class->parse_browser( $ua );

	$text  = '';
	$text .= $this->get_constant('TITLE_MAIL_JUDGE')."<br><br>\n";
	$text .= 'User Agent : '. $ua ."<br>\n";

	if ( $carrier ) {
		$text .= $this->get_constant('MAIL_MODEL').' : '. $carrier ."<br>\n";

	} elseif ( $browser ) {
		$text .= $this->get_constant('MAIL_BROWSER').' : '. $browser ."<br>\n";

	} else {
		$mailto = 'mailto:'. $this->_xoops_adminmail .'?subject=mobile_model&amp;body='. $ua ;
		$text .= "<br>\n";
		$text .= $this->get_constant('MAIL_NOT_JUDGE')."<br>\n";
		$text .= '<a href="'. $mailto .'">';
		$text .= $this->get_constant('MAIL_TO_WEBMASTER');
		$text .= "<a><br>\n";
	}
	return $text ;
}

//---------------------------------------------------------
// show
//---------------------------------------------------------
function _show()
{
	$tpl = new XoopsTpl();
	$tpl->assign( $this->_show_exec() ) ;
	$tpl->display( $this->_MOBILE_TEMPLATE );
}

function _show_exec()
{
	$id   = $this->_post_class->get_get_int('id');
	$size = $this->_post_class->get_get_int('s');
	$page = $this->_post_class->get_get_int('page', 1);
	$op   = $this->_post_class->get_get_text('op');

	$show_photo = false;
	$show_map   = false;
	$photo      = null;

	switch ($op)
	{
		case 'map':
			$show_map   = true;
			break;

		case 'map':

	
	}

// if noto specify page
	if ( $page <= 1 ) {
		$photo = $this->_get_photo( $op, $id );
	}

	$pagetitle = $this->_MODULE_NAME ;
	if ( is_array($photo) ) {
		$pagetitle  = $photo['title'];

		switch ($op)
		{
			case 'map':
				$show_map   = true;
				break;

			case 'latest':
			default:
				$show_photo = true;
				break;
		}
	}

	$arr = array(
		'photo'         => $photo,
		'photo_list'    => $this->_get_photo_list( $page ),
		'navi'          => $this->_build_navi( $page ) ,
		'xoops_dirname' => $this->_DIRNAME ,
		'charset'       => $this->_MOBILE_CHARSET_OUTPUT,
		'size'          => $size,
		'show_photo'    => $show_photo ,
		'show_map'      => $show_map ,
		'show_post'     => $this->check_perm() ,
		'token'         => $this->get_token() ,

		'cfg_thumb_width'  => $this->get_config_by_name('thumb_width') ,
		'cfg_middle_width' => $this->get_config_by_name('middle_width') ,
		'sitename_conv'    => $this->conv( $this->sanitize( $this->_xoops_sitename ) ) ,
		'pagetitle_conv'   => $this->conv( $this->sanitize( $pagetitle ) ) ,
		'modulename_conv'  => $this->conv( $this->sanitize( $this->_MODULE_NAME ) ) ,
		'lang_video_conv'  => $this->conv( $this->get_constant('ICON_VIDEO') ) ,
		'lang_second_conv' => $this->conv( $this->get_constant('SECOND') ) ,
		'lang_post_conv'   => $this->conv( $this->get_constant('TITLE_MAIL_POST') ) ,
		'lang_judge_conv'  => $this->conv( $this->get_constant('TITLE_MAIL_JUDGE') ) ,
		'lang_show_map'    => 'show map',
	);

	return $arr;
}

function _get_photo( $op, $id )
{
	$item_row = null;
	$photo    = null;

// latest
	if ( $op == 'latest' ) {
		$item_rows = $this->_photo_public_class->get_rows_imode_by_orderby(
			$this->_MOBILE_LIST_ORDERBY, $this->_MOBILE_LATEST_LIMIT );

		if ( isset($item_rows[0]) ) {
			$item_row = $item_rows[0] ;
		}

// specified
	} elseif ( $id > 0 ) {

// Fatal error: Call to undefined method get_row()
		$item_row = $this->_item_public_class->get_item_row( $id );

	}

// random
	if ( !is_array($item_row) ) {
		$item_rows = $this->_photo_public_class->get_rows_imode_by_orderby(
			$this->_MOBILE_RANDOM_ORDERBY, $this->_MOBILE_RANDOM_LIMIT );

		if ( isset($item_rows[0]) ) {
			$item_row = $item_rows[0] ;
		}
	}

	if ( is_array($item_row) ) {
		$photo = $this->build_show_conv( $item_row );
	}
	return $photo;
}

function _get_photo_list( $page )
{
	$this->_pagenavi_class->set_page( $page );
	$start = $this->_pagenavi_class->calc_start( $page, $this->_MOBILE_LIST_LIMIT );

	$item_rows = $this->_photo_public_class->get_rows_imode_by_orderby(
		$this->_MOBILE_LIST_ORDERBY, $this->_MOBILE_LIST_LIMIT, $start );

	return $this->build_show_conv_from_rows( $item_rows );
}

function _build_navi( $page )
{
	$url   = $this->_MODULE_URL .'/i.php?';
	$total = $this->_photo_public_class->get_count_imode();

	return $this->_pagenavi_class->build( 
		$url, $page, $this->_MOBILE_LIST_LIMIT, $total, $this->_MOBILE_NAVI_WINDOWS );
}

//---------------------------------------------------------
// build show
//---------------------------------------------------------
function build_show_conv( $item_row )
{
	$arr = $this->build_photo_show( $item_row );

	$arr['description_conv'] = $this->conv( $arr['description_disp'] ) ;
	$arr['summary_conv']     = $this->conv( $arr['summary'] ) ;

	foreach ( $this->_encode_type_array as $name ) {
		$arr[ $name.'_conv' ] = $this->conv( $arr[ $name.'_s'] ) ;
	}

	$has_map = $this->exist_gmap_item( $item_row );
	$map_src = null;

	if ( $has_map ) {
		$map_src = $this->build_map_src( $item_row );
	}

	$arr['has_map'] = $has_map;
	$arr['map_src'] = $map_src;

	return $arr;
}

function build_show_conv_from_rows( $item_rows )
{
	$arr = array();
	foreach ( $item_rows as $item_row ) {
		$arr[] = $this->build_show_conv( $item_row ) ;
	}
	return $arr;
}

//---------------------------------------------------------
// map
//---------------------------------------------------------
function exist_gmap_item( $item_row )
{
	if ( empty($this->_cfg_gmap_apikey) ) {
		return false;
	}

	return $this->exist_gmap( 
		$item_row['item_gmap_latitude'] , 
		$item_row['item_gmap_longitude'] , 
		$item_row['item_gmap_zoom'] );
	
}

function exist_gmap( $latitude, $longitude, $zoom )
{
	if ( $latitude == 0 ) {
		return false;
	}
	if ( $longitude == 0 ) {
		return false;
	}
	if ( $zoom == 0 ) {
		return false;
	}
	return true;
}

function build_map_src( $item_row )
{
	$marker = array(
		'latitude'  => $item_row['item_gmap_latitude'] ,
		'longitude' => $item_row['item_gmap_longitude'] ,
	);

	$param = array(
		'latitude'  => $item_row['item_gmap_latitude'] ,
		'longitude' => $item_row['item_gmap_longitude'] ,
		'zoom'      => $item_row['item_gmap_zoom'] ,
		'markers'   => array( $marker ) ,
	);

	return $this->_staticmap_class->build_url( $param );
}

// --- class end ---
}

?>