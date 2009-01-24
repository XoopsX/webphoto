<?php
// $Id: i_post.php,v 1.1 2009/01/24 07:12:23 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header_submit.php' );
webphoto_include_once( 'class/lib/pagenavi.php' );
webphoto_include_once( 'class/lib/mail_pop.php' );
webphoto_include_once( 'class/lib/mail_parse.php' );
webphoto_include_once( 'class/lib/user_agent.php' );
webphoto_include_once( 'class/webphoto/photo_public.php' );
webphoto_include_once( 'class/webphoto/item_public.php' );
webphoto_include_once( 'class/webphoto/imode.php' );
webphoto_include_once( 'class/edit/mail_check.php' );
webphoto_include_once( 'class/edit/mail_photo.php' );
webphoto_include_once( 'class/edit/mail_unlink.php' );
webphoto_include_once( 'class/edit/mail_retrieve.php' );
webphoto_include_once( 'class/main/i_post.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_i_post::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manage->main();
exit();

?>