<?php
// $Id: player_form.php,v 1.4 2010/02/23 01:10:59 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-02-20 K.OHWADA
// build_form_by_row()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_player_handler
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

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_player_form( $dirname , $trust_dirname )
{
	$this->webphoto_edit_form( $dirname , $trust_dirname );

	$this->_player_handler  =& webphoto_player_handler::getInstance( 
		$dirname , $trust_dirname );
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
	$template = 'db:'. $this->_DIRNAME .'_form_admin_player.html';

	$arr = array_merge( 
		$this->build_form_base_param(),
		$this->build_form_by_row( $row, $param ),
		$this->build_item_row( $row )
	);

	$tpl = new XoopsTpl() ;
	$tpl->assign( $arr ) ;
	echo $tpl->fetch( $template ) ;
}

function build_form_by_row( $row, $param )
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

	$show_color_style = $this->show_color_style( $player_style );

	$arr = array( 
		'title'    => $title ,
		'op'       => $mode ,
		'submit'   => $submit ,
		'item_id'  => $item_id ,
		'show_color_style'        =>  $show_color_style ,
		'show_color_style_hidden' => !$show_color_style ,
		'op_player_style'         => $mode.'_form' ,
		'player_style_options'    => $this->player_style_options( $player_style ),
	);
	return $arr;
}

function player_style_options( $value )
{
	$options   = $this->_player_handler->get_style_options();
	return $this->build_form_options( $value, $options );
}

function show_color_style( $style )
{
	if ( $style == _C_WEBPHOTO_PLAYER_STYLE_PLAYER ) {
		return true;
	}
	if ( $style == _C_WEBPHOTO_PLAYER_STYLE_PAGE ) {
		return true;
	}
	return false;
}

// --- class end ---
}

?>