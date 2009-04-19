<?php
// $Id: create_file_list.php,v 1.1 2009/04/19 16:07:42 ohwada Exp $

//=========================================================
// webphoto module
// 2009-04-19 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_create_file_list
//=========================================================
class webphoto_admin_create_file_list extends webphoto_base_this
{
	var $_file_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_create_file_list( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_file_class =& webphoto_lib_file_check::getInstance(
		$dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_create_file_list( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// function
//---------------------------------------------------------
function main()
{
	xoops_cp_header();

	echo "<h3>create file check list</h3>\n";

	$this->create_list( 'trust' );
	$this->create_list( 'root' );

	xoops_cp_footer();
	exit();
}

function create_list( $name )
{
	echo "<h4>".$name."</h4>\n";
	$data = $this->_file_class->create_list( $name );
	echo nl2br( $data ).'<br />';
}

function write_file( $name, $data )
{
	$file = XOOPS_TRUST_PATH.'/tmp/'.$this->_TRUST_DIRNAME.'_check_'.$name.'.txt';
	$this->_utility_class->write_file( $file, $data );
}

// --- class end ---
}

?>