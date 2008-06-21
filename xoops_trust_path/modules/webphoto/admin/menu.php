<?php
// $Id: menu.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

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
$manager =& webphoto_inc_admin_menu::getInstance();
$adminmenu = $manager->build_menu( $MY_DIRNAME );

?>