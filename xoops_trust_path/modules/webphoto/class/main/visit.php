<?php
// $Id: visit.php,v 1.2 2008/12/18 13:23:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-11-16 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-12 K.OHWADA
// webphoto_item_public
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_visit
//=========================================================
class webphoto_main_visit extends webphoto_item_public
{
	var $_post_class;

	var $_FLAG_REDIRECT = true;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_visit( $dirname , $trust_dirname )
{
	$this->webphoto_item_public( $dirname , $trust_dirname );

	$this->_post_class =& webphoto_lib_post::getInstance();

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
	$item_row = $this->get_item_row( $item_id );
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