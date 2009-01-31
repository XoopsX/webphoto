<?php
// $Id: header.php,v 1.12 2009/01/31 19:12:50 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-01-25 K.OHWADA
// gmap_api.php
// 2009-01-10 K.OHWADA
// multibyte.php
// 2009-01-04 K.OHWADA
// plugin.php
// 2008-12-12 K.OHWADA
// photo_public.php
// 2008-12-07 K.OHWADA
// rate_check.php
// 2008-11-29 K.OHWADA
// auto_publish.php etc
// 2008-11-16 K.OHWADA
// show_image.php
// 2008-10-01 K.OHWADA
// player_handler.php  flashvar_handler.php
// 2008-08-24 K.OHWADA
// added item_handler.php qrcode_img.php
// 2008-07-01 K.OHWADA
// added uri.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// xoops system files
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH.'/class/template.php';
include_once XOOPS_ROOT_PATH.'/class/snoopy.php';

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
define("QRCODE_DATA_PATH", WEBPHOTO_TRUST_PATH.'/class/qrcode/qrcode_data' );

webphoto_include_once( 'include/constants.php' );
webphoto_include_once( 'include/gmap_api.php' );
webphoto_include_once( 'class/qrcode/qrcode_img.php' );
webphoto_include_once( 'class/xoops/base.php' );
webphoto_include_once( 'class/inc/handler.php' );
webphoto_include_once( 'class/inc/config.php' );
webphoto_include_once( 'class/inc/group_permission.php' );
webphoto_include_once( 'class/inc/xoops_header.php' );
webphoto_include_once( 'class/inc/catlist.php' );
webphoto_include_once( 'class/inc/tagcloud.php' );
webphoto_include_once( 'class/inc/auto_publish.php' );
webphoto_include_once( 'class/inc/uri.php' );
webphoto_include_once( 'class/inc/gmap_info.php' );
webphoto_include_once( 'class/d3/language.php' );
webphoto_include_once( 'class/d3/notification_select.php' );
webphoto_include_once( 'class/d3/preload.php' );
webphoto_include_once( 'class/lib/error.php' );
webphoto_include_once( 'class/lib/msg.php' );
webphoto_include_once( 'class/lib/handler.php' );
webphoto_include_once( 'class/lib/tree_handler.php' );
webphoto_include_once( 'class/lib/post.php' );
webphoto_include_once( 'class/lib/pathinfo.php' );
webphoto_include_once( 'class/lib/highlight.php' );
webphoto_include_once( 'class/lib/pagenavi.php' );
webphoto_include_once( 'class/lib/utility.php' );
webphoto_include_once( 'class/lib/remote_file.php' );
webphoto_include_once( 'class/lib/base.php' );
webphoto_include_once( 'class/lib/cloud.php' );
webphoto_include_once( 'class/lib/multibyte.php' );
webphoto_include_once( 'class/lib/xml.php' );
webphoto_include_once( 'class/lib/plugin.php' );
webphoto_include_once( 'class/handler/item_handler.php' );
webphoto_include_once( 'class/handler/file_handler.php' );
webphoto_include_once( 'class/handler/cat_handler.php' );
webphoto_include_once( 'class/handler/tag_handler.php' );
webphoto_include_once( 'class/handler/p2t_handler.php' );
webphoto_include_once( 'class/handler/photo_tag_handler.php' );
webphoto_include_once( 'class/handler/gicon_handler.php' );
webphoto_include_once( 'class/handler/user_handler.php' );
webphoto_include_once( 'class/handler/player_handler.php' );
webphoto_include_once( 'class/handler/flashvar_handler.php' );
webphoto_include_once( 'class/handler/vote_handler.php' );
webphoto_include_once( 'class/handler/item_cat_handler.php' );
webphoto_include_once( 'class/webphoto/xoops_header.php' );
webphoto_include_once( 'class/webphoto/config.php' );
webphoto_include_once( 'class/webphoto/permission.php' );
webphoto_include_once( 'class/webphoto/uri.php' );
webphoto_include_once( 'class/webphoto/kind.php' );
webphoto_include_once( 'class/webphoto/multibyte.php' );
webphoto_include_once( 'class/webphoto/base_this.php' );
webphoto_include_once( 'class/webphoto/tag.php' );
webphoto_include_once( 'class/webphoto/gmap.php' );
webphoto_include_once( 'class/webphoto/photo_sort.php' );
webphoto_include_once( 'class/webphoto/photo_public.php' );
webphoto_include_once( 'class/webphoto/playlist.php' );
webphoto_include_once( 'class/webphoto/flash_player.php' );
webphoto_include_once( 'class/webphoto/embed_base.php' );
webphoto_include_once( 'class/webphoto/embed.php' );
webphoto_include_once( 'class/webphoto/rate_check.php' );
webphoto_include_once( 'class/webphoto/show_image.php' );
webphoto_include_once( 'class/webphoto/show_photo.php' );
webphoto_include_once( 'class/webphoto/show_main.php' );
webphoto_include_once( 'class/webphoto/show_list.php' );

webphoto_include_language( 'modinfo.php' );
webphoto_include_language( 'main.php' );

webphoto_include_once_preload_trust();
webphoto_include_once_preload();

?>