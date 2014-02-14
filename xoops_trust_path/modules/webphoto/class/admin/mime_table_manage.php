<?php
// $Id: mime_table_manage.php,v 1.4 2009/11/29 07:34:21 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// $trust_dirname in webphoto_mime_handler
// 2009-10-25 K.OHWADA
// mime_kind
// 2008-07-01 K.OHWADA
// added mime_ffmpeg
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_mime_table_manage
//=========================================================
class webphoto_admin_mime_table_manage extends webphoto_lib_manage
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_mime_table_manage( $dirname , $trust_dirname )
{
	$this->webphoto_lib_manage( $dirname , $trust_dirname );
	$this->set_manage_handler( 
		webphoto_mime_handler::getInstance( $dirname , $trust_dirname ) );
	$this->set_manage_title_by_name( 'MIME_TABLE_MANAGE' );

	$this->set_manage_list_column_array(
		array( 'mime_ext', 'mime_name' ) );

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_mime_table_manage( $dirname , $trust_dirname );
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
		'mime_id'          => $this->_post_class->get_post_get_int( 'mime_id' ),
		'mime_time_create' => $this->_post_class->get_post_int(  'mime_time_create' ),
		'mime_time_update' => $this->_post_class->get_post_int(  'mime_time_update' ),
		'mime_ext'         => $this->_post_class->get_post_text( 'mime_ext' ),
		'mime_medium'      => $this->_post_class->get_post_text( 'mime_medium' ),
		'mime_type'        => $this->_post_class->get_post_text( 'mime_type' ),
		'mime_name'        => $this->_post_class->get_post_text( 'mime_name' ),
		'mime_perms'       => $this->_post_class->get_post_text( 'mime_perms' ),
		'mime_ffmpeg'      => $this->_post_class->get_post_text( 'mime_ffmpeg' ),
		'mime_kind'        => $this->_post_class->get_post_int(  'mime_kind' ),
		'mime_option'      => $this->_post_class->get_post_text( 'mime_option' ),
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
	echo $this->build_manage_header();

	echo $this->build_manage_id();
	echo $this->build_comp_text( 'mime_time_create' );
	echo $this->build_comp_text( 'mime_time_update' );
	echo $this->build_comp_text( 'mime_ext' );
	echo $this->build_comp_text( 'mime_medium' );
	echo $this->build_comp_text( 'mime_type' );
	echo $this->build_comp_text( 'mime_name' );
	echo $this->build_comp_text( 'mime_perms' );
	echo $this->build_comp_text( 'mime_ffmpeg' );
	echo $this->build_comp_text( 'mime_kind' );
	echo $this->build_comp_text( 'mime_option' );

	echo $this->build_manage_submit();

	echo "</table></form>\n";
}

// --- class end ---
}

?>