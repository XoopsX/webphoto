<?php
// $Id: import.php,v 1.3 2008/07/07 23:34:23 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
	var $_image_class;
	var $_build_class;
	var $_mime_class;
	var $_video_class;
	var $_exif_class;

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

	$this->_photo_handler->set_debug_error( 1 );
	$this->_photo_handler->set_debug_sql_by_const_name( $CONST_DEBUG_SQL );

	$this->_vote_handler  =& webphoto_vote_handler::getInstance( $dirname );
	$this->_vote_handler->set_debug_error( 1 );
	$this->_vote_handler->set_debug_sql_by_const_name( $CONST_DEBUG_SQL );

	$this->_xoops_comments_handler =& webphoto_xoops_comments_handler::getInstance();
	$this->_xoops_comments_handler->set_debug_error( 1 );
	$this->_xoops_comments_handler->set_debug_sql_by_const_name( $CONST_DEBUG_SQL );

	$this->_myalbum_handler =& webphoto_myalbum_handler::getInstance();
	$this->_myalbum_handler->set_debug_error( 1 );
	$this->_myalbum_handler->set_debug_sql_by_const_name( $CONST_DEBUG_SQL );

	$this->_image_class =& webphoto_image_create::getInstance( $dirname , $trust_dirname );
	$this->_build_class =& webphoto_photo_build::getInstance( $dirname );
	$this->_mime_class  =& webphoto_mime::getInstance( $dirname );
	$this->_video_class =& webphoto_video::getInstance( $dirname );
	$this->_exif_class  =& webphoto_lib_exif::getInstance();

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
function create_photo_row_from_myalbum( $photo_id, $cat_id, $myalbum_row )
{
	$row = $this->_photo_handler->create();
	$row['photo_id']            = $photo_id;
	$row['photo_cat_id']        = $cat_id;
	$row['photo_title']         = $myalbum_row['title'];
	$row['photo_time_create']   = $myalbum_row['date'];
	$row['photo_time_update']   = $myalbum_row['date'];
	$row['photo_uid']           = $myalbum_row['submitter'];
	$row['photo_status']        = $myalbum_row['status'];
	$row['photo_hits']          = $myalbum_row['hits'];
	$row['photo_rating']        = $myalbum_row['rating'];
	$row['photo_votes']         = $myalbum_row['votes'];
	$row['photo_comments']      = $myalbum_row['comments'];

	$row['photo_description']
		= $this->build_photo_description( $myalbum_row['lid'] );

	return $row;
}

function build_photo_description( $id )
{
	$row = $this->_myalbum_handler->get_text_row_by_id( $id );
	if ( isset( $row['description'] ) ) {
		return  $row['description'];
	}
	return null;
}

function build_photo_info( $info_in )
{
	$photo_name = $info_in['photo_name'] ;
	$photo_path = $info_in['photo_path'] ;
	$photo_ext  = $info_in['photo_ext'] ;

	$info = $this->_image_class->build_photo_info( $photo_path, $photo_ext );
	$info['url']  = XOOPS_URL.$photo_path;
	$info['name'] = $photo_name;

	$info = $this->_mime_class->add_mime_to_info_if_empty( $info );

	return $info;
}

function build_thumb_info( $info_in )
{
	$thumb_name       = $info_in['thumb_name'] ;
	$thumb_path       = $info_in['thumb_path'] ;
	$thumb_ext        = $info_in['thumb_ext'] ;
	$thumb_substitute = $info_in['thumb_substitute'] ;

	$info = $this->_image_class->build_thumb_info( $thumb_path, $thumb_ext );
	$info['url']  = XOOPS_URL.$thumb_path;
	$info['name'] = $thumb_name;

	if ( $thumb_substitute ) {
		$info['path'] = '';
		$info['name'] = '';
	}

	return $info;
}

function build_photo_search( $row )
{
	return $this->_build_class->build_search( $row );
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

//---------------------------------------------------------
// copy
//---------------------------------------------------------
function copy_photo_from_myalbum( $src_id, $photo_id, $src_ext )
{
	$base_info = null;
	$flag_video_thumb = false;

	$src_name_ext       = $src_id .'.'. $src_ext;
	$src_name_gif       = $src_id .'.'. $this->_EXT_GIF ;
	$src_photo_file     = $this->_myalbum_photos_dir .'/'. $src_name_ext;
	$src_thumb_file_ext = $this->_myalbum_thumbs_dir .'/'. $src_name_ext;
	$src_thumb_file_gif = $this->_myalbum_thumbs_dir .'/'. $src_name_gif;

	$photo_ext  = $src_ext ;
	$photo_name = $this->_image_class->build_photo_name( $photo_id, $photo_ext );
	$photo_path = $this->_PHOTOS_PATH .'/'. $photo_name;
	$photo_file = XOOPS_ROOT_PATH . $photo_path;

	$thumb_name_ext = $this->_image_class->build_photo_name( $photo_id, $src_ext );
	$thumb_name_gif = $this->_image_class->build_photo_name( $photo_id, $this->_EXT_GIF);
	$thumb_path_ext = $this->_THUMBS_PATH .'/'. $thumb_name_ext;
	$thumb_path_gif = $this->_THUMBS_PATH .'/'. $thumb_name_gif;

	if ( !is_readable($src_photo_file) || !filesize($src_photo_file) ) {
		echo $this->highlight( ' fail to read file : '.$src_photo_file ) ;
		return false;
	}

// exif
	if ( $this->is_normal_ext( $photo_ext ) ) {
		$base_info = $this->get_exif_info( $src_photo_file );
	}

// modify photo
	if ( $this->_FLAG_RESIZE && $this->is_normal_ext( $photo_ext ) ) {
		$ret1 = $this->_image_class->cmd_modify_photo( $src_photo_file , $photo_file );
		if ( $ret1 == _C_WEBPHOTO_IMAGE_RESIZE ) {
			echo ' resize photo, ';
		}

// copy
	} else {
		$this->copy_file( $src_photo_file , $photo_file ) ;
	}

	$photo_info = $this->_image_class->build_photo_full_info( $photo_path, $photo_name, $photo_ext ); 
	if ( !is_array($photo_info) ) {
		echo $this->highlight( ' not create photo ' ) ;
		return false;
	}

	$thumb_src_path  = $photo_path;
	$thumb_src_ext   = $photo_ext;

	$photo_info = $this->_mime_class->add_mime_to_info_if_empty( $photo_info );

// if video
	if ( $this->_mime_class->is_video_ext( $photo_ext ) && $this->_cfg_use_ffmpeg ) {
		$photo_info = $this->_video_class->add_duration_size_to_info( $photo_info );

		$flash_name = $this->_image_class->build_photo_name( 
			$photo_id, $this->_video_class->get_flash_ext() );

		$ret = $this->_video_class->create_flash( $photo_file, $flash_name ) ;
		if ( $ret == _C_WEBPHOTO_VIDEO_CREATED ) {
			echo ' create flash, ' ;
			$photo_info = array_merge( $photo_info, $this->_video_class->get_flash_info() );
		} elseif ( $ret == _C_WEBPHOTO_VIDEO_FAILED ) {
			echo $this->highlight( ' fail to create flash, ' ) ;
		}

// create video thumb
		if ( $this->_cfg_makethumb ) {
			$video_thumb_path = $this->_video_class->create_single_thumb( $photo_id, $photo_file ) ;
			if ( $video_thumb_path ) {
				$flag_video_thumb = true;
				$thumb_src_path   = $video_thumb_path;
				$thumb_src_ext    = $this->_video_class->get_thumb_ext();
			}
		}

	}

// if exists thumb file
	if ( file_exists( $src_thumb_file_ext ) && !$flag_video_thumb ) {
		$this->copy_file_rel( $src_thumb_file_ext , $thumb_path_ext ) ;
		$thumb_info = $this->_image_class->build_thumb_info_full(
			$thumb_path_ext, $thumb_name_ext, $src_ext );

// if exists thumb icon 
	} elseif ( file_exists( $src_thumb_file_gif ) && !$flag_video_thumb ) {
		$this->copy_file_rel( $src_thumb_file_gif , $thumb_path_gif ) ;
		$thumb_info = $this->_image_class->build_thumb_info_full(
			$thumb_path_gif, $thumb_name_gif, $this->_EXT_GIF );

// if image file
	} elseif ( $this->is_normal_ext( $photo_ext ) || $flag_video_thumb ) {

// create thumb
		if ( $this->_cfg_makethumb ) {
			$this->_image_class->create_thumb_from_photo( 
				$photo_id, $thumb_src_path, $thumb_src_ext );
			$thumb_info = $this->_image_class->get_thumb_info();
			if ( is_array($thumb_info) ) {
				echo ' create thumb, ' ;
			} else {
				echo $this->highlight( ' fail to create thumb, ' ) ;
			}

// substitute with photo image
		} else {
			$this->_image_class->create_thumb_substitute( $photo_path, $photo_ext );
			$thumb_info = $this->_image_class->get_thumb_info();
		}

// thumb icon
	} else {
		$this->_image_class->create_thumb_icon( $photo_id, $photo_ext );
		$thumb_info = $this->_image_class->get_thumb_info();
	}

	$photo_thumb_info
		= $this->_image_class->merge_photo_thumb_info( $photo_info, $thumb_info, $base_info );

	return $photo_thumb_info;
}

function get_exif_info( $file )
{
	$exif_info = $this->_exif_class->read_file( $file );
	if ( !is_array($exif_info) ) {
		return null;
	}

	$base_info = array();
	$datetime  = $this->exif_to_mysql_datetime( $exif_info );
	$equipment = $exif_info['equipment'] ;
	$exif      = $exif_info['all_data'] ;
	if ( $datetime ) {
		$base_info['photo_datetime'] = $datetime ;
	}
	if ( $equipment ) {
		$base_info['photo_equipment'] = $equipment ;
	}
	if ( $exif ) {
		echo ' get exif, ';
		$base_info['photo_cont_exif'] = $exif ;
	}

	return $base_info;
}

function copy_photo_from_webphoto( $photo_id, $webphoto_row )
{
	$src_photo_cont_path  = $webphoto_row['photo_cont_path'];
	$src_photo_cont_name  = $webphoto_row['photo_cont_name'];
	$src_photo_cont_ext   = $webphoto_row['photo_cont_ext'];
	$src_photo_thumb_path = $webphoto_row['photo_thumb_path'];
	$src_photo_thumb_name = $webphoto_row['photo_thumb_name'];
	$src_photo_thumb_ext  = $webphoto_row['photo_thumb_ext'];

	$src_photo_file = XOOPS_ROOT_PATH . $src_photo_cont_path ;
	$src_thumb_file = XOOPS_ROOT_PATH . $src_photo_thumb_path ;

	$photo_name = $this->_image_class->build_photo_name( $photo_id, $src_photo_cont_ext );
	$thumb_name = $this->_image_class->build_photo_name( $photo_id, $src_photo_thumb_ext );

	$photo_path = $this->_PHOTOS_PATH .'/'. $photo_name;
	$thumb_path = $this->_THUMBS_PATH .'/'. $thumb_name;

	$photo_ext  = $src_photo_cont_ext ;
	$photo_file = XOOPS_ROOT_PATH . $photo_path;

	$this->copy_file_rel( $src_photo_file , $photo_path ) ;
	$photo_info = array(
		'photo_file_url'      => $webphoto_row['photo_file_url'] ,
		'photo_file_path'     => $webphoto_row['photo_file_path'] ,
		'photo_file_name'     => $webphoto_row['photo_file_name'] ,
		'photo_file_ext'      => $webphoto_row['photo_file_ext'] ,
		'photo_file_mime'     => $webphoto_row['photo_file_mime'] ,
		'photo_file_medium'   => $webphoto_row['photo_file_medium'] ,
		'photo_file_size'     => $webphoto_row['photo_file_size'] ,
		'photo_cont_url'      => $webphoto_row['photo_cont_url'] ,
		'photo_cont_path'     => $webphoto_row['photo_cont_path'] ,
		'photo_cont_name'     => $webphoto_row['photo_cont_name'] ,
		'photo_cont_ext'      => $webphoto_row['photo_cont_ext'] ,
		'photo_cont_mime'     => $webphoto_row['photo_cont_mime'] ,
		'photo_cont_medium'   => $webphoto_row['photo_cont_medium'] ,
		'photo_cont_size'     => $webphoto_row['photo_cont_size'] ,
		'photo_cont_width'    => $webphoto_row['photo_cont_width'] ,
		'photo_cont_height'   => $webphoto_row['photo_cont_height'] ,
		'photo_cont_duration' => $webphoto_row['photo_cont_duration'] ,
		'photo_middle_width'  => $webphoto_row['photo_middle_width'] ,
		'photo_middle_height' => $webphoto_row['photo_middle_height'] ,
	);

	if ( file_exists( $src_thumb_file ) ) {
		$this->copy_file_rel( $src_thumb_file , $thumb_path ) ;
		$thumb_info = array(
			'photo_thumb_url'     => $webphoto_row['photo_thumb_url'] ,
			'photo_thumb_path'    => $webphoto_row['photo_thumb_path'] ,
			'photo_thumb_name'    => $webphoto_row['photo_thumb_name'] ,
			'photo_thumb_ext'     => $webphoto_row['photo_thumb_ext'] ,
			'photo_thumb_mime'    => $webphoto_row['photo_thumb_mime'] ,
			'photo_thumb_medium'  => $webphoto_row['photo_thumb_medium'] ,
			'photo_thumb_size'    => $webphoto_row['photo_thumb_size'] ,
			'photo_thumb_width'   => $webphoto_row['photo_thumb_width'] ,
			'photo_thumb_height'  => $webphoto_row['photo_thumb_height'] ,
		);

// create thumb
	} elseif ( $this->_cfg_makethumb ) {
		echo ' create thumb ' ;
		$this->_image_class->create_thumb_from_photo( $photo_id, $photo_path, $photo_ext ) ;
		$thumb_info = $this->_image_class->get_thumb_info();

// substitute with photo image
	} elseif ( $this->is_normal_ext( $photo_ext ) ) {
		$this->_image_class->create_thumb_substitute( $photo_path, $photo_ext );
		$thumb_info = $this->_image_class->get_thumb_info();
	}

	$photo_thumb_info
		= $this->_image_class->merge_photo_thumb_info( $photo_info, $thumb_info );

	return $photo_thumb_info;
}

function copy_file_rel( $src_full, $dst_rel )
{
	return $this->copy_file( $src_full, XOOPS_ROOT_PATH.$dst_rel );
}

// --- class end ---
}

?>