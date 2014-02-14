<?php
// $Id: _jodconverter.php,v 1.1 2009/02/01 14:32:06 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-25 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

// === define begin ===
if( !defined("_C_WEBPHOTO_JODCONVERTER_LOADED") ) 
{

define("_C_WEBPHOTO_JODCONVERTER_LOADED", 1 ) ;

//=========================================================
// Constant
//=========================================================

define("_C_WEBPHOTO_JAVA_PATH", "/usr/bin/" ) ;
define("_C_WEBPHOTO_JODCONVERTER_JAR", "/usr/local/java/jodconverter-2.2.1/lib/jodconverter-cli-2.2.1.jar" ) ;

// === define end ===
}

?>