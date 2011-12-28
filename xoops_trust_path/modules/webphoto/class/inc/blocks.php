<?php
// $Id: blocks.php,v 1.24 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// webphoto_inc_timeline
// 2011-06-04 K.OHWADA
// webphoto_inc_uri
// 2010-10-01 K.OHWADA
// img_large_src
// 2009-11-11 K.OHWADA
// getInstance -> getSingleton 
// 2009-04-10 K.OHWADA
// timeline_show()
// 2009-01-25 K.OHWADA
// webphoto_inc_gmap_block
// 2009-01-04 K.OHWADA
// fatal error: Call to undefined method get_cat_parent_all_child_id()
// 2008-12-12 K.OHWADA
// webphoto_inc_public
// 2008-11-29 K.OHWADA
// catlist_show()
// build_show_file_image()
// 2008-10-01 K.OHWADA
// item_external_thumb
// 2008-08-24 K.OHWADA
// table_photo -> table_item
// 2008-08-06 K.OHWADA
// added cache_time
// 2008-08-05 K.OHWADA
// BUG: cannot select category
// 2008-07-01 K.OHWADA
// used use_pathinfo
// _assign_xoops_header() -> _get_popbox_js()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_blocks
//=========================================================
class webphoto_inc_blocks extends webphoto_inc_public
{
	var $_TRUST_DIRNAME ;

	var $_utility_class ;
	var $_multibyte_class ;
	var $_catlist_class ;
	var $_header_class;
	var $_tagcloud_class ;
	var $_timeline_class ;
	var $_gmap_block_class ;
	var $_gmap_info_class  ;
	var $_uri_class;

	var $_cfg_use_pathinfo   = false;
	var $_cfg_use_popbox     = false;
	var $_cfg_thumb_width    = 0 ;
	var $_cfg_thumb_height   = 0 ;
	var $_cfg_cat_main_width = 0 ;
	var $_cfg_cat_sub_width  = 0 ;
	var $_cfg_gmap_apikey    = null ;
	var $_cfg_gmap_latitude  = 0 ;
	var $_cfg_gmap_longitude = 0 ;
	var $_cfg_gmap_zoom      = 0 ;
	var $_cfg_timeline_dirname = null ;

	var $_block_id = 0;
	var $_init_timeline = false;

	var $_CHECKED  = 'checked="checked"';
	var $_SELECTED = 'selected="selected"';

	var $_YESNO_OPTIONS = null;
	var $_CACHE_OPTIONS    = null;

	var $_TOP_CATLIST_DELMITA = '<br />';
	var $_SHOW_SUBCAT_IMG     = true;
	var $_lang_catlist_total  = 'Total:';
	var $_lang_iframe_not_support = 'Do not use iframe in your web browser';

	var $_GMAP_WIDTH      = '100%';
	var $_GMAP_HEIGHT     = '650px';

	var $_ORDERBY_RANDOM = 'rand()';
	var $_ORDERBY_LATEST = 'item_time_update DESC, item_id DESC';
	var $_TIMELINE_DATE  = null;

	var $_OFFSET_ZERO = 0 ;
	var $_KEY_TRUE    = true ;
	var $_KEY_NAME    = 'item_id' ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_blocks( $dirname , $trust_dirname )
{
	$this->webphoto_inc_public();
	$this->init_public( $dirname , $trust_dirname );
	$this->auto_publish();

	$this->_init_xoops_config_for_block( $dirname );

	$this->_catlist_class    
		=& webphoto_inc_catlist::getSingleton( $dirname , $trust_dirname );
	$this->_gmap_info_class  
		=& webphoto_inc_gmap_info::getSingleton( $dirname , $trust_dirname );
	$this->_tagcloud_class   
		=& webphoto_inc_tagcloud::getSingleton( $dirname , $trust_dirname );

	$this->_header_class     =& webphoto_inc_xoops_header::getSingleton( $dirname );
	$this->_gmap_block_class =& webphoto_inc_gmap_block::getSingleton( $dirname );
	$this->_uri_class        =& webphoto_inc_uri::getSingleton( $dirname );
	$this->_utility_class    =& webphoto_lib_utility::getInstance();
	$this->_multibyte_class  =& webphoto_lib_multibyte::getInstance();

	$this->_timeline_class   =& webphoto_inc_timeline::getSingleton( $dirname );
	$this->_init_timeline    =  $this->_timeline_class->init( $this->_cfg_timeline_dirname );

	$this->_block_id = isset($_GET['bid']) ? intval($_GET['bid']) : 0 ;

	$this->_YESNO_OPTIONS = array(
		1 => _YES ,
		0 => _NO  ,
	);

	$this->_CACHE_OPTIONS = array(
		'0'       => _NOCACHE, 
		'30'      => sprintf(_SECONDS, 30), 
		'60'      => _MINUTE, 
		'300'     => sprintf(_MINUTES, 5), 
		'1800'    => sprintf(_MINUTES, 30), 
		'3600'    => _HOUR, 
		'18000'   => sprintf(_HOURS, 5), 
		'86400'   => _DAY, 
		'259200'  => sprintf(_DAYS, 3), 
		'604800'  => _WEEK, 
		'2592000' => _MONTH
	);
}

function &getSingleton( $dirname , $trust_dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_blocks( $dirname , $trust_dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// topnews
//
// options
//   0 : dirname
//   1 : photos_num (5)
//   2 : cat_limitation (0)
//   3 : cat_limit_recursive (1)
//   4 : title_max_length (20)
//   5 : cols (1)
//   6 : cache_time (0)

// [10] google map mode (0)
//       when 0, not show
//       when 1, use config latitude/longitude/zoom
//       when 2, use following value
// [11] google map latitude  (0)
// [12] google map longitude (0)
// [13] google map zoom      (0)
// [14] google map height  (300) px
//---------------------------------------------------------
function topnews_show( $options )
{
	return $this->_top_show_common( 'topnews', $options );
}

function topnews_p_show( $options )
{
	return $this->_top_show_common( 'topnews_p', $options );
}

function topnews_edit( $options )
{
	return $this->_top_edit_common( 'topnews', $options ) ;
}

function topnews_p_edit( $options )
{
	return $this->_top_edit_common( 'topnews_p', $options ) ;
}

//---------------------------------------------------------
// tophits
//---------------------------------------------------------
function tophits_show( $options )
{
	return $this->_top_show_common( 'tophits', $options );
}

function tophits_p_show( $options )
{
	return $this->_top_show_common( 'tophits_p', $options );
}

function tophits_edit( $options )
{
	return $this->_top_edit_common('tophits', $options ) ;
}

function tophits_p_edit( $options )
{
	return $this->_top_edit_common( 'tophits_p', $options ) ;
}

//---------------------------------------------------------
// rphoto
//---------------------------------------------------------
function rphoto_show( $options )
{
	return $this->_top_show_common( 'rphoto', $options );
}

function rphoto_edit( $options )
{
	return $this->_top_edit_common( 'rphoto', $options ) ;
}

//---------------------------------------------------------
// category list
//
// options
//   0 : dirname
//   1 : show_sub (1)
//   2 : show_main_img (1)
//   3 : show_sub_img  (1)
//   4 : cols (3)
//---------------------------------------------------------
function catlist_show( $options )
{
	$show_sub      = $this->_get_option_int(  $options, 1 ) ;
	$show_main_img = $this->_get_option_int(  $options, 2 ) ;
	$show_sub_img  = $this->_get_option_int(  $options, 3 ) ;
	$cols          = $this->_get_option_int(  $options, 4 ) ;


	list( $cols, $width ) =
		$this->_catlist_class->calc_width( $cols ) ;

	$param = array(
		'cats'             => $this->_catlist_class->build_catlist( 0, $show_sub ) ,
		'cols'             => $cols ,
		'width'            => $width ,
		'delmita'          => $this->_TOP_CATLIST_DELMITA ,
		'show_sub'         => $show_sub ,
		'show_main_img'    => $show_main_img ,
		'show_sub_img'     => $show_sub_img ,
		'main_width'       => $this->_cfg_cat_main_width ,
		'sub_width'        => $this->_cfg_cat_sub_width ,
		'lang_total'       => $this->_lang_catlist_total ,
	);

	$block = array(
		'dirname'          => $this->_DIRNAME ,
		'cfg_use_pathinfo' => $this->_cfg_use_pathinfo ,
		'catlist'          => $param ,
	);

	return $this->_assign_block( 'catlist', $block ) ;
}

function _assign_block( $mode, $block )
{
	$template = 'db:'. $this->_DIRNAME .'_block_'. $mode .'.html';
	$tpl = new XoopsTpl();
	$tpl->assign( 'block', $block );
	$ret = array();
	$ret['content'] = $tpl->fetch( $template ) ;
	return $ret ;
}

function catlist_edit( $options )
{
	$show_sub      = $this->_get_option_int(  $options, 1 ) ;
	$show_main_img = $this->_get_option_int(  $options, 2 ) ;
	$show_sub_img  = $this->_get_option_int(  $options, 3 ) ;
	$cols          = $this->_get_option_int(  $options, 4 ) ;

	$ret  = '<table border="0"><tr><td>'."\n";
	$ret .= 'dirname';
	$ret .= '</td><td>'."\n";
	$ret .= $this->_DIRNAME;
	$ret .= '<input type="hidden" name="options[0]" value="'. $this->_DIRNAME .'" />'."\n";
	$ret .= '</td></tr><tr><td>'."\n";
	$ret .= $this->_constant( 'TEXT_CATLIST_SUB' );
	$ret .= '</td><td>'."\n";
	$ret .= $this->build_form_radio( 'options[1]', $show_sub, $this->_YESNO_OPTIONS );
	$ret .= '</td></tr><tr><td>'."\n";
	$ret .= $this->_constant( 'TEXT_CATLIST_MAIN_IMG' );
	$ret .= '</td><td>'."\n";
	$ret .= $this->build_form_radio( 'options[2]', $show_main_img, $this->_YESNO_OPTIONS );
	$ret .= '</td></tr><tr><td>'."\n";
	$ret .= $this->_constant( 'TEXT_CATLIST_SUB_IMG' );
	$ret .= '</td><td>'."\n";
	$ret .= $this->build_form_radio( 'options[3]', $show_sub_img, $this->_YESNO_OPTIONS );
	$ret .= '</td></tr><tr><td>'."\n";
	$ret .= $this->_constant( 'TEXT_CATLIST_COLS' );
	$ret .= '</td><td>'."\n";
	$ret .= '<input type="text" size="4" name="options[4]" value="'. $cols .'" />'."\n";
	$ret .= '</td></tr>'."\n";
	$ret .= '</table>'."\n";

	return $ret ;
}

//---------------------------------------------------------
// tag cloud
//
// options
//   0 : dirname
//   1 : limit (100)
//---------------------------------------------------------
function tagcloud_show( $options )
{
	$limit = $this->_get_option_int(  $options, 1 ) ;

	$block = array() ;
	$block['dirname']  = $this->_DIRNAME ;
	$block['tagcloud'] = $this->_tagcloud_class->build_tagcloud( $limit );

	return $this->_assign_block( 'tagcloud', $block ) ;
}

function tagcloud_edit( $options )
{
	$limit = $this->_get_option_int( $options, 1 ) ;

	$ret  = '<table border="0"><tr><td>'."\n";
	$ret .= 'dirname';
	$ret .= '</td><td>'."\n";
	$ret .= $this->_DIRNAME;
	$ret .= '<input type="hidden" name="options[0]" value="'. $this->_DIRNAME .'" />'."\n";
	$ret .= '</td></tr><tr><td>'."\n";
	$ret .= $this->_constant( 'TEXT_TAGCLOUD_LIMIT' );
	$ret .= '</td><td>'."\n";
	$ret .= '<input type="text" size="4" name="options[1]" value="'. $limit .'" />'."\n";
	$ret .= '</td></tr>'."\n";
	$ret .= '</table>'."\n";

	return $ret ;
}

//---------------------------------------------------------
// timeline
//
// options
//   0 : dirname
//   1 : random  limit (100)
//   2 : lateset limit (10)
//   3 : height (300) px
//   4 : scale  (month)
//---------------------------------------------------------
function timeline_show( $options )
{
	$latest = $this->_get_option_int( $options, 1 ) ;
	$random = $this->_get_option_int( $options, 2 ) ;
	$height = $this->_get_option_int( $options, 3 ) ;
	$scale  = $this->_get_option(     $options, 4 ) ;

	$js      = null ;
	$element = null;

	if ( $this->_init_timeline ) {
		$item_rows = $this->_get_item_rows_timeline( $latest, $random );
		list( $photos, $photo_num ) = 
			$this->_build_photos( $options, $item_rows, false );

		if ( $photo_num > 0 ) {
			$this->_timeline_class->set_show_onload(  true );
			$tl_param = $this->_timeline_class->fetch_timeline( 
				'painter', $scale, $this->_TIMELINE_DATE, $photos );
			$js      = $tl_param['timeline_js'] ;
			$element = $tl_param['timeline_element'] ;
		}
	}

	$block = array() ;
	$block['dirname']          = $this->_DIRNAME ;
	$block['timeline_js']      = $js;
	$block['timeline_element'] = $element;
	$block['timeline_height']  = $height;

	return $this->_assign_block( 'timeline', $block ) ;
}

function timeline_edit( $options )
{
	$latest = $this->_get_option_int( $options, 1 ) ;
	$random = $this->_get_option_int( $options, 2 ) ;
	$height = $this->_get_option_int( $options, 3 ) ;
	$scale  = $this->_get_option(     $options, 4 ) ;

	$scale_options = array();
	if ( $this->_init_timeline ) {
		$scale_options = $this->_timeline_class->get_scale_options();
	}

	$ret  = '<table border="0"><tr><td>'."\n";
	$ret .= 'dirname';
	$ret .= '</td><td>'."\n";
	$ret .= $this->_DIRNAME;
	$ret .= '<input type="hidden" name="options[0]" value="'. $this->_DIRNAME .'" />'."\n";
	$ret .= '</td></tr><tr><td>'."\n";
	$ret .= $this->_constant( 'TIMELINE_LATEST' );
	$ret .= '</td><td>'."\n";
	$ret .= '<input type="text" size="4" name="options[1]" value="'. $latest .'" />'."\n";
	$ret .= '</td></tr><tr><td>'."\n";
	$ret .= $this->_constant( 'TIMELINE_RANDOM' );
	$ret .= '</td><td>'."\n";
	$ret .= '<input type="text" size="4" name="options[2]" value="'. $random .'" />'."\n";
	$ret .= "</td></tr>\n<tr><td>";
	$ret .= $this->_constant('TIMELINE_HEIGHT') ;
	$ret .= "</td><td>";
	$ret .= '<input type="text" name="options[3]" value="'. $height .'" />'."\n";
	$ret .= $this->_constant('PIXEL') ;
	$ret .= '</td></tr><tr><td>'."\n";
	$ret .= $this->_constant( 'TIMELINE_SCALE' );
	$ret .= '</td><td>'."\n";
	$ret .= $this->build_form_select( 'options[4]', $scale, $scale_options );
	$ret .= "</td></tr>\n";
	$ret .= '</table>'."\n";

	return $ret ;
}

//---------------------------------------------------------
// show common
//---------------------------------------------------------
function _top_show_common( $mode , $options )
{
	$cache_time        = $this->_get_option_int(  $options, 6 ) ;
	$disable_renderer  = $this->_get_option(      $options, 'disable_renderer', false ) ;
	$show_popbox       = $this->_get_option(      $options, 'show_popbox',      true ) ;

	$use_popbox = ( $show_popbox && $this->_cfg_use_popbox ) ? true : false ;

	list ( $show_popbox_js , $popbox_js )
		= $this->_get_popbox_js( $mode, $use_popbox );

	$template = 'db:'. $this->_DIRNAME .'_block_'. $mode .'.html';

	$tpl = new XoopsTpl();

// set cache time
	if ( $cache_time > 0 ) {
		$tpl->xoops_setCaching(2);
		$tpl->xoops_setCacheTime( $cache_time );
	}

// build block if cache time over
	if ( !$tpl->is_cached( $template ) || ($cache_time == 0) || $show_popbox_js ) {

		$block = $this->_build_block( $mode , $options );
		$block['show_popbox']    = $use_popbox ;
		$block['show_popbox_js'] = $show_popbox_js ;
		$block['popbox_js']      = $popbox_js ;

// return orinal block
		if ( $disable_renderer ) {
			return $block ;
		}

		$tpl->assign( 'block', $block );
	}

	$ret = array();
	$ret['content'] = $tpl->fetch( $template ) ;
	return $ret ;
}

function _build_block( $mode , $options )
{
	$cols = $this->_get_option_cols( $options, 5 ) ;

	$block = array(
		'dirname'          => $this->_DIRNAME ,
		'cols'             => $cols ,
		'cfg_use_pathinfo' => $this->_cfg_use_pathinfo ,
		'cfg_thumb_width'  => $this->_cfg_thumb_width ,
	);

	$item_rows = $this->_get_item_rows_top_common( $mode , $options );

	list( $photos, $photo_num ) = 
		$this->_build_photos( $options, $item_rows );

	$block['photo']     = $photos ;
	$block['photo_num'] = $photo_num ;

	list( $show_gmap, $gmap ) =
		$this->_build_gmap_block( $mode, $block['photo'], $options );

	$block['show_gmap'] = $show_gmap ;
	$block['gmap']      = $gmap ;

	return $block ;
}

function _build_photos( $options, $item_rows, $flag_amp_sanitize=true )
{
	$title_max_length  = $this->_get_option_int(  $options, 4, 20 ) ;

	if ( !is_array($item_rows) || !count($item_rows) ) {
		return array( null, 0 ) ; 
	}

// count begins from
	$count  = 1 ;
	$photos = array();

	foreach ( $item_rows as $row )
	{
		$item_id   = $row['item_id'] ;
		$title     = $row['item_title'] ;

		$cat_title = $this->_build_cat_title( $row );

		$arr = array_merge( $row, $this->_build_imgsrc( $row ) );

		$arr['photo_id']      = $item_id ;
		$arr['onclick']       = $row['item_onclick'] ;
		$arr['title']         = $title ;
		$arr['title_s']       = $this->sanitize( $title ) ;
		$arr['title_short_s'] = $this->_build_short_title( $title, $title_max_length ) ;
		$arr['cat_title_s']   = $this->sanitize( $cat_title ) ;
		$arr['hits_suffix']   = $this->_build_hits_suffix( $row['item_hits'] ) ;

		$arr['time_create']   = $row['item_time_create'];
		$arr['datetime']      = $row['item_datetime'];
		$arr['photo_uri']     = $this->_uri_class->build_photo( $item_id, $flag_amp_sanitize ) ;
		$arr['description_disp'] = $this->build_item_description( $row );

		$photos[ $count ++ ] = $arr ;
	}

	return array( $photos, ($count - 1) ) ; 
}

//---------------------------------------------------------
// edit common
//---------------------------------------------------------
function _top_edit_common( $mode, $options )
{
	$photos_num          = $this->_get_option_int(   $options, 1, 5 ) ;
	$cat_limitation      = $this->_get_option_int(   $options, 2, 0 ) ;
	$cat_limit_recursive = $this->_get_option_int(   $options, 3, 0 ) ;
	$title_max_length    = $this->_get_option_int(   $options, 4, 20 ) ;
	$cols                = $this->_get_option_cols(  $options, 5 ) ;
	$cache_time          = $this->_get_option_int(   $options, 6 ) ;

	$catselbox = $this->_get_catselbox( $cat_limitation , 1 , 'options[2]' ) ;

	$ret  = '<table border="0"><tr><td>'."\n";
	$ret .= 'dirname';
	$ret .= '</td><td>'."\n";
	$ret .= $this->_DIRNAME;
	$ret .= '<input type="hidden" name="options[0]" value="'. $this->_DIRNAME .'" />'."\n";
	$ret .= '</td></tr><tr><td>'."\n";
	$ret .= $this->_constant( 'TEXT_DISP' );
	$ret .= '</td><td>'."\n";
	$ret .= '<input type="text" size="4" name="options[1]" value="'. $photos_num .'" />'."\n";
	$ret .= '</td></tr><tr><td>'."\n";
	$ret .= $this->_constant( 'TEXT_CATLIMITATION' );
	$ret .= '</td><td>'."\n";
	$ret .= $catselbox;
	$ret .= '</td></tr><tr><td>'."\n";
	$ret .= $this->_constant( 'TEXT_CATLIMITRECURSIVE' );
	$ret .= '</td><td>'."\n";
	$ret .= $this->build_form_radio( 'options[3]', $cat_limit_recursive, $this->_YESNO_OPTIONS );
	$ret .= '</td></tr><tr><td>'."\n";
	$ret .= $this->_constant( 'TEXT_STRLENGTH' );
	$ret .= '</td><td>'."\n";
	$ret .= '<input type="text" size="6" name="options[4]" value="'. $title_max_length .'" />'."\n";
	$ret .= '</td></tr><tr><td>'."\n";
	$ret .= $this->_constant( 'TEXT_COLS' );
	$ret .= '</td><td>'."\n";
	$ret .= '<input type="text" size="2" name="options[5]" value="' .$cols .'" />'."\n";
	$ret .= '</td></tr><tr><td>'."\n";
	$ret .= $this->_constant( 'TEXT_CACHETIME' );
	$ret .= '</td><td>'."\n";
	$ret .= $this->build_form_select( 'options[6]', $cache_time, $this->_CACHE_OPTIONS );
	$ret .= "</td></tr>\n";

	if ( $this->_check_gmap( $mode ) ) {
		$ret .= $this->_top_edit_gmap( $options ) ;
	}

	$ret .= '</table>'."\n";

	return $ret;
}

function _top_edit_gmap( $options )
{
	$gmap_latitude  = $this->_get_option_float( $options, 8 );
	$gmap_longitude = $this->_get_option_float( $options, 9 );
	$gmap_zoom      = $this->_get_option_int(   $options, 10 );
	$gmap_height    = $this->_get_option_int(   $options, 11 );

	$ret  = $this->_top_edit_gmap_mode( $options );
	$ret .= "<tr><td>";
	$ret .= $this->_constant('GMAP_LATITUDE') ;
	$ret .= "</td><td>";
	$ret .= '<input type="text" name="options[8]" id="webphoto_gmap_latitude" value="'. $gmap_latitude .'" />'."\n";
	$ret .= "</td></tr>\n<tr><td>";
	$ret .= $this->_constant('GMAP_LONGITUDE') ;
	$ret .= "</td><td>";
	$ret .= '<input type="text" name="options[9]" id="webphoto_gmap_longitude" value="'. $gmap_longitude .'" />'."\n";
	$ret .= "</td></tr>\n<tr><td>";
	$ret .= $this->_constant('GMAP_ZOOM') ;
	$ret .= "</td><td>";
	$ret .= '<input type="text" name="options[10]" id="webphoto_gmap_zoom" value="'. $gmap_zoom .'" />'."\n";
	$ret .= "</td></tr>\n<tr><td>";
	$ret .= $this->_constant('GMAP_HEIGHT') ;
	$ret .= "</td><td>";
	$ret .= '<input type="text" name="options[11]" value="'. $gmap_height .'" />'."\n";
	$ret .= $this->_constant('PIXEL') ;
	$ret .= "</td></tr>\n";
	$ret .= '<tr><td colspan="2">'."\n";
	$ret .= $this->_build_gmap_iframe( 8 );
	$ret .= "</td></tr>\n";

	return $ret;
}

function _top_edit_gmap_mode( $options )
{
	$gmap_mode = $this->_get_option_int(   $options, 7 );

	$checked_0 = '' ;
	$checked_1 = '' ;
	$checked_2 = '' ;

	switch ( $gmap_mode ) 
	{
		case 1 :
			$checked_1 = $this->_CHECKED ;
			break;

		case 2 :
			$checked_2 = $this->_CHECKED ;
			break;

		case 0 :
		default :
			$checked_0 = $this->_CHECKED ;
			break;
	}

	$ret  = '<tr><td>'."\n";
	$ret .= $this->_constant('GMAP_MODE') ;
	$ret .= "</td><td>";
	$ret .= '<input type="radio" name="options[7]" value="0" '. $checked_0 .' />'."\n";
	$ret .= $this->_constant('GMAP_MODE_NONE') ;
	$ret .= '<input type="radio" name="options[7]" value="1" '. $checked_1 .' />'."\n";
	$ret .= $this->_constant('GMAP_MODE_DEFAULT') ;
	$ret .= '<input type="radio" name="options[7]" value="2" '. $checked_2 .' />'."\n";
	$ret .= $this->_constant('GMAP_MODE_SET') ;
	$ret .= "</td></tr>\n";
	return $ret;
}

function _build_gmap_iframe( $num )
{
	$src = $this->_MODULE_URL .'/index.php?fct=gmap_location&amp;block_id='. intval($this->_block_id) .'&amp;option_num='. intval($num);
	$str  = '<iframe src="'. $src .'" width="'. $this->_GMAP_WIDTH .'" height="'. $this->_GMAP_HEIGHT .'" frameborder="0" scrolling="yes" >' ;
	$str .= $this->_lang_iframe_not_support ;
	$str .= '</iframe>';
	return $str;
}

function build_form_radio( $name, $value, $options, $del="\n" )
{
	if ( !is_array($options) || !count($options) ) {
		return null;
	}

	$text = '';
	foreach ( $options as $k => $v )
	{
		$checked = '';
		if ( $value == $k ) {
			$checked = $this->_CHECKED;
		}
		$text .= '<input type="radio" name="'. $name .'" value="'. $k .'" '. $checked.' />'."\n";
		$text .= ' ';
		$text .= $v;
		$text .= ' ';
		$text .= $del;
	}
	return $text;
}

function build_form_select( $name, $value, $options, $size=1 )
{
	if ( !is_array($options) || !count($options) ) {
		return null;
	}

	$text = '<select id="'. $name.'" name="'. $name.'" size="'. $size .'">'."\n";
	foreach ( $options as $k => $v )
	{
		$selected = '';
		if ( $value == $k ) {
			$selected = $this->_SELECTED;
		}
		$text .= '<option value="'. $k .'" '. $selected .' >';
		$text .= $v;
		$text .= '</option >'."\n";
	}
	$text .= '</select>'."\n";
	return $text;
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function _get_option( $options, $num, $default=null )
{
	$val = isset( $options[ $num ] ) ? $options[ $num ] : $default;
	return $val;
}

function _get_option_int( $options, $num, $default=0 )
{
	$val = $this->_get_option( $options, $num, $default );
	return intval( $val );
}

function _get_option_float( $options, $num, $default=0 )
{
	$val = $this->_get_option( $options, $num, $default );
	return floatval( $val );
}

function _get_option_cols( $options, $num )
{
	$val = $this->_get_option_int( $options, $num, 1 );
	if ( $val <= 0 ) {
		$val = 1;
	}
	return $val;
}

function _build_imgsrc( $item_row )
{
	$img_photo_src     = '';
	$img_photo_width   = 0 ;
	$img_photo_height  = 0 ;
	$img_large_src     = '';
	$img_large_width   = 0 ;
	$img_large_height  = 0 ;
	$img_thumb_src     = '';
	$img_thumb_width   = 0 ;
	$img_thumb_height  = 0 ;

	$kind           = $item_row['item_kind'] ;
	$external_url   = $item_row['item_external_url'] ;
	$external_thumb = $item_row['item_external_thumb'];

	$is_image_kind = $this->_is_src_image_kind( $kind );

	$cont_row  = $this->get_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_CONT );
	$thumb_row = $this->get_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_THUMB );
	$large_row = $this->get_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_LARGE );
	$small_row = $this->get_file_row_by_kind( $item_row, _C_WEBPHOTO_FILE_KIND_SMALL );

	list( $cont_url, $cont_width, $cont_height ) =
		$this->build_show_file_image( $cont_row ) ;

	list( $thumb_url, $thumb_width, $thumb_height ) =
		$this->build_show_file_image( $thumb_row ) ;

	list( $large_url, $large_width, $large_height ) =
		$this->build_show_file_image( $large_row ) ;

	list( $small_url, $small_width, $small_height ) =
		$this->build_show_file_image( $small_row ) ;

	list( $icon_url, $icon_width, $icon_height ) =
		$this->build_show_icon_image( $item_row ) ;

// photo image
	if ( $cont_url && $is_image_kind ) {
		$img_photo_src    = $cont_url;
		$img_photo_width  = $cont_width ;
		$img_photo_height = $cont_height ;

	} elseif ( $external_url && $is_image_kind ) {
		$img_photo_src    = $external_url;

	} else {
		$img_photo_src = $this->_DEFAULT_ICON_SRC ;
	}

// large image
	if ( $large_url ) {
		$img_large_src    = $large_url;
		$img_large_width  = $large_width ;
		$img_large_height = $large_height ;

	} elseif ( $cont_url && $is_image_kind ) {
		$img_large_src    = $cont_url;
		$img_large_width  = $cont_width ;
		$img_large_height = $cont_height ;

	} elseif ( $external_url && $is_image_kind ) {
		$img_large_src    = $external_url;

	} else {
		$img_large_src = $this->_DEFAULT_ICON_SRC ;
	}

// thumb image
	if ( $thumb_url ) {
		$img_thumb_src    = $thumb_url ;
		$img_thumb_width  = $thumb_width ;
		$img_thumb_height = $thumb_height ;

	} elseif ( $external_thumb ) {
		$img_thumb_src    = $external_thumb ;

	} elseif ( $icon_url ) {
		$img_thumb_src    = $icon_url ;
		$img_thumb_width  = $icon_width;
		$img_thumb_height = $icon_height;

	} elseif ( $cont_url && $is_image_kind ) {
		$img_thumb_src    = $cont_url;
		$img_thumb_width  = $cont_width;
		$img_thumb_height = $cont_height;

	} elseif ( $external_url && $is_image_kind ) {
		$img_thumb_src    = $external_url ;

	} else {
		$img_thumb_src    = $this->_PIXEL_ICON_SRC;
		$img_thumb_width  = 1;
		$img_thumb_height = 1;
	}

	list( $img_thumb_width, $img_thumb_height )
		= $this->_adjust_image_thumb( $img_thumb_width, $img_thumb_height );

	$arr = array(
		'cont_url'          => $cont_url ,
		'cont_url_s'        => $this->sanitize( $cont_url ) ,
		'cont_width'        => $cont_width ,
		'cont_height'       => $cont_height ,
		'thumb_url'         => $thumb_url ,
		'thumb_url_s'       => $this->sanitize( $thumb_url ) ,
		'thumb_width'       => $thumb_width ,
		'thumb_height'      => $thumb_height ,
		'small_url'         => $small_url ,
		'small_url_s'       => $this->sanitize( $small_url ) ,
		'small_width'       => $small_width ,
		'small_height'      => $small_height ,
		'icon_url'          => $icon_url ,
		'icon_url_s'        => $this->sanitize( $icon_url ) ,
		'icon_width'        => $icon_width ,
		'icon_height'       => $icon_height ,
		'img_photo_src'     => $img_photo_src ,
		'img_photo_src_s'   => $this->sanitize( $img_photo_src ) ,
		'img_photo_width'   => $img_photo_width ,
		'img_photo_height'  => $img_photo_height ,
		'img_large_src'     => $img_large_src ,
		'img_large_src_s'   => $this->sanitize( $img_large_src ) ,
		'img_large_width'   => $img_large_width ,
		'img_large_height'  => $img_large_height ,
		'img_thumb_src'     => $img_thumb_src ,
		'img_thumb_src_s'   => $this->sanitize( $img_thumb_src ) ,
		'img_thumb_width'   => $img_thumb_width ,
		'img_thumb_height'  => $img_thumb_height ,
	);
	return $arr;

}

function _is_src_image_kind( $kind )
{
	if ( $kind == _C_WEBPHOTO_ITEM_KIND_IMAGE ) {
		return true;
	}
	if ( $kind == _C_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE ) {
		return true;
	}
	return false;
}

function _build_cat_title( $item_row )
{
	$cat_id  = $item_row['item_cat_id'];
	$cat_row = $this->get_cat_row_by_id( $cat_id );
	if ( isset( $cat_row['cat_title'] ) ) {
		return  $cat_row['cat_title'] ;
	}
	return null;
}

function _build_short_title( $str, $max )
{
	if ( $max == 0 ) {
		$str = '';
	} elseif ( strlen( $str ) >= $max ) {
		$str = $this->_shorten_text( $str , $max - 1 );
	}
	return $this->sanitize( $str );
}

function _build_hits_suffix( $hits )
{
	$val = $hits > 1 ? 'hits' : 'hit' ;
	return $val;
}

function _adjust_image_thumb( $width, $height )
{
	return $this->_adjust_image_size( 
		$width, $height, $this->_cfg_thumb_width, $this->_cfg_thumb_height );
}

function _adjust_image_size( $width, $height, $max_width, $max_height )
{
	if ( $width > $max_width ) {
		$mag    = $max_width / $width;
		$width  = $max_width;
		$height = $height * $mag;
	}

	if ( $height > $max_height ) {
		$mag    = $max_height / $height;
		$height = $max_height;
		$width  = $width * $mag;
	}

	return array( intval($width), intval($height) );
}

//---------------------------------------------------------
// gmap
//---------------------------------------------------------
function _build_gmap_block( $mode, $photos, $options )
{
	if ( ! $this->_check_gmap( $mode ) ) {
		return array( false, null );	
	}

	$gmap_mode  = $this->_get_option_int(   $options, 7 );
	$latitude   = $this->_get_option_float( $options, 8 );
	$longitude  = $this->_get_option_float( $options, 9 );
	$zoom       = $this->_get_option_int(   $options, 10 );
	$height     = $this->_get_option_int(   $options, 11 );

	$photo_arr = array();
	foreach( $photos as $photo ) 
	{
		if ( ! $this->_exist_gmap( $photo ) ) {
			continue;
		}

		$temp = $photo;
		$temp['gmap_latitude']  = $photo['item_gmap_latitude'] ;
		$temp['gmap_longitude'] = $photo['item_gmap_longitude'] ;
		$temp['gmap_info']      = $this->_gmap_info_class->build_info( $photo );
		$photo_arr[] = $temp;
	}

	if ( !is_array($photo_arr) || !count($photo_arr) ) {
		return array( false, null );
	}

// google map
	$param = array(
		'block_mode'        => $mode ,
		'photos'            => $photo_arr ,
		'apikey'            => $this->_cfg_gmap_apikey ,
		'default_latitude'  => $this->_cfg_gmap_latitude ,
		'default_longitude' => $this->_cfg_gmap_longitude ,
		'default_zoom'      => $this->_cfg_gmap_zoom ,
		'gmap_mode'         => $gmap_mode ,
		'option_latitude'   => $latitude ,
		'option_longitude'  => $longitude ,
		'option_zoom'       => $zoom ,
		'height'            => $height ,
	);

	return $this->_gmap_block_class->build_gmap( $param );
}

function _exist_gmap( $photo )
{
	if ( $photo['item_gmap_latitude'] != 0 ) {
		return true;
	}
	if ( $photo['item_gmap_longitude'] != 0 ) {
		return true;
	}
	if ( $photo['item_gmap_zoom'] != 0 ) {
		return true;
	}
	return false ;
}

function _check_gmap( $mode )
{
	if ( ! $this->_cfg_gmap_apikey ) {
		return false;
	}

	switch( $mode )
	{
		case 'topnews_p':
		case 'tophits_p':
		case 'rphoto':
			return true;
			break;

		case 'tophits':
		case 'topnews':
		default:
			break;
	}
	return false ;
}

//---------------------------------------------------------
// langauge
//---------------------------------------------------------
function _constant( $name )
{
	return constant( $this->_constant_name( $name ) );
}

function _constant_name( $name )
{
	return strtoupper( '_BL_' . $this->_DIRNAME . '_' . $name );
}

//---------------------------------------------------------
// multibyte class
//---------------------------------------------------------
function _shorten_text( $str, $max )
{
	return $this->_multibyte_class->sub_str( $str, 0, $max ) .'...';;
}

//---------------------------------------------------------
// database handler
//---------------------------------------------------------
function _get_item_rows_timeline( $latest, $random )
{
	$param = array(
		'cat_limitation'      => 0 ,
		'cat_limit_recursive' => false ,
	);

	$latest_rows = $this->get_item_rows_for_block( 
		$param, $this->_ORDERBY_LATEST, $latest, $this->_OFFSET_ZERO, $this->_KEY_TRUE );

	$random_rows = $this->get_item_rows_for_block( 
		$param, $this->_ORDERBY_RANDOM, $random, $this->_OFFSET_ZERO, $this->_KEY_TRUE );

	return $this->_utility_class->array_merge_unique( $random_rows, $latest_rows, $this->_KEY_NAME );
}

function _get_item_rows_top_common( $mode, $options )
{
	$photos_num          = $this->_get_option_int(  $options, 1, 5 ) ;
	$cat_limitation      = $this->_get_option_int(  $options, 2, 0 ) ;
	$cat_limit_recursive = $this->_get_option_int(  $options, 3, 0 ) ;

	$param = array(
		'cat_limitation'      => $cat_limitation ,
		'cat_limit_recursive' => $cat_limit_recursive,
	);

	switch( $mode )
	{
		case 'tophits':
		case 'tophits_p':
			$orderby = 'item_hits DESC, item_id DESC';
			break;

		case 'rphoto':
			$orderby = 'rand()';
			break;

		case 'topnews':
		case 'topnews_p':
		default:
			$orderby = 'item_time_update DESC, item_id DESC';
			break;
	}

	return $this->get_item_rows_for_block( $param, $orderby, $photos_num );
}

// use in public.php
function build_where_block_cat_limitation( $param )
{
	$cat_limitation      = $param['cat_limitation'];
	$cat_limit_recursive = $param['cat_limit_recursive'];

// Category limitation
	$where = '' ;
	if ( $cat_limitation > 0 ) {
		if ( $cat_limit_recursive ) {

// fatal error: Call to undefined method get_cat_parent_all_child_id()
			$id_array = $this->_catlist_class->get_cat_parent_all_child_id_by_id( $cat_limitation );

			$str = $this->array_to_str( $id_array, ',' );
			if ( $str ) {
				$where = ' item_cat_id IN ('. $str .') ';
			}

		} else {
			$where = 'item_cat_id='. intval($cat_limitation) ;
		}

	}

	return $where ;
}

function _get_catselbox( $preset_id=0, $none=0, $sel_name='', $onchange='' )
{
	$table_cat = $this->prefix_dirname( 'cat' ) ;

// BUG: cannot select category
	$cattree = new XoopsTree( $table_cat , 'cat_id' , 'cat_pid' ) ;

	ob_start() ;
	$cattree->makeMySelBox( 'cat_title', 'cat_title', $preset_id, $none, $sel_name, $onchange ) ;
	$catselbox = ob_get_contents() ;
	ob_end_clean() ;

	return $catselbox;
}

//---------------------------------------------------------
// xoops header class
//---------------------------------------------------------
function _get_popbox_js( $mode, $flag_popbox )
{
	$show      = false ;
	$popbox_js = null ;

	switch( $mode )
	{
		case 'topnews_p':
		case 'tophits_p':
		case 'rphoto':
			break;

		case 'tophits':
		case 'topnews':
		default:
			return array( $show, $popbox_js );
			break;
	}

	if ( ! $flag_popbox ) {
		return array( $show, $popbox_js );
	}

	$popbox_js = $this->_header_class->assign_or_get_popbox_js( 
		$this->_constant( 'POPBOX_REVERT' ) );

	if ( empty($popbox_js) ) {
		return array( $show, $popbox_js );
	}

	$show = true;
	return array( $show , $popbox_js );
}

//---------------------------------------------------------
// xoops_config
//---------------------------------------------------------
function _init_xoops_config_for_block( $dirname )
{
	$config_handler =& webphoto_inc_config::getSingleton( $dirname );

	$this->_cfg_use_pathinfo   = $config_handler->get_by_name( 'use_pathinfo' );
	$this->_cfg_use_popbox     = $config_handler->get_by_name( 'use_popbox' );
	$this->_cfg_thumb_width    = $config_handler->get_by_name( 'thumb_width' );
	$this->_cfg_thumb_height   = $config_handler->get_by_name( 'thumb_height' );
	$this->_cfg_cat_main_width = $config_handler->get_by_name( 'cat_main_width' );
	$this->_cfg_cat_sub_width  = $config_handler->get_by_name( 'cat_sub_width' );
	$this->_cfg_gmap_apikey    = $config_handler->get_by_name( 'gmap_apikey' );
	$this->_cfg_gmap_latitude  = $config_handler->get_by_name( 'gmap_latitude' );
	$this->_cfg_gmap_longitude = $config_handler->get_by_name( 'gmap_longitude' );
	$this->_cfg_gmap_zoom      = $config_handler->get_by_name( 'gmap_zoom' );
	$this->_cfg_timeline_dirname = $config_handler->get_by_name('timeline_dirname');

}

// --- class end ---
}

?>