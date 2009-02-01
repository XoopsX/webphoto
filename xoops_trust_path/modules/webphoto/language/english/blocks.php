<?php
// $Id: blocks.php,v 1.6 2009/02/01 11:02:38 ohwada Exp $

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

//---------------------------------------------------------
// v0.20
//---------------------------------------------------------
define($constpref."POPBOX_REVERT", "Click the image to shrink it.");

//---------------------------------------------------------
// v0.30
//---------------------------------------------------------
define($constpref."TEXT_CACHETIME", "Cache Time");

//---------------------------------------------------------
// v0.80
//---------------------------------------------------------
define($constpref."TEXT_CATLIST_SUB", "Show sub category");
define($constpref."TEXT_CATLIST_MAIN_IMG", "Show image of main category");
define($constpref."TEXT_CATLIST_SUB_IMG", "Show image of sub category");
define($constpref."TEXT_CATLIST_COLS", "Number of columns");
define($constpref."TEXT_TAGCLOUD_LIMIT", "Number of tags");

//---------------------------------------------------------
// v1.20
//---------------------------------------------------------
// google map
define($constpref."GMAP_MODE","GoogleMap Mode");
define($constpref."GMAP_MODE_NONE","Not show");
define($constpref."GMAP_MODE_DEFAULT","Default");
define($constpref."GMAP_MODE_SET","Following value");
define($constpref."GMAP_LATITUDE","Latitude");
define($constpref."GMAP_LONGITUDE","Longitude");
define($constpref."GMAP_ZOOM","Zoom");
define($constpref."GMAP_HEIGHT","Height of Map");
define($constpref."PIXEL", "Pixel");

// === define end ===
}

?>