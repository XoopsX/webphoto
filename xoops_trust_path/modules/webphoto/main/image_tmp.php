<?php
// $Id: image_tmp.php,v 1.2 2010/09/19 06:43:11 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-09-17 K.OHWADA
// class/lib/readfile.php
// 2008-11-16 K.OHWADA
// image.php -> image_tmp.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'include/constants.php' );
webphoto_include_once( 'class/xoops/base.php' );
webphoto_include_once( 'class/inc/config.php' );
webphoto_include_once( 'class/lib/utility.php' );
webphoto_include_once( 'class/lib/post.php' );
webphoto_include_once( 'class/lib/readfile.php' );
webphoto_include_once( 'class/webphoto/config.php' );
webphoto_include_once( 'class/main/image_tmp.php' );

//=========================================================
// main
//=========================================================
$webphoto_manage =& webphoto_main_image_tmp::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$webphoto_manage->main();
exit();

?>