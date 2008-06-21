<?php
// $Id: comment.php,v 1.1 2008/06/21 12:22:26 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

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
function update_photo_comments( $dirname, $photo_id, $comments )
{
	$this->init_handler( $dirname );

	$sql  = 'UPDATE '. $this->prefix_dirname( 'photo' );
	$sql .= ' SET ';
	$sql .= 'photo_comments='. intval($comments) .' ';
	$sql .= 'WHERE photo_id='. intval($photo_id);

	return $this->query( $sql );
}

// --- class end ---
}

?>