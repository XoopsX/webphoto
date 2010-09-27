<?php
// $Id: wav_create.php,v 1.1 2010/09/27 03:44:45 ohwada Exp $

//=========================================================
// webphoto module
// 2010-09-20 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_wav_create
//=========================================================
class webphoto_edit_wav_create extends webphoto_edit_base_create
{
	var $_ext_class ;

	var $_SUB_DIR_WAVS = 'wavs';
	var $_EXT_WAV      = 'wav';

	var $_WAV_EXT    = 'wav';
	var $_WAV_MIME   = 'audio/wav';
	var $_WAV_MEDIUM = 'audio';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_wav_create( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base_create( $dirname , $trust_dirname );

	$this->_ext_class 
		=& webphoto_ext::getInstance( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_wav_create( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create wav
//---------------------------------------------------------
function create_param( $param )
{
	$this->clear_msg_array();

	$item_id  = $param['item_id'];
	$src_file = $param['src_file'];
	$src_ext  = $param['src_ext'];
	$src_kind = $param['src_kind'];

// return input file is wav 
	if ( $this->is_wav_ext( $src_ext ) ) {
		return null ;
	}

	$wav_param = $this->create_wav( $item_id, $src_file, $src_ext ) ;
	if ( !is_array($wav_param) ) {
		return null;
	}

	return $wav_param ;
}

function create_wav( $item_id, $src_file, $src_ext )
{
	$this->_flag_created = false ;
	$this->_flag_failed  = false ;

	$wav_param = null ;

	$name_param =$this->build_random_name_param( 
		$item_id, $this->_EXT_WAV, $this->_SUB_DIR_WAVS );
	$name  = $name_param['name'] ;
	$path  = $name_param['path'] ;
	$file  = $name_param['file'] ;
	$url   = $name_param['url']  ;

	$param = array(
		'item_id'  => $item_id ,
		'src_file' => $src_file ,
		'src_ext'  => $src_ext ,
		'wav_file' => $file ,
	);

	$ret = $this->_ext_class->execute( 'wav', $param ) ;

// created
	if ( $ret == 1 ) {
		$this->set_flag_created() ;
		$this->set_msg( 'create wav' );
		$wav_param = array(
			'url'    => $url ,
			'file'   => $file ,
			'path'   => $path ,
			'name'   => $name ,
			'ext'    => $this->_WAV_EXT ,
			'mime'   => $this->_WAV_MIME ,
			'medium' => $this->_WAV_MEDIUM ,
			'size'   => filesize( $file ) ,
			'kind'   => _C_WEBPHOTO_FILE_KIND_WAV ,
		);

// failed
	} elseif ( $ret == -1 ) {
		$this->set_flag_failed() ;
		$this->set_msg( 'fail to create wav', true ) ;
	}

	return $wav_param ;
}

// --- class end ---
}

?>