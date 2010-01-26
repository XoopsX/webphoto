<?php
// $Id: rss_view.php,v 1.2 2010/01/26 10:58:31 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-01-10 K.OHWADA
// class/webphoto/tag.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/lib/search.php' );
webphoto_include_once( 'class/lib/xml.php' );
webphoto_include_once( 'class/lib/rss.php' );
webphoto_include_once( 'class/inc/tagcloud.php' );
webphoto_include_once( 'class/handler/item_cat_handler.php' );
webphoto_include_once( 'class/webphoto/photo_sort.php' );
webphoto_include_once( 'class/webphoto/photo_public.php' );
webphoto_include_once( 'class/webphoto/tag.php' );
webphoto_include_once( 'class/webphoto/rss.php' );
webphoto_include_once( 'class/admin/rss_view.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_rss_view::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>