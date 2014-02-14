<?php
// $Id: notification_select.php,v 1.1 2010/01/25 10:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2010-01-10 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_notification_select
//=========================================================
class webphoto_notification_select
{
	var $_d3_notification_select_class;
	var $_config_class;

	var $_cfg_use_pathinfo;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_notification_select( $dirname )
{
	$this->_notification_select_class =& webphoto_d3_notification_select::getInstance();
	$this->_notification_select_class->init( $dirname ); 

	$this->_config_class =& webphoto_config::getInstance( $dirname );
	$this->_cfg_use_pathinfo = $this->_config_class->get_by_name('use_pathinfo');
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_notification_select( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// notification select class
//---------------------------------------------------------
function build_notification_select( $cat_id=0 )
{
// for core's notificationSubscribableCategoryInfo
	$_SERVER['PHP_SELF'] = $this->_notification_select_class->get_new_php_self();
	if ( $cat_id > 0 ) {
		$_GET['cat_id'] = $cat_id;
	}

	return $this->_notification_select_class->build( $this->_cfg_use_pathinfo );
}

// --- class end ---
}

?>