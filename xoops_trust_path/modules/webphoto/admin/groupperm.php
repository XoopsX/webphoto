<?php
// $Id: groupperm.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// main
//=========================================================

//---------------------------------------------------------
// mygroupperm
//---------------------------------------------------------
if( ! empty( $_POST['submit'] ) ) {
	$file_trust = WEBPHOTO_TRUST_PATH .'/admin/mygroupperm.php' ;
	$file_root  = WEBPHOTO_ROOT_PATH  .'/admin/mygroupperm.php' ;

	if ( file_exists( $file_root ) ) {
		webphoto_debug_msg( 'include '.$file_root );
		include_once $file_root;

	} elseif( file_exists( $file_trust ) ) {
		webphoto_debug_msg( 'include '.$file_trust );
		include_once $file_trust;

	} else {
		webphoto_debug_msg( 'CANNOT include '.$file );
	}

	webphoto_include_language( 'admin.php' );

	$url = XOOPS_URL .'/modules/'. WEBPHOTO_DIRNAME .'/admin/index.php?fct=groupperm';
	redirect_header( $url , 1 , _AM_WEBPHOTO_GPERMUPDATED );
}

//---------------------------------------------------------
// webphoto
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/lib/mygrouppermform.php' );
webphoto_include_once( 'class/admin/groupperm.php' );

$manager =& webphoto_admin_groupperm::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>