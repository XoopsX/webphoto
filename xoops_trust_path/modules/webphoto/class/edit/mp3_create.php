<?php
// $Id: mp3_create.php,v 1.4 2010/10/08 15:53:16 ohwada Exp $

//=========================================================
// webphoto module
// 2009-10-25 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
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

	var $_param_ext    = 'mp3' ;
	var $_param_dir    = 'mp3s';
	var	$_param_mime   = 'audio/mpeg' ;
	var $_param_medium = 'audio' ;
	var $_param_kind   = _C_WEBPHOTO_FILE_KIND_MP3 ;
	var $_msg_created  = 'create mp3' ;
	var $_msg_failed   = 'fail to create mp3' ;

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
	$name_param =$this->build_name_param( $item_id );
	$file  = $name_param['file'] ;

	$ret = $this->_lame_class->create_mp3( $src_file, $file );

	return $this->build_result( $ret, $name_param );
}

// --- class end ---
}

?>