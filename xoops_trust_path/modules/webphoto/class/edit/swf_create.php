<?php
// $Id: swf_create.php,v 1.4 2010/10/08 15:53:16 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// create_swf() -> execute()
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_swf_create
//=========================================================
class webphoto_edit_swf_create extends webphoto_edit_base_create
{
	var $_ext_class ;

	var $_param_ext    = 'swf' ;
	var $_param_dir    = 'swfs';
	var	$_param_mime   = 'application/x-shockwave-flash' ;
	var $_param_medium = '' ;
	var $_param_kind   = _C_WEBPHOTO_FILE_KIND_SWF ;
	var $_msg_created  = 'create swf' ;
	var $_msg_failed   = 'fail to create swf' ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_swf_create( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base_create( $dirname , $trust_dirname );

	$this->_ext_class =& webphoto_ext::getInstance( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_swf_create( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create swf
//---------------------------------------------------------
function create_param( $param )
{
	$this->clear_msg_array();

	$item_id  = $param['item_id'];
	$src_file = $param['src_file'];
	$src_ext  = $param['src_ext'];
	$src_kind = $param['src_kind'];

// return input file is swf 
	if ( $this->is_swf_ext( $src_ext ) ) {
		return null ;
	}

	$swf_param = $this->create_swf( $item_id, $src_file, $src_ext ) ;
	if ( !is_array($swf_param) ) {
		return null;
	}

	return $swf_param ;
}

function create_swf( $item_id, $src_file, $src_ext )
{
	$name_param =$this->build_name_param( $item_id );
	$file  = $name_param['file'] ;

	$param = array(
		'src_file' => $src_file ,
		'src_ext'  => $src_ext ,
		'swf_file' => $file ,
	);

	$ret = $this->_ext_class->execute( 'swf', $param ) ;

	return $this->build_result( $ret, $name_param );
}

// --- class end ---
}

?>