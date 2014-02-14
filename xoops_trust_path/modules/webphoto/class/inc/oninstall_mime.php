<?php
// $Id: oninstall_mime.php,v 1.2 2010/10/06 02:22:46 ohwada Exp $

//=========================================================
// webphoto module
// 2010-10-01 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_inc_oninstall_mime
//=========================================================
class webphoto_inc_oninstall_mime extends webphoto_inc_base_ini
{
	var $_table_mime ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_inc_oninstall_mime( $dirname , $trust_dirname )
{
	$this->webphoto_inc_base_ini();
	$this->init_base_ini( $dirname , $trust_dirname );
	$this->init_handler(  $dirname );

	$this->_table_mime   = $this->prefix_dirname( 'mime' );
}

function &getSingleton( $dirname , $trust_dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webphoto_inc_oninstall_mime( $dirname , $trust_dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// mime table
//---------------------------------------------------------
function update()
{
	$this->_mime_add_column_ffmpeg();
	$this->_mime_add_column_kind_etc();
	$this->_mime_add_record_asf_etc();
	$this->_mime_add_record_ai_etc();
	$this->_mime_add_record_docx_etc();
	$this->_mime_add_record_svg_etc();
	$this->_mime_update_record_ffmpeg_s();
	$this->_mime_update_record_kind_s();
	$this->_mime_update_record_type_audio();
	$this->_mime_update_record_option_s();
	$this->_mime_delete_record_asx();
}

function _mime_add_column_ffmpeg()
{

// return if already exists
	if ( $this->exists_column( $this->_table_mime, 'mime_ffmpeg' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_mime ;
	$sql .= " ADD mime_ffmpeg varchar(255) NOT NULL default '' ";
	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Add mime_ffmpeg in <b>'. $this->_table_mime .'</b>' );
		return true;
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_mime .'</b>.' ) );
		return false;
	}

}

function _mime_add_column_kind_etc()
{

// return if already exists
	if ( $this->exists_column( $this->_table_mime, 'mime_kind' ) ) {
		return true;
	}

	$sql  = "ALTER TABLE ". $this->_table_mime ." ADD ( ";
	$sql .= "mime_kind int(4) NOT NULL default '0', ";
	$sql .= "mime_option varchar(255) NOT NULL default '' ";
	$sql .= " )";

	$ret = $this->query( $sql );

	if ( $ret ) {
		$this->set_msg( 'Add mime_kind in <b>'. $this->_table_mime .'</b>' );
		return true;
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_mime .'</b>.' ) );
		return false;
	}

}

function _mime_add_record_asf_etc()
{
	$mime_list = array();

	$mime_list[] = array(
		'mime_time_create' => 0 ,
		'mime_time_update' => 0 ,
		'mime_name'        => 'Third Generation Partnership Project 2 File Format' ,
		'mime_ext'         => '3g2' ,
		'mime_medium'      => 'video' ,
		'mime_type'        => 'video/3gpp2' ,
		'mime_perms'       => '&1&' ,
		'mime_option'      => 'ffmpeg:-ar 44100' ,
	);

	$mime_list[] = array(
		'mime_time_create' => 0 ,
		'mime_time_update' => 0 ,
		'mime_name'        => 'Third Generation Partnership Project File Format' ,
		'mime_ext'         => '3gp' ,
		'mime_medium'      => 'video' ,
		'mime_type'        => 'video/3gpp' ,
		'mime_perms'       => '&1&' ,
		'mime_option'      => 'ffmpeg:-ar 44100' ,
	);

	$mime_list[] = array(
		'mime_time_create' => 0 ,
		'mime_time_update' => 0 ,
		'mime_name'        => 'Advanced Systems Format' ,
		'mime_ext'         => 'asf' ,
		'mime_medium'      => 'video' ,
		'mime_type'        => 'video/x-ms-asf' ,
		'mime_perms'       => '&1&' ,
		'mime_option'      => 'ffmpeg:-ar 44100' ,
	);

	$mime_list[] = array(
		'mime_time_create' => 0 ,
		'mime_time_update' => 0 ,
		'mime_name'        => 'Flash Video' ,
		'mime_ext'         => 'flv' ,
		'mime_medium'      => 'video' ,
		'mime_type'        => 'video/x-flv application/octet-stream' ,
		'mime_perms'       => '&1&' ,
		'mime_option'      => 'ffmpeg:-ar 44100' ,
	);

	foreach ( $mime_list as $mime_row ) 
	{
		$ext = $mime_row['mime_ext'];

// skip if already exists
		$row = $this->_mime_get_row_by_ext( $ext );
		if ( is_array($row) ) {
			continue;
		}

		$ret = $this->_mime_insert_record( $mime_row );
		if ( $ret ) {
			$this->set_msg( 'Add '. $ext .' in <b>'. $this->_table_mime .'</b>' );
		} else {
			$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_mime .'</b>.' ) );
		}
	}
}

function _mime_add_record_ai_etc()
{
	$mime_list = array();

	$mime_list[] = array(
		'mime_name'        => 'Adobe Illustrator' ,
		'mime_ext'         => 'ai' ,
		'mime_type'        => 'application/postscript' ,
		'mime_perms'       => '&1&' ,
		'mime_kind'        => _C_WEBPHOTO_MIME_KIND_IMAGE_CONVERT ,
	);

	$mime_list[] = array(
		'mime_name'        => 'Encapsulated PostScript' ,
		'mime_ext'         => 'eps' ,
		'mime_type'        => 'application/postscript' ,
		'mime_perms'       => '&1&' ,
		'mime_kind'        => _C_WEBPHOTO_MIME_KIND_IMAGE_CONVERT ,
	);

	$mime_list[] = array(
		'mime_name'        => 'Apple Macintosh QuickDraw/PICT' ,
		'mime_ext'         => 'pct' ,
		'mime_type'        => 'image/x-pict' ,
		'mime_perms'       => '&1&' ,
		'mime_kind'        => _C_WEBPHOTO_MIME_KIND_IMAGE_CONVERT ,
	);

	$mime_list[] = array(
		'mime_name'        => 'Adobe Photoshop' ,
		'mime_ext'         => 'psd' ,
		'mime_type'        => 'image/x-photshop application/octet-stream' ,
		'mime_perms'       => '&1&' ,
		'mime_kind'        => _C_WEBPHOTO_MIME_KIND_IMAGE_CONVERT ,
		'mime_option'      => 'convert:-flatten;' ,
	);

	$mime_list[] = array(
		'mime_name'        => 'Tag Image File Format' ,
		'mime_ext'         => 'tif' ,
		'mime_type'        => 'image/tiff' ,
		'mime_perms'       => '&1&' ,
		'mime_kind'        => _C_WEBPHOTO_MIME_KIND_IMAGE_CONVERT ,
	);

	$mime_list[] = array(
		'mime_name'        => 'Windows Meta File' ,
		'mime_ext'         => 'wmf' ,
		'mime_type'        => 'image/wmf application/octet-stream' ,
		'mime_perms'       => '&1&' ,
		'mime_kind'        => _C_WEBPHOTO_MIME_KIND_IMAGE_CONVERT ,
	);

	$this->_mime_add_record_list( $mime_list );
}

function _mime_add_record_docx_etc()
{
	$mime_list = array();

	$mime_list[] = array(
		'mime_name'        => 'MS Word 2007' ,
		'mime_ext'         => 'docx' ,
		'mime_type'        => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ,
		'mime_perms'       => '&1&' ,
		'mime_kind'        => _C_WEBPHOTO_MIME_KIND_OFFICE_DOC ,
	);

	$mime_list[] = array(
		'mime_name'        => 'MS Power Point 2007' ,
		'mime_ext'         => 'pptx' ,
		'mime_type'        => 'application/vnd.openxmlformats-officedocument.presentationml.presentation' ,
		'mime_perms'       => '&1&' ,
		'mime_kind'        => _C_WEBPHOTO_MIME_KIND_OFFICE_PPT ,
	);

	$mime_list[] = array(
		'mime_name'        => 'MS Excel 2007' ,
		'mime_ext'         => 'xlsx' ,
		'mime_type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ,
		'mime_perms'       => '&1&' ,
		'mime_kind'        => _C_WEBPHOTO_MIME_KIND_OFFICE_XLS ,
	);

	$this->_mime_add_record_list( $mime_list );
}

function _mime_add_record_svg_etc()
{
	$mime_list = array();

	$mime_list[] = array(
		'mime_name'   => 'Scalable Vector Graphics' ,
		'mime_ext'    => 'svg' ,
		'mime_type'   => 'image/svg+xml' ,
		'mime_perms'  => '&1&' ,
		'mime_kind'   => _C_WEBPHOTO_MIME_KIND_IMAGE_CONVERT ,
		'mime_option' => 'convert:-size 1200;' ,
	);

	$mime_list[] = array(
		'mime_name'   => 'Windows Media Audio' ,
		'mime_ext'    => 'wma' ,
		'mime_type'   => 'audio/x-ms-wma' ,
		'mime_perms'  => '&1&' ,
		'mime_kind'   => _C_WEBPHOTO_MIME_KIND_AUDIO_FFMPEG ,
		'mime_option' => '' ,
	);

	$mime_list[] = array(
		'mime_name'   => 'Audio Interchange File Format' ,
		'mime_ext'    => 'aif' ,
		'mime_type'   => 'audio/aiff' ,
		'mime_perms'  => '&1&' ,
		'mime_kind'   => _C_WEBPHOTO_MIME_KIND_AUDIO_FFMPEG ,
		'mime_option' => '' ,
	);

	$mime_list[] = array(
		'mime_name'   => 'Audio Interchange File Format' ,
		'mime_ext'    => 'aifc' ,
		'mime_type'   => 'audio/aiff' ,
		'mime_perms'  => '&1&' ,
		'mime_kind'   => _C_WEBPHOTO_MIME_KIND_AUDIO_FFMPEG ,
		'mime_option' => '' ,
	);

	$mime_list[] = array(
		'mime_name'   => 'Audio Interchange File Format' ,
		'mime_ext'    => 'aiff' ,
		'mime_type'   => 'audio/aiff' ,
		'mime_perms'  => '&1&' ,
		'mime_kind'   => _C_WEBPHOTO_MIME_KIND_AUDIO_FFMPEG ,
		'mime_option' => '' ,
	);


	$mime_list[] = array(
		'mime_name'   => 'Audio UNIX' ,
		'mime_ext'    => 'au' ,
		'mime_type'   => 'audio/basic' ,
		'mime_perms'  => '&1&' ,
		'mime_kind'   => _C_WEBPHOTO_MIME_KIND_AUDIO_FFMPEG ,
		'mime_option' => '' ,
	);

	$mime_list[] = array(
		'mime_name'   => 'Sound UNIX' ,
		'mime_ext'    => 'snd' ,
		'mime_type'   => 'audio/basic' ,
		'mime_perms'  => '&1&' ,
		'mime_kind'   => _C_WEBPHOTO_MIME_KIND_AUDIO_FFMPEG ,
		'mime_option' => '' ,
	);


	$mime_list[] = array(
		'mime_name'   => 'Indeo Video Files' ,
		'mime_ext'    => 'ivf' ,
		'mime_type'   => 'application/octet-stream' ,
		'mime_perms'  => '&1&' ,
		'mime_kind'   => _C_WEBPHOTO_MIME_KIND_AUDIO_FFMPEG ,
		'mime_option' => '' ,
	);

	$mime_list[] = array(
		'mime_name'   => 'Musical Instrument Digital Interface MIDI-sequention Sound' ,
		'mime_ext'    => 'midi' ,
		'mime_type'   => 'audio/mid' ,
		'mime_perms'  => '&1&' ,
		'mime_kind'   => _C_WEBPHOTO_MIME_KIND_AUDIO_MID ,
		'mime_option' => '' ,
	);

	$mime_list[] = array(
		'mime_name'   => 'Musical Instrument Digital Interface MIDI-sequention Sound' ,
		'mime_ext'    => 'rmi' ,
		'mime_type'   => 'audio/mid' ,
		'mime_perms'  => '&1&' ,
		'mime_kind'   => _C_WEBPHOTO_MIME_KIND_AUDIO_MID ,
		'mime_option' => '' ,
	);

	$mime_list[] = array(
		'mime_name'   => 'MPEG-1 Audio Layer-III' ,
		'mime_ext'    => 'mpa' ,
		'mime_type'   => 'video/x-mpg' ,
		'mime_perms'  => '&1&' ,
		'mime_kind'   => _C_WEBPHOTO_MIME_KIND_AUDIO_FFMPEG ,
		'mime_option' => 'ffmpeg:-ar 44100' ,
	);

	$mime_list[] = array(
		'mime_name'   => 'MPEG-1 Audio Layer-III' ,
		'mime_ext'    => 'm1v' ,
		'mime_type'   => 'video/mpeg video/x-mpeg' ,
		'mime_perms'  => '&1&' ,
		'mime_kind'   => _C_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG ,
		'mime_option' => 'ffmpeg:-ar 44100' ,
	);

	$mime_list[] = array(
		'mime_name'   => 'MPEG-1 Audio Layer-III' ,
		'mime_ext'    => 'mpe' ,
		'mime_type'   => 'video/mpeg video/x-mpeg' ,
		'mime_perms'  => '&1&' ,
		'mime_kind'   => _C_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG ,
		'mime_option' => 'ffmpeg:-ar 44100' ,
	);

	$mime_list[] = array(
		'mime_name'   => 'MPEG-4' ,
		'mime_ext'    => 'mp4' ,
		'mime_type'   => 'video/mp4 video/mpeg4' ,
		'mime_perms'  => '&1&' ,
		'mime_kind'   => _C_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG ,
		'mime_option' => 'ffmpeg:-ar 44100' ,
	);

	$this->_mime_add_record_list( $mime_list );
}

function _mime_update_record_ffmpeg_s()
{
	$list = array( 'avi', 'mov', 'mpeg', 'mpg', 'wmv' );

	foreach ( $list as $ext ) 
	{
		$row  = $this->_mime_get_row_by_ext( $ext );

// skip if already set
		if ( $row['mime_option'] ) {
			continue;
		}

		$row['mime_option'] = 'ffmpeg:-ar 44100' ;

		$ret = $this->_mime_update_record( $row );
		if ( $ret ) {
			$this->set_msg( 'Update '. $ext .' in <b>'. $this->_table_mime .'</b>' );
		} else {
			$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_mime .'</b>.' ) );
		}
	}

}

function _mime_update_record_kind_s()
{
	$this->_mime_update_record_kind( 'gif',  _C_WEBPHOTO_MIME_KIND_IMAGE );
	$this->_mime_update_record_kind( 'png',  _C_WEBPHOTO_MIME_KIND_IMAGE );
	$this->_mime_update_record_kind( 'jpg',  _C_WEBPHOTO_MIME_KIND_IMAGE_JPEG );
	$this->_mime_update_record_kind( 'jpeg', _C_WEBPHOTO_MIME_KIND_IMAGE_JPEG );
	$this->_mime_update_record_kind( 'bmp',  _C_WEBPHOTO_MIME_KIND_IMAGE_CONVERT );

	$this->_mime_update_record_kind( 'flv',  _C_WEBPHOTO_MIME_KIND_VIDEO_FLV );
	$this->_mime_update_record_kind( '3g2',  _C_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG );
	$this->_mime_update_record_kind( '3gp',  _C_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG );
	$this->_mime_update_record_kind( 'asf',  _C_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG );
	$this->_mime_update_record_kind( 'avi',  _C_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG );
	$this->_mime_update_record_kind( 'mov',  _C_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG );
	$this->_mime_update_record_kind( 'mpg',  _C_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG );
	$this->_mime_update_record_kind( 'mpeg', _C_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG );
	$this->_mime_update_record_kind( 'wmv',  _C_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG );

	$this->_mime_update_record_kind( 'ram',  _C_WEBPHOTO_MIME_KIND_AUDIO );
	$this->_mime_update_record_kind( 'mp3',  _C_WEBPHOTO_MIME_KIND_AUDIO_MP3 );
	$this->_mime_update_record_kind( 'mid',  _C_WEBPHOTO_MIME_KIND_AUDIO_MID );
	$this->_mime_update_record_kind( 'wav',  _C_WEBPHOTO_MIME_KIND_AUDIO_WAV );

	$this->_mime_update_record_kind( 'doc',  _C_WEBPHOTO_MIME_KIND_OFFICE_DOC );
	$this->_mime_update_record_kind( 'xls',  _C_WEBPHOTO_MIME_KIND_OFFICE_XLS );
	$this->_mime_update_record_kind( 'ppt',  _C_WEBPHOTO_MIME_KIND_OFFICE_PPT );
	$this->_mime_update_record_kind( 'pdf',  _C_WEBPHOTO_MIME_KIND_OFFICE_PDF );

}

function _mime_update_record_type_audio()
{
	$this->_mime_update_record_type( 'mp3', 'audio/mp3' );
}

function _mime_update_record_option_s()
{
	$rows = $this->_mime_get_rows_all();
	foreach( $rows as $row )
	{
		// skip if empty
		if ( empty($row['mime_ffmpeg']) ) {
			continue;
		}

		// skip if already set
		if ( $row['mime_option'] ) {
			continue;
		}

		$row['mime_option'] = 'ffmpeg:'.$row['mime_ffmpeg'].';';
		$ret = $this->_mime_update_record( $row );
		if ( $ret ) {
			$this->set_msg( 'Update '. $row['mime_ext'] .' in <b>'. $this->_table_mime .'</b>' );
		} else {
			$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_mime .'</b>.' ) );
		}
	}
}

function _mime_delete_record_asx()
{
	$row = $this->_mime_get_row_by_ext( 'asx' );
	if ( !is_array($row) ) {
		return true;	// no action
	}

	$ret = $this->_mime_delete_by_id( $row['mime_id'] );
	if ( $ret ) {
		$this->set_msg( 'Delete asx in <b>'. $this->_table_mime .'</b>' );
		return true;
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not delete <b>'. $this->_table_mime .'</b>.' ) );
		return false;
	}

}

function _mime_add_record_list( $mime_list )
{
	foreach ( $mime_list as $mime_row ) 
	{
		$ext = $mime_row['mime_ext'];

// skip if already exists
		$row = $this->_mime_get_row_by_ext( $ext );
		if ( is_array($row) ) {
			continue;
		}

		$mime_row['mime_time_create'] = 0 ;
		$mime_row['mime_time_update'] = 0 ;

		if ( ! isset($mime_row['mime_kind']) ) {
			$mime_row['mime_kind'] = 0 ;
		}
		if ( ! isset($mime_row['mime_medium']) ) {
			$mime_row['mime_medium'] = '' ;
		}
		if ( ! isset($mime_row['mime_ffmpeg']) ) {
			$mime_row['mime_ffmpeg'] = '' ;
		}
		if ( ! isset($mime_row['mime_option']) ) {
			$mime_row['mime_option'] = '' ;
		}

		$ret = $this->_mime_insert_record( $mime_row );
		if ( $ret ) {
			$this->set_msg( 'Add '. $ext .' in <b>'. $this->_table_mime .'</b>' );
		} else {
			$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_mime .'</b>.' ) );
		}
	}
}

function _mime_update_record_kind( $ext, $kind )
{
	$row = $this->_mime_get_row_by_ext( $ext );

// no acton if not exist
	if ( !is_array($row) ) {
		return;
	}

// no acton if same kind
	if ( $row['mime_kind'] == $kind ) {
		return;
	}

	$row['mime_kind'] = $kind ;

	$ret = $this->_mime_update_record( $row );
	if ( $ret ) {
		$this->set_msg( 'Update '. $ext .' in <b>'. $this->_table_mime .'</b>' );
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_mime .'</b>.' ) );
	}

}

function _mime_update_record_type( $ext, $type )
{
	$row = $this->_mime_get_row_by_ext( $ext );

// no acton if not exist
	if ( !is_array($row) ) {
		return;
	}

	$type_arr = explode( ' ', $row['mime_type'] );

// no acton if same type
	if ( in_array( $type, $type_arr ) ) {
		return;
	}

	$row['mime_type'] .= ' '.$type ;

	$ret = $this->_mime_update_record( $row );
	if ( $ret ) {
		$this->set_msg( 'Update '. $ext .' in <b>'. $this->_table_mime .'</b>' );
	} else {
		$this->set_msg( $this->highlight( 'ERROR: Could not update <b>'. $this->_table_mime .'</b>.' ) );
	}

}

function _mime_insert_record( $row )
{
	extract( $row ) ;

	$sql  = 'INSERT INTO '. $this->_table_mime .' (';

	$sql .= 'mime_time_create, ';
	$sql .= 'mime_time_update, ';
	$sql .= 'mime_name, ';
	$sql .= 'mime_ext, ';
	$sql .= 'mime_medium, ';
	$sql .= 'mime_type, ';
	$sql .= 'mime_perms, ';
	$sql .= 'mime_ffmpeg, ';
	$sql .= 'mime_kind, ';
	$sql .= 'mime_option ';
	
	$sql .= ') VALUES ( ';

	$sql .= intval($mime_time_create).', ';
	$sql .= intval($mime_time_update).', ';
	$sql .= $this->quote($mime_name).', ';
	$sql .= $this->quote($mime_ext).', ';
	$sql .= $this->quote($mime_medium).', ';
	$sql .= $this->quote($mime_type).', ';
	$sql .= $this->quote($mime_perms).', ';
	$sql .= $this->quote($mime_ffmpeg).', ';
	$sql .= intval($mime_kind).', ';
	$sql .= $this->quote($mime_option).' ';

	$sql .= ')';

	$ret = $this->query( $sql );
	if ( !$ret ) { return false; }

	return $this->_db->getInsertId();
}

function _mime_update_record( $row )
{
	extract( $row ) ;

	$sql  = 'UPDATE '. $this->_table_mime .' SET ';

	$sql .= 'mime_time_create='.intval($mime_time_create).', ';
	$sql .= 'mime_time_update='.intval($mime_time_update).', ';
	$sql .= 'mime_name='.$this->quote($mime_name).', ';
	$sql .= 'mime_ext='.$this->quote($mime_ext).', ';
	$sql .= 'mime_medium='.$this->quote($mime_medium).', ';
	$sql .= 'mime_type='.$this->quote($mime_type).', ';
	$sql .= 'mime_perms='.$this->quote($mime_perms).', ';
	$sql .= 'mime_ffmpeg='.$this->quote($mime_ffmpeg).', ';
	$sql .= 'mime_kind='.intval($mime_kind).', ';
	$sql .= 'mime_option='.$this->quote($mime_option).' ';
	
	$sql .= 'WHERE mime_id='.intval($mime_id);

	return $this->query( $sql );
}

function _mime_delete_by_id( $id )
{
	$sql  = 'DELETE FROM '. $this->_table_mime ;
	$sql .= ' WHERE mime_id='.intval( $id );
	return $this->query( $sql );
}

function _mime_get_rows_all()
{
	$sql  = 'SELECT * FROM '. $this->_table_mime ;
	$sql .= ' ORDER BY mime_id';
	return $this->get_rows_by_sql( $sql );
}

function _mime_get_row_by_ext( $ext )
{
	$sql  = 'SELECT * FROM '. $this->_table_mime ;
	$sql .= ' WHERE mime_ext='.$this->quote( $ext );
	return $this->get_row_by_sql( $sql );
}

// --- class end ---
}

?>