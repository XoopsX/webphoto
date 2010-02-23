<?php
// $Id: player_manager.php,v 1.7 2010/02/23 01:10:59 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-02-20 K.OHWADA
// set_path_color_pickup()
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_flash_player
// 2009-02-20 K.OHWADA
// BUG: not set player_screencolor
// 2009-01-25 K.OHWADA
// build_movie_by_item_row();
// 2009-01-10 K.OHWADA
// $_STYLE_NOT_SET
// 2008-11-16 K.OHWADA
// load_movie() -> build_movie()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_player_manager
//=========================================================
class webphoto_admin_player_manager extends webphoto_base_this
{
	var $_player_handler;
	var $_flashvar_handler;
	var $_playlist_class;
	var $_player_class;

	var $_player_id    = 0 ;
	var $_player_title = null;

 	var $_STYLE_NOT_SET = -1 ;
 
	var $_THIS_FCT = 'player_manager';
	var $_THIS_URL;

	var $_TIME_SUCCESS = 1;
	var $_TIME_FAIL    = 5;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_player_manager( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_flashvar_handler =& webphoto_flashvar_handler::getInstance( 
		$dirname, $trust_dirname );
	$this->_player_handler   =& webphoto_player_handler::getInstance( 
		$dirname, $trust_dirname  );
	$this->_playlist_class   =& webphoto_playlist::getInstance( 
		$dirname, $trust_dirname );
	$this->_player_class     =& webphoto_flash_player::getInstance( 
		$dirname , $trust_dirname  );

	$this->_THIS_URL = $this->_MODULE_URL .'/admin/index.php?fct='.$this->_THIS_FCT;
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_player_manager( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$op = $this->_post_class->get_post_get_text('op');

	$this->_player_id    = $this->_post_class->get_post_get_int(  'player_id' );
	$this->_player_title = $this->_post_class->get_post_text( 'player_title' );

	switch ($op) 
	{
		case 'submit':
		case 'clone':
			$this->_submit();
			break;	

		case 'modify':
			$this->_modify();
			break;

		case 'delete':
			$this->_delete();
			break;

		default:
			break;
	}

	switch ($op) 
	{
		case 'modify_form':
			$this->_modify_form();
			break;

		case 'clone_form':
			$this->_clone_form();
			break;	

		case 'main':
		case 'submit_form':
		default:
			$this->_submit_form();
			break;
	}


}

//---------------------------------------------------------
// menu
//---------------------------------------------------------
function _print_menu()
{
	$player_id = $this->_post_class->get_get_int('player_id');
	$item_id   = $this->_post_class->get_get_int('item_id');

	$onclick = "location='". $this->_THIS_URL ."&amp;item_id=". $item_id ."'";

	echo '<table class="outer" style="font-size: 90%;">';
	echo '<tr><th colspan="6">'. _AM_WEBPHOTO_PLAYER_MANAGER .'</th></tr>'."\n";

	echo '<tr class="head" colspan="6">';
	echo '<td class="even" width="30px"><b>'._WEBPHOTO_PLAYER_ID.'</b></td>';
	echo '<td class="odd"  width="232 px"><b>'._WEBPHOTO_PLAYER_TITLE.'</b></td>';
	echo '<td class="even" width="165px"><b>'._WEBPHOTO_PLAYER_STYLE.'</b></td>';
	echo '<td class="odd"  width="55px"><b>'._WEBPHOTO_PLAYER_WIDTH.'</b></td>';
	echo '<td class="even" width="50px"><b>'._WEBPHOTO_PLAYER_HEIGHT.'</b></td>';
	echo '<td class="odd"  width="195px">';
	echo '<input type="button" value="'._AM_WEBPHOTO_PLAYER_ADD.'" onClick="'.$onclick.'">';
	echo '</td>';
	echo '</tr>'."\n";

	$rows = $this->_player_handler->get_rows_list();
	foreach ( $rows as $row )
	{
		$player_id = $row['player_id'] ; 

		echo '<tr>';
		echo '<td class="even" align="right">'.$player_id.'</td>'."\n";;
		echo '<td class="odd"  >'. $row['player_title'] .'</td>'."\n";;
		echo '<td class="even" >';
		echo $this->_style_to_lang( $row['player_style'] );
		echo '</td>'."\n";
		echo '<td class="odd"  align="right">'. $row['player_width'] .'</td>'."\n";;
		echo '<td class="even" align="right">'. $row['player_height'] .'</td>'."\n";;
		echo '<td class="odd"  >';
		echo $this->_build_button( $item_id, $player_id, 'modify_form',   _EDIT );
		echo $this->_build_button( $item_id, $player_id, 'clone_form', _AM_WEBPHOTO_BUTTON_CLONE );

		if ( $player_id != 1 ) {
			echo $this->_build_button( $item_id, $player_id, 'delete', _DELETE );
		}

		echo '</td></tr>'."\n";
	}

	echo "</table><br />\n";
}

function _style_to_lang( $style )
{
	$arr = $this->_player_handler->get_style_options();
	if ( isset( $arr[ $style ] ) ) {
		return  $arr[ $style ];
	}
	return null;
}

function _build_button( $item_id, $player_id, $op, $value )
{
	$location = $this->_THIS_URL.'&amp;op='.$op.'&amp;player_id='.$player_id.'&amp;item_id='.$item_id;
	$onclick  = "location='".$location."'";
	$str = '<input type="button" value="'.$value.'" onClick="'.$onclick.'">'."\n";
	return $str;
}

//---------------------------------------------------------
// new player editor
//---------------------------------------------------------
function _submit_form()
{
	$row = $this->_player_handler->create( true );
	$this->_print_form_common( 'submit', $row );
}

function _print_form_common( $mode, $row )
{
	xoops_cp_header();
	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'PLAYER_MANAGER' );

	$this->_print_menu();
	$this->_print_player_table( $mode, $row );

	xoops_cp_footer();
}

function _print_player_table( $mode, $player_row )
{
	$form =& webphoto_admin_player_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );
	$form->set_path_color_pickup( $this->_MODULE_URL.'/libs' );

	$style = $this->_post_class->get_get_int( 'style', $this->_STYLE_NOT_SET );
	if ( $style != $this->_STYLE_NOT_SET ) {
		$player_row['player_style'] = $style ;
	}

	$player_id = $player_row['player_id'] ;

	$item_id  = 0 ;
	$item_row = $this->_get_item_row();

	$movie = null;

	if ( is_array($item_row) ) {
		$item_id = $item_row['item_id'];
		$movie   = $this->_player_class->build_movie_by_item_row( $item_row, $player_row );
	}

	$op = $mode.'_form';

	$param_form = array(
		'mode'     => $mode ,
		'item_id'  => $item_id ,
	);



// PLAYER TABLE
	echo $form->build_script_color_pickup();
	echo '<table class="outer">';

// PLAYER FORM	
	echo '<tr><td width="40%">';

	$form->print_form( $player_row, $param_form );

	echo '</td>';

// LINK SELECT
	echo '<td valign="top" align="center">';

	if ( $item_id > 0 ) {
		$this->_print_movie( $op, $item_id, $player_id, $style, $movie );
	} else {
    	echo _AM_WEBPHOTO_PLAYER_NO_ITEM ;      
	}

	echo '</td></tr>'."\n";
	echo '<tr><td colspan="2">';
	echo "<br />\n";
	echo nl2br( $this->sanitize($movie) ) ;   
	echo '</td></tr>'."\n";
	echo '</table>'."\n";
}

function _print_movie( $op, $item_id, $player_id, $style, $movie )
{
	$selbox = $this->_build_item_selbox( $op, $item_id, $player_id, $style );

	echo "<br />\n";
	echo '<h3>'._AM_WEBPHOTO_PLAYER_PREVIEW.'</h3>'."\n";
	echo "<br />\n";
	echo $movie;   
	echo "<br />\n";
	echo _AM_WEBPHOTO_PLAYER_PREVIEW_LINK.' &nbsp; ';
	echo $selbox;
	echo "<br />\n";
	echo _AM_WEBPHOTO_PLAYER_PREVIEW_DSC;
}

function _get_item_row()
{
	$item_id = $this->_post_class->get_get_int('item_id');

	$row = $this->_item_handler->get_row_by_id( $item_id );
	if ( is_array($row) ) {
		return $row ;
	}

	$rows = $this->_item_handler->get_rows_flashplayer( 1 );
	if ( isset( $rows[0] ) && is_array( $rows[0] )) {
		return  $rows[0] ;
	}

	return null ;
}

function _build_item_selbox( $op, $item_id, $player_id, $style )
{
	$rows = $this->_item_handler->get_rows_flashplayer();

	$location = $this->_THIS_URL.'&amp;op='.$op.'&amp;player_id='.$player_id.'&amp;style='.$style.'&amp;item_id=';
	$onchange = "window.location='".$location."'+this.value";

	$text = '<select name="item_id" onchange="'. $onchange .'" >'."\n";
	foreach ( $rows as $row )
	{
		$id    = $row['item_id'];
		$title = $row['item_title'];

		$sel = '';
		if ( $id == $item_id ) {
			$sel = ' selected="selected" ';
		}

		$text .= '<option value="'. $id .'" '. $sel .'>';
		$text .= $this->sanitize($title);
		$text .= "</option>\n";
	}
	$text .=  "</select>\n";
	return $text;
}

//---------------------------------------------------------
// clone player editor
//---------------------------------------------------------
function _clone_form()
{
	$row = $this->_player_handler->get_row_by_id( $this->_player_id );
	if ( !is_array($row) ) {
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, _AM_WEBPHOTO_NOTEXIST );
	}

	$row['player_id'] = 0;
	$this->_print_form_common( 'clone', $row );
}

//---------------------------------------------------------
// modify player
//---------------------------------------------------------
function _modify_form()
{
	$row = $this->_player_handler->get_row_by_id( $this->_player_id );
	if ( !is_array($row) ) {
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, _AM_WEBPHOTO_ERR_NO_RECORD );
	}

	$this->_print_form_common( 'modify', $row );
}

//---------------------------------------------------------
// new player save
//---------------------------------------------------------
function _submit()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, $this->get_token_errors() );
	}

	if (empty($this->_player_title)) {
		redirect_header($this->_THIS_URL, $this->_TIME_FAIL, _WEBPHOTO_ERR_TITLE);
		exit();
	}

// check same title 
	$rows = $this->_player_handler->get_rows_by_title( $this->_player_title, 1 );
	if ( isset( $rows[0]['player_title'] ) ) {
		redirect_header($this->_THIS_URL, $this->_TIME_FAIL, _AM_WEBPHOTO_PLAYER_ERR_EXIST );
		exit();
	}

	$row = $this->_player_handler->create( true );
	$row = $this->_build_row_by_post( $row );

	$newid = $this->_player_handler->insert( $row );
	if ( !$newid ) {
		$msg  = "DB Error <br />\n";
		$msg .= $this->_player_handler->get_format_error();
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, $msg );
	}

	$url = $this->_THIS_URL.'&amp;op=modify_form&amp;player_id='.$newid;
	redirect_header( $url, $this->_TIME_SUCCESS, _AM_WEBPHOTO_PLAYER_ADDED );
}

function _build_row_by_post( $row )
{
	$row['player_title']         = $this->_player_title ;
	$row['player_screencolor']   = $this->_post_class->get_post_text( 'player_screencolor' );
	$row['player_backcolor']     = $this->_post_class->get_post_text( 'player_backcolor' );
	$row['player_frontcolor']    = $this->_post_class->get_post_text( 'player_frontcolor' );
	$row['player_lightcolor']    = $this->_post_class->get_post_text( 'player_lightcolor' );
	$row['player_style']         = $this->_post_class->get_post_int( 'player_style' );
	$row['player_width']         = $this->_post_class->get_post_int( 'player_width' );
	$row['player_height']        = $this->_post_class->get_post_int( 'player_height' );
	$row['player_displaywidth']  = $this->_post_class->get_post_int( 'player_displaywidth' );
	$row['player_displayheight'] = $this->_post_class->get_post_int( 'player_displayheight' );
	$row['player_largecontrols'] = $this->_post_class->get_post_int( 'player_largecontrols' );
	return $row;
}

//---------------------------------------------------------
// modify player save
//---------------------------------------------------------
function _modify()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, $this->get_token_errors() );
	}

	if (empty($this->_player_title)) {
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, _AM_WEBPHOTO_ERR_TITLE);
		exit();
	}

	$row = $this->_player_handler->get_row_by_id( $this->_player_id );
	$row = $this->_build_row_by_post( $row );

	$url = $this->_THIS_URL.'&amp;op=modify_form&amp;player_id='.$this->_player_id;

	$ret = $this->_player_handler->update( $row );
	if ( !$ret ) {
		$msg  = "DB Error <br />\n";
		$msg .= $this->_player_handler->get_format_error();
		redirect_header( $url, $this->_TIME_FAIL, $msg );
	}

	redirect_header( $url, $this->_TIME_SUCCESS, _AM_WEBPHOTO_PLAYER_MODIFIED );	
}

//---------------------------------------------------------
// delete player
//---------------------------------------------------------
function _delete()
{
	$ok = $this->_post_class->get_post_int('ok');

	if ( $ok == 1 ) {
		$this->_delete_excute();

	 } else {
		$this->_delete_comfirm();
	}

}

function _delete_comfirm()
{
	$form_class =& webphoto_admin_player_form::getInstance( 
		$this->_DIRNAME , $this->_TRUST_DIRNAME );

 	$hiddens = array(
		'fct' => $this->_THIS_FCT,
		'op'  => 'delete', 
		'ok'  => 1,
		'player_id' => $this->_player_id,
	);

	$url= $this->_MODULE_URL .'/admin/index.php';

	xoops_cp_header();
	echo $this->_build_bread_crumb();

	echo $form_class->build_form_confirm( 
		$hiddens, $url, _AM_WEBPHOTO_PLAYER_WARNING, _YES, _NO );

	xoops_cp_footer();
	exit();
}

function _delete_excute()
{
	if ( ! $this->check_token() ) {
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, $this->get_token_errors() );
	}

	$ret = $this->_player_handler->delete_by_id( $this->_player_id );
	if ( !$ret ) {
		$msg  = "DB Error <br />\n";
		$msg .= $this->_player_handler->get_format_error();
		redirect_header( $this->_THIS_URL, $this->_TIME_FAIL, $msg );
	}

	redirect_header( $this->_THIS_URL, $this->_TIME_SUCCESS, _AM_WEBPHOTO_PLAYER_DELETED );
	exit();
}

function _build_bread_crumb()
{
	return $this->build_admin_bread_crumb( 
		$this->get_admin_title( 'PLAYER_MANAGER' ), $this->_THIS_URL );
}

// --- class end ---
}

?>