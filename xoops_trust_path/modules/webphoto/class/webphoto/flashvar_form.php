<?php
// $Id: flashvar_form.php,v 1.2 2008/11/11 06:53:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-11-08 K.OHWADA
// _C_WEBPHOTO_UPLOAD_FIELD_PLOGO
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_flashvar_form
//=========================================================

class webphoto_flashvar_form extends webphoto_form_this
{
	var $_flashvar_handler;

	var $_cfg_fsize      = 0 ;
	var $_cfg_logo_width = 0 ;
	var $_cfg_captcha    = null;

	var $_LOGOS_PATH ;
	var $_LOGOS_DIR ;
	var $_LOGOS_URL ;

	var $_THIS_FCT = null;
	var $_THIS_URL = null;

	var $_PLAYERLOGO_SIZE       = _C_WEBPHOTO_PLAYERLOGO_SIZE ;	// 30 KB
	var $_PLAYERLOGO_FIELD_NAME = _C_WEBPHOTO_UPLOAD_FIELD_PLOGO ;

	var $_CAPTCHA_API_FILE = null;
	var $_SIZE_COLOR   = 10;
	var $_SIZE_DISPLAY =  4;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_flashvar_form( $dirname , $trust_dirname )
{
	$this->webphoto_form_this( $dirname , $trust_dirname );
	$this->set_path_color_pickup( $this->_MODULE_URL.'/libs' );

	$this->_flashvar_handler  =& webphoto_flashvar_handler::getInstance( $dirname );

	$uploads_path          = $this->_config_class->get_uploads_path();
	$this->_cfg_fsize      = $this->_config_class->get_by_name( 'fsize' );
	$this->_cfg_logo_width = $this->_config_class->get_by_name( 'logo_width' );

	$this->_LOGOS_PATH = $uploads_path . '/logos' ;
	$this->_LOGOS_DIR  = XOOPS_ROOT_PATH . $this->_LOGOS_PATH ;
	$this->_LOGOS_URL  = XOOPS_URL       . $this->_LOGOS_PATH ;

	$this->_CAPTCHA_API_FILE   = XOOPS_ROOT_PATH.'/modules/captcha/include/api.php';
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_flashvar_form( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function print_form( $mode, $row )
{
	switch ($mode)
	{
		case 'edit';
			$op = 'flashvar';
			$this->_THIS_FCT = 'edit';
			$this->_THIS_URL = $this->_MODULE_URL .'/index.php';
			break;

		case 'admin_item_submit';
			$op = 'flashvar_submit';
			$this->_THIS_FCT = 'item_manager';
			$this->_THIS_URL = $this->_MODULE_URL .'/admin/index.php';
			break;

		case 'admin_item_modify';
			$op = 'flashvar_modify';
			$this->_THIS_FCT = 'item_manager';
			$this->_THIS_URL = $this->_MODULE_URL .'/admin/index.php';
			break;

		case 'admin_modify';
		default:
			$op = 'modify';
			$this->_THIS_FCT = 'flashvar_manager';
			$this->_THIS_URL = $this->_MODULE_URL .'/admin/index.php';
			break;
	}

	$this->set_row( $row );

	$item_id = $row['flashvar_item_id'] ;

	echo $this->build_script_color_pickup();
	echo $this->build_form_upload( 'flashform', $this->_THIS_URL );
	echo $this->build_html_token();

	echo $this->build_input_hidden( 'fct',       $this->_THIS_FCT );
	echo $this->build_input_hidden( 'op',        $op );
	echo $this->build_input_hidden( 'photo_id',  $item_id );
	echo $this->build_input_hidden( 'item_id',   $item_id );

	echo $this->build_input_hidden( 'max_file_size', $this->_cfg_fsize );
	echo $this->build_input_hidden( 'fieldCounter',  $this->_FILED_COUNTER_1 );

	echo $this->build_row_hidden( 'flashvar_id' );
	echo $this->build_row_hidden( 'flashvar_item_id' );

	echo $this->build_table_begin();
	echo $this->build_line_title( $this->get_constant( 'FLASHVARS_FORM' ) );

	echo $this->_build_line_help();

	if ( $this->_is_module_admin ) {
		echo $this->build_row_label( $this->get_constant( 'FLASHVAR_ID' ) ,      'flashvar_id' );
		echo $this->build_row_label( $this->get_constant( 'FLASHVAR_ITEM_ID' ) , 'flashvar_item_id' );
	}

// basics
	echo $this->_build_line_display( 'flashvar_height' );
	echo $this->_build_line_display( 'flashvar_width' );
	echo $this->_build_line_display( 'flashvar_displayheight' );
	echo $this->_build_line_display( 'flashvar_displaywidth' );
	echo $this->_build_line_color( 'flashvar_screencolor' );
	echo $this->_build_line_color( 'flashvar_backcolor' );
	echo $this->_build_line_color( 'flashvar_frontcolor' );
	echo $this->_build_line_color( 'flashvar_lightcolor' );
	echo $this->_build_line_radio_yesno( 'flashvar_image_show' );
//	echo $this->_build_line_text( 'flashvar_file' );
//	echo $this->_build_line_text( 'flashvar_image' );

// Display
	echo $this->_build_line_radio_yesno( 'flashvar_showeq' );
	echo $this->_build_line_radio_yesno( 'flashvar_showicons' );
	echo $this->_build_line_select( 'flashvar_ovrestrech' );
	echo $this->_build_line_select( 'flashvar_transition' );

// Controlbar
	echo $this->_build_line_radio_yesno( 'flashvar_shownavigation' );
	echo $this->_build_line_radio_yesno( 'flashvar_usefullscreen' );	
	echo $this->_build_line_radio_yesno( 'flashvar_showstop' );
	echo $this->_build_line_radio_yesno( 'flashvar_showdigits' );

// Playlist
	echo $this->_build_line_radio_yesno( 'flashvar_autoscroll' );
	echo $this->_build_line_radio_yesno( 'flashvar_thumbsinplaylist' );

// Playback
	echo $this->_build_line_radio_yesno( 'flashvar_repeat' );
	echo $this->_build_line_radio_yesno( 'flashvar_shuffle' );
	echo $this->_build_line_select( 'flashvar_autostart' );
	echo $this->_build_line_text( 'flashvar_audio' );
	echo $this->_build_line_text( 'flashvar_captions' );
	echo $this->_build_line_text( 'flashvar_rotatetime' );
	echo $this->_build_line_text( 'flashvar_volume' );
	echo $this->_build_line_radio_yesno( 'flashvar_smoothing' );
	echo $this->_build_line_text( 'flashvar_bufferlength' );
	echo $this->_build_line_text( 'flashvar_fallback' );
//	echo $this->_build_line_text( 'flashvar_callback' );

// External
	echo $this->_build_line_select( 'flashvar_type' );
	echo $this->_build_line_text( 'flashvar_recommendations' );
	echo $this->_build_line_text( 'flashvar_streamscript' );
//	echo $this->_build_line_radio_yesno( 'flashvar_enablejs' );
//	echo $this->_build_line_text( 'flashvar_javascriptid' );

// link
	echo $this->_build_line_radio_yesno( 'flashvar_showdownload' );
	echo $this->_build_line_radio_yesno( 'flashvar_linkfromdisplay' );
	echo $this->_build_line_select( 'flashvar_link_type' );
	echo $this->_build_line_select( 'flashvar_linktarget' );
//	echo $this->_build_line_text( 'flashvar_link' );

// search bar
	echo $this->_build_line_radio_yesno( 'flashvar_searchbar' );
	echo $this->_build_line_text( 'flashvar_searchlink' );

// logo
	echo $this->_build_line_logo_file();
	echo $this->_build_line_logo_select();

	echo $this->_build_line_captcha();

	echo $this->_build_line_button( $item_id );

	echo $this->build_table_end();

	echo $this->render_hidden_buffers();
	echo $this->build_form_end();

}

function _build_line_text( $name )
{
	$cap   = $this->get_constant( $name );
	$desc  = $this->_get_caption_desc( $name );
	$value = $this->get_row_by_key( $name );
	$ele   = $this->build_input_text( $name, $value );

	return $this->build_line_cap_ele( $cap, $desc, $ele );
}

function _build_line_radio_yesno( $name )
{
	$cap   = $this->get_constant( $name );
	$desc  = $this->_get_caption_desc( $name );
	$value = $this->get_row_by_key( $name );
	$ele   = $this->build_form_radio_yesno( $name, $value );

	return $this->build_line_cap_ele( $cap, $desc, $ele );
}

function _build_line_select( $name, $flag_down=true )
{
	$cap   = $this->get_constant( $name );
	$desc  = $this->_get_caption_desc( $name );
	$value = $this->get_row_by_key( $name );

	switch ( $name )
	{
		case 'flashvar_autostart';
			$options = $this->_flashvar_handler->get_autostart_options();
			break;

		case 'flashvar_linktarget';
			$options = $this->_flashvar_handler->get_linktarget_options();
			break;

		case 'flashvar_overstretch';
			$options = $this->_flashvar_handler->get_overstretch_options();
			break;
	
		case 'flashvar_transition';
			$options = $this->_flashvar_handler->get_transition_options();
			break;

		case 'flashvar_link_type';
			$options = $this->_flashvar_handler->get_link_type_options( $flag_down );
			break;

		default:
			return null;
	}

	$ele = $this->build_form_select( $name, $value, $options, 1 );
	return $this->build_line_cap_ele( $cap, $desc, $ele );
}

function _build_line_display( $name )
{
	$title = $this->get_constant( $name );
	$value = $this->get_row_by_key( $name );
	$ele   = $this->build_input_text( $name, $value, $this->_SIZE_DISPLAY );

	$desc  = $this->_get_caption_desc( $name );
	$desc .= '<br />';
	$desc .= $this->get_constant( 'FLASHVAR_DISPLAY_DEFAULT' );

	return $this->build_line_cap_ele( $title, $desc, $ele );
}

function _build_line_color( $name )
{
	$title = $this->get_constant( $name );
	$value = $this->get_row_by_key( $name );

	$desc  = $this->_get_caption_desc( $name );
	$desc .= '<br />';
	$desc .= $this->get_constant( 'FLASHVAR_COLOR_DEFAULT' );

	$ele  = $this->build_form_color_pickup( 
		$name, $value, $this->get_constant('BUTTON_COLOR_PICKUP'), $this->_SIZE_COLOR ) ;

	return $this->build_line_cap_ele( $title, $desc, $ele );
}

function _build_line_logo_file()
{
	$desc  = $this->get_constant( 'CAP_MAXPIXEL' ) .' ';
	$desc .= $this->_cfg_logo_width .' x ';
	$desc .= $this->_cfg_logo_width .' px';
	$desc .= "<br />\n";
	$desc .= $this->get_constant( 'DSC_PIXCEL_RESIZE' ) .' ';

	$ele = $this->build_form_file( $this->_PLAYERLOGO_FIELD_NAME );
	return $this->build_line_cap_ele( 
		$this->get_constant('FLASHVARS_LOGO_UPLOAD'), $desc, $ele );
}

function _build_line_logo_select()
{
	$desc  = $this->get_constant( 'FLASHVARS_LOGO_DSC' ) ."<br />\n";
	$desc .= $this->_LOGOS_DIR ;

	$ele  = $this->_build_ele_logo_select();
	$ele .= $this->_build_ele_logo_show();

	return $this->build_line_cap_ele( 
		$this->get_constant('FLASHVARS_LOGO_SELECT'), $desc, $ele );
}

function _build_ele_logo_show()
{
	$logo = $this->get_row_by_key( 'flashvar_logo' );
	$logo_file = $this->_LOGOS_DIR .'/'. $logo ;
	$logo_url  = $this->_LOGOS_URL .'/'. $logo ;

	$str = null ;

	if ( $logo && file_exists( $logo_file ) ) {
		$str  = '<div style="padding: 8px;">';
		$str .= '<img src="'. $logo_url .'" name="plogo" id="plogo" alt="plogo" />';
		$str .= '</div>'."\n";
	}

	return $str;
}

function _build_ele_logo_select()
{
// xoops.js showImgSelected(imgId, selectId, imgDir, extra, xoopsUrl)
	$onchange = "showImgSelected('plogo', 'flashvar_logo', '". $this->_LOGOS_PATH ."', '', '". XOOPS_URL ."')" ;
	$extra    = 'onchange="'. $onchange .'"';

	$name  = 'flashvar_logo';
	$value = $this->get_row_by_key( $name );

	$options = XoopsLists::getImgListAsArray( $this->_LOGOS_DIR );
	array_unshift( $options, _NONE );

	return $this->build_form_select( $name, $value, $options, 1, $extra );
}

function _build_line_help()
{
	$ele  = '<a href="http://code.jeroenwijering.com/trac/wiki/Flashvars3" target="_blank">';
	$ele .= $this->get_constant( 'FLASHVARS_LIST' ) ; 
	$ele .= '</a>';
	return $this->build_line_ele( '', $ele );
}

function _build_line_captcha()
{

// show captcha if anoymous user
	if ( $this->_cfg_captcha && !$this->_is_login_user && 
	     file_exists( $this->_CAPTCHA_API_FILE ) ) 
	{
		include_once $this->_CAPTCHA_API_FILE ;
		$captcha_api =& captcha_api::getInstance() ;
		return $captcha_api->make_xoops_form_label() ;
	}
	return null;
}

function _build_line_button( $item_id )
{
	$flashvar_id = $this->get_row_by_key( 'flashvar_id' );

	if ( $flashvar_id > 0 ) {
		$ele  = $this->build_input_submit( 'submit',  _EDIT );
		$ele .= $this->build_input_submit( 'restore', $this->get_constant( 'BUTTON_RESTORE' ) );

	} else {
		$ele  = $this->build_input_submit( 'submit',  _ADD );
	}

	return $this->build_line_buttom( $ele );
}

function _get_caption_desc( $name )
{
	$desc_name = strtoupper( '_WEBPHOTO_'.$name.'_DSC' );
	if ( defined($desc_name) ) {
		return constant( $desc_name ) ;
	}
	return null;
}

// --- class end ---
}

?>