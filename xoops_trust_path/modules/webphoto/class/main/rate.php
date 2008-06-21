<?php
// $Id: rate.php,v 1.1 2008/06/21 12:22:19 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_rate
//=========================================================
class webphoto_main_rate extends webphoto_base_this
{
	var $_vote_handler;

	var $_session_name;

	var $_ERR_NO_RATING = -1;
	var $_ERR_VOTE_OWN  = -2;
	var $_ERR_VOTE_ONCE = -3;

	var $_TIME_SUCCESS = 1;
	var $_TIME_FAIL    = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_rate( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );

	$this->_vote_handler =& webphoto_vote_handler::getInstance( $dirname );

	$this->_session_name = $dirname.'_uri4return';
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_main_rate( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function rate()
{
	if ( $this->_is_rate() ) {
		$this->_rate();
		exit();
	}

// for template
	if ( !defined("_WEBPHOTO_CANCEL") ) {
		define("_WEBPHOTO_CANCEL", _CANCEL );
	}
}

function _is_rate()
{
	return $this->_post_class->get_post_text('submit');
}

function _rate()
{
	$post_photo_id = $this->_post_class->get_post_int('photo_id') ;
	$url_rate = $this->_MODULE_URL.'/index.php?fct=rate&amp;photo_id='.$post_photo_id;

	$ret = $this->_exec_rate();
	switch ( $ret )
	{
		case $this->_ERR_VOTE_OWN:
			redirect_header( $this->_INDEX_PHP , $this->_TIME_FAIL , $this->get_constant('ERR_CANTVOTEOWN') ) ;
			exit() ;

		case $this->_ERR_VOTE_ONCE:
			redirect_header( $this->_INDEX_PHP, $this->_TIME_FAIL , $this->get_constant('ERR_VOTEONCE') ) ;
			exit() ;

		case $this->_ERR_NO_RATING:
			redirect_header( $url_rate , $this->_TIME_FAIL , $this->get_constant('ERR_NORATING') ) ;
			exit() ;

		case _C_WEBPHOTO_ERR_TOKEN:
			$msg = 'Token Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_token_errors();
			}
			redirect_header( $url_rate, $this->_TIME_FAIL , $msg );
			exit();

		case _C_WEBPHOTO_ERR_DB:
			$msg = 'DB Error';
			if ( $this->_is_module_admin ) {
				$msg .= '<br />'.$this->get_format_error();
			}
			redirect_header( $url_rate, $this->_TIME_FAIL, $msg ) ;
			exit();

		case 0:
		default:
			break;
	}

	$url  = $this->_INDEX_PHP;
	$msg  = $this->get_constant( 'RATE_VOTEAPPRE') ."<br />\n";
	$msg .= sprintf( $this->get_constant( 'RATE_S_THANKURATE') , $this->_xoops_sitename );

	if ( isset( $_SESSION[ $this->_session_name ] ) ) {
		$url =  $_SESSION[ $this->_session_name ] ;
		unset(  $_SESSION[ $this->_session_name ] ) ;
	}

	redirect_header( $url , $this->_TIME_SUCCESS , $msg ) ;
	exit();
}

function _exec_rate()
{
	if ( ! $this->check_token() ) { return _C_WEBPHOTO_ERR_TOKEN; }

	//Make sure only 1 anonymous from an IP in a single day.
	$ip = getenv( "REMOTE_ADDR" ) ;

	$post_photo_id = $this->_post_class->get_post_int('photo_id') ;
	$post_rating   = $this->_post_class->get_post_int('rating') ;

	// Check if rating is valid
	if( $post_rating <= 0 || $post_rating > 10 ) {
		return $this->_ERR_NO_RATING;
	}

// registered user
	if ( $this->_xoops_uid != 0 ) {

		// Check if Photo POSTER is voting
		$photo_count = $this->_photo_handler->get_count_by_photoid_uid( $post_photo_id, $this->_xoops_uid );
		if ( $photo_count ) { return $this->_ERR_VOTE_OWN; }

		// Check if REG user is trying to vote twice.
		$vote_count = $this->_vote_handler->get_count_by_photoid_uid( $post_photo_id, $this->_xoops_uid );
		if ( $vote_count ) { return $this->_ERR_VOTE_ONCE; }

// anonymous user
	} else {
		// Check if ANONYMOUS user is trying to vote more than once per day.
		$vote_anonymous_count = $this->_vote_handler->get_count_anonymous_by_photoid_hostname( $post_photo_id, $ip );
		if ( $vote_anonymous_count ) { return $this->_ERR_VOTE_ONCE; }

	}

	// All is well.  Add to Line Item Rate to DB.
	$row = $this->_vote_handler->create( true );
	$row['vote_photo_id'] = $post_photo_id;
	$row['vote_uid']      = $this->_xoops_uid;
	$row['vote_rating']   = $post_rating;
	$row['vote_hostname'] = $ip;

	$ret = $this->_vote_handler->insert( $row );
	if ( !$ret ) { return _C_WEBPHOTO_ERR_DB; }

	//All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
	$ret = $this->update_rating_by_photoid( $post_photo_id );
	if ( !$ret ) { return _C_WEBPHOTO_ERR_DB; }

	return 0;
}

function update_rating_by_photoid( $photo_id )
{
	$rows = $this->_vote_handler->get_rows_by_photoid( $photo_id );
	if ( !is_array($rows) ) { return true; }	// no action

	$votesDB = count( $rows ) ;

	$totalrating = 0;
	foreach( $rows as $row ) {
		$totalrating += $row['vote_rating'] ;
	}

	$finalrating = number_format( $totalrating / $votesDB , 4 ) ;

	$ret = $this->_photo_handler->update_rating_by_id( $photo_id, $finalrating, $votesDB );
	if ( !$ret ) {
		$this->set_error( $this->_photo_handler->get_errors() );
		return false;
	}

	return true;
}

function get_photo()
{
	$show_class  =& webphoto_show_photo::getInstance( $this->_DIRNAME, $this->_TRUST_DIRNAME );

	$get_photo_id = $this->_post_class->get_get_int('photo_id') ;

	// store the referer
	if( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
		$_SESSION[ $this->_session_name ] = $_SERVER['HTTP_REFERER'] ;
	}

	$row = $this->_photo_handler->get_row_by_id( $get_photo_id );
	if ( !is_array($row) || ($row['photo_status'] == 0) ) {
		redirect_header( $this->_INDEX_PHP , $this->_TIME_FAIL , $this->get_constant('NOMATCH_PHOTO') ) ;
		exit ;
	}

	$arr = array(
		'photo'       => $show_class->build_photo_show( $row ),
		'token_name'  => $this->get_token_name(),
		'token_value' => $this->get_token(),
	);

	return $arr;
}


// --- class end ---
}

?>