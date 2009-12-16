<?php
// $Id: menu.php,v 1.3 2009/12/16 13:32:34 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2009-12-06 K.OHWADA
// class/inc/ini.php
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
webphoto_include_once( 'class/inc/ini.php',        $MY_DIRNAME );
webphoto_include_once( 'class/inc/admin_menu.php', $MY_DIRNAME );
webphoto_include_language( 'modinfo.php',          $MY_DIRNAME );

//=========================================================
// main
//=========================================================
$manager =& webphoto_inc_admin_menu::getSingleton( 
	$MY_DIRNAME, WEBPHOTO_TRUST_DIRNAME );
$adminmenu = $manager->build_menu();

?>