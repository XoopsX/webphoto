<?php
// $Id: mid.php,v 1.1 2009/11/06 18:06:06 ohwada Exp $

//=========================================================
// webphoto module
// 2009-10-25 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_ext_mid
//=========================================================
class webphoto_ext_mid extends webphoto_ext_base
{
	var $_timidity_class;
	var $_lame_class;
	var $_ffmpeg_class ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_ext_mid( $dirname )
{
	$this->webphoto_ext_base( $dirname );

	$this->_timidity_class =& webphoto_timidity::getInstance( $dirname );
	$this->_lame_class     =& webphoto_lame::getInstance( $dirname );
	$this->_ffmpeg_class   =& webphoto_ffmpeg::getInstance( $dirname );

	$this->set_debug_by_name( 'MID' );
}

//---------------------------------------------------------
// check ext
//---------------------------------------------------------
function is_ext( $ext )
{
	return $this->is_audio_mid_ext( $ext );
}

function is_audio_mid_ext( $ext )
{
	return $this->match_ext_kind( $ext, _C_WEBPHOTO_MIME_KIND_AUDIO_MID );
}

//---------------------------------------------------------
// create mp3
//---------------------------------------------------------
function create_mp3( $param )
{
	$item_id  = $param['item_id'];
	$src_file = $param['src_file'];
	$mp3_file = $param['mp3_file'];

	$ret1 = $this->_timidity_class->create_wav_tmp( $item_id, $src_file );
	if ( !isset($ret1['flag']) || !$ret1['flag'] ) {
		return $ret1;
	}

	$wav_file = $ret1['src_file'];
	$ret2 = $this->_lame_class->create_mp3( $wav_file, $mp3_file );
	unlink( $wav_file );
	return $ret2;
}

//---------------------------------------------------------
// duration
//---------------------------------------------------------
function get_duration_size( $param )
{
	$src_file = $param['src_file'];
	return $this->_ffmpeg_class->get_duration_size( $src_file );
}

// --- class end ---
}

?>