<?php
// $Id: docomo_create.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_docomo_create
//=========================================================
class webphoto_edit_docomo_create extends webphoto_edit_base_create
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_docomo_create( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base_create( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_docomo_create( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create docomo
//---------------------------------------------------------
function create_param( $param )
{
	$this->clear_msg_array();

	if ( ! $this->is_video_docomo_ext( $param['src_ext'] ) ) {
		return null;
	}

// same file as cont
	$docomo_param         = $param ;
	$docomo_param['path'] = '' ;	// null
	$docomo_param['kind'] = _C_WEBPHOTO_FILE_KIND_VIDEO_DOCOMO ;
	$this->set_msg( 'create docomo' );
	return $docomo_param ;
}

// --- class end ---
}

?>