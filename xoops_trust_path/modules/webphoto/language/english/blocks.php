<?php
// $Id: blocks.php,v 1.1 2008/06/21 12:22:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

$constpref = strtoupper( '_BL_' . $GLOBALS['MY_DIRNAME']. '_' ) ;

// === define begin ===
if( !defined($constpref."LANG_LOADED") ) 
{

define($constpref."LANG_LOADED" , 1 ) ;

//=========================================================
// same as myalbum
//=========================================================

define($constpref."BTITLE_TOPNEW","Recent Photos");
define($constpref."BTITLE_TOPHIT","Top Photos");
define($constpref."BTITLE_RANDOM","Random Photo");
define($constpref."TEXT_DISP","Display");
define($constpref."TEXT_STRLENGTH","Max length of photo's title");
define($constpref."TEXT_CATLIMITATION","Limit by category");
define($constpref."TEXT_CATLIMITRECURSIVE","with Sub-categories");
define($constpref."TEXT_BLOCK_WIDTH","Displays max");
define($constpref."TEXT_BLOCK_WIDTH_NOTES","(if you set this to 0, the thumbnail image displays in its original size.)");
define($constpref."TEXT_RANDOMCYCLE","Switching cycle of random images (sec)");
define($constpref."TEXT_COLS","Columns of Photos");

// === define end ===
}

?>