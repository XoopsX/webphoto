<?php
// $Id: notification_event.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_cat_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_notification
//=========================================================
class webphoto_notification_event extends webphoto_d3_notification_event
{
	var $_cat_handler;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_notification_event( $dirname , $trust_dirname )
{
	$this->webphoto_d3_notification_event();
	$this->init( $dirname , $trust_dirname );

	$this->_cat_handler  =& webphoto_cat_handler::getInstance( 
		$dirname , $trust_dirname );

	$this->_PHOTO_PHP = $this->_MODULE_URL.'/index.php?fct=photo';
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_notification_event( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// function
//---------------------------------------------------------
function notify_new_photo( $photo_id, $cat_id, $photo_title )
{
	$cat_title = $this->_cat_handler->get_cached_value_by_id_name( $cat_id, 'cat_title' );

	$photo_uri = $this->_PHOTO_PHP .'&photo_id='. $photo_id .'&cat_id='. $cat_id;

	// Global Notification
	$photo_tags = array( 
		'PHOTO_TITLE' => $photo_title ,
		'PHOTO_URI'   => $photo_uri,
	 );

	$this->trigger_event( 'global' , 0 , 'new_photo' , $photo_tags ) ;

	// Category Notification
	if ( $cat_title ) {
		$cat_tags = array(
			'PHOTO_TITLE'    => $photo_title  ,
			'CATEGORY_TITLE' => $cat_title , 
			'PHOTO_URI'      => $photo_uri,
		);

		$this->trigger_event( 'category' , $cat_id , 'new_photo' , $cat_tags ) ;
	}

}

// --- class end ---
}

?>