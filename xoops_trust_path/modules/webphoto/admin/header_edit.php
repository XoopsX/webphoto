<?php
// $Id: header_edit.php,v 1.17 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWAD
// remove class/inc/config.php
// 2011-05-01 K.OHWADA
// main/include_submit.php
// 2010-10-01 K.OHWADA
// class/edit/wav_create.php
// 2010-03-18 K.OHWADA
// class/edit/item_create.php
// 2010-01-10 K.OHWADA
// class/webphoto/tag.php -> tag_build.php
// 2009-12-06 K.OHWADA
// class/d3/mail_template.php
// 2009-11-11 K.OHWADA
// class/inc/ini.php
// 2009-10-25 K.OHWADA
// class/lib/lame.php
// 2009-04-10 K.OHWADA
// small_create.php
// 2008-01-25 K.OHWADA
// jodconverter.php
// 2008-01-10 K.OHWADA
// xpdf.php etc
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
include_once XOOPS_ROOT_PATH.'/class/template.php';
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/include_submit.php' );

webphoto_include_once( 'class/xoops/base.php' );
webphoto_include_once( 'class/xoops/user.php' );

webphoto_include_once( 'class/inc/handler.php' );
webphoto_include_once( 'class/inc/base_ini.php' );
webphoto_include_once( 'class/inc/catlist.php' );
webphoto_include_once( 'class/inc/tagcloud.php' );
webphoto_include_once( 'class/inc/timeline.php' );
webphoto_include_once( 'class/inc/group_permission.php' );
webphoto_include_once( 'class/inc/xoops_header.php' );
webphoto_include_once( 'class/inc/ini.php' );
webphoto_include_once( 'class/inc/gmap_info.php' );
webphoto_include_once( 'class/inc/admin_menu.php' );

webphoto_include_once( 'class/d3/mail_template.php' );

webphoto_include_once( 'class/lib/utility.php' );
webphoto_include_once( 'class/lib/pagenavi.php' );
webphoto_include_once( 'class/lib/mail_send.php' );
webphoto_include_once( 'class/lib/admin_menu.php' );

webphoto_include_once( 'class/webphoto/permission.php' );
webphoto_include_once( 'class/webphoto/photo_sort.php' );
webphoto_include_once( 'class/webphoto/flash_log.php' );
webphoto_include_once( 'class/webphoto/flash_player.php' );
webphoto_include_once( 'class/webphoto/mail_send.php' );

webphoto_include_once( 'class/edit/submit.php' );
webphoto_include_once( 'class/edit/action.php' );

webphoto_include_language( 'modinfo.php' );
webphoto_include_language( 'main.php' );
webphoto_include_language( 'admin.php' );

webphoto_include_once_preload_trust();
webphoto_include_once_preload();

?>