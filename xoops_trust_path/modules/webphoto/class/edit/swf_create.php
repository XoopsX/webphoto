<?php
// $Id: swf_create.php,v 1.3 2010/09/27 03:42:54 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-20 K.OHWADA
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

	var $_SUB_DIR_SWFS = 'swfs';
	var $_EXT_SWF      = 'swf';

	var $_SWF_EXT    = 'swf';
	var $_SWF_MIME   = 'application/x-shockwave-flash';
	var $_SWF_MEDIUM = '';

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
	$this->_flag_created = false ;
	$this->_flag_failed  = false ;

	$swf_param = null ;

	$name_param =$this->build_random_name_param( $item_id, $this->_EXT_SWF, $this->_SUB_DIR_SWFS );
	$name  = $name_param['name'] ;
	$path  = $name_param['path'] ;
	$file  = $name_param['file'] ;
	$url   = $name_param['url']  ;

	$param = array(
		'src_file' => $src_file ,
		'src_ext'  => $src_ext ,
		'swf_file' => $file ,
	);

	$ret = $this->_ext_class->execute( 'swf', $param ) ;

// created
	if ( $ret == 1 ) {
		$this->set_flag_created() ;
		$this->set_msg( 'create swf' );
		$swf_param = array(
			'url'    => $url ,
			'file'   => $file ,
			'path'   => $path ,
			'name'   => $name ,
			'ext'    => $this->_SWF_EXT ,
			'mime'   => $this->_SWF_MIME ,
			'medium' => $this->_SWF_MEDIUM ,
			'size'   => filesize( $file ) ,
			'kind'   => _C_WEBPHOTO_FILE_KIND_SWF ,
		);

// failed
	} elseif ( $ret == -1 ) {
		$this->set_flag_failed() ;
		$this->set_msg( 'fail to create swf', true ) ;
	}

	return $swf_param ;
}

// --- class end ---
}

?>