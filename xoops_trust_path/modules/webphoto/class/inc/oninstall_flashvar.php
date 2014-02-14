<?php
// $Id: oninstall_flashvar.php,v 1.1 2011/05/15 22:24:01 ohwada Exp $

//=========================================================
// webphoto module
// 2011-05-01 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_oninstall_flashvar
//=========================================================
class webphoto_inc_oninstall_flashvar extends webphoto_inc_base_ini
{
	var $_table_flashvar ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_oninstall_flashvar( $dirname , $trust_dirname )
{
	$this->webphoto_inc_base_ini();
	$this->init_base_ini( $dirname , $trust_dirname );
	$this->init_handler(  $dirname );

	$this->_table_flashvar = $this->prefix_dirname( 'flashvar' );
}

function &getSingleton( $dirname , $trust_dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_oninstall_flashvar( $dirname , $trust_dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// flashvar table
//---------------------------------------------------------
function update()
{
	$this->_flashvar_add_column_240();
}

function _flashvar_add_column_240()
{

// return if already exists
	if ( $this->exists_column( $this->_table_flashvar, 'flashvar_dock' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_flashvar ." ADD ( " ;

	$sql  .= "flashvar_dock  TINYINT(2)  NOT NULL DEFAULT '0', " ;
	$sql  .= "flashvar_icons TINYINT(2)  NOT NULL DEFAULT '0', " ;
	$sql  .= "flashvar_mute  TINYINT(2)  NOT NULL DEFAULT '0', " ;
	$sql  .= "flashvar_controlbar_idlehide TINYINT(2)  NOT NULL DEFAULT '0', " ;
	$sql  .= "flashvar_display_showmute TINYINT(2)  NOT NULL DEFAULT '0', " ;
	$sql  .= "flashvar_logo_hide TINYINT(2)  NOT NULL DEFAULT '0', " ;
	$sql  .= "flashvar_duration INT(3)  NOT NULL DEFAULT '0', " ;
	$sql  .= "flashvar_start    INT(3)  NOT NULL DEFAULT '0', " ;
	$sql  .= "flashvar_item     INT(3)  NOT NULL DEFAULT '0', " ;
	$sql  .= "flashvar_playlist_size INT(3)  NOT NULL DEFAULT '0', " ;
	$sql  .= "flashvar_logo_margin   INT(3)  NOT NULL DEFAULT '0', " ;
	$sql  .= "flashvar_logo_timeout  INT(3)  NOT NULL DEFAULT '0', " ;
	$sql  .= "flashvar_logo_over     FLOAT(5,4) NOT NULL DEFAULT '0', " ;
	$sql  .= "flashvar_logo_out      FLOAT(5,4) NOT NULL DEFAULT '0', " ;
	$sql  .= "flashvar_playlistfile VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "flashvar_mediaid      VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "flashvar_provider     VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "flashvar_streamer     VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "flashvar_netstreambasepath VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "flashvar_skin          VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "flashvar_player_repeat VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "flashvar_playerready   VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "flashvar_plugins       VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "flashvar_stretching    VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "flashvar_controlbar_position VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "flashvar_playlist_position   VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "flashvar_logo_file       VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "flashvar_logo_link       VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "flashvar_logo_linktarget VARCHAR(255) NOT NULL DEFAULT '', " ;
	$sql  .= "flashvar_logo_position   VARCHAR(255) NOT NULL DEFAULT '' " ;

	$sql .= " )";
	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Add flashvar_player_repeat in <b>'. $this->_table_flashvar .'</b>' );
		return true;
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_flashvar .'</b>.' ) );
		return false;
	}

}

// --- class end ---
}

?>