<?php
// $Id: blocks.php,v 1.1 2008/06/21 12:22:25 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_blocks
//=========================================================
class webphoto_inc_blocks extends webphoto_inc_handler
{
	var $_multibyte_class;

	var $_cfg_use_popbox  = false;
	var $_normal_exts     = null;

	var $_URL_DEFUALT_ICON;
	var $_URL_PIXEL_GIF;

	var $_CHECKED  = ' checked="checked" ';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_blocks()
{
	$this->webphoto_inc_handler();

	$this->_multibyte_class =& webphoto_lib_multibyte::getInstance();

	$this->_normal_exts = explode('|', _C_WEBPHOTO_IMAGE_EXTS);
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_blocks();
	}
	return $instance;
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
//---------------------------------------------------------
function topnews_show( $options )
{
	$this->_init( $options );
	return $this->_top_show_common( 'topnews', $options );
}

function topnews_p_show( $options )
{
	$this->_init( $options );
	return $this->_top_show_common( 'topnews_p', $options );
}

function topnews_edit( $options )
{
	$this->_init( $options );
	return $this->_top_edit_common( $options ) ;
}

//---------------------------------------------------------
// tophits
//---------------------------------------------------------
function tophits_show( $options )
{
	$this->_init( $options );
	return $this->_top_show_common( 'tophits', $options );
}

function tophits_p_show( $options )
{
	$this->_init( $options );
	return $this->_top_show_common( 'tophits_p', $options );
}

function tophits_edit( $options )
{
	$this->_init( $options );
	return $this->_top_edit_common( $options ) ;
}

//---------------------------------------------------------
// rphoto
//---------------------------------------------------------
function rphoto_show( $options )
{
	$this->_init( $options );
	return $this->_top_show_common( 'rphoto', $options );
}

function rphoto_edit( $options )
{
	$this->_init( $options );
	return $this->_top_edit_common( $options ) ;
}

//---------------------------------------------------------
// common
//---------------------------------------------------------
function _init( $options )
{
	$dirname = $this->_get_option( $options, 0, null ) ;

	$this->init_handler( $dirname );
	$this->_init_xoops_config( $dirname );

	$ICONS_URL               = XOOPS_URL  .'/modules/' .$dirname .'/images/icons';
	$this->_URL_DEFUALT_ICON = $ICONS_URL .'/default.gif';
	$this->_URL_PIXEL_GIF    = $ICONS_URL .'/pixel_trans.gif';
}

function _top_show_common( $mode , $options )
{
	$title_max_length  = $this->_get_option_int(  $options, 4, 20 ) ;
	$cols              = $this->_get_option_cols( $options, 5 ) ;
	$disable_renderer  = $this->_get_option(      $options, 'disable_renderer', false ) ;
	$show_popbox       = $this->_get_option(      $options, 'show_popbox',      true ) ;

	$use_popbox = ( $show_popbox && $this->_cfg_use_popbox ) ? true : false ;

	$this->_assign_xoops_header( $mode, $use_popbox );

	$template = 'db:'. $this->_DIRNAME .'_block_'. $mode .'.html';

	$block = array() ;
	$count = 1 ;

	$rows = $this->_get_photo_rows_top_common( $mode , $options );

	if ( !is_array($rows) || !count($rows) ) { return $block; }

	foreach ( $rows as $row )
	{
		$arr = array_merge( $row, $this->_build_imgsrc( $row ) );

		$arr['title_s']       = $this->sanitize( $row['photo_title'] ) ;
		$arr['title_short_s'] = $this->_build_short_title( $row['photo_title'], $title_max_length ) ;
		$arr['hits_suffix']   = $this->_build_hits_suffix( $row['photo_hits'] ) ;

		$block['photo'][$count++] = $arr ;
	}

	$block['dirname']     = $this->_DIRNAME ;
	$block['cols']        = $cols ;
	$block['show_popbox'] = $use_popbox ;

	if ( $disable_renderer ) {
		return $block ;
	}

	return $this->_assign_template( $block, $template );
}

function _top_edit_common( $options )
{
	$photos_num          = $this->_get_option_int(  $options, 1, 5 ) ;
	$cat_limitation      = $this->_get_option_int(  $options, 2, 0 ) ;
	$cat_limit_recursive = $this->_get_option_int(  $options, 3, 0 ) ;
	$title_max_length    = $this->_get_option_int(  $options, 4, 20 ) ;
	$cols                = $this->_get_option_cols( $options, 5 ) ;

	$catselbox = $this->_get_catselbox( $cat_limitation , 1 , 'options[2]' ) ;

	$recursive_checked_yes = '';
	$recursive_checked_no  = '';

	if ( $cat_limit_recursive ) {
		$recursive_checked_yes = $this->_CHECKED ;
	} else {
		$recursive_checked_no  = $this->_CHECKED ;
	}

	$ret  = 'dirname  &nbsp ';
	$ret .= $this->_DIRNAME."\n";
	$ret .= '<input type="hidden" name="options[0]" value="'. $this->_DIRNAME .'" />'."\n";
	$ret .= "<br />\n";
	$ret .= $this->_constatnt( 'TEXT_DISP' )." &nbsp";
	$ret .= '<input type="text" size="4" name="options[1]" value="'. $photos_num .'" style="text-align:right;" />'."\n";
	$ret .= "<br />\n";
	$ret .= $this->_constatnt( 'TEXT_CATLIMITATION' ) .' &nbsp; '. $catselbox ."\n";
	$ret .= $this->_constatnt( 'TEXT_CATLIMITRECURSIVE' )."\n";
	$ret .= '<input type="radio" name="options[3]" value="1" '. $recursive_checked_yes .' />'._YES."\n";
	$ret .= '<input type="radio" name="options[3]" value="0" '. $recursive_checked_no .' />'._NO."\n";
	$ret .= "<br />\n";
	$ret .= $this->_constatnt( 'TEXT_STRLENGTH' )." &nbsp; \n";
	$ret .= '<input type="text" size="6" name="options[4]" value="'. $title_max_length .'" style="text-align:right;" />'."\n";
	$ret .= "<br />\n";
	$ret .= $this->_constatnt( 'TEXT_COLS' )." &nbsp; \n";
	$ret .= '<input type="text" size="2" name="options[5]" value="' .$cols .'" style="text-align:right;" />'."\n";
	$ret .= "<br />\n";

	return $ret;
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

function _get_option_cols( $options, $num )
{
	$val = $this->_get_option_int( $options, $num, 1 );
	if ( $val <= 0 ) {
		$val = 1;
	}
	return $val;
}

function _build_imgsrc( $row )
{
	extract( $row ) ;

	$ahref_file   = '';
	$imgsrc_photo = '';
	$imgsrc_thumb = '';
	$is_normal_image = false ;

	$photo_file_url_s  = $this->sanitize( $photo_file_url );
	$photo_cont_url_s  = $this->sanitize( $photo_cont_url );
	$photo_thumb_url_s = $this->sanitize( $photo_thumb_url );
	$photo_width       = intval($photo_cont_width);
	$photo_height      = intval($photo_cont_height);
	$middle_width      = intval($photo_middle_width);
	$middle_height     = intval($photo_middle_height);
	$thumb_width       = intval($photo_thumb_width);
	$thumb_height      = intval($photo_thumb_height);

// normal exts
	if ( $photo_cont_url_s && $photo_thumb_url_s ) {
		$ahref_file   = $photo_cont_url_s;
		$imgsrc_photo = $photo_cont_url_s;
		$imgsrc_thumb = $photo_thumb_url_s;

// no thumbnail
	} elseif ( $photo_cont_url_s ) {
		$ahref_file   = $photo_cont_url_s;
		$imgsrc_photo = $photo_cont_url_s;
		$imgsrc_thumb = $photo_cont_url_s;

// icon gif (not normal exts)
	} elseif ( $photo_thumb_url_s ) {
		$ahref_file   = $photo_file_url_s;
		$imgsrc_photo = $photo_thumb_url_s;
		$imgsrc_thumb = $photo_thumb_url_s;

	} else {
		$ahref_file   = $photo_file_url_s;
		$imgsrc_photo = $this->_URL_DEFUALT_ICON;
		$imgsrc_thumb = $this->_URL_PIXEL_GIF;
		$thumb_width  = 1;
		$thumb_height = 1;
	}

	if ( $photo_cont_url_s && $this->_is_normal_ext( $photo_cont_ext ) ) {
		$is_normal_image = true ;
	}

	$arr = array(
		'ahref_file'       => $ahref_file ,
		'imgsrc_thumb'     => $imgsrc_thumb ,
		'imgsrc_photo'     => $imgsrc_photo ,
		'photo_width'      => $photo_width ,
		'photo_height'     => $photo_height ,
		'middle_width'     => $middle_width ,
		'middle_height'    => $middle_height ,
		'thumb_width'      => $thumb_width ,
		'thumb_height'     => $thumb_height ,
		'is_normal_image'  => $is_normal_image ,
	);
	return $arr;

}

function _is_normal_ext( $ext )
{
	if ( in_array( strtolower($ext) , $this->_normal_exts ) ) {
		return true;
	}
	return false;
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

function _assign_template( $block, $template )
{
	$tpl =& new XoopsTpl() ;
	$tpl->assign( 'block' , $block ) ;
	$ret = array();
	$ret['content'] = $tpl->fetch( $template ) ;
	return $ret ;
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
function _get_photo_rows_top_common( $mode, $options )
{
	$photos_num          = $this->_get_option_int(  $options, 1, 5 ) ;
	$cat_limitation      = $this->_get_option_int(  $options, 2, 0 ) ;
	$cat_limit_recursive = $this->_get_option_int(  $options, 3, 0 ) ;

	switch( $mode )
	{
		case 'tophits':
		case 'tophits_p':
			$orderby = 'p.photo_hits DESC, p.photo_id DESC';
			break;

		case 'rphoto':
			$orderby = 'rand()';
			break;

		case 'topnews':
		case 'topnews_p':
		default:
			$orderby = 'p.photo_time_update DESC, p.photo_id DESC';
			break;
	}

	$table_photo = $this->prefix_dirname( 'photo' ) ;
	$table_cat   = $this->prefix_dirname( 'cat' ) ;

	// Category limitation
	$where = '' ;
	if( $cat_limitation ) {
		if( $cat_limit_recursive ) {
			$cattree = new XoopsTree( $table_cat , "cat_id" , "pid" ) ;
			$children = $cattree->getAllChildId( $cat_limitation ) ;

			$where = "p.cat_id IN (" ;
			foreach( $children as $child ) {
				$where .= "$child," ;
			}
			$where .= "$cat_limitation)" ;

		} else {
			$where = "p.cat_id='$cat_limitation'" ;
		}

	}

	$sql  = 'SELECT p.* , c.* ';
	$sql .= 'FROM '. $table_photo .' p ';
	$sql .= 'LEFT JOIN '. $table_cat .' c ';
	$sql .= 'ON p.photo_cat_id = c.cat_id ';
	$sql .= 'WHERE p.photo_status > 0 ';
	if ( $where ) {
		$sql .= 'AND '. $where;
	}
	$sql .= ' ORDER BY '.$orderby;

	return $this->get_rows_by_sql( $sql, $photos_num );
}

function _get_catselbox( $preset_id=0, $none=0, $sel_name="", $onchange="" )
{
	$table_cat = $this->prefix_dirname( 'cat' ) ;

	$cattree = new XoopsTree( $table_cat , "cat_id" , "pid" ) ;

	ob_start() ;
	$cattree->makeMySelBox( 'cat_title', 'cat_title', $preset_id, $none, $sel_name, $onchange ) ;
	$catselbox = ob_get_contents() ;
	ob_end_clean() ;

	return $catselbox;
}

//---------------------------------------------------------
// xoops header class
//---------------------------------------------------------
function _assign_xoops_header( $mode, $show_popbox )
{
	switch( $mode )
	{
		case 'topnews_p':
		case 'tophits_p':
		case 'rphoto':
			$header_class =& webphoto_inc_xoops_header::getInstance();
			$header_class->assign_for_block( $this->_DIRNAME, $show_popbox, null );
			break;

		default:
			break;
	}

}

//---------------------------------------------------------
// xoops_config
//---------------------------------------------------------
function _init_xoops_config( $dirname )
{
	$config_handler =& webphoto_inc_config::getInstance();
	$config_handler->init( $dirname );

	$this->_cfg_use_popbox = $config_handler->get_by_name('use_popbox');
}

// --- class end ---
}

?>