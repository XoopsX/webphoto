<?php
// $Id: redothumbs.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// used webphoto_lib_exif
// used create_thumb_from_photo()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_redothumbs
//=========================================================
class webphoto_admin_redothumbs extends webphoto_base_this
{
	var $_image_class;
	var $_delete_class;
	var $_exif_class;

	var $_post_forceredo ;
	var $_post_removerec ;
	var $_post_resize    ;
	var $_post_exif      ;

	var $_cfg_width     ;
	var $_cfg_height    ;
	var $_cfg_makethumb ;

	var $_msg_array = array();

	var $_ADMIN_REDO_PHP;

	var $_DEFAULT_SIZE = 10;
	var $_MAX_SIZE     = 1000;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_redothumbs( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_image_class  =& webphoto_image_create::getInstance( $dirname , $trust_dirname );
	$this->_delete_class =& webphoto_photo_delete::getInstance( $dirname );
	$this->_exif_class   =& webphoto_lib_exif::getInstance();

	$this->_ADMIN_REDO_PHP = $this->_MODULE_URL .'/admin/index.php?fct=redothumbs';
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_redothumbs( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$post_submit = $this->_post_class->get_post_text('submit');
	$post_start  = $this->_post_class->get_post_int('start');

	$post_size = $this->_post_class->get_post_int('size') ;
	if( $post_size <= 0 ) {
		$post_size = $this->_DEFAULT_SIZE ;
	} elseif ( $post_size > $this->_MAX_SIZE ) {
		$post_size = $this->_MAX_SIZE ;
	}

	$param = array(
		'start'     => $post_start,
		'size'      => $post_size,
		'forceredo' => $this->_post_class->get_post_int('forceredo'),
		'removerec' => $this->_post_class->get_post_int('removerec'),
		'resize'    => $this->_post_class->get_post_int('resize'),
		'exif'      => $this->_post_class->get_post_int('exif'),
	);

	$result  = null;
	$start   = $post_start;
	$counter = 0;

	$this->_check();

	if( $post_submit ) {
		$this->_clear_msg();
		$counter = $this->_submit( $param );
		if ( $counter === false ) {
			$msg = 'DB Error <br />'.$this->get_format_error();
			redirect_header( $this->_ADMIN_REDO_PHP, 5, $msg ) ;
			exit();
		}

		$result = $this->_get_msg();
		$start  = $post_start + $post_size ;
	}

// Render forms
	xoops_cp_header() ;
	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'REDOTHUMB' );

	$param['start']   = $start;
	$param['counter'] = $counter;

	$this->_print_form( $param );

	$this->_remove_tmp_files();

	if ( $result ) {
		echo "<br />\n" ;
		echo $result ;
	}

	xoops_cp_footer() ;
}

function _check()
{
	$this->_cfg_makethumb   = $this->_config_class->get_by_name('makethumb');

// get flag of safe_mode
	$safe_mode_flag = ini_get( "safe_mode" ) ;

// check if the directories of thumbs and photos are same.
	if( $this->_THUMBS_DIR == $this->_PHOTOS_DIR ) {
		die( "The directory for thumbnails is same as for photos." ) ;
	}

// check or make thumbs_dir
	if( $this->_cfg_makethumb && ! is_dir( $this->_THUMBS_DIR ) ) {
		if( $safe_mode_flag ) {
			$msg = 'At first create & chmod 777 "'. $this->_THUMBS_DIR .'" by ftp or shell.' ;
			redirect_header( $this->_ADMIN_INDEX_PHP, 5, $msg);
			exit() ;
		}

		$ret = mkdir( $this->_THUMBS_DIR , 0777 ) ;
		if( ! $ret ) {
			$msg = $this->_THUMBS_DIR.' is not a directory' ;
			redirect_header( $this->_ADMIN_INDEX_PHP, 5, $msg );
			exit() ;
		} else {
			@chmod( $this->_THUMBS_DIR , 0777 ) ;
		}
	}

	return true;
}

function _remove_tmp_files()
{
// Clear tempolary files
	$removed_tmp_num = $this->_image_class->clear_tmp_files_in_tmp_dir();
	if( $removed_tmp_num > 0 ) {
		printf( "<br />"._AM_WEBPHOTO_FMT_NUMBEROFREMOVEDTMPS."<br />\n" , $removed_tmp_num ) ;
	}
}

function _submit( $param )
{
	$post_start            = $param['start'];
	$post_size             = $param['size'];
	$this->_post_forceredo = $param['forceredo'];
	$this->_post_removerec = $param['removerec'];
	$this->_post_resize    = $param['resize'];
	$this->_post_exif      = $param['exif'];

	$this->_cfg_width     = $this->get_config_by_name('width');
	$this->_cfg_height    = $this->get_config_by_name('height');
	$this->_cfg_makethumb = $this->get_config_by_name('makethumb');

	$rows = $this->_photo_handler->get_rows_all_asc( $post_size, $post_start );

	$counter = 0 ;

	foreach ( $rows as $row_orig )
	{
		extract( $row_orig ) ;
		$row = $row_orig;

		$photo_size = array(
			'cont_size'     => $photo_cont_size ,
			'cont_width'    => $photo_cont_width ,
			'cont_height'   => $photo_cont_height ,
			'middle_width'  => $photo_middle_width ,
			'middle_height' => $photo_middle_height ,
		);

		$counter ++ ;
		$this->_set_msg( ( $counter + $post_start - 1 ) . ') ' ) ;
		$this->_set_msg( sprintf( _AM_WEBPHOTO_FMT_CHECKING , $photo_id ) ) ;

	// Check if the main image exists
		if ( $this->_check_main_file( $photo_file_path, $photo_cont_path ) ) {
			$this->_remove_main_file( $photo_id );
			continue ;
		}

	// Check file info
		if ( $this->_check_file_info( $photo_file_path, $photo_file_size ) ) {
			$row = $this->_update_file_info( $row_update, $photo_file_path ) ;
			if ( !$row ) {
				return false;
			}
		}

	// Check if the file is not image
		if ( ! $this->is_normal_ext( $photo_cont_ext ) ) {
			$ret = $this->_update_no_image( $row );
			if ( !$ret ) {
				return false;
			}
			continue ;
		}

	// --- nomal image ---

	// get exif
		if ( $this->_check_exif( $photo_cont_exif ) ) {
			$row = $this->_update_exif( $row );
			if ( !$row ) {
				return false;
			}
		}

	// Size of main photo
		$photo_size = $this->_get_photo_size( $photo_cont_path, $photo_size );

		if ( $this->_check_resize( $photo_size ) ) {
			$photo_size = $this->_redo_photo_size( $photo_cont_path, $photo_cont_ext ) ;
		}

	// Check and repair record of the photo if necessary
		if ( $this->_check_photo_size( $photo_cont_width, $photo_cont_height, $photo_size ) ) {
			$row = $this->_update_photo_size( $row, $photo_size );
			if ( !$row ) {
				return false ;
			}
		}

	// --- thumb ---
		$ret = $this->_update_thumb( $row );
		if ( !$ret ) {
			return false ;
		}

	}

	return $counter ;
}

function _check_main_file( $photo_file_path, $photo_cont_path )
{
	if ( $photo_file_path ) {
		$file_full_path  = XOOPS_ROOT_PATH . $photo_file_path;
	} else {
		return true ;
	}

	if ( $photo_cont_path ) {
		$cont_full_path  = XOOPS_ROOT_PATH . $photo_cont_path;
	} else {
		return true ;
	}

	if ( !$this->check_file( $file_full_path ) ||
	     !$this->check_file( $cont_full_path )) {
		return true;
	}

	return false;
}

function _remove_main_file( $photo_id )
{
	$this->_set_msg( _AM_WEBPHOTO_PHOTONOTEXISTS." &nbsp; " ) ;
	if ( $this->_post_removerec ) {
		$this->_delete_class->delete_photo( $photo_id );
		$this->_set_msg( _AM_WEBPHOTO_RECREMOVED."<br />\n" ) ;
	} else {
		$this->_set_msg( _AM_WEBPHOTO_SKIPPED."<br />\n" ) ;
	}
}

function _check_file_info( $photo_file_path, $photo_file_size )
{
	if ( $photo_file_path && empty($photo_file_size) ) {
		return true;
	}
	return false;
}

function _update_file_info( $row, $photo_file_path )
{
	if ( $photo_file_path ) {
		$file_full_path  = XOOPS_ROOT_PATH . $photo_file_path;
	} else {
		return true ;
	}

	$row['photo_file_size'] = filesize( $file_full_path );
	$ret = $this->_photo_handler->update( $row );
	if ( !$ret ) {
		$this->set_error( $this->_photo_handler->get_errors() );
		return false ;
	}

	$this->_set_msg( " update file info &nbsp; " ) ;
	return $row ;
}

function _update_no_image( $row )
{
	extract( $row ) ;

	$thumb_full_path = '';
	if ( $photo_thumb_path ) {
		$thumb_full_path = XOOPS_ROOT_PATH . $photo_thumb_path;
	}

	$this->_set_msg( ' not image ' ) ;

	if ( $this->_post_forceredo || !$this->check_file( $thumb_full_path ) ) {
		$ret = $this->_create_update_thumb(
			$row, $photo_cont_path , $photo_id , $photo_cont_ext );

		if ( $ret == _C_WEBPHOTO_ERR_DB ) {
			return false;
		} elseif ( $ret == _C_WEBPHOTO_IMAGE_READFAULT ) {
			$this->_set_msg( _AM_WEBPHOTO_FAILEDREADING."<br />\n" ) ;
		} elseif ( $ret == _C_WEBPHOTO_IMAGE_SKIPPED ) {
			$this->_set_msg( _AM_WEBPHOTO_SKIPPED."<br />\n" ) ;
		} else {
			$this->_set_msg( _AM_WEBPHOTO_CREATEDTHUMBS."<br />\n" ) ;
		}

	} else {
		$this->_set_msg( _AM_WEBPHOTO_SKIPPED."<br />\n" ) ;
	}

	return true ;
}

function _check_exif( $photo_cont_exif )
{
	if (( $this->_post_exif == 2 ) || 
	   (( $this->_post_exif == 1 ) && empty( $photo_cont_exif ) )) {
		return true;
	}
	return false;
}

function _update_exif( $row )
{
	$file = XOOPS_ROOT_PATH . $row['photo_cont_path'] ;

	$exif_info = $this->_exif_class->read_file( $file );
	if ( !is_array($exif_info) ) {
		return $row ;
	}

	$datetime  = $this->exif_to_mysql_datetime( $exif_info );
	$equipment = $exif_info['equipment'] ;
	$exif      = $exif_info['all_data'] ;
	if ( $datetime ) {
		$row['photo_datetime'] = $datetime ;
	}
	if ( $equipment ) {
		$row['photo_equipment'] = $equipment ;
	}
	if ( $exif ) {
		$this->_set_msg( ' redo exif ' );
		$row['photo_cont_exif'] = $exif ;
	}

	$ret = $this->_photo_handler->update( $row );
	if ( !$ret ) {
		$this->set_error( $this->_photo_handler->get_errors() );
		return false ;
	}

	return $row ;
}

function _get_photo_size( $photo_cont_path, $photo_size )
{
	$cont_full_path  = XOOPS_ROOT_PATH . $photo_cont_path;
	list( $cont_width , $cont_height ) = getimagesize( $cont_full_path ) ;
	$this->_set_msg( $cont_width .' x '. $cont_height .' .. ' ) ;

	$photo_size['cont_width']  = $cont_width ;
	$photo_size['cont_height'] = $cont_height ;
	return $photo_size;

}

function _check_resize( $photo_size )
{
	if ( !$this->_post_resize ) {
		return false;
	}
	if  ( $photo_size['cont_width']  > $this->_cfg_width ) {
		return true;
	}
	if ( $photo_size['cont_height'] > $this->_cfg_width ) {
		return true;
	}
	return false;
}

function _check_photo_size( $photo_cont_width, $photo_cont_height, $photo_size )
{
	if ( $photo_cont_width != $photo_size['cont_width'] ) {
		return true;
	}
	if ( $photo_cont_height != $photo_size['cont_height'] ) {
		return true;
	}
	return false;
}

function _redo_photo_size( $photo_cont_path, $photo_cont_ext )
{
	$cont_full_path  = XOOPS_ROOT_PATH . $photo_cont_path;

	$tmp_full_path = $this->_TMP_DIR.'/'.uniqid('tmp_') ;
	$this->unlink_file( $tmp_full_path ) ;
	$this->rename_file( $cont_full_path , $tmp_full_path ) ;
	$this->_image_class->cmd_modify_photo( $tmp_full_path , $cont_full_path );
	$this->unlink_file( $tmp_full_path ) ;

	$this->_set_msg( _AM_WEBPHOTO_PHOTORESIZED." &nbsp; " );

	$photo_info = $this->_image_class->build_photo_info( $cont_full_path, $photo_cont_ext );

	$photo_size = array(
		'cont_size'     => $photo_info['width'] ,
		'cont_width'    => $photo_info['height'] ,
		'cont_height'   => $photo_info['size'] ,
		'middle_width'  => $photo_info['middle_width'] ,
		'middle_height' => $photo_info['middle_height'] ,
	);
		
	return $photo_size;
}

function _update_photo_size( $row, $photo_size )
{
	$row['photo_cont_width']    = $photo_size['cont_width'] ;
	$row['photo_cont_height']   = $photo_size['cont_height'];
	$row['photo_cont_size']     = $photo_size['cont_size'];
	$row['photo_middle_width']  = $photo_size['middle_width'];
	$row['photo_middle_height'] = $photo_size['middle_height'];

	$ret = $this->_photo_handler->update( $row );
	if ( !$ret ) {
		$this->set_error( $this->_photo_handler->get_errors() );
		return false ;
	}

	$this->_set_msg( _AM_WEBPHOTO_SIZEREPAIRED." &nbsp; "  );
	return $row ;
}

function _update_thumb( $row )
{
	extract( $row ) ;

	$thumb_full_path = XOOPS_ROOT_PATH . $photo_thumb_path;

// exist thumb
	if ( $this->check_file( $thumb_full_path ) ) {
		list( $thumb_w , $thumb_h ) = getimagesize( $thumb_full_path ) ;
		$this->_set_msg( $thumb_w .' x '. $thumb_h .' .. ' ) ;

		if ( $this->_post_forceredo ) {
			$retcode = $this->_create_update_thumb(
				$row_update, $photo_cont_path, $photo_id , $photo_cont_ext );

		} else {
			$retcode = _C_WEBPHOTO_IMAGE_SKIPPED ;
		}

// no thumb
	} else {
		if ( $this->_cfg_makethumb ) {
			$retcode = $this->_create_update_thumb(
				$row, $photo_cont_path , $photo_id , $photo_cont_ext );
		} else {
			$retcode = _C_WEBPHOTO_IMAGE_SKIPPED ;
		}
	}

	switch( $retcode ) 
	{
		case _C_WEBPHOTO_ERR_DB : 
			return false;
			break ;

		case _C_WEBPHOTO_IMAGE_READFAULT : 
			$this->_set_msg( _AM_WEBPHOTO_FAILEDREADING."<br />\n" ) ;
			break ;

		case _C_WEBPHOTO_IMAGE_CREATED : 
			$this->_set_msg( _AM_WEBPHOTO_CREATEDTHUMBS."<br />\n" ) ;
			break ;

		case _C_WEBPHOTO_IMAGE_COPIED : 
			$this->_set_msg( _AM_WEBPHOTO_BIGTHUMBS."<br />\n" ) ;
			break ;

		case _C_WEBPHOTO_IMAGE_SKIPPED : 
			$this->_set_msg( _AM_WEBPHOTO_SKIPPED."<br />\n" ) ;
			break ;

		default : 
			$this->_set_msg( 'unexpect return code '. $retocde ."<br />\n" ) ;
			break ;
	}

	return true;
}

function check_file( $file )
{
	if ( $file && file_exists($file) && is_file($file) && !is_dir($file) ) {
		return true;
	}
	return false;
}

function _create_update_thumb( $row, $src_path , $id , $ext )
{
// create thumb
	if ( $this->is_normal_ext( $ext ) ) {
		$ret1 = $this->_image_class->create_thumb_from_photo( 
			$id, $src_path, $ext );
		$thumb_info = $this->_image_class->get_thumb_info();

// thumb icon
	} else {
		$ret1 = $this->_image_class->create_thumb_icon( $id, $ext );
		$thumb_info = $this->_image_class->get_thumb_info();
	}

// update date
	$update_row = array_merge( $row, $thumb_info );

	$ret2 = $this->_photo_handler->update( $update_row );
	if ( !$ret2 ) {
		$this->set_error( $this->_photo_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB ;
	}

	return $ret1;
}

function _clear_msg( )
{
	$this->_msg_array = array();
}

function _set_msg( $msg )
{
	$this->_msg_array[] = $msg;
}

function _get_msg()
{
	return implode( $this->_msg_array, '' );
}

function _print_form( $param )
{
	$form =& webphoto_admin_redo_form::getInstance(
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$form->print_form_redothumbs( $param );
}

// --- class end ---
}

?>