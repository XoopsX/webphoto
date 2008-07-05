<?php
// $Id: export.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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

	$constpref = strtoupper( '_P_' . $dirname. '_' ) ;
	$CONST_DEBUG_SQL = $constpref.'DEBUG_SQL';

	$this->_image_handler =& webphoto_xoops_image_handler::getInstance();
	$this->_image_handler->set_debug_error( 1 );
	$this->_image_handler->set_debug_sql_by_const_name( $CONST_DEBUG_SQL );

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
	$const_name = strtoupper( '_MI_'. $this->_DIRNAME .'_ADMENU_EXPORT' ) ;
	echo "<h3>". constant($const_name) ."</h3>\n";

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

	$photo_rows = $this->_photo_handler->get_rows_by_catid( $this->_src_catid );
	if ( !is_array($photo_rows) || !count($photo_rows) ) {
		echo 'no photo image';
		return false;
	}

	$export_count = 0 ;

	foreach( $photo_rows as $photo_row )
	{
		extract( $photo_row ) ;

		echo $photo_id.' '.$this->sanitize($photo_title).' : ';

		if ( !$this->is_normal_ext( $photo_cont_ext) ) {
			echo " skip not image <br />\n";
			continue;
		}

		if ( $use_thumb ) {
			$src_file = XOOPS_ROOT_PATH . $photo_thumb_path ;
			$ext      = $photo_thumb_ext ;
			$mime     = $photo_thumb_mime ;

		} else {
			$src_file = XOOPS_ROOT_PATH . $photo_cont_path ;
			$ext      = $photo_cont_ext ;
			$mime     = $photo_cont_mime ;
		}

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
			'image_nicename' => $photo_title ,
			'image_created'  => $photo_time_update ,
			'image_mimetype' => $mime ,
			'image_display'  => $photo_status ? 1 : 0 ,
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
	$cat_selbox_class->init( $this->_DIRNAME );

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