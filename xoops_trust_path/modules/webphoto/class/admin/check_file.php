<?php
// $Id: check_file.php,v 1.1 2009/12/24 06:33:24 ohwada Exp $

//=========================================================
// webphoto module
// 2009-12-06 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_check_file
//=========================================================
class webphoto_admin_check_file extends webphoto_base_this
{
	var $_file_check_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_check_file( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_file_check_class =& webphoto_lib_file_check::getInstance(
		$dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_check_file( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	xoops_cp_header();

	echo $this->build_admin_menu();
	echo "<h3>". _AM_WEBPHOTO_FILE_CHECK ."</h3>\n";
	echo _AM_WEBPHOTO_FILE_CHECK_DSC ."<br /><br />\n";

	$this->_print_file_check();

	xoops_cp_footer();
}

//---------------------------------------------------------
// check file
//---------------------------------------------------------
function _print_file_check()
{
	$flag_error = false;

	$msg = $this->_file_check_class->check_list( 'trust' );
	if ( $msg ) {
		$flag_error = true;
		echo $this->highlight( $msg );
	}

	$msg = $this->_file_check_class->check_list( 'root' );
	if ( $msg ) {
		$flag_error = true;
		echo $this->highlight( $msg );
	}

	if ( !$flag_error ) {
		echo "OK <br />\n";
	}
	echo "<br/>\n";
}

// --- class end ---
}

?>