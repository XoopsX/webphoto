<?php
// $Id: myphoto.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// used $WEBPHOTO_FCT
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
webphoto_include_once( 'main/header.php' );
webphoto_include_once( 'class/main/user.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_user::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manage->set_mode( $WEBPHOTO_FCT ) ;

$xoopsOption['template_main'] = $manage->list_get_template() ;
include XOOPS_ROOT_PATH . '/header.php' ;

$xoopsTpl->assign( $manage->get_photo_show_globals() ) ;
$xoopsTpl->assign( $manage->get_lang_array() ) ;
$xoopsTpl->assign( $manage->list_main() ) ;
$xoopsTpl->assign( $manage->get_footer_param() ) ;

include XOOPS_ROOT_PATH .'/footer.php' ;
exit();

?>