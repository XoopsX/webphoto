<?php
// $Id: visit.php,v 1.1 2008/11/19 10:26:45 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_visit
//=========================================================
class webphoto_main_visit extends webphoto_lib_error
{
	var $_item_handler;
	var $_post_class;

	var $_FLAG_REDIRECT = true;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_visit( $dirname , $trust_dirname )
{
	$this->webphoto_lib_error();

	$this->_item_handler =& webphoto_item_handler::getInstance( $dirname );
	$this->_post_class   =& webphoto_lib_post::getInstance();

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_visit( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$item_id  = $this->_post_class->get_get_int('item_id') ;
	$item_row = $this->_item_handler->get_row_by_id( $item_id );
	if ( ! is_array($item_row ) ) {
		exit();
	}

	$this->_item_handler->countup_hits( $item_id, true );

	$siteurl   = $item_row['item_siteurl'];
	$siteurl   = preg_replace( '/javascript:/si' , 'java script:', $siteurl );
	$siteurl_s = $this->sanitize( $siteurl );

	if ( $this->_FLAG_REDIRECT ) {
		header( 'Location: '.$siteurl );

	} else {
		echo '<html><head>';
		echo '<meta http-equiv="Refresh" content="0; URL='. $siteurl_s .'"></meta>';
		echo '</head><body></body></html>';
	}

	exit();
}

// --- class end ---
}
?>