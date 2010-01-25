<?php
// $Id: tag.php,v 1.6 2010/01/25 10:03:07 ohwada Exp $

//=========================================================
// webphoto module
// 2010-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_tag
//=========================================================
class webphoto_tag extends webphoto_base_this
{
	var $_public_class;

	var $_TAG_LIST_START = 0;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_tag( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_public_class
		=& webphoto_photo_public::getInstance( $dirname, $trust_dirname );

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_tag( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// list
//---------------------------------------------------------
function build_rows_for_list()
{
	$tag_list_limit  = $this->get_ini('tag_list_limit');
	$tag_photo_limit = $this->get_ini('tag_photo_limit');

	$tag_rows = $this->_public_class->get_tag_rows( 
		$tag_list_limit, $this->_TAG_LIST_START );
	if ( !is_array($tag_rows) || !count($tag_rows) ) {
		return false;
	}

	$i   = 0;
	$arr = array();
	foreach ( $tag_rows as $row )
	{
		$tag_name  = $row['tag_name'];
		$total     = $row['photo_count'];

		$photo_row = $this->_public_class->get_first_row_by_tag_orderby(
			$tag_name, $this->_PHOTO_LIST_UPDATE_ORDER, $this->_PHOTO_LIST_LIMIT );

		$arr[] = array( $tag_name, $tag_name, $total, $photo_row );

		$i ++;
		if ( $i > $tag_photo_limit ) {
			break;
		}
	}

	return $arr;
}

//---------------------------------------------------------
// detail
//---------------------------------------------------------
function build_rows_for_detail( $tag_in, $orderby, $limit, $start )
{
	$tag_name = $this->decode_uri_str( $tag_in );

	$title = $this->build_title( $tag_name );
	$rows  = null ;
	$total = $this->_public_class->get_count_by_tag( $tag_name );

	if ( $total > 0 ) {
		$rows = $this->_public_class->get_rows_by_tag_orderby( 
			$tag_name, $orderby, $limit, $start );
	}

	return array( $title, $total, $rows );
}

function build_title( $tag_name )
{
	$str = $this->get_constant('TITLE_TAGS') .' : '. $tag_name ;
	return $str;
}

// --- class end ---
}

?>