<?php
// $Id: rss.php,v 1.9 2009/03/06 03:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-03-01 K.OHWADA
// webphoto_rss()
// 2008-12-12 K.OHWADA
// webphoto_photo_public
// 2008-12-09 K.OHWADA
// Parse error & Fatal error
// 2008-11-29 K.OHWADA
// _build_file_image()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_rss
//=========================================================
class webphoto_main_rss extends webphoto_rss
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_rss( $dirname, $trust_dirname )
{
	$this->webphoto_rss( $dirname, $trust_dirname ) ;
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_rss( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$this->show_rss();
}

// --- class end ---
}

?>