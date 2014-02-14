<?php
// $Id: header_rss.php,v 1.1 2010/11/04 02:24:16 ohwada Exp $

//=========================================================
// webphoto module
// 2010-11-03 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/lib/highlight.php' );
webphoto_include_once( 'class/lib/search.php' );
webphoto_include_once( 'class/lib/xml.php' );
webphoto_include_once( 'class/lib/rss.php' );
webphoto_include_once( 'class/inc/tagcloud.php' );
webphoto_include_once( 'class/handler/item_cat_handler.php' );
webphoto_include_once( 'class/handler/tag_handler.php' );
webphoto_include_once( 'class/handler/photo_tag_handler.php' );
webphoto_include_once( 'class/handler/p2t_handler.php' );
webphoto_include_once( 'class/webphoto/photo_sort.php' );
webphoto_include_once( 'class/webphoto/photo_public.php' );
webphoto_include_once( 'class/webphoto/show_image.php' );
webphoto_include_once( 'class/webphoto/show_photo.php' );
webphoto_include_once( 'class/webphoto/main.php' );
webphoto_include_once( 'class/webphoto/category.php' );
webphoto_include_once( 'class/webphoto/user.php' );
webphoto_include_once( 'class/webphoto/place.php' );
webphoto_include_once( 'class/webphoto/date.php' );
webphoto_include_once( 'class/webphoto/tag_build.php' );
webphoto_include_once( 'class/webphoto/tag.php' );
webphoto_include_once( 'class/webphoto/search.php' );
webphoto_include_once( 'class/webphoto/rss.php' );
webphoto_include_once( 'class/admin/rss_form.php' );

?>