<?php
// $Id: i.php,v 1.4 2009/01/24 07:10:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-08-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-12-29 K.OHWADA
// imode.php
// 2008-12-29 K.OHWADA
// item_public.php
// 2008-12-07 K.OHWADA
// imagemagick.php
//---------------------------------------------------------

if( ! defined( 'WEBPHOTO_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// xoops system files
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH.'/class/template.php';

//---------------------------------------------------------
// webphoto files
//---------------------------------------------------------
if( !defined("WEBPHOTO_DIRNAME") ) {
	  define("WEBPHOTO_DIRNAME", $MY_DIRNAME );
}
if( !defined("WEBPHOTO_ROOT_PATH") ) {
	  define("WEBPHOTO_ROOT_PATH", XOOPS_ROOT_PATH.'/modules/'.WEBPHOTO_DIRNAME );
}

include_once WEBPHOTO_TRUST_PATH.'/class/d3/optional.php';
include_once WEBPHOTO_TRUST_PATH.'/include/optional.php';

webphoto_include_once( 'main/header.php' );
webphoto_include_once( 'class/lib/mail_pop.php' );
webphoto_include_once( 'class/lib/mail_parse.php' );
webphoto_include_once( 'class/lib/user_agent.php' );
webphoto_include_once( 'class/webphoto/item_public.php' );
webphoto_include_once( 'class/webphoto/imode.php' );
webphoto_include_once( 'class/main/i.php' );

webphoto_include_language( 'extra.php' );

//=========================================================
// main
//=========================================================
$manage =& webphoto_main_i::getInstance( WEBPHOTO_DIRNAME , WEBPHOTO_TRUST_DIRNAME );

$manage->main();

?>