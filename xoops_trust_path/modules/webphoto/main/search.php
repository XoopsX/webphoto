<?php
// $Id: search.php,v 1.2 2009/04/11 14:23:35 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-04-10 K.OHWADA
// remove get_photo_globals()
//---------------------------------------------------------

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

$xoopsTpl->assign( $manage->list_main() ) ;

include XOOPS_ROOT_PATH .'/footer.php' ;
exit();

?>