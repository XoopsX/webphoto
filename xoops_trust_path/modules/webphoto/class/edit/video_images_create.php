<?php
// $Id: video_images_create.php,v 1.2 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2010-10-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_video_images_create
//=========================================================
class webphoto_edit_video_images_create extends webphoto_edit_base_create
{
	var $_ext_class ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_video_images_create( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base_create( $dirname , $trust_dirname );

	$this->_ext_class 
		=& webphoto_ext::getInstance( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_video_images_create( 
			$dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create
//---------------------------------------------------------
function create( $param )
{
	$this->clear_flags() ;

	$ret = $this->_ext_class->execute( 'video_images', $param );
	if ( $ret == 1 ) {
		$this->set_flag_created() ;
		return 1 ;
	} elseif ( $ret == -1 ) {
		$this->set_flag_failed() ;
		return -1 ;
	}
	return 0;
}

// --- class end ---
}

?>