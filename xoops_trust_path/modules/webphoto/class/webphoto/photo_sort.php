<?php
// $Id: photo_sort.php,v 1.3 2008/10/30 00:22:49 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-10-01 K.OHWADA
// photo_sort_array_admin()
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_sort
//=========================================================
class webphoto_photo_sort
{
	var $_DIRNAME       = null;
	var $_TRUST_DIRNAME = null;
	var $_MODULE_URL;
	var $_MODULE_DIR;
	var $_TRUST_DIR;

	var $_PHOTO_SORT_ARRAY   = null;
	var $_MODE_TO_SORT_ARRAY = null;

	var $_PHOTO_SORT_DEFAULT = 'dated';
	var $_ORDERBY_RANDOM = 'rand()';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_sort( $dirname, $trust_dirname )
{
	$this->set_trust_dirname( $trust_dirname );
	$this->_init_d3_language( $dirname, $trust_dirname );

	$this->set_photo_sort_array(   $this->photo_sort_array_default() );
	$this->set_mode_to_sort_array( $this->mode_to_sort_array_default() );
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo_sort( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// function
//---------------------------------------------------------
function mode_to_orderby( $mode )
{
	return $this->sort_to_orderby( $this->mode_to_sort( $mode ) );
}

//---------------------------------------------------------
// mode
//---------------------------------------------------------
function mode_to_sort_array_default()
{
	$arr = array(
		'latest'   => 'dated' ,
		'popular'  => 'hitsd' ,
		'highrate' => 'ratingd' ,
		'random'   => 'random' ,
	);
	return $arr;
}

function set_mode_to_sort_array( $arr )
{
	if ( is_array($arr) && count($arr) ) {
		$this->_MODE_TO_SORT_ARRAY = $arr;
	}
}

function mode_to_sort( $mode )
{
	if ( isset( $this->_MODE_TO_SORT_ARRAY[ $mode ] ) ){
		return  $this->_MODE_TO_SORT_ARRAY[ $mode ];
	}
	return null;
}

//---------------------------------------------------------
// photo sort
//---------------------------------------------------------
function photo_sort_array_basic()
{
	$arr = array(
		'ida'     => array( 'item_id ASC' ,           $this->get_constant('SORT_IDA') ) ,
		'idd'     => array( 'item_id DESC' ,          $this->get_constant('SORT_IDD') ) ,
		'titlea'  => array( 'item_title ASC' ,        $this->get_constant('SORT_TITLEA') ) ,
		'titled'  => array( 'item_title DESC' ,       $this->get_constant('SORT_TITLED') ) ,
		'datea'   => array( 'item_time_update ASC' ,  $this->get_constant('SORT_DATEA') ) ,
		'dated'   => array( 'item_time_update DESC' , $this->get_constant('SORT_DATED') ) ,
		'hitsa'   => array( 'item_hits ASC' ,         $this->get_constant('SORT_HITSA') ) ,
		'hitsd'   => array( 'item_hits DESC' ,        $this->get_constant('SORT_HITSD') ) ,
		'ratinga' => array( 'item_rating ASC' ,       $this->get_constant('SORT_RATINGA') ) ,
		'ratingd' => array( 'item_rating DESC' ,      $this->get_constant('SORT_RATINGD') ) ,
	) ;
	return $arr;
}

function photo_sort_array_default()
{
	$arr = $this->photo_sort_array_basic() ;

	$arr['random'] = array( $this->_ORDERBY_RANDOM , $this->get_constant('SORT_RANDOM') ) ;

	return $arr;
}

function photo_sort_array_admin()
{
	$arr = $this->photo_sort_array_basic() ;

	$arr['votesa']   = array( 'item_votes ASC' ,   $this->get_constant('SORT_VOTESA') ) ;
	$arr['votesd']   = array( 'item_votes DESC' ,  $this->get_constant('SORT_VOTESD') ) ;
	$arr['viewsa']   = array( 'item_views ASC' ,   $this->get_constant('SORT_VIEWSA') ) ;
	$arr['viewsd']   = array( 'item_views DESC' ,  $this->get_constant('SORT_VIEWSD') ) ;

	return $arr;
}

function set_photo_sort_array( $arr )
{
	if ( is_array($arr) && count($arr) ) {
		$this->_PHOTO_SORT_ARRAY = $arr;
	}
}

function sort_to_orderby( $sort )
{
	$orderby = $this->get_photo_sort( $sort, 0 );

	if (($orderby != 'item_id DESC')&&( $orderby != 'rand()')) {
		$orderby = $orderby.', item_id DESC';
	}
	return $orderby;
}

function sort_to_lang( $sort )
{
	return $this->get_photo_sort( $sort, 1 );
}

function get_lang_sortby( $name )
{
	return sprintf( 
		$this->get_constant('SORT_S_CURSORTEDBY') , 
		$this->sort_to_lang( $name ) );
}

function get_photo_sort_name( $name )
{
	if( $name && isset( $this->_PHOTO_SORT_ARRAY[ $name ] ) ) {
		return $name ;
	} elseif( isset( $this->_PHOTO_SORT_ARRAY[ $this->_PHOTO_SORT_DEFAULT ] ) ) {
		return $this->_PHOTO_SORT_DEFAULT ;
	}

	return false;
}

function get_photo_sort( $name, $num )
{
	if ( isset( $this->_PHOTO_SORT_ARRAY[ $name ][ $num ] ) ) {
		return  $this->_PHOTO_SORT_ARRAY[ $name ][ $num ];
	}
	return $this->_PHOTO_SORT_ARRAY[ $this->_PHOTO_SORT_DEFAULT ][ $num ];
}

function set_photo_sort_default( $val )
{
	$this->_PHOTO_SORT_DEFAULT = $val;
}

function get_random_orderby()
{
	return $this->_ORDERBY_RANDOM;
}

//---------------------------------------------------------
// join sql
//---------------------------------------------------------
function convert_orderby_join( $str )
{
	return str_replace( 'item_', 'i.item_', $str );
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

function set_trust_dirname( $trust_dirname )
{
	$this->_TRUST_DIRNAME = $trust_dirname;
	$this->_TRUST_DIR     = XOOPS_TRUST_PATH .'/modules/'. $trust_dirname;
}

// --- class end ---
}

?>