<?php
// $Id: import.php,v 1.10 2011/06/05 07:23:40 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-06-04 K.OHWADA
// Fatal error: Class 'webphoto_item_create_class' not found
// 2010-11-11 K.OHWADA
// file_id_to_item_name()
// 2010-03-18 K.OHWADA
// format_and_insert_item()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_item_handler
// get_ini()
// 2009-01-10 K.OHWADA
// webphoto_import -> webphoto_edit_import
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// 2008-08-01 K.OHWADA
// use create_from_file()
// 2008-07-01 K.OHWADA
// added _import_image_read_src() _import_image_each_photo()
// xoops_error() -> build_error_msg()
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_import
//=========================================================
class webphoto_admin_import extends webphoto_edit_import
{
	var $_image_handler;
	var $_groupperm_class;
	var $_form_class;

	var $_image_cat_row = null;

	var $_webphoto_dirname ;
	var $_webphoto_mid ;
	var $_webphoto_photos_path ;
	var $_webphoto_thumbs_path ;
	var $_webphoto_cat_handler   ;
	var $_webphoto_item_create_class ;
	var $_webphoto_file_handler ;
	var $_webphoto_vote_handler  ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_import( $dirname , $trust_dirname )
{
	$this->webphoto_edit_import( $dirname , $trust_dirname );

	$this->_groupperm_class =& webphoto_xoops_groupperm::getInstance();

	$this->_image_handler =& webphoto_xoops_image_handler::getInstance();
	$this->_image_handler->set_debug_error( 1 );

	$val = $this->get_ini( _C_WEBPHOTO_NAME_DEBUG_SQL );
	if ( $val ) {
		$this->_image_handler->set_debug_sql( $val );
	}
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
			if ( $this->check_token_with_print_error() ) {
				$this->_import_image();
			}
			break;

		case 'myalbum':
			if ( $this->check_token_with_print_error() ) {
				$this->_import_myalbum();
			}
			break;

		case 'webphoto':
			if ( $this->check_token_with_print_error() ) {
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

	$item_row = $this->_item_create_class->create();
	$item_row['item_cat_id'] = $new_cid ;
	$item_row['item_uid']    = $this->_xoops_uid ;

	$import_count = 0 ;
	foreach( $image_rows as $image_row )
	{
		$image_id   = $image_row['image_id'];
		$image_name = $image_row['image_name'];
		$tmp_file   = $this->_TMP_DIR .'/'. $image_name ;

		echo $image_id.' '.$this->sanitize($image_name).' -> ';

		$ret = $this->_import_image_read_src( $image_row, $tmp_file, $imgcat_storetype );
		if ( !$ret ) {
			echo "<br />\n" ;
			continue;
		}

		$this->_import_image_each_photo( $item_row, $image_row, $tmp_file );
		echo "<br />\n" ;

		$import_count ++ ;
	}

	$this->print_import_count( $import_count );
}

function _import_image_read_src( $image_row, $tmp_file, $imgcat_storetype )
{
	$image_id   = $image_row['image_id'];
	$image_name = $image_row['image_name'];
	$src_file = XOOPS_UPLOAD_PATH . '/'. $image_name ;

// image in db
	if ( $imgcat_storetype == 'db' ) {
		$body_row = $this->_image_handler->get_body_row_by_imageid($image_id);
		if ( isset( $body_row['image_body'] ) ) {
			$this->_utility_class->write_file( $tmp_file, $body_row['image_body'], 'wb' );
		}
		if ( !is_readable($tmp_file) || !filesize($tmp_file) ) {
			echo $this->highlight( ' fail to read file in DB ' ) ;
			return false;
		}

// image in file
	} else {
		if ( !is_readable($src_file) || !filesize($src_file) ) {
			echo $this->highlight( ' fail to read file : '.$src_file ) ;
			return false;
		}
		$this->copy_file( $src_file , $tmp_file ) ;
	}

	return true;
}

function _import_image_each_photo( $item_row, $image_row, $tmp_file )
{
	$created = $image_row['image_created'] ;

	$item_row['item_title']       = $image_row['image_nicename'] ;
	$item_row['item_status']      = $image_row['image_display'] ;
	$item_row['item_time_create'] = $created ;
	$item_row['item_time_update'] = $created ;

	$param = array(
		'src_file' => $tmp_file ,
	);

	$this->_factory_create_class->create_item_from_param( $item_row, $param );
	echo $this->_factory_create_class->get_main_msg();

// remove tmp file
	$this->unlink_file( $tmp_file );

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
		echo $this->build_error_msg( $msg );
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

	$myalbum_rows = $this->_myalbum_handler->get_photos_rows_by_cid( $src_cid );
	$import_count = 0;

	foreach ( $myalbum_rows as $myalbum_row )
	{
		$lid   = $myalbum_row['lid'];
		$title = $myalbum_row['title'];
		$ext   = $myalbum_row['ext'];
		$file  = $this->_myalbum_photos_dir .'/'. $lid .'.'. $ext ;

		echo 'photo : '.$lid.' '.$this->sanitize($ext).' '.$this->sanitize($title).' -> ' ;

		if ( ! $this->is_my_allow_ext( $ext ) ) {
			echo " <b>Skip : not allow ext</b> <br />\n" ;
			continue;
		}

		if ( !is_readable($file) || !filesize($file) ) {
			echo $this->highlight( ' fail to read file : '.$file ) ."<br />\n" ;
			continue;
		}

		$newid = $this->add_photo_from_myalbum( 0, $new_cid, $myalbum_row );
		if ( !$newid ) {
			echo "<br />\n";
			continue;
		}

		echo "<br />\n";

		$this->_add_votes_from_myalbum( $lid, $newid );

		// exec only moving mode
		if ( $this->_is_copy_comment() ) {
			$this->add_comments_from_src( $this->_myalbum_mid, $lid, $newid );
		}

		$import_count ++ ;
	}

	$this->print_import_count( $import_count );
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
		echo $this->build_error_msg( $msg );
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
	$module_class =& webphoto_xoops_module::getInstance();
	$config_class =& webphoto_inc_config::getSingleton( $src_dirname );

	$mid = $module_class->get_mid_by_dirname( $src_dirname );
	if ( !$mid ) {
		return false;
	}

	$this->_webphoto_dirname = $src_dirname;
	$this->_webphoto_mid     = $mid;

	$this->_webphoto_photos_path = $config_class->get_by_name( 'photospath' );
	$this->_webphoto_thumbs_path = $config_class->get_by_name( 'thumbspath' );

// Fatal error: Class 'webphoto_item_create_class' not found
	$this->_webphoto_item_create_class = new webphoto_edit_item_create( $src_dirname, $this->_TRUST_DIRNAME );

	$this->_webphoto_cat_handler  = new webphoto_cat_handler(  $src_dirname, $this->_TRUST_DIRNAME );
	$this->_webphoto_file_handler = new webphoto_file_handler( $src_dirname, $this->_TRUST_DIRNAME );
	$this->_webphoto_vote_handler = new webphoto_vote_handler( $src_dirname, $this->_TRUST_DIRNAME );

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

	$webphoto_item_rows = $this->_webphoto_item_create_class->get_rows_by_catid( $src_cid );

	$import_count = 0;

	foreach ( $webphoto_item_rows as $webphoto_item_row )
	{
		$src_id  = $webphoto_item_row['item_id'];
		$title_s = $this->sanitize( $webphoto_item_row['item_title'] );

		echo "photo : $src_id $title_s -> ";

		$newid = $this->_add_photo_from_webphoto( $new_cid, $webphoto_item_row );
		echo "<br />\n";

		$this->_add_votes_from_webphoto( $src_id, $newid );

		// exec only moving mode
		if ( $this->_is_copy_comment() ) {
			$this->add_comments_from_src( $this->_webphoto_mid, $src_id, $newid );
		}

		$import_count ++ ;
	}

	$this->print_import_count( $import_count );
}

function _add_photo_from_webphoto( $new_cid, $webphoto_item_row )
{
// insert
	$item_row = $webphoto_item_row;
	$item_row['item_id']     = 0 ;
	$item_row['item_cat_id'] = $new_cid ;

	$newid = $this->format_and_insert_item( $item_row );
	if ( !$newid ) {
		echo ' db error ' ;
		return false;
	}

	$item_id             = $newid ;
	$item_row['item_id'] = $item_id;

	echo $this->_factory_create_class->build_uri_photo_id( $item_id );

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_ITEM_FILE_ID; $i++ ) 
	{
		$name = $this->_item_handler->file_id_to_item_name( $i ) ;
		$item_row[ $name ] = 0 ;
		$webphoto_file_id = $webphoto_item_row[ $name ];
		if ( $webphoto_file_id > 0 ) {
			$file_newid = $this->_add_file_from_webphoto( $item_id, $webphoto_file_id ) ;
			if ( $file_newid > 0 ) {
				$item_row[ $name ] = $file_newid ;
			}
		}
	}

	$ret = $this->format_and_update_item( $item_row );
	if ( !$ret ) {
		echo ' db error ' ;
		return false;
	}

	return $item_id ;
}

function _add_file_from_webphoto( $item_id, $webphoto_file_id )
{
	$file_row = $this->_webphoto_file_handler->get_row_by_id( $webphoto_file_id );
	if ( !is_array($file_row) ){
		echo ' no src file record ' ;
		return false;
	}

	$file_row['file_id']      = 0 ;
	$file_row['file_item_id'] = $item_id ;

	$newid = $this->_file_handler->insert( $file_row );
	if ( !$newid ) {
		echo ' db error ' ;
		$this->set_error( $this->_file_handler->get_errors() );
		return false;
	}

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

		$selbox_class->init( $dirname, $this->_TRUST_DIRNAME );
		$cat_selbox = $selbox_class->build_selbox();

		echo "<h4>". sprintf(_AM_WEBPHOTO_FMT_IMPORTFROM_WEBPHOTO, $name_s )."</h4>\n";

		$this->_form_class->print_form_webphoto( $dirname, $cat_selbox );
	}
}

// --- class end ---
}

?>