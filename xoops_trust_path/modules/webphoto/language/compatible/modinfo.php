<?php
// $Id: modinfo.php,v 1.6 2009/04/12 02:49:35 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-01 K.OHWADA
//=========================================================

// test
if ( defined( 'FOR_XOOPS_LANG_CHECKER' ) ) {
	$MY_DIRNAME = 'webphoto' ;

// normal
} elseif (  isset($GLOBALS['MY_DIRNAME']) ) {
	$MY_DIRNAME = $GLOBALS['MY_DIRNAME'];

// call by altsys/mytplsadmin.php
} elseif ( $mydirname ) {
	$MY_DIRNAME = $mydirname;

// probably error
} else {
	echo "not set dirname in ". __FILE__ ." <br />\n";
	$MY_DIRNAME = 'webphoto' ;
}

$constpref = strtoupper( '_MI_' . $MY_DIRNAME. '_' ) ;

//---------------------------------------------------------
// v1.40
//---------------------------------------------------------
if( !defined($constpref."CFG_TIMELINE_LATEST") ) 
{

// timeline
define($constpref."CFG_TIMELINE_LATEST", "Number of latest photos in timeline");
define($constpref."CFG_TIMELINE_RANDOM", "Number of random photos in timeline");
define($constpref."BNAME_TIMELINE" , "Timeline" ) ;

// map, tag
define($constpref."CFG_GMAP_PHOTOS", "Number of photos in map");
define($constpref."CFG_TAGS", "Number of tags in tagcloud");

}

//---------------------------------------------------------
// v1.30
//---------------------------------------------------------
if( !defined($constpref."CFG_SMALL_WIDTH") ) 
{

define($constpref."CFG_SMALL_WIDTH" ,  "Image Width in timeline" ) ;
define($constpref."CFG_SMALL_HEIGHT" , "Image Height in timeline" ) ;
define($constpref."CFG_TIMELINE_DIRNAME", "timeline dirname" ) ;
define($constpref."CFG_TIMELINE_DIRNAME_DSC", "Set dirname of timeline module" ) ;
define($constpref."CFG_TIMELINE_SCALE", "Timeline scale") ;
define($constpref."CFG_TIMELINE_SCALE_DSC", "Time scale in about 600px width" ) ;
define($constpref."OPT_TIMELINE_SCALE_WEEK",   "one week") ;
define($constpref."OPT_TIMELINE_SCALE_MONTH",  "one month") ;
define($constpref."OPT_TIMELINE_SCALE_YEAR",   "one year") ;
define($constpref."OPT_TIMELINE_SCALE_DECADE", "10 years") ;

}

//---------------------------------------------------------
// modinfo for French
//---------------------------------------------------------
if( !defined($constpref."ADMENU_RSS_MANAGER") ) 
{
//---------------------------------------------------------
// v1.21
//---------------------------------------------------------
define($constpref."ADMENU_RSS_MANAGER", "RSS Manager");

}
// === define begin ===

//---------------------------------------------------------
// modinfo for Portugues.do.Brasil
//---------------------------------------------------------
// === define begin ===
if( !defined($constpref."CFG_UPLOADSPATH") ) 
{

//---------------------------------------------------------
// v0.50
//---------------------------------------------------------
define($constpref."CFG_UPLOADSPATH" , "Path to upload files" ) ;
define($constpref."CFG_UPLOADSPATH_DSC" , "Path from the directory installed XOOPS.<br />(The first character must be '/'. The last character should not be '/'.)<br />This directory's permission is 777 or 707 in unix." ) ;
define($constpref."CFG_MEDIASPATH" , "Path to medias" ) ;
define($constpref."CFG_MEDIASPATH_DSC" , "The directory where there are media files which are created the playlist. <br />Path from the directory installed XOOPS.<br />(The first character must be '/'. The last character should not be '/'.)" ) ;
define($constpref."CFG_LOGO_WIDTH" ,  "Player Logo Width and Height" ) ;
define($constpref."CFG_USE_CALLBACK", "Use callback log");
define($constpref."CFG_USE_CALLBACK_DSC", "loggin Flash Player events by callback.");

define($constpref."ADMENU_ITEM_MANAGER", "Item Management");
define($constpref."ADMENU_PLAYER_MANAGER", "Player Management");
define($constpref."ADMENU_FLASHVAR_MANAGER", "Flashvar Management");
define($constpref."ADMENU_PLAYER_TABLE_MANAGE", "Player Table Management");
define($constpref."ADMENU_FLASHVAR_TABLE_MANAGE", "Flashvar Table Management");

//---------------------------------------------------------
// v0.60
//---------------------------------------------------------
define($constpref."CFG_WORKDIR" ,   "Work Directory Path" ) ;
define($constpref."CFG_WORKDIR_DSC" , "Fill the fullpath (The first character must be '/'. The last character should not be '/'.)<br />Recommend to set to this out of the document route.");
define($constpref."CFG_CAT_WIDTH" ,   "Category Image Width and Height" ) ;
define($constpref."CFG_CSUB_WIDTH" ,  "Image Width and Height in Sub Category" ) ;
define($constpref."CFG_GICON_WIDTH" ,  "GoogleMap Icon Width and Height" ) ;
define($constpref."CFG_JPEG_QUALITY" ,  "JPEG Quality" ) ;
define($constpref."CFG_JPEG_QUALITY_DSC" ,  "1 - 100 <br />This configuration is significant only when using GD" ) ;

//---------------------------------------------------------
// v0.80
//---------------------------------------------------------
define($constpref."BNAME_CATLIST"  , "Category List" ) ;
define($constpref."BNAME_TAGCLOUD" , "Tag Cloud" ) ;

//---------------------------------------------------------
// v0.90
//---------------------------------------------------------
define($constpref."CFG_PERM_CAT_READ"      , "Permission of Category" ) ;
define($constpref."CFG_PERM_CAT_READ_DSC"  , "Enable with the setting of Category table" ) ;
define($constpref."CFG_PERM_ITEM_READ"     , "Permission of Item" ) ;
define($constpref."CFG_PERM_ITEM_READ_DSC" , "Enable with the setting of Item table" ) ;
define($constpref."OPT_PERM_READ_ALL"     , "Show ALL" ) ;
define($constpref."OPT_PERM_READ_NO_ITEM" , "Not show Items" ) ;
define($constpref."OPT_PERM_READ_NO_CAT"  , "Not show Categories and Items" ) ;

//---------------------------------------------------------
// v1.10
//---------------------------------------------------------
define($constpref."CFG_USE_XPDF"  , "Use xpdf" ) ;
define($constpref."CFG_XPDFPATH"  , "Path to xpdf" ) ;
define($constpref."CFG_XPDFPATH_DSC" , "Alhough the full path to 'pdftoppm' should be written, leave it blank in most environments.<br />This configuration is significant only when using xpdf" ) ;

}
// === define begin ===

?>