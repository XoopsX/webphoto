<?php
// $Id: header_submit.php,v 1.18 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
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
// class/webphoto/base_this.php
// 2009-11-11 K.OHWADA
// main/header_item_handler.php
// 2009-10-25 K.OHWADA
// class/lib/lame.php
// 2009-04-10 K.OHWADA
// small_create.php
// 2009-01-25 K.OHWADA
// jodconverter.php
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
webphoto_include_once( 'main/header_item_handler.php' );
webphoto_include_once( 'main/include_submit.php' );

webphoto_include_once( 'include/gmap_api.php' );

webphoto_include_once( 'class/xoops/base.php' );
webphoto_include_once( 'class/xoops/user.php' );

webphoto_include_once( 'class/inc/handler.php' );
webphoto_include_once( 'class/inc/base_ini.php' );
webphoto_include_once( 'class/inc/catlist.php' );
webphoto_include_once( 'class/inc/tagcloud.php' );
webphoto_include_once( 'class/inc/timeline.php' );
webphoto_include_once( 'class/inc/group_permission.php' );
webphoto_include_once( 'class/inc/xoops_header.php' );

webphoto_include_once( 'class/webphoto/permission.php' );

webphoto_include_language( 'modinfo.php' );
webphoto_include_language( 'main.php' );

webphoto_include_once_preload_trust();
webphoto_include_once_preload();

?>