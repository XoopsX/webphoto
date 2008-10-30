<?php
// $Id: update.php,v 1.5 2008/10/30 00:22:49 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_update
//=========================================================
class webphoto_admin_update extends webphoto_base_this
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_update( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );
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

	$url_040 = $this->_MODULE_URL .'/admin/index.php?fct=update_040' ;
	$url_050 = $this->_MODULE_URL .'/admin/index.php?fct=update_050' ;

	echo ' - <a href="'. $url_040 .'">';
	echo "Update v0.30 to v0.40";
	echo "</a><br /><br />\n";

	echo ' - <a href="'. $url_050 .'">';
	echo "Update v0.40 to v0.50";
	echo "</a><br /><br />\n";

	xoops_cp_footer();
	exit();
}

// --- class end ---
}

?>