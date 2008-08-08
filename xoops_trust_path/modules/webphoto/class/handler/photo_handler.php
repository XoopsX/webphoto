<?php
// $Id: photo_handler.php,v 1.3 2008/08/08 04:36:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-01 K.OHWADA
// added force in insert() update()
// added get_rows_public_imode_by_orderby()
// 2008-07-01 K.OHWADA
// removed build_row_by_photo_info() build_row_by_thumb_info()
//--------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_photo_handler
//=========================================================
class webphoto_photo_handler extends webphoto_lib_handler
{
	var $_AREA_NS = 1.0;
	var $_AREA_EW = 1.0;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_photo_handler( $dirname )
{
	$this->webphoto_lib_handler( $dirname );
	$this->set_table_prefix_dirname( 'photo' );
	$this->set_id_name( 'photo_id' );

	$constpref = strtoupper( '_P_' . $dirname. '_' ) ;
	$this->set_debug_sql_by_const_name(   $constpref.'DEBUG_SQL' );
	$this->set_debug_error_by_const_name( $constpref.'DEBUG_ERROR' );

}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_photo_handler( $dirname );
	}
	return $instance;
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
		'photo_id'             => 0,
		'photo_time_create'    => $time_create,
		'photo_time_update'    => $time_update,
		'photo_cat_id'         => 0,
		'photo_gicon_id'        => 0,
		'photo_uid'            => 0,
		'photo_datetime'       => '0000-00-00 00:00:00',
		'photo_title'          => '',
		'photo_place'          => '',
		'photo_equipment'      => '',
		'photo_file_url'       => '',
		'photo_file_path'      => '',
		'photo_file_name'      => '',
		'photo_file_ext'       => '',
		'photo_file_mime'      => '',
		'photo_file_medium'    => '',
		'photo_file_size'      => 0,
		'photo_cont_url'       => '',
		'photo_cont_path'      => '',
		'photo_cont_name'      => '',
		'photo_cont_ext'       => '',
		'photo_cont_mime'      => '',
		'photo_cont_medium'    => '',
		'photo_cont_size'      => 0,
		'photo_cont_width'     => 0,
		'photo_cont_height'    => 0,
		'photo_cont_duration'  => 0,
		'photo_cont_exif'      => '',
		'photo_middle_width'   => 0,
		'photo_middle_height'  => 0,
		'photo_thumb_url'      => '',
		'photo_thumb_path'     => '',
		'photo_thumb_name'     => '',
		'photo_thumb_ext'      => '',
		'photo_thumb_mime'     => '',
		'photo_thumb_medium'   => '',
		'photo_thumb_size'     => 0,
		'photo_thumb_width'    => 0,
		'photo_thumb_height'   => 0,
		'photo_gmap_latitude'  => 0,
		'photo_gmap_longitude' => 0,
		'photo_gmap_zoom'      => 0,
		'photo_gmap_type'      => 0,
		'photo_perm_read'      => '*',
		'photo_status'         => 0,
		'photo_hits'           => 0,
		'photo_rating'         => 0,
		'photo_votes'          => 0,
		'photo_comments'       => 0,
		'photo_description'    => '',
		'photo_search'         => '',
	);

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_PHOTO_TEXT; $i++ ) {
		$arr[ 'photo_text'.$i ] = '';
	}

	return $arr;
}

//---------------------------------------------------------
// insert
//---------------------------------------------------------
function insert( $row, $force=false )
{
	extract( $row ) ;

	$sql  = 'INSERT INTO '.$this->_table.' (';

	if ( $photo_id > 0 ) {
		$sql .= 'photo_id, ';
	}

	$sql .= 'photo_time_create, ';
	$sql .= 'photo_time_update, ';
	$sql .= 'photo_cat_id, ';
	$sql .= 'photo_gicon_id, ';
	$sql .= 'photo_uid, ';
	$sql .= 'photo_datetime, ';
	$sql .= 'photo_title, ';
	$sql .= 'photo_place, ';
	$sql .= 'photo_equipment, ';
	$sql .= 'photo_file_url, ';
	$sql .= 'photo_file_path, ';
	$sql .= 'photo_file_name, ';
	$sql .= 'photo_file_ext, ';
	$sql .= 'photo_file_mime, ';
	$sql .= 'photo_file_medium, ';
	$sql .= 'photo_file_size, ';
	$sql .= 'photo_cont_url, ';
	$sql .= 'photo_cont_path, ';
	$sql .= 'photo_cont_name, ';
	$sql .= 'photo_cont_ext, ';
	$sql .= 'photo_cont_mime, ';
	$sql .= 'photo_cont_medium, ';
	$sql .= 'photo_cont_size, ';
	$sql .= 'photo_cont_width, ';
	$sql .= 'photo_cont_height, ';
	$sql .= 'photo_cont_duration, ';
	$sql .= 'photo_cont_exif, ';
	$sql .= 'photo_middle_width, ';
	$sql .= 'photo_middle_height, ';
	$sql .= 'photo_thumb_url, ';
	$sql .= 'photo_thumb_path, ';
	$sql .= 'photo_thumb_name, ';
	$sql .= 'photo_thumb_ext, ';
	$sql .= 'photo_thumb_mime, ';
	$sql .= 'photo_thumb_medium, ';
	$sql .= 'photo_thumb_size, ';
	$sql .= 'photo_thumb_width, ';
	$sql .= 'photo_thumb_height, ';
	$sql .= 'photo_gmap_latitude, ';
	$sql .= 'photo_gmap_longitude, ';
	$sql .= 'photo_gmap_zoom, ';
	$sql .= 'photo_gmap_type, ';
	$sql .= 'photo_perm_read, ';
	$sql .= 'photo_status, ';
	$sql .= 'photo_hits, ';
	$sql .= 'photo_rating, ';
	$sql .= 'photo_votes, ';
	$sql .= 'photo_comments, ';
	$sql .= 'photo_description, ';

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_PHOTO_TEXT; $i++ ) {
		$sql .= 'photo_text'.$i.', ';
	}

	$sql .= 'photo_search ';

	$sql .= ') VALUES ( ';

	if ( $photo_id > 0 ) {
		$sql .= intval($photo_id).', ';
	}

	$sql .= intval($photo_time_create).', ';
	$sql .= intval($photo_time_update).', ';
	$sql .= intval($photo_cat_id).', ';
	$sql .= intval($photo_gicon_id).', ';
	$sql .= intval($photo_uid).', ';
	$sql .= $this->quote($photo_datetime).', ';
	$sql .= $this->quote($photo_title).', ';
	$sql .= $this->quote($photo_place).', ';
	$sql .= $this->quote($photo_equipment).', ';
	$sql .= $this->quote($photo_file_url).', ';
	$sql .= $this->quote($photo_file_path).', ';
	$sql .= $this->quote($photo_file_name).', ';
	$sql .= $this->quote($photo_file_ext).', ';
	$sql .= $this->quote($photo_file_mime).', ';
	$sql .= $this->quote($photo_file_medium).', ';
	$sql .= intval($photo_file_size).', ';
	$sql .= $this->quote($photo_cont_url).', ';
	$sql .= $this->quote($photo_cont_path).', ';
	$sql .= $this->quote($photo_cont_name).', ';
	$sql .= $this->quote($photo_cont_ext).', ';
	$sql .= $this->quote($photo_cont_mime).', ';
	$sql .= $this->quote($photo_cont_medium).', ';
	$sql .= intval($photo_cont_size).', ';
	$sql .= intval($photo_cont_width).', ';
	$sql .= intval($photo_cont_height).', ';
	$sql .= intval($photo_cont_duration).', ';
	$sql .= $this->quote($photo_cont_exif).', ';
	$sql .= intval($photo_middle_width).', ';
	$sql .= intval($photo_middle_height).', ';
	$sql .= $this->quote($photo_thumb_url).', ';
	$sql .= $this->quote($photo_thumb_path).', ';
	$sql .= $this->quote($photo_thumb_name).', ';
	$sql .= $this->quote($photo_thumb_ext).', ';
	$sql .= $this->quote($photo_thumb_mime).', ';
	$sql .= $this->quote($photo_thumb_medium).', ';
	$sql .= intval($photo_thumb_size).', ';
	$sql .= intval($photo_thumb_width).', ';
	$sql .= intval($photo_thumb_height).', ';
	$sql .= floatval($photo_gmap_latitude).', ';
	$sql .= floatval($photo_gmap_longitude).', ';
	$sql .= intval($photo_gmap_zoom).', ';
	$sql .= intval($photo_gmap_type).', ';
	$sql .= $this->quote($photo_perm_read).', ';
	$sql .= intval($photo_status).', ';
	$sql .= intval($photo_hits).', ';
	$sql .= floatval($photo_rating).', ';
	$sql .= intval($photo_votes).', ';
	$sql .= intval($photo_comments).', ';
	$sql .= $this->quote($photo_description).', ';

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_PHOTO_TEXT; $i++ ) {
		$sql .= $this->quote( $row[ 'photo_text'.$i ] ).', ';
	}

	$sql .= $this->quote($photo_search).' ';

	$sql .= ')';

	$ret = $this->query( $sql, 0, 0, $force );
	if ( !$ret ) { return false; }

	return $this->_db->getInsertId();
}

//---------------------------------------------------------
// update
//---------------------------------------------------------
function update( $row, $force=false )
{
	extract( $row ) ;

	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'photo_time_create='.intval($photo_time_create).', ';
	$sql .= 'photo_time_update='.intval($photo_time_update).', ';
	$sql .= 'photo_cat_id='.intval($photo_cat_id).', ';
	$sql .= 'photo_gicon_id='.intval($photo_gicon_id).', ';
	$sql .= 'photo_uid='.intval($photo_uid).', ';
	$sql .= 'photo_datetime='.$this->quote($photo_datetime).', ';
	$sql .= 'photo_title='.$this->quote($photo_title).', ';
	$sql .= 'photo_place='.$this->quote($photo_place).', ';
	$sql .= 'photo_equipment='.$this->quote($photo_equipment).', ';
	$sql .= 'photo_file_url='.$this->quote($photo_file_url).', ';
	$sql .= 'photo_file_path='.$this->quote($photo_file_path).', ';
	$sql .= 'photo_file_name='.$this->quote($photo_file_name).', ';
	$sql .= 'photo_file_ext='.$this->quote($photo_file_ext).', ';
	$sql .= 'photo_file_mime='.$this->quote($photo_file_mime).', ';
	$sql .= 'photo_file_medium='.$this->quote($photo_file_medium).', ';
	$sql .= 'photo_file_size='.intval($photo_file_size).', ';
	$sql .= 'photo_cont_url='.$this->quote($photo_cont_url).', ';
	$sql .= 'photo_cont_path='.$this->quote($photo_cont_path).', ';
	$sql .= 'photo_cont_name='.$this->quote($photo_cont_name).', ';
	$sql .= 'photo_cont_ext='.$this->quote($photo_cont_ext).', ';
	$sql .= 'photo_cont_mime='.$this->quote($photo_cont_mime).', ';
	$sql .= 'photo_cont_medium='.$this->quote($photo_cont_medium).', ';
	$sql .= 'photo_cont_size='.intval($photo_cont_size).', ';
	$sql .= 'photo_cont_width='.intval($photo_cont_width).', ';
	$sql .= 'photo_cont_height='.intval($photo_cont_height).', ';
	$sql .= 'photo_cont_duration='.intval($photo_cont_duration).', ';
	$sql .= 'photo_cont_exif='.$this->quote($photo_cont_exif).', ';
	$sql .= 'photo_middle_width='.intval($photo_middle_width).', ';
	$sql .= 'photo_middle_height='.intval($photo_middle_height).', ';
	$sql .= 'photo_thumb_url='.$this->quote($photo_thumb_url).', ';
	$sql .= 'photo_thumb_path='.$this->quote($photo_thumb_path).', ';
	$sql .= 'photo_thumb_name='.$this->quote($photo_thumb_name).', ';
	$sql .= 'photo_thumb_ext='.$this->quote($photo_thumb_ext).', ';
	$sql .= 'photo_thumb_mime='.$this->quote($photo_thumb_mime).', ';
	$sql .= 'photo_thumb_medium='.$this->quote($photo_thumb_medium).', ';
	$sql .= 'photo_thumb_size='.intval($photo_thumb_size).', ';
	$sql .= 'photo_thumb_width='.intval($photo_thumb_width).', ';
	$sql .= 'photo_thumb_height='.intval($photo_thumb_height).', ';
	$sql .= 'photo_gmap_latitude='.floatval($photo_gmap_latitude).', ';
	$sql .= 'photo_gmap_longitude='.floatval($photo_gmap_longitude).', ';
	$sql .= 'photo_gmap_zoom='.intval($photo_gmap_zoom).', ';
	$sql .= 'photo_gmap_type='.intval($photo_gmap_type).', ';
	$sql .= 'photo_perm_read='.$this->quote($photo_perm_read).', ';
	$sql .= 'photo_status='.intval($photo_status).', ';
	$sql .= 'photo_hits='.intval($photo_hits).', ';
	$sql .= 'photo_rating='.floatval($photo_rating).', ';
	$sql .= 'photo_votes='.intval($photo_votes).', ';
	$sql .= 'photo_comments='.intval($photo_comments).', ';
	$sql .= 'photo_description='.$this->quote($photo_description).', ';

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_PHOTO_TEXT; $i++ ) 
	{
		$name = 'photo_text'.$i;
		$sql .= $name .'='. $this->quote( $row[ $name ] ).', ';
	}

	$sql .= 'photo_search='.$this->quote($photo_search).' ';
	$sql .= 'WHERE photo_id='.intval($photo_id);

	return $this->query( $sql, 0, 0, $force );
}

function update_status_by_id_array( $id_array )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'photo_status=1 ';
	$sql .= 'WHERE '.$this->build_where_by_photoid_array( $id_array );

	return $this->query( $sql );
}

function update_rating_by_id( $photo_id, $rating, $votes )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'photo_rating='. floatval($rating) .', ';
	$sql .= 'photo_votes='. intval($votes) .' ';
	$sql .= 'WHERE photo_id='. intval($photo_id);

	return $this->query( $sql );
}

function clear_gicon_id( $gicon_id )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'photo_gicon_id=0 ';
	$sql .= 'WHERE photo_gicon_id='.intval($gicon_id);

	return $this->query( $sql );
}

// when GET request
function update_hits( $photo_id )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'photo_hits = photo_hits+1 ';
	$sql .= 'WHERE '. $this->build_where_public();
	$sql .= 'AND photo_id='.intval($photo_id);

	return $this->queryF( $sql );
}

//---------------------------------------------------------
// get count
//---------------------------------------------------------
function get_count_public()
{
	$where = $this->build_where_public();
	return $this->get_count_by_where( $where );
}

function get_count_waiting()
{
	return $this->get_count_by_where( $this->build_where_waiting() );
}

function get_count_by_catid( $cat_id )
{
	$where = 'photo_cat_id='.intval($cat_id);
	return $this->get_count_by_where( $where );
}

function get_count_public_by_catid( $cat_id )
{
	$where = $this->build_where_public_by_catid( $cat_id );
	return $this->get_count_by_where( $where );
}

function get_count_public_by_catid_array( $catid_array )
{
	$where = $this->build_where_public_by_catid_array( $catid_array );
	return $this->get_count_by_where( $where );
}

function get_count_public_by_uid( $uid )
{
	$where = $this->build_where_public_by_uid( $uid );
	return $this->get_count_by_where( $where );
}

function get_count_by_photoid_uid( $photo_id, $uid )
{
	$where  = 'photo_id='.intval($photo_id);
	$where .= ' AND photo_uid='.intval($uid);
	return $this->get_count_by_where( $where );
}

function get_count_public_by_datetime( $datetime )
{
	$where = $this->build_where_public_by_datetime( $datetime );
	return $this->get_count_by_where( $where );
}

function get_count_public_by_like_datetime( $datetime )
{
	$where = $this->build_where_public_by_like_datetime( $datetime );
	return $this->get_count_by_where( $where );
}

function get_count_public_imode()
{
	$where = $this->build_where_public_imode();
	return $this->get_count_by_where( $where );
}

//---------------------------------------------------------
// get row
//---------------------------------------------------------
function get_row_public_by_id( $photo_id )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' WHERE '. $this->build_where_public();
	$sql .= ' AND photo_id='.intval($photo_id);
	return $this->get_row_by_sql( $sql );
}

function get_title_by_id( $photo_id )
{
	$row = $this->get_row_by_id( $photo_id );
	if ( is_array($row) ) {
		return $row['photo_title'] ;
	}
	return false;
}

//---------------------------------------------------------
// get rows
//---------------------------------------------------------
function get_rows_public( $limit=0, $offset=0 )
{
	$where   = $this->build_where_public();
	$orderby = 'photo_id ASC';
	return $this->get_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_public_latest( $limit=0, $offset=0 )
{
	$where   = $this->build_where_public();
	$orderby = 'photo_time_update DESC, photo_id DESC';
	return $this->get__rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_public_by_orderby( $orderby, $limit=0, $offset=0  )
{
	$where = $this->build_where_public();
	return $this->get_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_public_by_catid_orderby( $cat_id, $orderby, $limit=0, $offset=0  )
{
	$where  = $this->build_where_public();
	$where .= ' AND photo_cat_id='.intval($cat_id);
	return $this->get_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_public_by_uid_orderby( $uid, $orderby, $limit=0, $offset=0 )
{
	$where = $this->build_where_public_by_uid( $uid );
	return $this->get_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_public_by_datetime_orderby( $datetime, $orderby, $limit=0, $offset=0 )
{
	$where = $this->build_where_public_by_datetime( $datetime );
	return $this->get_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_public_imode_by_orderby( $orderby, $limit=0, $offset=0 )
{
	$where = $this->build_where_public_imode();
	return $this->get_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_waiting( $limit=0, $offset=0 )
{
	$where   = $this->build_where_waiting();
	$orderby = 'photo_id ASC';
	return $this->get_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_by_catid( $cat_id, $limit=0, $offset=0 )
{
	$where   = 'photo_cat_id='.intval($cat_id);
	$orderby = 'photo_id ASC';
	return $this->get_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_by_id_array( $id_array, $limit=0, $offset=0  )
{
	$where = $this->build_where_by_photoid_array( $id_array );
	$orderby = 'photo_id ASC';
	return $this->get_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_public_gmap_latest( $limit=0, $offset=0 )
{
	$where   = $this->build_where_public();
	$where  .= ' AND '. $this->build_where_gmap();
	$orderby = 'photo_time_update DESC, photo_id DESC';
	return $this->get_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_public_gmap_latest_by_catid_array( $catid_array, $limit=0, $offset=0 )
{
	$where   = $this->build_where_public_by_catid_array( $catid_array );
	$where  .= ' AND '. $this->build_where_gmap();
	$orderby = 'photo_time_update DESC, photo_id DESC';
	return $this->get_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_public_gmap_area( $photo_id, $lat, $lon, $limit=0, $offset=0 )
{
	$where   = $this->build_where_public();
	$where  .= ' AND '. $this->build_where_gmap();
	$where  .= ' AND '. $this->build_where_gmap_area( $lat, $lon );
	$where  .= ' AND photo_id <> '. intval($photo_id);
	$orderby = 'photo_id ASC';
	return $this->get_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_public_catlist( $limit=0, $offset=0 )
{
	$sql  = 'SELECT *, COUNT(photo_id) AS cat_sum ';
	$sql .= ' FROM '. $this->_table;
	$sql .= ' WHERE '. $this->build_where_public();
	$sql .= ' GROUP BY photo_cat_id' ;
	$sql .= ' ORDER BY photo_cat_id' ;
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function get_rows_from_id_array( $id_array )
{
	$arr = array();
	foreach ( $id_array as $id ) {
		$arr[] = $this->get_row_by_id( $id ) ;
	}
	return $arr;
}

//---------------------------------------------------------
// get id array
//---------------------------------------------------------
function get_id_array_public_by_catid_orderby( $cat_id, $orderby, $limit=0, $offset=0 )
{
	$where  = $this->build_where_public();
	$where .= ' AND photo_cat_id='.intval($cat_id);
	return $this->get_id_array_by_where_orderby( $where, $orderby, $limit, $offset );
}

//---------------------------------------------------------
// build
//---------------------------------------------------------
function build_search( $row )
{
	$text  = '';
	$text .= $row['photo_datetime'].' ';
	$text .= $row['photo_title'].' ';
	$text .= $row['photo_place'].' ';
	$text .= $row['photo_equipment'].' ';

	$text .= $row['photo_description'].' ';
	$text .= $row['photo_file_ext'].' ';

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_PHOTO_TEXT; $i++ ) {
		$text .= $row[ 'photo_text'.$i ].' ';
	}

	return $text;
}

//---------------------------------------------------------
// where
//---------------------------------------------------------
function build_where_public_by_catid( $cat_id )
{
	$where  = $this->build_where_public();
	$where .= ' AND photo_cat_id='.intval($cat_id);
	return $where;
}

function build_where_public_by_catid_array( $catid_array )
{
	$where  = $this->build_where_public() ;
	$where .= ' AND '. $this->build_where_by_catid_array( $catid_array );
	return $where;
}

function build_where_public_by_uid( $uid )
{
	$where  = $this->build_where_public();
	$where .= ' AND photo_uid='.intval($uid);
	return $where;
}

function build_where_public_by_datetime( $datetime )
{
	$where  = $this->build_where_public();
	$where .= ' AND photo_datetime ='. $this->quote($datetime);
	return $where;
}

function build_where_public_by_like_datetime( $datetime )
{
	$where  = $this->build_where_public();
	$where .= ' AND photo_datetime LIKE '. $this->quote( $datetime.'%' );
	return $where;
}

function build_where_public_by_place( $place )
{
	$where  = $this->build_where_public();
	$where .= ' AND photo_place ='. $this->quote($place);
	return $where;
}

function build_where_public_by_place_array( $place_array )
{
	$where  = $this->build_where_public();
	$where .= ' AND '.$this->build_where_place_array( $place_array );
	return $where;
}

function build_where_public_photo()
{
	$where  = $this->build_where_public();
	$where .= ' AND '. $this->build_where_photo();
	return $where;
}

function build_where_public_photo_by_catid( $cat_id )
{
	$where  = $this->build_where_public();
	$where .= ' AND '. $this->build_where_photo();
	$where .= ' AND photo_cat_id='. intval($cat_id);
	return $where;
}

function build_where_public_imode()
{
	$where  = $this->build_where_public();
	$where .= ' AND '. $this->build_where_imode();
	return $where;
}

function build_where_public()
{
	$where = ' photo_status > 0 ';
	return $where;
}

function build_where_waiting()
{
	$where = ' photo_status = 0 ';
	return $where;
}

function build_where_imode()
{
	$where  = " ( photo_cont_ext='gif' ";
	$where .= "OR photo_cont_ext='jpg' ";
	$where .= "OR photo_cont_ext='jpeg' ";
	$where .= "OR photo_cont_ext='3gp' ";
	$where .= "OR photo_cont_ext='3g2' )";
	return $where;
}

function build_where_photo()
{
	$where  = " ( photo_cont_ext='gif' ";
	$where .= "OR photo_cont_ext='png' ";
	$where .= "OR photo_cont_ext='jpg' ";
	$where .= "OR photo_cont_ext='jpeg' ) ";
	return $where;
}

function build_where_gmap()
{
	$where  = ' ( photo_gmap_latitude <> 0 ';
	$where .= 'OR photo_gmap_longitude <> 0 ';
	$where .= 'OR photo_gmap_zoom <> 0 ) ';
	return $where;
}

function build_where_place_array( $place_array )
{
	return $this->build_where_by_keyword_array( $place_array, 'AND', 'photo_place' );
}

function build_where_waiting_by_keyword_array( $keyword_array )
{
	$where = $this->build_where_waiting() ;
	$where_key = $this->build_where_by_keyword_array( $keyword_array );
	if ( $where_key ) {
		$where .= ' AND '.$where_key ;
	}
	return $where;
}

function build_where_by_keyword_array_catid( $keyword_array, $cat_id )
{
	$where_key = $this->build_where_by_keyword_array( $keyword_array );

	$where_cat = null;
	if ( $cat_id != 0 ) {
		$where_cat = "photo_cat_id=".intval($cat_id);
	}

	if ( $where_key && $where_cat ) {
		$where = $where_key .' AND '. $where_cat ;
		return $where;
	} elseif ( $where_key ) {
		return $where_key;
	} elseif ( $where_cat ) {
		return $where_cat;
	}

	return null;
}

function build_where_by_keyword_array( $keyword_array, $andor='AND', $name='photo_search' )
{
	if ( !is_array($keyword_array) || !count($keyword_array) ) {
		return null;
	}

	switch ( strtolower($andor) )
	{
		case 'exact':
			$where = $this->build_where_keyword_single( $keyword_array[0], $name );
			return $where;

		case 'or':
			$andor_glue = 'OR';
			break;

		case 'and':
		default:
			$andor_glue = 'AND';
			break;
	}

	$arr = array();

	foreach( $keyword_array as $keyword ) 
	{
		$keyword = trim($keyword);
		if ( $keyword ) {
			$arr[] = $this->build_where_keyword_single( $keyword, $name ) ;
		}
	}

	if ( is_array( $arr ) && count( $arr ) ) {
		$glue  = ' '. $andor_glue .' ';
		$where = ' ( '. implode( $glue , $arr ) .' ) ' ;
		return $where;
	}

	return null;
}

function build_where_keyword_single( $str, $name='photo_search' )
{
	$text = $name ." LIKE '%" . addslashes( $str ) . "%'" ;
	return $text;
}

function build_where_by_photoid_array( $id_array )
{
	$where = '';
	foreach( $id_array as $id ) {
		$where .= 'photo_id='. intval($id) .' OR ';
	}

// 0 means to belong no category
	$where .= '0';
	return $where;
}

function build_where_by_catid_array( $catid_array )
{
	$where  = ' photo_cat_id IN ( ' ;
	foreach( $catid_array as $id ) {
		$where .= intval($id) .', ';
	}

// 0 means to belong no category	
	$where .= ' 0 )';
	return $where;
}

//---------------------------------------------------------
// build gmap
//---------------------------------------------------------
function build_where_gmap_area( $lat, $lon )
{
	$north = $this->adjust_latitude(  $lat + $this->_AREA_NS );
	$south = $this->adjust_latitude(  $lat - $this->_AREA_NS );
	$east  = $this->adjust_longitude( $lon + $this->_AREA_EW );
	$west  = $this->adjust_longitude( $lon - $this->_AREA_EW );

	$where  = ' photo_gmap_latitude > '.floatval($south);
	$where .= ' AND photo_gmap_latitude < '.floatval($north);
	$where .= ' AND photo_gmap_longitude > '.floatval($west);
	$where .= ' AND photo_gmap_longitude < '.floatval($east);
	return $where;
}

function adjust_latitude( $lat )
{
// north pole
	if ( $lat > 90 ) {
		$lat = 90;

// south pole
	} elseif ( $lat < -90 ) {
		$lat = -90;
	}

	return $lat;
}

function adjust_longitude( $lon )
{
// international date line
	if ( $lon > 180 ) {
		$lon = -360 + $lon;
	} elseif ( $lon < -180 ) {
		$lon = 360 + $lon;
	}
	return $lon;
}

//---------------------------------------------------------
// build datetime
//---------------------------------------------------------
function build_datetime_by_post( $key, $default=null )
{
	$val = isset($_POST[$key]) ? $_POST[$key] : $default;
	return $this->build_datetime( $val );
}

function build_datetime( $str )
{
	$utility_class =& webphoto_lib_utility::getInstance();
	return $utility_class->str_to_mysql_datetime( $str );
}

//---------------------------------------------------------
// for show
//---------------------------------------------------------
function build_show_description_disp( $row )
{
	$myts =& MyTextSanitizer::getInstance();
	return $myts->displayTarea( $row['photo_description'] , 0 , 1 , 1 , 1 , 1  );
}

function build_show_cont_exif_disp( $row )
{
	$myts =& MyTextSanitizer::getInstance();
	return $myts->displayTarea( $row['photo_cont_exif'] , 0 , 0 , 0 , 0 , 1 );
}

//---------------------------------------------------------
// for comment_new
//---------------------------------------------------------
function get_replytitle()
{
	$com_itemid = isset( $_GET['com_itemid'] ) ? intval( $_GET['com_itemid'] ) : 0 ;

	if ( $com_itemid > 0 ) {
		return $this->get_title_by_id( $com_itemid );
	}
	return null;
}

// --- class end ---
}

?>