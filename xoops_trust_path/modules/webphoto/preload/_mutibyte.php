<?php
// $Id: _mutibyte.php,v 1.1 2009/08/09 05:47:32 ohwada Exp $

//=========================================================
// webphoto module
// 2009-08-08 K.OHWADA
//=========================================================

echo "_C_WEBPHOTO_MULTIBYTE_LOADED";

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

// === define begin ===
if( !defined("_C_WEBPHOTO_MULTIBYTE_LOADED") ) 
{

define("_C_WEBPHOTO_MULTIBYTE_LOADED", 1 ) ;

//=========================================================
// Constant
//=========================================================

// priority : mbstring
define("_C_WEBPHOTO_MULTIBYTE_FUNC_SEL", "0" ) ;

// === define end ===
}

?>