<?php
// $Id: comment.php,v 1.2 2008/08/25 19:28:05 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
function webphoto_inc_comment()
{
	$this->webphoto_inc_handler();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_inc_comment();
	}
	return $instance;
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function update_photo_comments( $dirname, $item_id, $comments )
{
	$this->init_handler( $dirname );

	$sql  = 'UPDATE '. $this->prefix_dirname( 'item' );
	$sql .= ' SET ';
	$sql .= 'item_comments='. intval($comments) .' ';
	$sql .= 'WHERE item_id='. intval($item_id);

	return $this->query( $sql );
}

// --- class end ---
}

?>