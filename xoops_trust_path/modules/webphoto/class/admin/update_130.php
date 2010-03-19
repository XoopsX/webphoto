<?php
// $Id: update_130.php,v 1.6 2010/03/19 00:23:02 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-15 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-03-18 K.OHWADA
// webphoto_edit_item_create
// 2009-10-25 K.OHWADA
// BUG: Notice [PHP]: Undefined variable: item_id
// 2009-04-10 K.OHWADA
// webphoto_small_create
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_update_130
//=========================================================
class webphoto_admin_update_130 extends webphoto_base_this
{
	var $_item_create_class;
	var $_form_class;
	var $_small_create_class;

	var $_post_offset;
	var $_next;

	var $_LIMIT = 50;

	var $_THIS_FCT = 'update_130'; 
	var $_THIS_URL = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_update_130( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_item_create_class  
		=& webphoto_edit_item_create::getInstance( $dirname , $trust_dirname  );
	$this->_form_class =& webphoto_lib_form::getInstance(   $dirname , $trust_dirname );
	$this->_small_create_class =& webphoto_edit_small_create::getInstance( $dirname );

	$this->_item_create_class->set_debug_error( true );
	$this->_file_handler->set_debug_error( true );

	$this->_THIS_URL = $this->_MODULE_URL .'/admin/index.php?fct='.$this->_THIS_FCT ;

	$this->preload_init();
	$this->preload_constant();
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_update_130( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// init
//---------------------------------------------------------
function get_post_offset()
{
	$this->_post_offset = $this->_post_class->get_post_get('offset');
	$this->_next        = $this->_post_offset + $this->_LIMIT;
	return $this->_post_offset;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	xoops_cp_header();

	$op = $this->_post_class->get_post_text('op');

// when form
	if ( empty($op) ) {
		echo $this->build_admin_menu();
		echo $this->build_admin_title( 'UPDATE' );

		$item_count  = $this->_item_create_class->get_count_all();
		$small_count = $this->_file_handler->get_count_by_kind( _C_WEBPHOTO_FILE_KIND_SMALL );
		echo 'There are '. $item_count .' items and '. $small_count .' small images';
		echo "<br /><br />\n";
		if ( $item_count == 0 ) {
			$msg = 'You dont need update.';
		} elseif ( $small_count > 0 ) {
			$msg = 'Probably, you dont need update.';
		} else {
			$msg = _AM_WEBPHOTO_MUST_UPDATE ;
		}
		echo $this->build_error_msg( $msg, '', false );
		echo "<br />\n";

	} else {
		echo $this->build_admin_bread_crumb( 
			$this->get_admin_title( 'UPDATE' ), $this->_THIS_URL );
	}

	echo "Update v1.20 to v1.30 <br /><br />\n";

	switch ( $op ) 
	{
		case 'update_item':
			if ( $this->check_token() ) {
				$this->_update_item();
			}
			break;

		case 'form':
		default:
			$this->_form_item();
			break;
	}

	xoops_cp_footer();
	exit();
}

//---------------------------------------------------------
// update_item
//---------------------------------------------------------
function _update_item()
{
	$offset = $this->get_post_offset();

	$total     = $this->_item_create_class->get_count_all();
	$item_rows = $this->_item_create_class->get_rows_all_asc( $this->_LIMIT, $offset );

	$next = $this->_next;
	if ( $this->_next > $total ) {
		$next = $total;
	}

	echo "<h4>item table</h4>\n";
	echo "There are $total items in item table<br />\n";
	echo "Update $offset - $next th item <br /><br />";

	foreach ( $item_rows as $item_row )
	{
		$item_id        = $item_row['item_id'];
		$title          = $item_row['item_title'];
		$file_id_cont   = $item_row[ _C_WEBPHOTO_ITEM_FILE_CONT ];
		$file_id_middle = $item_row[ _C_WEBPHOTO_ITEM_FILE_MIDDLE ];
		$file_id_small  = $item_row[ _C_WEBPHOTO_ITEM_FILE_SMALL ];

		echo $item_id.' : '.$this->sanitize($title).' : ';

// src param
		$src_param = $this->_get_src_param( $item_row );
		if ( ! is_array($src_param) ) {
			echo "<br />\n";
			continue;
		}

// create small image
		$small_param = $this->_small_create_class->create_small_image( $src_param );
		if ( ! is_array($small_param) ) {
			echo ' failed to create image ';
			echo "<br />\n";
			continue;
		}

// insert file
		$small_param['item_id'] = $item_id ;
		$file_row   = $this->_file_handler->create( true );
		$file_row   = $this->_file_handler->build_row_by_param( $file_row, $small_param );
		$file_newid = $this->_file_handler->insert( $file_row );
		if ( ! $file_newid ) {
			echo ' failed to insert file table <br />';
			echo $this->_file_handler->get_format_error() ;
			echo "<br />\n";
			continue;
		}

// update item
		$item_row[ _C_WEBPHOTO_ITEM_FILE_SMALL ]  = $file_newid ;
		$ret = $this->_item_create_class->format_and_update( $item_row );
		if ( $ret ) {
			echo ' OK ';
		} else {
			echo ' failed to update item table <br />';
			echo $this->_item_create_class->get_format_error() ;
		}

		echo "<br />\n";
	}

	if ( $total > $next ) {
		$this->_form_next_item( $next );
	} else {
		$this->_print_finish();
	}
}

function _get_src_param( $item_row )
{
	$item_id        = $item_row[ 'item_id' ];
	$file_id_cont   = $item_row[ _C_WEBPHOTO_ITEM_FILE_CONT ];
	$file_id_middle = $item_row[ _C_WEBPHOTO_ITEM_FILE_MIDDLE ];
	$file_id_thumb  = $item_row[ _C_WEBPHOTO_ITEM_FILE_THUMB ];
	$file_id_small  = $item_row[ _C_WEBPHOTO_ITEM_FILE_SMALL ];

	if ( $file_id_small > 0 ) {
		echo ' skip already small image ';
		return false;
	}

	$param = $this->_get_file_param( $item_id, $file_id_cont, true );
	if ( is_array($param) ) {
		return $param;
	}

	$param = $this->_get_file_param( $item_id, $file_id_middle );
	if ( is_array($param) ) {
		return $param;
	}

	$param = $this->_get_file_param( $item_id, $file_id_thumb );
	if ( is_array($param) ) {
		return $param;
	}

	$param = $this->_small_create_class->build_small_param_from_external_icon( $item_row );
	if ( is_array($param) ) {
		return $param;
	}

	echo ' skip not exist original image ';
	return false;
}

// Notice [PHP]: Undefined variable: item_id
function _get_file_param( $item_id, $file_id, $flag_ext=false )
{
	if ( empty($file_id) ) {
		return null;
	}

	$row = $this->_file_handler->get_row_by_id( $file_id );
	if ( !is_array($row) ) {
		return null;
	} 

	if ( empty( $row['file_path'] ) ) {
		return null;
	}

	if ( $flag_ext && !$this->is_image_ext( $row['file_ext'] ) ) {
			return null;
	}

	$arr = array(
		'item_id'  => $item_id ,
		'src_file' => XOOPS_ROOT_PATH . $row['file_path'] ,
		'src_ext'  => $row['file_ext'] ,
	);
	return $arr;
}

//---------------------------------------------------------
// form
//---------------------------------------------------------
function _print_finish()
{
	echo "<br /><hr />\n";
	echo "<h4>FINISHED</h4>\n";
	echo '<a href="index.php">GOTO Admin Menu</a>'."<br />\n";
}

function _form_item()
{
	$title  = 'Convert item table to item table';
	$op     = 'update_item';
	$submit = 'Update';

	echo "<h4>".$title."</h4>\n";
	$this->_print_form_next($title, $op, $submit);
}

function _form_next_item($offset)
{
	$title  = 'Convert item table to item table';
	$submit = "GO next ".$this->_LIMIT." items";
	$op     = 'update_item';

	echo "<br /><hr />\n";
	$this->_print_form_next($title, $op, $submit, $offset);
}

function _print_form_next( $title, $op, $submit_value, $offset=0 )
{
	echo "<br />\n";

	$desc = '';
	if ( $offset > 0 ) {
		$next = $offset + $this->_LIMIT;
		$desc = "Update ".$offset." - ".$next." th record";
	}

// show form
	$param = array(
		'title'        => $title,
		'desc'         => $desc,
		'submit_value' => $submit_value,
	);

	$hidden_arr = array(
		'fct'    => $this->_THIS_FCT ,
		'op'     => $op,
		'limit'  => 0,
		'offset' => $offset,
	);

	$text = $this->_form_class->build_form_box_with_style( $param, $hidden_arr );
	echo $text;
}

// --- class end ---
}

?>