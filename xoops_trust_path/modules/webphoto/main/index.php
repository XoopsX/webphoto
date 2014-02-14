<?php
// $Id: index.php,v 1.5 2010/01/25 10:03:07 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-10-25 K.OHWADA
// get_template_main()
// 2009-04-10 K.OHWADA
// remove get_photo_globals()
// 2008-08-01 K.OHWADA
// remove xoops_template.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

webphoto_include_once( 'main/header.php' );
webphoto_include_once( 'class/main/index.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_index::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );
$manage->init();

$xoopsOption['template_main'] = $manage->get_template_main() ;
include XOOPS_ROOT_PATH . "/header.php" ;

$xoopsTpl->assign( $manage->main() ) ;

include( XOOPS_ROOT_PATH . "/footer.php" ) ;

?>