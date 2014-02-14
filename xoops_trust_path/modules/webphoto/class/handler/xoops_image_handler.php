<?php
// $Id: xoops_image_handler.php,v 1.1 2008/06/21 12:22:25 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class uploader_photo_handler
//=========================================================
class webphoto_xoops_image_handler extends webphoto_lib_handler
{
	var $_category_table ;
	var $_image_table ;
	var $_body_table ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_xoops_image_handler()
{
	$this->webphoto_lib_handler();

	$this->_category_table = $this->db_prefix( 'imagecategory' ) ;
	$this->_image_table    = $this->db_prefix( 'image' ) ;
	$this->_body_table     = $this->db_prefix( 'imagebody' );

//	$constpref = strtoupper( '_C_' . $dirname. '_' ) ;
//	$this->set_debug_sql_by_const_name(   $constpref.'DEBUG_SQL' );
//	$this->set_debug_error_by_const_name( $constpref.'DEBUG_ERROR' );

}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_xoops_image_handler();
	}
	return $instance;
}

//---------------------------------------------------------
// insert
//---------------------------------------------------------
// image_id mediumint(8) unsigned NOT NULL auto_increment,
// image_name varchar(30) NOT NULL default '',
// image_nicename varchar(255) NOT NULL default '',
// image_mimetype varchar(30) NOT NULL default '',
// image_created int(10) unsigned NOT NULL default '0',
// image_display tinyint(1) unsigned NOT NULL default '0',
// image_weight smallint(5) unsigned NOT NULL default '0',
// imgcat_id smallint(5) unsigned NOT NULL default '0',
//---------------------------------------------------------
function insert_image( $row )
{
	extract( $row ) ;

	$sql  = 'INSERT INTO '. $this->_image_table .' SET ';
	$sql .= 'image_name='.$this->quote($image_name).', ';
	$sql .= 'image_nicename='.$this->quote($image_nicename).', ';
	$sql .= 'image_created='.intval($image_created).', ';
	$sql .= 'image_mimetype='.$this->quote($image_mimetype).', ';
	$sql .= 'image_display='.intval($image_display).', ';
	$sql .= 'image_weight='.intval($image_weight).', ';
	$sql .= 'imgcat_id='.intval($imgcat_id);

	$ret = $this->query( $sql );
	if ( !$ret ) { return false; }

	return $this->_db->getInsertId();
}

//---------------------------------------------------------
// image_id mediumint(8) unsigned NOT NULL default '0',
// image_body mediumblob,
//---------------------------------------------------------
function insert_body( $row )
{
	extract( $row ) ;

	$sql = 'INSERT INTO '. $this->_body_table .' SET ';
	$sql .= 'image_id='.intval($image_id).', ';
	$sql .= 'image_body='.$this->quote($image_body);

	$ret = $this->query( $sql );
	if ( !$ret ) { return false; }

	return $this->_db->getInsertId();
}

//---------------------------------------------------------
// category
//---------------------------------------------------------
function get_category_row_by_id( $id )
{
	$sql  = 'SELECT * FROM '. $this->_category_table;
	$sql .= ' WHERE imgcat_id='.intval($id);
	return $this->get_row_by_sql( $sql );
}

function get_category_rows_with_image_count( $limit=0, $offset=0 )
{
	$sql  = 'SELECT c.*, COUNT(i.image_id) AS image_sum ';
	$sql .= 'FROM '. $this->_category_table .' c ';
	$sql .= 'NATURAL LEFT JOIN '. $this->_image_table .' i ';
	$sql .= 'GROUP BY c.imgcat_id ';
	$sql .= 'ORDER BY c.imgcat_weight, c.imgcat_id' ;
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

//---------------------------------------------------------
// image
//---------------------------------------------------------
function get_image_rows_by_catid( $catid, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->_image_table;
	$sql .= ' WHERE imgcat_id='.intval($catid);
	$sql .= ' ORDER BY image_id' ;
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

//---------------------------------------------------------
// body
//---------------------------------------------------------
function get_body_row_by_imageid( $image_id )
{
	$sql  = 'SELECT * FROM '. $this->_body_table;
	$sql .= ' WHERE image_id='.intval($image_id) ;
	return $this->get_row_by_sql( $sql );
}

//---------------------------------------------------------
// category selbox
//---------------------------------------------------------
function build_cat_selbox( $name='imgcat_id' , $flag_storetype=true )
{
	$cat_rows = $this->get_category_rows_with_image_count();

	$str = '<select name="'. $name .'">'."\n";

	foreach ( $cat_rows as $row )
	{
		$imgcat_id     = intval( $row['imgcat_id'] );
		$image_sum     = intval( $row['image_sum'] );
		$imgcat_name_s = $this->sanitize( $row['imgcat_name'] );
		$imgcat_storetype = $row['imgcat_storetype'];

		$str .= '<option value="'. $imgcat_id .'">';
		if ( $flag_storetype ) {
			$str .= $imgcat_storetype .' : ';
		}
		$str .= $imgcat_name_s .' ('. $image_sum .')';
		$str .= '</option>'."\n" ;
	}

	$str .= "</select>\n";

	return $str;
}

// --- class end ---
}

?>