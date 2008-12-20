<?php
// $Id: xoops_version.php,v 1.5 2008/12/20 06:11:27 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-12 K.OHWADA
// getInstance() -> getSingleton()
// 2008-11-08 K.OHWADA
// workdir.php
// 2008-08-01 K.OHWADA
// use WEBPHOTO_TRUST_DIRNAME
// 2008-07-01 K.OHWADA
// remove class/xoops/base.php
//---------------------------------------------------------

//---------------------------------------------------------
// $MY_DIRNAME WEBPHOTO_TRUST_PATH are set by caller
//---------------------------------------------------------

if ( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

include_once WEBPHOTO_TRUST_PATH.'/class/d3/optional.php';
include_once WEBPHOTO_TRUST_PATH.'/include/optional.php';

webphoto_include_once( 'preload/debug.php',              $MY_DIRNAME );
webphoto_include_once( 'include/constants.php',          $MY_DIRNAME );
webphoto_include_once( 'include/version.php',            $MY_DIRNAME );
webphoto_include_once( 'class/inc/handler.php',          $MY_DIRNAME );
webphoto_include_once( 'class/inc/config.php',           $MY_DIRNAME );
webphoto_include_once( 'class/inc/group_permission.php', $MY_DIRNAME );
webphoto_include_once( 'class/inc/workdir.php',          $MY_DIRNAME );
webphoto_include_once( 'class/inc/xoops_version.php',    $MY_DIRNAME );
webphoto_include_language( 'modinfo.php',                $MY_DIRNAME );

//---------------------------------------------------------
// main
//---------------------------------------------------------
$webphoto_inc_xoops_version =& webphoto_inc_xoops_version::getSingleton( 
	$MY_DIRNAME, WEBPHOTO_TRUST_DIRNAME );
$modversion = $webphoto_inc_xoops_version->build_modversion();

?>