<?php
// $Id: __flashvar_manager.php,v 1.1 2008/10/30 00:25:51 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_flashvar_manager
//=========================================================
class webphoto_admin_flashvar_manager extends webphoto_flashvar_edit
{
	var $_THIS_FCT = 'flashvar_manager';
	var $_THIS_URL;

	var $_TIME_SUCCESS = 1;
	var $_TIME_FAIL    = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_flashvar_manager( $dirname , $trust_dirname )
{
	$this->webphoto_flashvar_edit( $dirname , $trust_dirname );

	$this->_THIS_URL = $this->_MODULE_URL .'/admin/index.php?fct='.$this->_THIS_FCT;
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_flashvar_manager( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$op = $this->_post_class->get_post_get_text('op');

	switch ($op) 
	{
		case 'admin_modify':
			$this->_modify();
			break;

		case 'restore':
			$this->_restore();
			break;

		case 'main':
		case 'config': 
		default:  
			$this->_config();
			break;
	}
}

//---------------------------------------------------------
// menu
//---------------------------------------------------------
function _config()
{
	xoops_cp_header();
	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'FLASHVAR_MANAGER' );

//	$this->_check_default();

	$item_id = $this->_post_class->get_get_int( 'item_id' );

	$this->_print_linkselect_form( $item_id );

	$item_row = $this->_item_handler->get_row_by_id($item_id);

	if ( !is_array($item_row) ) {
		$item_row = $this->_item_handler->create( true );
	}

	$title       = $item_row['item_title'];
	$displaytype = $item_row['item_displaytype'];

	if ( !$this->_is_player_displaytype( $displaytype ) ) {
		redirect_header("javascript:history.go(-1)", 2, _WEBPHOTO_EMBEDLINK." "._WEBPHOTO_NOTALLOWED);
	}

	$flashvar_rows = $this->_flashvar_handler->get_rows_by_itemid( $item_id );

//	if ( isset($flashvar_rows[0]) ) {
//		$flashvar_row = $flashvar_rows[0];
//	} else {
//		$flashvar_row = $this->_flashvar_handler->create( true );
//		$flashvar_row['flashvar_item_id'] = $item_id;
//	}

// Playlist Title
	if ( $item_id > 0 ){
		echo '<h3>';
		echo '<a href="'.$this->_MODULE_URL.'/singlelink.php?item_id='.$item_id.'" title="'._WEBPHOTO_FLASHVAR_LINK_PAGE.'" target="_blank">';
		echo '<img src="'.$this->_MODULE_URL.'/images/link.gif" />';
		echo $title.' : '._WEBPHOTO_FLASHVARS;
		echo '</a></h3>'."\n";
		echo _WEBPHOTO_FLASHVARS_DSC;
		echo "<br /><br />\n";

	} else {
		echo '<h3>'. _WEBPHOTO_FLASHVARS_CONFIG .'</h3>'."\n";
		echo _WEBPHOTO_FLASHVARS_CONFIG_DSC;
		echo "<br /><br />\n";
	}

	if ( isset($flashvar_rows[0]) ) {
		$this->_print_form( 'admin_modify', $flashvar_rows[0] );
	}

	xoops_cp_footer();
}

function _is_player_displaytype( $displaytype )
{
	if ( $displaytype == _C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER ) {
		return true;
	}
	if ( $displaytype == _C_WEBPHOTO_DISPLAYTYPE_IMAGEROTATOR ) {
		return true;
	}
	return false;
}

function _print_linkselect_form( $item_id )
{
	echo '<form name="linkform" id="linkform">';
	echo '<select name="linkselect" onChange="location=this.options[this.selectedIndex].value;">';
	echo $this->_build_linkselect_option( 0, _AM_WEBPHOTO_SELECT_ITEM );

	$rows = $this->_item_handler->get_rows_internal();
	foreach ( $rows as $row ) {
		echo $this->_build_linkselect_option( $row['item_id'], $row['item_title'] );
	}

	echo "</select>\n";

	if ( $item_id > 0 ){
		$onclick = "location='". $this->_THIS_URL ."'" ;
		echo '<input type="button" value="'. _WEBPHOTO_FLASHVARS_CONFIG .'" onClick="'. $onclick .'" />'."\n";
	}

	echo "</form>\n";
	echo "<br />\n";
}

function _build_linkselect_option( $item_id, $value )
{
	$url  = $this->_THIS_URL .'&amp;item_id='. $item_id ;
	$str  = '<option value="'. $url .'">';
	$str .= $this->sanitize($value);
	$str .= "</option>\n";
	return $str;
}

//---------------------------------------------------------
// modify
//---------------------------------------------------------
function _modify()
{
	$ret = $this->modify();

	list( $url, $time, $msg ) =  $this->_build_flashvar_redirect( 
		$ret, 
		$this->get_format_error(), 
		$this->get_error_upload() );

	redirect_header( $url, $time, $msg );
	exit();

//-----
	if ( ! $this->check_token() ) {
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, $this->get_token_errors() );
	}

	$item_id = $this->_post_class->get_post_int(  'item_id' );

	$rows = $this->_flashvar_handler->get_rows_by_itemid($item_id);
	if ( !is_array($rows) ) {
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, _WEBPHOTO_NOTALLOWED );
	}

	$row = $this->_build_row_by_post( $row );

	$logo = $this->_fetch_logo();
	if ( $logo ) {
		$row['flashvar_logo'] = $logo ;
	}

	$newid = $this->_flashvar_handler->insert( $row );
	if ( !$newid ) {
		$msg  = "DB Error <br />\n";
		$msg .= $this->_flashvar_handler->get_format_error();
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, $msg );
	}

	$url = $this->_THIS_URL.'&amp;op=config&amp;item_id='.$item_id;

	redirect_header( $url, $this->_TIME_SUCCESS, _WEBPHOTO_DBUPDATED );
}

function XXX_build_row_by_post( $row ) 
{
	$row['flashvar_searchbar']        = $this->_post_class->get_post_int( 'flashvar_searchbar' );
	$row['flashvar_showeq']           = $this->_post_class->get_post_int( 'flashvar_showeq' );
	$row['flashvar_showicons']        = $this->_post_class->get_post_int( 'flashvar_showicons' );
	$row['flashvar_shownavigation']   = $this->_post_class->get_post_int( 'flashvar_shownavigation' );
	$row['flashvar_showstop']         = $this->_post_class->get_post_int( 'flashvar_showstop' );
	$row['flashvar_showdigits']       = $this->_post_class->get_post_int( 'flashvar_showdigits' );
	$row['flashvar_showdownload']     = $this->_post_class->get_post_int( 'flashvar_showdownload' );
	$row['flashvar_usefullscreen']    = $this->_post_class->get_post_int( 'flashvar_usefullscreen' );
	$row['flashvar_autoscroll']       = $this->_post_class->get_post_int( 'flashvar_autoscroll' );
	$row['flashvar_thumbsinplaylist'] = $this->_post_class->get_post_int( 'flashvar_thumbsinplaylist' );
	$row['flashvar_autostart']        = $this->_post_class->get_post_int( 'flashvar_autostart' );
	$row['flashvar_repeat']           = $this->_post_class->get_post_int( 'flashvar_repeat' );
	$row['flashvar_shuffle']          = $this->_post_class->get_post_int( 'flashvar_shuffle' );
	$row['flashvar_smoothing']        = $this->_post_class->get_post_int( 'flashvar_smoothing' );
	$row['flashvar_enablejs']         = $this->_post_class->get_post_int( 'flashvar_enablejs' );
	$row['flashvar_linkfromdisplay']  = $this->_post_class->get_post_int( 'flashvar_linkfromdisplay' );
	$row['flashvar_link_type']        = $this->_post_class->get_post_int( 'flashvar_link_type' );
	$row['flashvar_bufferlength']     = $this->_post_class->get_post_int( 'flashvar_bufferlength' );
	$row['flashvar_rotatetime']       = $this->_post_class->get_post_int( 'flashvar_rotatetime' );
	$row['flashvar_volume']           = $this->_post_class->get_post_int( 'flashvar_volume' );
	$row['flashvar_linktarget']       = $this->_post_class->get_post_text( 'flashvar_linktarget' );
	$row['flashvar_overstretch']      = $this->_post_class->get_post_text( 'flashvar_overstretch' );
	$row['flashvar_transition']       = $this->_post_class->get_post_text( 'flashvar_transition' );
	$row['flashvar_type']             = $this->_post_class->get_post_text( 'flashvar_type' );
	$row['flashvar_logo']             = $this->_post_class->get_post_text( 'flashvar_logo' );
	$row['flashvar_link']             = $this->_post_class->get_post_text( 'flashvar_link' );
	$row['flashvar_captions']         = $this->_post_class->get_post_text( 'flashvar_captions' );
	$row['flashvar_fallback']         = $this->_post_class->get_post_text( 'flashvar_fallback' );
	$row['flashvar_javascriptid']     = $this->_post_class->get_post_text( 'flashvar_javascriptid' );
	$row['flashvar_recommendations']  = $this->_post_class->get_post_text( 'flashvar_recommendations' );
	$row['flashvar_streamscript']     = $this->_post_class->get_post_text( 'flashvar_streamscript' );
	$row['flashvar_searchlink']       = $this->_post_class->get_post_text( 'flashvar_searchlink' );
	$row['flashvar_audio']            = $this->_post_class->get_post_url( 'flashvar_audio' );

	return $row;
}

function XXX_fetch_logo()
{
	$mimes = $this->_mime_class->get_image_mimes();

	$this->_upload_class->init_media_uploader_full( 
		$this->_TMP_DIR, $mimes, 
		$this->_PLAYERLOGO_SIZE, $this->_PLAYERLOGO_WIDTH, $this->_PLAYERLOGO_HEIGHT, 
		$this->get_normal_exts() );

	$ret = $this->_upload_class->uploader_fetch( $this->_LOGO_FIELD_NAME );
	if ( $ret < 0 ) { 
		echo $this->_upload_class->get_format_error() ;
		return null;	// failed
	}

	$tmp_name = $this->_upload_class->get_uploader_file_name() ;
	if ( $tmp_name ) {
		$tmp_file  = $this->_TMP_DIR  .'/'. $tmp_name;
		$logo_file = $this->_LOGO_DIR .'/'. $tmp_name ;
		$this->rename_file( $tmp_file , $logo_file ) ;
		return $tmp_name ;
	}

	return null ;
}

//---------------------------------------------------------
// restore
//---------------------------------------------------------
function _restore() 
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, $this->get_token_errors() );
	}

   $item_id =  isset($_GET['item_id']) ? intval($_GET['item_id']) : intval($_POST['item_id']);

	$default_rows = $this->_flashvar_handler->get_rows_by_itemid( 0 );
	if ( isset($default_rows[0]) ) {
		$default_row = $default_rows[0];
	} else {
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, 'no default record' );
	}

// Get GLOBAL Defaults from Row 0
	if ( $item_id > 0 ) {
		$target_rows  = $this->_flashvar_handler->get_rows_by_itemid( $item_id );
		if ( isset($target_rows[0]) ) {
			$row = $default_row;
			$row['flashvar_id']      = $target_rows[0]['flashvar_id'] ;
			$row['flashvar_item_id'] = $item_id ;
			$ret = $this->_flashvar_handler->update( $row );
			if ( !$ret ) {
				$msg  = "DB Error <br />\n";
				$msg .= $this->_flashvar_handler->get_format_error();
				redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, $msg );
			}

		} else {
			redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, 'no traget record' );
		}

// default value
	} else {
		$row = $this->_flashvar_handler->create( true );
		$row['flashvar_id']      = $default_row['flashvar_id'] ;
		$row['flashvar_item_id'] = 0 ;
		$ret = $this->_flashvar_handler->update( $row );
		if ( !$ret ) {
			$msg  = "DB Error <br />\n";
			$msg .= $this->_flashvar_handler->get_format_error();
			redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, $msg );
		}
	}

	$url = $this->_THIS_URL.'&amp;op=config&amp;item_id='.$item_id;
	redirect_header( $url, $this->_TIME_SUCCESS, _WEBPHOTO_DBUPDATED );
}

//---------------------------------------------------------
// check
//---------------------------------------------------------
function XXX_check_default()
{
	$rows = $this->_flashvar_handler->get_rows_by_itemid( 0 );
	if ( !isset($rows[0]) ) {
		$row = $this->_flashvar_handler->create( true );
		$this->_flashvar_handler->insert( $row, true );
		echo "created default record <br />\n";
	}

	return;
}

//---------------------------------------------------------
// print form
//---------------------------------------------------------
function _print_form( $mode, $row )
{
	$form =& webphoto_flashvar_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$form->print_form( $mode, $row );
}

// --- class end ---
}

?>