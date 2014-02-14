<?php
// $Id: update_040.php,v 1.6 2010/11/16 23:43:38 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-11 K.OHWADA
// build_random_file_node()
// 2010-03-18 K.OHWADA
// webphoto_edit_item_create
// 2010-01-10 K.OHWADA
// get_ini()
// 2008-11-08 K.OHWADA
// create_thumb() -> resize_rotate() 
// 2008-10-01 K.OHWADA
// webphoto_admin_update -> webphoto_admin_update_040
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_update_040
//=========================================================
class webphoto_admin_update_040 extends webphoto_base_this
{
	var $_item_create_class;
	var $_photo_handler;
	var $_image_class;
	var $_image_cmd_class;
	var $_form_class;

	var $_cfg_middle_width  = 0 ;
	var $_cfg_middle_height = 0 ;

	var $_post_offset;
	var $_next;

	var $_LIMIT = 50;
	var $_FLAG_FDIVING = false ;

	var $_THIS_FCT = 'update_040'; 
	var $_THIS_URL = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_update_040( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_item_create_class  
		=& webphoto_edit_item_create::getInstance( $dirname , $trust_dirname  );

	$this->_photo_handler =& webphoto_photo_handler::getInstance( $dirname );
	$this->_image_class   =& webphoto_image_info::getInstance( $dirname , $trust_dirname );
	$this->_form_class    =& webphoto_lib_form::getInstance(   $dirname , $trust_dirname );

	$this->_item_create_class->set_debug_error( true );
	$this->_file_handler->set_debug_error( true );

	$this->_cfg_middle_width  = $this->get_config_by_name( 'middle_width' ) ;
	$this->_cfg_middle_height = $this->get_config_by_name( 'middle_height' ) ;

	$this->_THIS_URL = $this->_MODULE_URL .'/admin/index.php?fct='.$this->_THIS_FCT ;

	$this->_init_image_cmd();

	$this->preload_init();
	$this->preload_constant();

	$this->_FLAG_FDIVING = $this->get_ini('update_fdiving');
	echo "flag: ".$this->_FLAG_FDIVING ;
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_update_040( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// init
//---------------------------------------------------------
function _init_image_cmd()
{
	$this->_image_cmd_class =& webphoto_lib_image_cmd::getInstance();

	$this->_image_cmd_class->set_imagingpipe(  $this->get_config_by_name( 'imagingpipe' ) );
	$this->_image_cmd_class->set_forcegd2(     $this->get_config_by_name( 'forcegd2' ) );
	$this->_image_cmd_class->set_imagickpath(  $this->get_config_by_name( 'imagickpath' ) );
	$this->_image_cmd_class->set_netpbmpath(   $this->get_config_by_name( 'netpbmpath' ) );
	$this->_image_cmd_class->set_jpeg_quality( $this->get_config_by_name( 'jpeg_quality' ) );
	$this->_image_cmd_class->set_normal_exts(  $this->get_normal_exts() );
}

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

		if ( $this->_item_create_class->get_count_all() > 0 ) {
			$msg = 'You dont need update.<br />already exists item records';
		} elseif ( $this->_photo_handler->get_count_all() > 0 ) {
			$msg = _AM_WEBPHOTO_MUST_UPDATE ;
		}
		echo $this->build_error_msg( $msg, '', false );
		echo "<br />\n";

	} else {
		echo $this->build_admin_bread_crumb( 
			$this->get_admin_title( 'UPDATE' ), $this->_THIS_URL );
	}

	echo "Update v0.30 to v0.40 <br /><br />\n";

	switch ( $op ) 
	{
		case 'update_photo':
			if ( $this->check_token() ) {
				$this->_update_photo();
			}
			break;

		case 'form':
		default:
			$this->_form_photo();
			break;
	}

	xoops_cp_footer();
	exit();
}

//---------------------------------------------------------
// update_photo
//---------------------------------------------------------
function _update_photo()
{
	$offset = $this->get_post_offset();

	$total      = $this->_photo_handler->get_count_all();
	$photo_rows = $this->_photo_handler->get_rows_all_asc( $this->_LIMIT, $offset );

	$next = $this->_next;
	if ( $this->_next > $total ) {
		$next = $total;
	}

	echo "<h4>photo table</h4>\n";
	echo "There are $total photos in photo table<br />\n";
	echo "Update $offset - $next th photo <br /><br />";

	foreach ( $photo_rows as $photo_row )
	{
		$photo_id = $photo_row['photo_id'];
		$title    = $photo_row['photo_title'];
		$cont_ext = $photo_row['photo_cont_ext'];
		$file_ext = $photo_row['photo_file_ext'];

		$file_id_middle = 0;
		$file_id_flash  = 0 ;
		$file_id_docomo = 0 ;
		$file_id_10     = 0 ;

		echo $photo_id.' : '.$this->sanitize($title).' : ';

		$item_row = $this->_insert_item( $photo_row );
		if ( !is_array($item_row) ) {
			echo "<br />\n";
			continue;
		}

		$file_id_cont  = $this->_insert_file_cont(  $photo_row );
		$file_id_thumb = $this->_insert_file_thumb( $photo_row );

// docomo imotion
		if ( $cont_ext == '3gp' ) {
			$file_id_docomo = $this->_insert_file_docomo( $photo_row );
		}

// flash
		if ( $file_ext == 'flv' ) {
			$file_id_flash = $this->_insert_file_file(
				_C_WEBPHOTO_FILE_KIND_VIDEO_FLASH, $photo_row );
		}

// create middle image
		$param = $this->_build_image_middle( $photo_row );
		if ( is_array($param) ) {
			$file_id_middle = $this->_insert_file_middle( $photo_row, $param );
		}

// for fdiving
		if ( $this->_FLAG_FDIVING ) {
			$file_id_10 = $this->_insert_file_file( 10, $photo_row );
		}

		$item_row['item_file_id_1']  = $file_id_cont ;
		$item_row['item_file_id_2']  = $file_id_thumb ;
		$item_row['item_file_id_3']  = $file_id_middle ;
		$item_row['item_file_id_4']  = $file_id_flash ;
		$item_row['item_file_id_5']  = $file_id_docomo ;
		$item_row['item_file_id_10'] = $file_id_10 ;
		$this->_item_create_class->format_and_update( $item_row );

		echo "<br />\n";
	}

	if ( $total > $next ) {
		$this->_form_next_photo( $next );
	} else {
		$this->_print_finish();
	}
}

function _insert_item( $photo_row )
{
	$row = $this->_item_create_class->create();
	$row['item_id']             = $photo_row['photo_id'] ;
	$row['item_time_create']    = $photo_row['photo_time_create'] ;
	$row['item_time_update']    = $photo_row['photo_time_update'] ;
	$row['item_cat_id']         = $photo_row['photo_cat_id'] ;
	$row['item_gicon_id']       = $photo_row['photo_gicon_id'] ;
	$row['item_uid']            = $photo_row['photo_uid'] ;
	$row['item_ext']            = $photo_row['photo_cont_ext'] ;
	$row['item_title']          = $photo_row['photo_title'] ;
	$row['item_datetime']       = $photo_row['photo_datetime'] ;
	$row['item_place']          = $photo_row['photo_place'] ;
	$row['item_equipment']      = $photo_row['photo_equipment'] ;
	$row['item_gmap_latitude']  = $photo_row['photo_gmap_latitude'] ;
	$row['item_gmap_longitude'] = $photo_row['photo_gmap_longitude'] ;
	$row['item_gmap_zoom']      = $photo_row['photo_gmap_zoom'] ;
	$row['item_gmap_type']      = $photo_row['photo_gmap_type'] ;
	$row['item_status']         = $photo_row['photo_status'] ;
	$row['item_hits']           = $photo_row['photo_hits'] ;
	$row['item_rating']         = $photo_row['photo_rating'] ;
	$row['item_votes']          = $photo_row['photo_votes'] ;
	$row['item_comments']       = $photo_row['photo_comments'] ;
	$row['item_perm_read']      = $photo_row['photo_perm_read'] ;
	$row['item_description']    = $photo_row['photo_description'] ;
	$row['item_exif']           = $photo_row['photo_cont_exif'] ;
	$row['item_search']         = $photo_row['photo_search'] ;
	$row['item_text_1']         = $photo_row['photo_text1'] ;
	$row['item_text_2']         = $photo_row['photo_text2'] ;
	$row['item_text_3']         = $photo_row['photo_text3'] ;
	$row['item_text_4']         = $photo_row['photo_text4'] ;
	$row['item_text_5']         = $photo_row['photo_text5'] ;
	$row['item_text_6']         = $photo_row['photo_text6'] ;
	$row['item_text_7']         = $photo_row['photo_text7'] ;
	$row['item_text_8']         = $photo_row['photo_text8'] ;
	$row['item_text_9']         = $photo_row['photo_text9'] ;
	$row['item_text_10']        = $photo_row['photo_text10'] ;
	$row['item_kind']           = $this->_build_item_kind( $photo_row ) ;

	$newid = $this->_item_create_class->format_and_insert( $row );
	if ( !$newid ) {
		return false;
	}

	$row['item_id'] = $newid;
	return $row;
}

function _insert_file_cont( $photo_row )
{
	$row = $this->_file_handler->create();
	$row['file_time_create'] = $photo_row['photo_time_create'] ;
	$row['file_time_update'] = $photo_row['photo_time_update'] ;
	$row['file_item_id']     = $photo_row['photo_id'] ;
	$row['file_url']         = $photo_row['photo_cont_url'] ;
	$row['file_path']        = $photo_row['photo_cont_path'] ;
	$row['file_name']        = $photo_row['photo_cont_name'] ;
	$row['file_ext']         = $photo_row['photo_cont_ext'] ;
	$row['file_mime']        = $photo_row['photo_cont_mime'] ;
	$row['file_medium']      = $photo_row['photo_cont_medium'] ;
	$row['file_size']        = $photo_row['photo_cont_size'] ;
	$row['file_width']       = $photo_row['photo_cont_width'] ;
	$row['file_height']      = $photo_row['photo_cont_height'] ;
	$row['file_duration']    = $photo_row['photo_cont_duration'] ;
	$row['file_kind']        = _C_WEBPHOTO_FILE_KIND_CONT ;

	return $this->_file_handler->insert( $row );
}

function _insert_file_thumb( $photo_row )
{
	$row = $this->_file_handler->create();
	$row['file_time_create'] = $photo_row['photo_time_create'] ;
	$row['file_time_update'] = $photo_row['photo_time_update'] ;
	$row['file_item_id']     = $photo_row['photo_id'] ;
	$row['file_url']         = $photo_row['photo_thumb_url'] ;
	$row['file_path']        = $photo_row['photo_thumb_path'] ;
	$row['file_name']        = $photo_row['photo_thumb_name'] ;
	$row['file_ext']         = $photo_row['photo_thumb_ext'] ;
	$row['file_mime']        = $photo_row['photo_thumb_mime'] ;
	$row['file_medium']      = $photo_row['photo_thumb_medium'] ;
	$row['file_size']        = $photo_row['photo_thumb_size'] ;
	$row['file_width']       = $photo_row['photo_thumb_width'] ;
	$row['file_height']      = $photo_row['photo_thumb_height'] ;
	$row['file_kind']        = _C_WEBPHOTO_FILE_KIND_THUMB ;

	return $this->_file_handler->insert( $row );
}

function _insert_file_file( $kind, $photo_row )
{
	$row = $this->_file_handler->create();
	$row['file_time_create'] = $photo_row['photo_time_create'] ;
	$row['file_time_update'] = $photo_row['photo_time_update'] ;
	$row['file_item_id']     = $photo_row['photo_id'] ;
	$row['file_url']         = $photo_row['photo_file_url'] ;
	$row['file_path']        = $photo_row['photo_file_path'] ;
	$row['file_name']        = $photo_row['photo_file_name'] ;
	$row['file_ext']         = $photo_row['photo_file_ext'] ;
	$row['file_mime']        = $photo_row['photo_file_mime'] ;
	$row['file_medium']      = $photo_row['photo_file_medium'] ;
	$row['file_size']        = $photo_row['photo_file_size'] ;
	$row['file_kind']        = $kind ;

	if ( $kind == _C_WEBPHOTO_FILE_KIND_VIDEO_FLASH ) {
		$row['file_width']    = $photo_row['photo_cont_width'] ;
		$row['file_height']   = $photo_row['photo_cont_height'] ;
		$row['file_duration'] = $photo_row['photo_cont_duration'] ;
	}

	return $this->_file_handler->insert( $row );
}

function _insert_file_docomo( $photo_row )
{
	$row = $this->_file_handler->create();
	$row['file_time_create'] = $photo_row['photo_time_create'] ;
	$row['file_time_update'] = $photo_row['photo_time_update'] ;
	$row['file_item_id']     = $photo_row['photo_id'] ;
	$row['file_url']         = $photo_row['photo_cont_url'] ;
	$row['file_path']        = '' ;	// null
	$row['file_name']        = $photo_row['photo_cont_name'] ;
	$row['file_ext']         = $photo_row['photo_cont_ext'] ;
	$row['file_mime']        = $photo_row['photo_cont_mime'] ;
	$row['file_medium']      = $photo_row['photo_cont_medium'] ;
	$row['file_size']        = $photo_row['photo_cont_size'] ;
	$row['file_width']       = $photo_row['photo_cont_width'] ;
	$row['file_height']      = $photo_row['photo_cont_height'] ;
	$row['file_duration']    = $photo_row['photo_cont_duration'] ;
	$row['file_kind']        = _C_WEBPHOTO_FILE_KIND_VIDEO_DOCOMO ;

	return $this->_file_handler->insert( $row );
}

function _insert_file_middle( $photo_row, $param )
{
	$row = $this->_file_handler->create();
	$row['file_time_create'] = $photo_row['photo_time_create'] ;
	$row['file_time_update'] = $photo_row['photo_time_update'] ;
	$row['file_item_id']     = $photo_row['photo_id'] ;
	$row['file_kind']        = _C_WEBPHOTO_FILE_KIND_MIDDLE ;

	$update = array_merge( $row, $param );

	return $this->_file_handler->insert( $update );
}

function _build_item_kind( $photo_row )
{
	$kind = 0 ;
	if ( $this->is_normal_ext( $photo_row['photo_cont_ext'] ) ) {
		$kind = _C_WEBPHOTO_ITEM_KIND_IMAGE ;
	} elseif ( $photo_row['photo_cont_medium'] == 'video' ) {
		$kind = _C_WEBPHOTO_ITEM_KIND_VIDEO ;
	}
	return $kind ;
}

function _build_image_middle( $photo_row )
{
	$mime   = '' ;
	$medium = '' ;
	$width  = 0 ;
	$height = 0 ;

	if ( $this->is_normal_ext( $photo_row['photo_cont_ext'] ) ) {

// create image if big
		if (( $photo_row['photo_cont_width']  > $this->_cfg_middle_width )||
		    ( $photo_row['photo_cont_height'] > $this->_cfg_middle_height )) {
			$param = $this->_create_image_middle( $photo_row );
			if ( !is_array($param) ) {
				echo $this->highlight( ' not create middle ' );
				return false;
			}

			$path = $param['path'];
			$name = $param['name'];
			$ext  = $param['ext'];
			$file = XOOPS_ROOT_PATH . $path;
			$url  = XOOPS_URL . $path ;

			$image_size = GetImageSize( $file ) ;
			if ( !is_array($image_size) ) {
				echo $this->highlight( ' not get middle size ' );
				return false;
			}

			$width  = $image_size[0];
			$height = $image_size[1];
			$mime   = $image_size['mime'];
			$medium = 'image' ;
			$size   = filesize( $file );

			echo ' create middle ';

// use orginal if small
		} else {
			$url    = $photo_row['photo_cont_url'];
			$path   = '' ;	// null
			$name   = $photo_row['photo_cont_name'];
			$ext    = $photo_row['photo_cont_ext'];
			$size   = $photo_row['photo_cont_size'] ;
			$width  = $photo_row['photo_cont_width'] ;
			$height = $photo_row['photo_cont_height'] ;
			$mime   = $photo_row['photo_cont_mime'] ;
			$medium = $photo_row['photo_cont_medium'] ;

		}

// use thumbnail if video
	} elseif ( $photo_row['photo_cont_medium'] == 'video' ) {
		$url    = $photo_row['photo_thumb_url'];
		$path   = '' ;	// null
		$name   = $photo_row['photo_thumb_name'];
		$ext    = $photo_row['photo_thumb_ext'];
		$size   = $photo_row['photo_thumb_size'] ;
		$width  = $photo_row['photo_thumb_width'] ;
		$height = $photo_row['photo_thumb_height'] ;
		$mime   = $photo_row['photo_thumb_mime'] ;
		$medium = $photo_row['photo_thumb_medium'] ;

// others
	} else {
		return false;
	}

	$arr = array(
		'file_url'    => $url ,
		'file_path'   => $path ,
		'file_name'   => $name ,
		'file_ext'    => $ext ,
		'file_mime'   => $mime ,
		'file_medium' => $medium ,
		'file_size'   => $size ,
		'file_width'  => $width ,
		'file_height' => $height ,
	);

	return $arr;
}

function _create_image_middle( $photo_row )
{
	$photo_id  = $photo_row['photo_id'] ;
	$cont_path = $photo_row['photo_cont_path'] ;
	$cont_ext  = $photo_row['photo_cont_ext'] ;

	$src_file  = XOOPS_ROOT_PATH . $cont_path ;
	$node      = $this->build_random_file_node( $photo_id );
	$name      = $node .'.'. $cont_ext ;
	$path      = $this->_MIDDLES_PATH .'/'. $name;
	$dst_file  = XOOPS_ROOT_PATH . $path ;

	$ret = $this->_image_cmd_class->resize_rotate( 
		 $src_file, $dst_file, $this->_cfg_middle_width, $this->_cfg_middle_height );

	if (( $ret == _C_WEBPHOTO_IMAGE_READFAULT )||
	    ( $ret == _C_WEBPHOTO_IMAGE_SKIPPED )) {
		return false;
	}

	$arr = array(
		'path' => $path ,
		'name' => $name ,
		'ext'  => $cont_ext ,
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

function _form_photo()
{
	$title  = 'Convert photo table to item table';
	$op     = 'update_photo';
	$submit = 'Update';

	echo "<h4>".$title."</h4>\n";
	$this->_print_form_next($title, $op, $submit);
}

function _form_next_photo($offset)
{
	$title  = 'Convert photo table to item table';
	$submit = "GO next ".$this->_LIMIT." photos";
	$op     = 'update_photo';

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