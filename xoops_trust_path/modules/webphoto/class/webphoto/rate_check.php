<?php
// $Id: rate_check.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-12-07 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_item_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_rate_check
//=========================================================
class webphoto_rate_check extends webphoto_lib_base
{
	var $_item_handler;
	var $_vote_handler;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_rate_check( $dirname, $trust_dirname )
{
	$this->webphoto_lib_base( $dirname, $trust_dirname );

	$this->_item_handler =& webphoto_item_handler::getInstance( 
		$dirname, $trust_dirname );
	$this->_vote_handler =& webphoto_vote_handler::getInstance( 
		$dirname, $trust_dirname  );

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_rate_check( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function can_rate( $item_id )
{
	if ( $this->check( $item_id ) == 0 ) {
		return true;
	}
	return false;
}

function check( $item_id )
{

// registered user
	if ( $this->_xoops_uid != 0 ) {

		// Check if Photo POSTER is voting
		$photo_count = $this->_item_handler->get_count_by_itemid_uid( $item_id, $this->_xoops_uid );
		if ( $photo_count ) {
			return _C_WEBPHOTO_ERR_VOTE_OWN ;
		}

		// Check if REG user is trying to vote twice.
		$vote_count = $this->_vote_handler->get_count_by_photoid_uid( $item_id, $this->_xoops_uid );
		if ( $vote_count ) {
			return _C_WEBPHOTO_ERR_VOTE_ONCE ;
		}

// anonymous user
	} else {
		// Check if ANONYMOUS user is trying to vote more than once per day.
		$vote_count = $this->_vote_handler->get_count_anonymous_by_photoid_hostname( 
			$item_id, getenv( "REMOTE_ADDR" ) );
		if ( $vote_count ) {
			return _C_WEBPHOTO_ERR_VOTE_ONCE ;
		}
	}

	return 0 ;
}

function redirect( $ret, $url, $time )
{
	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_VOTE_OWN :
			redirect_header( $url , $time , $this->get_constant('ERR_CANTVOTEOWN') ) ;
			exit() ;

		case _C_WEBPHOTO_ERR_VOTE_ONCE :
			redirect_header( $url, $time , $this->get_constant('ERR_VOTEONCE') ) ;
			exit() ;
	}
}

// --- class end ---
}

?>