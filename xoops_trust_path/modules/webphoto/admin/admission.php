<?php
// $Id: admission.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/d3/notification_event.php' );
webphoto_include_once( 'class/lib/pagenavi.php' );
webphoto_include_once( 'class/handler/tag_handler.php' );
webphoto_include_once( 'class/handler/p2t_handler.php' );
webphoto_include_once( 'class/handler/photo_tag_handler.php' );
webphoto_include_once( 'class/handler/vote_handler.php' );
webphoto_include_once( 'class/handler/syno_handler.php' );
webphoto_include_once( 'class/webphoto/tag.php' );
webphoto_include_once( 'class/webphoto/photo_delete.php' );
webphoto_include_once( 'class/webphoto/notification_event.php' );
webphoto_include_once( 'class/admin/admission_form.php' );
webphoto_include_once( 'class/admin/admission.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_admission::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>