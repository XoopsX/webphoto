<?php
// $Id: notification_event.php,v 1.3 2009/12/16 13:32:34 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-12-06 K.OHWADA
// notify_waiting()
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
	var $_uri_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_notification_event( $dirname , $trust_dirname )
{
	$this->webphoto_d3_notification_event();
	$this->init( $dirname , $trust_dirname );

	$this->_cat_handler  
		=& webphoto_cat_handler::getInstance( $dirname , $trust_dirname );
	$this->_uri_class =& webphoto_uri::getInstance( $dirname );

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

	$photo_uri = $this->_uri_class->build_photo( $photo_id );

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

function notify_waiting( $photo_id, $photo_title )
{
	$url = $this->_MODULE_URL .'/admin/index.php?fct=item_manager&op=modify_form&item_id='. $photo_id ;
	$tags = array( 
		'PHOTO_TITLE' => $photo_title ,
		'WAITING_URL' => $url ,
	 );
	$this->trigger_event( 'global' , 0 , 'waiting' , $tags ) ;
}

// --- class end ---
}

?>