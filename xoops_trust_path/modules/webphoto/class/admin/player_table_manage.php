<?php
// $Id: player_table_manage.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_player_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_player_table_manage
//=========================================================
class webphoto_admin_player_table_manage extends webphoto_lib_manage
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_player_table_manage( $dirname , $trust_dirname )
{
	$this->webphoto_lib_manage( $dirname , $trust_dirname );
	$this->set_manage_handler( 
		webphoto_player_handler::getInstance( $dirname, $trust_dirname ) );
	$this->set_manage_title_by_name( 'PLAYER_TABLE_MANAGE' );

	$this->set_manage_list_column_array(
		array( 'player_title', 'player_width', 'player_height' ) );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_player_table_manage( $dirname , $trust_dirname );
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
		'player_id'            => $this->_post_class->get_post_get_int( 'player_id' ),
		'player_time_create'   => $this->_post_class->get_post_int(  'player_time_create' ),
		'player_time_update'   => $this->_post_class->get_post_int(  'player_time_update' ),
		'player_title'         => $this->_post_class->get_post_text( 'player_title' ),
		'player_style'         => $this->_post_class->get_post_int(  'player_style' ),
		'player_width'         => $this->_post_class->get_post_int(  'player_width' ),
		'player_height'        => $this->_post_class->get_post_int(  'player_height' ),
		'player_displaywidth'  => $this->_post_class->get_post_int(  'player_displaywidth' ),
		'player_displayheight' => $this->_post_class->get_post_int(  'player_displayheight' ),
		'player_screencolor'   => $this->_post_class->get_post_text( 'player_screencolor' ),
		'player_backcolor'     => $this->_post_class->get_post_text( 'player_backcolor' ),
		'player_frontcolor'    => $this->_post_class->get_post_text( 'player_frontcolor' ),
		'player_lightcolor'    => $this->_post_class->get_post_text( 'player_lightcolor' ),
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
	echo $this->build_comp_text( 'player_time_create' );
	echo $this->build_comp_text( 'player_time_update' );
	echo $this->build_comp_text( 'player_title' );
	echo $this->build_comp_text( 'player_style' );
	echo $this->build_comp_text( 'player_width' );
	echo $this->build_comp_text( 'player_height' );
	echo $this->build_comp_text( 'player_displaywidth' );
	echo $this->build_comp_text( 'player_displayheight' );
	echo $this->build_comp_text( 'player_screencolor' );
	echo $this->build_comp_text( 'player_backcolor' );
	echo $this->build_comp_text( 'player_frontcolor' );
	echo $this->build_comp_text( 'player_lightcolor' );

	echo $this->build_manage_submit();

	echo "</table></form>\n";
}

// --- class end ---
}

?>