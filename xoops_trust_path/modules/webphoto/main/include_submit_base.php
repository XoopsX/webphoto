<?php
// $Id: include_submit_base.php,v 1.2 2011/12/26 06:51:31 ohwada Exp $

//=========================================================
// webphoto module
// 2011-05-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// class/lib/mysql_utility.php
// 2011-11-11 K.OHWADA
// change file name
// include_submit.php -> include_submit_base.php
//---------------------------------------------------------

//---------------------------------------------------------
// caller main/header_submit_imagemanager.php 
//   admin/header_edit.php bin/retrieve.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// xoops system files
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH.'/class/snoopy.php';

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'include/constants.php' );

webphoto_include_once( 'class/inc/xoops_config.php' );
webphoto_include_once( 'class/inc/config.php' );
webphoto_include_once( 'class/inc/uri.php' );

webphoto_include_once( 'class/d3/language.php' );
webphoto_include_once( 'class/d3/notification_event.php' );
webphoto_include_once( 'class/d3/preload.php' );

webphoto_include_once( 'class/lib/error.php' );
webphoto_include_once( 'class/lib/handler.php' );
webphoto_include_once( 'class/lib/tree_handler.php' );
webphoto_include_once( 'class/lib/msg.php' );
webphoto_include_once( 'class/lib/base.php' );
webphoto_include_once( 'class/lib/post.php' );
webphoto_include_once( 'class/lib/pathinfo.php' );
webphoto_include_once( 'class/lib/highlight.php' );
webphoto_include_once( 'class/lib/multibyte.php' );
webphoto_include_once( 'class/lib/xml.php' );
webphoto_include_once( 'class/lib/gtickets.php' );
webphoto_include_once( 'class/lib/element.php' );
webphoto_include_once( 'class/lib/form.php' );
webphoto_include_once( 'class/lib/remote_file.php' );
webphoto_include_once( 'class/lib/uploader.php' );
webphoto_include_once( 'class/lib/gd.php' );
webphoto_include_once( 'class/lib/imagemagick.php' );
webphoto_include_once( 'class/lib/netpbm.php' );
webphoto_include_once( 'class/lib/image_cmd.php' );
webphoto_include_once( 'class/lib/exif.php' );
webphoto_include_once( 'class/lib/ffmpeg.php' );
webphoto_include_once( 'class/lib/lame.php' );
webphoto_include_once( 'class/lib/timidity.php' );
webphoto_include_once( 'class/lib/xpdf.php' );
webphoto_include_once( 'class/lib/jodconverter.php' );
webphoto_include_once( 'class/lib/plugin.php' );
webphoto_include_once( 'class/lib/utility.php' );
webphoto_include_once( 'class/lib/mysql_utility.php' );

webphoto_include_once( 'class/handler/base_ini.php' );
webphoto_include_once( 'class/handler/item_handler.php' );
webphoto_include_once( 'class/handler/file_handler.php' );
webphoto_include_once( 'class/handler/cat_handler.php' );
webphoto_include_once( 'class/handler/mime_handler.php' );
webphoto_include_once( 'class/handler/syno_handler.php' );
webphoto_include_once( 'class/handler/tag_handler.php' );
webphoto_include_once( 'class/handler/p2t_handler.php' );
webphoto_include_once( 'class/handler/gicon_handler.php' );
webphoto_include_once( 'class/handler/user_handler.php' );
webphoto_include_once( 'class/handler/player_handler.php' );
webphoto_include_once( 'class/handler/flashvar_handler.php' );
webphoto_include_once( 'class/handler/vote_handler.php' );
webphoto_include_once( 'class/handler/maillog_handler.php' );
webphoto_include_once( 'class/handler/item_cat_handler.php' );
webphoto_include_once( 'class/handler/photo_tag_handler.php' );

webphoto_include_once( 'class/webphoto/plugin_ini.php' );
webphoto_include_once( 'class/webphoto/base_ini.php' );
webphoto_include_once( 'class/webphoto/base_this.php' );
webphoto_include_once( 'class/webphoto/xoops_header.php' );
webphoto_include_once( 'class/webphoto/config.php' );
webphoto_include_once( 'class/webphoto/uri.php' );
webphoto_include_once( 'class/webphoto/kind.php' );
webphoto_include_once( 'class/webphoto/base_ini.php' );
webphoto_include_once( 'class/webphoto/base_this.php' );
webphoto_include_once( 'class/webphoto/mime.php' );
webphoto_include_once( 'class/webphoto/gmap.php' );
webphoto_include_once( 'class/webphoto/tag_build.php' );
webphoto_include_once( 'class/webphoto/multibyte.php' );
webphoto_include_once( 'class/webphoto/playlist.php' );
webphoto_include_once( 'class/webphoto/image_create.php' );
webphoto_include_once( 'class/webphoto/exif.php' );
webphoto_include_once( 'class/webphoto/cmd_base.php' );
webphoto_include_once( 'class/webphoto/imagemagick.php' );
webphoto_include_once( 'class/webphoto/ffmpeg.php' );
webphoto_include_once( 'class/webphoto/lame.php' );
webphoto_include_once( 'class/webphoto/timidity.php' );
webphoto_include_once( 'class/webphoto/pdf.php' );
webphoto_include_once( 'class/webphoto/jodconverter.php' );
webphoto_include_once( 'class/webphoto/upload.php' );
webphoto_include_once( 'class/webphoto/embed_base.php' );
webphoto_include_once( 'class/webphoto/embed.php' );
webphoto_include_once( 'class/webphoto/editor_base.php' );
webphoto_include_once( 'class/webphoto/editor.php' );
webphoto_include_once( 'class/webphoto/ext_base.php' );
webphoto_include_once( 'class/webphoto/ext.php' );
webphoto_include_once( 'class/webphoto/photo_public.php' );
webphoto_include_once( 'class/webphoto/page.php' );
webphoto_include_once( 'class/webphoto/show_image.php' );
webphoto_include_once( 'class/webphoto/show_photo.php' );
webphoto_include_once( 'class/webphoto/notification_event.php' );

webphoto_include_once( 'class/edit/base.php' );
webphoto_include_once( 'class/edit/form.php' );
webphoto_include_once( 'class/edit/item_create.php' );
webphoto_include_once( 'class/edit/base_create.php' );
webphoto_include_once( 'class/edit/use_item.php' );
webphoto_include_once( 'class/edit/item_build.php' );
webphoto_include_once( 'class/edit/icon_build.php' );
webphoto_include_once( 'class/edit/ext_build.php' );
webphoto_include_once( 'class/edit/cont_create.php' );
webphoto_include_once( 'class/edit/jpeg_create.php' );
webphoto_include_once( 'class/edit/middle_thumb_create.php' );
webphoto_include_once( 'class/edit/small_create.php' );
webphoto_include_once( 'class/edit/flash_create.php' );
webphoto_include_once( 'class/edit/docomo_create.php' );
webphoto_include_once( 'class/edit/mp3_create.php' );
webphoto_include_once( 'class/edit/wav_create.php' );
webphoto_include_once( 'class/edit/pdf_create.php' );
webphoto_include_once( 'class/edit/swf_create.php' );
webphoto_include_once( 'class/edit/video_images_create.php' );
webphoto_include_once( 'class/edit/video_middle_thumb_create.php' );
webphoto_include_once( 'class/edit/file_action.php' );
webphoto_include_once( 'class/edit/factory_create.php' );
webphoto_include_once( 'class/edit/external_build.php' );
webphoto_include_once( 'class/edit/embed_build.php' );
webphoto_include_once( 'class/edit/playlist_build.php' );
webphoto_include_once( 'class/edit/search_build.php' );
webphoto_include_once( 'class/edit/redirect.php' );
webphoto_include_once( 'class/edit/item_delete.php' );
webphoto_include_once( 'class/edit/mail_unlink.php' );
webphoto_include_once( 'class/edit/imagemanager_submit.php' );

?>