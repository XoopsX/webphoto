<?php
// $Id: rss.php,v 1.8 2009/11/29 07:34:23 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// call form XOOPS_ROOT_PATH/modules/xxx/rss.php
//---------------------------------------------------------

//---------------------------------------------------------
// change log
// 2009-11-11 K.OHWADA
// main/header_item_handler.php
// 2009-03-01 K.OHWADA
// class/webphoto/rss.php
// 2008-12-12 K.OHWADA
// photo_public.php
// 2008-12-09 K.OHWADA
// class/inc/uri.php
// 2008-10-01 K.OHWADA
// added xml.php
// 2008-08-24 K.OHWADA
// added item_handler.php
// 2008-07-01 K.OHWADA
// added uri.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// xoops system files
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH.'/class/xoopstree.php';
include_once XOOPS_ROOT_PATH.'/class/template.php';

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header_item_handler.php' );

webphoto_include_once( 'class/xoops/base.php' );

webphoto_include_once( 'class/inc/handler.php' );
webphoto_include_once( 'class/inc/base_ini.php' );
webphoto_include_once( 'class/inc/config.php' );
webphoto_include_once( 'class/inc/uri.php' );
webphoto_include_once( 'class/inc/catlist.php' );
webphoto_include_once( 'class/inc/tagcloud.php' );

webphoto_include_once( 'class/d3/language.php' );

webphoto_include_once( 'class/lib/multibyte.php' );
webphoto_include_once( 'class/lib/base.php' );
webphoto_include_once( 'class/lib/pathinfo.php' );
webphoto_include_once( 'class/lib/search.php' );
webphoto_include_once( 'class/lib/xml.php' );
webphoto_include_once( 'class/lib/rss.php' );

webphoto_include_once( 'class/handler/file_handler.php' );
webphoto_include_once( 'class/handler/cat_handler.php' );
webphoto_include_once( 'class/handler/item_cat_handler.php' );
webphoto_include_once( 'class/webphoto/config.php' );
webphoto_include_once( 'class/webphoto/uri.php' );
webphoto_include_once( 'class/webphoto/photo_sort.php' );
webphoto_include_once( 'class/webphoto/photo_public.php' );
webphoto_include_once( 'class/webphoto/rss.php' );
webphoto_include_once( 'class/main/rss.php' );

webphoto_include_language( 'main.php' );

//=========================================================
// main
//=========================================================
$webphoto_manage =& webphoto_main_rss::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$webphoto_manage->main();
exit();

?>