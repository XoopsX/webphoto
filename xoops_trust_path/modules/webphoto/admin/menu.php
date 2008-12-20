<?php
// $Id: menu.php,v 1.2 2008/12/20 06:11:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
//---------------------------------------------------------

//---------------------------------------------------------
// $MY_DIRNAME WEBPHOTO_TRUST_PATH are set by caller
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

include_once WEBPHOTO_TRUST_PATH.'/class/d3/optional.php';
include_once WEBPHOTO_TRUST_PATH.'/include/optional.php';

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
$MY_DIRNAME= $GLOBALS['MY_DIRNAME'];
webphoto_include_once( 'class/inc/admin_menu.php', $MY_DIRNAME );
webphoto_include_language( 'modinfo.php',          $MY_DIRNAME );

//=========================================================
// main
//=========================================================
$manager =& webphoto_inc_admin_menu::getSingleton( $MY_DIRNAME );
$adminmenu = $manager->build_menu();

?>