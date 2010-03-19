<?php
// $Id: update_050.php,v 1.4 2010/03/19 00:23:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-03-18 K.OHWADA
// webphoto_edit_item_create
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_player_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_update_050
//=========================================================
class webphoto_admin_update_050 extends webphoto_base_this
{
	var $_item_create_class;
	var $_player_handler;
	var $_form_class;
	var $_mime_class;

	var $_post_offset;
	var $_next;

	var $_LIMIT = 100;

	var $_THIS_FCT = 'update_050'; 
	var $_THIS_URL = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_update_050( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_item_create_class  
		=& webphoto_edit_item_create::getInstance( $dirname , $trust_dirname  );

	$this->_player_handler =& webphoto_player_handler::getInstance( 
		$dirname , $trust_dirname );
	$this->_form_class     =& webphoto_lib_form::getInstance(   
		$dirname , $trust_dirname );
	$this->_mime_class     =& webphoto_mime::getInstance(   
		$dirname , $trust_dirname );

	$this->_item_create_class->set_debug_error( true );
	$this->_player_handler->set_debug_error( true );

	$this->_THIS_URL = $this->_MODULE_URL .'/admin/index.php?fct='.$this->_THIS_FCT ;
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_update_050( $dirname , $trust_dirname );
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

		if ( $this->_player_handler->get_count_all() > 0 ) {
			$msg = 'You dont need update.<br />already exists player records';
		} else {
			$msg = _AM_WEBPHOTO_MUST_UPDATE ;
		}
		echo $this->build_error_msg( $msg, '', false );
		echo "<br />\n";

	} else {
		echo $this->build_admin_bread_crumb( 
			$this->get_admin_title( 'UPDATE' ), $this->_THIS_URL );
	}

	echo "Update v0.40 to v0.50 <br />\n";
	echo "set displaytype, onclick, duration <br /><br />\n";

	switch ( $op ) 
	{
		case 'update_item':
			if ( $this->check_token() ) {
				$this->_update_item();
			}
			break;

		case 'update_player':
			if ( $this->check_token() ) {
				$this->_update_player();
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
	echo "There are $total records in item table<br />\n";
	echo "Update $offset - $next th record <br /><br />";

	foreach ( $item_rows as $item_row )
	{
		$item_id = $item_row['item_id'];
		$title   = $item_row['item_title'];

		echo $item_id.' : '.$this->sanitize($title).' : ';

		$item_row['item_kind']        = $this->get_kind( $item_row );
		$item_row['item_displaytype'] = $this->get_displaytype( $item_row ) ;
		$item_row['item_onclick']     = $this->get_onclick( $item_row );
		$item_row['item_duration']    = $this->get_duration( $item_row );

		$this->_item_create_class->format_and_update( $item_row );

		echo "update <br />\n";
	}

	if ( $total > $next ) {
		$this->_form_next_item( $next );
	} else {
		$this->_form_player();
	}
}

function get_kind( $item_row )
{
	$ext  = $item_row['item_ext'];
	$kind = $this->_mime_class->ext_to_kind( $ext );
	return $kind;
}

function get_displaytype( $item_row )
{
	$ext      = $item_row['item_ext'];
	$flash_id = $item_row['item_file_id_4'];
	$displaytype = _C_WEBPHOTO_DISPLAYTYPE_GENERAL ;

	if ( $this->is_image_ext( $ext ) ) {
		$displaytype = _C_WEBPHOTO_DISPLAYTYPE_IMAGE ;

	} elseif ( $this->is_swfobject_ext( $ext ) ) {
		$displaytype = _C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT ;

	} elseif ( $this->is_mediaplayer_ext( $ext ) || $flash_id ) {
		$displaytype = _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ;
	}
	return $displaytype ;
}

function get_onclick( $item_row )
{
	$ext     = $item_row['item_ext'];
	$onclick = _C_WEBPHOTO_ONCLICK_PAGE ;

	if ( $this->is_image_ext( $ext ) ) {
		$onclick = _C_WEBPHOTO_ONCLICK_POPUP ;
	}
	return $onclick ;
}

function get_duration( $item_row )
{
	$duration = 0 ;

	$kind    = $item_row['item_kind'];
	$cont_id = $item_row['item_file_id_1'];

	if ( ! $this->is_video_audio_kind( $kind ) ) {
		return $duration ;
	}

	$cont_row = $this->_file_handler->get_row_by_id( $cont_id );
	if ( isset( $cont_row['file_duration'] ) ) {
		return  $cont_row['file_duration'] ;
	}

	return $duration ;
}

//---------------------------------------------------------
// update_player
//---------------------------------------------------------
function _update_player()
{
	echo "<h4>player table</h4>\n";

// default
	$row = $this->_player_handler->create( true );
	$row['player_title'] = 'default' ;

	$this->_player_handler->insert( $row );

// playlist default
	$row['player_title'] = 'playlist default' ;
	$row['player_width']         = _C_WEBPHOTO_PLAYER_WIDTH_PLAYLIST ;
	$row['player_height']        = _C_WEBPHOTO_PLAYER_HEIGHT_PLAYLIST ;
	$row['player_displaywidth']  = _C_WEBPHOTO_PLAYER_DISPLAYWIDTH_PLAYLIST ;
	$row['player_displayheight'] = _C_WEBPHOTO_PLAYER_DISPLAYHEIGHT_PLAYLIST ;

	$this->_player_handler->insert( $row );

	$this->_print_finish();
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
	$title  = 'Set display type in item table';
	$submit = 'Update';
	$op     = 'update_item';

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

function _form_player()
{
	$title  = 'insert player';
	$submit = 'Update';
	$op     = 'update_player';

	echo "<h4>".$title."</h4>\n";
	$this->_print_form_next($title, $op, $submit);
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