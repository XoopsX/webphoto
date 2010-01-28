<?php
// $Id: search.php,v 1.2 2010/01/28 02:08:13 ohwada Exp $

//=========================================================
// webphoto module
// 2010-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_search
//=========================================================
class webphoto_search extends webphoto_base_this
{
	var $_public_class;
	var $_search_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_search( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_public_class
		=& webphoto_photo_public::getInstance( $dirname, $trust_dirname );

	$this->_search_class =& webphoto_lib_search::getInstance();
	$this->_search_class->set_lang_zenkaku( $this->get_constant('SR_ZENKAKU') );
	$this->_search_class->set_lang_hankaku( $this->get_constant('SR_HANKAKU') );
	$this->_search_class->set_min_keyword( 
		$this->_search_class->get_xoops_config_search_keyword_min() );
	$this->_search_class->set_is_japanese( $this->_is_japanese );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_search( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// detail
//---------------------------------------------------------
function build_rows_for_detail( $query, $orderby, $limit, $start )
{
	$title = _SR_SEARCH;
	$rows  = null;
	$total = 0;

	$this->_search_class->get_post_get_param();
	$this->_search_class->set_query( $query );

	$ret = $this->_search_class->parse_query();
	if ( !$ret ) {
		return array( $title, $total, $rows );
	}

	$sql_query = $this->_search_class->build_sql_query( 'item_search' );
	$total     = $this->_public_class->get_count_by_search( $sql_query );

	if ( $total > 0 ) {
		$title = _SR_SEARCH.' : '.$this->_search_class->get_query_raw('s');
		$rows  = $this->_public_class->get_rows_by_search_orderby( 
			$sql_query, $orderby, $limit, $start );
	}

	return array( $title, $total, $rows );
}

function build_query_param( $total )
{
	$param  = $this->_search_class->get_query_param();
	$param['show_search'] = true ;
	if ( $total == 0 ) {
		$param['show_search_lang_keytooshort'] = true ;
	}
	return $param;
}

// --- class end ---
}

?>