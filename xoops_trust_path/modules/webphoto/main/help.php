<?php
// $Id: help.php,v 1.1 2008/06/21 12:22:14 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header.php' );
webphoto_include_once( 'class/main/help.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_help::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );

$xoopsOption['template_main'] = WEBPHOTO_DIRNAME.'_main_help.html' ;
include XOOPS_ROOT_PATH . '/header.php' ;

$xoopsTpl->assign( $manage->get_photo_globals() ) ;
$xoopsTpl->assign( $manage->get_lang_array() ) ;

include XOOPS_ROOT_PATH .'/footer.php' ;
exit();

?>