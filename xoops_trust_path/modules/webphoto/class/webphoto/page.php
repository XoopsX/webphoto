<?php
// $Id: page.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2009-04-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_photo_public
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_page
//=========================================================
class webphoto_page
{
	var $_xoops_class ;
	var $_utility_class ;
	var $_config_class ;
	var $_perm_class ;
	var $_public_class ;
	var $_timeline_class ;

	var $_cfg_file_dir;
	var $_cfg_is_set_mail;
	var $_cfg_uploads_path;

	var $_has_mail;
	var $_has_file;

	var $_DIRNAME ;
	var $_TRUST_DIRNAME ;

// check show
	var $_USE_BOX_JS = true;

	var $_ARRAY_DENY_MENU         = array( 'map','timeline' );
	var $_ARRAY_DENY_SEARCH       = array( 'map','timeline' );
	var $_ARRAY_DENY_CATLIST      = array( 'map','timeline' );
	var $_ARRAY_DENY_TAGCLOUD     = array( 'map','timeline' );
	var $_ARRAY_DENY_GMAP         = array( 'timeline' );
	var $_ARRAY_DENY_TIMELINE     = array( 'map' );
	var $_ARRAY_DENY_DESC         = array();
	var $_ARRAY_DENY_NOTIFICATION = array();
	var $_ARRAY_DENY_NAVI         = array( 'map','timeline','random' );	// except random
	var $_ARRAY_DENY_NAVI_SORT    = array( 'map','timeline','random' );
	var $_ARRAY_DENY_QR           = array( 'map','timeline' );

	var $_ARRAY_ALLOW_MENU         = '*' ;	// all
	var $_ARRAY_ALLOW_SEARCH       = '*' ;	// all
	var $_ARRAY_ALLOW_CATLIST      = '*' ;	// all
	var $_ARRAY_ALLOW_TAGCLOUD     = '*' ;	// all
	var $_ARRAY_ALLOW_GMAP         = '*' ;	// all
	var $_ARRAY_ALLOW_TIMELINE     = '*' ;	// all
	var $_ARRAY_ALLOW_DESC         = array( 'latest' );
	var $_ARRAY_ALLOW_NOTIFICATION = array( 'latest' );
	var $_ARRAY_ALLOW_NAVI         = '*';	// all
	var $_ARRAY_ALLOW_NAVI_SORT    = '*';	// all
	var $_ARRAY_ALLOW_QR           = '*';	// all

	var $_ARRAY_CHECKSORT_NAVI     = array();

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_page( $dirname, $trust_dirname )
{
	$this->_DIRNAME       = $dirname ;
	$this->_TRUST_DIRNAME = $trust_dirname;

	$this->_init_d3_language( $dirname, $trust_dirname );

	$this->_xoops_class    =& webphoto_xoops_base::getInstance();
	$this->_utility_class  =& webphoto_lib_utility::getInstance();
	$this->_config_class   =& webphoto_config::getInstance( $dirname );
	$this->_timeline_class =& webphoto_inc_timeline::getSingleton( $dirname );

	$this->_perm_class     
		=& webphoto_permission::getInstance( $dirname, $trust_dirname );
	$this->_public_class   
		=& webphoto_photo_public::getInstance( $dirname, $trust_dirname );

	$this->_cfg_file_dir     = $this->_config_class->get_by_name('file_dir') ;
	$this->_cfg_is_set_mail  = $this->_config_class->is_set_mail() ;
	$this->_cfg_uploads_path = $this->_config_class->get_uploads_path();
	
	$this->_has_mail        = $this->_perm_class->has_mail() ;
	$this->_has_file        = $this->_perm_class->has_file() ;
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_page( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// build main param
//---------------------------------------------------------
function build_main_param( $mode=null, $cat_id=0 )
{
	$qrs_path = $this->_cfg_uploads_path.'/qrs' ;
	$qrs_url  = XOOPS_URL . $qrs_path ;

	$param = array(
		'mode'            => $mode,
		'cfg_is_set_mail' => $this->_cfg_is_set_mail ,
		'qrs_path'        => $qrs_path ,
		'qrs_url'         => $qrs_url ,
		'use_box_js'      => $this->_USE_BOX_JS ,
		'show_menu'       => $this->check_show_common( $mode, 'menu' ) ,
		'show_search'     => $this->check_show_common( $mode, 'search' ) ,
		'show_qr'         => $this->check_show_common( $mode, 'qr' ) ,
		'is_taf_module'   => $this->get_is_taf_module() ,
	);

	$arr = array_merge( 
		$param, 
		$this->build_base_param(), 
		$this->build_menu_param( $cat_id ), 
		$this->build_footer_param() ,
		$this->get_lang_array()
	);
	return $arr;
}

function build_menu_param( $cat_id )
{
	$total = $this->get_public_total();

	$arr = array(
		'cat_id'             => $cat_id ,
		'photo_total_all'    => $total ,
		'lang_thereare'      => $this->build_lang_thereare( $total ) ,
		'show_menu_mail'     => $this->get_show_menu_mail() ,
		'show_menu_file'     => $this->get_show_menu_file() ,
		'show_menu_map'      => $this->get_show_menu_map() ,
		'show_menu_timeline' => $this->timeline_check_exist() ,
	);

	return $arr;
}

function build_base_param()
{
	$arr = array(
		'mydirname'        => $this->_DIRNAME ,
		'cfg_is_set_mail'  => $this->_cfg_is_set_mail ,
		'has_rateview'     => $this->_perm_class->has_rateview() ,
		'has_ratevote'     => $this->_perm_class->has_ratevote() ,
		'has_tellafriend'  => $this->_perm_class->has_tellafriend() ,
		'has_insertable'   => $this->_perm_class->has_insertable(),
		'has_mail'         => $this->_has_mail ,
		'has_file'         => $this->_has_file ,

// for XOOPS 2.0.18
		'xoops_dirname'    => $this->_DIRNAME ,
		'xoops_modulename' => $this->xoops_module_name( 's' ) ,
	);

// config
	$config_array = $this->get_config_array();
	foreach ( $config_array as $k => $v ) {
		$arr[ 'cfg_'.$k ] = $v ;
	}

	return $arr;
}

function build_footer_param()
{
	$arr = array(
		'is_module_admin' => $this->xoops_is_module_admin() ,
		'execution_time'  => $this->get_execution_time() ,
		'memory_usage'    => $this->get_memory_usage() ,
		'happy_linux_url' => $this->get_happy_linux_url() ,
	);
	return $arr;
}

function build_lang_thereare( $total_all )
{
	return sprintf( $this->get_constant('S_THEREARE') , $total_all ) ;
}

function get_show_menu_mail()
{
	$show = ( $this->_cfg_is_set_mail && $this->_has_mail );
	return $show;
}

function get_show_menu_file()
{
	$show = ( $this->_cfg_file_dir && $this->_has_file );
	return $show;
}

function get_show_menu_map()
{
	$gmap_apikey = $this->_config_class->get_by_name('gmap_apikey');
	$ret = empty( $gmap_apikey ) ? false : true ;
	return $ret;
}

function get_is_taf_module()
{
	$file = XOOPS_ROOT_PATH .'/modules/tellafriend/index.php';
	if ( file_exists($file) ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// show JavaScript
//---------------------------------------------------------
function add_show_js_windows( $param )
{
	$use_box_js    = isset($param['use_box_js'])    ? (bool)$param['use_box_js']    : false ;
	$show_gmap     = isset($param['show_gmap'])     ? (bool)$param['show_gmap']     : false ;
	$show_timeline = isset($param['show_timeline']) ? (bool)$param['show_timeline'] : false ;

	$show_js_window  = false;
	$show_js_boxlist = false;
	$show_js_load    = false;
	$show_js_unload  = false;
	$js_load         = null;

	if ( $use_box_js && $show_gmap && $show_timeline ) {
		$show_js_window  = true;
		$show_js_boxlist = true;
		$show_js_load    = true;
		$show_js_unload  = true;
		$js_load = 'webphoto_box_gmap_timeline_init';

	} elseif ( $use_box_js && $show_gmap ) {
		$show_js_window  = true;
		$show_js_boxlist = true;
		$show_js_load    = true;
		$show_js_unload  = true;
		$js_load = 'webphoto_box_gmap_init';

	} elseif ( $use_box_js && $show_timeline ) {
		$show_js_window  = true;
		$show_js_boxlist = true;
		$show_js_load    = true;
		$js_load = 'webphoto_box_timeline_init';

	} elseif ( $show_gmap && $show_timeline ) {
		$show_js_window  = true;
		$show_js_load    = true;
		$show_js_unload  = true;
		$js_load = 'webphoto_gmap_timeline_init';

	} elseif ( $use_box_js ) {
		$show_js_window  = true;
		$show_js_boxlist = true;
		$js_load = 'webphoto_box_init';

	} elseif ( $show_gmap ) {
		$show_js_window  = true;
		$show_js_load    = true;
		$show_js_unload  = true;
		$js_load = 'webphoto_gmap_init';

	} elseif ( $show_timeline ) {
		$show_js_window  = true;
		$show_js_load    = true;
		$js_load = 'webphoto_timeline_init';
	}

	$boxlist = $this->build_box_list( $param );
	$param['box_list']   = $boxlist;
	$param['js_boxlist'] = $boxlist;

	$param['show_js_window']  = $show_js_window;
	$param['show_js_boxlist'] = $show_js_boxlist;
	$param['show_js_load']    = $show_js_load;
	$param['show_js_unload']  = $show_js_unload;
	$param['js_load']         = $js_load;
	$param['js_unload']       = 'GUnload';

	return $param;
}

function build_box_list( $param )
{
	$arr = array();
	if ( isset($param['use_box_js']) && $param['use_box_js'] ) {
		if ( isset($param['show_catlist']) && $param['show_catlist'] ) {
			$arr[] = 'webphoto_box_catlist';
		}
		if ( isset($param['show_tagcloud']) && $param['show_tagcloud'] ) {
			$arr[] = 'webphoto_box_tagcloud';
		}
		if ( isset($param['show_gmap']) && $param['show_gmap'] ) {
			$arr[] = 'webphoto_box_gmap';
		}
		if ( isset($param['show_timeline']) && $param['show_timeline'] ) {
			$arr[] = 'webphoto_box_timeline';
		}
		if ( isset($param['show_photo']) && $param['show_photo'] ) {
			$arr[] = 'webphoto_box_photo';
		}
		if ( count($arr) ) {
			return implode( ',', $arr );
		}
	}
	return '';
}

//---------------------------------------------------------
// check show
//---------------------------------------------------------
function check_show_navi( $mode, $sort )
{
	if ( $this->check_show_common( $mode, 'navi' ) ) {
		if ( $this->is_in_array( $mode, $this->_ARRAY_CHECKSORT_NAVI ) ) {
			if ( $this->check_show_navi_sort( $sort ) ) {
				return true;
			}
		} else {
			return true;
		}
	}
	return false;
}

function check_show_common( $mode, $name )
{
	$allow_name = strtoupper( '_ARRAY_ALLOW_'.$name );
	$deny_name  = strtoupper( '_ARRAY_DENY_'. $name );
	$allow_arr  = $this->$allow_name;
	$deny_arr   = $this->$deny_name;

	if ( $this->is_in_array( $mode, $deny_arr ) ) {
		return false;
	}
	if ( $this->is_in_array( $mode, $allow_arr ) ) {
		return true;
	}
	return false;
}

function is_in_array( $needle, $haystack )
{
	if ( is_array($haystack) ) {
		if ( in_array( $needle, $haystack ) ) {
			return true;
		}
	} else {
		if ( $haystack == '*' ) {
			return true;
		}
	}
	return false;
}

//---------------------------------------------------------
// preload
//---------------------------------------------------------
function init_preload()
{
	$this->_preload_class =& webphoto_d3_preload::getInstance();
	$this->_preload_class->init( $this->_DIRNAME , $this->_TRUST_DIRNAME );

	$this->preload_constant();
}

function preload_constant()
{
	$arr = $this->_preload_class->get_preload_const_array();

	if ( !is_array($arr) || !count($arr) ) {
		return true;	// no action
	}

	foreach( $arr as $k => $v )
	{
		$local_name = strtoupper( '_' . $k );

// array type
		if ( strpos($k, 'array_') === 0 ) {
			$temp = $this->str_to_array( $v, '|' );
			if ( is_array($temp) && count($temp) ) {
				$this->$local_name = $temp;
			}

// string type
		} else {
			$this->$local_name = $v;
		}
	}

}

//---------------------------------------------------------
// d3 language
//---------------------------------------------------------
function _init_d3_language( $dirname, $trust_dirname )
{
	$this->_language_class =& webphoto_d3_language::getInstance();
	$this->_language_class->init( $dirname , $trust_dirname );
}

function get_lang_array()
{
	return $this->_language_class->get_lang_array();
}

function get_constant( $name )
{
	return $this->_language_class->get_constant( $name );
}

//---------------------------------------------------------
// xoops class
//---------------------------------------------------------
function xoops_is_module_admin()
{
	return $this->_xoops_class->get_my_user_is_module_admin();
}

function xoops_is_japanese()
{
	return $this->_xoops_class->is_japanese( _C_WEBPHOTO_JPAPANESE ) ;
}

function xoops_module_name( $format='s' )
{
	return $this->_xoops_class->get_my_module_name( $format );
}

//---------------------------------------------------------
// config class
//---------------------------------------------------------
function get_config_array()
{
	return $this->_config_class->get_config_array();
}

//---------------------------------------------------------
// public class
//---------------------------------------------------------
function get_public_total()
{
	return $this->_public_class->get_count();
}

//---------------------------------------------------------
// timeline class
//---------------------------------------------------------
function timeline_check_exist()
{
	$timeline_dirname = $this->_config_class->get_by_name('timeline_dirname');
	return $this->_timeline_class->check_exist( $timeline_dirname );
}

//---------------------------------------------------------
// utility class
//---------------------------------------------------------
function str_to_array( $str, $pattern )
{
	return $this->_utility_class->str_to_array( $str, $pattern );
}

function get_execution_time()
{
	return $this->_utility_class->get_execution_time( WEBPHOTO_TIME_START ) ;
}

function get_memory_usage()
{
	return $this->_utility_class->get_memory_usage() ;
}

function get_happy_linux_url()
{
	return $this->_utility_class->get_happy_linux_url( $this->xoops_is_japanese() ) ;
}

//---------------------------------------------------------
// get param
//---------------------------------------------------------
function get_use_box_js()
{
	return $this->_USE_BOX_JS;
}

// --- class end ---
}

?>