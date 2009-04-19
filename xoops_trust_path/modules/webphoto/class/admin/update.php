<?php
// $Id: update.php,v 1.9 2009/04/19 16:07:42 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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

	$url_040  = $this->_update_check_class->get_url('040');
	$url_050  = $this->_update_check_class->get_url('050');
	$url_130  = $this->_update_check_class->get_url('130');
	$url_file = $this->_MODULE_URL .'/admin/index.php?fct=create_file_list' ;

	echo $this->_update_check_class->build_msg();
	echo "<br />\n";

	echo ' - <a href="'. $url_130 .'">';
	echo "Update v1.20 to v1.30";
	echo "</a><br /><br />\n";

	echo ' - <a href="'. $url_050 .'">';
	echo "Update v0.40 to v0.50";
	echo "</a><br /><br />\n";

	echo ' - <a href="'. $url_040 .'">';
	echo "Update v0.30 to v0.40";
	echo "</a><br /><br />\n";

	echo ' - <a href="'. $url_file .'">';
	echo "Create file check list";
	echo "</a><br /><br />\n";

	xoops_cp_footer();
	exit();
}

// --- class end ---
}

?>