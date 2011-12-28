<?php
// $Id: modinfo.php,v 1.27 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// Photo -> Photo Video Media
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

// === define begin ===
if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || !defined($constpref."LANG_LOADED") ) 
{

define($constpref."LANG_LOADED" , 1 ) ;

//=========================================================
// same as myalbum
//=========================================================

// The name of this module
define($constpref."NAME","Web Photo Video Media");

// A brief description of this module
define($constpref."DESC","Creates a photos section where users can search/submit/rate various photos.");

// Names of blocks for this module (Not all module has blocks)
define($constpref."BNAME_RECENT","Recent Photo Video Media");
define($constpref."BNAME_HITS","Top Photo Video Media");
define($constpref."BNAME_RANDOM","Random Photo Video Media");
define($constpref."BNAME_RECENT_P","Recent Photo Video Media with thumbnails");
define($constpref."BNAME_HITS_P","Top Photo Video Media with thumbnails");

// Config Items
//define($constpref."CFG_PHOTOSPATH" , "Path to photos" ) ;
//define($constpref."CFG_DESCPHOTOSPATH" , "Path from the directory installed XOOPS.<br />(The first character must be '/'. The last character should not be '/'.)<br />This directory's permission is 777 or 707 in unix." ) ;
//define($constpref."CFG_THUMBSPATH" , "Path to thumbnails" ) ;
//define($constpref."CFG_DESCTHUMBSPATH" , "Same as 'Path to photos'." ) ;
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
define($constpref."CFG_NEWPHOTOS" , "Number of Photo Video Media as New on Top Page" ) ;

//define($constpref."CFG_DEFAULTORDER" , "Default order in category's view" ) ;

define($constpref."CFG_PERPAGE" , "Displayed Photo Video Media per Page" ) ;
define($constpref."CFG_DESCPERPAGE" , "Input selectable numbers separated with '|'<br />eg) 10|20|50|100" ) ;
define($constpref."CFG_ALLOWNOIMAGE" , "Allow a submit without images" ) ;
define($constpref."CFG_MAKETHUMB" , "Make Thumbnail Image" ) ;
define($constpref."CFG_DESCMAKETHUMB" , "When you change 'No' to 'Yes', You'd better 'Redo thumbnails'." ) ;

//define($constpref."CFG_THUMBWIDTH" , "Thumb Image Width" ) ;
//define($constpref."CFG_DESCTHUMBWIDTH" , "The height of thumbs will be decided from the width automatically." ) ;
//define($constpref."CFG_THUMBSIZE" , "Size of thumbnails (pixel)" ) ;
//define($constpref."CFG_THUMBRULE" , "Calculation rule for building thumbnails" ) ;

// v2.30
//define($constpref."CFG_WIDTH" , "Max photo width" ) ;
//define($constpref."CFG_DESCWIDTH" , "This means the photo's width to be resized.<br />If you use GD without truecolor, this means the limitation of width." ) ;
//define($constpref."CFG_HEIGHT" , "Max photo height" ) ;
//define($constpref."CFG_DESCHEIGHT" , "This means the photo's height to be resized.<br />If you use GD without truecolor, this means the limitation of height." ) ;
define($constpref."CFG_WIDTH" , "Image Width in popup" ) ;
define($constpref."CFG_DESCWIDTH" , "Image size when popup. <br /><br />In old version, this parameter was the limitation of the image size when upload. <br />This limitation was abolished. <br />The specification was changed in V2.30. 
" ) ;
define($constpref."CFG_HEIGHT" , "Image Height in popup" ) ;
define($constpref."CFG_DESCHEIGHT" , "Same as Image Width" ) ;

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

//define($constpref."OPT_CALCFROMWIDTH" , "width:specified  height:auto" ) ;
//define($constpref."OPT_CALCFROMHEIGHT" , "width:auto  width:specified" ) ;
//define($constpref."OPT_CALCWHINSIDEBOX" , "put in specified size squre" ) ;

define($constpref."OPT_VIEWLIST" , "List View" ) ;
define($constpref."OPT_VIEWTABLE" , "Table View" ) ;

// Sub menu titles
//define($constpref."TEXT_SMNAME1","Submit");
//define($constpref."TEXT_SMNAME2","Popular");
//define($constpref."TEXT_SMNAME3","Top Rated");
//define($constpref."TEXT_SMNAME4","My Photo Video Media");

// Names of admin menu items
//define($constpref."ADMENU0","Submitted Photo Video Media");
//define($constpref."ADMENU1","Photo Video Media Management");
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
define($constpref."PHOTO_NOTIFY", "Photo Video Media");
define($constpref."PHOTO_NOTIFYDSC", "Notification options that apply to the current photo");

define($constpref."GLOBAL_NEWPHOTO_NOTIFY", "New Photo Video Media");
define($constpref."GLOBAL_NEWPHOTO_NOTIFYCAP", "Notify me when any new photos are posted");
define($constpref."GLOBAL_NEWPHOTO_NOTIFYDSC", "Receive notification when a new photo description is posted.");
define($constpref."GLOBAL_NEWPHOTO_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE}: auto-notify : New photo");

define($constpref."CATEGORY_NEWPHOTO_NOTIFY", "New Photo Video Media");
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
define($constpref."OPT_SORT_DATEA","Updated Date (Old Photo Video Media Listed First)");
define($constpref."OPT_SORT_DATED","Updated Date (New Photo Video Media Listed First)");
define($constpref."OPT_SORT_RATINGA","Rating (Lowest Score to Highest Score)");
define($constpref."OPT_SORT_RATINGD","Rating (Highest Score to Lowest Score)");
define($constpref."OPT_SORT_RANDOM","Random");

//define($constpref."CFG_GICONSPATH" , "Path to Google Icons" ) ;
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
define($constpref."SMNAME_MYPHOTO","My Photo Video Media");

// Names of admin menu items
//define($constpref."ADMENU_ADMISSION","Admitting images");

define($constpref."ADMENU_PHOTOMANAGER","Photo Video Media Management");
define($constpref."ADMENU_CATMANAGER","Add/Edit Categories");
define($constpref."ADMENU_CHECKCONFIGS","Check Configuration");
define($constpref."ADMENU_BATCH","Batch Register");
//define($constpref."ADMENU_REDOTHUMB","Rebuild Thumbnails");
define($constpref."ADMENU_GROUPPERM","Global Permissions");
define($constpref."ADMENU_IMPORT","Import Images");
define($constpref."ADMENU_EXPORT","Export Images");

define($constpref."ADMENU_GICONMANAGER","Google Icons Management");
define($constpref."ADMENU_MIMETYPES","MIME Type Management");
define($constpref."ADMENU_IMPORT_MYALBUM","Batch Import from Myalbum");
define($constpref."ADMENU_CHECKTABLES","Check Tables Configuration");
define($constpref."ADMENU_PHOTO_TABLE_MANAGE","Photo Video Media Table Management");
define($constpref."ADMENU_CAT_TABLE_MANAGE","Category Table Management");
define($constpref."ADMENU_VOTE_TABLE_MANAGE","Vote Table Management");
define($constpref."ADMENU_GICON_TABLE_MANAGE","Google Icon Table Management");
define($constpref."ADMENU_MIME_TABLE_MANAGE","MIME Table Management");
define($constpref."ADMENU_TAG_TABLE_MANAGE","Tag Table Management");
define($constpref."ADMENU_P2T_TABLE_MANAGE","Photo Video Media-Tag Table Management");
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
//define($constpref."CFG_TMPDIR" ,   "Path to temporary" ) ;
//define($constpref."CFG_TMPDIR_DSC" , "Fill the fullpath (The first character must be '/'. The last character should not be '/'.)<br />Recommend to set to this out of the document route.");

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
define($constpref."CFG_COM_DIRNAME",  "Comment-integration: dirname of d3forum");
define($constpref."CFG_COM_FORUM_ID", "Comment-integration: forum ID");
define($constpref."CFG_COM_VIEW",     "View of Comment-integration");

define($constpref."ADMENU_UPDATE", "Update");
define($constpref."ADMENU_ITEM_TABLE_MANAGE", "Item Table Management");
define($constpref."ADMENU_FILE_TABLE_MANAGE", "File Table Management");

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

//---------------------------------------------------------
// v1.21
//---------------------------------------------------------
define($constpref."ADMENU_RSS_MANAGER", "RSS Manager");

//---------------------------------------------------------
// v1.30
//---------------------------------------------------------
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

//---------------------------------------------------------
// v1.40
//---------------------------------------------------------
// timeline
define($constpref."CFG_TIMELINE_LATEST", "Number of latest photos in timeline");
define($constpref."CFG_TIMELINE_RANDOM", "Number of random photos in timeline");
define($constpref."BNAME_TIMELINE" , "Timeline" ) ;

// map, tag
define($constpref."CFG_GMAP_PHOTOS", "Number of photos in map");
define($constpref."CFG_TAGS", "Number of tags in tagcloud");

//---------------------------------------------------------
// v1.70
//---------------------------------------------------------
define($constpref."CFG_ITEM_SUMMARY", "Max characters of photo description");
define($constpref."CFG_ITEM_SUMMARY_DSC", "Enter the maximum number of characters of photo description in the photo list.<br />-1 is unlimited");
define($constpref."CFG_CAT_SUMMARY", "Max characters of category description");
define($constpref."CFG_CAT_SUMMARY_DSC", "Enter the maximum number of characters of category description in the category list.<br />-1 is unlimited");
define($constpref."CFG_CAT_CHILD", "Show photos of subcategoies");
define($constpref."CFG_CAT_CHILD_DSC", "Enter to show or not photos of subcategoies in category list");
define($constpref."OPT_CAT_CHILD_NON", "Photo Video Media of this category only. Not show photos of subcategoies");
define($constpref."OPT_CAT_CHILD_EMPTY", "When no photo in this category, show photos of subcategoies");
define($constpref."OPT_CAT_CHILD_ALWAYS", "Show alwayas photos of subcategoies");

//---------------------------------------------------------
// v1.80
//---------------------------------------------------------
define($constpref."CFG_USE_LAME"  , "Use lame" ) ;
define($constpref."CFG_LAMEPATH"  , "Path to lame" ) ;
define($constpref."CFG_LAMEPATH_DSC" , "Alhough the full path to 'lame' should be written, leave it blank in most environments.<br />This configuration is significant only when using lame" ) ;

define($constpref."CFG_USE_TIMIDITY"  , "Use timidity" ) ;
define($constpref."CFG_TIMIDITYPATH"  , "Path to timidity" ) ;
define($constpref."CFG_TIMIDITYPATH_DSC" , "Alhough the full path to 'timidity' should be written, leave it blank in most environments.<br />This configuration is significant only when using timidity" ) ;

define($constpref."SMNAME_SEARCH","Search");

//---------------------------------------------------------
// v2.00
//---------------------------------------------------------
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

//---------------------------------------------------------
// v2.10
//---------------------------------------------------------
define($constpref."CFG_USE_LIGHTBOX","Use LightBox");

//---------------------------------------------------------
// v2.11
//---------------------------------------------------------
define($constpref."ADMENU_REDOTHUMBS","Rebuild Thumbnails");

//---------------------------------------------------------
// v2.20
//---------------------------------------------------------
define($constpref."CFG_EMBED_WIDTH", "Screen width of video site");
define($constpref."CFG_EMBED_HEIGHT","Screen height of video site");

//---------------------------------------------------------
// v2.40
//---------------------------------------------------------
define($constpref."CFG_PEAR_PATH", 'Path of PEAR libraly');
define($constpref."CFG_PEAR_PATH_DSC", 'Enter the absolute path in the PEAR library with Net_POP3.<br />When not enter, modules/webphoto/PEAR is used.');

//---------------------------------------------------------
// v2.60
//---------------------------------------------------------
define($constpref."OPT_TIMELINE_SCALE_HOUR",       "Hour") ;
define($constpref."OPT_TIMELINE_SCALE_DAY",        "Day") ;
define($constpref."OPT_TIMELINE_SCALE_CENTURY",    "Century") ;
define($constpref."OPT_TIMELINE_SCALE_MILLENNIUM", "Millennium") ;

}
// === define begin ===

?>