<?php
// $Id: catmanager.php,v 1.7 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-12-25 K.OHWADA
// class/webphoto/timeline_init.php
// 2009-12-06 K.OHWADA
// class/inc/group.php
// 2008-01-10 K.OHWADA
// class/edit/xxx
// 2008-11-08 K.OHWADA
// imagemagick.php
// 2008-08-24 K.OHWADA
// added maillog_handler.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header.php' );
webphoto_include_once( 'class/inc/gperm_def.php' );
webphoto_include_once( 'class/inc/group.php' );
webphoto_include_once( 'class/inc/timeline.php' );
webphoto_include_once( 'class/lib/uploader.php' );
webphoto_include_once( 'class/lib/gd.php' );
webphoto_include_once( 'class/lib/imagemagick.php' );
webphoto_include_once( 'class/lib/netpbm.php' );
webphoto_include_once( 'class/lib/image_cmd.php' );
webphoto_include_once( 'class/lib/groupperm.php' );
webphoto_include_once( 'class/lib/groupperm_form.php' );
webphoto_include_once( 'class/lib/userlist.php' );
webphoto_include_once( 'class/handler/gicon_handler.php' );
webphoto_include_once( 'class/handler/vote_handler.php' );
webphoto_include_once( 'class/handler/p2t_handler.php' );
webphoto_include_once( 'class/handler/maillog_handler.php' );
webphoto_include_once( 'class/handler/mime_handler.php' );
webphoto_include_once( 'class/webphoto/mime.php' );
webphoto_include_once( 'class/webphoto/upload.php' );
webphoto_include_once( 'class/webphoto/image_create.php' );
webphoto_include_once( 'class/webphoto/timeline_init.php' );
webphoto_include_once( 'class/edit/mail_unlink.php' );
webphoto_include_once( 'class/edit/item_delete.php' );
webphoto_include_once( 'class/admin/groupperm_form.php' );
webphoto_include_once( 'class/admin/cat_form.php' );
webphoto_include_once( 'class/admin/catmanager.php' );

//=========================================================
// main
//=========================================================
$manager =& webphoto_admin_catmanager::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manager->main();
exit();

?>