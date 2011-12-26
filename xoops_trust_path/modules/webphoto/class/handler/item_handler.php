<?php
// $Id: item_handler.php,v 1.25 2011/12/26 06:51:31 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// str_to_mysql_datetime()
// 2010-11-11 K.OHWADA
// file_id_to_item_name()
// 2010-10-01 K.OHWADA
// item_displayfile etc
// 2010-03-18 K.OHWADA
// change insert update
// 2010-02-15 K.OHWADA
// add $flag_admin in check_perm_by_row_name_groups()
// 2010-01-10 K.OHWADA
// item_description_scroll
// 2009-12-06 K.OHWADA
// item_perm_level
// 2009-11-11 K.OHWADA
// webphoto_lib_handler -> webphoto_handler_base_ini
// info_array_to_str()
// item_detail_onclick
// 2009-10-25 K.OHWADA
// _C_WEBPHOTO_CODEINFO_JPEG 
// 2009-01-25 K.OHWADA
// _C_WEBPHOTO_CODEINFO_SWF
// 2009-01-10 K.OHWADA
// item_content etc
// 2009-01-04 K.OHWADA
// item_editor
// 2008-12-12 K.OHWADA
// check_perm_by_row_name_groups()
// move get_rows_public() to item_cat_handler
// 2008-12-07 K.OHWADA
// get_text_type_array()
// 2008-11-29 K.OHWADA
// item_icon_width
// get_rows_waiting() -> get_rows_status()
// 2008-11-16 K.OHWADA
// item_codeinfo
// 2008-11-08 K.OHWADA
// item_external_middle
// 2008-10-10 K.OHWADA
// item_embed_type item_playlist_srctype etc
// 2008-10-01 K.OHWADA
// BUG: Incorrect integer value: 'item_file_id_1'
// error in mysql 5 if datetime is null
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_item_handler
//=========================================================
class webphoto_item_handler extends webphoto_handler_base_ini
{
	var $_BUILD_SEARCH_ARRAY ;
	var $_TEXT_ARRAY ;
	var $_ENCODE_ARRAY ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_item_handler( $dirname, $trust_dirname )
{
	$this->webphoto_handler_base_ini( $dirname, $trust_dirname );
	$this->set_table_prefix_dirname( 'item' );
	$this->set_id_name(    'item_id' );
	$this->set_title_name( 'item_title' );

	$this->_BUILD_SEARCH_ARRAY = $this->item_explode_ini( 'item_build_search_list' );
	$this->_TEXT_ARRAY         = $this->item_explode_ini( 'item_text_list' );
	$this->_ENCODE_ARRAY       = $this->item_explode_ini( 'item_encode_list' );

}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_item_handler( $dirname, $trust_dirname );
	}
	return $instance;
}

function item_explode_ini( $name )
{
	return $this->explode_ini( $name, '|', 'item_' );
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
		'item_id'              => 0,
		'item_time_create'     => $time_create,
		'item_time_update'     => $time_update,
		'item_time_publish'    => 0 ,
		'item_time_expire'     => 0 ,
		'item_cat_id'          => 0,
		'item_gicon_id'        => 0,
		'item_player_id'       => 0,
		'item_flashvar_id'     => 0,
		'item_uid'             => 0,
		'item_ext'             => '',
		'item_title'           => '',
		'item_place'           => '',
		'item_equipment'       => '',
		'item_gmap_latitude'   => 0,
		'item_gmap_longitude'  => 0,
		'item_gmap_zoom'       => 0,
		'item_gmap_type'       => 0,
		'item_status'          => 0,
		'item_hits'            => 0,
		'item_rating'          => 0,
		'item_votes'           => 0,
		'item_comments'        => 0,
		'item_exif'            => '',
		'item_description'     => '',
		'item_search'          => '',
		'item_duration'        => 0,
		'item_width'           => 0,
		'item_height'          => 0,
		'item_siteurl'         => '',
		'item_artist'          => '',
		'item_album'           => '',
		'item_label'           => '',
		'item_displaytype'     => 0,
		'item_displayfile'     => 0,
		'item_onclick'         => 0,
		'item_views'           => 0,
		'item_chain'           => 0,
		'item_icon_name'       => '',
		'item_icon_width'      => 0,
		'item_icon_height'     => 0,
		'item_external_url'    => '',
		'item_external_thumb'  => '',
		'item_external_middle' => '',
		'item_embed_type'      => '',
		'item_embed_src'       => '',
		'item_embed_text'      => '',
		'item_playlist_feed'   => '',
		'item_playlist_dir'    => '',
		'item_playlist_cache'  => '',
		'item_playlist_type'   => 0,
		'item_page_width'      => 0,
		'item_page_height'     => 0,
		'item_content'         => '',
		'item_detail_onclick'  => 0,
		'item_weight'          => 0,
		'item_kind'            => $this->get_ini('item_kind_default') ,
		'item_datetime'        => $this->get_ini('item_datetime_default') ,
		'item_playlist_time'   => $this->get_ini('item_playlist_time_defualt') ,
		'item_showinfo'        => $this->get_ini('item_showinfo_default') ,
		'item_codeinfo'        => $this->get_ini('item_codeinfo_default') ,
		'item_perm_read'       => $this->get_ini('item_perm_read_default') ,
		'item_perm_down'       => $this->get_ini('item_perm_down_default') ,
		'item_perm_level'      => $this->get_ini('item_perm_level_default') ,
		'item_editor'          => $this->get_ini('item_editor_default') ,
		'item_description_html'   => $this->get_ini('item_description_html_default') ,
		'item_description_smiley' => $this->get_ini('item_description_smiley_default') ,
		'item_description_xcode'  => $this->get_ini('item_description_xcode_default') ,
		'item_description_image'  => $this->get_ini('item_description_image_default') ,
		'item_description_br'     => $this->get_ini('item_description_br_default') ,
		'item_description_scroll' => $this->get_ini('item_description_scroll_default') ,
	);

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; $i++ ) {
		$name = $this->file_id_to_item_name( $i );
		$arr[ $name ] = 0;
	}

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; $i++ ) {
		$name = $this->text_id_to_item_name( $i );
		$arr[ $name ] = '';
	}

	return $arr;
}

function file_id_to_item_name( $id )
{
	$str = 'item_file_id_'. $id ;
	return $str;
}

function text_id_to_item_name( $id )
{
	$str = 'item_text_'. $id ;
	return $str;
}

function build_name_fileid_by_kind( $kind )
{
	return $this->file_id_to_item_name( $kind );
}

function build_name_text_by_kind( $kind )
{
	return $this->text_id_to_item_name( $kind );
}

//---------------------------------------------------------
// insert
//---------------------------------------------------------
function insert( $row, $force=false )
{
	extract( $row ) ;

	$sql  = 'INSERT INTO '.$this->_table.' (';

	if ( $item_id > 0 ) {
		$sql .= 'item_id, ';
	}

	$sql .= 'item_time_create, ';
	$sql .= 'item_time_update, ';
	$sql .= 'item_time_publish, ';
	$sql .= 'item_time_expire, ';
	$sql .= 'item_cat_id, ';
	$sql .= 'item_gicon_id, ';
	$sql .= 'item_player_id, ';
	$sql .= 'item_flashvar_id, ';
	$sql .= 'item_uid, ';
	$sql .= 'item_kind, ';
	$sql .= 'item_ext, ';
	$sql .= 'item_datetime, ';
	$sql .= 'item_title, ';
	$sql .= 'item_place, ';
	$sql .= 'item_equipment, ';
	$sql .= 'item_gmap_latitude, ';
	$sql .= 'item_gmap_longitude, ';
	$sql .= 'item_gmap_zoom, ';
	$sql .= 'item_gmap_type, ';
	$sql .= 'item_perm_read, ';
	$sql .= 'item_perm_down, ';
	$sql .= 'item_status, ';
	$sql .= 'item_hits, ';
	$sql .= 'item_rating, ';
	$sql .= 'item_votes, ';
	$sql .= 'item_comments, ';
	$sql .= 'item_exif, ';
	$sql .= 'item_description, ';
	$sql .= 'item_duration, ';
	$sql .= 'item_width, ';
	$sql .= 'item_height, ';
	$sql .= 'item_displaytype, ';
	$sql .= 'item_displayfile, ';
	$sql .= 'item_onclick, ';
	$sql .= 'item_views, ';
	$sql .= 'item_chain, ';
	$sql .= 'item_siteurl, ';
	$sql .= 'item_artist, ';
	$sql .= 'item_album, ';
	$sql .= 'item_label, ';
	$sql .= 'item_icon_name, ';
	$sql .= 'item_icon_width, ';
	$sql .= 'item_icon_height, ';
	$sql .= 'item_external_url, ';
	$sql .= 'item_external_thumb, ';
	$sql .= 'item_external_middle, ';
	$sql .= 'item_embed_type, ';
	$sql .= 'item_embed_src, ';
	$sql .= 'item_embed_text, ';
	$sql .= 'item_playlist_type, ';
	$sql .= 'item_playlist_time, ';
	$sql .= 'item_playlist_feed, ';
	$sql .= 'item_playlist_dir, ';
	$sql .= 'item_playlist_cache, ';
	$sql .= 'item_showinfo, ';
	$sql .= 'item_codeinfo, ';
	$sql .= 'item_page_width, ';
	$sql .= 'item_page_height, ';
	$sql .= 'item_editor, ';
	$sql .= 'item_description_html, ';
	$sql .= 'item_description_smiley, ';
	$sql .= 'item_description_xcode, ';
	$sql .= 'item_description_image, ';
	$sql .= 'item_description_br, ';
	$sql .= 'item_description_scroll, ';
	$sql .= 'item_content, ';
	$sql .= 'item_detail_onclick, ';
	$sql .= 'item_weight, ';
	$sql .= 'item_perm_level, ';

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; $i++ ) {
		$name = $this->file_id_to_item_name( $i );
		$sql .= $name.', ';
	}

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; $i++ ) {
		$name = $this->text_id_to_item_name( $i );
		$sql .= $name.', ';
	}

	$sql .= 'item_search ';

	$sql .= ') VALUES ( ';

	if ( $item_id > 0 ) {
		$sql .= intval($item_id).', ';
	}

	$sql .= intval($item_time_create).', ';
	$sql .= intval($item_time_update).', ';
	$sql .= intval($item_time_publish).', ';
	$sql .= intval($item_time_expire).', ';
	$sql .= intval($item_cat_id).', ';
	$sql .= intval($item_gicon_id).', ';
	$sql .= intval($item_player_id).', ';
	$sql .= intval($item_flashvar_id).', ';
	$sql .= intval($item_uid).', ';
	$sql .= intval($item_kind).', ';
	$sql .= $this->quote($item_ext).', ';
	$sql .= $this->quote($item_datetime).', ';
	$sql .= $this->quote($item_title).', ';
	$sql .= $this->quote($item_place).', ';
	$sql .= $this->quote($item_equipment).', ';
	$sql .= floatval($item_gmap_latitude).', ';
	$sql .= floatval($item_gmap_longitude).', ';
	$sql .= intval($item_gmap_zoom).', ';
	$sql .= intval($item_gmap_type).', ';
	$sql .= $this->quote($item_perm_read).', ';
	$sql .= $this->quote($item_perm_down).', ';
	$sql .= intval($item_status).', ';
	$sql .= intval($item_hits).', ';
	$sql .= floatval($item_rating).', ';
	$sql .= intval($item_votes).', ';
	$sql .= intval($item_comments).', ';
	$sql .= $this->quote($item_exif).', ';
	$sql .= $this->quote($item_description).', ';
	$sql .= intval($item_duration).', ';
	$sql .= intval($item_width).', ';
	$sql .= intval($item_height).', ';
	$sql .= intval($item_displaytype).', ';
	$sql .= intval($item_displayfile).', ';
	$sql .= intval($item_onclick).', ';
	$sql .= intval($item_views).', ';
	$sql .= intval($item_chain).', ';
	$sql .= $this->quote($item_siteurl).', ';
	$sql .= $this->quote($item_artist).', ';
	$sql .= $this->quote($item_album).', ';
	$sql .= $this->quote($item_label).', ';
	$sql .= $this->quote($item_icon_name).', ';
	$sql .= intval($item_icon_width).', ';
	$sql .= intval($item_icon_height).', ';
	$sql .= $this->quote($item_external_url).', ';
	$sql .= $this->quote($item_external_thumb).', ';
	$sql .= $this->quote($item_external_middle).', ';
	$sql .= $this->quote($item_embed_type).', ';
	$sql .= $this->quote($item_embed_src).', ';
	$sql .= $this->quote($item_embed_text).', ';
	$sql .= intval($item_playlist_type).', ';
	$sql .= intval($item_playlist_time).', ';
	$sql .= $this->quote($item_playlist_feed).', ';
	$sql .= $this->quote($item_playlist_dir).', ';
	$sql .= $this->quote($item_playlist_cache).', ';
	$sql .= $this->quote($item_showinfo).', ';
	$sql .= $this->quote($item_codeinfo).', ';
	$sql .= intval($item_page_width).', ';
	$sql .= intval($item_page_height).', ';
	$sql .= $this->quote($item_editor).', ';
	$sql .= intval($item_description_html).', ';
	$sql .= intval($item_description_smiley).', ';
	$sql .= intval($item_description_xcode).', ';
	$sql .= intval($item_description_image).', ';
	$sql .= intval($item_description_br).', ';
	$sql .= intval($item_description_scroll).', ';
	$sql .= $this->quote($item_content).', ';
	$sql .= intval($item_detail_onclick).', ';
	$sql .= intval($item_weight).', ';
	$sql .= intval($item_perm_level).', ';

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; $i++ ) {
		$name = $this->file_id_to_item_name( $i );
		$sql .= intval( $row[ $name ] ).', ';
	}

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; $i++ ) {
		$name = $this->text_id_to_item_name( $i );
		$sql .= $this->quote( $row[ $name ] ).', ';
	}

	$sql .= $this->quote($item_search).' ';

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
	$sql .= 'item_time_create='.intval($item_time_create).', ';
	$sql .= 'item_time_update='.intval($item_time_update).', ';
	$sql .= 'item_time_publish='.intval($item_time_publish).', ';
	$sql .= 'item_time_expire='.intval($item_time_expire).', ';
	$sql .= 'item_cat_id='.intval($item_cat_id).', ';
	$sql .= 'item_gicon_id='.intval($item_gicon_id).', ';
	$sql .= 'item_player_id='.intval($item_player_id).', ';
	$sql .= 'item_flashvar_id='.intval($item_flashvar_id).', ';
	$sql .= 'item_uid='.intval($item_uid).', ';
	$sql .= 'item_kind='.$this->quote($item_kind).', ';
	$sql .= 'item_ext='.$this->quote($item_ext).', ';
	$sql .= 'item_datetime='.$this->quote($item_datetime).', ';
	$sql .= 'item_title='.$this->quote($item_title).', ';
	$sql .= 'item_place='.$this->quote($item_place).', ';
	$sql .= 'item_equipment='.$this->quote($item_equipment).', ';
	$sql .= 'item_gmap_latitude='.floatval($item_gmap_latitude).', ';
	$sql .= 'item_gmap_longitude='.floatval($item_gmap_longitude).', ';
	$sql .= 'item_gmap_zoom='.intval($item_gmap_zoom).', ';
	$sql .= 'item_gmap_type='.intval($item_gmap_type).', ';
	$sql .= 'item_perm_read='.$this->quote($item_perm_read).', ';
	$sql .= 'item_perm_down='.$this->quote($item_perm_down).', ';
	$sql .= 'item_status='.intval($item_status).', ';
	$sql .= 'item_hits='.intval($item_hits).', ';
	$sql .= 'item_rating='.floatval($item_rating).', ';
	$sql .= 'item_votes='.intval($item_votes).', ';
	$sql .= 'item_comments='.intval($item_comments).', ';
	$sql .= 'item_exif='.$this->quote($item_exif).', ';
	$sql .= 'item_description='.$this->quote($item_description).', ';
	$sql .= 'item_duration='.intval($item_duration).', ';
	$sql .= 'item_width='.intval($item_width).', ';
	$sql .= 'item_height='.intval($item_height).', ';
	$sql .= 'item_displaytype='.intval($item_displaytype).', ';
	$sql .= 'item_displayfile='.intval($item_displayfile).', ';
	$sql .= 'item_onclick='.intval($item_onclick).', ';
	$sql .= 'item_views='.intval($item_views).', ';
	$sql .= 'item_chain='.intval($item_chain).', ';
	$sql .= 'item_siteurl='.$this->quote($item_siteurl).', ';
	$sql .= 'item_artist='.$this->quote($item_artist).', ';
	$sql .= 'item_album='.$this->quote($item_album).', ';
	$sql .= 'item_label='.$this->quote($item_label).', ';
	$sql .= 'item_icon_name='.$this->quote($item_icon_name).', ';
	$sql .= 'item_icon_width='.intval($item_icon_width).', ';
	$sql .= 'item_icon_height='.intval($item_icon_height).', ';
	$sql .= 'item_external_url='.$this->quote($item_external_url).', ';
	$sql .= 'item_external_thumb='.$this->quote($item_external_thumb).', ';
	$sql .= 'item_external_middle='.$this->quote($item_external_middle).', ';
	$sql .= 'item_embed_type='.$this->quote($item_embed_type).', ';
	$sql .= 'item_embed_src='.$this->quote($item_embed_src).', ';
	$sql .= 'item_embed_text='.$this->quote($item_embed_text).', ';
	$sql .= 'item_playlist_type='.intval($item_playlist_type).', ';
	$sql .= 'item_playlist_time='.intval($item_playlist_time).', ';
	$sql .= 'item_playlist_feed='.$this->quote($item_playlist_feed).', ';
	$sql .= 'item_playlist_dir='.$this->quote($item_playlist_dir).', ';
	$sql .= 'item_playlist_cache='.$this->quote($item_playlist_cache).', ';
	$sql .= 'item_showinfo='.$this->quote($item_showinfo).', ';
	$sql .= 'item_codeinfo='.$this->quote($item_codeinfo).', ';
	$sql .= 'item_page_width='.intval($item_page_width).', ';
	$sql .= 'item_page_height='.intval($item_page_height).', ';
	$sql .= 'item_editor='.$this->quote($item_editor).', ';
	$sql .= 'item_description_html='.intval($item_description_html).', ';
	$sql .= 'item_description_smiley='.intval($item_description_smiley).', ';
	$sql .= 'item_description_xcode='.intval($item_description_xcode).', ';
	$sql .= 'item_description_image='.intval($item_description_image).', ';
	$sql .= 'item_description_br='.intval($item_description_br).', ';
	$sql .= 'item_description_scroll='.intval($item_description_scroll).', ';
	$sql .= 'item_content='.$this->quote($item_content).', ';
	$sql .= 'item_detail_onclick='.intval($item_detail_onclick).', ';
	$sql .= 'item_weight='.intval($item_weight).', ';
	$sql .= 'item_perm_level='.intval($item_perm_level).', ';

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; $i++ ) {
		$name = $this->file_id_to_item_name( $i );
		$sql .= $name .'='. intval( $row[ $name ] ).', ';
	}

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; $i++ ) {
		$name = $this->text_id_to_item_name( $i );
		$sql .= $name .'='. $this->quote( $row[ $name ] ).', ';
	}

	$sql .= 'item_search='.$this->quote($item_search).' ';
	$sql .= 'WHERE item_id='.intval($item_id);

	return $this->query( $sql, 0, 0, $force );
}

function update_status_by_id_array( $id_array )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'item_status=1 ';
	$sql .= 'WHERE '.$this->build_where_by_itemid_array( $id_array );

	return $this->query( $sql );
}

function update_rating_by_id( $item_id, $votes, $rating )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'item_rating='. floatval($rating) .', ';
	$sql .= 'item_votes='. intval($votes) .' ';
	$sql .= 'WHERE item_id='. intval($item_id);

	return $this->query( $sql );
}

function clear_gicon_id( $gicon_id )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'item_gicon_id=0 ';
	$sql .= 'WHERE item_gicon_id='.intval($gicon_id);

	return $this->query( $sql );
}

// when GET request
function countup_hits( $item_id, $force=false )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'item_hits = item_hits+1 ';
	$sql .= 'WHERE '. $this->build_where_public();
	$sql .= 'AND item_id='.intval($item_id);

	return $this->query( $sql, 0, 0, $force );
}

function countup_views( $item_id, $force=false )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'item_views = item_views+1 ';
	$sql .= 'WHERE '. $this->build_where_public();
	$sql .= 'AND item_id='.intval($item_id);

	return $this->query( $sql, 0, 0, $force );
}

function update_status( $item_id, $status, $force=false )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= ' item_status = '. intval($status) ;
	$sql .= ' WHERE item_id='.intval($item_id);

	return $this->query( $sql, 0, 0, $force );
}

function update_playlist_cache( $item_id, $cache, $force=false )
{
	$sql  = 'UPDATE '.$this->_table.' SET ';
	$sql .= 'item_playlist_cache ='. $this->quote( $cache );
	$sql .= 'WHERE item_id='.intval($item_id);

	return $this->query( $sql, 0, 0, $force );
}

//---------------------------------------------------------
// get count
//---------------------------------------------------------
function get_count_status( $status )
{
	return $this->get_count_by_where( $this->build_where_status( $status ) );
}

function get_count_waiting()
{
	return $this->get_count_status( _C_WEBPHOTO_STATUS_WAITING );
}

function get_count_by_catid( $cat_id )
{
	$where = 'item_cat_id='.intval($cat_id);
	return $this->get_count_by_where( $where );
}

function get_count_by_itemid_uid( $item_id, $uid )
{
	$where  = 'item_id='.intval($item_id);
	$where .= ' AND item_uid='.intval($uid);
	return $this->get_count_by_where( $where );
}

function get_count_photo()
{
	return $this->get_count_by_where( $this->build_where_ext_photo() );
}

function get_count_photo_detail_onclick()
{
	return $this->get_count_by_where( $this->build_where_ext_photo_detail_onclick_image() );
}

//---------------------------------------------------------
// get row
//---------------------------------------------------------
function get_title_by_id( $item_id )
{
	$row = $this->get_row_by_id( $item_id );
	if ( is_array($row) ) {
		return $row['item_title'] ;
	}
	return false;
}

//---------------------------------------------------------
// get rows
//---------------------------------------------------------
function get_rows_status( $status, $limit=0, $offset=0 )
{
	$where   = $this->build_where_status( $status );
	$orderby = 'item_id ASC';
	return $this->get_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_by_catid( $cat_id, $limit=0, $offset=0 )
{
	$where   = 'item_cat_id='.intval($cat_id);
	$orderby = 'item_id ASC';
	return $this->get_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_by_id_array( $id_array, $limit=0, $offset=0  )
{
	$where = $this->build_where_by_itemid_array( $id_array );
	$orderby = 'item_id ASC';
	return $this->get_rows_by_where_orderby( $where, $orderby, $limit, $offset );
}

function get_rows_flashplayer( $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->_table;
	$sql .= ' WHERE item_displaytype >= '. _C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT ;
	$sql .= ' ORDER BY item_title ASC';
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
// where
//---------------------------------------------------------
function build_where_by_keyword_array_catid( $keyword_array, $cat_id )
{
	$where_key = $this->build_where_by_keyword_array( 
		$keyword_array, 'item_search' );

	$where_cat = null;
	if ( $cat_id != 0 ) {
		$where_cat = "item_cat_id=".intval($cat_id);
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

//---------------------------------------------------------
// build
//---------------------------------------------------------
function format_rating( $rating, $decimals=2 )
{
	return number_format( $rating , $decimals ) ;
}

function build_search( $row )
{
	$text  = '';

	foreach ( $row as $k => $v ) {
		if ( in_array( $k, $this->_BUILD_SEARCH_ARRAY ) ) {
			$text .= $v.' ';
		}
	}

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_TEXT; $i++ ) {
		$text .= $row[ 'item_text_'.$i ].' ';
	}

	return $text;
}

//---------------------------------------------------------
// where
//---------------------------------------------------------
function build_where_public()
{
	$where = ' item_status > 0 ';
	return $where;
}

function build_where_status( $status )
{
	$where = ' item_status = '. intval($status) ;
	return $where;
}

function build_where_by_itemid_array( $id_array )
{
	$where = '';
	foreach( $id_array as $id ) {
		$where .= 'item_id='. intval($id) .' OR ';
	}

// 0 means to belong no category
	$where .= '0';
	return $where;
}

function build_where_ext_photo()
{
	$str  = " ( item_ext='gif' ";
	$str .= "OR item_ext='png' ";
	$str .= "OR item_ext='jpg' ";
	$str .= "OR item_ext='jpeg' ) ";
	return $str;
}

function build_where_detail_onclick_image()
{
	$str  = " ( item_detail_onclick=". _C_WEBPHOTO_DETAIL_ONCLICK_IMAGE ." ";
	$str .= "OR item_detail_onclick=". _C_WEBPHOTO_DETAIL_ONCLICK_LIGHTBOX ." ) ";
	return $str;
}

function build_where_ext_photo_detail_onclick_image()
{
	$str  = $this->build_where_ext_photo();
	$str .= ' AND ';
	$str .= $this->build_where_detail_onclick_image();
	return $str;
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
	return $this->str_to_mysql_datetime( $str );
}

function build_info( $arr )
{
	return $this->info_array_to_str( $this->sanitize_array_int( $arr ) );
}

//---------------------------------------------------------
// for show
//---------------------------------------------------------
function build_value_fileid_by_kind( $row, $kind )
{
	if ( isset( $row[ $this->build_name_fileid_by_kind( $kind ) ] ) ) {
		return  $row[ $this->build_name_fileid_by_kind( $kind ) ];
	}
	return false ;
}

function build_value_text_by_num( $row, $num )
{
	if ( isset( $row[ $this->build_name_text_by_num( $num ) ] ) ) {
		return  $row[ $this->build_name_text_by_num( $num ) ];
	}
	return false ;
}

function build_show_description_disp( $row )
{
	$editor = $row['item_editor'] ;
	$text   = $row['item_description'] ;

// new version (v0.10)
	$html   = $row['item_description_html'] ;
	$smiley = $row['item_description_smiley'] ;
	$xcode  = $row['item_description_xcode'] ;
	$image  = $row['item_description_image'] ;
	$br     = $row['item_description_br'] ;

// prev version (v0.90)
	if ( empty($editor) ) {
		$html   = 0 ;
		$smiley = 1 ;
		$xcode  = 1 ;
		$image  = 1 ;
		$br     = 1 ;
	}

	$myts =& MyTextSanitizer::getInstance();
	return $myts->displayTarea( $text, $html, $smiley, $xcode, $image, $br );
}

function build_show_exif_disp( $row )
{
	$myts =& MyTextSanitizer::getInstance();
	return $myts->displayTarea( $row['item_exif'] , 0 , 0 , 0 , 0 , 1 );
}

function build_show_icon_image( $item_row, $base_url )
{
	$url    = null ;
	$name   = $item_row['item_icon_name'] ;
	$width  = $item_row['item_icon_width'] ;
	$height = $item_row['item_icon_height'] ;
	if ( $name ) {
		$url = $base_url .'/'. $name ;
	}
	return array( $url, $width, $height ) ;
}

function get_showinfo_array( $row )
{
	return $this->info_str_to_array( $row['item_showinfo'] );
}

function get_codeinfo_array( $row )
{
	return $this->info_str_to_array( $row['item_codeinfo'] );
}

//---------------------------------------------------------
// permission
//---------------------------------------------------------
function get_perm_read_array( $row )
{
	return $this->get_perm_array( $row['item_perm_read'] );
}

function get_perm_down_array( $row )
{
	return $this->get_perm_array( $row['item_perm_down'] );
}

function check_perm_read_by_row( $row, $groups=null, $flag_admin=false )
{
	return $this->check_perm_by_row_name_groups( $row, 'item_perm_read', $groups, $flag_admin );
}

function check_perm_down_by_row( $row, $groups=null, $flag_admin=false )
{
	return $this->check_perm_by_row_name_groups( $row, 'item_perm_down', $groups, $flag_admin );
}

function is_public_read_by_row( $row )
{
	return $this->check_perm_by_row_name_groups( 
		$row, 'item_perm_read', array(XOOPS_GROUP_ANONYMOUS) );
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

//---------------------------------------------------------
// text define
//---------------------------------------------------------
function get_text_type_array()
{
	return array_merge( $this->_TEXT_ARRAY, $this->_ENCODE_ARRAY) ;
}

function get_encode_type_array()
{
	return $this->_ENCODE_ARRAY ;
}

//---------------------------------------------------------
// option
//---------------------------------------------------------
function get_kind_options( $kind='default' )
{
	switch ( $kind )
	{
		case 'playlist' :
			$arr = $this->get_kind_playlist_options();
			break;

		case 'default' :
		default:
			$arr = $this->get_kind_default_options();
			break;
	}
	return $arr;
}

function get_kind_default_options()
{
	$arr = array(
		_C_WEBPHOTO_ITEM_KIND_UNDEFINED        => _WEBPHOTO_ITEM_KIND_UNDEFINED ,
		_C_WEBPHOTO_ITEM_KIND_NONE             => _WEBPHOTO_ITEM_KIND_NONE ,
		_C_WEBPHOTO_ITEM_KIND_GENERAL          => _WEBPHOTO_ITEM_KIND_GENERAL ,
		_C_WEBPHOTO_ITEM_KIND_IMAGE            => _WEBPHOTO_ITEM_KIND_IMAGE ,

// v2.30
		_C_WEBPHOTO_ITEM_KIND_IMAGE_CMYK       => _WEBPHOTO_ITEM_KIND_IMAGE_CMYK ,

		_C_WEBPHOTO_ITEM_KIND_IMAGE_OTHER      => _WEBPHOTO_ITEM_KIND_IMAGE_OTHER ,
		_C_WEBPHOTO_ITEM_KIND_VIDEO            => _WEBPHOTO_ITEM_KIND_VIDEO ,

// v2.30
		_C_WEBPHOTO_ITEM_KIND_VIDEO_H264       => _WEBPHOTO_ITEM_KIND_VIDEO_H264 ,

		_C_WEBPHOTO_ITEM_KIND_AUDIO            => _WEBPHOTO_ITEM_KIND_AUDIO ,
		_C_WEBPHOTO_ITEM_KIND_OFFICE           => _WEBPHOTO_ITEM_KIND_OFFICE ,
		_C_WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL => _WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL ,
		_C_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE   => _WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE ,
		_C_WEBPHOTO_ITEM_KIND_EMBED            => _WEBPHOTO_ITEM_KIND_EMBED ,
		_C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED    => _WEBPHOTO_ITEM_KIND_PLAYLIST_FEED ,
		_C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR     => _WEBPHOTO_ITEM_KIND_PLAYLIST_DIR ,
	);
	return $arr;
}

function get_kind_playlist_options()
{
	$arr = array(
		_C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED    => _WEBPHOTO_ITEM_KIND_PLAYLIST_FEED ,
		_C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR     => _WEBPHOTO_ITEM_KIND_PLAYLIST_DIR ,
	);
	return $arr;
}

function get_displaytype_options()
{
	$arr = array(
		_C_WEBPHOTO_DISPLAYTYPE_GENERAL      => _WEBPHOTO_ITEM_DISPLAYTYPE_GENERAL ,
		_C_WEBPHOTO_DISPLAYTYPE_IMAGE        => _WEBPHOTO_ITEM_DISPLAYTYPE_IMAGE ,
		_C_WEBPHOTO_DISPLAYTYPE_EMBED        => _WEBPHOTO_ITEM_DISPLAYTYPE_EMBED ,
		_C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT    => _WEBPHOTO_ITEM_DISPLAYTYPE_SWFOBJECT ,
		_C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER  => _WEBPHOTO_ITEM_DISPLAYTYPE_MEDIAPLAYER ,
		_C_WEBPHOTO_DISPLAYTYPE_IMAGEROTATOR => _WEBPHOTO_ITEM_DISPLAYTYPE_IMAGEROTATOR ,
	);
	return $arr;
}

function get_displayfile_options()
{
	$arr = array(
		_C_WEBPHOTO_DISPLAYFILE_DEFAULT     => _WEBPHOTO_ITEM_DISPLAYFILE_DEFAULT ,
		_C_WEBPHOTO_FILE_KIND_CONT          => _WEBPHOTO_FILE_KIND_CONT ,
//		_C_WEBPHOTO_FILE_KIND_THUMB         => _WEBPHOTO_FILE_KIND_THUMB ,
//		_C_WEBPHOTO_FILE_KIND_MIDDLE        => _WEBPHOTO_FILE_KIND_MIDDLE ,
		_C_WEBPHOTO_FILE_KIND_FLASH         => _WEBPHOTO_FILE_KIND_FLASH ,
//		_C_WEBPHOTO_FILE_KIND_DOCOMO        => _WEBPHOTO_FILE_KIND_DOCOMO ,
//		_C_WEBPHOTO_FILE_KIND_PDF           => _WEBPHOTO_FILE_KIND_PDF ,
		_C_WEBPHOTO_FILE_KIND_SWF           => _WEBPHOTO_FILE_KIND_SWF ,
//		_C_WEBPHOTO_FILE_KIND_SMALL         => _WEBPHOTO_FILE_KIND_SMALL ,
//		_C_WEBPHOTO_FILE_KIND_JPEG          => _WEBPHOTO_FILE_KIND_JPEG ,
		_C_WEBPHOTO_FILE_KIND_MP3           => _WEBPHOTO_FILE_KIND_MP3 ,
	);
	return $arr;
}

function get_onclick_options()
{
	$arr = array(
		_C_WEBPHOTO_ONCLICK_PAGE    => _WEBPHOTO_ITEM_ONCLICK_PAGE ,
		_C_WEBPHOTO_ONCLICK_DIRECT  => _WEBPHOTO_ITEM_ONCLICK_DIRECT ,
		_C_WEBPHOTO_ONCLICK_POPUP   => _WEBPHOTO_ITEM_ONCLICK_POPUP ,
	);
	return $arr;
}

function get_status_options()
{
	$arr = array(
		_C_WEBPHOTO_STATUS_WAITING  => _WEBPHOTO_ITEM_STATUS_WAITING ,
		_C_WEBPHOTO_STATUS_APPROVED => _WEBPHOTO_ITEM_STATUS_APPROVED ,
		_C_WEBPHOTO_STATUS_UPDATED  => _WEBPHOTO_ITEM_STATUS_UPDATED ,
		_C_WEBPHOTO_STATUS_OFFLINE  => _WEBPHOTO_ITEM_STATUS_OFFLINE ,
		_C_WEBPHOTO_STATUS_EXPIRED  => _WEBPHOTO_ITEM_STATUS_EXPIRED ,
	);
	return $arr;
}

function get_playlist_type_options()
{
	$arr = array(
		_C_WEBPHOTO_PLAYLIST_TYPE_NONE   => _WEBPHOTO_ITEM_PLAYLIST_TYPE_NONE ,
		_C_WEBPHOTO_PLAYLIST_TYPE_IMAGE  => _WEBPHOTO_ITEM_PLAYLIST_TYPE_IMAGE ,
		_C_WEBPHOTO_PLAYLIST_TYPE_AUDIO  => _WEBPHOTO_ITEM_PLAYLIST_TYPE_AUDIO ,
		_C_WEBPHOTO_PLAYLIST_TYPE_VIDEO  => _WEBPHOTO_ITEM_PLAYLIST_TYPE_VIDEO ,
//		_C_WEBPHOTO_PLAYLIST_TYPE_FLASH  => _WEBPHOTO_ITEM_PLAYLIST_TYPE_FLASH ,
	);
	return $arr;
}

function get_playlist_time_options()
{
	$arr = array(
		_C_WEBPHOTO_PLAYLIST_TIME_HOUR  => _WEBPHOTO_ITEM_PLAYLIST_TIME_HOUR ,
		_C_WEBPHOTO_PLAYLIST_TIME_DAY   => _WEBPHOTO_ITEM_PLAYLIST_TIME_DAY ,
		_C_WEBPHOTO_PLAYLIST_TIME_WEEK  => _WEBPHOTO_ITEM_PLAYLIST_TIME_WEEK ,
		_C_WEBPHOTO_PLAYLIST_TIME_MONTH => _WEBPHOTO_ITEM_PLAYLIST_TIME_MONTH ,
	);
	return $arr;
}

function get_showinfo_options()
{
	$arr = array(
		_C_WEBPHOTO_SHOWINFO_DESCRIPTION => _WEBPHOTO_ITEM_SHOWINFO_DESCRIPTION ,
		_C_WEBPHOTO_SHOWINFO_LOGOIMAGE   => _WEBPHOTO_ITEM_SHOWINFO_LOGOIMAGE ,
		_C_WEBPHOTO_SHOWINFO_CREDITS     => _WEBPHOTO_ITEM_SHOWINFO_CREDITS ,
		_C_WEBPHOTO_SHOWINFO_STATISTICS  => _WEBPHOTO_ITEM_SHOWINFO_STATISTICS ,
		_C_WEBPHOTO_SHOWINFO_SUBMITTER   => _WEBPHOTO_ITEM_SHOWINFO_SUBMITTER ,
		_C_WEBPHOTO_SHOWINFO_POPUP       => _WEBPHOTO_ITEM_SHOWINFO_POPUP ,
		_C_WEBPHOTO_SHOWINFO_DOWNLOAD    => _WEBPHOTO_ITEM_SHOWINFO_DOWNLOAD ,
		_C_WEBPHOTO_SHOWINFO_WEBSITE     => _WEBPHOTO_ITEM_SHOWINFO_WEBSITE ,
		_C_WEBPHOTO_SHOWINFO_WEBFEED     => _WEBPHOTO_ITEM_SHOWINFO_WEBFEED ,
	);
	return $arr;
}

function get_codeinfo_options()
{
	$arr = array(
		_C_WEBPHOTO_CODEINFO_CONT   => _WEBPHOTO_ITEM_CODEINFO_CONT ,
		_C_WEBPHOTO_CODEINFO_JPEG   => _WEBPHOTO_ITEM_CODEINFO_JPEG ,
		_C_WEBPHOTO_CODEINFO_THUMB  => _WEBPHOTO_ITEM_CODEINFO_THUMB ,
		_C_WEBPHOTO_CODEINFO_LARGE  => _WEBPHOTO_ITEM_CODEINFO_LARGE ,
		_C_WEBPHOTO_CODEINFO_MIDDLE => _WEBPHOTO_ITEM_CODEINFO_MIDDLE ,
		_C_WEBPHOTO_CODEINFO_SMALL  => _WEBPHOTO_ITEM_CODEINFO_SMALL ,
		_C_WEBPHOTO_CODEINFO_FLASH  => _WEBPHOTO_ITEM_CODEINFO_FLASH ,
//		_C_WEBPHOTO_CODEINFO_DOCOMO => _WEBPHOTO_ITEM_CODEINFO_DOCOMO ,
		_C_WEBPHOTO_CODEINFO_WAV    => _WEBPHOTO_ITEM_CODEINFO_WAV ,
		_C_WEBPHOTO_CODEINFO_MP3    => _WEBPHOTO_ITEM_CODEINFO_MP3 ,
		_C_WEBPHOTO_CODEINFO_PDF    => _WEBPHOTO_ITEM_CODEINFO_PDF ,
		_C_WEBPHOTO_CODEINFO_SWF    => _WEBPHOTO_ITEM_CODEINFO_SWF ,
		_C_WEBPHOTO_CODEINFO_PAGE   => _WEBPHOTO_ITEM_CODEINFO_PAGE ,
		_C_WEBPHOTO_CODEINFO_SITE   => _WEBPHOTO_ITEM_CODEINFO_SITE ,
		_C_WEBPHOTO_CODEINFO_PLAY   => _WEBPHOTO_ITEM_CODEINFO_PLAY ,
		_C_WEBPHOTO_CODEINFO_EMBED  => _WEBPHOTO_ITEM_CODEINFO_EMBED ,
		_C_WEBPHOTO_CODEINFO_JS     => _WEBPHOTO_ITEM_CODEINFO_JS ,
	);
	return $arr;
}

function get_detail_onclick_options()
{
	$arr = array(
		_C_WEBPHOTO_DETAIL_ONCLICK_DEFAULT  => _WEBPHOTO_ITEM_DETAIL_ONCLICK_DEFAULT ,
		_C_WEBPHOTO_DETAIL_ONCLICK_DOWNLOAD => _WEBPHOTO_ITEM_DETAIL_ONCLICK_DOWNLOAD ,
		_C_WEBPHOTO_DETAIL_ONCLICK_IMAGE    => _WEBPHOTO_ITEM_DETAIL_ONCLICK_IMAGE ,
		_C_WEBPHOTO_DETAIL_ONCLICK_LIGHTBOX => _WEBPHOTO_ITEM_DETAIL_ONCLICK_LIGHTBOX ,
//		_C_WEBPHOTO_FILE_KIND_CONT          => _WEBPHOTO_FILE_KIND_CONT ,
//		_C_WEBPHOTO_FILE_KIND_THUMB         => _WEBPHOTO_FILE_KIND_THUMB ,
//		_C_WEBPHOTO_FILE_KIND_MIDDLE        => _WEBPHOTO_FILE_KIND_MIDDLE ,
//		_C_WEBPHOTO_FILE_KIND_FLASH         => _WEBPHOTO_FILE_KIND_FLASH ,
//		_C_WEBPHOTO_FILE_KIND_DOCOMO        => _WEBPHOTO_FILE_KIND_DOCOMO ,
		_C_WEBPHOTO_FILE_KIND_PDF           => _WEBPHOTO_FILE_KIND_PDF ,
//		_C_WEBPHOTO_FILE_KIND_SWF           => _WEBPHOTO_FILE_KIND_SWF ,
//		_C_WEBPHOTO_FILE_KIND_SMALL         => _WEBPHOTO_FILE_KIND_SMALL ,
//		_C_WEBPHOTO_FILE_KIND_JPEG          => _WEBPHOTO_FILE_KIND_JPEG ,
//		_C_WEBPHOTO_FILE_KIND_MP3           => _WEBPHOTO_FILE_KIND_MP3 ,
	);
	return $arr;
}

function get_perm_level_options()
{
	$arr = array(
		_C_WEBPHOTO_PERM_LEVEL_PUBLIC => _WEBPHOTO_ITEM_PERM_LEVEL_PUBLIC ,
		_C_WEBPHOTO_PERM_LEVEL_GROUP  => _WEBPHOTO_ITEM_PERM_LEVEL_GROUP ,
	);
	return $arr;
}

// --- class end ---
}

?>