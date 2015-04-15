<?php
// $Id: image_create.php,v 1.1 2010/09/27 03:44:45 ohwada Exp $

//=========================================================
// webphoto module
// 2010-09-20 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_image_create
//=========================================================
class webphoto_edit_image_create extends webphoto_edit_base_create
{
	var $_ext_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_image_create( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base_create( $dirname , $trust_dirname );

	$this->_ext_class  
		=& webphoto_ext::getInstance( $dirname , $trust_dirname );
}

public static function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_image_create( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create
//---------------------------------------------------------
function create( $param )
{
	$extra_param = $this->_ext_class->execute( 'image', $param );
	if ( isset( $extra_param['src_file'] ) ) {
		$this->set_flag_created() ;
		$this->set_result( $extra_param );
		return 1 ;

	} elseif ( isset( $extra_param['errors'] ) ) {
		$this->set_flag_failed() ;
		$this->set_error( $extra_param['errors'] ) ;
		return -1 ;
	}

	return 0 ;
}

// --- class end ---
}

?>