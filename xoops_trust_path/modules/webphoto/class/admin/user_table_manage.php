<?php
// $Id: user_table_manage.php,v 1.2 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_user_handler
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_user_table_manage
//=========================================================
class webphoto_admin_user_table_manage extends webphoto_lib_manage
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_user_table_manage( $dirname , $trust_dirname )
{
	$this->webphoto_lib_manage( $dirname , $trust_dirname );
	$this->set_manage_handler( 
		webphoto_user_handler::getInstance( $dirname, $trust_dirname ) );
	$this->set_manage_title_by_name( 'USER_TABLE_MANAGE' );

	$this->set_manage_list_column_array(
		array( 'user_uid', 'user_email' ) );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_user_table_manage( $dirname , $trust_dirname );
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
		'user_id'             => $this->_post_class->get_post_get_int( 'user_id' ),
		'user_time_create'    => $this->_post_class->get_post_int(   'user_time_create' ),
		'user_time_update'    => $this->_post_class->get_post_int(   'user_time_update' ),
		'user_uid'            => $this->_post_class->get_post_int(   'user_uid' ),
		'user_cat_id'         => $this->_post_class->get_post_int(   'user_cat_id' ),
		'user_email'          => $this->_post_class->get_post_text(  'user_email' ),
		'user_text1'          => $this->_post_class->get_post_text(  'user_text1' ),
		'user_text2'          => $this->_post_class->get_post_text(  'user_text2' ),
		'user_text3'          => $this->_post_class->get_post_text(  'user_text3' ),
		'user_text4'          => $this->_post_class->get_post_text(  'user_text4' ),
		'user_text5'          => $this->_post_class->get_post_text(  'user_text5' ),
	);

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_CAT_TEXT; $i++ ) 
	{
		$name = 'user_text'.$i;
		$row[ $name ] = $this->_post_class->get_post_text( $name );
	}

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
	echo $this->build_comp_text( 'user_time_create' );
	echo $this->build_comp_text( 'user_time_update' );
	echo $this->build_comp_text( 'user_uid' );
	echo $this->build_comp_text( 'user_cat_id' );
	echo $this->build_comp_text( 'user_email' );

	for ( $i=1; $i <= _C_WEBPHOTO_MAX_USER_TEXT; $i++ ) {
		echo $this->build_comp_text( 'user_text'.$i );
	}

	echo $this->build_manage_submit();

	echo "</table></form>\n";
}

// --- class end ---
}

?>