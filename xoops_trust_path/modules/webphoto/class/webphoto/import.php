<?php
// $Id: import.php,v 1.1 2008/06/21 12:22:23 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

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

// post
	var $_post_op;
	var $_post_offset;
	var $_next;

	var $_cfg_makethumb;

	var $_myalbum_dirname;
	var $_myalbum_mid;
	var $_myalbum_photos_dir;
	var $_myalbum_thumbs_dir;

	var $_LIMIT = 100;

	var $_CONST_DEBUG_SQL;

	var $_CAT_MAIN_WIDTH  = _C_WEBPHOTO_CAT_MAIN_WIDTH_DEFAULT;
	var $_CAT_MAIN_HEIGHT = _C_WEBPHOTO_CAT_MAIN_HEIGHT_DEFAULT;
	var $_CAT_SUB_WIDTH   = _C_WEBPHOTO_CAT_SUB_WIDTH_DEFAULT;
	var $_CAT_SUB_HEIGHT  = _C_WEBPHOTO_CAT_SUB_HEIGHT_DEFAULT;

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

	$this->_ICON_EXT_DIR = $this->_MODULE_DIR .'/images/exts';
	$this->_ICON_EXT_URL = $this->_MODULE_URL .'/images/exts';

	$this->_cfg_makethumb = $this->get_config_by_name( 'makethumb' );
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

function build_photo_row_by_photo_info( $row, $info )
{
	return $this->_photo_handler->build_row_by_photo_info( $row, $info );
}

function build_photo_row_by_thumb_info( $row, $info )
{
	return $this->_photo_handler->build_row_by_thumb_info( $row, $info );
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

	$info = $this->_mime_class->add_mime_if_empty( $info );

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
function copy_photo_from_myalbum( $src_id, $dst_id, $ext )
{
	$thumb_name = '' ;
	$thumb_path = '' ;
	$thumb_ext  = '' ;
	$thumb_substitute = false;

	$src_name_ext = $src_id .'.'. $ext;
	$src_name_gif = $src_id .'.gif';

	$dst_name_ext = $this->_image_class->build_photo_name( $dst_id, $ext );
	$dst_name_gif = $this->_image_class->build_photo_name( $dst_id, 'gif');

	$src_full_photo     = $this->_myalbum_photos_dir .'/'. $src_name_ext;
	$src_full_thumb_ext = $this->_myalbum_thumbs_dir .'/'. $src_name_ext;
	$src_full_thumb_gif = $this->_myalbum_thumbs_dir .'/'. $src_name_gif;

	$dst_rel_photo     = $this->_PHOTOS_PATH .'/'. $dst_name_ext;
	$dst_rel_thumb_ext = $this->_THUMBS_PATH .'/'. $dst_name_ext;
	$dst_rel_thumb_gif = $this->_THUMBS_PATH .'/'. $dst_name_gif;
	$dst_full_photo    = XOOPS_ROOT_PATH . $dst_rel_photo;

	$this->copy_file_rel( $src_full_photo , $dst_rel_photo ) ;
	$photo_name = $dst_name_ext ;
	$photo_path = $dst_rel_photo ;
	$photo_ext  = $ext ;

	if ( file_exists( $src_full_thumb_ext ) ) {
		$this->copy_file_rel( $src_full_thumb_ext , $dst_rel_thumb_ext ) ;
		$thumb_name = $dst_name_ext ;
		$thumb_path = $dst_rel_thumb_ext ;
		$thumb_ext  = $ext ;

	} elseif ( file_exists( $src_full_thumb_gif ) ) {
		$this->copy_file_rel( $src_full_thumb_gif , $dst_rel_thumb_gif ) ;
		$thumb_name = $dst_name_gif ;
		$thumb_path = $dst_rel_thumb_gif ;
		$thumb_ext  = 'gif' ;

 // create thumb
	} elseif ( $this->_cfg_makethumb ) {
		$this->_image_class->create_thumb( $photo_path , $dst_id , $ext ) ;
		$image_thumb_info = $this->_image_class->get_thumb_info();
		$thumb_name = $image_thumb_info['name'] ;
		$thumb_path = $image_thumb_info['path'] ;
		$thumb_ext  = $image_thumb_info['ext'] ;

	} elseif ( $this->is_normal_ext($ext) ) {
		$thumb_name = $photo_name ;
		$thumb_path = $photo_path ;
		$thumb_ext  = $photo_ext ;
		$thumb_substitute = true;
	}

	$arr = array(
		'photo_name' => $photo_name ,
		'photo_path' => $photo_path ,
		'photo_ext'  => $photo_ext ,
		'thumb_name' => $thumb_name ,
		'thumb_path' => $thumb_path ,
		'thumb_ext'  => $thumb_ext ,
		'thumb_substitute' => $thumb_substitute ,
	);

	return $arr;
}

function copy_photo_from_webphoto( $dst_id, $webphoto_row )
{
	$thumb_name = '' ;
	$thumb_path = '' ;
	$thumb_ext  = '' ;
	$thumb_substitute = false;

	$src_photo_cont_path  = $webphoto_row['photo_cont_path'];
	$src_photo_cont_ext   = $webphoto_row['photo_cont_ext'];
	$src_photo_thumb_path = $webphoto_row['photo_thumb_path'];
	$src_photo_thumb_ext  = $webphoto_row['photo_thumb_ext'];

	$dst_photo_name = $this->_image_class->build_photo_name( $dst_id, $src_photo_cont_ext );
	$dst_thumb_name = $this->_image_class->build_photo_name( $dst_id, $src_photo_thumb_ext );

	$src_full_photo = XOOPS_ROOT_PATH . $src_photo_cont_path ;
	$src_full_thumb = XOOPS_ROOT_PATH . $src_photo_thumb_path ;

	$dst_rel_photo = $this->_PHOTOS_PATH .'/'. $dst_photo_name;
	$dst_rel_thumb = $this->_THUMBS_PATH .'/'. $dst_thumb_name;

	$dst_full_photo = XOOPS_ROOT_PATH . $dst_rel_photo;

	$this->copy_file_rel( $src_full_photo , $dst_rel_photo ) ;
	$photo_name = $dst_photo_name ;
	$photo_path = $dst_rel_photo ;
	$photo_ext  = $src_photo_cont_ext ;

	if ( file_exists( $src_full_thumb ) ) {
		$this->copy_file_rel( $src_full_thumb , $dst_rel_thumb ) ;
		$thumb_name = $dst_thumb_name ;
		$thumb_path = $dst_rel_thumb ;
		$thumb_ext  = $src_photo_thumb_ext ;

 // create thumb
	} elseif ( $this->_cfg_makethumb ) {
		$this->_image_class->create_thumb( $photo_path , $dst_id , $photo_ext ) ;
		$image_thumb_info = $this->_image_class->get_thumb_info();
		$thumb_name = $image_thumb_info['name'] ;
		$thumb_path = $image_thumb_info['path'] ;
		$thumb_ext  = $image_thumb_info['ext'] ;

	} elseif ( $this->is_normal_ext( $photo_ext ) ) {
		$thumb_name = $photo_name ;
		$thumb_path = $photo_path ;
		$thumb_ext  = $photo_ext ;
		$thumb_substitute = true;
	}

	$arr = array(
		'photo_name' => $photo_name ,
		'photo_path' => $photo_path ,
		'photo_ext'  => $photo_ext ,
		'thumb_name' => $thumb_name ,
		'thumb_path' => $thumb_path ,
		'thumb_ext'  => $thumb_ext ,
		'thumb_substitute' => $thumb_substitute ,
	);

	return $arr;
}

function copy_file_rel( $src_full, $dst_rel )
{
	return $this->copy_file( $src_full, XOOPS_ROOT_PATH.$dst_rel );
}

// --- class end ---
}

?>