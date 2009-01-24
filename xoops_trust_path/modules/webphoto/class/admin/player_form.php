<?php
// $Id: player_form.php,v 1.2 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// webphoto_form_this -> webphoto_edit_form
// $param['style'] -> $row['player_style']
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_player_form
//=========================================================
class webphoto_admin_player_form extends webphoto_edit_form
{
	var $_player_handler;

	var $_THIS_FCT = 'player_manager';
	var $_THIS_URL;

	var $_SIZE_TITLE   = 20;
	var $_SIZE_COLOR   = 10;
	var $_SIZE_DISPLAY =  4;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_player_form( $dirname , $trust_dirname )
{
	$this->webphoto_edit_form( $dirname , $trust_dirname );
	$this->set_path_color_pickup( $this->_MODULE_URL.'/libs' );

	$this->_player_handler  =& webphoto_player_handler::getInstance( $dirname );

	$this->_THIS_URL = $this->_MODULE_URL .'/admin/index.php?fct='.$this->_THIS_FCT;
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_player_form( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function print_form( $row, $param )
{
	$mode         = $param['mode'] ;
	$item_id      = $param['item_id'] ;
	$player_style = $row['player_style'] ;

	switch ( $mode )
	{
		case 'clone':
			$title  = _AM_WEBPHOTO_PLAYER_CLONE;
			$submit = _ADD;
			break;

		case 'modify':
			$title  = _AM_WEBPHOTO_PLAYER_MOD;
			$submit = _EDIT;
			break;

		case 'submit':
		default:
			$mode   = 'submit';
			$title  = _AM_WEBPHOTO_PLAYER_ADD;
			$submit = _ADD;
			break;
	}

	$op      = $mode ;
	$op_form = $mode.'_form';

	$this->set_row( $row );

	echo $this->build_form_tag( 'playerform', null, 'post', 'enctype="multipart/form-data"' );
	echo $this->build_html_token();

	echo $this->build_input_hidden( 'fct',       $this->_THIS_FCT );
	echo $this->build_input_hidden( 'op',        $op );
	echo $this->build_input_hidden( 'player_id', $row['player_id'] );
	echo $this->build_input_hidden( 'item_id',   $item_id );

	echo $this->build_table_begin();
	echo $this->build_line_title( $title );

	echo $this->build_row_label( _WEBPHOTO_PLAYER_ID, 'player_id' );

	echo $this->build_line_ele( _WEBPHOTO_PLAYER_STYLE, 
		$this->_build_ele_style( $op_form, $player_style ) );

	echo $this->build_row_text( _WEBPHOTO_PLAYER_TITLE, 'player_title', $this->_SIZE_TITLE );

// color
	if ( $this->_is_color_style( $player_style ) ) {
		echo $this->_build_line_color( 'player_screencolor' );
		echo $this->_build_line_color( 'player_backcolor' );
		echo $this->_build_line_color( 'player_frontcolor' );
		echo $this->_build_line_color( 'player_lightcolor' );

// mono
	} else {
		echo $this->build_row_hidden( 'player_screencolor'    );
		echo $this->build_row_hidden( 'player_backcolor'  );
		echo $this->build_row_hidden( 'player_frontcolor' );
		echo $this->build_row_hidden( 'player_lightcolor' );
	}

	echo $this->_build_line_display( 'player_height' );
	echo $this->_build_line_display( 'player_width' );
	echo $this->_build_line_display( 'player_displayheight' );
	echo $this->_build_line_display( 'player_displaywidth' );

	echo $this->build_line_submit( 'submit', $submit);

	echo $this->build_table_end();
	echo $this->build_form_end();
}

function _is_color_style( $style )
{
	if ( $style == _C_WEBPHOTO_PLAYER_STYLE_PLAYER ) {
		return true;
	}
	if ( $style == _C_WEBPHOTO_PLAYER_STYLE_PAGE ) {
		return true;
	}
	return false;
}

function _build_ele_style( $op_form, $style )
{
	$player_id = $this->get_row_by_key( 'player_id' );
	$options   = $this->_player_handler->get_style_options();
	$extra     = $this->_build_style_extra( $op_form, $player_id );

	return $this->build_form_select( 'player_style', $style, $options, 1, $extra );
}

function _build_style_extra( $op, $player_id )
{
	$location = $this->_THIS_URL.'&amp;op='. $op .'&amp;player_id='. $player_id .'&amp;style=';
	$onchange = "window.location='". $location ."'+this.value";
	$str = 'onchange="'. $onchange .'"';
	return $str;
}

function _build_line_display( $name )
{
	$title = $this->get_constant( $name );
	$value = $this->get_row_by_key( $name );
	$ele   = $this->build_input_text( $name, $value, $this->_SIZE_DISPLAY );
	$desc  = $this->_get_caption_desc( $name );

	return $this->build_line_cap_ele( $title, $desc, $ele );
}

function _build_line_color( $name )
{
	$title = $this->get_constant( $name );
	$value = $this->get_row_by_key( $name );
	$desc  = $this->_get_caption_desc( $name );

	$ele  = $this->build_form_color_pickup( 
		$name, $value, $this->get_constant('BUTTON_COLOR_PICKUP'), $this->_SIZE_COLOR ) ;

	return $this->build_line_cap_ele( $title, $desc, $ele );
}

function _get_caption_desc( $name )
{
	$desc_name = str_replace( 'player_', '', $name );
	return $this->get_constant('FLASHVAR_'. $desc_name .'_DSC') ;
}

// --- class end ---
}

?>