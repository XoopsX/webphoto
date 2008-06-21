<?php
// $Id: xoops_comments_handler.php,v 1.1 2008/06/21 12:22:25 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class uploader_photo_handler
//=========================================================
class webphoto_xoops_comments_handler extends webphoto_lib_handler
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_xoops_comments_handler()
{
	$this->webphoto_lib_handler();
	$this->set_table_prefix( 'xoopscomments' );

//	$constpref = strtoupper( '_C_' . $dirname. '_' ) ;
//	$this->set_debug_sql_by_const_name(   $constpref.'DEBUG_SQL' );
//	$this->set_debug_error_by_const_name( $constpref.'DEBUG_ERROR' );

}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_xoops_comments_handler();
	}
	return $instance;
}

//---------------------------------------------------------
// insert
//---------------------------------------------------------
function insert( $row )
{
	extract( $row ) ;

	$sql  = "INSERT INTO ".$this->_table." (";
	$sql .= "com_modid, com_itemid, com_icon, com_created, com_modified, com_uid, ";
	$sql .= "com_ip, com_title, com_text, com_sig, com_status, com_exparams, ";
	$sql .= "dohtml, dosmiley, doxcode, doimage, dobr ";
	$sql .= ") VALUES (";
	$sql .= "$com_modid, $com_itemid, '$com_icon', $com_created, $com_modified, $com_uid, ";
	$sql .= " '$com_ip', '$com_title', '$com_text', $com_sig, $com_status, '$com_exparams', ";
	$sql .= "$dohtml, $dosmiley, $doxcode, $doimage, $dobr ";
	$sql .= ")";

	$this->query($sql);
	return $this->_db->getInsertId();
}

//---------------------------------------------------------
// update
//---------------------------------------------------------
function update_rootid_pid( $com_id, $com_rootid, $com_pid )
{
	$sql  = "UPDATE ".$this->_table." SET ";
	$sql .= "com_rootid=".$com_rootid.", ";
	$sql .= "com_pid=".$com_pid." ";
	$sql .= "WHERE com_id=".$com_id;

	return $this->query($sql);
}

function move( $src_mid, $src_id, $dst_mid, $dst_lid )
{
	$sql  = 'UPDATE '. $this->_table .' SET ';
	$sql .= ' com_modid='. intval($dst_mid);
	$sql .= ' com_itemid='. intval($dst_id);
	$sql .= ' WHERE com_modid='. intval($src_mid);
	$sql .= ' AND com_itemid='. intval($src_id) ;

	return $this->query($sql);
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function delete_all_by_modid( $modid )
{
	$sql  = 'DELETE FROM '. $this->_table;
	$sql .= ' WHERE com_modid='. intval( $modid );
	$res = $this->query($sql);
}

//---------------------------------------------------------
// get
//---------------------------------------------------------
function get_count_by_modid( $modid )
{
	$sql  = 'SELECT COUNT(com_id) FROM '. $this->_table;
	$sql .= ' WHERE com_modid='. intval($modid);
	return $this->get_count_by_sql( $sql );
}

function get_rows_by_modid( $modid )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' WHERE com_modid='. intval($modid);
	$sql .= ' ORDER BY com_id';
	return $this->get_rows_by_sql($sql);
}

function get_rows_by_modid_itemid( $modid, $itemid )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' WHERE com_modid='. intval($modid);
	$sql .= ' AND com_itemid='. intval($itemid) ;
	$sql .= ' ORDER BY com_id';
	return $this->get_rows_by_sql($sql);
}

// --- class end ---
}

?>