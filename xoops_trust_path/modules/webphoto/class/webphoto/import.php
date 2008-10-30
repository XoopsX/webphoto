<?php
// $Id: import.php,v 1.7 2008/10/30 00:22:49 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-10-01 K.OHWADA
// use build_update_item_row()
// BUG : thum_param -> thumb_param
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// 2008-08-01 K.OHWADA
// used create_video_flash_thumb()
// 2008-07-01 K.OHWADA
// used webphoto_lib_exif webphoto_video
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_import
//=========================================================
class webphoto_import extends webphoto_base_this
{
	var $_vote_handler;
	var $_myalbum_handler;
	var $_xoops_comments_handler;
	var $_build_class;
	var $_photo_class;

// post
	var $_post_op;
	var $_post_offset;
	var $_next;

	var $_cfg_makethumb;
	var $_cfg_use_ffmpeg;

	var $_myalbum_dirname;
	var $_myalbum_mid;
	var $_myalbum_photos_dir;
	var $_myalbum_thumbs_dir;

	var $_video_param = null ;

	var $_FLAG_RESIZE = false;

	var $_LIMIT = 100;

	var $_CONST_DEBUG_SQL;

	var $_CAT_MAIN_WIDTH  = _C_WEBPHOTO_CAT_MAIN_WIDTH_DEFAULT;
	var $_CAT_MAIN_HEIGHT = _C_WEBPHOTO_CAT_MAIN_HEIGHT_DEFAULT;
	var $_CAT_SUB_WIDTH   = _C_WEBPHOTO_CAT_SUB_WIDTH_DEFAULT;
	var $_CAT_SUB_HEIGHT  = _C_WEBPHOTO_CAT_SUB_HEIGHT_DEFAULT;

	var $_EXT_GIF = 'gif';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_import( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$constpref = strtoupper( '_P_' . $dirname. '_' ) ;
	$CONST_DEBUG_SQL = $constpref.'DEBUG_SQL';

	$this->_cat_handler->set_debug_error( 1 );
	$this->_cat_handler->set_debug_sql_by_const_name( $CONST_DEBUG_SQL );

	$this->_item_handler->set_debug_error( 1 );
	$this->_item_handler->set_debug_sql_by_const_name( $CONST_DEBUG_SQL );

	$this->_vote_handler  =& webphoto_vote_handler::getInstance( $dirname );
	$this->_vote_handler->set_debug_error( 1 );
	$this->_vote_handler->set_debug_sql_by_const_name( $CONST_DEBUG_SQL );

	$this->_xoops_comments_handler =& webphoto_xoops_comments_handler::getInstance();
	$this->_xoops_comments_handler->set_debug_error( 1 );
	$this->_xoops_comments_handler->set_debug_sql_by_const_name( $CONST_DEBUG_SQL );

	$this->_myalbum_handler =& webphoto_myalbum_handler::getInstance();
	$this->_myalbum_handler->set_debug_error( 1 );
	$this->_myalbum_handler->set_debug_sql_by_const_name( $CONST_DEBUG_SQL );

	$this->_photo_class =& webphoto_photo_create::getInstance( $dirname , $trust_dirname );
	$this->_photo_class->set_msg_level( _C_WEBPHOTO_MSG_LEVEL_ADMIN );

	$this->_build_class =& webphoto_photo_build::getInstance( $dirname );

	$this->_ICON_EXT_DIR = $this->_MODULE_DIR .'/images/exts';
	$this->_ICON_EXT_URL = $this->_MODULE_URL .'/images/exts';

	$this->_cfg_makethumb  = $this->get_config_by_name( 'makethumb' );
	$this->_cfg_use_ffmpeg = $this->get_config_by_name( 'use_ffmpeg' );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_import( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// init
//---------------------------------------------------------
function init_myalbum( $dirname )
{
	$mid = $this->_myalbum_handler->init( $dirname );
	if ( !$mid ) {
		return false;
	}

	$this->_myalbum_dirname = $dirname;
	$this->_myalbum_mid     = $mid;
	list ( $this->_myalbum_photos_dir, $this->_myalbum_thumbs_dir )
		= $this->_myalbum_handler->get_photos_thumbs_dir();

	return $mid;
}

//---------------------------------------------------------
// POST
//---------------------------------------------------------
function get_post_op()
{
	$this->_post_op = $this->_post_class->get_post_get('op');
	return $this->_post_op;
}

function get_post_offset()
{
	$this->_post_offset = $this->_post_class->get_post_get('offset');
	$this->_next        = $this->_post_offset + $this->_LIMIT;
	return $this->_post_offset;
}

//---------------------------------------------------------
// category
//---------------------------------------------------------
function insert_category_from_myalbum( $cid, $myalbum_row )
{
	$param = $this->build_category_img_path( $myalbum_row['imgurl'] );

	$row = $this->_cat_handler->create( true );
	$row['cat_id']          = $cid;
	$row['cat_title']       = $myalbum_row['title'];
	$row['cat_pid']         = $myalbum_row['pid'];
	$row['cat_weight']      = $myalbum_row['weight'] + 1 ;
	$row['cat_depth']       = $myalbum_row['depth'];
	$row['cat_description'] = $myalbum_row['description'];
	$row['cat_allowed_ext'] = $myalbum_row['allowed_ext'];

	$row['cat_img_path']    = $param['img_path'] ;
	$row['cat_orig_width']  = $param['orig_width'] ;
	$row['cat_orig_height'] = $param['orig_height'] ;
	$row['cat_main_width']  = $param['main_width'] ;
	$row['cat_main_height'] = $param['main_height'] ;
	$row['cat_sub_width']   = $param['sub_width'] ;
	$row['cat_sub_height']  = $param['sub_height'] ;

	return $this->_cat_handler->insert( $row );
}

function build_category_img_path( $imgurl )
{
	$img_path    = '';
	$orig_width  = 0;
	$orig_height = 0;
	$main_width  = 0;
	$main_height = 0;
	$sub_width   = 0;
	$sub_height  = 0;

	if ( $imgurl ) {
		$tmp_path  = str_replace( XOOPS_URL, '', $imgurl );
		$full_path = XOOPS_ROOT_PATH . $tmp_path ;

// in this site
		if ( file_exists($full_path) ) { 
			$img_path = $tmp_path;

			$image_size = GetImageSize( $full_path ) ;
			if ( is_array($image_size) ) {
				$orig_width  = $image_size[0];
				$orig_height = $image_size[1];

				list( $main_width, $main_height ) 
					= $this->adjust_image_size(
						$orig_width, $orig_height, $this->_CAT_MAIN_WIDTH, $this->_CAT_MAIN_HEIGHT );

				list( $sub_width, $sub_height ) 
					= $this->adjust_image_size(
						$orig_width, $orig_height, $this->_CAT_SUB_WIDTH, $this->_CAT_SUB_HEIGHT );
			}

// in other site
		} else {
			$img_path = $imgurl;
		}
	}

	$arr = array(
		'img_path'    => $img_path ,
		'orig_width'  => $orig_width ,
		'orig_height' => $orig_height ,
		'main_width'  => $main_width ,
		'main_height' => $main_height ,
		'sub_width'   => $sub_width ,
		'sub_height'  => $sub_height ,
	);
	return $arr;
}

//---------------------------------------------------------
// photo
//---------------------------------------------------------
function add_photo_from_myalbum( $myalbum_id, $new_cid, $myalbum_row )
{

// --- insert item ---
	$item_row = $this->create_photo_row_from_myalbum( $myalbum_id, $new_cid, $myalbum_row );

	$newid = $this->_item_handler->insert( $item_row );
	if ( !$newid ) {
		echo ' db error ' ;
		$this->set_error( $this->_item_handler->get_errors() );
		return false;
	}

	$item_id             = $newid ;
	$item_row['item_id'] = $item_id;

	list( $src_id, $src_ext, $src_file )
		= $this->build_myalbum_filename( $myalbum_row );

	if ( ! $this->is_readable_file( $src_file ) ) {
		echo $this->highlight( ' fail to read file : '.$src_file ) ;
		return false ;
	}

// copy photo
	$file_params = $this->copy_photo_from_myalbum( $item_row, $myalbum_row );
	if ( !is_array($file_params) ) {
		return false ;
	}

	$file_ids = $this->_photo_class->insert_files_from_params(
		$item_id,  $file_params );

	$update_row = $this->_photo_class->build_update_item_row(
		$item_row, $file_ids );

	$ret = $this->_item_handler->update( $update_row );
	if ( !$ret ) {
		echo ' db error ' ;
		$this->set_error( $this->_item_handler->get_errors() );
		return false;
	}

	return $item_id;
}

function create_photo_row_from_myalbum( $photo_id, $cat_id, $myalbum_row )
{
	list( $src_id, $src_ext, $src_file )
		= $this->build_myalbum_filename( $myalbum_row );

	$row = $this->_item_handler->create();
	$row['item_id']            = $photo_id;
	$row['item_cat_id']        = $cat_id;
	$row['item_title']         = $myalbum_row['title'];
	$row['item_time_create']   = $myalbum_row['date'];
	$row['item_time_update']   = $myalbum_row['date'];
	$row['item_uid']           = $myalbum_row['submitter'];
	$row['item_status']        = $myalbum_row['status'];
	$row['item_hits']          = $myalbum_row['hits'];
	$row['item_rating']        = $myalbum_row['rating'];
	$row['item_votes']         = $myalbum_row['votes'];
	$row['item_comments']      = $myalbum_row['comments'];

	$row['item_description']
		= $this->get_myambum_description( $src_id );

	if ( $this->is_readable_file( $src_file ) ) {
		$row = array_merge(
			$row, $this->_photo_class->get_item_param_extention( $src_file, $src_ext ) );
		$this->_video_param = $this->_photo_class->get_video_param() ;
	}

	$row['item_search'] = $this->build_photo_search( $row );

	return $row;
}

function get_myambum_description( $id )
{
	$row = $this->_myalbum_handler->get_text_row_by_id( $id );
	if ( isset( $row['description'] ) ) {
		return  $row['description'];
	}
	return null;
}

function build_myalbum_filename( $myalbum_row )
{
	$src_id   = $myalbum_row['lid'];
	$src_ext  = $myalbum_row['ext'];
	$src_name = $src_id .'.'. $src_ext;
	$src_file = $this->_myalbum_photos_dir .'/'. $src_name ;

	return array( $src_id, $src_ext, $src_file );
}

function is_readable_file( $file )
{
	if ( is_readable($file) && filesize($file) ) {
		return true ;
	}
	return false ;
}

function build_photo_search( $row )
{
	return $this->_build_class->build_search( $row );
}

function copy_photo_from_myalbum( $item_row, $myalbum_row )
{
	$cont_param   = null ;
	$thumb_param  = null ;
	$middle_param = null ;
	$flash_param  = null ;
	$docomo_param = null ;

	$flag_video_thumb     = false;
	$video_thumb_tmp_file = null ;

	$item_id   = $item_row['item_id'] ;
	$item_kind = $item_row['item_kind'] ;

	list( $src_id, $src_ext, $src_photo_file )
		= $this->build_myalbum_filename( $myalbum_row );

	$src_name_ext       = $src_id .'.'. $src_ext;
	$src_name_gif       = $src_id .'.'. $this->_EXT_GIF ;
	$src_thumb_file_ext = $this->_myalbum_thumbs_dir .'/'. $src_name_ext;
	$src_thumb_file_gif = $this->_myalbum_thumbs_dir .'/'. $src_name_gif;

	$photo_ext  = $src_ext ;
	$photo_name = $this->_photo_class->build_photo_name( $item_id, $photo_ext );
	$photo_path = $this->_PHOTOS_PATH .'/'. $photo_name;
	$photo_file = XOOPS_ROOT_PATH . $photo_path;

	$thumb_name_ext = $this->_photo_class->build_photo_name( $item_id, $src_ext );
	$thumb_name_gif = $this->_photo_class->build_photo_name( $item_id, $this->_EXT_GIF);
	$thumb_path_ext = $this->_THUMBS_PATH .'/'. $thumb_name_ext;
	$thumb_path_gif = $this->_THUMBS_PATH .'/'. $thumb_name_gif;

	$middle_name_ext = $this->_photo_class->build_photo_name( $item_id, $src_ext );
	$middle_path_ext = $this->_THUMBS_PATH .'/'. $middle_name_ext;

	$param                     = array();
	$param['src_file']         = $src_photo_file ;
	$param['src_ext']          = $src_ext ;
	$param['src_kind']         = $item_kind ;
	$param['video_param']      = $this->_video_param ;
	$param['mode_video_thumb'] = _C_WEBPHOTO_VIDEO_THUMB_SINGLE ;

// --- create cont ---
// modify photo
	if ( $this->_FLAG_RESIZE && $this->is_image_kind( $item_kind ) ) {
		$ret1 = $this->_photo_class->cmd_modify_photo( $src_photo_file , $photo_file );
		if ( $ret1 == _C_WEBPHOTO_IMAGE_RESIZE ) {
			echo ' resize photo, ';
		}

// copy
	} else {
		$this->copy_file( $src_photo_file , $photo_file ) ;
	}

	$file_param = $this->_photo_class->build_file_param( $photo_path, $photo_name, $photo_ext ); 
	if ( !is_array($file_param) ) {
		echo $this->highlight( ' not create photo ' ) ;
		return null ;
	}

	$cont_param = $this->_photo_class->build_cont_param( $file_param, $param );

// --- create video thumb ---
	$thumb_src_file   = $photo_file;
	$thumb_src_ext    = $photo_ext;
	$flag_video_thumb = false ;
	$video_tmp_file   = null ;

	if ( $this->is_video_kind( $item_kind ) ) {
		$param_video_thumb = $this->_photo_class->create_video_thumb( $item_id, $param );
		if ( is_array($param_video_thumb) ) {
			$flag_video_thumb = $param_video_thumb['flag'];
			$thumb_src_file   = $param_video_thumb['file'];
			$thumb_src_ext    = $param_video_thumb['ext'];
			$video_tmp_file   = $thumb_src_file ;
		}
	}

// --- create thumb ---
// if exists thumb file
	if ( file_exists( $src_thumb_file_ext ) && !$flag_video_thumb ) {
		$this->copy_file_rel( $src_thumb_file_ext , $thumb_path_ext ) ;
		$thumb_param = $this->_photo_class->build_file_param(
			$thumb_path_ext, $thumb_name_ext, $src_ext, _C_WEBPHOTO_FILE_KIND_THUMB );

// if exists thumb icon 
	} elseif ( file_exists( $src_thumb_file_gif ) && !$flag_video_thumb ) {
		$this->copy_file_rel( $src_thumb_file_gif , $thumb_path_gif ) ;
		$thumb_param = $this->_photo_class->build_file_param(
			$thumb_path_gif, $thumb_name_gif, $this->_EXT_GIF, _C_WEBPHOTO_FILE_KIND_THUMB );

// if image file
	} elseif ( $this->_cfg_makethumb && 
	   ( $this->is_image_kind( $item_kind ) || $flag_video_thumb ) ) {

		$this->_photo_class->create_thumb_from_image_file( 
			$thumb_src_file, $item_id, $thumb_src_ext );
		$thumb_param = $this->_photo_class->get_thumb_param();
		if ( is_array($thumb_param) ) {
			echo ' create thumb, ' ;
		} else {
			echo $this->highlight( ' fail to create thumb, ' ) ;
		}
	}

// thumb icon
	if ( ! is_array($thumb_param) ) {
		$this->_photo_class->create_thumb_icon( $item_id, $photo_ext );
		$thumb_param = $this->_photo_class->get_thumb_param() ;
	}

// --- create middle ---
	if ( $this->_cfg_makethumb && 
	   ( $this->is_image_kind( $item_kind ) || $flag_video_thumb ) ) {

		$this->_photo_class->create_middle_from_image_file( 
			$thumb_src_file, $item_id, $thumb_src_ext );
		$middle_param = $this->_photo_class->get_middle_param();
		if ( is_array($middle_param) ) {
			echo ' create middle, ';
		} else {
			echo $this->highlight( ' fail to create middle, ' ) ;
		}

// if exists thumb file
	} elseif ( file_exists( $src_thumb_file_ext ) && !$flag_video_thumb ) {
		$this->copy_file_rel( $src_thumb_file_ext , $middle_path_ext ) ;
		$$middle_param = $this->_photo_class->build_file_param(
			$thumb_path_ext, $middle_name_ext, $src_ext, _C_WEBPHOTO_FILE_KIND_MIDDLE );
	}

// middle icon
	if ( ! is_array($middle_param) ) {
		$this->_photo_class->create_middle_icon( $item_id, $src_ext );
		$middle_param = $this->_photo_class->get_middle_param() ;
	}

// remove temp file
	if ( $video_thumb_tmp_file ) {
		$this->_utility_class->unlink_file( $video_thumb_tmp_file );
	}

// --- create video flash , video docomo---
	if ( $this->is_video_kind( $item_kind ) && is_array( $cont_param ) ) {
		$flash_param  = $this->_photo_class->create_video_flash_param(  $item_id, $param );
		$docomo_param = $this->_photo_class->create_video_docomo_param( $item_id, $cont_param );
	}

	$file_params = array(
		'cont'   => $cont_param ,
		'thumb'  => $thumb_param ,
		'middle' => $middle_param ,
		'flash'  => $flash_param ,
		'docomo' => $docomo_param ,
	);

	return $file_params ;
}

function copy_file_rel( $src_full, $dst_rel )
{
	return $this->copy_file( $src_full, XOOPS_ROOT_PATH.$dst_rel );
}

//---------------------------------------------------------
// vote
//---------------------------------------------------------
function insert_vote_from_myalbum( $vote_id, $photo_id, $myalbum_row )
{
	if ( !is_array($myalbum_row) || !count($myalbum_row) ) {
		return true;	//no action
	}

	$ratingtimestamp = $myalbum_row['ratingtimestamp'];

	$row = $this->_vote_handler->create();
	$row['vote_id']          = $vote_id;
	$row['vote_photo_id']    = $photo_id;
	$row['vote_time_create'] = $ratingtimestamp;
	$row['vote_time_update'] = $ratingtimestamp;
	$row['vote_uid']         = $myalbum_row['ratinguser'];
	$row['vote_rating']      = $myalbum_row['rating'];
	$row['vote_hostname']    = $myalbum_row['ratinghostname'];

	$this->_vote_handler->insert( $row );
}

//---------------------------------------------------------
// comment
//---------------------------------------------------------
function add_comments_from_src( $src_mid, $src_id, $dst_id )
{
	$rows = $this->_xoops_comments_handler->get_rows_by_modid_itemid( $src_mid, $src_id );
	$this->insert_comments_from_src( $dst_id, $rows );
}

function insert_comments_from_src( $itemid, $src_rows )
{
	if ( !is_array($src_rows) || !count($src_rows) ) {
		return true;	//no action
	}

	$com_id_arr = array();

	foreach ( $src_rows as $src_row )
	{
		$com_id      = $src_row['com_id'];
		$com_pid     = $src_row['com_pid'];
		$com_title_s = $this->sanitize( $src_row['com_title'] );

		echo "comment: $com_id $com_title_s <br />\n";

		$row = $src_row;
		$row['com_modid']  = $this->_MODULE_ID ;

		if ( $itemid ) {
			$row['com_itemid'] = $itemid ;
		}

		$newid = $this->_xoops_comments_handler->insert( $row );

		$com_id_new     = $newid;
		$com_rootid_new = $newid;
		$com_pid_new    = 0;

		if ( $com_pid ) {
			if ( isset( $com_id_arr[$com_pid] ) ){
				$com_rootid_new = $com_id_arr[$com_pid]['com_rootid_new'];
				$com_pid_new    = $com_id_arr[$com_pid]['com_id_new'];
			} else {
				echo $this->highlight( "pid convert error: $com_id" )."<br />\n";
			}
		}

		$this->_xoops_comments_handler->update_rootid_pid(
			$com_id_new, $com_rootid_new, $com_pid_new );

		$com_id_arr[$com_id]['com_id_new']     = $com_id_new;
		$com_id_arr[$com_id]['com_rootid_new'] = $com_rootid_new;
	}
}

//---------------------------------------------------------
// form
//---------------------------------------------------------
function print_import_count( $count )
{
	echo "<br />\n";
	echo "<b>";
	echo sprintf( _AM_WEBPHOTO_FMT_IMPORTSUCCESS , $count ) ;
	echo "</b><br />\n";
}

function print_finish()
{
	echo "<br /><hr />\n";
	echo "<h4>FINISHED</h4>\n";
	echo '<a href="index.php">GOTO Admin Menu</a>'."<br />\n";
}

// --- class end ---
}

?>