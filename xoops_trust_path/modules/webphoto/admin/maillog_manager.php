<?php
// $Id: maillog_manager.php,v 1.6 2011/11/13 05:24:37 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// main/include_mail_recv.php
// 2011-05-01 K.OHWADA
// main/include_mail.php
// 2010-09-17 K.OHWADA
// class/admin/maillog_form.php
// 2008-01-10 K.OHWADA
// header_edit.php
// 2008-11-08 K.OHWADA
// imagemagick.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'admin/header_edit.php' );
webphoto_include_once( 'main/include_mail_recv.php' );
webphoto_include_once( 'class/lib/pagenavi.php' );
webphoto_include_once( 'class/lib/manage.php' );
webphoto_include_once( 'class/webphoto/cat_selbox.php' );
webphoto_include_once( 'class/admin/maillog_form.php' );
webphoto_include_once( 'class/admin/maillog_manager.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_admin_maillog_manager::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manage->main();

exit();
// --- main end ---

?>