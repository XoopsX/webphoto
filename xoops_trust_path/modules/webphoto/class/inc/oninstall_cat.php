<?php
// $Id: oninstall_cat.php,v 1.1 2011/12/28 18:03:28 ohwada Exp $

//=========================================================
// webphoto module
// 2011-12-25 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_oninstall_cat
//=========================================================
class webphoto_inc_oninstall_cat extends webphoto_inc_base_ini
{
	var $_table_cat ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_oninstall_cat( $dirname , $trust_dirname )
{
	$this->webphoto_inc_base_ini();
	$this->init_base_ini( $dirname , $trust_dirname );
	$this->init_handler(  $dirname );

	$this->_table_cat   = $this->prefix_dirname( 'cat' );
}

function &getSingleton( $dirname , $trust_dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_oninstall_cat( $dirname , $trust_dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// cat table
//---------------------------------------------------------
function update()
{
	$this->_cat_add_column_260();
	$this->_cat_add_column_060();
	$this->_cat_add_column_200();
}

function _cat_add_column_260()
{

// return if already exists
	if ( $this->exists_column( $this->_table_cat, 'cat_timeline_mode' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_cat ." ADD ( " ;
	$sql  .= "cat_timeline_mode  TINYINT(2) NOT NULL DEFAULT '0', " ;
	$sql  .= "cat_timeline_scale INT(5) NOT NULL DEFAULT '0' " ;
	$sql .= " )";

	$ret = $this->query( $sql );
	if ( $ret ) {
		$this->set_msg( 'Add cat_timeline_scale in <b>'. $this->_table_cat .'</b>' );
		return true;
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_cat .'</b>.' ) );
		return false;
	}

}

function _cat_add_column_060()
{

// return if already exists
	if ( $this->exists_column( $this->_table_cat, 'cat_img_name' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_cat ." ADD ( " ;
	$sql  .= "cat_img_name VARCHAR(255) NOT NULL DEFAULT '' " ;
	$sql .= " )";

	$ret = $this->query( $sql );
	if ( $ret ) {
		$this->set_msg( 'Add cat_img_name in <b>'. $this->_table_cat .'</b>' );
		return true;
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_cat .'</b>.' ) );
		return false;
	}

}

function _cat_add_column_200()
{

// return if already exists
	if ( $this->exists_column( $this->_table_cat, 'cat_group_id' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_cat ." ADD ( " ;
	$sql  .= "cat_group_id INT(5) UNSIGNED NOT NULL DEFAULT '0' " ;
	$sql .= " )";

	$ret = $this->query( $sql );
	if ( $ret ) {
		$this->set_msg( 'Add cat_img_name in <b>'. $this->_table_cat .'</b>' );
		return true;
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_cat .'</b>.' ) );
		return false;
	}

}

// --- class end ---
}

?>