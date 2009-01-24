<?php
// $Id: header_file.php,v 1.2 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-12-12 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-10 K.OHWADA
// msg.php
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

include_once WEBPHOTO_TRUST_PATH.'/class/d3/optional.php';
include_once WEBPHOTO_TRUST_PATH.'/include/optional.php';

webphoto_include_once( 'include/constants.php' );
webphoto_include_once( 'class/xoops/base.php' );
webphoto_include_once( 'class/d3/language.php' );
webphoto_include_once( 'class/lib/error.php' );
webphoto_include_once( 'class/lib/msg.php' );
webphoto_include_once( 'class/lib/handler.php' );
webphoto_include_once( 'class/lib/tree_handler.php' );
webphoto_include_once( 'class/lib/utility.php' );
webphoto_include_once( 'class/lib/post.php' );
webphoto_include_once( 'class/lib/base.php' );
webphoto_include_once( 'class/lib/multibyte.php' );
webphoto_include_once( 'class/handler/item_handler.php' );
webphoto_include_once( 'class/handler/cat_handler.php' );
webphoto_include_once( 'class/handler/file_handler.php' );
webphoto_include_once( 'class/webphoto/kind.php' );
webphoto_include_once( 'class/webphoto/config.php' );
webphoto_include_once( 'class/webphoto/item_public.php' );
webphoto_include_once( 'class/webphoto/file_read.php' );

webphoto_include_language( 'main.php' );

?>