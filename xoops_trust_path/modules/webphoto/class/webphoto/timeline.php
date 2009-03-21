<?php
// $Id: timeline.php,v 1.4 2009/03/21 07:52:26 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-15 K.OHWADA
//=========================================================

// === class begin ===
if( !class_exists('webphoto_timeline') ) 
{

//=========================================================
// class webphoto_timeline
//=========================================================
class webphoto_timeline
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function &getInstance( $dirname )
{
	static $instance;
	$class = 'webphoto_'. $dirname .'_timeline';

	if (!isset($instance)) {

// if extend class loaded
		if ( class_exists( $class ) ) {
			$instance = new $class( $dirname );

// default
		} else {
			$instance = new webphoto_inc_timeline( $dirname );
		}
	}

	return $instance;
}

// --- class end ---
}

// === class end ===
}

?>