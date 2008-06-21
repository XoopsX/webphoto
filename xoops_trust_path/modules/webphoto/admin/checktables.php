<?php
// $Id: checktables.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/handler/vote_handler.php' );
webphoto_include_once( 'class/handler/gicon_table.php' );
webphoto_include_once( 'class/handler/gicon_handler.php' );
webphoto_include_once( 'class/handler/mime_handler.php' );
webphoto_include_once( 'class/handler/tag_handler.php' );
webphoto_include_once( 'class/handler/p2t_handler.php' );
webphoto_include_once( 'class/handler/syno_handler.php' );
webphoto_include_once( 'class/handler/xoops_comments_handler.php' );
webphoto_include_once( 'class/admin/checktables.php' );
webphoto_include_once( 'class/admin/index.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_checktables::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>