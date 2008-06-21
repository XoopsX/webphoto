<?php
// $Id: index.php,v 1.1 2008/06/21 12:22:14 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

webphoto_include_once( 'main/header.php' );
webphoto_include_once( 'class/main/index.php' );
webphoto_include_once( 'class/inc/xoops_template.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_index::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );

$xoopsOption['template_main'] = WEBPHOTO_DIRNAME.'_main_index.html' ;
include XOOPS_ROOT_PATH . "/header.php" ;

$xoopsTpl->assign( $manage->get_photo_show_globals() ) ;
$xoopsTpl->assign( $manage->get_lang_array() ) ;
$xoopsTpl->assign( $manage->main() ) ;
$xoopsTpl->assign( $manage->get_footer_param() ) ;

include( XOOPS_ROOT_PATH . "/footer.php" ) ;

?>