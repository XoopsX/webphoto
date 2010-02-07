<?php
// $Id: rate.php,v 1.8 2010/02/07 12:20:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-01-10 K.OHWADA
// $trust_dirname in webphoto_show_image
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_vote_handler
// 2009-05-15 K.OHWADA
// Parse error: syntax error
// webphoto_page
// 2008-12-07 K.OHWADA
// webphoto_rate_check
// 2008-10-01 K.OHWADA
// use get_rating_by_photoid()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_main_rate
//=========================================================
class webphoto_main_rate extends webphoto_base_this
{
	var $_vote_handler;
	var $_rate_check_class;
	var $_page_class;

	var $_photo_id = 0 ;
	var $_item_row = null ;

	var $_RATE_SESSION_NAME = null ;
	var $_RATE_MIN_RATING   = 1;
	var $_RATE_MAX_RATING   = 10;

	var $_TIME_SUCCESS = 1;
	var $_TIME_FAIL    = 5;

	var $_INDEX_PHP ;

// overwrite by preload
	var $_ARRAY_RATING_OPTIONS = array(
		'10','9','8','7','6','5','4','3','2','1' ) ;

	var $_RATE_SELECT_TYPE    = '';
	var $_RATE_SELECT_DELMITA = ' ';
	var $_RATE_RADIO_DELMITA  = '';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_main_rate( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );

	$this->_vote_handler     
		=& webphoto_vote_handler::getInstance( $dirname, $trust_dirname );
	$this->_rate_check_class 
		=& webphoto_rate_check::getInstance( $dirname, $trust_dirname );
	$this->_page_class       
		=& webphoto_page::getInstance( $dirname , $trust_dirname );

	$this->_RATE_SESSION_NAME = $dirname.'_rate_uri';

	$this->_INDEX_PHP = $this->_MODULE_URL.'/index.php' ;

// preload
	$this->preload_init();
	$this->preload_constant();
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
// rate
//---------------------------------------------------------
function rate()
{
	$this->_check();

	if ( $this->_is_rate() ) {
		$this->_rate();
		exit();
	}

// for template
	if ( !defined("_WEBPHOTO_CANCEL") ) {
		define("_WEBPHOTO_CANCEL", _CANCEL );
	}
}

function _check()
{
	$this->_photo_id = $this->_post_class->get_post_get_int('photo_id') ;

	$url = $this->_INDEX_PHP ;

// store the referer
	if ( ! empty(  $_SERVER['HTTP_REFERER'] ) ) {
		$referer = $_SERVER['HTTP_REFERER'] ;
		if ( ! preg_match( '/fct=rate/i', $referer ) ) {
			$url = $referer ;
			$_SESSION[ $this->_RATE_SESSION_NAME ] = $referer ;
		}
	}

	$check = $this->_rate_check_class->check( $this->_photo_id );
	if ( $check < 0 ) {
		$this->_rate_check_class->redirect( $check, $url, $this->_TIME_FAIL );
		exit();
	}

	$row = $this->_item_handler->get_row_by_id( $this->_photo_id );
	if ( !is_array($row) || ($row['item_status'] <= 0) ) {
		redirect_header( $this->_INDEX_PHP , $this->_TIME_FAIL , $this->get_constant('NOMATCH_PHOTO') ) ;
		exit() ;
	}

	$this->_item_row = $row ;
}

function _is_rate()
{
	return $this->_post_class->get_post_text('submit');
}

function _rate()
{
	$url_rate = $this->_MODULE_URL.'/index.php?fct=rate&amp;photo_id='.$this->_photo_id;

	$ret = $this->_exec_rate();
	switch ( $ret )
	{
		case _C_WEBPHOTO_ERR_NO_RATING :
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

	if ( isset( $_SESSION[ $this->_RATE_SESSION_NAME ] ) ) {
		$url =  $_SESSION[ $this->_RATE_SESSION_NAME ] ;
		unset(  $_SESSION[ $this->_RATE_SESSION_NAME ] ) ;
	}

	redirect_header( $url , $this->_TIME_SUCCESS , $msg ) ;
	exit();
}

function _exec_rate()
{
	if ( ! $this->check_token() ) { return _C_WEBPHOTO_ERR_TOKEN; }

	$post_rating = $this->_post_class->get_post_int('rating') ;

	// Check if rating is valid
	if ( $post_rating < $this->_RATE_MIN_RATING || 
	     $post_rating > $this->_RATE_MAX_RATING ) {
		return _C_WEBPHOTO_ERR_NO_RATING ;
	}

	// All is well.  Add to Line Item Rate to DB.
	$row = $this->_vote_handler->create( true );
	$row['vote_photo_id'] = $this->_photo_id;
	$row['vote_uid']      = $this->_xoops_uid;
	$row['vote_rating']   = $post_rating;
	$row['vote_hostname'] = getenv( "REMOTE_ADDR" ) ;

	$ret = $this->_vote_handler->insert( $row );
	if ( !$ret ) { return _C_WEBPHOTO_ERR_DB; }

	//All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
	$ret = $this->update_rating_by_photoid( $this->_photo_id );
	if ( !$ret ) { return _C_WEBPHOTO_ERR_DB; }

	return 0;
}

function update_rating_by_photoid( $photo_id )
{
	list( $votes, $total, $rating )
		= $this->_vote_handler->calc_rating_by_photoid( $photo_id );

	if ( $votes == 0 ) {
		return true; 	// no action
	}

	$ret = $this->_item_handler->update_rating_by_id( $photo_id, $votes, $rating );
	if ( !$ret ) {
		$this->set_error( $this->_item_handler->get_errors() );
		return false;
	}

	return true;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
// Parse error: syntax error
	$ret = array_merge(
		$this->_page_class->build_main_param() ,
		$this->get_photo() 
	);
	return $ret;
}

function get_photo()
{
	$show_class =& webphoto_show_image::getInstance( 
		$this->_DIRNAME, $this->_TRUST_DIRNAME );

	$arr = array(
		'photo'          => $show_class->build_image_title_by_item_row( $this->_item_row ) ,
		'token_name'     => $this->get_token_name(),
		'token_value'    => $this->get_token(),
		'rating_options' => $this->_build_rating_options(),
		'select_type'    => $this->_RATE_SELECT_TYPE ,
		'select_delmita' => $this->_RATE_SELECT_DELMITA ,
		'radio_delmita'  => $this->_RATE_RADIO_DELMITA ,
	);

	return $arr;
}

function _build_rating_options()
{
	$arr = array();
	foreach ( $this->_ARRAY_RATING_OPTIONS as $key ) 
	{
		$arr[] = array(
			'value' => $key ,
			'name'  => $this->get_constant( 'VOTE_RATING_'.$key ) ,
		);
	}
	return $arr ;
}

// --- class end ---
}

?>