<?php
// $Id: mimetypes.php,v 1.3 2010/05/09 12:54:48 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/lib/pagenavi.php' );
webphoto_include_once( 'class/handler/mime_handler.php' );
webphoto_include_once( 'class/admin/mime_form.php' );
webphoto_include_once( 'class/admin/mimetypes.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_mimetypes::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>