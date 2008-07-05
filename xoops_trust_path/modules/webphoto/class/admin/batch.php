<?php
// $Id: batch.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// used create_flash() create_single_thumb()
// xoops_error() -> build_error_msg()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_batch
//=========================================================
class webphoto_admin_batch extends webphoto_base_this
{
	var $_mime_class;
	var $_image_class;
	var $_build_class;
	var $_video_class;
	var $_exif_class;

	var $_post_catid;
	var $_post_desc;
	var $_post_uid;
	var $_time_update;

	var $_cfg_makethumb ;

	var $_ADMIN_BATCH_PHP;

	var $_TIME_SUCCESS  = 1;
	var $_TIME_FAIL     = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_batch( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_build_class  =& webphoto_photo_build::getInstance( $dirname );
	$this->_image_class  =& webphoto_image_create::getInstance( $dirname , $trust_dirname );
	$this->_mime_class   =& webphoto_mime::getInstance( $dirname );
	$this->_video_class  =& webphoto_video::getInstance( $dirname );
	$this->_exif_class   =& webphoto_lib_exif::getInstance();

	$this->_cfg_makethumb = $this->get_config_by_name( 'makethumb' );

	$this->_ADMIN_BATCH_PHP = $this->_MODULE_URL .'/admin/index.php?fct=batch';
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_batch( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$this->_check_cat();

	if ( !$this->_check_cat() ) {
		$msg  = '<a href="'. $this->_MODULE_URL.'/admin/index.php?fct=catmanager">';
		$msg .= _WEBPHOTO_ERR_MUSTADDCATFIRST ;
		$msg .= '</a>';
		xoops_cp_header();
		echo $this->build_admin_menu();
		echo $this->build_admin_title( 'BATCH' );
		echo $this->build_error_msg( $msg, '', false );
		xoops_cp_footer() ;
		exit();
	}

	$post_submit = $this->_post_class->get_post_text('submit');

	if ( $post_submit != '' ) {
		$this->_submit();
		exit();
	}

	xoops_cp_header();

	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'BATCH' );

	$this->_print_form();

	xoops_cp_footer() ;

}

function _check_cat()
{
// check Categories exist
	$count = $this->_cat_handler->get_count_all();
	if( $count > 0 ) {
		return true;
	}
	return false;
}

//---------------------------------------------------------
// submit
//---------------------------------------------------------
function _submit()
{
	$title = $this->get_admin_title( 'BATCH' );

	xoops_cp_header();
	echo $this->build_admin_bread_crumb( $title, $this->_ADMIN_BATCH_PHP );
	echo "<h3>". $title ."</h3>\n";

	$this->_exec_submit();

	if( $this->has_error() ) {
		echo $this->get_format_error( false, true ) ;
		echo "<br />\n" ;
	}

	echo "<br /><hr />\n";
	echo "<h4>". _AM_WEBPHOTO_FINISHED."</h4>\n";
	echo '<a href="index.php">GOTO Admin Menu</a>'."<br />\n";

	xoops_cp_footer() ;
}

function _exec_submit()
{
	// Check Directory
	$post_dir          = $this->_post_class->get_post_text( 'dir' ) ;
	$post_title        = $this->_post_class->get_post_text( 'title' ) ;
	$post_update       = $this->_post_class->get_post_time( 'update' ) ;
	$this->_post_catid = $this->_post_class->get_post_get_int('cat_id') ;
	$this->_post_desc  = $this->_post_class->get_post_text( 'desc' ) ;
	$this->_post_uid   = $this->_post_class->get_post_int( 'uid', $this->_xoops_uid ) ;

	if ( $post_update > 0 ) {
		$this->_time_update = $post_update;
	} else {
		$this->_time_update = time();
	}

	if ( !$this->check_token() ) {
		$this->set_error( 'Token Error' );
		$this->set_error( $this->get_token_errors() );
		return false ;
	}

	$dir = $post_dir;

	if ( empty( $dir ) || ! is_dir( $dir ) ) {
		$dir = $this->add_slash_to_head( $dir );
		$prefix = XOOPS_ROOT_PATH ;
		while( strlen( $prefix ) > 0 ) {
			if( is_dir( $prefix.$dir ) ) {
				$dir = $prefix.$dir ;
				break ;
			}
			$prefix = substr( $prefix , 0 , strrpos( $prefix , '/' ) ) ;
		}
		if( ! is_dir( $dir ) ) {
			$this->set_error( _AM_WEBPHOTO_MES_INVALIDDIRECTORY );
			$this->set_error( $post_dir );
			return false ;
		}
	}

	$dir = $this->strip_slash_from_tail( $dir );

	$dh = opendir( $dir ) ;
	if( $dh === false ) {
		$this->set_error( _AM_WEBPHOTO_MES_INVALIDDIRECTORY );
		$this->set_error( $post_dir );
		return false;
	}

	// get all file_names from the directory.
	$file_names = array() ;
	while( $file_name = readdir( $dh ) ) {
		$file_names[] = $file_name ;
	}
	sort( $file_names ) ;

	list ( $allowed_mime_types, $allowed_exts )
		= $this->_mime_class->get_my_allowed_mimes();

	$filecount = 1 ;
	foreach( $file_names as $file_name ) 
	{
		// Skip '.' , '..' and hidden file
		if ( substr( $file_name , 0 , 1 ) == '.' ) { continue ; }

		$ext  = $this->parse_ext( $file_name ) ;
		$node = substr( $file_name , 0 , - strlen( $ext ) - 1 ) ;
		$src_file = $dir.'/'.$node.'.'.$ext ;

		if ( ! is_readable( $src_file ) || ! in_array( strtolower( $ext ) , $allowed_exts ) ) {
			echo ' Skip : '. $this->sanitize($file_name) ."<br />\n" ;
			continue;
		}

		$title = empty( $post_title ) ? addslashes( $node ) : $post_title.' '.$filecount ;

		$this->_exec_each_file( $src_file, $ext, $title );

		$filecount ++ ;
	}

	closedir( $dh ) ;

	if ( $filecount <= 1 ) {
		$msg = $this->sanitize($post_dir) . ' : '. _AM_WEBPHOTO_MES_BATCHNONE ;
	} else {
		$msg = sprintf( _AM_WEBPHOTO_MES_BATCHDONE , $filecount - 1 ) ;
	}

	echo "<br />\n";
	echo "<b>". $msg ."</b><br />\n";

	return $this->return_code();
}

function _exec_each_file( $src_file, $photo_ext, $title )
{
	$thumb_info = null;
	$flag_video_thumb  = false;

// insert
	$row = $this->_photo_handler->create( true );
	$row['photo_title']       = $title;
	$row['photo_cat_id']      = $this->_post_catid;
	$row['photo_uid']         = $this->_post_uid;
	$row['photo_time_update'] = $this->_time_update;
	$row['photo_description'] = $this->_post_desc;
	$row['photo_status']      = 1;

// get exif date
	$exif_info = $this->_exif_class->read_file( $src_file );
	if ( is_array($exif_info) ) {
		$datetime = $this->exif_to_mysql_datetime( $exif_info );
		if ( $datetime ) {
			$row['photo_datetime'] = $datetime;
		}
		$row['photo_equipment'] = $exif_info['equipment'];
		$row['photo_cont_exif'] = $exif_info['all_data'];
	}

	$row['photo_search'] = $this->_build_class->build_search( $row );

// insert record
	$newid = $this->_photo_handler->insert( $row );
	if ( !$newid ) {
		echo " db error <br />\n" ;
		$this->set_error( $this->_photo_handler->get_errors() );
		return false;
	}

	$url = $this->_MODULE_URL .'/index.php/photo/'. $newid .'/';
	echo '- <a href="'. $url .'" target="_blank">'. $src_file .'</a> : ';

// create photo
	$ret1 = $this->_image_class->create_photo( $src_file , $newid );
	if ( $ret1 == _C_WEBPHOTO_IMAGE_RESIZE ) {
		echo ' resize photo, ';
	}

	$photo_info = $this->_image_class->get_photo_info(); 
	if ( !is_array($photo_info) ) {
		echo ' not create photo ' ;
		return false;
	}

	$photo_path = $photo_info['photo_cont_path'] ;
	$photo_name = $photo_info['photo_cont_name'] ;
	$photo_ext  = $photo_info['photo_cont_ext'] ;
	$photo_file = XOOPS_ROOT_PATH . $photo_path ;

	$thumb_src_path  = $photo_path;
	$thumb_src_ext   = $photo_ext;

	$photo_info = $this->_mime_class->add_mime_to_info_if_empty( $photo_info );

// if video
	if ( $this->_mime_class->is_video_ext( $photo_ext ) ) {
		$photo_info = $this->_video_class->add_duration_size_to_info( $photo_info );

// create flash
		$flash_name = $this->_image_class->build_photo_name( 
			$newid, $this->_video_class->get_flash_ext() );

		$ret1 = $this->_video_class->create_flash( $photo_file, $flash_name ) ;
		if ( $ret1 ) {
			echo ' create flash, ' ;
			$photo_info = array_merge( $photo_info, $this->_video_class->get_flash_info() );
		} else {
			echo ' fail to create flash, ' ;
		}

// create video thumb
		if ( $this->_cfg_makethumb ) {
			$video_thumb_path = $this->_video_class->create_single_thumb( $newid, $photo_file ) ;
			if ( $video_thumb_path ) {
				$flag_video_thumb = true;
				$thumb_src_path   = $video_thumb_path;
				$thumb_src_ext    = $this->_video_class->get_thumb_ext();
			}
		}

	}

	if ( $this->is_normal_ext( $photo_ext ) || $flag_video_thumb ) {

// create thumb
		if ( $this->_cfg_makethumb ) {
			echo ' create thumb ' ;
			$this->_image_class->create_thumb_from_photo( 
				$newid, $thumb_src_path, $thumb_src_ext );
			$thumb_info = $this->_image_class->get_thumb_info();

// substitute with photo image
		} else {
			$this->_image_class->create_thumb_substitute( $photo_path, $photo_ext );
			$thumb_info = $this->_image_class->get_thumb_info();
		}

// thumb icon
	} else {
		$this->_image_class->create_thumb_icon( $newid, $photo_ext );
		$thumb_info = $this->_image_class->get_thumb_info();
	}

	$photo_thumb_info 
		= $this->_image_class->merge_photo_thumb_info( $photo_info, $thumb_info );

// update date
	$row['photo_id'] = $newid ;
	$update_row = array_merge( $row, $photo_thumb_info );

// update record
	$ret2 = $this->_photo_handler->update( $update_row );
	if ( !$ret2 ) {
		echo " db error <br />\n" ;
		$this->set_error( $this->_photo_handler->get_errors() );
		return false;
	}

	echo _AM_WEBPHOTO_FINISHED."<br />\n" ;

	return true;
}

function exif_to_mysql_datetime( $exif )
{
	$datetime     = $exif['datetime'];
	$datetime_gnu = $exif['datetime_gnu'];

	if ( $datetime_gnu ) {
		return $datetime_gnu;
	}

	$time = $this->_utility_class->str_to_time( $datetime );
	if ( $time <= 0 ) { return false; }

	return $this->_utility_class->time_to_mysql_datetime( $time );
}

//---------------------------------------------------------
// print form
//---------------------------------------------------------
function _print_form()
{
	$form =& webphoto_admin_batch_form::getInstance(
		$this->_DIRNAME, $this->_TRUST_DIRNAME  );

	$form->print_form_batch( 
		$this->_cat_handler->build_selbox_catid( 0 ) );
}

// --- class end ---
}

?>