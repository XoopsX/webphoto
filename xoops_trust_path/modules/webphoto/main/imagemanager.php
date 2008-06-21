<?php
// $Id: imagemanager.php,v 1.1 2008/06/21 12:22:14 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

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
if( !defined("WEBPHOTO_DIRNAME") ) {
	  define("WEBPHOTO_DIRNAME", $MY_DIRNAME );
}
if( !defined("WEBPHOTO_ROOT_PATH") ) {
	  define("WEBPHOTO_ROOT_PATH", XOOPS_ROOT_PATH.'/modules/'.WEBPHOTO_DIRNAME );
}

include_once WEBPHOTO_TRUST_PATH.'/class/d3/optional.php';
include_once WEBPHOTO_TRUST_PATH.'/include/optional.php';

webphoto_include_once( 'preload/debug.php' );
webphoto_include_once( 'include/constants.php' );
webphoto_include_once( 'class/inc/handler.php' );
webphoto_include_once( 'class/inc/config.php' );
webphoto_include_once( 'class/inc/group_permission.php' );
webphoto_include_once( 'class/main/imagemanager.php' );
webphoto_include_language( 'main.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_imagemanager::getInstance( WEBPHOTO_DIRNAME );

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