<?php
// $Id: help.php,v 1.3 2009/04/11 14:23:35 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-04-10 K.OHWADA
// remove get_photo_globals()
// 2008-08-01 K.OHWADA
// use main()
//---------------------------------------------------------

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

$xoopsTpl->assign( $manage->main() ) ;

include XOOPS_ROOT_PATH .'/footer.php' ;
exit();

?>