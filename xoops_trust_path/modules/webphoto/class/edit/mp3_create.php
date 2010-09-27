<?php
// $Id: mp3_create.php,v 1.3 2010/09/27 03:42:54 ohwada Exp $

//=========================================================
// webphoto module
// 2009-10-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-20 K.OHWADA
// webphoto_lame
// 2009-11-11 K.OHWADA
// $trust_dirname
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_edit_mp3_create
//=========================================================
class webphoto_edit_mp3_create extends webphoto_edit_base_create
{
	var $_lame_class;

	var $_SUB_DIR_MP3S = 'mp3s';
	var $_EXT_MP3      = 'mp3';

	var $_MP3_EXT    = 'mp3';
	var $_MP3_MIME   = 'audio/mpeg';
	var $_MP3_MEDIUM = 'audio';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_edit_mp3_create( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base_create( $dirname , $trust_dirname );

	$this->_lame_class   =& webphoto_lame::getInstance(   $dirname, $trust_dirname  );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_edit_mp3_create( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// create mp3
//---------------------------------------------------------
function create_param( $param )
{
	$this->clear_msg_array();

	$item_id  = $param['item_id'];
	$src_file = $param['src_file'];
	$src_ext  = $param['src_ext'];
	$src_kind = $param['src_kind'];

// return input file is not wav 
	if ( ! $this->is_wav_ext( $src_ext ) ) {
		return null ;
	}

	$mp3_param = $this->create_mp3( $item_id, $src_file, $src_ext ) ;
	if ( !is_array($mp3_param) ) {
		return null;
	}

	return $mp3_param ;
}

function create_mp3( $item_id, $src_file, $src_ext )
{
	$this->_flag_created = false ;
	$this->_flag_failed  = false ;

	$mp3_param = null ;

	$name_param =$this->build_random_name_param( $item_id, $this->_EXT_MP3, $this->_SUB_DIR_MP3S );
	$name  = $name_param['name'] ;
	$path  = $name_param['path'] ;
	$file  = $name_param['file'] ;
	$url   = $name_param['url']  ;

	$ret = $this->_lame_class->create_mp3( $src_file, $file );

// created
	if ( $ret == 1 ) {
		$this->set_flag_created() ;
		$this->set_msg( 'create mp3' );
		$mp3_param = array(
			'url'    => $url ,
			'file'   => $file ,
			'path'   => $path ,
			'name'   => $name ,
			'ext'    => $this->_MP3_EXT ,
			'mime'   => $this->_MP3_MIME ,
			'medium' => $this->_MP3_MEDIUM ,
			'size'   => filesize( $file ) ,
			'kind'   => _C_WEBPHOTO_FILE_KIND_MP3 ,
		);

// failed
	} elseif ( $ret == -1 ) {
		$this->set_flag_failed() ;
		$this->set_msg( 'fail to create mp3', true ) ;
	}

	return $mp3_param ;
}

// --- class end ---
}

?>