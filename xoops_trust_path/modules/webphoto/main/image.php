<?php
// $Id: image.php,v 1.1 2008/08/08 04:38:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'include/constants.php' );
webphoto_include_once( 'class/xoops/base.php' );
webphoto_include_once( 'class/inc/config.php' );
webphoto_include_once( 'class/lib/utility.php' );
webphoto_include_once( 'class/lib/post.php' );
webphoto_include_once( 'class/webphoto/config.php' );
webphoto_include_once( 'class/main/image.php' );

//=========================================================
// main
//=========================================================
$webphoto_manage =& webphoto_main_image::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$webphoto_manage->main();
exit();

?>