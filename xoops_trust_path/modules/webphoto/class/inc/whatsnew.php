<?php
// $Id: whatsnew.php,v 1.3 2008/07/05 16:57:40 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// used use_pathinfo
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_whatsnew
//=========================================================
class webphoto_inc_whatsnew extends webphoto_inc_handler
{
	var $_cfg_use_pathinfo = false;

	var $_cat_cached = array();

	var $_FLAG_SUBSTITUTE = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_whatsnew()
{
	$this->webphoto_inc_handler();
	$this->set_normal_exts( _C_WEBPHOTO_IMAGE_EXTS );

}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_whatsnew();
	}
	return $instance;
}

function _init( $dirname )
{
	$this->init_handler( $dirname );
	$this->_init_xoops_config( $dirname );

// preload
	$name = strtoupper( '_P_'. $dirname .'_WHATSNEW_SUBSTITUTE' );
	if ( defined( $name ) ) {
		$this->_FLAG_SUBSTITUTE = constant( $name );
	}
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function whatsnew( $dirname , $limit=0 , $offset=0 )
{
	$this->_init( $dirname );

	$photo_table = $this->prefix_dirname( 'photo' );
	$cat_table   = $this->prefix_dirname( 'cat' );

	$rows = $this->_get_photo_rows( $photo_table, $limit, $offset );
	if ( !is_array($rows) ) { return array(); }

	$i   = 0;
	$ret = array();

	foreach( $rows as $row )
	{
		$cat_title   = '';

		$photo_id     = $row['photo_id'];
		$cat_id       = $row['photo_cat_id'];
		$time_update  = $row['photo_time_update'];
		$time_create  = $row['photo_time_create'];

		$cat_row = $this->_get_cat_cached_row( $cat_table, $cat_id );
		if ( is_array($cat_row) ) {
			$cat_title = $cat_row['cat_title'];
		}

		if ( $this->_cfg_use_pathinfo ) {
			$link     = $this->_MODULE_URL.'/index.php/photo/'.    $photo_id .'/' ;
			$cat_link = $this->_MODULE_URL.'/index.php/category/'. $cat_id .'/' ;
		} else {
			$link     = $this->_MODULE_URL.'/index.php?fct=photo&amp;p='.    $photo_id ;
			$cat_link = $this->_MODULE_URL.'/index.php?fct=category&amp;p='. $cat_id ;
		}

		$arr = array(
			'link'     => $link ,
			'cat_link' => $cat_link ,
			'title'    => $row['photo_title'] ,
			'cat_name' => $cat_title ,
			'uid'      => $row['photo_uid'] ,
			'hits'     => $row['photo_hits'] ,
			'time'     => $time_update ,

// atom
			'id'          => $photo_id ,
			'modified'    => $time_update ,
			'issued'      => $time_create ,
			'created'     => $time_create ,
			'description' => $this->_build_description( $row ) ,
		);

		$is_normal_ext = $this->is_normal_ext( $row['photo_cont_ext'] );

// photo image
		if (( $is_normal_ext || $this->_FLAG_SUBSTITUTE ) && 
		      $row['photo_thumb_url'] ) {
			$arr['image']  = $row['photo_thumb_url'];
			$arr['width']  = $row['photo_thumb_width'];
			$arr['height'] = $row['photo_thumb_height'];
		}

// media rss
		if ( $is_normal_ext ) {
			$arr['content_url']      = $row['photo_cont_url'];
			$arr['content_width']    = $row['photo_cont_width'];
			$arr['content_height']   = $row['photo_cont_height'];
			$arr['content_type']     = $row['photo_cont_mime'];
			$arr['thumbnail_url']    = $row['photo_thumb_url'];
			$arr['thumbnail_width']  = $row['photo_thumb_width'];
			$arr['thumbnail_height'] = $row['photo_thumb_height'];
		}

// geo rss
		if ( $this->_is_gmap( $row ) ) {
			$arr['geo_lat']  = floatval( $row['photo_gmap_latitude'] ) ;
			$arr['geo_long'] = floatval( $row['photo_gmap_longitude'] ) ;
		}

		$ret[ $i ] = $arr;
		$i++;
	}

	return $ret;
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function _build_description( $row )
{
	$myts =& MyTextSanitizer::getInstance();
	return $myts->displayTarea( $row['photo_description'] , 0 , 1 , 1 , 1 , 1 , 1 );
}

function _is_gmap( $row )
{
	if (( floatval( $row['photo_gmap_latitude'] )  != 0 )||
	    ( floatval( $row['photo_gmap_longitude'] ) != 0 )||
	    ( intval(   $row['photo_gmap_zoom'] )      != 0 )) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// photo handler
//---------------------------------------------------------
function _get_photo_rows( $table, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $table;
	$sql .= ' WHERE photo_status > 0 ';
	$sql .= ' ORDER BY photo_time_update DESC, photo_id DESC';
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

//---------------------------------------------------------
// cat handler
//---------------------------------------------------------
function _get_cat_cached_row( $table, $id )
{
	if ( isset( $this->_cat_cached[ $id ] ) ) {
		return  $this->_cat_cached[ $id ];
	}

	$row = $this->_get_cat_row( $table, $id );
	if ( is_array($row) ) {
		$this->_cat_cached[ $id ] = $row;
		return $row;
	}

	return null;
}

function _get_cat_row( $table, $cat_id )
{
	$sql  = 'SELECT * FROM '. $table;
	$sql .= ' WHERE cat_id='.intval($cat_id);
	return $this->get_row_by_sql( $sql );
}

//---------------------------------------------------------
// xoops_config
//---------------------------------------------------------
function _init_xoops_config( $dirname )
{
	$config_handler =& webphoto_inc_config::getInstance();
	$config_handler->init( $dirname );

	$this->_cfg_use_pathinfo = $config_handler->get_by_name('use_pathinfo');
}

// --- class end ---
}

?>