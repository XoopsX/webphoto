<?php
// $Id: export.php,v 1.6 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// get_file_extend_row_by_kind()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_cat_selbox
// get_ini()
// 2008-10-01 K.OHWADA
// remove an unnecessary title
// 2008-08-24 K.OHWADA
// photo_handler -> item_handler
// 2008-07-01 K.OHWADA
// xoops_error() -> build_error_msg()
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_export
//=========================================================
class webphoto_admin_export extends webphoto_base_this
{
	var $_groupperm_class;
	var $_image_handler;
	var $_form_class;

	var $_src_catid;
	var $_img_catid;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_export( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

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
		$instance = new webphoto_admin_export( $dirname , $trust_dirname );
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
	echo $this->build_admin_title( 'EXPORT' );

	switch ( $this->_get_op() )
	{
		case 'image':
			if ( $this->check_token_with_print_error() ) {
				$this->_export_image();
			}
			break;

		case 'myalbum':
			if ( $this->check_token_with_print_error() ) {
				$this->_export_myalbum();
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
	$op               = $this->_post_class->get_post_text('op');
	$this->_src_catid = $this->_post_class->get_post_int('cat_id');
	$this->_img_catid = $this->_post_class->get_post_int('imgcat_id');

	if ( ( $op == 'myalbum' ) && ( $this->_src_catid > 0 ) ) {
		return 'myalbum';
	}

// only when user has admin right of system 'imagemanager'
	elseif ( $this->_groupperm_class->has_system_image() &&
	     ( $op == 'image' ) && ( $this->_src_catid > 0 ) && ( $this->_img_catid > 0 ) ) {
		return 'image';
	}

	return '';
}

//---------------------------------------------------------
// image
//---------------------------------------------------------
function _export_image()
{
	$use_thumb = $this->_post_class->get_post_int( 'use_thumb' ) ;

	$cat_row = $this->_image_handler->get_category_row_by_id( $this->_img_catid );
	if ( !is_array($cat_row) || !count($cat_row) ) {
		echo 'Invalid imgcat_id.';
		return false;
	}

	$imgcat_storetype = $cat_row['imgcat_storetype'];

	$item_rows = $this->_item_handler->get_rows_by_catid( $this->_src_catid );
	if ( !is_array($item_rows) || !count($item_rows) ) {
		echo 'no photo image';
		return false;
	}

	$export_count = 0 ;

	foreach( $item_rows as $item_row )
	{
		$item_id          = $item_row['item_id'];
		$item_title       = $item_row['item_title'];
		$item_kind        = $item_row['item_kind'];
		$item_status      = $item_row['item_status'];
		$item_time_update = $item_row['item_time_update'];

		echo $item_id.' '.$this->sanitize($item_title).' : ';

		if ( ! $this->is_image_kind( $item_kind ) ) {
			echo " skip non-image <br />\n";
			continue;
		}

		if ( $use_thumb ) {
			$file_row = $this->get_file_extend_row_by_kind( 
				$item_row, _C_WEBPHOTO_FILE_KIND_THUMB );
			if ( !is_array($file_row) ) {
				echo " cannot get thumb row <br />\n";
				continue;
			}

		} else {
			$file_row = $this->get_file_extend_row_by_kind( 
				$item_row, _C_WEBPHOTO_FILE_KIND_CONT );
			if ( !is_array($file_row) ) {
				echo " cannot get cont row <br />\n";
				continue;
			}
		}

		$src_file = $file_row['full_path'];
		$ext      = $file_row['file_ext'] ;
		$mime     = $file_row['file_mime'] ;

		$image_name = uniqid( 'img' ) .'.'. $ext ;
		$dst_file   = XOOPS_UPLOAD_PATH . '/' . $image_name ;

// image in db
		if ( $imgcat_storetype == 'db' ) {
			$body = $this->read_file( $src_file, 'rb' );
			if ( !$body ) {
				echo 'failed to read file : '.$src_file."<br />\n";
				continue ; 
			}

// image file
		} else {
			echo $src_file."<br />\n -> ".$dst_file.' ';
			$ret = $this->copy_file( $src_file , $dst_file );
			if ( !$ret ) {
				echo "failed to copy <br />\n";
				continue ;
			}
		}

		// insert into image table
		$image_row = array(
			'image_name'     => $image_name ,
			'image_nicename' => $item_title ,
			'image_created'  => $item_time_update ,
			'image_mimetype' => $mime ,
			'image_display'  => $item_status ? 1 : 0 ,
			'image_weight'   => 0 ,
			'imgcat_id'      => $this->_img_catid ,
		);

		$newid = $this->_image_handler->insert_image( $image_row );
		if ( $newid ) {
			echo " Success <br />\n";

// image in db
			if ( $imgcat_storetype == 'db' ) {
				$body_row = array(
					'image_id'   => $newid ,
					'image_body' => $body ,
				);
				$this->_image_handler->insert_body( $body_row );
			}

		} else {
			echo " Failed <br />\n";
		}

		$export_count ++ ;
	}

	$this->_print_export_count( $export_count );
	$this->_print_finish();
}

//---------------------------------------------------------
// print form
//---------------------------------------------------------
function _print_form()
{
	$this->_form_class = webphoto_admin_export_form::getInstance(
		$this->_DIRNAME, $this->_TRUST_DIRNAME );

// only when user has admin right of system 'imagemanager'
	if ( $this->_groupperm_class->has_system_image() ) {
		$this->_print_form_image();
	} else {
		echo $this->build_error_msg( 'you have no permission' );
	}
}

function _print_form_image()
{
	echo "<h4>"._AM_WEBPHOTO_FMT_EXPORTTOIMAGEMANAGER."</h4>\n";

	$cat_selbox_class =& webphoto_cat_selbox::getInstance();
	$cat_selbox_class->init( $this->_DIRNAME, $this->_TRUST_DIRNAME );

	$this->_form_class->print_form_image(
		$cat_selbox_class->build_selbox( 'cat_title', 0, null ) ,
		$this->_image_handler->build_cat_selbox() 
	);

}

function _print_export_count( $count )
{
	echo "<br />\n";
	echo "<b>";
	echo sprintf( _AM_WEBPHOTO_FMT_EXPORTSUCCESS , $count ) ;
	echo "</b><br />\n";
}

function _print_finish()
{
	echo "<br /><hr />\n";
	echo "<h4>FINISHED</h4>\n";
	echo '<a href="index.php">GOTO Admin Menu</a>'."<br />\n";
}

// --- class end ---
}

?>