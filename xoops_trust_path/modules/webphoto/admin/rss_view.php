<?php
// $Id: rss_view.php,v 1.3 2010/11/04 02:24:16 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-11-03 K.OHWADA
// admin/header_rss.php
// 2010-01-10 K.OHWADA
// class/webphoto/tag.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header_rss.php' );
webphoto_include_once( 'class/admin/rss_view.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_rss_view::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>