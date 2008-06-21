<?php
// $Id: redothumbs.php,v 1.1 2008/06/21 12:22:22 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_redothumbs
//=========================================================
class webphoto_admin_redothumbs extends webphoto_base_this
{
	var $_image_class;
	var $_delete_class;

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
	);

	$start = $post_start;
	$record_counter = 0;

	$this->_check();

	if( $post_submit ) {
		$ret = $this->_submit( $param );
		if ( !is_array($ret) ) {
			$msg = 'DB Error';
			$msg .= '<br />'.$this->get_format_error();
			redirect_header( $this->_ADMIN_REDO_PHP, 5, $msg ) ;
			exit();
		}

		list( $result_str, $record_counter ) = $ret;
		$start = $post_start + $post_size ;
	}

// Render forms
	xoops_cp_header() ;
	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'REDOTHUMB' );

	$param['start']   = $start;
	$param['counter'] = $record_counter;

	$this->_print_form( $param );

	$this->_remove_tmp_files();

	if( isset( $result_str ) ) {
		echo "<br />\n" ;
		echo $result_str ;
	}

	xoops_cp_footer() ;
}

function _check()
{
	$cfg_makethumb   = $this->_config_class->get_by_name('makethumb');

// get flag of safe_mode
	$safe_mode_flag = ini_get( "safe_mode" ) ;

// check if the directories of thumbs and photos are same.
	if( $this->_THUMBS_DIR == $this->_PHOTOS_DIR ) {
		die( "The directory for thumbnails is same as for photos." ) ;
	}

// check or make thumbs_dir
	if( $cfg_makethumb && ! is_dir( $this->_THUMBS_DIR ) ) {
		if( $safe_mode_flag ) {
			$msg = 'At first create & chmod 777 "'. $this->_THUMBS_DIR .'" by ftp or shell.';
			redirect_header( $this->_ADMIN_INDEX_PHP, 5, $msg);
			exit() ;
		}

		$rs = mkdir( $this->_THUMBS_DIR , 0777 ) ;
		if( ! $rs ) {
			$msg = $this->_THUMBS_DIR.' is not a directory';
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
	$post_start     = $param['start'];
	$post_size      = $param['size'];
	$post_forceredo = $param['forceredo'];
	$post_removerec = $param['removerec'];
	$post_resize    = $param['resize'];

	$cfg_width     = $this->get_config_by_name('width');
	$cfg_height    = $this->get_config_by_name('height');
	$cfg_makethumb = $this->get_config_by_name('makethumb');

	$rows = $this->_photo_handler->get_rows_all_asc( $post_size, $post_start );

	$record_counter = 0 ;
	$msg = '';

	foreach ( $rows as $row )
	{
		$row_update = $row;

		extract( $row ) ;

		$cont_size     = $photo_cont_size;
		$cont_width    = $photo_cont_width;
		$cont_height   = $photo_cont_height;
		$middle_width  = $photo_middle_width;
		$middle_height = $photo_middle_height;

		$file_full_path  = '';
		$cont_full_path  = '';
		$thumb_full_path = '';

		if ( $photo_file_path ) {
			$file_full_path  = XOOPS_ROOT_PATH . $photo_file_path;
		}
		if ( $photo_cont_path ) {
			$cont_full_path  = XOOPS_ROOT_PATH . $photo_cont_path;
		}
		if ( $photo_thumb_path ) {
			$thumb_full_path = XOOPS_ROOT_PATH . $photo_thumb_path;
		}

		$record_counter ++ ;
		$msg .= ( $record_counter + $post_resize - 1 ) . ") " ;
		$msg .= sprintf( _AM_WEBPHOTO_FMT_CHECKING , $photo_id ) ;

	// Check if the main image exists
		if ( !$this->check_file( $file_full_path ) ||
		     !$this->check_file( $cont_full_path )) {
			$msg .= _AM_WEBPHOTO_PHOTONOTEXISTS." &nbsp; " ;

			if ( $post_removerec ) {
				$this->_delete_class->delete_photo( $photo_id );
				$msg .= _AM_WEBPHOTO_RECREMOVED."<br />\n" ;
			} else {
				$msg .= _AM_WEBPHOTO_SKIPPED."<br />\n" ;
			}
			continue ;
		}

	// Check file info
		if ( $photo_file_path && empty($photo_file_size) ) {

			$row_update['photo_file_size'] = filesize( $photo_file_path );

			$ret = $this->_photo_handler->update( $row_update );
			if ( !$ret ) {
				$this->set_error( $this->_photo_handler->get_errors() );
				return false ;
			}
			$msg .= " update file info &nbsp; " ;
		}

	// Check if the file is not image
		if ( ! $this->is_normal_ext( $photo_cont_ext ) ) {
			$msg .= ' not image ' ;
			if ( $post_forceredo || !$this->check_file( $thumb_full_path ) ) {
				$ret = $this->_create_update_thumb(
					$row_update, $photo_cont_path , $photo_id , $photo_cont_ext );
				if ( $ret == _C_WEBPHOTO_ERR_DB ) {
					return false;
				} elseif ( $ret == _C_WEBPHOTO_IMAGE_READ_FAULT ) {
					$msg .= _AM_WEBPHOTO_FAILEDREADING."<br />\n" ;
				} elseif ( $ret == _C_WEBPHOTO_IMAGE_SKIPPED ) {
					$msg .= _AM_WEBPHOTO_SKIPPED."<br />\n" ;
				} else {
					$msg .= _AM_WEBPHOTO_CREATEDTHUMBS."<br />\n" ;
				}
			} else {
				$msg .= _AM_WEBPHOTO_SKIPPED."<br />\n" ;
			}
			continue ;
		}

	// nomal image
		// Size of main photo
		list( $cont_width , $cont_height ) = getimagesize( $cont_full_path ) ;
		$msg .= $cont_width .' x '. $cont_height .' .. ' ;

		// Check and resize the main photo if necessary
		if ( $post_resize && ( $cont_width > $cfg_width || $cont_height > $cfg_width ) ) {

			$tmp_full_path = $this->_TMP_DIR.'/'.uniqid('tmp_') ;
			$this->unlink_file( $tmp_full_path ) ;
			$this->rename_file( $cont_full_path , $tmp_full_path ) ;
			$this->_image_class->cmd_modify_photo( $tmp_full_path , $cont_full_path );
			$this->unlink_file( $tmp_full_path ) ;

			$msg .= _AM_WEBPHOTO_PHOTORESIZED." &nbsp; " ;

			$photo_info = $this->_image_class->build_photo_info( $cont_full_path, $photo_cont_ext );
			$cont_width    = $photo_info['width'];
			$cont_height   = $photo_info['height'];
			$cont_size     = $photo_info['size'];
			$middle_width  = $photo_size['middle_width'];
			$middle_height = $photo_size['middle_height'];
		}

		// Check and repair record of the photo if necessary
		if ( $cont_width != $photo_cont_width || $cont_height != $photo_cont_height ) {

			$row_update['photo_cont_width']    = $cont_width;
			$row_update['photo_cont_height']   = $cont_height;
			$row_update['photo_cont_size']     = $cont_size;
			$row_update['photo_middle_width']  = $middle_width;
			$row_update['photo_middle_height'] = $middle_height;

			$ret = $this->_photo_handler->update( $row_update );
			if ( !$ret ) {
				$this->set_error( $this->_photo_handler->get_errors() );
				return false ;
			}

			$msg .= _AM_WEBPHOTO_SIZEREPAIRED." &nbsp; " ;
		}

// exist thumb
		if ( $this->check_file( $thumb_full_path ) ) {
			list( $thumb_w , $thumb_h ) = getimagesize( $thumb_full_path ) ;
			$msg .= $thumb_w .' x '. $thumb_h .' .. ' ;

			if ( $post_forceredo ) {
				$retcode = $this->_create_update_thumb(
					$row_update, $photo_cont_path, $photo_id , $photo_cont_ext );

			} else {
				$retcode = _C_WEBPHOTO_IMAGE_SKIPPED ;
			}

// no thumb
		} else {
			if ( $cfg_makethumb ) {
				$retcode = $this->_create_update_thumb(
					$row_update, $photo_cont_path , $photo_id , $photo_cont_ext );
			} else {
				$retcode = _C_WEBPHOTO_IMAGE_SKIPPED ;
			}
		}

		switch( $retcode ) 
		{
			case _C_WEBPHOTO_ERR_DB : 
				return false;
				break ;

			case _C_WEBPHOTO_IMAGE_READ_FAULT : 
				$msg .= _AM_WEBPHOTO_FAILEDREADING."<br />\n" ;
				break ;

			case _C_WEBPHOTO_IMAGE_CREATED : 
				$msg .= _AM_WEBPHOTO_CREATEDTHUMBS."<br />\n" ;
				break ;

			case _C_WEBPHOTO_IMAGE_COPIED : 
				$msg .= _AM_WEBPHOTO_BIGTHUMBS."<br />\n" ;
				break ;

			case _C_WEBPHOTO_IMAGE_SKIPPED : 
				$msg .= _AM_WEBPHOTO_SKIPPED."<br />\n" ;
				break ;

			default : 
				$msg .= 'unexpect return code '. $retocde ."<br />\n" ;
				break ;
		}
	}

	return array( $msg, $record_counter );
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
	$ret1 = $this->_image_class->create_thumb( $src_path , $id , $ext );
	if (( $ret1 == _C_WEBPHOTO_IMAGE_READ_FAULT )||
	    ( $ret1 == _C_WEBPHOTO_IMAGE_SKIPPED )) {
		return $ret1;
	}

	$image_info = $this->_image_class->get_thumb_info();
	if ( is_array($image_info) ) {
		$thumb_name = $image_info['name'] ;
		$thumb_path = $image_info['path'] ;
		$thumb_ext  = $image_info['ext'] ;
		$thumb_url  = XOOPS_URL       . $thumb_path ;
		$thumb_file = XOOPS_ROOT_PATH . $thumb_path ;

		$thumb_info = $this->_image_class->build_thumb_info( $thumb_path, $thumb_ext );

		$row['photo_thumb_url']    = $thumb_url;
		$row['photo_thumb_path']   = $thumb_path;
		$row['photo_thumb_name']   = $thumb_name;
		$row['photo_thumb_ext']    = $thumb_ext;
		$row['photo_thumb_mime']   = $thumb_info['mime'];
		$row['photo_thumb_medium'] = $thumb_info['medium'];
		$row['photo_thumb_size']   = $thumb_info['size'];
		$row['photo_thumb_width']  = $thumb_info['thumb_width'];
		$row['photo_thumb_height'] = $thumb_info['thumb_height'];
	}

	$ret2 = $this->_photo_handler->update( $row );
	if ( !$ret2 ) {
		$this->set_error( $this->_photo_handler->get_errors() );
		return _C_WEBPHOTO_ERR_DB ;
	}

	return $ret1;
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