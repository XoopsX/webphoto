<?php
// $Id: modinfo.php,v 1.5 2008/08/25 19:28:06 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

$constpref = strtoupper( '_MI_' . $GLOBALS['MY_DIRNAME']. '_' ) ;

// === define begin ===
if( !defined($constpref."LANG_LOADED") ) 
{

define($constpref."LANG_LOADED" , 1 ) ;

//=========================================================
// same as myalbum
//=========================================================

// The name of this module
define($constpref."NAME","Web Photo");

// A brief description of this module
define($constpref."DESC","Creates a photos section where users can search/submit/rate various photos.");

// Names of blocks for this module (Not all module has blocks)
define($constpref."BNAME_RECENT","Recent Photos");
define($constpref."BNAME_HITS","Top Photos");
define($constpref."BNAME_RANDOM","Random Photo");
define($constpref."BNAME_RECENT_P","Recent Photos with thumbnails");
define($constpref."BNAME_HITS_P","Top Photos with thumbnails");

// Config Items
define($constpref."CFG_PHOTOSPATH" , "Path to photos" ) ;
define($constpref."CFG_DESCPHOTOSPATH" , "Path from the directory installed XOOPS.<br />(The first character must be '/'. The last character should not be '/'.)<br />This directory's permission is 777 or 707 in unix." ) ;
define($constpref."CFG_THUMBSPATH" , "Path to thumbnails" ) ;
define($constpref."CFG_DESCTHUMBSPATH" , "Same as 'Path to photos'." ) ;
//define($constpref."CFG_USEIMAGICK" , "Use ImageMagick for treating images" ) ;
//define($constpref."CFG_DESCIMAGICK" , "Not use ImageMagick cause Not work resize or rotate the main photo, and make thumbnails by GD.<br />You'd better use ImageMagick if you can." ) ;
define($constpref."CFG_IMAGINGPIPE" , "Package treating images" ) ;
define($constpref."CFG_DESCIMAGINGPIPE" , "Almost all PHP environments can use GD. But GD is functionally inferior than 2 other packages.<br />It is best to use ImageMagick or NetPBM if you can." ) ;
define($constpref."CFG_FORCEGD2" , "Force GD2 conversion" ) ;
define($constpref."CFG_DESCFORCEGD2" , "Even if the GD is a bundled version of PHP, it force GD2(truecolor) conversion.<br />Some configured PHP fails to create thumbnails in GD2<br />This configuration is significant only when using GD" ) ;
define($constpref."CFG_IMAGICKPATH" , "Path of ImageMagick" ) ;
define($constpref."CFG_DESCIMAGICKPATH" , "Although the full path to 'convert' should be written, leave it blank in most environments.<br />This configuration is significant only when using ImageMagick" ) ;
define($constpref."CFG_NETPBMPATH" , "Path of NetPBM" ) ;
define($constpref."CFG_DESCNETPBMPATH" , "Alhough the full path to 'pnmscale' should be written, leave it blank in most environments.<br />This configuration is significant only when using NetPBM" ) ;
define($constpref."CFG_POPULAR" , "Hits to be Popular" ) ;
define($constpref."CFG_NEWDAYS" , "Days between displaying icon of 'new'&'update'" ) ;
define($constpref."CFG_NEWPHOTOS" , "Number of Photos as New on Top Page" ) ;

//define($constpref."CFG_DEFAULTORDER" , "Default order in category's view" ) ;

define($constpref."CFG_PERPAGE" , "Displayed Photos per Page" ) ;
define($constpref."CFG_DESCPERPAGE" , "Input selectable numbers separated with '|'<br />eg) 10|20|50|100" ) ;
define($constpref."CFG_ALLOWNOIMAGE" , "Allow a submit without images" ) ;
define($constpref."CFG_MAKETHUMB" , "Make Thumbnail Image" ) ;
define($constpref."CFG_DESCMAKETHUMB" , "When you change 'No' to 'Yes', You'd better 'Redo thumbnails'." ) ;

//define($constpref."CFG_THUMBWIDTH" , "Thumb Image Width" ) ;
//define($constpref."CFG_DESCTHUMBWIDTH" , "The height of thumbs will be decided from the width automatically." ) ;
//define($constpref."CFG_THUMBSIZE" , "Size of thumbnails (pixel)" ) ;

define($constpref."CFG_THUMBRULE" , "Calculation rule for building thumbnails" ) ;
define($constpref."CFG_WIDTH" , "Max photo width" ) ;
define($constpref."CFG_DESCWIDTH" , "This means the photo's width to be resized.<br />If you use GD without truecolor, this means the limitation of width." ) ;
define($constpref."CFG_HEIGHT" , "Max photo height" ) ;
define($constpref."CFG_DESCHEIGHT" , "This means the photo's height to be resized.<br />If you use GD without truecolor, this means the limitation of height." ) ;
define($constpref."CFG_FSIZE" , "Max file size" ) ;
define($constpref."CFG_DESCFSIZE" , "The limitation of the size of uploading file.(bytes)" ) ;

//define($constpref."CFG_MIDDLEPIXEL" , "Max image size in single view" ) ;
//define($constpref."CFG_DESCMIDDLEPIXEL" , "Specify (width)x(height)<br />(eg. 480x480)" ) ;

define($constpref."CFG_ADDPOSTS" , "The number added User's posts by posting a photo." ) ;
define($constpref."CFG_DESCADDPOSTS" , "Normally, 0 or 1. Under 0 mean 0" ) ;
define($constpref."CFG_CATONSUBMENU" , "Register top categories into submenu" ) ;
define($constpref."CFG_NAMEORUNAME" , "Poster name displayed" ) ;
define($constpref."CFG_DESCNAMEORUNAME" , "Select which 'name' is displayed" ) ;

//define($constpref."CFG_VIEWCATTYPE" , "Type of view in category" ) ;
define($constpref."CFG_VIEWTYPE" , "Type of view " ) ;

//define($constpref."CFG_COLSOFTABLEVIEW" , "Number of columns in table view" ) ;
define($constpref."CFG_COLSOFTABLE" , "Number of columns in table view" ) ;

//define($constpref."CFG_ALLOWEDEXTS" , "File extensions that can be uploaded" ) ;
//define($constpref."CFG_DESCALLOWEDEXTS" , "Input extensions with separator '|'. (eg 'jpg|jpeg|gif|png') .<br />All characters must be lowercase. Don't insert periods or spaces<br />Never add php or phtml etc." ) ;
//define($constpref."CFG_ALLOWEDMIME" , "MIME Types can be uploaded" ) ;
//define($constpref."CFG_DESCALLOWEDMIME" , "Input MIME Types with separator '|'. (eg 'image/gif|image/jpeg|image/png')<br />If you want to be checked by MIME Type, leave this blank" ) ;

define($constpref."CFG_USESITEIMG" , "Use [siteimg] in ImageManager Integration" ) ;
define($constpref."CFG_DESCUSESITEIMG" , "The Integrated Image Manager input [siteimg] instead of [img].<br />You have to hack module.textsanitizer.php for each module to enable tag of [siteimg]" ) ;

define($constpref."OPT_USENAME" , "Real Name" ) ;
define($constpref."OPT_USEUNAME" , "Login Name" ) ;

define($constpref."OPT_CALCFROMWIDTH" , "width:specified  height:auto" ) ;
define($constpref."OPT_CALCFROMHEIGHT" , "width:auto  width:specified" ) ;
define($constpref."OPT_CALCWHINSIDEBOX" , "put in specified size squre" ) ;

define($constpref."OPT_VIEWLIST" , "List View" ) ;
define($constpref."OPT_VIEWTABLE" , "Table View" ) ;

// Sub menu titles
//define($constpref."TEXT_SMNAME1","Submit");
//define($constpref."TEXT_SMNAME2","Popular");
//define($constpref."TEXT_SMNAME3","Top Rated");
//define($constpref."TEXT_SMNAME4","My Photos");

// Names of admin menu items
//define($constpref."ADMENU0","Submitted Photos");
//define($constpref."ADMENU1","Photo Management");
//define($constpref."ADMENU2","Add/Edit Categories");
//define($constpref."ADMENU_GPERM","Global Permissions");
//define($constpref."ADMENU3","Check Configuration & Environment");
//define($constpref."ADMENU4","Batch Register");
//define($constpref."ADMENU5","Rebuild Thumbnails");
//define($constpref."ADMENU_IMPORT","Import Images");
//define($constpref."ADMENU_EXPORT","Export Images");
//define($constpref."ADMENU_MYBLOCKSADMIN","Blocks & Groups Admin");
//define($constpref."ADMENU_MYTPLSADMIN","Templates");


// Text for notifications
define($constpref."GLOBAL_NOTIFY", "Global");
define($constpref."GLOBAL_NOTIFYDSC", "Global notification options");
define($constpref."CATEGORY_NOTIFY", "Category");
define($constpref."CATEGORY_NOTIFYDSC", "Notification options that apply to the current photo category");
define($constpref."PHOTO_NOTIFY", "Photo");
define($constpref."PHOTO_NOTIFYDSC", "Notification options that apply to the current photo");

define($constpref."GLOBAL_NEWPHOTO_NOTIFY", "New Photo");
define($constpref."GLOBAL_NEWPHOTO_NOTIFYCAP", "Notify me when any new photos are posted");
define($constpref."GLOBAL_NEWPHOTO_NOTIFYDSC", "Receive notification when a new photo description is posted.");
define($constpref."GLOBAL_NEWPHOTO_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE}: auto-notify : New photo");

define($constpref."CATEGORY_NEWPHOTO_NOTIFY", "New Photo");
define($constpref."CATEGORY_NEWPHOTO_NOTIFYCAP", "Notify me when a new photo is posted to the current category");
define($constpref."CATEGORY_NEWPHOTO_NOTIFYDSC", "Receive notification when a new photo description is posted to the current category");
define($constpref."CATEGORY_NEWPHOTO_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE}: auto-notify : New photo");


//=========================================================
// add for webphoto
//=========================================================

// Config Items
define($constpref."CFG_SORT" , "Default order in list view" ) ;
define($constpref."OPT_SORT_IDA","Record Number (Smaller to Bigger)");
define($constpref."OPT_SORT_IDD","Record Number (Smaller is latter)");
define($constpref."OPT_SORT_HITSA","Popularity (Least to Most Hits)");
define($constpref."OPT_SORT_HITSD","Popularity (Most to Least Hits)");
define($constpref."OPT_SORT_TITLEA","Title (A to Z)");
define($constpref."OPT_SORT_TITLED","Title (Z to A)");
define($constpref."OPT_SORT_DATEA","Updated Date (Old Photos Listed First)");
define($constpref."OPT_SORT_DATED","Updated Date (New Photos Listed First)");
define($constpref."OPT_SORT_RATINGA","Rating (Lowest Score to Highest Score)");
define($constpref."OPT_SORT_RATINGD","Rating (Highest Score to Lowest Score)");
define($constpref."OPT_SORT_RANDOM","Random");

define($constpref."CFG_GICONSPATH" , "Path to Google Icons" ) ;

//define($constpref."CFG_TMPPATH" ,   "Path to temporary" ) ;

define($constpref."CFG_MIDDLE_WIDTH" ,  "Image Width in single view" ) ;
define($constpref."CFG_MIDDLE_HEIGHT" , "Image Height in single view" ) ;
define($constpref."CFG_THUMB_WIDTH" ,  "Thumb Image Width" ) ;
define($constpref."CFG_THUMB_HEIGHT" , "Thumb Image Height" ) ;

define($constpref."CFG_APIKEY","Google API Key");
define($constpref."CFG_APIKEY_DSC", 'Get the API key on <br/><a href="http://www.google.com/apis/maps/signup.html" target="_blank">Sign Up for the Google Maps API</a><br /><br />For the details of the parameter, see the following<br /><a href="http://www.google.com/apis/maps/documentation/reference.html" target="_blank">Google Maps API Reference</a>' );
define($constpref."CFG_LATITUDE",  "Latitude");
define($constpref."CFG_LONGITUDE", "Longitude");
define($constpref."CFG_ZOOM", "Zoom Level");

define($constpref."CFG_USE_POPBOX","Use PopBox");

define($constpref."CFG_INDEX_DESC", "Introductory Text in main page");
define($constpref."CFG_INDEX_DESC_DEFAULT", "Here is where your page introduction goes.<br />You can edit it at Preferences");

// Sub menu titles
define($constpref."SMNAME_SUBMIT","Submit");
define($constpref."SMNAME_POPULAR","Popular");
define($constpref."SMNAME_HIGHRATE","Top Rated");
define($constpref."SMNAME_MYPHOTO","My Photos");

// Names of admin menu items
define($constpref."ADMENU_ADMISSION","Admitting images");
define($constpref."ADMENU_PHOTOMANAGER","Photo Management");
define($constpref."ADMENU_CATMANAGER","Add/Edit Categories");
define($constpref."ADMENU_CHECKCONFIGS","Check Configuration");
define($constpref."ADMENU_BATCH","Batch Register");
define($constpref."ADMENU_REDOTHUMB","Rebuild Thumbnails");
define($constpref."ADMENU_GROUPPERM","Global Permissions");
define($constpref."ADMENU_IMPORT","Import Images");
define($constpref."ADMENU_EXPORT","Export Images");

define($constpref."ADMENU_GICONMANAGER","Google Icons Management");
define($constpref."ADMENU_MIMETYPES","MIME Type Management");
define($constpref."ADMENU_IMPORT_MYALBUM","Batch Import from Myalbum");
define($constpref."ADMENU_CHECKTABLES","Check Tables Configuration");
define($constpref."ADMENU_PHOTO_TABLE_MANAGE","Photo Table Management");
define($constpref."ADMENU_CAT_TABLE_MANAGE","Category Table Management");
define($constpref."ADMENU_VOTE_TABLE_MANAGE","Vote Table Management");
define($constpref."ADMENU_GICON_TABLE_MANAGE","Google Icon Table Management");
define($constpref."ADMENU_MIME_TABLE_MANAGE","MIME Table Management");
define($constpref."ADMENU_TAG_TABLE_MANAGE","Tag Table Management");
define($constpref."ADMENU_P2T_TABLE_MANAGE","Photo-Tag Table Management");
define($constpref."ADMENU_SYNO_TABLE_MANAGE","Synonym Table Management");

//---------------------------------------------------------
// v0.20
//---------------------------------------------------------
define( $constpref."CFG_USE_FFMPEG"  , "Use ffmpeg" ) ;
define( $constpref."CFG_FFMPEGPATH"  , "Path to ffmpeg" ) ;
define( $constpref."CFG_DESCFFMPEGPATH" , "Alhough the full path to 'ffmpeg' should be written, leave it blank in most environments.<br />This configuration is significant only when 'Use ffmpeg' is yes" ) ;
define($constpref."CFG_USE_PATHINFO","Use pathinfo");

//---------------------------------------------------------
// v0.30
//---------------------------------------------------------
define($constpref."CFG_TMPDIR" ,   "Path to temporary" ) ;
define($constpref."CFG_TMPDIR_DSC" , "Fill the fullpath (The first character must be '/'. The last character should not be '/'.)<br />Recommend to set to this out of the document route.");
define($constpref."CFG_MAIL_HOST"  , "Mail Server Hostname" ) ;
define($constpref."CFG_MAIL_USER"  , "Mail User ID" ) ;
define($constpref."CFG_MAIL_PASS"  , "Mail Password" ) ;
define($constpref."CFG_MAIL_ADDR"  , "Mail Addresss" ) ;
define($constpref."CFG_MAIL_CHARSET"  , "Mail Charset" ) ;
define($constpref."CFG_MAIL_CHARSET_DSC" , "Input Charset with separator '|'.<br />If you want not to be checked by MIME Type, leave this blank" ) ;
define($constpref."CFG_MAIL_CHARSET_LIST","ISO-8859-1|US-ASCII");
define($constpref."CFG_FILE_DIR"  , "Path to files by FTP" ) ;
define($constpref."CFG_FILE_DIR_DSC" , "Fill the fullpath (The first character must be '/'. The last character should not be '/'.)Recommend to set to this out of the document route." ) ;
define($constpref."CFG_FILE_SIZE"  , "Max file size by FTP (byte)" ) ;
define($constpref."CFG_FILE_DESC"  , "FTP Help Description");
define($constpref."CFG_FILE_DESC_DSC"  , "Show in Help, when has the permission of 'Post by FTP' ");
define($constpref."CFG_FILE_DESC_TEXT"  , "
<b>FTP Server</b><br />
FTP Server Host: xxx<br />
FTP UserID: xxx<br />
FTP Password: xxx<br />" ) ;

define($constpref."ADMENU_MAILLOG_MANAGER","Maillog Management");
define($constpref."ADMENU_MAILLOG_TABLE_MANAGE","Maillog Table Management");
define($constpref."ADMENU_USER_TABLE_MANAGE","User Aux Table Management");

//---------------------------------------------------------
// v0.40
//---------------------------------------------------------
define($constpref."CFG_BIN_PASS" , "Command Password" ) ;

define($constpref."ADMENU_UPDATE", "Update");
define($constpref."ADMENU_ITEM_TABLE_MANAGE", "Item Table Management");
define($constpref."ADMENU_FILE_TABLE_MANAGE", "File Table Management");

}
// === define begin ===

?>