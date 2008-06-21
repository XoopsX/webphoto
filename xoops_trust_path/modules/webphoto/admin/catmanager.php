<?php
// $Id: catmanager.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/handler/gicon_table.php' );
webphoto_include_once( 'class/handler/gicon_handler.php' );
webphoto_include_once( 'class/handler/vote_handler.php' );
webphoto_include_once( 'class/handler/p2t_handler.php' );
webphoto_include_once( 'class/webphoto/photo_delete.php' );
webphoto_include_once( 'class/admin/cat_form.php' );
webphoto_include_once( 'class/admin/catmanager.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_catmanager::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>