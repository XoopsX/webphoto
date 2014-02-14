<?php
// $Id: imagemanager.php,v 1.7 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// remove class/inc/config.php
// 2009-11-11 K.OHWADA
// /main/header_inc_handler.php
// 2009-10-20 K.OHWADA
// Fatal error: Class 'webphoto_lib_multibyte' not found
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// public.php
// 2008-07-01 K.OHWADA
// added class/xoops/base.php etc
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// xoops system files
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH."/class/xoopstree.php" ;
include_once XOOPS_ROOT_PATH.'/class/pagenav.php' ;
include_once XOOPS_ROOT_PATH.'/class/template.php' ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
include_once WEBPHOTO_TRUST_PATH.'/main/header_inc_handler.php';

webphoto_include_once( 'class/lib/multibyte.php' );
webphoto_include_once( 'class/xoops/base.php' );
webphoto_include_once( 'class/inc/group_permission.php' );
webphoto_include_once( 'class/inc/catlist.php' );
webphoto_include_once( 'class/inc/public.php' );
webphoto_include_once( 'class/main/imagemanager.php' );

webphoto_include_language( 'main.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_imagemanager::getSingleton( WEBPHOTO_DIRNAME, WEBPHOTO_TRUST_DIRNAME );

// exit if error
$manage->check();

list( $param, $photos ) = $manage->main();

$xoopsTpl = new XoopsTpl();
$xoopsTpl->assign( $param ) ;

if ( is_array($photos) && count($photos) ) {
	foreach( $photos as $photo ) {
		$xoopsTpl->append( 'photos' , $photo );
	}
}

$xoopsTpl->display( $manage->get_template() ) ;
exit() ;

?>