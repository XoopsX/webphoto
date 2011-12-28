<?php
// $Id: main.php,v 1.4 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2010-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// remove build_title()
// 2010-11-03 K.OHWADA
// build_rows_for_rss()
// 2010-05-10 K.OHWADA
// build_total_for_detail()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main
//=========================================================
class webphoto_main extends webphoto_base_this
{
	var $_public_class;
	var $_sort_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_public_class
		=& webphoto_photo_public::getInstance( $dirname, $trust_dirname );
	$this->_sort_class 
		=& webphoto_photo_sort::getInstance( $dirname, $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// detail
//---------------------------------------------------------
function build_total_for_detail( $mode )
{
	$title = $this->build_title_by_mode( $mode );
	$name  = $this->_sort_class->mode_to_name( $mode );
	$total = $this->_public_class->get_count_by_name_param( $name, null );

	return array( $title, $total );
}

function build_rows_for_detail( $mode, $sort, $limit=0, $start=0 )
{
	$name    = $this->_sort_class->mode_to_name( $mode );
	$orderby = $this->_sort_class->mode_to_orderby( $mode, $sort );

	return $this->_public_class->get_rows_by_name_param_orderby( 
		$name, null, $orderby, $limit, $start );
}

//---------------------------------------------------------
// rss
//---------------------------------------------------------
function build_rows_for_rss( $mode, $limit=0, $start=0 )
{
	$sort = null;
	return $this->build_rows_for_detail( $mode, $sort, $limit, $start );
}

// --- class end ---
}

?>