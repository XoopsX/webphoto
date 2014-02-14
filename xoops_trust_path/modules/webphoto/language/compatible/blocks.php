<?php
// $Id: blocks.php,v 1.3 2010/04/04 01:33:56 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-04-04 K.OHWADA
// use $mydirname
//---------------------------------------------------------

// test
if ( defined( 'FOR_XOOPS_LANG_CHECKER' ) ) {
	$MY_DIRNAME = 'webphoto' ;
}

if ( !isset( $MY_DIRNAME ) ) {
// call by altsys D3LanguageManager
	if ( isset( $mydirname ) ) {
		$MY_DIRNAME = $mydirname;

// probably error
	} elseif ( isset( $GLOBALS['MY_DIRNAME'] ) ) {
			$MY_DIRNAME = $GLOBALS['MY_DIRNAME'];
	} else {
		$MY_DIRNAME = 'webphoto' ;
	}
}

$constpref = strtoupper( '_BL_' . $MY_DIRNAME. '_' ) ;

//---------------------------------------------------------
// v1.40
//---------------------------------------------------------
if( !defined($constpref."TIMELINE_LATEST") ) 
{

define($constpref."TIMELINE_LATEST", "Number of latest photos");
define($constpref."TIMELINE_RANDOM", "Number of random photos");
define($constpref."TIMELINE_HEIGHT","Height of Timeline");
define($constpref."TIMELINE_SCALE", "Timeline scale") ;
define($constpref."TIMELINE_SCALE_WEEK",   "one week") ;
define($constpref."TIMELINE_SCALE_MONTH",  "one month") ;
define($constpref."TIMELINE_SCALE_YEAR",   "one year") ;
define($constpref."TIMELINE_SCALE_DECADE", "10 years") ;

}

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