<?php
// $Id: header_item_handler.php,v 1.3 2011/12/26 06:51:31 ohwada Exp $

//=========================================================
// webphoto module
// 2009-11-11 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// class/lib/mysql_utility.php
// 2011-11-11 K.OHWADA
// class/inc/config.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto
//---------------------------------------------------------
if( !defined("WEBPHOTO_DIRNAME") ) {
	  define("WEBPHOTO_DIRNAME", $MY_DIRNAME );
}
if( !defined("WEBPHOTO_ROOT_PATH") ) {
	  define("WEBPHOTO_ROOT_PATH", XOOPS_ROOT_PATH.'/modules/'.WEBPHOTO_DIRNAME );
}

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
include_once WEBPHOTO_TRUST_PATH.'/class/d3/optional.php';
include_once WEBPHOTO_TRUST_PATH.'/include/optional.php';

webphoto_include_once( 'include/constants.php' );
webphoto_include_once( 'class/lib/error.php' );
webphoto_include_once( 'class/lib/utility.php' );
webphoto_include_once( 'class/lib/mysql_utility.php' );
webphoto_include_once( 'class/lib/handler.php' );
webphoto_include_once( 'class/lib/tree_handler.php' );
webphoto_include_once( 'class/inc/ini.php' );
webphoto_include_once( 'class/inc/xoops_config.php' );
webphoto_include_once( 'class/inc/config.php' );
webphoto_include_once( 'class/handler/base_ini.php' );
webphoto_include_once( 'class/handler/item_handler.php' );

?>