<?php
// $Id: rss.php,v 1.11 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// call form XOOPS_ROOT_PATH/modules/xxx/rss.php
//---------------------------------------------------------

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// remove class/inc/config.php
// 2010-11-03 K.OHWADA
// class/webphoto/main.php
// 2010-01-10 K.OHWADA
// class/webphoto/tag.php
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
webphoto_include_once( 'class/inc/group_permission.php' );
webphoto_include_once( 'class/inc/uri.php' );
webphoto_include_once( 'class/inc/catlist.php' );
webphoto_include_once( 'class/inc/tagcloud.php' );

webphoto_include_once( 'class/d3/language.php' );

webphoto_include_once( 'class/lib/multibyte.php' );
webphoto_include_once( 'class/lib/highlight.php' );
webphoto_include_once( 'class/lib/base.php' );
webphoto_include_once( 'class/lib/msg.php' );
webphoto_include_once( 'class/lib/post.php' );
webphoto_include_once( 'class/lib/pathinfo.php' );
webphoto_include_once( 'class/lib/search.php' );
webphoto_include_once( 'class/lib/xml.php' );
webphoto_include_once( 'class/lib/rss.php' );

webphoto_include_once( 'class/handler/file_handler.php' );
webphoto_include_once( 'class/handler/cat_handler.php' );
webphoto_include_once( 'class/handler/item_cat_handler.php' );
webphoto_include_once( 'class/handler/tag_handler.php' );
webphoto_include_once( 'class/handler/photo_tag_handler.php' );
webphoto_include_once( 'class/handler/p2t_handler.php' );

webphoto_include_once( 'class/webphoto/config.php' );
webphoto_include_once( 'class/webphoto/permission.php' );
webphoto_include_once( 'class/webphoto/base_ini.php' );
webphoto_include_once( 'class/webphoto/base_this.php' );
webphoto_include_once( 'class/webphoto/multibyte.php' );
webphoto_include_once( 'class/webphoto/kind.php' );
webphoto_include_once( 'class/webphoto/uri.php' );
webphoto_include_once( 'class/webphoto/photo_sort.php' );
webphoto_include_once( 'class/webphoto/photo_public.php' );
webphoto_include_once( 'class/webphoto/show_image.php' );
webphoto_include_once( 'class/webphoto/show_photo.php' );
webphoto_include_once( 'class/webphoto/tag_build.php' );
webphoto_include_once( 'class/webphoto/main.php' );
webphoto_include_once( 'class/webphoto/category.php' );
webphoto_include_once( 'class/webphoto/user.php' );
webphoto_include_once( 'class/webphoto/place.php' );
webphoto_include_once( 'class/webphoto/date.php' );
webphoto_include_once( 'class/webphoto/tag.php' );
webphoto_include_once( 'class/webphoto/search.php' );
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