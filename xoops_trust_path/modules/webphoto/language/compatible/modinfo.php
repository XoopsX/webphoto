<?php
// $Id: modinfo.php,v 1.15 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-04-04 K.OHWADA
// remove echo
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

$constpref = strtoupper( '_MI_' . $MY_DIRNAME. '_' ) ;

//---------------------------------------------------------
// v2.60
//---------------------------------------------------------
if( !defined($constpref."OPT_TIMELINE_SCALE_HOUR") ) 
{
define($constpref."OPT_TIMELINE_SCALE_HOUR",       "Hour") ;
define($constpref."OPT_TIMELINE_SCALE_DAY",        "Day") ;
define($constpref."OPT_TIMELINE_SCALE_CENTURY",    "Century") ;
define($constpref."OPT_TIMELINE_SCALE_MILLENNIUM", "Millennium") ;
}

//---------------------------------------------------------
// v2.40
//---------------------------------------------------------
if( !defined($constpref."CFG_PEAR_PATH") ) 
{
define($constpref."CFG_PEAR_PATH", 'Path of PEAR libraly');
define($constpref."CFG_PEAR_PATH_DSC", 'Enter the absolute path in the PEAR library with Net_POP3.<br />When not enter, modules/webphoto/PEAR is used.');
}

//---------------------------------------------------------
// v2.20
//---------------------------------------------------------
if( !defined($constpref."CFG_EMBED_WIDTH") ) 
{
define($constpref."CFG_EMBED_WIDTH", "Screen width of video site");
define($constpref."CFG_EMBED_HEIGHT","Screen height of video site");
}

//---------------------------------------------------------
// v2.11
//---------------------------------------------------------
if( !defined($constpref."ADMENU_REDOTHUMBS") ) 
{
define($constpref."ADMENU_REDOTHUMBS","Rebuild Thumbnails");
}

//---------------------------------------------------------
// v2.10
//---------------------------------------------------------
if( !defined($constpref."CFG_USE_LIGHTBOX") ) 
{
define($constpref."CFG_USE_LIGHTBOX","Use LightBox");
}

//---------------------------------------------------------
// v2.00
//---------------------------------------------------------
if( !defined($constpref."CFG_GROUPID_ADMIN") ) 
{

// config
define($constpref."CFG_GROUPID_ADMIN"  , "Admin Group ID" ) ;
define($constpref."CFG_GROUPID_ADMIN_DSC" , "The user group ID of the administrator of this module. <br />This value is set in module installation. <br />Don't change rashly. " ) ;
define($constpref."CFG_GROUPID_USER"  , "User Group ID" ) ;
define($constpref."CFG_GROUPID_USER_DSC" , "The user group ID of the user of this module. <br />This value is set in module installation. <br />Don't change rashly. " ) ;

// admin menu
define($constpref."ADMENU_INVITE", "Invite a friend");

// notifications
define($constpref."GLOBAL_WAITING_NOTIFY", "Waiting Approval");
define($constpref."GLOBAL_WAITING_NOTIFYCAP", "Notify me when any new photos waiting approval are posted (Admin)");
define($constpref."GLOBAL_WAITING_NOTIFYDSC", "Notify me when any new photos waiting approval are posted");
define($constpref."GLOBAL_WAITING_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE}: auto-notify : Waiting Approval");
}

//---------------------------------------------------------
// v1.80
//---------------------------------------------------------
if( !defined($constpref."CFG_USE_LAME") ) 
{
define($constpref."CFG_USE_LAME"  , "Use lame" ) ;
define($constpref."CFG_LAMEPATH"  , "Path to lame" ) ;
define($constpref."CFG_LAMEPATH_DSC" , "Alhough the full path to 'lame' should be written, leave it blank in most environments.<br />This configuration is significant only when using lame" ) ;
define($constpref."CFG_USE_TIMIDITY"  , "Use timidity" ) ;
define($constpref."CFG_TIMIDITYPATH"  , "Path to timidity" ) ;
define($constpref."CFG_TIMIDITYPATH_DSC" , "Alhough the full path to 'timidity' should be written, leave it blank in most environments.<br />This configuration is significant only when using timidity" ) ;
define($constpref."SMNAME_SEARCH","Search");
}

//---------------------------------------------------------
// v1.70
//---------------------------------------------------------
if( !defined($constpref."CFG_ITEM_SUMMARY") ) 
{
define($constpref."CFG_ITEM_SUMMARY", "Max characters of photo description");
define($constpref."CFG_ITEM_SUMMARY_DSC", "Enter the maximum number of characters of photo description in the photo list.<br />-1 is unlimited");
define($constpref."CFG_CAT_SUMMARY", "Max characters of category description");
define($constpref."CFG_CAT_SUMMARY_DSC", "Enter the maximum number of characters of category description in the category list.<br />-1 is unlimited");
define($constpref."CFG_CAT_CHILD", "Show photos of subcategoies");
define($constpref."CFG_CAT_CHILD_DSC", "Enter to show or not photos of subcategoies in category list");
define($constpref."OPT_CAT_CHILD_NON", "Photos of this category only. Not show photos of subcategoies");
define($constpref."OPT_CAT_CHILD_EMPTY", "When no photo in this category, show photos of subcategoies");
define($constpref."OPT_CAT_CHILD_ALWAYS", "Show alwayas photos of subcategoies");
}

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