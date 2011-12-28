<?php
// $Id: main.php,v 1.48 2011/12/28 16:16:15 ohwada Exp $

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

// === define begin ===
if( !defined("_MB_WEBPHOTO_LANG_LOADED") ) 
{

define("_MB_WEBPHOTO_LANG_LOADED" , 1 ) ;

//=========================================================
// base on myalbum
//=========================================================

define("_WEBPHOTO_CATEGORY","Category");
define("_WEBPHOTO_SUBMITTER","Submitter");
define("_WEBPHOTO_NOMATCH_PHOTO","No photo video media matches your request");

define("_WEBPHOTO_ICON_NEW","New");
define("_WEBPHOTO_ICON_UPDATE","Updated");
define("_WEBPHOTO_ICON_POPULAR","Popular");
define("_WEBPHOTO_ICON_LASTUPDATE","Last Update");
define("_WEBPHOTO_ICON_HITS","Hits");
define("_WEBPHOTO_ICON_COMMENTS","Comments");

define("_WEBPHOTO_SORT_IDA","Record Number (Smaller ID to Bigger)");
define("_WEBPHOTO_SORT_IDD","Record Number (Bigger ID to Smaller)");
define("_WEBPHOTO_SORT_HITSA","Popularity (Least to Most Hits)");
define("_WEBPHOTO_SORT_HITSD","Popularity (Most to Least Hits)");
define("_WEBPHOTO_SORT_TITLEA","Title (A to Z)");
define("_WEBPHOTO_SORT_TITLED","Title (Z to A))");
define("_WEBPHOTO_SORT_DATEA","Updated Date (Old Photo Video Media Listed First)");
define("_WEBPHOTO_SORT_DATED","Updated Date (New Photo Video Media Listed First)");
define("_WEBPHOTO_SORT_RATINGA","Rating (Lowest Score to Highest Score)");
define("_WEBPHOTO_SORT_RATINGD","Rating (Highest Score to Lowest Score)");
define("_WEBPHOTO_SORT_RANDOM","Random");

define("_WEBPHOTO_SORT_SORTBY","Sort by:");
define("_WEBPHOTO_SORT_TITLE","Title");
define("_WEBPHOTO_SORT_DATE","Updated Date");
define("_WEBPHOTO_SORT_HITS","Popularity");
define("_WEBPHOTO_SORT_RATING","Rating");
define("_WEBPHOTO_SORT_S_CURSORTEDBY","Photo Video Media currently sorted by: %s");

define("_WEBPHOTO_NAVI_PREVIOUS","Previous");
define("_WEBPHOTO_NAVI_NEXT","Next");
define("_WEBPHOTO_S_NAVINFO" , "Photo Video Media No. %s - %s (out of %s medias hit)" ) ;
define("_WEBPHOTO_S_THEREARE","There are <b>%s</b> Photo Video Media in our Database.");
define("_WEBPHOTO_S_MOREPHOTOS","More Photo Video Media from %s");
define("_WEBPHOTO_ONEVOTE","1 vote");
define("_WEBPHOTO_S_NUMVOTES","%s votes");
define("_WEBPHOTO_ONEPOST","1 post");
define("_WEBPHOTO_S_NUMPOSTS","%s posts");
define("_WEBPHOTO_VOTETHIS","Vote this");
define("_WEBPHOTO_TELLAFRIEND","Tell a friend");
define("_WEBPHOTO_SUBJECT4TAF","A photo video media for you");


//---------------------------------------------------------
// submit
//---------------------------------------------------------
// only "Y/m/d" , "d M Y" , "M d Y" can be interpreted
define("_WEBPHOTO_DTFMT_YMDHI" , "d M Y H:i" ) ;

define("_WEBPHOTO_TITLE_ADDPHOTO","Add Photo Video Media");
define("_WEBPHOTO_TITLE_PHOTOUPLOAD","Photo Video Media Upload");
define("_WEBPHOTO_CAP_MAXPIXEL","Max pixel size");
define("_WEBPHOTO_CAP_MAXSIZE","Max file size (byte)");
define("_WEBPHOTO_CAP_VALIDPHOTO","Valid");
define("_WEBPHOTO_DSC_TITLE_BLANK","Leave title blank to use file names as title");

define("_WEBPHOTO_RADIO_ROTATETITLE" , "Image rotation" ) ;
define("_WEBPHOTO_RADIO_ROTATE0" , "no turn" ) ;
define("_WEBPHOTO_RADIO_ROTATE90" , "turn right" ) ;
define("_WEBPHOTO_RADIO_ROTATE180" , "turn 180 degree" ) ;
define("_WEBPHOTO_RADIO_ROTATE270" , "turn left" ) ;

define("_WEBPHOTO_SUBMIT_RECEIVED","We received your Photo Video Media. Thank you!");
define("_WEBPHOTO_SUBMIT_ALLPENDING","All photo video media are posted pending verification.");

define("_WEBPHOTO_ERR_MUSTREGFIRST","Sorry, you don't have permission to perform this action.<br />Please register or login first!");
define("_WEBPHOTO_ERR_MUSTADDCATFIRST","Sorry, there are no categories to add to yet.<br />Please create a category first!");
define("_WEBPHOTO_ERR_NOIMAGESPECIFIED","No photo video media was uploaded");
define("_WEBPHOTO_ERR_FILE","Photo Video Media are too big or there is a problem with the configuration");
define("_WEBPHOTO_ERR_FILEREAD","Photo Video Media are not readable.");
define("_WEBPHOTO_ERR_TITLE","You must enter 'Title' ");


//---------------------------------------------------------
// edit
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_EDIT","Edit Photo Video Media");
define("_WEBPHOTO_TITLE_PHOTODEL","Delete Photo Video Media");
define("_WEBPHOTO_CONFIRM_PHOTODEL","Delete photo video media ?");
define("_WEBPHOTO_DBUPDATED","Database Updated Successfully !");
define("_WEBPHOTO_DELETED","Deleted !");


//---------------------------------------------------------
// rate
//---------------------------------------------------------
define("_WEBPHOTO_RATE_VOTEONCE","Please do not vote for the same resource more than once.");
define("_WEBPHOTO_RATE_RATINGSCALE","The scale is 1 - 10, with 1 being poor and 10 being excellent.");
define("_WEBPHOTO_RATE_BEOBJECTIVE","Please be objective, if everyone receives a 1 or a 10, the ratings aren't very useful.");
define("_WEBPHOTO_RATE_DONOTVOTE","Do not vote for your own resource.");
define("_WEBPHOTO_RATE_IT","Rate It!");
define("_WEBPHOTO_RATE_VOTEAPPRE","Your vote is appreciated.");
define("_WEBPHOTO_RATE_S_THANKURATE","Thank you for taking the time to rate photo video media here at %s.");

define("_WEBPHOTO_ERR_NORATING","No rating selected.");
define("_WEBPHOTO_ERR_CANTVOTEOWN","You cannot vote on the resource you submitted.<br />All votes are logged and reviewed.");
define("_WEBPHOTO_ERR_VOTEONCE","Vote for the selected resource only once.<br />All votes are logged and reviewed.");

//---------------------------------------------------------
// move from myalbum_constants.php
//---------------------------------------------------------
// Caption
define("_WEBPHOTO_CAPTION_TOTAL" , "Total:" ) ;
define("_WEBPHOTO_CAPTION_GUESTNAME" , "Guest" ) ;
define("_WEBPHOTO_CAPTION_REFRESH" , "Refresh" ) ;
define("_WEBPHOTO_CAPTION_IMAGEXYT" , "Size(Type)" ) ;
define("_WEBPHOTO_CAPTION_CATEGORY" , "Category" ) ;


//=========================================================
// add for webphoto
//=========================================================

//---------------------------------------------------------
// database table items
//---------------------------------------------------------

// photo table
define("_WEBPHOTO_PHOTO_TABLE" , "Photo Table" ) ;
define("_WEBPHOTO_PHOTO_ID" , "Photo ID" ) ;
define("_WEBPHOTO_PHOTO_TIME_CREATE" , "Create Time" ) ;
define("_WEBPHOTO_PHOTO_TIME_UPDATE" , "Update Time" ) ;
define("_WEBPHOTO_PHOTO_CAT_ID" ,  "Category ID" ) ;
define("_WEBPHOTO_PHOTO_GICON_ID" , "Icon ID" ) ;
define("_WEBPHOTO_PHOTO_UID" ,   "User ID" ) ;
define("_WEBPHOTO_PHOTO_DATETIME" ,  "Photo Datetime" ) ;
define("_WEBPHOTO_PHOTO_TITLE" , "Photo Title" ) ;
define("_WEBPHOTO_PHOTO_PLACE" , "Place" ) ;
define("_WEBPHOTO_PHOTO_EQUIPMENT" , "Equipment" ) ;
define("_WEBPHOTO_PHOTO_FILE_URL" ,  "File URL" ) ;
define("_WEBPHOTO_PHOTO_FILE_PATH" , "File Path" ) ;
define("_WEBPHOTO_PHOTO_FILE_NAME" , "File Name" ) ;
define("_WEBPHOTO_PHOTO_FILE_EXT" ,  "File Extention" ) ;
define("_WEBPHOTO_PHOTO_FILE_MIME" ,  "File MIME type" ) ;
define("_WEBPHOTO_PHOTO_FILE_MEDIUM" ,  "File Medium Type" ) ;
define("_WEBPHOTO_PHOTO_FILE_SIZE" , "File Size" ) ;
define("_WEBPHOTO_PHOTO_CONT_URL" ,    "Photo URL" ) ;
define("_WEBPHOTO_PHOTO_CONT_PATH" ,   "Photo Path" ) ;
define("_WEBPHOTO_PHOTO_CONT_NAME" ,   "Photo Name" ) ;
define("_WEBPHOTO_PHOTO_CONT_EXT" ,    "Photo Extention" ) ;
define("_WEBPHOTO_PHOTO_CONT_MIME" ,   "Photo MIME type" ) ;
define("_WEBPHOTO_PHOTO_CONT_MEDIUM" , "Photo Medium Type" ) ;
define("_WEBPHOTO_PHOTO_CONT_SIZE" ,   "Photo File Size" ) ;
define("_WEBPHOTO_PHOTO_CONT_WIDTH" ,  "Photo Image Width" ) ;
define("_WEBPHOTO_PHOTO_CONT_HEIGHT" , "Photo Image Height" ) ;
define("_WEBPHOTO_PHOTO_CONT_DURATION" , "Video Duration Time" ) ;
define("_WEBPHOTO_PHOTO_CONT_EXIF" , "Exif Information" ) ;
define("_WEBPHOTO_PHOTO_MIDDLE_WIDTH" ,  "Middle Image Width" ) ;
define("_WEBPHOTO_PHOTO_MIDDLE_HEIGHT" , "Middle Image Height" ) ;
define("_WEBPHOTO_PHOTO_THUMB_URL" ,    "Thumb URL" ) ;
define("_WEBPHOTO_PHOTO_THUMB_PATH" ,   "Thumb Path" ) ;
define("_WEBPHOTO_PHOTO_THUMB_NAME" ,   "Thumb Name" ) ;
define("_WEBPHOTO_PHOTO_THUMB_EXT" ,    "Thumb Extention" ) ;
define("_WEBPHOTO_PHOTO_THUMB_MIME" ,   "Thumb MIME type" ) ;
define("_WEBPHOTO_PHOTO_THUMB_MEDIUM" , "Thumb Meduim Type" ) ;
define("_WEBPHOTO_PHOTO_THUMB_SIZE" ,   "Thumb File Size" ) ;
define("_WEBPHOTO_PHOTO_THUMB_WIDTH" ,  "Thumb Image Width" ) ;
define("_WEBPHOTO_PHOTO_THUMB_HEIGHT" , "Thumb Image Height" ) ;
define("_WEBPHOTO_PHOTO_GMAP_LATITUDE" ,  "GoogleMap Latitude" ) ;
define("_WEBPHOTO_PHOTO_GMAP_LONGITUDE" , "GoogleMap Longitude" ) ;
define("_WEBPHOTO_PHOTO_GMAP_ZOOM" ,      "GoogleMap Zoom" ) ;
define("_WEBPHOTO_PHOTO_GMAP_TYPE" ,      "GoogleMap Type" ) ;
define("_WEBPHOTO_PHOTO_PERM_READ" , "Read Permission" ) ;
define("_WEBPHOTO_PHOTO_STATUS" ,   "Status" ) ;
define("_WEBPHOTO_PHOTO_HITS" ,     "Hits" ) ;
define("_WEBPHOTO_PHOTO_RATING" ,   "Rating" ) ;
define("_WEBPHOTO_PHOTO_VOTES" ,    "Votes" ) ;
define("_WEBPHOTO_PHOTO_COMMENTS" , "Comment" ) ;
define("_WEBPHOTO_PHOTO_TEXT1" ,  "text1" ) ;
define("_WEBPHOTO_PHOTO_TEXT2" ,  "text2" ) ;
define("_WEBPHOTO_PHOTO_TEXT3" ,  "text3" ) ;
define("_WEBPHOTO_PHOTO_TEXT4" ,  "text4" ) ;
define("_WEBPHOTO_PHOTO_TEXT5" ,  "text5" ) ;
define("_WEBPHOTO_PHOTO_TEXT6" ,  "text6" ) ;
define("_WEBPHOTO_PHOTO_TEXT7" ,  "text7" ) ;
define("_WEBPHOTO_PHOTO_TEXT8" ,  "text8" ) ;
define("_WEBPHOTO_PHOTO_TEXT9" ,  "text9" ) ;
define("_WEBPHOTO_PHOTO_TEXT10" , "text10" ) ;
define("_WEBPHOTO_PHOTO_DESCRIPTION" ,  "Photo Description" ) ;
define("_WEBPHOTO_PHOTO_SEARCH" ,  "Search" ) ;

// category table
define("_WEBPHOTO_CAT_TABLE" , "Category Table" ) ;
define("_WEBPHOTO_CAT_ID" ,          "Category ID" ) ;
define("_WEBPHOTO_CAT_TIME_CREATE" , "Create Time" ) ;
define("_WEBPHOTO_CAT_TIME_UPDATE" , "Update Time" ) ;
define("_WEBPHOTO_CAT_GICON_ID" ,  "Icon ID" ) ;
define("_WEBPHOTO_CAT_FORUM_ID" ,  "Forum ID" ) ;
define("_WEBPHOTO_CAT_PID" ,    "Parent ID" ) ;
define("_WEBPHOTO_CAT_TITLE" ,  "Category Title" ) ;
define("_WEBPHOTO_CAT_IMG_PATH" , "Category Image Path" ) ;
define("_WEBPHOTO_CAT_IMG_MODE" , "Image View Mode" ) ;
define("_WEBPHOTO_CAT_ORIG_WIDTH" ,  "Image Original Width" ) ;
define("_WEBPHOTO_CAT_ORIG_HEIGHT" , "Image Original Height" ) ;
define("_WEBPHOTO_CAT_MAIN_WIDTH" ,  "Image Width in Main Category" ) ;
define("_WEBPHOTO_CAT_MAIN_HEIGHT" , "Image Height in Main Category" ) ;
define("_WEBPHOTO_CAT_SUB_WIDTH" ,   "Image Width in Sub Category" ) ;
define("_WEBPHOTO_CAT_SUB_HEIGHT" ,  "Image Height in Sub Category" ) ;
define("_WEBPHOTO_CAT_WEIGHT" , "Weight" ) ;
define("_WEBPHOTO_CAT_DEPTH" ,  "Depth" ) ;
define("_WEBPHOTO_CAT_ALLOWED_EXT" , "Allowed Extentions" ) ;
define("_WEBPHOTO_CAT_ITEM_TYPE" ,      "Item Type" ) ;
define("_WEBPHOTO_CAT_GMAP_MODE" ,      "GoogleMap View Mode" ) ;
define("_WEBPHOTO_CAT_GMAP_LATITUDE" ,  "GoogleMap Latitude" ) ;
define("_WEBPHOTO_CAT_GMAP_LONGITUDE" , "GoogleMap Longitude" ) ;
define("_WEBPHOTO_CAT_GMAP_ZOOM" ,      "GoogleMap Zoom" ) ;
define("_WEBPHOTO_CAT_GMAP_TYPE" ,      "GoogleMap Type" ) ;
define("_WEBPHOTO_CAT_PERM_READ" , "Read Permission" ) ;
define("_WEBPHOTO_CAT_PERM_POST" , "Post Permission" ) ;
define("_WEBPHOTO_CAT_TEXT1" ,  "text1" ) ;
define("_WEBPHOTO_CAT_TEXT2" ,  "text2" ) ;
define("_WEBPHOTO_CAT_TEXT3" ,  "text3" ) ;
define("_WEBPHOTO_CAT_TEXT4" ,  "text4" ) ;
define("_WEBPHOTO_CAT_TEXT5" ,  "text5" ) ;
define("_WEBPHOTO_CAT_DESCRIPTION" ,  "Category Description" ) ;

// vote table
define("_WEBPHOTO_VOTE_TABLE" , "Vote Table" ) ;
define("_WEBPHOTO_VOTE_ID" ,          "Vote ID" ) ;
define("_WEBPHOTO_VOTE_TIME_CREATE" , "Create Time" ) ;
define("_WEBPHOTO_VOTE_TIME_UPDATE" , "Update Time" ) ;
define("_WEBPHOTO_VOTE_PHOTO_ID" , "Photo Id" ) ;
define("_WEBPHOTO_VOTE_UID" ,      "User Id" ) ;
define("_WEBPHOTO_VOTE_RATING" ,   "Rating" ) ;
define("_WEBPHOTO_VOTE_HOSTNAME" , "IP Address" ) ;

// google icon table
define("_WEBPHOTO_GICON_TABLE" , "Google Icon Table" ) ;
define("_WEBPHOTO_GICON_ID" ,          "Icon ID" ) ;
define("_WEBPHOTO_GICON_TIME_CREATE" , "Create Time" ) ;
define("_WEBPHOTO_GICON_TIME_UPDATE" , "Update Time" ) ;
define("_WEBPHOTO_GICON_TITLE" ,     "Icon Title" ) ;
define("_WEBPHOTO_GICON_IMAGE_PATH" ,  "Image Path" ) ;
define("_WEBPHOTO_GICON_IMAGE_NAME" ,  "Image Name" ) ;
define("_WEBPHOTO_GICON_IMAGE_EXT" ,   "Image Extntion" ) ;
define("_WEBPHOTO_GICON_SHADOW_PATH" , "Shadow Path" ) ;
define("_WEBPHOTO_GICON_SHADOW_NAME" , "Shadow Name" ) ;
define("_WEBPHOTO_GICON_SHADOW_EXT" ,  "Shadow Extention" ) ;
define("_WEBPHOTO_GICON_IMAGE_WIDTH" ,  "Image Width" ) ;
define("_WEBPHOTO_GICON_IMAGE_HEIGHT" , "Image Height" ) ;
define("_WEBPHOTO_GICON_SHADOW_WIDTH" ,  "Shadow Width" ) ;
define("_WEBPHOTO_GICON_SHADOW_HEIGHT" , "Shadow Height" ) ;
define("_WEBPHOTO_GICON_ANCHOR_X" , "Anchor X Size" ) ;
define("_WEBPHOTO_GICON_ANCHOR_Y" , "Anchor Y Size" ) ;
define("_WEBPHOTO_GICON_INFO_X" , "WindowInfo X Size" ) ;
define("_WEBPHOTO_GICON_INFO_Y" , "WindowInfo Y Size" ) ;

// mime type table
define("_WEBPHOTO_MIME_TABLE" , "MIME Type Table" ) ;
define("_WEBPHOTO_MIME_ID" ,          "MIME ID" ) ;
define("_WEBPHOTO_MIME_TIME_CREATE" , "Create Time" ) ;
define("_WEBPHOTO_MIME_TIME_UPDATE" , "Update Time" ) ;
define("_WEBPHOTO_MIME_EXT" ,   "Extention" ) ;
define("_WEBPHOTO_MIME_MEDIUM" ,  "Meduim Type" ) ;
define("_WEBPHOTO_MIME_TYPE" ,  "MIME Type" ) ;
define("_WEBPHOTO_MIME_NAME" ,  "MIME Name" ) ;
define("_WEBPHOTO_MIME_PERMS" , "Permission" ) ;

// added in v0.20
define("_WEBPHOTO_MIME_FFMPEG" , "ffmpeg option" ) ;

// added in v1.80
define("_WEBPHOTO_MIME_KIND" , "File Kind" ) ;
define("_WEBPHOTO_MIME_OPTION" , "Command Option" ) ;

// tag table
define("_WEBPHOTO_TAG_TABLE" , "Tag Table" ) ;
define("_WEBPHOTO_TAG_ID" ,          "Tag ID" ) ;
define("_WEBPHOTO_TAG_TIME_CREATE" , "Create Time" ) ;
define("_WEBPHOTO_TAG_TIME_UPDATE" , "Update Time" ) ;
define("_WEBPHOTO_TAG_NAME" ,   "Tag Name" ) ;

// photo-to-tag table
define("_WEBPHOTO_P2T_TABLE" , "Photo Video Media and Tag Relation Table" ) ;
define("_WEBPHOTO_P2T_ID" ,          "Photo-Tag ID" ) ;
define("_WEBPHOTO_P2T_TIME_CREATE" , "Create Time" ) ;
define("_WEBPHOTO_P2T_TIME_UPDATE" , "Update Time" ) ;
define("_WEBPHOTO_P2T_PHOTO_ID" , "Photo ID" ) ;
define("_WEBPHOTO_P2T_TAG_ID" ,   "Tag ID" ) ;
define("_WEBPHOTO_P2T_UID" ,      "User ID" ) ;

// synonym table
define("_WEBPHOTO_SYNO_TABLE" , "Synonym Table" ) ;
define("_WEBPHOTO_SYNO_ID" ,          "Synonym ID" ) ;
define("_WEBPHOTO_SYNO_TIME_CREATE" , "Create Time" ) ;
define("_WEBPHOTO_SYNO_TIME_UPDATE" , "Update Time" ) ;
define("_WEBPHOTO_SYNO_WEIGHT" , "Weight" ) ;
define("_WEBPHOTO_SYNO_KEY" , "Key" ) ;
define("_WEBPHOTO_SYNO_VALUE" , "Synonym" ) ;


//---------------------------------------------------------
// title
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_LATEST","Latest");
define("_WEBPHOTO_TITLE_SUBMIT","Submit");
define("_WEBPHOTO_TITLE_POPULAR","Popular");
define("_WEBPHOTO_TITLE_HIGHRATE","TopRated");
define("_WEBPHOTO_TITLE_MYPHOTO","My Photo Video Media");
define("_WEBPHOTO_TITLE_RANDOM","Random Photo Video Media");
define("_WEBPHOTO_TITLE_HELP","Help");
define("_WEBPHOTO_TITLE_CATEGORY_LIST", "Category List");
define("_WEBPHOTO_TITLE_TAG_LIST",  "Tag List");
define("_WEBPHOTO_TITLE_TAGS",  "Tag");
define("_WEBPHOTO_TITLE_USER_LIST", "Submitter List");
define("_WEBPHOTO_TITLE_DATE_LIST", "Photo Video Media Date list");
define("_WEBPHOTO_TITLE_PLACE_LIST","Photo Video Media Place List");
define("_WEBPHOTO_TITLE_RSS","RSS");

define("_WEBPHOTO_VIEWTYPE_LIST", "List type");
define("_WEBPHOTO_VIEWTYPE_TABLE", "Table Type");

define("_WEBPHOTO_CATLIST_ON",   "Show Category");
define("_WEBPHOTO_CATLIST_OFF",  "Hide Category");
define("_WEBPHOTO_TAGCLOUD_ON",  "Show Tag Cloud");
define("_WEBPHOTO_TAGCLOUD_OFF", "Hide Tag Cloud");
define("_WEBPHOTO_GMAP_ON",  "Show GoogleMap");
define("_WEBPHOTO_GMAP_OFF", "Hide GoogleMap");

define("_WEBPHOTO_NO_TAG","Not Set Tag");

//---------------------------------------------------------
// google maps
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_GET_LOCATION", "Setting of Latitude and Longitude");
define("_WEBPHOTO_GMAP_DESC", "Show thumb image, when click the marker in GoogleMaps");
define("_WEBPHOTO_GMAP_ICON", "GoogleMap Icons");
define("_WEBPHOTO_GMAP_LATITUDE", "GoogleMap Latitude");
define("_WEBPHOTO_GMAP_LONGITUDE","GoogleMap Longitude");
define("_WEBPHOTO_GMAP_ZOOM","GoogleMap Zoom");
define("_WEBPHOTO_GMAP_ADDRESS",  "Address");
define("_WEBPHOTO_GMAP_GET_LOCATION", "Get latitude and longitude");
define("_WEBPHOTO_GMAP_SEARCH_LIST",  "Search list");
define("_WEBPHOTO_GMAP_CURRENT_LOCATION",  "Current Location");
define("_WEBPHOTO_GMAP_CURRENT_ADDRESS",  "Current Address");
define("_WEBPHOTO_GMAP_NO_MATCH_PLACE",  "There are no matched place");
define("_WEBPHOTO_GMAP_NOT_COMPATIBLE", "Do not show GoogleMaps in your web browser");
define("_WEBPHOTO_JS_INVALID", "Do not use JavaScript in your web browser");
define("_WEBPHOTO_IFRAME_NOT_SUPPORT","Do not use iframe in your web browser");

//---------------------------------------------------------
// search
//---------------------------------------------------------
define("_WEBPHOTO_SR_SEARCH","Search");

//---------------------------------------------------------
// popbox
//---------------------------------------------------------
define("_WEBPHOTO_POPBOX_REVERT", "Click the image to shrink it.");

//---------------------------------------------------------
// tag
//---------------------------------------------------------
define("_WEBPHOTO_TAGS","Tags");
define("_WEBPHOTO_EDIT_TAG","Edit Tags");
define("_WEBPHOTO_DSC_TAG_DIVID", "divide by commas (,) when you want to use two or more tags");
define("_WEBPHOTO_DSC_TAG_EDITABLE", "You can edit only the tags which you posted");

//---------------------------------------------------------
// submit form
//---------------------------------------------------------
define("_WEBPHOTO_CAP_ALLOWED_EXTS", "Allowed Extentions");
define("_WEBPHOTO_CAP_PHOTO_SELECT","Select the photo video media");
define("_WEBPHOTO_CAP_THUMB_SELECT", "Select the thumb image");
define("_WEBPHOTO_DSC_THUMB_SELECT", "create the thumb image from the photo video media, when not select");
define("_WEBPHOTO_DSC_SET_DATETIME",  "Set photo video media datetime");

define("_WEBPHOTO_DSC_PIXCEL_RESIZE", "Resize automatically if bigger than this size");
define("_WEBPHOTO_DSC_PIXCEL_REJECT", "Cannot upload if bigger than this size");
define("_WEBPHOTO_BUTTON_CLEAR", "Clear");
define("_WEBPHOTO_SUBMIT_RESIZED", "Resized, because image is too big ");

// PHP upload error
// http://www.php.net/manual/en/features.file-upload.errors.php
define("_WEBPHOTO_PHP_UPLOAD_ERR_OK", "There is no error, the file uploaded with success.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_INI_SIZE", "The uploaded file exceeds the upload_max_filesize.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_FORM_SIZE", "The uploaded file exceeds %s .");
define("_WEBPHOTO_PHP_UPLOAD_ERR_PARTIAL", "The uploaded file was only partially uploaded.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_NO_FILE", "No file was uploaded.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_NO_TMP_DIR", "Missing a temporary folder.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_CANT_WRITE", "Failed to write file to disk.");
define("_WEBPHOTO_PHP_UPLOAD_ERR_EXTENSION", "File upload stopped by extension.");

// upload error
define("_WEBPHOTO_UPLOADER_ERR_NOT_FOUND", "Uploaded File not found");
define("_WEBPHOTO_UPLOADER_ERR_INVALID_FILE_SIZE", "Invalid File Size");
define("_WEBPHOTO_UPLOADER_ERR_EMPTY_FILE_NAME", "Filename Is Empty");
define("_WEBPHOTO_UPLOADER_ERR_NO_FILE", "No file uploaded");
define("_WEBPHOTO_UPLOADER_ERR_NOT_SET_DIR", "Upload directory not set");
define("_WEBPHOTO_UPLOADER_ERR_NOT_ALLOWED_EXT", "Extension not allowed");
define("_WEBPHOTO_UPLOADER_ERR_PHP_OCCURED", "Error occurred: Error #");
define("_WEBPHOTO_UPLOADER_ERR_NOT_OPEN_DIR", "Failed opening directory: ");
define("_WEBPHOTO_UPLOADER_ERR_NO_PERM_DIR", "Failed opening directory with write permission: ");
define("_WEBPHOTO_UPLOADER_ERR_NOT_ALLOWED_MIME", "MIME type not allowed: ");
define("_WEBPHOTO_UPLOADER_ERR_LARGE_FILE_SIZE", "File size too large: ");
define("_WEBPHOTO_UPLOADER_ERR_LARGE_WIDTH", "File width must be smaller than ");
define("_WEBPHOTO_UPLOADER_ERR_LARGE_HEIGHT", "File height must be smaller than ");
define("_WEBPHOTO_UPLOADER_ERR_UPLOAD", "Failed uploading file: ");

//---------------------------------------------------------
// help
//---------------------------------------------------------
define("_WEBPHOTO_HELP_DSC", "This is description of the application which works on your PC");

define("_WEBPHOTO_HELP_PICLENS_TITLE", "PicLens");
define("_WEBPHOTO_HELP_PICLENS_DSC", '
Piclens is the addon which Cooliris Inc provides for FireFox<br />
This is the viewer of images in the web site<br /><br />
<b>Setting</b><br />
(1) Download FireFox<br />
<a href="http://www.mozilla-japan.org/products/firefox/" target="_blank">
http://www.mozilla-japan.org/products/firefox/
</a><br /><br />
(2) Download Piclens addon<br />
<a href="http://www.piclens.com/" target="_blank">
http://www.piclens.com/
</a><br /><br />
(3) View webphoto in webphoto<br />
http://THIS-SITE/modules/webphoto/ <br /><br />
(4) Click the blue icon on the upper right of Firefox<br />
You cannot use Piclens, when the icon is black<br />' );

//
// dummy lines , adjusts line number for Japanese lang file.
//

define("_WEBPHOTO_HELP_MEDIARSSSLIDESHOW_TITLE", "Media RSS Slide Show");
define("_WEBPHOTO_HELP_MEDIARSSSLIDESHOW_DSC", '
"Media RSS  Slide Show" is the google desktop gadget<br />
This shows images from the internet with the slide show<br /><br />
<b>Setting</b><br />
(1) Download "Google Desktop"<br />
<a href="http://desktop.google.co.jp/" target="_blank">
http://desktop.google.co.jp/
</a><br /><br />
(2) Download "Media RSS  Slide Show" gadget<br />
<a href="http://desktop.google.com/plugins/i/mediarssslideshow.html" target="_blank">
http://desktop.google.com/plugins/i/mediarssslideshow.html
</a><br /><br />
(3) Change "URL of MediaRSS" into the following, using the option of the gadget<br />' );

//---------------------------------------------------------
// others
//---------------------------------------------------------
define("_WEBPHOTO_RANDOM_MORE","More Photo Video Media at random");
define("_WEBPHOTO_USAGE_PHOTO","Popup the big photo video media, when click the thumbnail image");
define("_WEBPHOTO_USAGE_TITLE","Move to the photo video media page, when click the photo video media title");
define("_WEBPHOTO_DATE_NOT_SET","Not set Photo Video Media Date");
define("_WEBPHOTO_PLACE_NOT_SET","Not Set Photo Video Media Place");
define("_WEBPHOTO_GOTO_ADMIN", "Goto Admin Control");

//---------------------------------------------------------
// search for Japanese
//---------------------------------------------------------
define("_WEBPHOTO_SR_CANDICATE","Candicate for search");
define("_WEBPHOTO_SR_ZENKAKU","Zenkaku");
define("_WEBPHOTO_SR_HANKAKU","Hanhaku");

define("_WEBPHOTO_JA_KUTEN",   "");
define("_WEBPHOTO_JA_DOKUTEN", "");
define("_WEBPHOTO_JA_PERIOD",  "");
define("_WEBPHOTO_JA_COMMA",   "");

//---------------------------------------------------------
// v0.20
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_VIDEO_THUMB_SEL", "Select thumbnail of video");
define("_WEBPHOTO_TITLE_VIDEO_REDO","Re-create Flash and Thumbnail from uploaded video");
define("_WEBPHOTO_CAP_REDO_THUMB","Create Thumbnail");
define("_WEBPHOTO_CAP_REDO_FLASH","Create Flash Video");
define("_WEBPHOTO_ERR_VIDEO_FLASH", "Cannot create Flash video");
define("_WEBPHOTO_ERR_VIDEO_THUMB", "Substituted with the icon, because cannot create Thumbnail for video");
define("_WEBPHOTO_BUTTON_SELECT", "Select");

define("_WEBPHOTO_DSC_DOWNLOAD_PLAY","Play after download");
define("_WEBPHOTO_ICON_VIDEO", "Video");
define("_WEBPHOTO_HOUR", "hour");
define("_WEBPHOTO_MINUTE", "min");
define("_WEBPHOTO_SECOND", "sec");

//---------------------------------------------------------
// v0.30
//---------------------------------------------------------
// user table
define("_WEBPHOTO_USER_TABLE" , "User Aux Table" ) ;
define("_WEBPHOTO_USER_ID" ,          "User Aux ID" ) ;
define("_WEBPHOTO_USER_TIME_CREATE" , "Create Time" ) ;
define("_WEBPHOTO_USER_TIME_UPDATE" , "Update Time" ) ;
define("_WEBPHOTO_USER_UID" , "User ID" ) ;
define("_WEBPHOTO_USER_CAT_ID" , "Category ID" ) ;
define("_WEBPHOTO_USER_EMAIL" , "Email Address" ) ;
define("_WEBPHOTO_USER_TEXT1" ,  "text1" ) ;
define("_WEBPHOTO_USER_TEXT2" ,  "text2" ) ;
define("_WEBPHOTO_USER_TEXT3" ,  "text3" ) ;
define("_WEBPHOTO_USER_TEXT4" ,  "text4" ) ;
define("_WEBPHOTO_USER_TEXT5" ,  "text5" ) ;

// maillog
define("_WEBPHOTO_MAILLOG_TABLE" , "Maillog Table" ) ;
define("_WEBPHOTO_MAILLOG_ID" ,          "Maillog ID" ) ;
define("_WEBPHOTO_MAILLOG_TIME_CREATE" , "Create Time" ) ;
define("_WEBPHOTO_MAILLOG_TIME_UPDATE" , "Update Time" ) ;
define("_WEBPHOTO_MAILLOG_PHOTO_IDS" , "Photo IDs" ) ;
define("_WEBPHOTO_MAILLOG_STATUS" , "Status" ) ;
define("_WEBPHOTO_MAILLOG_FROM" , "Mail From Address" ) ;
define("_WEBPHOTO_MAILLOG_SUBJECT" , "Subject" ) ;
define("_WEBPHOTO_MAILLOG_BODY" ,  "Body" ) ;
define("_WEBPHOTO_MAILLOG_FILE" ,  "File Name" ) ;
define("_WEBPHOTO_MAILLOG_ATTACH" ,  "Attach Files" ) ;
define("_WEBPHOTO_MAILLOG_COMMENT" ,  "Comment" ) ;

// mail register
define("_WEBPHOTO_TITLE_MAIL_REGISTER" ,  "Mail Address Register" ) ;
define("_WEBPHOTO_MAIL_HELP" ,  "Please refer 'Help' for usage" ) ;
define("_WEBPHOTO_CAT_USER" ,  "User Name" ) ;
define("_WEBPHOTO_BUTTON_REGISTER" ,  "REGISTER" ) ;
define("_WEBPHOTO_NOMATCH_USER","There are no user");
define("_WEBPHOTO_ERR_MAIL_EMPTY","You must enter 'Mail Address' ");
define("_WEBPHOTO_ERR_MAIL_ILLEGAL","Illegal format of mail address");

// mail retrieve
define("_WEBPHOTO_TITLE_MAIL_RETRIEVE" ,  "Mail Retrieve" ) ;
define("_WEBPHOTO_DSC_MAIL_RETRIEVE" ,  "Retrieve mails from the mail server" ) ;
define("_WEBPHOTO_BUTTON_RETRIEVE" ,  "RETRIEVE" ) ;
define("_WEBPHOTO_SUBTITLE_MAIL_ACCESS" ,  "Accessing the mail server" ) ;
define("_WEBPHOTO_SUBTITLE_MAIL_PARSE" ,  "Parsing the received mails" ) ;
define("_WEBPHOTO_SUBTITLE_MAIL_PHOTO" ,  "Submiting the photo video media attached to mails" ) ;
define("_WEBPHOTO_TEXT_MAIL_ACCESS_TIME" ,  "In access limitation" ) ;
define("_WEBPHOTO_TEXT_MAIL_RETRY"  ,  "Access 1 minute later" ) ;
define("_WEBPHOTO_TEXT_MAIL_NOT_RETRIEVE" ,  "Cannot retrieve mail.<br />Probably temporary communication failure.<br />Please retry after a while" ) ;
define("_WEBPHOTO_TEXT_MAIL_NO_NEW" ,  "There no new mail" ) ;
define("_WEBPHOTO_TEXT_MAIL_RETRIEVED_FMT" ,  "Retrieved %s mails" ) ;
define("_WEBPHOTO_TEXT_MAIL_NO_VALID" ,  "There no valid mail" ) ;
define("_WEBPHOTO_TEXT_MAIL_SUBMITED_FMT" ,  "Submited %s photo video media" ) ;
define("_WEBPHOTO_GOTO_INDEX" ,  "Goto the module top page" ) ;

// i.php
define("_WEBPHOTO_TITLE_MAIL_POST" ,  "Post by Mail" ) ;

// file
define("_WEBPHOTO_TITLE_SUBMIT_FILE" , "Add Photo Video Media from File" ) ;
define("_WEBPHOTO_CAP_FILE_SELECT", "Select File");
define("_WEBPHOTO_ERR_EMPTY_FILE" , "You must select the file" ) ;
define("_WEBPHOTO_ERR_EMPTY_CAT" , "You must select the category" ) ;
define("_WEBPHOTO_ERR_INVALID_CAT" , "Invalid category" ) ;
define("_WEBPHOTO_ERR_CREATE_PHOTO" , "Cannot create photo video media" ) ;
define("_WEBPHOTO_ERR_CREATE_THUMB" , "Cannot create thumb image" ) ;

// help
define("_WEBPHOTO_HELP_MUST_LOGIN","Please login, if you want to read more detail");
define("_WEBPHOTO_HELP_NOT_PERM", "You have no permission. Please contact the webmaster");

define("_WEBPHOTO_HELP_MOBILE_TITLE", "Mobile Phone");
define("_WEBPHOTO_HELP_MOBILE_DSC", "You can look the photo and video in the mobile phone<br/>the size of screen is about 240x320 ");
define("_WEBPHOTO_HELP_MOBILE_TEXT_FMT", '
<b>Access URL</b><br />
<a href="{MODULE_URL}/i.php" target="_blank">{MODULE_URL}/i.php</a>');

define("_WEBPHOTO_HELP_MAIL_TITLE", "Mobile Mail");
define("_WEBPHOTO_HELP_MAIL_DSC", "You can post the photo and video by email from the mobile phone");
define("_WEBPHOTO_HELP_MAIL_GUEST", "This is sample. You can look the REAL mail address, if you have the permission.");

define("_WEBPHOTO_HELP_FILE_TITLE", "Post by FTP");
define("_WEBPHOTO_HELP_FILE_DSC", "You can post the big size photo and video, when you upload the file by FTP");
define("_WEBPHOTO_HELP_FILE_TEXT_FMT", '
<b>Post photo video media</b><br />
(1) Upload the file in FTP server<br />
(2) Click <a href="{MODULE_URL}/index.php?fct=submit_file" target="_blank">Add Photo Video Media from File</a><br />
(3) Select the uploaded file and post' );

// mail check
// for Japanese
define("_WEBPHOTO_MAIL_DENY_TITLE_PREG", "" ) ;
define("_WEBPHOTO_MAIL_AD_WORD_1", "" ) ;
define("_WEBPHOTO_MAIL_AD_WORD_2", "" ) ;

//---------------------------------------------------------
// v0.40
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_TABLE" , "Item Table" ) ;
define("_WEBPHOTO_ITEM_ID" , "Item ID" ) ;
define("_WEBPHOTO_ITEM_TIME_CREATE" , "Create Time" ) ;
define("_WEBPHOTO_ITEM_TIME_UPDATE" , "Update Time" ) ;
define("_WEBPHOTO_ITEM_CAT_ID" ,  "Category ID" ) ;
define("_WEBPHOTO_ITEM_GICON_ID" , "GoogleMap Icon ID" ) ;
define("_WEBPHOTO_ITEM_UID" ,   "User ID" ) ;
define("_WEBPHOTO_ITEM_KIND" , "File Kind" ) ;
define("_WEBPHOTO_ITEM_EXT" ,  "File Extention" ) ;
define("_WEBPHOTO_ITEM_DATETIME" ,  "Photo Video Media Datetime" ) ;
define("_WEBPHOTO_ITEM_TITLE" , "Photo Video Media Title" ) ;
define("_WEBPHOTO_ITEM_PLACE" , "Place" ) ;
define("_WEBPHOTO_ITEM_EQUIPMENT" , "Equipment" ) ;
define("_WEBPHOTO_ITEM_GMAP_LATITUDE" ,  "GoogleMap Latitude" ) ;
define("_WEBPHOTO_ITEM_GMAP_LONGITUDE" , "GoogleMap Longitude" ) ;
define("_WEBPHOTO_ITEM_GMAP_ZOOM" ,      "GoogleMap Zoom" ) ;
define("_WEBPHOTO_ITEM_GMAP_TYPE" ,      "GoogleMap Type" ) ;
define("_WEBPHOTO_ITEM_PERM_READ" , "Read Permission" ) ;
define("_WEBPHOTO_ITEM_STATUS" ,   "Status" ) ;
define("_WEBPHOTO_ITEM_HITS" ,     "Hits" ) ;
define("_WEBPHOTO_ITEM_RATING" ,   "Rating" ) ;
define("_WEBPHOTO_ITEM_VOTES" ,    "Votes" ) ;
define("_WEBPHOTO_ITEM_DESCRIPTION" ,  "Photo Video Media Description" ) ;
define("_WEBPHOTO_ITEM_EXIF" , "Exif Information" ) ;
define("_WEBPHOTO_ITEM_SEARCH" ,  "Search" ) ;
define("_WEBPHOTO_ITEM_COMMENTS" , "Comments" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_1" ,  "File ID: Content" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_2" ,  "File ID: Thumbnail" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_3" ,  "File ID: MIddle" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_4" ,  "File ID: Flash Video" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_5" ,  "File ID: Docomo Video" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_6" ,  "File ID: PDF" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_7" ,  "File ID: Flash swf" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_8" ,  "File ID: Small" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_9" ,  "File ID: JPEG" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_10" , "File ID: MP3" ) ;
define("_WEBPHOTO_ITEM_TEXT_1" ,  "text1" ) ;
define("_WEBPHOTO_ITEM_TEXT_2" ,  "text2" ) ;
define("_WEBPHOTO_ITEM_TEXT_3" ,  "text3" ) ;
define("_WEBPHOTO_ITEM_TEXT_4" ,  "text4" ) ;
define("_WEBPHOTO_ITEM_TEXT_5" ,  "text5" ) ;
define("_WEBPHOTO_ITEM_TEXT_6" ,  "text6" ) ;
define("_WEBPHOTO_ITEM_TEXT_7" ,  "text7" ) ;
define("_WEBPHOTO_ITEM_TEXT_8" ,  "text8" ) ;
define("_WEBPHOTO_ITEM_TEXT_9" ,  "text9" ) ;
define("_WEBPHOTO_ITEM_TEXT_10" , "text10" ) ;

// file table
define("_WEBPHOTO_FILE_TABLE" , "File Table" ) ;
define("_WEBPHOTO_FILE_ID" , "File ID" ) ;
define("_WEBPHOTO_FILE_TIME_CREATE" , "Create Time" ) ;
define("_WEBPHOTO_FILE_TIME_UPDATE" , "Update Time" ) ;
define("_WEBPHOTO_FILE_ITEM_ID" ,  "Item ID" ) ;
define("_WEBPHOTO_FILE_KIND" , "File Kind" ) ;
define("_WEBPHOTO_FILE_URL" ,    "URL" ) ;
define("_WEBPHOTO_FILE_PATH" ,   "File Path" ) ;
define("_WEBPHOTO_FILE_NAME" ,   "File Name" ) ;
define("_WEBPHOTO_FILE_EXT" ,    "File Extention" ) ;
define("_WEBPHOTO_FILE_MIME" ,   "MIME type" ) ;
define("_WEBPHOTO_FILE_MEDIUM" , "Medium Type" ) ;
define("_WEBPHOTO_FILE_SIZE" ,   "File Size" ) ;
define("_WEBPHOTO_FILE_WIDTH" ,  "Image Width" ) ;
define("_WEBPHOTO_FILE_HEIGHT" , "Image Height" ) ;
define("_WEBPHOTO_FILE_DURATION" , "Video Duration Time" ) ;

// file kind ( for admin checktables )
define("_WEBPHOTO_FILE_KIND_1" ,  "Content" ) ;
define("_WEBPHOTO_FILE_KIND_2" ,  "Thumbnail" ) ;
define("_WEBPHOTO_FILE_KIND_3" ,  "MIddle" ) ;
define("_WEBPHOTO_FILE_KIND_4" ,  "Flash Video" ) ;
define("_WEBPHOTO_FILE_KIND_5" ,  "Docomo Video" ) ;
define("_WEBPHOTO_FILE_KIND_6" ,  "PDF" ) ;
define("_WEBPHOTO_FILE_KIND_7" ,  "Flash swf" ) ;
define("_WEBPHOTO_FILE_KIND_8" ,  "Small" ) ;
define("_WEBPHOTO_FILE_KIND_9" ,  "JPEG" ) ;
define("_WEBPHOTO_FILE_KIND_10" , "MP3" ) ;

// index
define("_WEBPHOTO_MOBILE_MAILTO" , "Send URL to the mobile phone" ) ;

// i.php
define("_WEBPHOTO_TITLE_MAIL_JUDGE" ,  "Judge the mobile carrier" ) ;
define("_WEBPHOTO_MAIL_MODEL", "Mobile Carrier" ) ;
define("_WEBPHOTO_MAIL_BROWSER", "WEB Browser" ) ;
define("_WEBPHOTO_MAIL_NOT_JUDGE", "Cannot judge the mobile carrier" ) ;
define("_WEBPHOTO_MAIL_TO_WEBMASTER", "Mail to webmaster" ) ;

// help
define("_WEBPHOTO_HELP_MAIL_POST_FMT", '
<b>Prepare</b><br />
Register your mail address of mobile phone<br />
<a href="{MODULE_URL}/index.php?fct=mail_register" target="_blank">Register Mail Addrtess</a><br /><br />
<b>Post image</b><br />
Send mail to the fllowing address with attaching photo file.<br />
<a href="mailto:{MAIL_ADDR}">{MAIL_ADDR}</a> {MAIL_GUEST} <br /><br />
<b>Rotation for photo</b><br />
You can turn the photo right or left, since you enter the end of "Subject" as following<br />
 R@ : turn right <br />
 L@ : turn left <br /><br />' );
define("_WEBPHOTO_HELP_MAIL_SUBTITLE_RETRIEVE", "<b>Retrive mail and submit photo</b><br />" );
define("_WEBPHOTO_HELP_MAIL_RETRIEVE_FMT", '
Click <a href="{MODULE_URL}/i.php?op=post" target="_blank">Post by Mail</a>, after few seconds sent mail.<br />
Webphoto retrive the mail which you sent, submit and show the attached photo<br />' );
define("_WEBPHOTO_HELP_MAIL_RETRIEVE_TEXT", "Webphoto retrive the mail which you sent, submit and show the attached photo<br />" );
define("_WEBPHOTO_HELP_MAIL_RETRIEVE_AUTO_FMT", '
The mail is submitted automatically %s seconds later, when you send mail.<br />
Please click <a href="{MODULE_URL}/i.php?op=post" target="_blank">Post by Mail</a>, if not submitted.<br />' );

//---------------------------------------------------------
// v0.50
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_TIME_PUBLISH" , "Published Time" ) ;
define("_WEBPHOTO_ITEM_TIME_EXPIRE"  , "Expired Time" ) ;
define("_WEBPHOTO_ITEM_PLAYER_ID" ,    "Player ID" ) ;
define("_WEBPHOTO_ITEM_FLASHVAR_ID" ,  "FlashVar ID" ) ;
define("_WEBPHOTO_ITEM_DURATION" , "Video Duration Time" ) ;
define("_WEBPHOTO_ITEM_DISPLAYTYPE", "Display Type");
define("_WEBPHOTO_ITEM_ONCLICK","Action when click thumbnail");
define("_WEBPHOTO_ITEM_SITEURL", "WebSite URL");
define("_WEBPHOTO_ITEM_ARTIST", "Artist");
define("_WEBPHOTO_ITEM_ALBUM", "Album");
define("_WEBPHOTO_ITEM_LABEL", "Label");
define("_WEBPHOTO_ITEM_VIEWS", "Views");
define("_WEBPHOTO_ITEM_PERM_DOWN" , "Download Permission" ) ;
define("_WEBPHOTO_ITEM_EMBED_TYPE" ,  "Plugin Type" ) ;
define("_WEBPHOTO_ITEM_EMBED_SRC" ,   "Plugin URL Param" ) ;
define("_WEBPHOTO_ITEM_EXTERNAL_URL" , "External URL" ) ;
define("_WEBPHOTO_ITEM_EXTERNAL_THUMB" , "External Thumbnail URL" ) ;
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE",  "Playlist Type" ) ;
define("_WEBPHOTO_ITEM_PLAYLIST_FEED",  "Playlist Feed URL" ) ;
define("_WEBPHOTO_ITEM_PLAYLIST_DIR",   "Playlist Directory" ) ;
define("_WEBPHOTO_ITEM_PLAYLIST_CACHE", "Playlist Cache Name" ) ;
define("_WEBPHOTO_ITEM_PLAYLIST_TIME",  "Playlist Cache Time" ) ;
define("_WEBPHOTO_ITEM_CHAIN", "Chain");
define("_WEBPHOTO_ITEM_SHOWINFO", "Show Infomation");

// player table
define("_WEBPHOTO_PLAYER_TABLE","Player Table");
define("_WEBPHOTO_PLAYER_ID","Player ID");
define("_WEBPHOTO_PLAYER_TIME_CREATE" , "Create Time" ) ;
define("_WEBPHOTO_PLAYER_TIME_UPDATE" , "Update Time" ) ;
define("_WEBPHOTO_PLAYER_TITLE","Player Title ");
define("_WEBPHOTO_PLAYER_STYLE","Style Option");
define("_WEBPHOTO_PLAYER_WIDTH","Player Width");
define("_WEBPHOTO_PLAYER_HEIGHT","Player Height");
define("_WEBPHOTO_PLAYER_DISPLAYWIDTH","Display Width");
define("_WEBPHOTO_PLAYER_DISPLAYHEIGHT","Display Height");
define("_WEBPHOTO_PLAYER_SCREENCOLOR","Screen Color");
define("_WEBPHOTO_PLAYER_BACKCOLOR","Back Color");
define("_WEBPHOTO_PLAYER_FRONTCOLOR","Front Color");
define("_WEBPHOTO_PLAYER_LIGHTCOLOR","Light Color");

// FlashVar table
define("_WEBPHOTO_FLASHVAR_TABLE","FlashVar Table");
define("_WEBPHOTO_FLASHVAR_ID","FlashVar ID");
define("_WEBPHOTO_FLASHVAR_TIME_CREATE" , "Create Time" ) ;
define("_WEBPHOTO_FLASHVAR_TIME_UPDATE" , "Update Time" ) ;
define("_WEBPHOTO_FLASHVAR_ITEM_ID","Item ID");
define("_WEBPHOTO_FLASHVAR_WIDTH","Player Width");
define("_WEBPHOTO_FLASHVAR_HEIGHT","Player Height");
define("_WEBPHOTO_FLASHVAR_DISPLAYWIDTH","Display Width");
define("_WEBPHOTO_FLASHVAR_DISPLAYHEIGHT","Display Height");
define("_WEBPHOTO_FLASHVAR_IMAGE_SHOW","Show Image");
define("_WEBPHOTO_FLASHVAR_SEARCHBAR","Searchbar");
define("_WEBPHOTO_FLASHVAR_SHOWEQ","Show Equalizer");
define("_WEBPHOTO_FLASHVAR_SHOWICONS","Activity Icons");
define("_WEBPHOTO_FLASHVAR_SHOWNAVIGATION","Show Navigation");
define("_WEBPHOTO_FLASHVAR_SHOWSTOP","Show Stop");
define("_WEBPHOTO_FLASHVAR_SHOWDIGITS","Show Digits");
define("_WEBPHOTO_FLASHVAR_SHOWDOWNLOAD","Show Download");
define("_WEBPHOTO_FLASHVAR_USEFULLSCREEN","Full Screen Button");
define("_WEBPHOTO_FLASHVAR_AUTOSCROLL","Scroll Bar Off");
define("_WEBPHOTO_FLASHVAR_THUMBSINPLAYLIST","Thumbnails");
define("_WEBPHOTO_FLASHVAR_AUTOSTART","Auto Start");
define("_WEBPHOTO_FLASHVAR_REPEAT","Repeat");
define("_WEBPHOTO_FLASHVAR_SHUFFLE","Shuffle");
define("_WEBPHOTO_FLASHVAR_SMOOTHING","Smoothing");
define("_WEBPHOTO_FLASHVAR_ENABLEJS","Enable Javascript");
define("_WEBPHOTO_FLASHVAR_LINKFROMDISPLAY","Link from Display");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE","Screen Hyperlink");
define("_WEBPHOTO_FLASHVAR_BUFFERLENGTH","Bufferlength");
define("_WEBPHOTO_FLASHVAR_ROTATETIME","Image Rotation Time");
define("_WEBPHOTO_FLASHVAR_VOLUME","volume");
define("_WEBPHOTO_FLASHVAR_LINKTARGET","Link Target");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH","Stretch Image/Video");
define("_WEBPHOTO_FLASHVAR_TRANSITION","Slide Show Transition");
define("_WEBPHOTO_FLASHVAR_SCREENCOLOR","Screen Color");
define("_WEBPHOTO_FLASHVAR_BACKCOLOR","Back Color");
define("_WEBPHOTO_FLASHVAR_FRONTCOLOR","Front Color");
define("_WEBPHOTO_FLASHVAR_LIGHTCOLOR","Light Color");
define("_WEBPHOTO_FLASHVAR_TYPE","Type");
define("_WEBPHOTO_FLASHVAR_FILE","Media File");
define("_WEBPHOTO_FLASHVAR_IMAGE","Preview Image");
define("_WEBPHOTO_FLASHVAR_LOGO","Player Logo Image");
define("_WEBPHOTO_FLASHVAR_LINK","Screen Hyperlink");
define("_WEBPHOTO_FLASHVAR_AUDIO","Audio Program");
define("_WEBPHOTO_FLASHVAR_CAPTIONS","Captions URL");
define("_WEBPHOTO_FLASHVAR_FALLBACK","Fallback URL");
define("_WEBPHOTO_FLASHVAR_CALLBACK","Callback URL");
define("_WEBPHOTO_FLASHVAR_JAVASCRIPTID","JavaScript ID");
define("_WEBPHOTO_FLASHVAR_RECOMMENDATIONS","Recommendations");
define("_WEBPHOTO_FLASHVAR_STREAMSCRIPT","Stream Script URL");
define("_WEBPHOTO_FLASHVAR_SEARCHLINK","Search Link");

// log file
define("_WEBPHOTO_LOGFILE_LINE","Line");
define("_WEBPHOTO_LOGFILE_DATE","Date");
define("_WEBPHOTO_LOGFILE_REFERER","Referer");
define("_WEBPHOTO_LOGFILE_IP","IP Address");
define("_WEBPHOTO_LOGFILE_STATE","State");
define("_WEBPHOTO_LOGFILE_ID","ID");
define("_WEBPHOTO_LOGFILE_TITLE","Title");
define("_WEBPHOTO_LOGFILE_FILE","File");
define("_WEBPHOTO_LOGFILE_DURATION","Duration");

// item option
define("_WEBPHOTO_ITEM_KIND_UNDEFINED","Undefined");
define("_WEBPHOTO_ITEM_KIND_NONE","No Media");
define("_WEBPHOTO_ITEM_KIND_GENERAL","General");
define("_WEBPHOTO_ITEM_KIND_IMAGE","Image (jpg,gif,png)");
define("_WEBPHOTO_ITEM_KIND_VIDEO","Video (wmv,mov,flv...");
define("_WEBPHOTO_ITEM_KIND_AUDIO","Audio (mp3...)");
define("_WEBPHOTO_ITEM_KIND_EMBED","Embed Plugin");
define("_WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL","External General");
define("_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE","External Image");
define("_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED","PlayList Web Feed");
define("_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR", "PlayList Media directory");

define("_WEBPHOTO_ITEM_DISPLAYTYPE_GENERAL","General");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_IMAGE","Image (jpg,gif,png)");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_EMBED","Embed Plugin");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_SWFOBJECT","FlashPlayer (swf)");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_MEDIAPLAYER","MediaPlayer (jpg,gif,png,flv,mp3)");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_IMAGEROTATOR","ImageRotator (jpg,gif,png)");

define("_WEBPHOTO_ITEM_ONCLICK_PAGE","Detail Page");
define("_WEBPHOTO_ITEM_ONCLICK_DIRECT","Direct Link");
define("_WEBPHOTO_ITEM_ONCLICK_POPUP","Image Popup");

define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_DSC","What is the media file type?");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_NONE","None");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_IMAGE","Image (jpg,gif,png)");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_AUDIO","Audio (mp3)");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_VIDEO","Video (flv)");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_FLASH","Flash (swf)");

define("_WEBPHOTO_ITEM_SHOWINFO_DESCRIPTION","Description");
define("_WEBPHOTO_ITEM_SHOWINFO_LOGOIMAGE","Thumbnail");
define("_WEBPHOTO_ITEM_SHOWINFO_CREDITS","Credits");
define("_WEBPHOTO_ITEM_SHOWINFO_STATISTICS","Statistics");
define("_WEBPHOTO_ITEM_SHOWINFO_SUBMITTER","Submitter");
define("_WEBPHOTO_ITEM_SHOWINFO_POPUP","PopUp");
define("_WEBPHOTO_ITEM_SHOWINFO_TAGS","Tags");
define("_WEBPHOTO_ITEM_SHOWINFO_DOWNLOAD","Download");
define("_WEBPHOTO_ITEM_SHOWINFO_WEBSITE","Site");
define("_WEBPHOTO_ITEM_SHOWINFO_WEBFEED","Feed");

define("_WEBPHOTO_ITEM_STATUS_WAITING","Waiting Approval");
define("_WEBPHOTO_ITEM_STATUS_APPROVED","Appoved");
define("_WEBPHOTO_ITEM_STATUS_UPDATED","Online (Updated)");
define("_WEBPHOTO_ITEM_STATUS_OFFLINE","Off Line");
define("_WEBPHOTO_ITEM_STATUS_EXPIRED","Expired");

// player option
define("_WEBPHOTO_PLAYER_STYLE_MONO","Monochrome");
define("_WEBPHOTO_PLAYER_STYLE_THEME","Color from Theme");
define("_WEBPHOTO_PLAYER_STYLE_PLAYER","Custom Player");
define("_WEBPHOTO_PLAYER_STYLE_PAGE","Custom Player/Page");

// flashvar desc
define("_WEBPHOTO_FLASHVAR_ID_DSC","[Basics] <br />Use this to set the RTMP stream identifier with the mediaplayer. <br />The ID will also be sent to statistics callbacks. <br />If you play a playlist, you can set an id for every entry. ");
//define("_WEBPHOTO_FLASHVAR_HEIGHT_DSC","[Basics] ");
//define("_WEBPHOTO_FLASHVAR_WIDTH_DSC","[Basics]  ");
define("_WEBPHOTO_FLASHVAR_DISPLAYHEIGHT_DSC","[Playlist] [mediaplayer] <br />Set this smaller as the height to show a playlist below the display. <br />If you set it the same as the height, the controlbar will auto-hide on top of the video. ");
define("_WEBPHOTO_FLASHVAR_DISPLAYWIDTH_DSC","[Playlist] [mediaplayer] <br />Bottom tracks:<br /> Screen = Player<br /> Side tracks:<br />Screen < Player ");
define("_WEBPHOTO_FLASHVAR_DISPLAY_DEFAULT","when 0, use value of the player.");
//define("_WEBPHOTO_FLASHVAR_SCREENCOLOR_DSC","[Colors] <br />[imagerotator] change this to your HTML page's color make images of different sizes blend nicely. ");
//define("_WEBPHOTO_FLASHVAR_BACKCOLOR_DSC","[Colors] <br />Backgroundcolor of the controls");
//define("_WEBPHOTO_FLASHVAR_FRONTCOLOR_DSC","[Colors] <br />Texts &amp; buttons color of the controls");
//define("_WEBPHOTO_FLASHVAR_LIGHTCOLOR_DSC","[Colors] <br />Rollover color of the controls");
define("_WEBPHOTO_FLASHVAR_COLOR_DEFAULT","when blank, use value of the player.");
define("_WEBPHOTO_FLASHVAR_SEARCHBAR_DSC","[Basics] <br />Set this to false to hide the searchbar below the display. <br />You can set the search destination with the searchlink flashvar. ");
define("_WEBPHOTO_FLASHVAR_IMAGE_SHOW_DSC","[Basics] <br />true = Show preview image");
//define("_WEBPHOTO_FLASHVAR_IMAGE_DSC","[Basics] <br />If you play a sound or movie, set this to the url of a preview image. <br />When using a playlist, you can set an image for every entry. ");
define("_WEBPHOTO_FLASHVAR_FILE_DSC","[Basics] <br />Sets the location of the file or playlist to play. <br />The imagerotator only plays playlists. ");
//define("_WEBPHOTO_FLASHVAR_LOGO_DSC","[Display] <br />Set this to an image that can be put as a watermark logo in the top right corner of the display. <br />Transparent PNG files give the best results. ");
//define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_DSC","[Display] <br />Sets how to stretch images/movies to make them fit the display. <br />false (default) = fit the display. <br />true = stretch them proportionally to fill the display. <br />fit = stretch them disproportionally<br />none = keep original dimensions. ");
define("_WEBPHOTO_FLASHVAR_SHOWEQ_DSC","[Display] <br />Set this to true to show a (fake) equalizer at the bottom of the display. <br />Nice for MP3 files. ");
//define("_WEBPHOTO_FLASHVAR_SHOWICONS_DSC","[Display] <br />Set this to false to hide the activity icon and play button in the middle of the display. ");
//define("_WEBPHOTO_FLASHVAR_TRANSITION_DSC","[Display] [imagerotator] <br />Sets the transition to use between images. ");
//define("_WEBPHOTO_FLASHVAR_SHOWNAVIGATION_DSC","[Controlbar] <br />Set this to false to completely hide the controlbar. ");
define("_WEBPHOTO_FLASHVAR_SHOWSTOP_DSC","[Controlbar] [mediaplayer] <br />Set this to true to show a stop button in the controlbar. ");
define("_WEBPHOTO_FLASHVAR_SHOWDIGITS_DSC","[Controlbar] [mediaplayer] <br />Set this to false to hide the elapsed/remaining digits in the controlbar. ");
define("_WEBPHOTO_FLASHVAR_SHOWDOWNLOAD_DSC","[Controlbar] [mediaplayer] <br />Set this to true to show a button in the player controlbar which links to the link flashvar. ");
//define("_WEBPHOTO_FLASHVAR_USEFULLSCREEN_DSC","[Controlbar] <br />Set this to false to hide the fullscreen button and disable fullscreen. ");
define("_WEBPHOTO_FLASHVAR_AUTOSCROLL_DSC","[Playlist] [mediaplayer] <br />Set this to true to automatically scroll through the playlist on rollover, instead of using a scrollbar. ");
define("_WEBPHOTO_FLASHVAR_THUMBSINPLAYLIST_DSC","[Playlist] [mediaplayer] <br />Set this to false to hide preview images in the display");
//define("_WEBPHOTO_FLASHVAR_AUDIO_DSC","[Playback] <br />Assigns an additional, synchronized MP3. <br />Use this for a closed audio description or director's comments with the mediaplayer or background music with the rotator. <br />When using the mediaplayer and a playlist, you can assign audio to every entry. ");
//define("_WEBPHOTO_FLASHVAR_AUTOSTART_DSC","[Playback] <br />Set this to true in the player to automatically start playing when the page loads, <br />or set this to false with the rotator to prevent the automatic rotation.");
//define("_WEBPHOTO_FLASHVAR_BUFFERLENGTH_DSC","[Playback]  [mediaplayer] <br />Sets the number of seconds a video should be buffered before the players starts playback.<br />Set this small for fast connections or short videos and big for slow connections.  ");
define("_WEBPHOTO_FLASHVAR_CAPTIONS_DSC","[Playback] [mediaplayer] <br />Captions should be in TimedText format. <br />When using a playlist, you can assign captions for every entry. ");
define("_WEBPHOTO_FLASHVAR_FALLBACK_DSC","[Playback] [mediaplayer] <br />If you play an MP4 file, set here the location of an FLV fallback. <br />It'll automatically be picked by older flash player. ");
//define("_WEBPHOTO_FLASHVAR_REPEAT_DSC","[Playback] <br />Set this to true to automatically repeat playback of all files. <br />Set this to list to playback an entire playlist once. ");
//define("_WEBPHOTO_FLASHVAR_ROTATETIME_DSC","[Playback] <br />Sets the number of seconds an image is played back. ");
//define("_WEBPHOTO_FLASHVAR_SHUFFLE_DSC","[Playback] <br />Set this to true to playback a playlist in random order. ");
//define("_WEBPHOTO_FLASHVAR_SMOOTHING_DSC","[Playback] [mediaplayer] <br />Set this to false to turn of the smoothing of video. <br />Quality will decrease, but performance will increase. <br />Good for HD files and slower computers. ");
//define("_WEBPHOTO_FLASHVAR_VOLUME_DSC","[Playback] <br />sets the startup volume for playback of sounds, movies and audiotracks. ");
define("_WEBPHOTO_FLASHVAR_ENABLEJS_DSC","[External] <br />Set this to true to enable javascript interaction. <br />This'll only work online! <br />Javascript interaction includes playback control, asynchroneous loading of media files and return of track information. ");
define("_WEBPHOTO_FLASHVAR_JAVASCRIPTID_DSC","[External] <br />If you interact with multiple mediaplayers/rotators in javascript, use this flashvar to give each of them a unique ID. ");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_DSC","[External] <br />This link is assigned to the display, logo and link button. <br >when None, assign nothing. <br />Else, assign a webpage to open. ");
//define("_WEBPHOTO_FLASHVAR_LINK_DSC","[External] <br />Set this to an external URL or downloadeable version of the file. <br />This link is assigned to the display, logo and link button. <br />With playlists, set links for every entry in the XML. ");
//define("_WEBPHOTO_FLASHVAR_LINKFROMDISPLAY_DSC","[External] <br />Set this to true to make a click on the display result in a jump to the webpage assigned to the link flashvar. ");
//define("_WEBPHOTO_FLASHVAR_LINKTARGET_DSC","[External] <br />Set this to the frame you want hyperlinks to open in. <br />Set it to _blank to open links in a new window or _top to open in the top frame. ");
define("_WEBPHOTO_FLASHVAR_CALLBACK_DSC","[External] <br />Set this to a serverside script that can process statistics. <br />The player will send it a POST every time an item starts/stops. <br />To send callbacks automatically to Google Analytics, set this to urchin or analytics. ");
define("_WEBPHOTO_FLASHVAR_RECOMMENDATIONS_DSC","[External] [mediaplayer] <br />Set this to an XML with items you want to recommend. <br />The thumbs will show up when the current movie stops playing, just like Youtube. ");
define("_WEBPHOTO_FLASHVAR_SEARCHLINK_DSC","[External] [mediaplayer] <br />Sets the destination of the searchbar. <br />The default is 'search.longtail.tv', but you can set other destinations. <br />Use the searchbar flashvar to hide the bar altogether. ");
define("_WEBPHOTO_FLASHVAR_STREAMSCRIPT_DSC","[External] [mediaplayer] <br />Set this to the URL of a script to use for http streaming movies. <br />The parameters file and pos are sent to the script. <br />If you use LigHTTPD streaming, set this to lighttpd. ");
define("_WEBPHOTO_FLASHVAR_TYPE_DSC","[External] [mediaplayer] <br />the mediaplayer which determines the type of file to play based upon the last three characters of the file flashvar. <br />This doesn't work with database id's or mod_rewrite, so you can set this flashvar to the correct filetype. <br />If not sure, the player assumes a playlist is loaded. ");

// flashvar option
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_NONE","None");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_SITE","Website URL");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_PAGE","Detail Page");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_FILE","Media File");
define("_WEBPHOTO_FLASHVAR_LINKTREGET_SELF","Self Window");
define("_WEBPHOTO_FLASHVAR_LINKTREGET_BLANK","New Window");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_FALSE","False");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_FIT","Fit");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_TRUE","True");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_NONE","None");
define("_WEBPHOTO_FLASHVAR_TRANSITION_OFF","Slide Show Player Off");
define("_WEBPHOTO_FLASHVAR_TRANSITION_FADE","Fade");
define("_WEBPHOTO_FLASHVAR_TRANSITION_SLOWFADE","Slow Fade");
define("_WEBPHOTO_FLASHVAR_TRANSITION_BGFADE","Background Fade");
define("_WEBPHOTO_FLASHVAR_TRANSITION_CIRCLES","Circles");
define("_WEBPHOTO_FLASHVAR_TRANSITION_BLOCKS","Blocks");
define("_WEBPHOTO_FLASHVAR_TRANSITION_BUBBLES","Bubbles");
define("_WEBPHOTO_FLASHVAR_TRANSITION_FLASH","Flash");
define("_WEBPHOTO_FLASHVAR_TRANSITION_FLUIDS","Fluids");
define("_WEBPHOTO_FLASHVAR_TRANSITION_LINES","Lines");
define("_WEBPHOTO_FLASHVAR_TRANSITION_RANDOM","Random");

// edit form
define("_WEBPHOTO_CAP_DETAIL","Show Detail");
define("_WEBPHOTO_CAP_DETAIL_ONOFF","On/Off");
define("_WEBPHOTO_PLAYER","Player");
define("_WEBPHOTO_EMBED_ADD", "Add Embed Plugin" ) ;
define("_WEBPHOTO_EMBED_THUMB","The external source will provide a thumbnail.");
define("_WEBPHOTO_ERR_EMBED","You MUST set plugin");
define("_WEBPHOTO_ERR_PLAYLIST","You MUST set playlist");

// sort
define("_WEBPHOTO_SORT_VOTESA","Votes (Least)");
define("_WEBPHOTO_SORT_VOTESD","Votes (Most)");
define("_WEBPHOTO_SORT_VIEWSA","Media Views (Least)");
define("_WEBPHOTO_SORT_VIEWSD","Media Views (Most)");

// flashvar form
//define("_WEBPHOTO_FLASHVARS_FORM","FlashVars");
define("_WEBPHOTO_FLASHVARS_LIST","List of Flash Variables");
define("_WEBPHOTO_FLASHVARS_LOGO_SELECT","Select a player logo");
define("_WEBPHOTO_FLASHVARS_LOGO_UPLOAD","Upload a player logo ");
define("_WEBPHOTO_FLASHVARS_LOGO_DSC","[Display] <br />Player Logos are in ");
define("_WEBPHOTO_BUTTON_COLOR_PICKUP","Color");
define("_WEBPHOTO_BUTTON_RESTORE","Restore Default");

// Playlist Cache 
define("_WEBPHOTO_PLAYLIST_STATUS_REPORT","Status Report");
define("_WEBPHOTO_PLAYLIST_STATUS_FETCHED","This webfeed has been fetched and cached.");
define("_WEBPHOTO_PLAYLIST_STATUS_CREATED","A new playlist has been cached");
define("_WEBPHOTO_PLAYLIST_ERR_CACHE","[ERROR] creating cache file");
define("_WEBPHOTO_PLAYLIST_ERR_FETCH","Failed to fetch the web feed. <br />Please confirm the web feed location and refresh the cache.");
define("_WEBPHOTO_PLAYLIST_ERR_NODIR","The media directory does not exist");
define("_WEBPHOTO_PLAYLIST_ERR_EMPTYDIR","The media directory is empty");
define("_WEBPHOTO_PLAYLIST_ERR_WRITE","can not write the cache file");

define("_WEBPHOTO_USER",  "User" ) ;
define("_WEBPHOTO_OR",  "OR" ) ;

//---------------------------------------------------------
// v0.60
//---------------------------------------------------------
// item table
//define("_WEBPHOTO_ITEM_ICON" , "Icon Name" ) ;

define("_WEBPHOTO_ITEM_EXTERNAL_MIDDLE" , "External Middle URL" ) ;

// cat table
define("_WEBPHOTO_CAT_IMG_NAME" , "Category Image Name" ) ;

// edit form
define("_WEBPHOTO_CAP_MIDDLE_SELECT", "Select the middle image");

//---------------------------------------------------------
// v0.70
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_CODEINFO", "Code Info");
define("_WEBPHOTO_ITEM_PAGE_WIDTH",  "Page Width");
define("_WEBPHOTO_ITEM_PAGE_HEIGHT", "Page Height");
define("_WEBPHOTO_ITEM_EMBED_TEXT",  "Embed");

// item option
define("_WEBPHOTO_ITEM_CODEINFO_CONT","Media");
define("_WEBPHOTO_ITEM_CODEINFO_THUMB","Thumbnail Image");
define("_WEBPHOTO_ITEM_CODEINFO_MIDDLE","Middle Image");
define("_WEBPHOTO_ITEM_CODEINFO_FLASH","Flash Video");
define("_WEBPHOTO_ITEM_CODEINFO_DOCOMO","Docomo Video");
define("_WEBPHOTO_ITEM_CODEINFO_PAGE","URL");
define("_WEBPHOTO_ITEM_CODEINFO_SITE","Site");
define("_WEBPHOTO_ITEM_CODEINFO_PLAY","Playlist");
define("_WEBPHOTO_ITEM_CODEINFO_EMBED","Embed");
define("_WEBPHOTO_ITEM_CODEINFO_JS","Script");

define("_WEBPHOTO_ITEM_PLAYLIST_TIME_HOUR", "1 hour");
define("_WEBPHOTO_ITEM_PLAYLIST_TIME_DAY",  "1 day");
define("_WEBPHOTO_ITEM_PLAYLIST_TIME_WEEK", "1 week");
define("_WEBPHOTO_ITEM_PLAYLIST_TIME_MONTH","1 month");

// photo
define("_WEBPHOTO_DOWNLOAD","Download");

// file_read
define("_WEBPHOTO_NO_FILE", "Not exist file");

//---------------------------------------------------------
// v0.80
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_ICON_NAME" ,   "Icon Name" ) ;
define("_WEBPHOTO_ITEM_ICON_WIDTH" ,  "Icon Width" ) ;
define("_WEBPHOTO_ITEM_ICON_HEIGHT" , "Icon Height" ) ;

// item form
define("_WEBPHOTO_DSC_SET_ITEM_TIME_UPDATE",  "Set update time");
define("_WEBPHOTO_DSC_SET_ITEM_TIME_PUBLISH", "Set publish time");
define("_WEBPHOTO_DSC_SET_ITEM_TIME_EXPIRE",  "Set expire time");

//---------------------------------------------------------
// v0.81
//---------------------------------------------------------
// vote option
define("_WEBPHOTO_VOTE_RATING_1", "1");
define("_WEBPHOTO_VOTE_RATING_2", "2");
define("_WEBPHOTO_VOTE_RATING_3", "3");
define("_WEBPHOTO_VOTE_RATING_4", "4");
define("_WEBPHOTO_VOTE_RATING_5", "5");
define("_WEBPHOTO_VOTE_RATING_6", "6");
define("_WEBPHOTO_VOTE_RATING_7", "7");
define("_WEBPHOTO_VOTE_RATING_8", "8");
define("_WEBPHOTO_VOTE_RATING_9", "9");
define("_WEBPHOTO_VOTE_RATING_10","10");

//---------------------------------------------------------
// v0.90
//---------------------------------------------------------
// edit form
define("_WEBPHOTO_GROUP_PERM_ALL" , "All Groups" ) ;

//---------------------------------------------------------
// v1.00
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_EDITOR", "Editor");
define("_WEBPHOTO_ITEM_DESCRIPTION_HTML",   "HTML tags");
define("_WEBPHOTO_ITEM_DESCRIPTION_SMILEY", "Smiley icons");
define("_WEBPHOTO_ITEM_DESCRIPTION_XCODE",  "XOOPS codes");
define("_WEBPHOTO_ITEM_DESCRIPTION_IMAGE",  "Images");
define("_WEBPHOTO_ITEM_DESCRIPTION_BR",     "Linebreak");

// edit form
define("_WEBPHOTO_TITLE_EDITOR_SELECT", "Select Editor");
define("_WEBPHOTO_CAP_DESCRIPTION_OPTION", "Options");
define("_WEBPHOTO_CAP_HTML",   "Enable HTML tags");
define("_WEBPHOTO_CAP_SMILEY", "Enable smiley icons");
define("_WEBPHOTO_CAP_XCODE",  "Enable XOOPS codes");
define("_WEBPHOTO_CAP_IMAGE",  "Enable images");
define("_WEBPHOTO_CAP_BR",     "Enable linebreak");

//---------------------------------------------------------
// v1.10
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_WIDTH",  "Width of Image");
define("_WEBPHOTO_ITEM_HEIGHT", "Height of Image");
define("_WEBPHOTO_ITEM_CONTENT", "Text Content");

//---------------------------------------------------------
// v1.20
//---------------------------------------------------------
// item option
define("_WEBPHOTO_ITEM_CODEINFO_PDF","PDF");
define("_WEBPHOTO_ITEM_CODEINFO_SWF","Flash swf");

// form
define("_WEBPHOTO_ERR_PDF", "Cannot create PDF");
define("_WEBPHOTO_ERR_SWF", "Cannot create Flash swf");

// jodconverter
define("_WEBPHOTO_JODCONVERTER_JUNK_WORDS", "");

//---------------------------------------------------------
// v1.30
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_MAP",  "GoogleMap");
define("_WEBPHOTO_MAP_LARGE", "Show large map");

// timeline
define("_WEBPHOTO_TITLE_TIMELINE",  "Timeline");
define("_WEBPHOTO_TIMELINE_ON",  "Show timeline");
define("_WEBPHOTO_TIMELINE_OFF", "Hide timeline");
define("_WEBPHOTO_TIMELINE_SCALE_WEEK",   "One Week") ;
define("_WEBPHOTO_TIMELINE_SCALE_MONTH",  "One Month") ;
define("_WEBPHOTO_TIMELINE_SCALE_YEAR",   "One Year") ;
define("_WEBPHOTO_TIMELINE_SCALE_DECADE", "10 Years") ;
define("_WEBPHOTO_TIMELINE_LARGE", "Show large timeline");
define("_WEBPHOTO_TIMELINE_CAUTION_IE", "In InternetExplore, you cannot see it sometimes. Please try in other browsers such as Firfox, Opera, Safari.");

// item option
define("_WEBPHOTO_ITEM_CODEINFO_SMALL","Small image");

// edit form
define("_WEBPHOTO_CAP_SMALL_SELECT", "Select the small image");

//---------------------------------------------------------
// v1.60
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_SUBMIT_SELECT",  "Select submit form");
define("_WEBPHOTO_TITLE_SUBMIT_SINGLE",  "Registration of one photo video media");
define("_WEBPHOTO_TITLE_SUBMIT_BULK",  "Batch registration of photo video media");

//---------------------------------------------------------
// v1.80
//---------------------------------------------------------
// item option
define("_WEBPHOTO_ITEM_CODEINFO_JPEG","JPEG Image");
define("_WEBPHOTO_ITEM_CODEINFO_MP3", "MP3");

// form
define("_WEBPHOTO_ERR_JPEG", "Cannot create JPEG");
define("_WEBPHOTO_ERR_MP3",  "Cannot create MP3");

// mime option
define("_WEBPHOTO_MIME_KIND_GENERAL",       "Genaral") ;
define("_WEBPHOTO_MIME_KIND_IMAGE",         "Image" ) ;
define("_WEBPHOTO_MIME_KIND_IMAGE_CONVERT", "Image convert"  ) ;
define("_WEBPHOTO_MIME_KIND_VIDEO",         "Video" ) ;
define("_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG",  "Video ffmpeg" ) ;
define("_WEBPHOTO_MIME_KIND_AUDIO",         "Audio" ) ;
define("_WEBPHOTO_MIME_KIND_AUDIO_MID",     "Audio midi" ) ;
define("_WEBPHOTO_MIME_KIND_AUDIO_WAV",     "Audio wav" ) ;
define("_WEBPHOTO_MIME_KIND_OFFICE",        "Office" ) ;

// player option
define("_WEBPHOTO_PLAYER_TITLE_DEFAULT", "Undefined");

define("_WEBPHOTO_TITLE_IMAGE",   "Images" ) ;
define("_WEBPHOTO_TITLE_VIDEO",   "Videos" ) ;
define("_WEBPHOTO_TITLE_MUSIC",   "Musics" ) ;
define("_WEBPHOTO_TITLE_OFFICE",  "Offices" ) ;

//---------------------------------------------------------
// v1.90
//---------------------------------------------------------
// menu
define("_WEBPHOTO_TITLE_PICTURE", "Pictures" ) ;
define("_WEBPHOTO_TITLE_AUDIO",   "Musics" ) ;

// item 
define("_WEBPHOTO_ITEM_DETAIL_ONCLICK", "Action on click the content image");
define("_WEBPHOTO_ITEM_WEIGHT", "Weight");

// item option
define("_WEBPHOTO_ITEM_KIND_OFFICE","Office (doc,xls,ppt...)");
define("_WEBPHOTO_ITEM_KIND_IMAGE_OTHER","Image (bmp,tif,wmf...)");
define("_WEBPHOTO_ITEM_DETAIL_ONCLICK_DEFAULT", "Default (uploaded content)");

// file
define("_WEBPHOTO_FILE_KIND_CONT" ,  "Content" ) ;
define("_WEBPHOTO_FILE_KIND_THUMB" ,  "Thumbnail" ) ;
define("_WEBPHOTO_FILE_KIND_MIDDLE" ,  "Middle image" ) ;
define("_WEBPHOTO_FILE_KIND_FLASH" ,  "Flash flv" ) ;
define("_WEBPHOTO_FILE_KIND_DOCOMO" ,  "Docomo video" ) ;
define("_WEBPHOTO_FILE_KIND_PDF" ,  "PDF" ) ;
define("_WEBPHOTO_FILE_KIND_SWF" ,  "Flash swf" ) ;
define("_WEBPHOTO_FILE_KIND_SMALL" ,  "Small image" ) ;
define("_WEBPHOTO_FILE_KIND_JPEG" ,  "JPEG" ) ;
define("_WEBPHOTO_FILE_KIND_MP3" , "MP3" ) ;

// mime option
define("_WEBPHOTO_MIME_KIND_OFFICE_DOC", "Office doc" ) ;
define("_WEBPHOTO_MIME_KIND_OFFICE_XLS", "Office xls" ) ;
define("_WEBPHOTO_MIME_KIND_OFFICE_PPT", "Office ppt" ) ;
define("_WEBPHOTO_MIME_KIND_OFFICE_PDF", "Office pdf" ) ;

// submit
define("_WEBPHOTO_UPLOADING", "Uploading..." ) ;
define("_WEBPHOTO_EMBED_ENTER", "Enter the video id from the url" ) ;
define("_WEBPHOTO_EMBED_EXAMPLE", "Example" ) ;

// photo
define("_WEBPHOTO_ICON_GROUP", "Only in group" ) ;

//---------------------------------------------------------
// v2.00
//---------------------------------------------------------
// item 
define("_WEBPHOTO_ITEM_PERM_LEVEL", "Permission Level");
define("_WEBPHOTO_ITEM_PERM_LEVEL_PUBLIC", "Public");
define("_WEBPHOTO_ITEM_PERM_LEVEL_GROUP", "Only in group");

// cat
define("_WEBPHOTO_CAT_GROUP_ID", "User Group ID");

//---------------------------------------------------------
// v2.10
//---------------------------------------------------------
// item 
define("_WEBPHOTO_ITEM_DESCRIPTION_SCROLL", "Scroll view of Photo Video Media Description");
define("_WEBPHOTO_ITEM_DESCRIPTION_SCROLL_DSC", "Enter the height of the scroll by the px unit.
 <br />When 0, it is usual view without the scroll.");

// item option
define("_WEBPHOTO_ITEM_DETAIL_ONCLICK_IMAGE", "Show photo video media in new window");
define("_WEBPHOTO_ITEM_DETAIL_ONCLICK_LIGHTBOX", "Show photo video media with lightbox");

// submit
define("_WEBPHOTO_MAIL_SUBMIT_WAITING", "Waiting Approval");
define("_WEBPHOTO_MAIL_SUBMIT_APPROVE", "Approved your photo video media");
define("_WEBPHOTO_MAIL_SUBMIT_REFUSE",  "Refused your photo video media");

// edit
define("_WEBPHOTO_LOOK_PHOTO","Look Photo Video Media");

//---------------------------------------------------------
// v2.11
//---------------------------------------------------------
// submit
define("_WEBPHOTO_ITEM_KIND_GROUP_UNDEFINED", "Undefined" ) ;
define("_WEBPHOTO_ITEM_KIND_GROUP_IMAGE", "Image" ) ;
define("_WEBPHOTO_ITEM_KIND_GROUP_VIDEO", "Video" ) ;
define("_WEBPHOTO_ITEM_KIND_GROUP_AUDIO", "Audio" ) ;
define("_WEBPHOTO_ITEM_KIND_GROUP_OFFICE", "Office" ) ;
define("_WEBPHOTO_ITEM_KIND_GROUP_OTHERS", "Others" ) ;

define("_WEBPHOTO_CONFIRM_PHOTODEL_DSC","All information, description comment and etc, are deleted");

// search
define("_WEBPHOTO_SEARCH_KEYTOOSHORT", "Keywords must be at least <b>%s</b> characters long");

//---------------------------------------------------------
// v2.20
//---------------------------------------------------------
// edit form
define("_WEBPHOTO_EMBED_SUPPORT_TITLE", "The external source will provide Title");
define("_WEBPHOTO_EMBED_SUPPORT_DESCRIPTION", "The external source will provide Description");
define("_WEBPHOTO_EMBED_SUPPORT_SITEURL", "The external source will provide Site url");
define("_WEBPHOTO_EMBED_SUPPORT_DURATION", "The external source will provide Duration");
define("_WEBPHOTO_EMBED_SUPPORT_EMBED_TEXT", "The external source will provide Emded");
define("_WEBPHOTO_EMBED_SUPPORT_TAGS", "The external source will provide Tags");

//---------------------------------------------------------
// v2.30
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_DISPLAYFILE", "Display File");
define("_WEBPHOTO_ITEM_FILE_ID_11" , "File ID: WAV" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_12" , "File ID: Large" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_13" , "File ID: 13" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_14" , "File ID: 14" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_15" , "File ID: 15" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_16" , "File ID: 16" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_17" , "File ID: 17" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_18" , "File ID: 18" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_19" , "File ID: 19" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_20" , "File ID: 20" ) ;

// file
define("_WEBPHOTO_FILE_KIND_11" , "WAVE" ) ;
define("_WEBPHOTO_FILE_KIND_12" , "Large" ) ;
define("_WEBPHOTO_FILE_KIND_13" , "File Kind: 13" ) ;
define("_WEBPHOTO_FILE_KIND_14" , "File Kind: 14" ) ;
define("_WEBPHOTO_FILE_KIND_15" , "File Kind: 15" ) ;
define("_WEBPHOTO_FILE_KIND_16" , "File Kind: 16" ) ;
define("_WEBPHOTO_FILE_KIND_17" , "File Kind: 17" ) ;
define("_WEBPHOTO_FILE_KIND_18" , "File Kind: 18" ) ;
define("_WEBPHOTO_FILE_KIND_19" , "File Kind: 19" ) ;
define("_WEBPHOTO_FILE_KIND_20" , "File Kind: 20" ) ;

// item kind
define("_WEBPHOTO_ITEM_KIND_IMAGE_CMYK", "Image (cmyk)" ) ;
define("_WEBPHOTO_ITEM_KIND_VIDEO_H264", "Video (H264)");

// item detail
define("_WEBPHOTO_ITEM_DETAIL_ONCLICK_DOWNLOAD", "Download");

// item codeinfo
define("_WEBPHOTO_ITEM_CODEINFO_LARGE", "Large Image");
define("_WEBPHOTO_ITEM_CODEINFO_WAV",   "WAVE");

// item display file
define("_WEBPHOTO_ITEM_DISPLAYFILE_DEFAULT","Default");

// photo form
define("_WEBPHOTO_CAP_JPEG_SELECT", "Select the JPEG image");
define("_WEBPHOTO_FILE_JPEG_DSC", "Create the thumb image");
define("_WEBPHOTO_FILE_JPEG_DELETE_DSC", "Delete with the thumb image");

// mime option
define("_WEBPHOTO_MIME_KIND_IMAGE_JPEG",    "Image jpg" ) ;
define("_WEBPHOTO_MIME_KIND_VIDEO_FLV",     "Video flv" ) ;
define("_WEBPHOTO_MIME_KIND_AUDIO_MP3",     "Audio mp3" ) ;
define("_WEBPHOTO_MIME_KIND_AUDIO_FFMPEG",  "Audio ffmpeg" ) ;

// error
define("_WEBPHOTO_ERR_WAV",  "Cannot create wave");

//---------------------------------------------------------
// v2.40
//---------------------------------------------------------
// === redefine previous definition ===
define("_WEBPHOTO_FLASHVARS_FORM","Edit of Flash Player's Options");

// --- flashvar ---
// common
define("_WEBPHOTO_FLASHVAR_HEIGHT_DSC","[Basics] height ");
define("_WEBPHOTO_FLASHVAR_WIDTH_DSC","[Basics] width ");

define("_WEBPHOTO_FLASHVAR_SCREENCOLOR_DSC","[Colors] screencolor <br />Background color of the display. <br/>Is black by default.");
define("_WEBPHOTO_FLASHVAR_BACKCOLOR_DSC","[Colors]  backcolor <br />background color of the controlbar and playlist. <br/>This is white by default.");
define("_WEBPHOTO_FLASHVAR_FRONTCOLOR_DSC","[Colors] frontcolor <br />color of all icons and texts in the controlbar and playlist. <br/>Is black by default.");
define("_WEBPHOTO_FLASHVAR_LIGHTCOLOR_DSC","[Colors] lightcolor <br />Color of an icon or text when you rollover it with the mouse. <br />Is black by default.");

define("_WEBPHOTO_FLASHVAR_SHUFFLE_DSC","[Behaviour] shuffle <br />Shuffle playback of playlist items. <br />The player will randomly pick the items.");
define("_WEBPHOTO_FLASHVAR_VOLUME_DSC","[Behaviour] volume <br />This sets the smoothing of videos, so you wonÅft see blocks when a video is upscaled.  <br />Set this to false to disable the feature and get performance improvements with old computers / big files. ");

// player
define("_WEBPHOTO_FLASHVAR_IMAGE_DSC","[Playlist] image <br />Location of a preview (poster) image; shown in display before the video starts.");

define("_WEBPHOTO_FLASHVAR_AUTOSTART_DSC","[Behaviour] autostart <br />Set this to true to automatically start the player on load.");
define("_WEBPHOTO_FLASHVAR_BUFFERLENGTH_DSC","[Behaviour] bufferlength <br />Number of seconds of the file that has to be loaded before the player starts playback. <br />Set this to a low value to enable instant-start (good for fast connections) and to a high value to get less mid-stream buffering (good for slow connections).");
define("_WEBPHOTO_FLASHVAR_SMOOTHING_DSC","[Behaviour] smoothing <br />This sets the smoothing of videos, so you wonÅft see blocks when a video is upscaled.  <br />Set this to false to disable the feature and get performance improvements with old computers / big files. ");

// imagetotator
define("_WEBPHOTO_FLASHVAR_LOGO_DSC","[Appearance] logo <br />Set this to an image that can be put as a watermark logo in the top right corner of the display.  <br />Transparent PNG files give the best results");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_DSC","[Appearance] overstretch <br />Sets how to stretch images to make them fit the display. <br />The default stretches to fit the display.  <br />Set this to true to stretch them proportionally to fill the display, fit to stretch them disproportionally and none to keep original dimensions. ");
define("_WEBPHOTO_FLASHVAR_SHOWICONS_DSC","[Appearance] showicons <br />Set this to false to hide the activity icon and play button in the middle of the display. ");
define("_WEBPHOTO_FLASHVAR_SHOWNAVIGATION_DSC","[Appearance] shownavigation <br />ÉRSet this to false to completely hide the navigation bar. ");
define("_WEBPHOTO_FLASHVAR_TRANSITION_DSC","[Appearance] transition <br />Sets the transition to use between images.  <br />The default, random, randomly pick a transition. To restrict to a certain transition, use these values: fade, bgfade, blocks, bubbles, circles, flash, fluids, lines or slowfade. ");
define("_WEBPHOTO_FLASHVAR_USEFULLSCREEN_DSC","[Appearance] usefullscreen <br />Set this to false to hide the fullscreen button and disable fullscreen. ");

define("_WEBPHOTO_FLASHVAR_AUDIO_DSC","[Behaviour] audio <br />Set this to false to completely hide the navigation bar. ");
define("_WEBPHOTO_FLASHVAR_LINKFROMDISPLAY_DSC","[Behaviour] linkfromdisplay <br />Set this to true to make a click on the display result in a jump to the webpage assigned to the link playlist metadata. ");
define("_WEBPHOTO_FLASHVAR_LINKTARGET_DSC","[Behaviour] linktarget <br />Set this to the frame you want hyperlinks to open in. Set it to _blank to open links in a new window or _top to open in the top frame. ");
define("_WEBPHOTO_FLASHVAR_REPEAT_DSC","[Behaviour] repeat <br />Set this to true to automatically repeat playback of all images.  <br />Set this to list to playback an entire playlist once. ");
define("_WEBPHOTO_FLASHVAR_ROTATETIME_DSC","[Behaviour] rotatetime <br />Sets the duration in seconds an image is shown before transitioning again.");

// === new definition ===
define("_WEBPHOTO_FLASHVARS_ADD","Add Flash Player's Options");

// --- flashvar table ---
// Playlist Properties
define("_WEBPHOTO_FLASHVAR_PLAYLISTFILE","Playlist File");
define("_WEBPHOTO_FLASHVAR_START","Start Time");
define("_WEBPHOTO_FLASHVAR_DURATION","Duration");
define("_WEBPHOTO_FLASHVAR_MEDIAID","Media ID");
define("_WEBPHOTO_FLASHVAR_PROVIDER","Provider");
define("_WEBPHOTO_FLASHVAR_STREAMER","Streamer");
define("_WEBPHOTO_FLASHVAR_NETSTREAMBASEPATH","Netstream Base Path");

// Layout
define("_WEBPHOTO_FLASHVAR_DOCK","Dock");
define("_WEBPHOTO_FLASHVAR_ICONS","Icons");
define("_WEBPHOTO_FLASHVAR_SKIN","Skin");
define("_WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION","Controlbar Pposition");
define("_WEBPHOTO_FLASHVAR_CONTROLBAR_IDLEHIDE","Controlbar Iidle Hide");
define("_WEBPHOTO_FLASHVAR_DISPLAY_SHOWMUTE","Display Show Mute ");
define("_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION","Playlist Position ");
define("_WEBPHOTO_FLASHVAR_PLAYLIST_SIZE","Playlist Size");

// Behavior
define("_WEBPHOTO_FLASHVAR_PLAYER_REPEAT","Repeat");
define("_WEBPHOTO_FLASHVAR_ITEM","Item");
define("_WEBPHOTO_FLASHVAR_MUTE","Mute");
define("_WEBPHOTO_FLASHVAR_PLAYERREADY","Player Ready");
define("_WEBPHOTO_FLASHVAR_PLUGINS","Plugins");
define("_WEBPHOTO_FLASHVAR_STRETCHING","Stretching");

// Logo
define("_WEBPHOTO_FLASHVAR_LOGO_FILE","Logo File");
define("_WEBPHOTO_FLASHVAR_LOGO_LINK","Logo Link");
define("_WEBPHOTO_FLASHVAR_LOGO_LINKTARGET","Logo Link Target");
define("_WEBPHOTO_FLASHVAR_LOGO_HIDE","Logo Hide");
define("_WEBPHOTO_FLASHVAR_LOGO_MARGIN","Logo Margin");
define("_WEBPHOTO_FLASHVAR_LOGO_POSITION","Logo Position");
define("_WEBPHOTO_FLASHVAR_LOGO_TIMEOUT","Logo Timeout");
define("_WEBPHOTO_FLASHVAR_LOGO_OVER","Logo Over");
define("_WEBPHOTO_FLASHVAR_LOGO_OUT","Logo Out");

// --- description ---
define("_WEBPHOTO_FLASHVAR_START_DSC","[Playlist] start <br />Position in seconds where playback should start. <br />This option works for HTTP Pseudostreaming, RTMP Streaming and the MP3 and YouTube files. <br />It does not work for regular videos.)");
define("_WEBPHOTO_FLASHVAR_DURATION_DSC","[Playlist] duration <br />Duration of the file in seconds. <br />Set this to present the duration in the controlbar before the video starts. <br />It can also be set to a shorter value than the actual file duration. <br />The player will restrict playback to only that section.");
define("_WEBPHOTO_FLASHVAR_PLAYLISTFILE_DSC","[Playlist] playlistfile <br />Location of an XML playlist to load into the player.");
define("_WEBPHOTO_FLASHVAR_MEDIAID_DSC","[Playlist] mediaid <br />Unique string (e.g. 9Ks83JsK) used to identify this media file. <br />Is used by certain plugins, e.g. for the targeting of advertisements. <br />The player itself doesnÅft use this ID anywhere.");
define("_WEBPHOTO_FLASHVAR_PROVIDER_DSC","[Playlist] provider <br />Set this flashvar to tell the player in which format (regular/streaming) the player is. <br />By default, the provider is detected by the player based upon the file extension. <br />If there is no suiteable extension, it can be manually set. <br />The following provider strings are supported:");
define("_WEBPHOTO_FLASHVAR_STREAMER_DSC","[Playlist] streamer <br />Location of an RTMP or HTTP server instance to use for streaming. <br />Can be an RTMP application or external PHP/ASP file. <br />See RTMP Streaming and HTTP Pseudostreaming.");
define("_WEBPHOTO_FLASHVAR_NETSTREAMBASEPATH_DSC","[Playlist] netstreambasepath  <br />The netstreambasepath should be set to a URL from which relative paths will be calculated for video files.  <br />Introduced in JW Player 5.4, this configuration parameter directs the video and http media providers to request video files relative to the specified netstreambasepath rather than relative to the player SWF.  <br />This will likely cause issues for publishers using the JW Embedder with relative file paths.");

define("_WEBPHOTO_FLASHVAR_DOCK_DSC","[Layout] dock <br />set this to false to show plugin buttons in controlbar. <br />By default (true), plugin buttons are shown in the display.");
define("_WEBPHOTO_FLASHVAR_ICONS_DSC","[Layout] icons <br />set this to false to hide the play button and buffering icons in the display.");
define("_WEBPHOTO_FLASHVAR_SKIN_DSC","[Layout] skin <br />Location of a skin file, containing graphics which change the look of the player. <br />There are two types of skins available:");
define("_WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION_DSC","[Layout] controlbar.position <br />Position of the controlbar. <br />Can be set to bottom, top, over and none.");
define("_WEBPHOTO_FLASHVAR_CONTROLBAR_IDLEHIDE_DSC","[Layout] controlbar.idlehide <br />If controlbar.position is set to over, this option determines whether the controlbar stays hidden when the player is paused or stopped.");
define("_WEBPHOTO_FLASHVAR_DISPLAY_SHOWMUTE_DSC","[Layout] display.showmute <br />Shows a mute icon in the player's display window while the player is playing. <br />Disabled by default.");
define("_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_DSC","[Layout] playlist.position <br />Position of the playlist. <br />Can be set to bottom, top, right, left, over or none.");
define("_WEBPHOTO_FLASHVAR_PLAYLIST_SIZE_DSC","[Layout]  playlist.size <br />When the playlist is positioned below the display, this option can be used to change its height.  <br />When the playlist lives left or right of the display, this option represents its width. In the other cases, this option isn't needed.");

define("_WEBPHOTO_FLASHVAR_PLAYER_REPEAT_DSC","[Behaviour] repeat <br />What to do when the mediafile has ended. <br />none Color of an icon or text when you rollover it with the mouse. Is black by default. <br />list: play each file in the playlist once, stop at the end. <br />always: continously play the file (or all files in the playlist). <br />single: continously repeat the current file in the playlist.");
define("_WEBPHOTO_FLASHVAR_ITEM_DSC","[Behaviour] item <br />Playlist item that should start to play.  <br />Use this to start the player with a specific item instead of with the first item.");
define("_WEBPHOTO_FLASHVAR_MUTE_DSC","[Behaviour] mute <br />Mute the sounds on startup.  <br />Is saved in a cookie.");
define("_WEBPHOTO_FLASHVAR_PLAYERREADY_DSC","[Behaviour] playerready <br />By default, the player calls a playerReady() JavaScript function when it is initialized.  <br />This option is used to let the player call a different function after itÅfs initialized (e.g. registerPlayer()).");
define("_WEBPHOTO_FLASHVAR_PLUGINS_DSC","[Behaviour] plugins <br />A powerful feature, this is a comma-separated list of plugins to load (e.g. hd,viral). <br />Plugins are separate JavaScript or SWF files that extend the functionality of the player, e.g. with advertising, analytics or viral sharing features. <br />Visit our addons repository to browse the long list of available plugins.");
define("_WEBPHOTO_FLASHVAR_STRETCHING_DSC","[Behaviour] stretching <br />Defines how to resize the poster image and video to fit the display.");

define("_WEBPHOTO_FLASHVAR_LOGO_FILE_DSC","[Logo] logo.file <br />");
define("_WEBPHOTO_FLASHVAR_LOGO_LINK_DSC","[Logo] logo.link <br />HTTP link to jump to when the watermark image is clicked. <br />If it is not set, a click on the watermark does nothing.");
define("_WEBPHOTO_FLASHVAR_LOGO_LINKTARGET_DSC","[Logo] logo.linktarget <br />Link target for logo click. <br />Can be _self, _blank, _parent, _top or a named frame.");
define("_WEBPHOTO_FLASHVAR_LOGO_HIDE_DSC","[Logo] logo.hide <br />By default, the logo will automatically show when the player buffers and hide 3 seconds later. <br />When this option is set false, the logo will stay visible all the time.");
define("_WEBPHOTO_FLASHVAR_LOGO_MARGIN_DSC","[Logo] logo.margin <br />The distance of the logo, in pixels from the sides of the player.");
define("_WEBPHOTO_FLASHVAR_LOGO_POSITION_DSC","[Logo] logo.position <br />This sets the corner in which to display the watermark. <br />It can be one of the following:");
define("_WEBPHOTO_FLASHVAR_LOGO_TIMEOUT_DSC","[Logo] logo.timeout <br />When logo.hide is set to true, this option sets the number of seconds the logo is visible after it appears.");
define("_WEBPHOTO_FLASHVAR_LOGO_OVER_DSC","[Logo] logo.over <br />The alpha transparency of the logo on mouseover. <br />Can be a decimal number from 0 to 1.");
define("_WEBPHOTO_FLASHVAR_LOGO_OUT_DSC","[Logo] logo.out <br />The default alpha transparency of the logo when not moused over.<br /> Can be a decimal number from 0 to 1.");

// --- options ---
define("_WEBPHOTO_FLASHVAR_PLAYER_REPEAT_NONE","none");
define("_WEBPHOTO_FLASHVAR_PLAYER_REPEAT_LIST","list");
define("_WEBPHOTO_FLASHVAR_PLAYER_REPEAT_ALWAYS","always");
define("_WEBPHOTO_FLASHVAR_PLAYER_REPEAT_SINGLE","single");

define("_WEBPHOTO_FLASHVAR_STRETCHING_NONE",    "none");
define("_WEBPHOTO_FLASHVAR_STRETCHING_EXACTFIT","exactfit");
define("_WEBPHOTO_FLASHVAR_STRETCHING_UNIFORM", "uniform");
define("_WEBPHOTO_FLASHVAR_STRETCHING_FILL",    "fill");

define("_WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION_BOTTOM", "bottom");
define("_WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION_TOP",    "top");
define("_WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION_OVER",   "over");
define("_WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION_NONE",   "none");

define("_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_BOTTOM", "bottom");
define("_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_TOP",    "top");
define("_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_RIGHT",  "right");
define("_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_LEFT",   "left");
define("_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_OVER",   "over");
define("_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_NONE",   "none");

define("_WEBPHOTO_FLASHVAR_LOGO_POSITION_BOTTOM_LEFT",  "bottom-left");
define("_WEBPHOTO_FLASHVAR_LOGO_POSITION_BOTTOM_RIGHT", "bottom-right");
define("_WEBPHOTO_FLASHVAR_LOGO_POSITION_TOP_LEFT",     "top-left");
define("_WEBPHOTO_FLASHVAR_LOGO_POSITION_TOP_RIGHT",    "top-right");

//---------------------------------------------------------
// v2.60
//---------------------------------------------------------
// cat handler
define("_WEBPHOTO_CAT_TIMELINE_MODE" ,   "Timeline mode" ) ;
define("_WEBPHOTO_CAT_TIMELINE_SCALE" ,  "Timeline scale" ) ;

// === define end ===
}

?>