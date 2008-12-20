<?php
// $Id: comment.php,v 1.3 2008/12/20 06:11:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-08-24 K.OHWADA
// table_photo -> table_item
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_comment
//=========================================================
class webphoto_inc_comment extends webphoto_inc_handler
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_comment( $dirname )
{
	$this->webphoto_inc_handler();
	$this->init_handler( $dirname );
}

function &getSingleton( $dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_comment( $dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function update_photo_comments( $item_id, $comments )
{
	$sql  = 'UPDATE '. $this->prefix_dirname( 'item' );
	$sql .= ' SET ';
	$sql .= 'item_comments='. intval($comments) .' ';
	$sql .= 'WHERE item_id='. intval($item_id);

	return $this->query( $sql );
}

// --- class end ---
}

?>