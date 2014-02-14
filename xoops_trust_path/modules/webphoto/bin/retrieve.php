<?php
// $Id: retrieve.php,v 1.17 2011/11/13 05:24:37 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// main/include_submit_base.php
// main/include_mail_recv.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
if( !defined("WEBPHOTO_DIRNAME") ) {
	  define("WEBPHOTO_DIRNAME", $MY_DIRNAME );
}
if( !defined("WEBPHOTO_ROOT_PATH") ) {
	  define("WEBPHOTO_ROOT_PATH", XOOPS_ROOT_PATH.'/modules/'.WEBPHOTO_DIRNAME );
}

if( !defined("WEBPHOTO_COMMOND_MODE") ) {
	  define("WEBPHOTO_COMMOND_MODE", 1 );
}

include_once WEBPHOTO_TRUST_PATH.'/class/d3/optional.php';
include_once WEBPHOTO_TRUST_PATH.'/include/optional.php';

webphoto_include_once( 'preload/debug.php' );

webphoto_include_once( 'class/lib/error.php' );
webphoto_include_once( 'class/lib/handler.php' );

webphoto_include_once( 'class/bin/xoops_database.php' );
webphoto_include_once( 'class/bin/xoops_mysql_database.php' );
webphoto_include_once( 'class/bin/xoops_base.php' );
webphoto_include_once( 'class/bin/permission.php' );
webphoto_include_once( 'class/bin/base.php' );
webphoto_include_once( 'class/bin/config.php' );

webphoto_include_once( 'main/include_submit_base.php' );
webphoto_include_once( 'main/include_mail_recv.php' );

webphoto_include_once( 'class/inc/ini.php' );

webphoto_include_once( 'class/lib/utility.php' );
webphoto_include_once( 'class/lib/tree_handler.php' );

webphoto_include_once( 'class/bin/retrieve.php' );

webphoto_include_once_preload();

//=========================================================
// main
//=========================================================
$manage =& webphoto_bin_retrieve::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );

$manage->main();

?>