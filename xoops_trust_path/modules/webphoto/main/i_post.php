<?php
// $Id: i_post.php,v 1.4 2011/11/13 05:24:37 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// main/include_mail_recv.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header_submit.php' );
webphoto_include_once( 'main/include_mail_recv.php' );

webphoto_include_once( 'class/lib/pagenavi.php' );
webphoto_include_once( 'class/lib/user_agent.php' );

webphoto_include_once( 'class/webphoto/photo_public.php' );
webphoto_include_once( 'class/webphoto/item_public.php' );
webphoto_include_once( 'class/webphoto/imode.php' );

webphoto_include_once( 'class/main/i_post.php' );

webphoto_include_language( 'extra.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_i_post::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manage->main();
exit();

?>