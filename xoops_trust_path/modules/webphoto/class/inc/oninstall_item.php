<?php
// $Id: oninstall_item.php,v 1.1 2010/09/27 03:44:45 ohwada Exp $

//=========================================================
// webphoto module
// 2010-09-20 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_oninstall_item
//=========================================================
class webphoto_inc_oninstall_item extends webphoto_inc_base_ini
{
	var $_table_item ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_oninstall_item( $dirname , $trust_dirname )
{
	$this->webphoto_inc_base_ini();
	$this->init_base_ini( $dirname , $trust_dirname );
	$this->init_handler(  $dirname );

	$this->_table_item   = $this->prefix_dirname( 'item' );
}

function &getSingleton( $dirname , $trust_dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_oninstall_item( $dirname , $trust_dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// item table
//---------------------------------------------------------
function update()
{
	$this->_item_add_column_050();
	$this->_item_add_column_060();
	$this->_item_add_column_070();
	$this->_item_add_column_080();
	$this->_item_chang_column_080();
	$this->_item_add_column_100();
	$this->_item_add_column_110();
	$this->_item_modify_column_173();
	$this->_item_add_column_190();
	$this->_item_add_column_200();
	$this->_item_add_column_210();
	$this->_item_add_column_230();
}

function _item_add_column_050()
{

// return if already exists
	if ( $this->exists_column( $this->_table_item, 'item_external_url' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_item ." ADD ( " ;

	$sql  .= "item_time_publish  INT(10) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_time_expire   INT(10) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_player_id   INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_flashvar_id INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_duration    INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_displaytype INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_onclick     INT(11) UNSIGNED NOT NULL DEFAULT '0', " ; 
	$sql  .= "item_views INT(11) NOT NULL DEFAULT '0', " ;
	$sql  .= "item_chain INT(11) NOT NULL DEFAULT '0', " ;
	$sql  .= "item_siteurl VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_artist  VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_album   VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_label   VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_perm_down VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_external_url   VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_external_thumb VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_embed_type  VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_embed_src   VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_playlist_feed  VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_playlist_dir   VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_playlist_cache VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_playlist_type INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_playlist_time INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql  .= "item_showinfo  VARCHAR(255) NOT NULL DEFAULT '' " ;

	$sql .= " )";
	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Add item_external_url in <b>'. $this->_table_item .'</b>' );
		return true;
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_item .'</b>.' ) );
		return false;
	}

}

function _item_add_column_060()
{

// return if already exists
	if ( $this->exists_column( $this->_table_item, 'item_external_middle' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_item ." ADD ( " ;

	$sql  .= "item_external_middle VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_icon_name VARCHAR(255) NOT NULL DEFAULT '' " ;

	$sql .= " )";
	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Add item_external_middle in <b>'. $this->_table_item .'</b>' );
		return true;
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_item .'</b>.' ) );
		return false;
	}

}

function _item_add_column_070()
{

// return if already exists
	if ( $this->exists_column( $this->_table_item, 'item_codeinfo' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_item ." ADD ( " ;

	$sql  .= "item_codeinfo VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "item_page_width  INT(11) NOT NULL DEFAULT '0', " ;
	$sql  .= "item_page_height INT(11) NOT NULL DEFAULT '0', " ;
	$sql  .= "item_embed_text  TEXT NOT NULL " ;

	$sql .= " )";
	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Add item_codeinfo in <b>'. $this->_table_item .'</b>' );
		return $this->_item_update_070();

	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_item .'</b>.' ) );
		return false;
	}

}

function _item_update_070()
{
	$sql  = "UPDATE ". $this->_table_item ." SET " ;
	$sql .= "item_codeinfo=".  $this->quote( $this->get_ini('item_codeinfo_default') ) .", " ;
	$sql .= "item_showinfo=".  $this->quote( $this->get_ini('item_showinfo_default') ) .", " ;
	$sql .= "item_perm_read=". $this->quote( _C_WEBPHOTO_PERM_ALLOW_ALL )   .", " ;
	$sql .= "item_perm_down=". $this->quote( _C_WEBPHOTO_PERM_ALLOW_ALL ) ;

	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Update item_codeinfo in <b>'. $this->_table_item .'</b>' );
		return true;
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_item .'</b>.' ) );
		return false;
	}

}

function _item_add_column_080()
{

// return if already exists
	if ( $this->exists_column( $this->_table_item, 'item_icon_width' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_item ." ADD ( " ;
	$sql .= "item_icon_width  INT(11) NOT NULL DEFAULT '0', " ;
	$sql .= "item_icon_height INT(11) NOT NULL DEFAULT '0' " ;
	$sql .= " )";

	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Add item_width in <b>'. $this->_table_item .'</b>' );
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_item .'</b>.' ) );
		return false;
	}

}

function _item_chang_column_080()
{

// return if already exists
	if ( $this->exists_column( $this->_table_item, 'item_icon_name' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_item ." CHANGE " ;
	$sql .= "item_icon item_icon_name VARCHAR(255) NOT NULL DEFAULT '' " ;

	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Change item_icon_name in <b>'. $this->_table_item .'</b>' );
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_item .'</b>.' ) );
		return false;
	}

}

function _item_add_column_100()
{

// return if already exists
	if ( $this->exists_column( $this->_table_item, 'item_editor' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_item ." ADD ( " ;
	$sql .= "item_editor VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql .= "item_description_html   TINYINT(2) NOT NULL DEFAULT '0', " ;
	$sql .= "item_description_smiley TINYINT(2) NOT NULL DEFAULT '0', " ;
	$sql .= "item_description_xcode  TINYINT(2) NOT NULL DEFAULT '0', " ;
	$sql .= "item_description_image  TINYINT(2) NOT NULL DEFAULT '0', " ;
	$sql .= "item_description_br     TINYINT(2) NOT NULL DEFAULT '0' " ;
	$sql .= " )";

	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Add item_editor in <b>'. $this->_table_item .'</b>' );
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_item .'</b>.' ) );
		return false;
	}

}

function _item_add_column_110()
{

// return if already exists
	if ( $this->exists_column( $this->_table_item, 'item_content' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_item ." ADD ( " ;
	$sql .= "item_width  INT(11) NOT NULL DEFAULT '0', " ;
	$sql .= "item_height INT(11) NOT NULL DEFAULT '0', " ;
	$sql .= "item_content TEXT NOT NULL " ;
	$sql .= " )";

	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Add item_content in <b>'. $this->_table_item .'</b>' );
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_item .'</b>.' ) );
		return false;
	}

}

function _item_modify_column_173()
{
// return if match column type
	if ( $this->preg_match_column_type( $this->_table_item, 'item_exif', 'BLOB' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_item ." MODIFY " ;
	$sql .= "item_exif BLOB NOT NULL ";

	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Modify item_exif in <b>'. $this->_table_item .'</b>' );
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_item .'</b>.' ) );
		return false;
	}

}

function _item_add_column_190()
{

// return if already exists
	if ( $this->exists_column( $this->_table_item, 'item_detail_onclick' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_item ." ADD ( " ;
	$sql .= "item_detail_onclick INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql .= "item_weight         INT(11) UNSIGNED NOT NULL DEFAULT '0' " ;
	$sql .= " )";

	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Add item_content in <b>'. $this->_table_item .'</b>' );
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_item .'</b>.' ) );
		return false;
	}

}

function _item_add_column_200()
{

// return if already exists
	if ( $this->exists_column( $this->_table_item, 'item_perm_level' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_item ." ADD ( " ;
	$sql .= "item_perm_level TINYINT(2) NOT NULL DEFAULT '0' " ;
	$sql .= " )";

	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Add item_content in <b>'. $this->_table_item .'</b>' );
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_item .'</b>.' ) );
		return false;
	}

}

function _item_add_column_210()
{

// return if already exists
	if ( $this->exists_column( $this->_table_item, 'item_description_scroll' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_item ." ADD ( " ;
	$sql .= "item_description_scroll INT(11) UNSIGNED NOT NULL DEFAULT '0' " ;
	$sql .= " )";

	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Add item_content in <b>'. $this->_table_item .'</b>' );
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_item .'</b>.' ) );
		return false;
	}

}

function _item_add_column_230()
{

// return if already exists
	if ( $this->exists_column( $this->_table_item, 'item_displayfile' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_item ." ADD ( " ;
	$sql .= "item_displayfile INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql .= "item_file_id_11  INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql .= "item_file_id_12  INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql .= "item_file_id_13  INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql .= "item_file_id_14  INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql .= "item_file_id_15  INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql .= "item_file_id_16  INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql .= "item_file_id_17  INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql .= "item_file_id_18  INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql .= "item_file_id_19  INT(11) UNSIGNED NOT NULL DEFAULT '0', " ;
	$sql .= "item_file_id_20  INT(11) UNSIGNED NOT NULL DEFAULT '0' " ;
	$sql .= " )";

	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Add item_file_id_11 in <b>'. $this->_table_item .'</b>' );
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_item .'</b>.' ) );
		return false;
	}

}

// --- class end ---
}

?>