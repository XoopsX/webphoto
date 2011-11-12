<?php
// $Id: header.php,v 1.2 2011/11/12 11:05:02 ohwada Exp $

//=========================================================
// webphoto module
// 2009-11-11 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-11-11 K.OHWAD
// class/inc/config.php
//---------------------------------------------------------

//---------------------------------------------------------
// $MY_DIRNAME WEBPHOTO_TRUST_PATH are set by caller
//---------------------------------------------------------

if ( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

include_once WEBPHOTO_TRUST_PATH.'/class/d3/optional.php';
include_once WEBPHOTO_TRUST_PATH.'/include/optional.php';

webphoto_include_once( 'include/constants.php',      $MY_DIRNAME );
webphoto_include_once( 'class/inc/ini.php',          $MY_DIRNAME );
webphoto_include_once( 'class/inc/handler.php',      $MY_DIRNAME );
webphoto_include_once( 'class/inc/base_ini.php',     $MY_DIRNAME );
webphoto_include_once( 'class/inc/xoops_config.php', $MY_DIRNAME );
webphoto_include_once( 'class/inc/config.php',       $MY_DIRNAME );

?>