<?php
// $Id: text.php,v 1.1 2008/08/08 04:39:14 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_text
//=========================================================
class webphoto_admin_text extends webphoto_base_this
{
//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_text( $dirname , $trust_dirname )
{
	$this->webphoto_base_this( $dirname , $trust_dirname );
}

function &getInstance( $dirname , $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_text( $dirname , $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	$name = $this->_post_class->get_get_text('name');
	$file = $this->_TMP_DIR .'/'. $name ;

	if ( empty($name) || !is_file($file) ) {
		exit();
	}

	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
	header('Cache-Control: no-store, no-cache, max-age=1, s-maxage=1, must-revalidate, post-check=0, pre-check=0');
	header('Content-type: text/plain');

	readfile( $file ) ;

}

// --- class end ---
}

?>