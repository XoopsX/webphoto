<?php
// $Id: xoops_version.php,v 1.8 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWADA
// class/inc/xoops_config.php
// 2010-04-27 K.OHWADA
// class/inc/catlist.php
// 2009-11-11 K.OHWADA
// class/inc/ini.php
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

//---------------------------------------------------------
// xoops system files
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH.'/class/xoopstree.php' ;

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
include_once WEBPHOTO_TRUST_PATH.'/class/d3/optional.php';
include_once WEBPHOTO_TRUST_PATH.'/include/optional.php';

webphoto_include_once( 'preload/debug.php',              $MY_DIRNAME );
webphoto_include_once( 'include/constants.php',          $MY_DIRNAME );
webphoto_include_once( 'include/version.php',            $MY_DIRNAME );
webphoto_include_once( 'class/lib/multibyte.php',        $MY_DIRNAME );
webphoto_include_once( 'class/inc/ini.php',              $MY_DIRNAME );
webphoto_include_once( 'class/inc/handler.php',          $MY_DIRNAME );
webphoto_include_once( 'class/inc/base_ini.php',         $MY_DIRNAME );
webphoto_include_once( 'class/inc/xoops_config.php',     $MY_DIRNAME );
webphoto_include_once( 'class/inc/config.php',           $MY_DIRNAME );
webphoto_include_once( 'class/inc/group_permission.php', $MY_DIRNAME );
webphoto_include_once( 'class/inc/workdir.php',          $MY_DIRNAME );
webphoto_include_once( 'class/inc/catlist.php',          $MY_DIRNAME );
webphoto_include_once( 'class/inc/xoops_version.php',    $MY_DIRNAME );
webphoto_include_language( 'modinfo.php',                $MY_DIRNAME );

//---------------------------------------------------------
// main
//---------------------------------------------------------
$webphoto_inc_xoops_version =& webphoto_inc_xoops_version::getSingleton( 
	$MY_DIRNAME, WEBPHOTO_TRUST_DIRNAME );
$modversion = $webphoto_inc_xoops_version->build_modversion();

?>