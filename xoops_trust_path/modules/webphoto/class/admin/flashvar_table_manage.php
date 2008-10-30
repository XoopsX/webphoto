<?php
// $Id: flashvar_table_manage.php,v 1.1 2008/10/30 00:25:51 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_flashvar_table_manage
//=========================================================
class webphoto_admin_flashvar_table_manage extends webphoto_lib_manage
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_flashvar_table_manage( $dirname , $trust_dirname )
{
	$this->webphoto_lib_manage( $dirname , $trust_dirname );
	$this->set_manage_handler( webphoto_flashvar_handler::getInstance( $dirname ) );
	$this->set_manage_title_by_name( 'FLASHVAR_TABLE_MANAGE' );

	$this->set_manage_list_column_array(
		array( 'flashvar_item_id' ) );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_flashvar_table_manage( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$this->_main();
}

//=========================================================
// override for caller
//=========================================================
function _build_row_by_post()
{
	$row = array(
		'flashvar_id'               => $this->_post_class->get_post_get_int( 'flashvar_id' ),
		'flashvar_time_create'      => $this->_post_class->get_post_int(  'flashvar_time_create' ),
		'flashvar_time_update'      => $this->_post_class->get_post_int(  'flashvar_time_update' ),
		'flashvar_item_id'          => $this->_post_class->get_post_int(  'flashvar_item_id' ),
		'flashvar_width'            => $this->_post_class->get_post_int(  'flashvar_width' ),
		'flashvar_height'           => $this->_post_class->get_post_int(  'flashvar_height' ),
		'flashvar_displaywidth'     => $this->_post_class->get_post_int(  'flashvar_displaywidth' ),
		'flashvar_displayheight'    => $this->_post_class->get_post_int(  'flashvar_displayheight' ),
		'flashvar_image_show'       => $this->_post_class->get_post_int(  'flashvar_image_show' ),
		'flashvar_searchbar'        => $this->_post_class->get_post_int(  'flashvar_searchbar' ),
		'flashvar_showeq'           => $this->_post_class->get_post_int(  'flashvar_showeq' ),
		'flashvar_showicons'        => $this->_post_class->get_post_int(  'flashvar_showicons' ),
		'flashvar_shownavigation'   => $this->_post_class->get_post_int(  'flashvar_shownavigation' ),
		'flashvar_showstop'         => $this->_post_class->get_post_int(  'flashvar_showstop' ),
		'flashvar_showdigits'       => $this->_post_class->get_post_int(  'flashvar_showdigits' ),
		'flashvar_showdownload'     => $this->_post_class->get_post_int(  'flashvar_showdownload' ),
		'flashvar_usefullscreen'    => $this->_post_class->get_post_int(  'flashvar_usefullscreen' ),
		'flashvar_autoscroll'       => $this->_post_class->get_post_int(  'flashvar_autoscroll' ),
		'flashvar_thumbsinplaylist' => $this->_post_class->get_post_int(  'flashvar_thumbsinplaylist' ),
		'flashvar_autostart'        => $this->_post_class->get_post_int(  'flashvar_autostart' ),
		'flashvar_repeat'           => $this->_post_class->get_post_int(  'flashvar_repeat' ),
		'flashvar_shuffle'          => $this->_post_class->get_post_int(  'flashvar_shuffle' ),
		'flashvar_smoothing'        => $this->_post_class->get_post_int(  'flashvar_smoothing' ),
		'flashvar_enablejs'         => $this->_post_class->get_post_int(  'flashvar_enablejs' ),
		'flashvar_linkfromdisplay'  => $this->_post_class->get_post_int(  'flashvar_linkfromdisplay' ),
		'flashvar_link_type'        => $this->_post_class->get_post_int(  'flashvar_link_type' ),
		'flashvar_bufferlength'     => $this->_post_class->get_post_int(  'flashvar_bufferlength' ),
		'flashvar_rotatetime'       => $this->_post_class->get_post_int(  'flashvar_rotatetime' ),
		'flashvar_volume'           => $this->_post_class->get_post_int(  'flashvar_volume' ),
		'flashvar_linktarget'       => $this->_post_class->get_post_text( 'flashvar_linktarget' ),
		'flashvar_overstretch'      => $this->_post_class->get_post_text( 'flashvar_overstretch' ),
		'flashvar_transition'       => $this->_post_class->get_post_text( 'flashvar_transition' ),
		'flashvar_screencolor'      => $this->_post_class->get_post_text( 'flashvar_screencolor' ),
		'flashvar_backcolor'        => $this->_post_class->get_post_text( 'flashvar_backcolor' ),
		'flashvar_frontcolor'       => $this->_post_class->get_post_text( 'flashvar_frontcolor' ),
		'flashvar_lightcolor'       => $this->_post_class->get_post_text( 'flashvar_lightcolor' ),
		'flashvar_type'             => $this->_post_class->get_post_text( 'flashvar_type' ),
		'flashvar_file'             => $this->_post_class->get_post_text( 'flashvar_file' ),
		'flashvar_image'            => $this->_post_class->get_post_text( 'flashvar_image' ),
		'flashvar_logo'             => $this->_post_class->get_post_text( 'flashvar_logo' ),
		'flashvar_link'             => $this->_post_class->get_post_text( 'flashvar_link' ),
		'flashvar_audio'            => $this->_post_class->get_post_text( 'flashvar_audio' ),
		'flashvar_captions'         => $this->_post_class->get_post_text( 'flashvar_captions' ),
		'flashvar_fallback'         => $this->_post_class->get_post_text( 'flashvar_fallback' ),
		'flashvar_callback'         => $this->_post_class->get_post_text( 'flashvar_callback' ),
		'flashvar_javascriptid'     => $this->_post_class->get_post_text( 'flashvar_javascriptid' ),
		'flashvar_recommendations'  => $this->_post_class->get_post_text( 'flashvar_recommendations' ),
		'flashvar_streamscript'     => $this->_post_class->get_post_text( 'flashvar_streamscript' ),
		'flashvar_searchlink'       => $this->_post_class->get_post_text( 'flashvar_searchlink' ),
	);
	return $row;
}

//---------------------------------------------------------
// form
//---------------------------------------------------------
function _print_form( $row )
{
	echo $this->build_manage_form_begin( $row );

	echo $this->build_table_begin();
	echo $this->build_manage_header( );

	echo $this->build_manage_id();
	echo $this->build_comp_text( 'flashvar_time_create' );
	echo $this->build_comp_text( 'flashvar_time_update' );
	echo $this->_build_row_item_id() ;
	echo $this->build_comp_text( 'flashvar_width' );
	echo $this->build_comp_text( 'flashvar_height' );
	echo $this->build_comp_text( 'flashvar_displaywidth' );
	echo $this->build_comp_text( 'flashvar_displayheight' );
	echo $this->build_comp_text( 'flashvar_image_show' );
	echo $this->build_comp_text( 'flashvar_image' );
	echo $this->build_comp_text( 'flashvar_file' );
	echo $this->build_comp_text( 'flashvar_screencolor' );
	echo $this->build_comp_text( 'flashvar_backcolor' );
	echo $this->build_comp_text( 'flashvar_frontcolor' );
	echo $this->build_comp_text( 'flashvar_lightcolor' );
	echo $this->build_comp_text( 'flashvar_searchbar' );
	echo $this->build_comp_text( 'flashvar_showeq' );
	echo $this->build_comp_text( 'flashvar_showicons' );
	echo $this->build_comp_text( 'flashvar_shownavigation' );
	echo $this->build_comp_text( 'flashvar_showstop' );
	echo $this->build_comp_text( 'flashvar_showdigits' );
	echo $this->build_comp_text( 'flashvar_showdownload' );
	echo $this->build_comp_text( 'flashvar_usefullscreen' );
	echo $this->build_comp_text( 'flashvar_autoscroll' );
	echo $this->build_comp_text( 'flashvar_thumbsinplaylist' );
	echo $this->build_comp_text( 'flashvar_autostart' );
	echo $this->build_comp_text( 'flashvar_repeat' );
	echo $this->build_comp_text( 'flashvar_shuffle' );
	echo $this->build_comp_text( 'flashvar_smoothing' );
	echo $this->build_comp_text( 'flashvar_enablejs' );
	echo $this->build_comp_text( 'flashvar_linkfromdisplay' );
	echo $this->build_comp_text( 'flashvar_bufferlength' );
	echo $this->build_comp_text( 'flashvar_rotatetime' );
	echo $this->build_comp_text( 'flashvar_volume' );
	echo $this->build_comp_text( 'flashvar_overstretch' );
	echo $this->build_comp_text( 'flashvar_transition' );
	echo $this->build_comp_text( 'flashvar_type' );
	echo $this->build_comp_text( 'flashvar_link_type' );
	echo $this->build_comp_text( 'flashvar_link' );
	echo $this->build_comp_text( 'flashvar_linktarget' );
	echo $this->build_comp_text( 'flashvar_logo' );
	echo $this->build_comp_text( 'flashvar_audio' );
	echo $this->build_comp_text( 'flashvar_captions' );
	echo $this->build_comp_text( 'flashvar_fallback' );
	echo $this->build_comp_text( 'flashvar_callback' );
	echo $this->build_comp_text( 'flashvar_javascriptid' );
	echo $this->build_comp_text( 'flashvar_recommendations' );
	echo $this->build_comp_text( 'flashvar_streamscript' );
	echo $this->build_comp_text( 'flashvar_searchlink' );

	echo $this->build_manage_submit();

	echo "</table></form>\n";
}

function _build_row_item_id()
{
	$name  = 'file_item_id' ;
	$value = intval( $this->get_row_by_key( $name ) );
	$ele   = $this->build_input_text( $name, $value );
	if ( $value > 0 ) {
		$url  = $this->_MODULE_URL.'/admin/index.php?fct=item_table_manage&op=form&id='.$value;
		$ele .= "<br />\n";
		$ele .= '<a href="'. $url .'">item table: '. $value .'</a>';
	}
	return $this->build_line_ele( $this->get_constant( $name ), $ele );
}

// --- class end ---
}

?>