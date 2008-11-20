<?php
// $Id: cat_handler.php,v 1.4 2008/11/20 11:15:46 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-11-16 K.OHWADA
// check_perms_in_groups()
// 2008-11-08 K.OHWADA
// cat_img_name
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_cat_handler
//=========================================================
class webphoto_cat_handler extends webphoto_lib_tree_handler
{
	var $_xoops_groups = null;

	var $_ALLOWED_EXT_DEFAULT = _C_WEBPHOTO_IMAGE_EXTS;
	var $_PREM_ALLOW_ALL      = _C_WEBPHOTO_PERM_ALLOW_ALL;
	var $_PREM_DENOY_ALL      = _C_WEBPHOTO_PERM_DENOY_ALL;
	var $_PREM_SEPARATOR      = _C_WEBPHOTO_PERM_SEPARATOR;
	var $_WEIGHT_DEFAULT = 1;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_cat_handler( $dirname )
{
	$this->webphoto_lib_tree_handler( $dirname );
	$this->set_table_prefix_dirname( 'cat' );
	$this->set_id_name(  'cat_id' );
	$this->set_pid_name( 'cat_pid' );
	$this->init_xoops_tree();
	$this->set_order_default( 'cat_weight ASC, cat_title ASC, cat_id ASC' );

	$constpref = strtoupper( '_P_' . $dirname. '_' ) ;
	$this->set_debug_sql_by_const_name(   $constpref.'DEBUG_SQL' );
	$this->set_debug_error_by_const_name( $constpref.'DEBUG_ERROR' );
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_cat_handler( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_xoops_groups( $val )
{
	if ( is_array($val) && count($val) ) {
		$this->_xoops_groups = $val;
	}
}

//---------------------------------------------------------
// create
//---------------------------------------------------------
function create( $flag_new=false )
{
	$time_create = 0;
	$time_update = 0;

	if ( $flag_new ) {
		$time = time();
		$time_create = $time;
		$time_update = $time;
	}

	$arr = array(
		'cat_id'             => 0,
		'cat_time_create'    => $time_create,
		'cat_time_update'    => $time_update,
		'cat_gicon_id'       => 0,
		'cat_forum_id'       => 0,
		'cat_pid'            => 0,
		'cat_title'          => '',
		'cat_img_path'       => '',
		'cat_img_name'       => '',
		'cat_weight'         => $this->_WEIGHT_DEFAULT ,
		'cat_depth'          => 0,
		'cat_allowed_ext'    => $this->_ALLOWED_EXT_DEFAULT ,
		'cat_img_mode'       => 0,
		'cat_orig_width'     => 0,
		'cat_orig_height'    => 0,
		'cat_main_width'     => 0,
		'cat_main_height'    => 0,
		'cat_sub_width'      => 0,
		'cat_sub_height'     => 0,
		'cat_item_type'      => 0,
		'cat_gmap_mode'      => 0,
		'cat_gmap_latitude'  => 0,
		'cat_gmap_longitude' => 0,
		'cat_gmap_zoom'      => 0,
		'cat_gmap_type'      => 0,
		'cat_perm_read'      => $this->_PREM_ALLOW_ALL ,
		'cat_perm_post'      => $this->_PREM_ALLOW_ALL ,
		'cat_description'    => '',
	);

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_CAT_TEXT; $i++ ) {
		$arr[ 'cat_text'.$i ] = '';
	}

	return $arr;
}

//---------------------------------------------------------
// insert
//---------------------------------------------------------
function insert( $row )
{
	extract( $row ) ;

	$sql  = 'INSERT INTO '.$this->_table.' (';

	if ( $cat_id > 0 ) {
		$sql .= 'cat_id, ';
	}

	$sql .= 'cat_time_create, ';
	$sql .= 'cat_time_update, ';
	$sql .= 'cat_gicon_id, ';
	$sql .= 'cat_forum_id, ';
	$sql .= 'cat_pid, ';
	$sql .= 'cat_title, ';
	$sql .= 'cat_img_path, ';
	$sql .= 'cat_img_name, ';
	$sql .= 'cat_weight, ';
	$sql .= 'cat_depth, ';
	$sql .= 'cat_allowed_ext, ';
	$sql .= 'cat_img_mode, ';
	$sql .= 'cat_orig_width, ';
	$sql .= 'cat_orig_height, ';
	$sql .= 'cat_main_width, ';
	$sql .= 'cat_main_height, ';
	$sql .= 'cat_sub_width, ';
	$sql .= 'cat_sub_height, ';
	$sql .= 'cat_item_type, ';
	$sql .= 'cat_gmap_mode, ';
	$sql .= 'cat_gmap_latitude, ';
	$sql .= 'cat_gmap_longitude, ';
	$sql .= 'cat_gmap_zoom, ';
	$sql .= 'cat_gmap_type, ';
	$sql .= 'cat_perm_read, ';
	$sql .= 'cat_perm_post, ';

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_CAT_TEXT; $i++ ) {
		$sql .= 'cat_text'.$i.', ';
	}

	$sql .= 'cat_description ';

	$sql .= ') VALUES ( ';

	if ( $cat_id > 0 ) {
		$sql .= intval($cat_id).', ';
	}

	$sql .= intval($cat_time_create).', ';
	$sql .= intval($cat_time_update).', ';
	$sql .= intval($cat_gicon_id).', ';
	$sql .= intval($cat_forum_id).', ';
	$sql .= intval($cat_pid).', ';
	$sql .= $this->quote($cat_title).', ';
	$sql .= $this->quote($cat_img_path).', ';
	$sql .= $this->quote($cat_img_name).', ';
	$sql .= intval($cat_weight).', ';
	$sql .= intval($cat_depth).', ';
	$sql .= $this->quote($cat_allowed_ext).', ';
	$sql .= intval($cat_img_mode).', ';
	$sql .= intval($cat_orig_width).', ';
	$sql .= intval($cat_orig_height).', ';
	$sql .= intval($cat_main_width).', ';
	$sql .= intval($cat_main_height).', ';
	$sql .= intval($cat_sub_width).', ';
	$sql .= intval($cat_sub_height).', ';
	$sql .= intval($cat_item_type).', ';
	$sql .= intval($cat_gmap_mode).', ';
	$sql .= floatval($cat_gmap_latitude).', ';
	$sql .= floatval($cat_gmap_longitude).', ';
	$sql .= intval($cat_gmap_zoom).', ';
	$sql .= intval($cat_gmap_type).', ';
	$sql .= $this->quote($cat_perm_read).', ';
	$sql .= $this->quote($cat_perm_post).', ';

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_CAT_TEXT; $i++ ) {
		$sql .= $this->quote( $row[ 'cat_text'.$i ] ).', ';
	}

	$sql .= $this->quote($cat_description).' ';

	$sql .= ')';

	$ret = $this->query( $sql );
	if ( !$ret ) { return false; }

	return $this->_db->getInsertId();
}

//---------------------------------------------------------
// update
//---------------------------------------------------------
function update( $row )
{
	extract( $row ) ;

	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'cat_time_create='.intval($cat_time_create).', ';
	$sql .= 'cat_time_update='.intval($cat_time_update).', ';
	$sql .= 'cat_gicon_id='.intval($cat_gicon_id).', ';
	$sql .= 'cat_forum_id='.intval($cat_forum_id).', ';
	$sql .= 'cat_pid='.intval($cat_pid).', ';
	$sql .= 'cat_title='.$this->quote($cat_title).', ';
	$sql .= 'cat_img_path='.$this->quote($cat_img_path).', ';
	$sql .= 'cat_img_name='.$this->quote($cat_img_name).', ';
	$sql .= 'cat_weight='.intval($cat_weight).', ';
	$sql .= 'cat_depth='.intval($cat_depth).', ';
	$sql .= 'cat_allowed_ext='.$this->quote($cat_allowed_ext).', ';
	$sql .= 'cat_img_mode='.intval($cat_img_mode).', ';
	$sql .= 'cat_orig_width='.intval($cat_orig_width).', ';
	$sql .= 'cat_orig_height='.intval($cat_orig_height).', ';
	$sql .= 'cat_main_width='.intval($cat_main_width).', ';
	$sql .= 'cat_main_height='.intval($cat_main_height).', ';
	$sql .= 'cat_sub_width='.intval($cat_sub_width).', ';
	$sql .= 'cat_sub_height='.intval($cat_sub_height).', ';
	$sql .= 'cat_item_type='.intval($cat_item_type).', ';
	$sql .= 'cat_gmap_mode='.intval($cat_gmap_mode).', ';
	$sql .= 'cat_gmap_latitude='.floatval($cat_gmap_latitude).', ';
	$sql .= 'cat_gmap_longitude='.floatval($cat_gmap_longitude).', ';
	$sql .= 'cat_gmap_zoom='.intval($cat_gmap_zoom).', ';
	$sql .= 'cat_gmap_type='.intval($cat_gmap_type).', ';
	$sql .= 'cat_perm_read='.$this->quote($cat_perm_read).', ';
	$sql .= 'cat_perm_post='.$this->quote($cat_perm_post).', ';

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_CAT_TEXT; $i++ ) {
		$name = 'cat_text'.$i;
		$sql .= $name .'='. $this->quote( $row[ $name ] ).', ';
	}

	$sql .= 'cat_description='.$this->quote($cat_description).' ';
	$sql .= 'WHERE cat_id='.intval($cat_id);

	return $this->query( $sql );
}

function update_pid( $cat_id, $cat_pid )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'cat_pid='.intval($cat_pid).' ';
	$sql .= 'WHERE cat_id='.intval($cat_id);

	return $this->query( $sql );
}

function update_weight( $cat_id, $cat_weight )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'cat_weight='.intval($cat_weight).' ';
	$sql .= 'WHERE cat_id='.intval($cat_id);

	return $this->query( $sql );
}

function clear_gicon_id( $gicon_id )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'cat_gicon_id=0 ';
	$sql .= 'WHERE cat_gicon_id='.intval($gicon_id);

	return $this->query( $sql );
}

//---------------------------------------------------------
// cached
//---------------------------------------------------------
function get_cached_title_by_id( $cat_id, $flag_sanitize=false )
{
	return $this->get_cached_value_by_id_name( $cat_id, 'cat_title', $flag_sanitize );
}

//---------------------------------------------------------
// rows
//---------------------------------------------------------
function get_rows_ghost()
{
	$live_cids = $this->get_all_child_id( 0 );

	$where = 'cat_id NOT IN ( ' ;
	foreach( $live_cids as $cid ) {
		$where .= $cid.', ';
	}
	$where .= ' 0 )' ;

	return $this->get_rows_by_where( $where );
}

function get_rows_by_pid( $pid, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->_table;
	$sql .= ' WHERE cat_pid='. $pid;
	$sql .= ' ORDER BY cat_title ASC';
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function get_rows_by_pid_orderby( $pid, $order, $limit=0, $offset=0 )
{
	return $this->get_rows_by_pid_order( $pid, $order, $limit, $offset );
}

//---------------------------------------------------------
// for show
//---------------------------------------------------------
function build_show_desc_disp( $row )
{
	$myts =& MyTextSanitizer::getInstance();
	return $myts->displayTarea( $row['cat_description'] , 0 , 1 , 1 , 1 , 1 , 1 );
}

function build_show_img_path( $row )
{
	$img_path = $row['cat_img_path'] ;
	if ( $this->check_http_null( $img_path ) ) {
		$url = '' ;
	} elseif ( $this->check_http_start( $img_path ) ) {
		$url = $img_path;
	} else {
		$url = XOOPS_URL . $this->add_slash_to_head( $img_path );
	}
	return $url;
}

function check_http_null( $str )
{
	if ( ($str == '') || ($str == 'http://') || ($str == 'https://') ) {
		return true;
	}
	return false;
}

function check_http_start( $str )
{
	if ( preg_match("|^https?://|", $str) ) {
		return true;	// include HTTP
	}
	return false;
}

function add_slash_to_head( $str )
{
// ord : the ASCII value of the first character of string
// 0x2f slash

	if( ord( $str ) != 0x2f ) {
		$str = "/". $str;
	}
	return $str;
}

//---------------------------------------------------------
// selbox
//---------------------------------------------------------
function build_selbox_catid( $cat_id, $sel_name='cat_id' )
{
	return $this->make_my_sel_box( 'cat_title', '', $cat_id, 0, $sel_name );
}

function build_selbox_pid( $pid )
{
	return $this->make_my_sel_box( 'cat_title', '', $pid, 1, 'cat_pid' );
}

function build_selbox_with_perm_post( $cat_id, $sel_name )
{
	return $this->build_sel_box(
		$this->get_all_tree_array( '', true ), 
		'cat_title', $cat_id, 0, $sel_name ) ;
}

//---------------------------------------------------------
// overwrite
//---------------------------------------------------------
function build_rows_with_perm( $rows )
{
	$arr = array();
	foreach ( $rows as $row ) 
	{
		if ( $this->check_perm_post( $row['cat_perm_post'] ) ) {
			$arr[] = $row;
		}
	}
	return $arr;
}

function check_perm_post( $perm_post )
{
	if ( $perm_post == $this->_PREM_ALLOW_ALL ) {
		return true;
	}
	if ( $perm_post == $this->_PREM_DENOY_ALL ) {
		return false;
	}

	$perms = $this->str_to_array( $perm_post, $this->_PREM_SEPARATOR );
	return $this->check_perms_in_groups( $perms, $this->_xoops_groups );
}



// --- class end ---
}

?>