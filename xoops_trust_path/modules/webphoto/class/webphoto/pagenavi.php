<?php
// $Id: pagenavi.php,v 1.1 2010/01/25 10:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2010-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_pagenavi
//=========================================================
class webphoto_pagenavi extends webphoto_base_this
{
	var $_pagenavi_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_pagenavi( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );

	$this->_pagenavi_class =& webphoto_lib_pagenavi::getInstance();
	$this->_pagenavi_class->set_mark_id_prev( '<b>'. $this->get_constant('NAVI_PREVIOUS') .'</b>' );
	$this->_pagenavi_class->set_mark_id_next( '<b>'. $this->get_constant('NAVI_NEXT') .'</b>' );

	$cfg_use_pathinfo = $this->_config_class->get_by_name('use_pathinfo');

// separator
	if ( $cfg_use_pathinfo ) {
		$this->_pagenavi_class->set_separator_path(  '/' );
		$this->_pagenavi_class->set_separator_query( '/' );
	}
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_pagenavi( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// pagenavi class
//---------------------------------------------------------
function build_navi( $mode, $total, $param_out, $sort, $kind, $page, $limit )
{
	$url = $this->_uri_class->build_navi_url( 
		$mode, $param_out, $sort, $kind );

	$navi_page = $this->build_navi_page( $url, $page, $limit, $total ) ;
	$navi_info = $this->build_navi_info( $page, $limit, $total );

	$arr = array(
		'navi_page'  => $navi_page ,
		'navi_info'  => $navi_info ,
	);
	return $arr;
}

function build_navi_page( $url, $page, $limit, $total )
{
	return $this->_pagenavi_class->build( $url, $page, $limit, $total );
}

function build_navi_info( $page, $limit, $total )
{
	$start = $this->calc_navi_start( $limit, $page );
	$end   = $this->calc_navi_end( $start, $limit, $total );

	return sprintf( $this->get_constant('S_NAVINFO') , $start + 1 , $end , $total ) ;
}

function calc_navi_start( $page, $limit )
{
	return $this->_pagenavi_class->calc_start( $page, $limit );
}

function calc_navi_end( $start, $limit, $total )
{
	return $this->_pagenavi_class->calc_end( $start, $limit, $total );
}

// --- class end ---
}

?>