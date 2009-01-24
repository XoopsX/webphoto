<?php
// $Id: batch.php,v 1.5 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// webphoto_photo_create -> webphoto_edit_factory_create
// 2008-08-01 K.OHWADA
// use webphoto_photo_create
// 2008-07-01 K.OHWADA
// used create_flash() create_single_thumb()
// xoops_error() -> build_error_msg()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_batch
//=========================================================
class webphoto_admin_batch extends webphoto_edit_base
{
	var $_factory_create_class;

	var $_post_catid;
	var $_post_desc;
	var $_post_uid;
	var $_time_update;

	var $_ADMIN_BATCH_PHP;

	var $_TIME_SUCCESS  = 1;
	var $_TIME_FAIL     = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_batch( $dirname , $trust_dirname )
{
	$this->webphoto_edit_base( $dirname , $trust_dirname );

	$this->_factory_create_class =& webphoto_edit_factory_create::getInstance( 
		$dirname , $trust_dirname );
	$this->_factory_create_class->set_msg_level( _C_WEBPHOTO_MSG_LEVEL_ADMIN );
	$this->_factory_create_class->set_flag_print_first_msg( true );

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

	if ( empty( $dir ) ) {
		$this->set_error( _AM_WEBPHOTO_MES_INVALIDDIRECTORY );
		$this->set_error( $post_dir );
		return false ;
	}

	if ( ! is_dir( $dir ) ) {
		$dir = $this->add_slash_to_head( $dir );
		$prefix = XOOPS_ROOT_PATH ;
		while( strlen( $prefix ) > 0 ) {
			if( is_dir( $prefix.$dir ) ) {
				$dir = $prefix.$dir ;
				break ;
			}
			$prefix = substr( $prefix , 0 , strrpos( $prefix , '/' ) ) ;
		}

	}

	if ( ! is_dir( $dir ) ) {
		$this->set_error( _AM_WEBPHOTO_MES_INVALIDDIRECTORY );
		$this->set_error( $post_dir );
		return false ;
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

	$item_row = $this->_item_handler->create( true );
	$item_row['item_cat_id']      = $this->_post_catid ;
	$item_row['item_uid']         = $this->_post_uid ;
	$item_row['item_time_update'] = $this->_time_update ;
	$item_row['item_description'] = $this->_post_desc ;
	$item_row['item_status']      = _C_WEBPHOTO_STATUS_APPROVED ;

	$param = array(
		'flag_video_single' => true ,
	);

	$filecount = 1 ;
	foreach( $file_names as $file_name ) 
	{
		// Skip '.' , '..' and hidden file
		if ( substr( $file_name , 0 , 1 ) == '.' ) { continue ; }

		$ext  = $this->parse_ext( $file_name ) ;
		$node = substr( $file_name , 0 , - strlen( $ext ) - 1 ) ;
		$src_file = $dir .'/'. $file_name ;

		if ( ! is_readable( $src_file ) ) {
			echo ' Skip : can not read : '. $this->sanitize($file_name)."<br />\n" ;
			continue;
		}

		if ( ! $this->is_my_allow_ext( $ext ) ) {
			echo ' Skip : not allow ext : '. $this->sanitize($file_name) ."<br />\n" ;
			continue;
		}

		$title = empty( $post_title ) ? addslashes( $node ) : $post_title.' - '.$filecount ;

		$item_row['item_title'] = $title ;
		$param['src_file']      = $src_file ;

		$this->_factory_create_class->create_item_from_param( $item_row, $param );
		echo $this->_factory_create_class->get_main_msg();
		echo "<br />\n";

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