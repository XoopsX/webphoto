<?php
// $Id: retrieve.php,v 1.16 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-24 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// class/bin/config.php
// 2011-05-01 K.OHWADA
// main/include_submit.php
// 2010-04-22 K.OHWADA
// class/edit/item_create.php
// 2010-01-10 K.OHWADA
// class/webphoto/tag.php -> tag_build.php
// 2009-11-11 K.OHWADA
// class/inc/ini.php
// 2009-05-15 K.OHWADA
// Fatal error: Class 'webphoto_edit_small_create' not found 
// 2008-01-25 K.OHWADA
// jodconverter.php
// 2009-01-10 K.OHWADA
// factory_create.php
// 2008-12-05 K.OHWADA
// class/inc/uri.php
// 2008-11-08 K.OHWADA
// imagemagick.php
// 2008-11-03 K.OHWADA
// kind.php
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

webphoto_include_once( 'main/include_submit.php' );
webphoto_include_once( 'main/include_mail.php' );

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