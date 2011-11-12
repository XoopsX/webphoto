<?php
// $Id: update.php,v 1.12 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// Older than v2.00
// 2010-01-10 K.OHWADA
// get_url('210')
// 2009-04-19 K.OHWADA
// create_file_check
// 2009-03-15 K.OHWADA
// v1.30
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_update
//=========================================================
class webphoto_admin_update extends webphoto_base_this
{
	var $_update_check_class;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_update( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );

	$this->_update_check_class  =& webphoto_admin_update_check::getInstance( $dirname , $trust_dirname );

}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_update( $dirname , $trust_dirname );
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
	echo $this->build_admin_title( 'UPDATE' );

	$op = $this->_post_class->get_post_text('op');

	$url_210  = $this->_update_check_class->get_url('210');
	$url_file = $this->_MODULE_URL .'/admin/index.php?fct=create_file_list' ;

	echo $this->_update_check_class->build_msg();
	echo "<br />\n";

	$this->_print_file_check();

	echo ' - <a href="'. $url_210 .'">';
	echo "Update v2.00 to v2.10";
	echo "</a><br /><br />\n";

	echo ' - <a href="'. $url_file .'">';
	echo "Create file check list";
	echo "</a><br /><br />\n";

	echo '- Older than v2.00 <br />';
	echo '  Please download packages from <a href="http://sourceforge.jp/projects/xoops4u/releases/?package_id=7795" target="_blank"><span style="font-size: 120%; font-weight: bold;">sourceforge.jp</span></a> <br />';
	echo '  and version up step by step, <br />';
	echo '  if you use the version older than v2.00. <br />';

	xoops_cp_footer();
	exit();
}


function _print_file_check()
{
	$url = $this->_MODULE_URL.'/admin/index.php?fct=check_file';

	echo '- <a href="'.$url.'">';
	echo _AM_WEBPHOTO_FILE_CHECK;
	echo "</a><br /><br/>\n";
}

// --- class end ---
}

?>