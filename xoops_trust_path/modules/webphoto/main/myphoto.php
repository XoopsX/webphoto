<?php
// $Id: myphoto.php,v 1.4 2009/06/28 14:48:07 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-08-28 K.OHWADA
// set_list_mode()
// 2009-04-10 K.OHWADA
// remove get_photo_globals()
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
$manage->set_list_mode( 'myphoto' ) ;

$xoopsOption['template_main'] = $manage->list_get_template() ;
include XOOPS_ROOT_PATH . '/header.php' ;

$xoopsTpl->assign( $manage->list_main() ) ;

include XOOPS_ROOT_PATH .'/footer.php' ;
exit();

?>