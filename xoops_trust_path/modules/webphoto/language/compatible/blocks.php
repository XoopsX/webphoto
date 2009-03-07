<?php
// $Id: blocks.php,v 1.1 2009/03/07 07:37:16 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-01 K.OHWADA
//=========================================================

$constpref = strtoupper( '_BL_' . $GLOBALS['MY_DIRNAME']. '_' ) ;

//---------------------------------------------------------
// blocks for Portugues.do.Brasil
//---------------------------------------------------------
// === define begin ===
if( !defined($constpref."TEXT_CATLIST_SUB") ) 
{

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