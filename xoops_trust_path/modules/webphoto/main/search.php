<?php
// $Id: search.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;



//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header.php' );
webphoto_include_once( 'class/lib/search.php' );
webphoto_include_once( 'class/main/search.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_search::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );

$xoopsOption['template_main'] = $manage->list_get_template() ;
include XOOPS_ROOT_PATH . '/header.php' ;

$xoopsTpl->assign( $manage->get_photo_show_globals() ) ;
$xoopsTpl->assign( $manage->get_lang_array() ) ;
$xoopsTpl->assign( $manage->list_main() ) ;
$xoopsTpl->assign( $manage->get_footer_param() ) ;

include XOOPS_ROOT_PATH .'/footer.php' ;
exit();

?>