<?php
// $Id: header.php,v 1.14 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// class/lib/mysql_utility.php
// 2011-11-11 K.OHWAD
// class/inc/xoops_config.php
// 2010-09-17 K.OHWADA
// class/lib/readfile.php
// 2010-03-31 K.OHWADA
// class/edit/item_create.php
// 2009-11-11 K.OHWADA
// class/inc/ini.php
// 2008-01-25 K.OHWADA
// webphoto_include_once_preload_trust()
// 2008-01-10 K.OHWADA
// class/edit/xxx
// 2008-12-12 K.OHWADA
// catlist.php
// 2008-11-29 K.OHWADA
// class/inc/uri.php
// 2008-10-01 K.OHWADA
// kind.php
// 2008-08-24 K.OHWADA
// added item_handler.php
// 2008-07-01 K.OHWADA
// added uri.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// xoops system files
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH."/class/xoopstree.php" ;
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'include/constants.php' );

webphoto_include_once( 'class/xoops/base.php' );

webphoto_include_once( 'class/inc/ini.php' );
webphoto_include_once( 'class/inc/handler.php' );
webphoto_include_once( 'class/inc/base_ini.php' );
webphoto_include_once( 'class/inc/xoops_config.php' );
webphoto_include_once( 'class/inc/config.php' );
webphoto_include_once( 'class/inc/group_permission.php' );
webphoto_include_once( 'class/inc/admin_menu.php' );
webphoto_include_once( 'class/inc/uri.php' );
webphoto_include_once( 'class/inc/catlist.php' );

webphoto_include_once( 'class/d3/language.php' );
webphoto_include_once( 'class/d3/preload.php' );

webphoto_include_once( 'class/lib/gtickets.php' );
webphoto_include_once( 'class/lib/error.php' );
webphoto_include_once( 'class/lib/msg.php' );
webphoto_include_once( 'class/lib/post.php' );
webphoto_include_once( 'class/lib/pathinfo.php' );
webphoto_include_once( 'class/lib/handler.php' );
webphoto_include_once( 'class/lib/tree_handler.php' );
webphoto_include_once( 'class/lib/utility.php' );
webphoto_include_once( 'class/lib/base.php' );
webphoto_include_once( 'class/lib/element.php' );
webphoto_include_once( 'class/lib/form.php' );
webphoto_include_once( 'class/lib/multibyte.php' );
webphoto_include_once( 'class/lib/plugin.php' );
webphoto_include_once( 'class/lib/readfile.php' );
webphoto_include_once( 'class/lib/admin_menu.php' );
webphoto_include_once( 'class/lib/mysql_utility.php' );

webphoto_include_once( 'class/handler/base_ini.php' );
webphoto_include_once( 'class/handler/item_handler.php' );
webphoto_include_once( 'class/handler/file_handler.php' );
webphoto_include_once( 'class/handler/cat_handler.php' );
webphoto_include_once( 'class/handler/mime_handler.php' );

webphoto_include_once( 'class/webphoto/config.php' );
webphoto_include_once( 'class/webphoto/permission.php' );
webphoto_include_once( 'class/webphoto/uri.php' );
webphoto_include_once( 'class/webphoto/kind.php' );
webphoto_include_once( 'class/webphoto/multibyte.php' );
webphoto_include_once( 'class/webphoto/base_ini.php' );
webphoto_include_once( 'class/webphoto/base_this.php' );
webphoto_include_once( 'class/webphoto/mime.php' );
webphoto_include_once( 'class/webphoto/embed.php' );

webphoto_include_once( 'class/edit/icon_build.php' );
webphoto_include_once( 'class/edit/base.php' );
webphoto_include_once( 'class/edit/item_create.php' );
webphoto_include_once( 'class/edit/form.php' );

webphoto_include_language( 'modinfo.php' );
webphoto_include_language( 'main.php' );
webphoto_include_language( 'admin.php' );

webphoto_include_once_preload_trust();
webphoto_include_once_preload();

?>