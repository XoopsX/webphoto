<?php
// $Id: import.php,v 1.1 2008/06/21 12:22:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_import
//=========================================================
class webphoto_admin_import extends webphoto_import
{
	var $_image_handler;
	var $_groupperm_class;
	var $_form_class;
	var $_mime_class;

	var $_image_cat_row = null;

	var $_webphoto_dirname ;
	var $_webphoto_mid ;
	var $_webphoto_photos_path ;
	var $_webphoto_thumbs_path ;
	var $_webphoto_cat_handler   ;
	var $_webphoto_photo_handler ;
	var $_webphoto_vote_handler  ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_import( $dirname , $trust_dirname )
{
	$this->webphoto_import( $dirname , $trust_dirname );

	$constpref = strtoupper( '_P_' . $dirname. '_' ) ;
	$CONST_DEBUG_SQL = $constpref.'DEBUG_SQL';

	$this->_image_handler =& webphoto_xoops_image_handler::getInstance();
	$this->_image_handler->set_debug_error( 1 );
	$this->_image_handler->set_debug_sql_by_const_name( $CONST_DEBUG_SQL );

	$this->_mime_class  =& webphoto_mime::getInstance( $dirname );

	$this->_groupperm_class =& webphoto_xoops_groupperm::getInstance();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_import( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	xoops_cp_header();

	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'IMPORT' );

	switch ( $this->_get_op() )
	{
		case 'image':
			if ( $this->check_token_with_xoops_error() ) {
				$this->_import_image();
			}
			break;

		case 'myalbum':
			if ( $this->check_token_with_xoops_error() ) {
				$this->_import_myalbum();
			}
			break;

		case 'webphoto':
			if ( $this->check_token_with_xoops_error() ) {
				$this->_import_webphoto();
			}
			break;

		case 'form':
		default:
			$this->_print_form();
			break;
	}

	xoops_cp_footer();
	exit();
}

function _get_op()
{
	$op        = $this->_post_class->get_post_text('op');
	$imgcat_id = $this->_post_class->get_post_int('imgcat_id');
	$cid       = $this->_post_class->get_post_int('cid');
	$cat_id    = $this->_post_class->get_post_int('cat_id');

	if ( ( $op == 'myalbum' ) && ( $cid > 0 ) ) {
		return 'myalbum';
	}

	if ( ( $op == 'webphoto' ) && ( $cat_id > 0 ) ) {
		return 'webphoto';
	}

// only when user has admin right of system 'imagemanager'
	elseif ( $this->_groupperm_class->has_system_image() &&
	     ( $op == 'image' ) && ( $imgcat_id > 0 ) ) {
		return 'image';
	}

	return '';
}

function _is_copy_comment()
{
	return $this->_post_class->get_post_int('copy_comment');
}

//---------------------------------------------------------
// image
//---------------------------------------------------------
function _import_image()
{
	$imgcat_id = $this->_post_class->get_post_int('imgcat_id');

	$new_cid = $this->_import_image_cat( $imgcat_id );
	if ( !$new_cid ) {
		return false;
	}

	$this->_import_image_photos( $imgcat_id, $new_cid );

	$this->print_finish();
}

function _import_image_cat( $src_cid )
{
	echo "<h4>category</h4>\n";

	$image_row = $this->_image_handler->get_category_row_by_id( $src_cid );
	if ( !is_array($image_row) || !count($image_row) ) {
		return false;
	}

// save row
	$this->_image_cat_row = $image_row;

	$title = $image_row['imgcat_name'];

	echo $src_cid.' : '.$this->sanitize($title)." <br />\n";

	$row = $this->_cat_handler->create();
	$row['cat_title']  = $title;
	$row['cat_weight'] = 1 ;

	return $this->_cat_handler->insert( $row );
}

function _import_image_photos( $src_cid, $new_cid )
{
	echo "<h4>photo</h4>\n";

// save row
	$image_cat_row = $this->_image_cat_row;
	$imgcat_storetype = $image_cat_row['imgcat_storetype'];

	$image_rows = $this->_image_handler->get_image_rows_by_catid( $src_cid );
	if ( !is_array($image_rows) || !count($image_rows) ) {
		return false;
	}

	$import_count = 0 ;
	foreach( $image_rows as $image_row )
	{
		extract( $image_row ) ;

		echo $image_id.' '.$this->sanitize($image_name)." <br />\n";

// insert
		$row = $this->_photo_handler->create();
		$row['photo_title']         = $image_nicename;
		$row['photo_time_create']   = $image_created;
		$row['photo_time_update']   = $image_created;
		$row['photo_cat_id']        = $new_cid;
		$row['photo_uid']           = $this->_xoops_uid;
		$row['photo_status']        = $image_display;

// at last
		$row['photo_search']        = $this->build_photo_search( $row );

		$newid = $this->_photo_handler->insert( $row );

		$ext      = $this->parse_ext( $image_name ) ;
		$src_file = XOOPS_UPLOAD_PATH . '/'. $image_name ;

		$dst_name = $this->_image_class->build_photo_name( $newid, $ext );
		$dst_path = $this->_PHOTOS_PATH .'/'. $dst_name ;
		$dst_file = XOOPS_ROOT_PATH . $dst_path ;

// image in db
		if ( $imgcat_storetype == 'db' ) {
			$body_row = $this->_image_handler->get_body_row_by_imageid($image_id);
			if ( isset( $body_row['image_body'] ) ) {
				$this->write_file( $dst_file, $body_row['image_body'], 'wb' );
			}

// image file
		} else {
			$this->copy_file( $src_file , $dst_file ) ;
		}

// create thumb
		$this->_image_class->create_thumb( $dst_path , $newid , $ext ) ;
		$image_thumb_info = $this->_image_class->get_thumb_info();

		$photo_param = array(
			'photo_name' => $dst_name ,
			'photo_path' => $dst_path ,
			'photo_ext'  => $ext ,
		);

		$thumb_param = array(
			'thumb_name' => $image_thumb_info['name'] ,
			'thumb_path' => $image_thumb_info['path'] ,
			'thumb_ext'  => $image_thumb_info['ext'] ,
			'thumb_substitute' => false ,
		);

		$photo_info = $this->build_photo_info( $photo_param );
		$thumb_info = $this->build_thumb_info( $thumb_param );

// update
		$row['photo_id'] = $newid;
		$row = $this->build_photo_row_by_photo_info( $row, $photo_info );
		$row = $this->build_photo_row_by_thumb_info( $row, $thumb_info );

		$this->_photo_handler->update( $row );

		$import_count ++ ;
	}

	$this->print_import_count( $import_count );
}

//---------------------------------------------------------
// myalbum
//---------------------------------------------------------
function _import_myalbum()
{
	$cid         = $this->_post_class->get_post_int('cid');
	$src_dirname = $this->_post_class->get_post_text('src_dirname');

	$ret = $this->init_myalbum( $src_dirname );
	if ( !$ret ) {
		$msg = $src_dirname." module is not installed \n";
		xoops_error( $msg );
		return false;
	}

	$new_cid = $this->_import_myalbum_cat( $cid );
	if ( !$new_cid ) {
		return false;
	}

	$this->_import_myalbum_photos( $cid, $new_cid );

	$this->print_finish();
}

function _import_myalbum_cat( $src_cid )
{
	echo "<h4>category</h4>\n";

	$myalbum_row = $this->_myalbum_handler->get_cat_row_by_id( $src_cid );

	$title = $myalbum_row['title'];

	echo $src_cid.' : '.$this->sanitize($title)." <br />\n";

	return $this->insert_category_from_myalbum( 0, $myalbum_row );
}

function _import_myalbum_photos( $src_cid, $new_cid )
{
	echo "<h4>photo</h4>\n";

	list ( $allowed_mime_types, $allowed_exts )
		= $this->_mime_class->get_my_allowed_mimes();

	$myalbum_rows = $this->_myalbum_handler->get_photos_rows_by_cid( $src_cid );

	$import_count = 0;

	foreach ( $myalbum_rows as $myalbum_row )
	{
		$lid   = $myalbum_row['lid'];
		$title = $myalbum_row['title'];
		$ext   = $myalbum_row['ext'];

		echo 'photo : '.$lid.' '.$this->sanitize($ext).' '.$this->sanitize($title)." <br />\n";

		if (  ! in_array( strtolower( $ext ) , $allowed_exts ) ) {
			echo " Skip : not allow ext <br />\n" ;
			continue;
		}

		$newid = $this->_add_photo_from_myalbum( $new_cid, $myalbum_row );
		$this->_add_votes_from_myalbum( $lid, $newid );

		// exec only moving mode
		if ( $this->_is_copy_comment() ) {
			$this->add_comments_from_src( $this->_myalbum_mid, $lid, $newid );
		}

		$import_count ++ ;
	}

	$this->print_import_count( $import_count );
}

function _add_photo_from_myalbum( $new_cid, $myalbum_row )
{
	$lid = $myalbum_row['lid'];
	$ext = $myalbum_row['ext'];

// insert
	$row = $this->create_photo_row_from_myalbum( 0, $new_cid, $myalbum_row );
	$row['photo_search'] = $this->build_photo_search( $row );

	$newid = $this->_photo_handler->insert( $row );

// copy photo
	$image_info = $this->copy_photo_from_myalbum( $lid, $newid, $ext );

// build image param
	$photo_info = $this->build_photo_info( $image_info );
	$thumb_info = $this->build_thumb_info( $image_info );

// update
	$row['photo_id'] = $newid;

	$row = $this->build_photo_row_by_photo_info( $row, $photo_info );
	$row = $this->build_photo_row_by_thumb_info( $row, $thumb_info );

	$this->_photo_handler->update( $row );

	return $newid;
}

function _add_votes_from_myalbum( $lid, $newid )
{
	$myalbum_rows = $this->_myalbum_handler->get_votedata_row_by_lid( $lid );
	if ( !is_array($myalbum_rows) || !count($myalbum_rows) ) {
		return true;	// no action
	}

	foreach ( $myalbum_rows as $myalbum_row )
	{
		$ratingid = $myalbum_row['ratingid'];
		$lid      = $myalbum_row['lid'];

		echo "vote : $ratingid $lid <br />\n";

		$this->insert_vote_from_myalbum( 0, $newid, $myalbum_row );
	}
}

//---------------------------------------------------------
// webphoto
//---------------------------------------------------------
function _import_webphoto()
{
	$cat_id      = $this->_post_class->get_post_int('cat_id');
	$src_dirname = $this->_post_class->get_post_text('src_dirname');

	$ret = $this->_init_webphoto( $src_dirname );
	if ( !$ret ) {
		$msg = $src_dirname." module is not installed \n";
		xoops_error( $msg );
		return false;
	}

	$new_cat_id = $this->_import_webphoto_cat( $cat_id );
	if ( !$new_cat_id ) {
		return false;
	}

	$this->_import_webphoto_photos( $cat_id, $new_cat_id );

	$this->print_finish();
}

function _init_webphoto( $src_dirname )
{
	$module_class =& webphoto_lib_xoops_module::getInstance();

	$mid = $module_class->get_mid_by_dirname( $src_dirname );
	if ( !$mid ) {
		return false;
	}

	$this->_webphoto_dirname = $src_dirname;
	$this->_webphoto_mid     = $mid;

	$this->_config_class->init( $src_dirname );

	$this->_webphoto_photos_path = $this->_config_class->get_path_by_name( 'photospath' );
	$this->_webphoto_thumbs_path = $this->_config_class->get_path_by_name( 'thumbspath' );

	$this->_webphoto_cat_handler   = new webphoto_cat_handler(   $src_dirname );
	$this->_webphoto_photo_handler = new webphoto_photo_handler( $src_dirname );
	$this->_webphoto_vote_handler  = new webphoto_vote_handler(  $src_dirname );

	return $mid;
}

function _import_webphoto_cat( $src_cid )
{
	echo "<h4>category</h4>\n";

	$webphoto_row = $this->_webphoto_cat_handler->get_row_by_id( $src_cid );

	$title_s = $this->sanitize( $webphoto_row['cat_title'] );

	echo " $src_cid : $title_s <br />\n";

	$row = $webphoto_row;
	$row['cat_id'] = 0;
	return $this->_cat_handler->insert( $row );
}

function _import_webphoto_photos( $src_cid, $new_cid )
{
	echo "<h4>photo</h4>\n";

	$webphoto_rows = $this->_webphoto_photo_handler->get_rows_by_catid( $src_cid );

	$import_count = 0;

	foreach ( $webphoto_rows as $webphoto_row )
	{
		$src_id  = $webphoto_row['photo_id'];
		$title_s = $this->sanitize( $webphoto_row['photo_title'] );

		echo "photo : $src_id $title_s <br />\n";

		$newid = $this->_add_photo_from_webphoto( $new_cid, $webphoto_row );
		$this->_add_votes_from_webphoto( $src_id, $newid );

		// exec only moving mode
		if ( $this->_is_copy_comment() ) {
			$this->add_comments_from_src( $this->_webphoto_mid, $src_id, $newid );
		}

		$import_count ++ ;
	}

	$this->print_import_count( $import_count );
}

function _add_photo_from_webphoto( $new_cid, $webphoto_row )
{
// insert
	$row = $webphoto_row;
	$row['photo_id'] = 0;
	$newid = $this->_photo_handler->insert( $row );

// copy photo
	$image_info = $this->copy_photo_from_webphoto( $newid, $webphoto_row );

// build image param
	$photo_info = $this->build_photo_info( $image_info );
	$thumb_info = $this->build_thumb_info( $image_info );

// update
	$row['photo_id'] = $newid;

	$row = $this->build_photo_row_by_photo_info( $row, $photo_info );
	$row = $this->build_photo_row_by_thumb_info( $row, $thumb_info );

	$this->_photo_handler->update( $row );

	return $newid;
}

function _add_votes_from_webphoto( $src_id, $newid )
{
	$webphoto_rows = $this->_webphoto_vote_handler->get_rows_by_photoid( $src_id );
	if ( !is_array($webphoto_rows) || !count($webphoto_rows) ) {
		return true;	// no action
	}

	foreach ( $webphoto_rows as $webphoto_row )
	{
		$vote_id  = $webphoto_row['vote_id'];
		$photo_id = $webphoto_row['vote_photo_id'];

		echo "vote : $vote_id $photo_id <br />\n";

// insert
		$row = $webphoto_row;
		$row['vote_id']       = 0;
		$row['vote_photo_id'] = $newid;
		$this->_vote_handler->insert( $row );
	}
}

//---------------------------------------------------------
// print form
//---------------------------------------------------------
function _print_form()
{
	$this->_form_class = webphoto_admin_import_form::getInstance(
		$this->_DIRNAME, $this->_TRUST_DIRNAME );

	$this->_print_myalbum_link();

// only when user has admin right of system 'imagemanager'
	if ( $this->_groupperm_class->has_system_image() ) {
		$this->_print_form_image();
	}

	$this->_print_form_myalbums();

	$this->_print_form_webphotos();
}

function _print_myalbum_link()
{
	$title = $this->get_admin_title( 'IMPORT_MYALBUM' );

	echo "<h4>". $title ."</h4>\n";

	echo $this->_form_class->build_div_tag();
	echo '<a href="'. $this->_MODULE_URL .'/admin/index.php?fct=import_myalbum">';
	echo $title ;
	echo "</a><br />\n";
	echo $this->_form_class->build_div_end();
}

function _print_form_image()
{
	echo "<h4>"._AM_WEBPHOTO_FMT_IMPORTFROMIMAGEMANAGER."</h4>\n";

	$cat_rows = $this->_image_handler->get_category_rows_with_image_count();
	$this->_form_class->print_form_image( $cat_rows );

}

function _print_form_myalbums()
{
	$module_array = $this->_myalbum_handler->get_myalbum_module_array();
	if ( !is_array($module_array) || !count($module_array) ) {
		return true;	// no acton
	}

	$myalbum_dirname = $module_array[0]['dirname'];
	include_once XOOPS_ROOT_PATH .'/modules/'. $myalbum_dirname .'/include/functions.php';

	foreach ( $module_array as $mod ) 
	{
		$dirname = $mod['dirname'];
		$number  = $mod['number'];
		$name    = $mod['name'];

		$selbox = $this->_myalbum_handler->build_cat_selbox( $number );

		echo "<h4>". sprintf(_AM_WEBPHOTO_FMT_IMPORTFROMMYALBUMP, $name )."</h4>\n";

		$this->_form_class->print_form_myalbum( $dirname, $selbox );
	}
}

function _print_form_webphotos()
{
	$param = array(
		'file'   => 'include/webphoto.php' ,
		'except' => $this->_DIRNAME ,
	);

	$module_class =& webphoto_xoops_module::getInstance();

	$module_array = $module_class->get_module_list( $param );
	if ( !is_array($module_array) || !count($module_array) ) {
		return true;	// no acton
	}

	$selbox_class =& webphoto_cat_selbox::getInstance();

	foreach ( $module_array as $module ) 
	{
		$dirname = $module->getVar('dirname');
		$name_s  = $module->getVar('name','s');

		$selbox_class->init( $dirname );
		$cat_selbox = $selbox_class->build_selbox();

		echo "<h4>". sprintf(_AM_WEBPHOTO_FMT_IMPORTFROM_WEBPHOTO, $name_s )."</h4>\n";

		$this->_form_class->print_form_webphoto( $dirname, $cat_selbox );
	}
}

// --- class end ---
}

?>