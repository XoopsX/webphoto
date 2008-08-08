<?php
// $Id: main.php,v 1.4 2008/08/08 04:36:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

// === define begin ===
if( !defined("_MB_WEBPHOTO_LANG_LOADED") ) 
{

define("_MB_WEBPHOTO_LANG_LOADED" , 1 ) ;

//=========================================================
// base on myalbum
//=========================================================

define("_WEBPHOTO_CATEGORY","Category");
define("_WEBPHOTO_SUBMITTER","Submitter");
define("_WEBPHOTO_NOMATCH_PHOTO","No photo matches your request");

define("_WEBPHOTO_ICON_NEW","New");
define("_WEBPHOTO_ICON_UPDATE","Updated");
define("_WEBPHOTO_ICON_POPULAR","Popluar");
define("_WEBPHOTO_ICON_LASTUPDATE","Last Update");
define("_WEBPHOTO_ICON_HITS","Hits");
define("_WEBPHOTO_ICON_COMMENTS","Comments");

define("_WEBPHOTO_SORT_IDA","Record Number (Smaller to Bigger)");
define("_WEBPHOTO_SORT_IDD","Record Number (Smaller is latter)");
define("_WEBPHOTO_SORT_HITSA","Popularity (Least to Most Hits)");
define("_WEBPHOTO_SORT_HITSD","Popularity (Most to Least Hits)");
define("_WEBPHOTO_SORT_TITLEA","Title (A to Z)");
define("_WEBPHOTO_SORT_TITLED","Title (Z to A))");
define("_WEBPHOTO_SORT_DATEA","Updated Date (Old Photos Listed First)");
define("_WEBPHOTO_SORT_DATED","Updated Date (New Photos Listed First)");
define("_WEBPHOTO_SORT_RATINGA","Rating (Lowest Score to Highest Score)");
define("_WEBPHOTO_SORT_RATINGD","Rating (Highest Score to Lowest Score)");
define("_WEBPHOTO_SORT_RANDOM","Random");

define("_WEBPHOTO_SORT_SORTBY","Sort by:");
define("_WEBPHOTO_SORT_TITLE","Title");
define("_WEBPHOTO_SORT_DATE","Updated Date");
define("_WEBPHOTO_SORT_HITS","Popularity");
define("_WEBPHOTO_SORT_RATING","Rating");
define("_WEBPHOTO_SORT_S_CURSORTEDBY","Photos currently sorted by: %s");

define("_WEBPHOTO_NAVI_PREVIOUS","Previous");
define("_WEBPHOTO_NAVI_NEXT","Next");
define("_WEBPHOTO_S_NAVINFO" , "Photo No. %s - %s (out of %s photos hit)" ) ;
define("_WEBPHOTO_S_THEREARE","There are <b>%s</b> Images in our Database.");
define("_WEBPHOTO_S_MOREPHOTOS","More Photos from %s");
define("_WEBPHOTO_ONEVOTE","1 vote");
define("_WEBPHOTO_S_NUMVOTES","%s votes");
define("_WEBPHOTO_ONEPOST","1 post");
define("_WEBPHOTO_S_NUMPOSTS","%s posts");
define("_WEBPHOTO_VOTETHIS","Vote this");
define("_WEBPHOTO_TELLAFRIEND","Tell a friend");
define("_WEBPHOTO_SUBJECT4TAF","A photo for you");


//---------------------------------------------------------
// submit
//---------------------------------------------------------
// only "Y/m/d" , "d M Y" , "M d Y" can be interpreted
define("_WEBPHOTO_DTFMT_YMDHI" , "d M Y H:i" ) ;

define("_WEBPHOTO_TITLE_ADDPHOTO","Add Photo");
define("_WEBPHOTO_TITLE_PHOTOUPLOAD","Photo Upload");
define("_WEBPHOTO_CAP_MAXPIXEL","Max pixel size");
define("_WEBPHOTO_CAP_MAXSIZE","Max file size (byte)");
define("_WEBPHOTO_CAP_VALIDPHOTO","Valid");
define("_WEBPHOTO_DSC_TITLE_BLANK","Leave title blank to use file names as title");

define("_WEBPHOTO_RADIO_ROTATETITLE" , "Image rotation" ) ;
define("_WEBPHOTO_RADIO_ROTATE0" , "no turn" ) ;
define("_WEBPHOTO_RADIO_ROTATE90" , "turn right" ) ;
define("_WEBPHOTO_RADIO_ROTATE180" , "turn 180 degree" ) ;
define("_WEBPHOTO_RADIO_ROTATE270" , "turn left" ) ;

define("_WEBPHOTO_SUBMIT_RECEIVED","We received your Photo. Thank you!");
define("_WEBPHOTO_SUBMIT_ALLPENDING","All photos are posted pending verification.");

define("_WEBPHOTO_ERR_MUSTREGFIRST","Sorry, you don't have permission to perform this action.<br />Please register or login first!");
define("_WEBPHOTO_ERR_MUSTADDCATFIRST","Sorry, there are no categories to add to yet.<br />Please create a category first!");
define("_WEBPHOTO_ERR_NOIMAGESPECIFIED","No photo was uploaded");
define("_WEBPHOTO_ERR_FILE","Photos are too big or there is a problem with the configuration");
define("_WEBPHOTO_ERR_FILEREAD","Photos are not readable.");
define("_WEBPHOTO_ERR_TITLE","You must enter 'Title' ");


//---------------------------------------------------------
// edit
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_EDIT","Edit Photo");
define("_WEBPHOTO_TITLE_PHOTODEL","Delete Photo");
define("_WEBPHOTO_CONFIRM_PHOTODEL","Delete photo?");
define("_WEBPHOTO_DBUPDATED","Database Updated Successfully!");
define("_WEBPHOTO_DELETED","Deleted!");


//---------------------------------------------------------
// rate
//---------------------------------------------------------
define("_WEBPHOTO_RATE_VOTEONCE","Please do not vote for the same resource more than once.");
define("_WEBPHOTO_RATE_RATINGSCALE","The scale is 1 - 10, with 1 being poor and 10 being excellent.");
define("_WEBPHOTO_RATE_BEOBJECTIVE","Please be objective, if everyone receives a 1 or a 10, the ratings aren't very useful.");
define("_WEBPHOTO_RATE_DONOTVOTE","Do not vote for your own resource.");
define("_WEBPHOTO_RATE_IT","Rate It!");
define("_WEBPHOTO_RATE_VOTEAPPRE","Your vote is appreciated.");
define("_WEBPHOTO_RATE_S_THANKURATE","Thank you for taking the time to rate a photo here at %s.");

define("_WEBPHOTO_ERR_NORATING","No rating selected.");
define("_WEBPHOTO_ERR_CANTVOTEOWN","You cannot vote on the resource you submitted.<br />All votes are logged and reviewed.");
define("_WEBPHOTO_ERR_VOTEONCE","Vote for the selected resource only once.<br />All votes are logged and reviewed.");


//---------------------------------------------------------
// movo to admin.php
//---------------------------------------------------------
// New in myAlbum-P

// only "Y/m/d" , "d M Y" , "M d Y" can be interpreted
//define( "_WEBPHOTO_DTFMT_YMDHI" , "d M Y H:i" ) ;

//define( "_WEBPHOTO_NEXT_BUTTON" , "Next" ) ;
//define( "_WEBPHOTO_REDOLOOPDONE" , "Done." ) ;

//define( "_WEBPHOTO_BTN_SELECTALL" , "Select All" ) ;
//define( "_WEBPHOTO_BTN_SELECTNONE" , "Select None" ) ;
//define( "_WEBPHOTO_BTN_SELECTRVS" , "Select Reverse" ) ;
//define( "_WEBPHOTO_FMT_PHOTONUM" , "%s every page" ) ;

//define( "_WEBPHOTO_AM_ADMISSION" , "Admit Photos" ) ;
//define( "_WEBPHOTO_AM_ADMITTING" , "Admitted photo(s)" ) ;
//define( "_WEBPHOTO_AM_LABEL_ADMIT" , "Admit the photos you checked" ) ;
//define( "_WEBPHOTO_AM_BUTTON_ADMIT" , "Admit" ) ;
//define( "_WEBPHOTO_AM_BUTTON_EXTRACT" , "extract" ) ;

//define( "_WEBPHOTO_AM_PHOTOMANAGER" , "Photo Manager" ) ;
//define( "_WEBPHOTO_AM_PHOTONAVINFO" , "Photo No. %s-%s (out of %s photos hit)" ) ;
//define( "_WEBPHOTO_AM_LABEL_REMOVE" , "Remove the photos checked" ) ;
//define( "_WEBPHOTO_AM_BUTTON_REMOVE" , "Remove!" ) ;
//define( "_WEBPHOTO_AM_JS_REMOVECONFIRM" , "Remove OK?" ) ;
//define( "_WEBPHOTO_AM_LABEL_MOVE" , "Change category of the checked photos" ) ;
//define( "_WEBPHOTO_AM_BUTTON_MOVE" , "Move" ) ;
//define( "_WEBPHOTO_AM_BUTTON_UPDATE" , "Modify" ) ;
//define( "_WEBPHOTO_AM_DEADLINKMAINPHOTO" , "The main image don't exist" ) ;


//---------------------------------------------------------
// not use
//---------------------------------------------------------
// New MyAlbum 1.0.1 (and 1.2.0)
//define("_WEBPHOTO_MOREPHOTOS","More Photos from %s");
//define("_WEBPHOTO_REDOTHUMBS","Redo Thumbnails (<a href='redothumbs.php'>re-start</a>)");
//define("_WEBPHOTO_REDOTHUMBS2","Rebuild Thumbnails");
//define("_WEBPHOTO_REDOTHUMBSINFO","Too large a number may lead to server time out.");
//define("_WEBPHOTO_REDOTHUMBSNUMBER","Number of thumbs at a time");
//define("_WEBPHOTO_REDOING","Redoing: ");
//define("_WEBPHOTO_BACK","Return");
//define("_WEBPHOTO_ADDPHOTO","Add Photo");


//---------------------------------------------------------
// movo to admin.php
//---------------------------------------------------------
// New MyAlbum 1.0.0
//define("_WEBPHOTO_PHOTOBATCHUPLOAD","Register photos uploaded to the server already");
//define("_WEBPHOTO_PHOTOUPLOAD","Photo Upload");
//define("_WEBPHOTO_PHOTOEDITUPLOAD","Photo Edit and Re-upload");
//define("_WEBPHOTO_MAXPIXEL","Max pixel size");
//define("_WEBPHOTO_MAXSIZE","Max file size(byte)");
//define("_WEBPHOTO_PHOTOTITLE","Title");
//define("_WEBPHOTO_PHOTOPATH","Path");
//define("_WEBPHOTO_TEXT_DIRECTORY","Directory");
//define("_WEBPHOTO_DESC_PHOTOPATH","Type the full path of the directory including photos to be registered");
//define("_WEBPHOTO_MES_INVALIDDIRECTORY","Invalid directory is specified.");
//define("_WEBPHOTO_MES_BATCHDONE","%s photo(s) have been registered.");
//define("_WEBPHOTO_MES_BATCHNONE","No photo was detected in the directory.");
//define("_WEBPHOTO_PHOTODESC","Description");
//define("_WEBPHOTO_PHOTOCAT","Category");
//define("_WEBPHOTO_SELECTFILE","Select photo");
//define("_WEBPHOTO_NOIMAGESPECIFIED","Error: No photo was uploaded");
//define("_WEBPHOTO_FILEERROR","Error: Photos are too big or there is a problem with the configuration");
//define("_WEBPHOTO_FILEREADERROR","Error: Photos are not readable.");

//define("_WEBPHOTO_BATCHBLANK","Leave title blank to use file names as title");
//define("_WEBPHOTO_DELETEPHOTO","Delete?");
//define("_WEBPHOTO_VALIDPHOTO","Valid");
//define("_WEBPHOTO_PHOTODEL","Delete photo?");
//define("_WEBPHOTO_DELETINGPHOTO","Deleting photo");
//define("_WEBPHOTO_MOVINGPHOTO","Moving photo");

//define("_WEBPHOTO_STORETIMESTAMP","Don't touch timestamp");

//define("_WEBPHOTO_POSTERC","Poster: ");
//define("_WEBPHOTO_DATEC","Date: ");
//define("_WEBPHOTO_EDITNOTALLOWED","You're not allowed to edit this comment!");
//define("_WEBPHOTO_ANONNOTALLOWED","Anonymous users are not allowed to post.");
//define("_WEBPHOTO_THANKSFORPOST","Thanks for your submission!");
//define("_WEBPHOTO_DELNOTALLOWED","You're not allowed to delete this comment!");
//define("_WEBPHOTO_GOBACK","Go Back");
//define("_WEBPHOTO_AREYOUSURE","Are you sure you want to delete this comment and all comments under it?");
//define("_WEBPHOTO_COMMENTSDEL","Comment(s) Deleted Successfully!");

// End New


//---------------------------------------------------------
// not use
//---------------------------------------------------------
//define("_WEBPHOTO_THANKSFORINFO","Thank you for the information. We'll look into your request shortly.");
//define("_WEBPHOTO_BACKTOTOP","Back to Photo Top");
//define("_WEBPHOTO_THANKSFORHELP","Thank you for helping to maintain this directory's integrity.");
//define("_WEBPHOTO_FORSECURITY","For security reasons your user name and IP address will also be temporarily recorded.");

//define("_WEBPHOTO_MATCH","Match");
//define("_WEBPHOTO_ALL","ALL");
//define("_WEBPHOTO_ANY","ANY");
//define("_WEBPHOTO_NAME","Name");
//define("_WEBPHOTO_DESCRIPTION","Description");

//define("_WEBPHOTO_MAIN","Main");
//define("_WEBPHOTO_NEW","New");
//define("_WEBPHOTO_UPDATED","Updated");
//define("_WEBPHOTO_POPULAR","Popular");
//define("_WEBPHOTO_TOPRATED","Top Rated");

//define("_WEBPHOTO_POPULARITYLTOM","Popularity (Least to Most Hits)");
//define("_WEBPHOTO_POPULARITYMTOL","Popularity (Most to Least Hits)");
//define("_WEBPHOTO_TITLEATOZ","Title (A to Z)");
//define("_WEBPHOTO_TITLEZTOA","Title (Z to A)");
//define("_WEBPHOTO_DATEOLD","Date (Old Photos Listed First)");
//define("_WEBPHOTO_DATENEW","Date (New Photos Listed First)");
//define("_WEBPHOTO_RATINGLTOH","Rating (Lowest Score to Highest Score)");
//define("_WEBPHOTO_RATINGHTOL","Rating (Highest Score to Lowest Score)");
//define("_WEBPHOTO_LIDASC","Record Number (Smaller to Bigger)");
//define("_WEBPHOTO_LIDDESC","Record Number (Smaller is latter)");

//define("_WEBPHOTO_NOSHOTS","No Screenshots Available");
//define("_WEBPHOTO_EDITTHISPHOTO","Edit This Photo");

//define("_WEBPHOTO_DESCRIPTIONC","Description");
//define("_WEBPHOTO_EMAILC","Email");
//define("_WEBPHOTO_CATEGORYC","Category");
//define("_WEBPHOTO_SUBCATEGORY","Sub-category");
//define("_WEBPHOTO_LASTUPDATEC","Last Update");

//define("_WEBPHOTO_HITSC","Hits");
//define("_WEBPHOTO_RATINGC","Rating");
//define("_WEBPHOTO_NUMVOTES","%s votes");
//define("_WEBPHOTO_NUMPOSTS","%s posts");
//define("_WEBPHOTO_COMMENTSC","Comments");
//define("_WEBPHOTO_RATETHISPHOTO","Rate it");
//define("_WEBPHOTO_MODIFY","Modify");
//define("_WEBPHOTO_VSCOMMENTS","View/Send Comments");

//define("_WEBPHOTO_DIRECTCATSEL","SELECT A CATEGORY");
//define("_WEBPHOTO_THEREARE","There are <b>%s</b> Images in our Database.");
//define("_WEBPHOTO_LATESTLIST","Latest Listings");

//define("_WEBPHOTO_VOTEAPPRE","Your vote is appreciated.");
//define("_WEBPHOTO_THANKURATE","Thank you for taking the time to rate a photo here at %s.");
//define("_WEBPHOTO_VOTEONCE","Please do not vote for the same resource more than once.");
//define("_WEBPHOTO_RATINGSCALE","The scale is 1 - 10, with 1 being poor and 10 being excellent.");
//define("_WEBPHOTO_BEOBJECTIVE","Please be objective, if everyone receives a 1 or a 10, the ratings aren't very useful.");
//define("_WEBPHOTO_DONOTVOTE","Do not vote for your own resource.");
//define("_WEBPHOTO_RATEIT","Rate It!");

//define("_WEBPHOTO_RECEIVED","We received your Photo. Thank you!");
//define("_WEBPHOTO_ALLPENDING","All photos are posted pending verification.");

//define("_WEBPHOTO_RANK","Rank");
//define("_WEBPHOTO_SUBCATEGORY","Sub-category");
//define("_WEBPHOTO_HITS","Hits");
//define("_WEBPHOTO_RATING","Rating");
//define("_WEBPHOTO_VOTE","Vote");
//define("_WEBPHOTO_TOP10","%s Top 10"); // %s is a photo category title

//define("_WEBPHOTO_SORTBY","Sort by:");
//define("_WEBPHOTO_TITLE","Title");
//define("_WEBPHOTO_DATE","Date");
//define("_WEBPHOTO_POPULARITY","Popularity");
//define("_WEBPHOTO_CURSORTEDBY","Photos currently sorted by: %s");
//define("_WEBPHOTO_FOUNDIN","Found in:");
//define("_WEBPHOTO_PREVIOUS","Previous");
//define("_WEBPHOTO_NEXT","Next");
//define("_WEBPHOTO_NOMATCH","No photo matches your request");

//define("_WEBPHOTO_CATEGORIES","Categories");
//define("_WEBPHOTO_SUBMIT","Submit");
//define("_WEBPHOTO_CANCEL","Cancel");

//define("_WEBPHOTO_MUSTREGFIRST","Sorry, you don't have permission to perform this action.<br>Please register or login first!");
//define("_WEBPHOTO_MUSTADDCATFIRST","Sorry, there are no categories to add to yet.<br>Please create a category first!");
//define("_WEBPHOTO_NORATING","No rating selected.");
//define("_WEBPHOTO_CANTVOTEOWN","You cannot vote on the resource you submitted.<br>All votes are logged and reviewed.");
//define("_WEBPHOTO_VOTEONCE2","Vote for the selected resource only once.<br>All votes are logged and reviewed.");


//---------------------------------------------------------
// move to admin.php
//---------------------------------------------------------
//%%%%%%	Module Name 'MyAlbum' (Admin)	  %%%%%
//define("_WEBPHOTO_PHOTOSWAITING","Photos Waiting for Validation");
//define("_WEBPHOTO_PHOTOMANAGER","Photo Management");
//define("_WEBPHOTO_CATEDIT","Add, Modify, and Delete Categories");
//define("_WEBPHOTO_GROUPPERM_GLOBAL","Global Permissions");
//define("_WEBPHOTO_CHECKCONFIGS","Check Configs & Environment");
//define("_WEBPHOTO_BATCHUPLOAD","Batch Register");
//define("_WEBPHOTO_GENERALSET","Preferences");
//define("_WEBPHOTO_REDOTHUMBS2","Rebuild Thumbnails");

//define("_WEBPHOTO_DELETE","Delete");
//define("_WEBPHOTO_NOSUBMITTED","No New Submitted Photos.");
//define("_WEBPHOTO_ADDMAIN","Add a MAIN Category");
//define("_WEBPHOTO_IMGURL","Image URL (OPTIONAL Image height will be resized to 50): ");
//define("_WEBPHOTO_ADD","Add");
//define("_WEBPHOTO_ADDSUB","Add a SUB-Category");
//define("_WEBPHOTO_IN","in");
//define("_WEBPHOTO_MODCAT","Modify Category");

//define("_WEBPHOTO_MODREQDELETED","Modification Request Deleted.");
//define("_WEBPHOTO_IMGURLMAIN","Image URL (OPTIONAL and Only valid for main categories. Image height will be resized to 50): ");
//define("_WEBPHOTO_PARENT","Parent Category:");
//define("_WEBPHOTO_SAVE","Save Changes");
//define("_WEBPHOTO_CATDELETED","Category Deleted.");
//define("_WEBPHOTO_CATDEL_WARNING","WARNING: Are you sure you want to delete this Category and ALL its Photos and Comments?");
//define("_WEBPHOTO_YES","Yes");
//define("_WEBPHOTO_NO","No");
//define("_WEBPHOTO_NEWCATADDED","New Category Added Successfully!");
//define("_WEBPHOTO_ERROREXIST","ERROR: The Photo you provided is already in the database!");
//define("_WEBPHOTO_ERRORTITLE","ERROR: You need to enter a TITLE!");
//define("_WEBPHOTO_ERRORDESC","ERROR: You need to enter a DESCRIPTION!");
//define("_WEBPHOTO_WEAPPROVED","We approved your link submission to the photo database.");
//define("_WEBPHOTO_THANKSSUBMIT","Thank you for your submission!");
//define("_WEBPHOTO_CONFUPDATED","Configuration Updated Successfully!");


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
define("_WEBPHOTO_CAT_IMG_PATH" , "Relative Path to Image" ) ;
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
define("_WEBPHOTO_GICON_SHADOW_WIDTH" ,  "Shadow Height" ) ;
define("_WEBPHOTO_GICON_SHADOW_HEIGHT" , "Shadow Y Size" ) ;
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

// tag table
define("_WEBPHOTO_TAG_TABLE" , "Tag Table" ) ;
define("_WEBPHOTO_TAG_ID" ,          "Tag ID" ) ;
define("_WEBPHOTO_TAG_TIME_CREATE" , "Create Time" ) ;
define("_WEBPHOTO_TAG_TIME_UPDATE" , "Update Time" ) ;
define("_WEBPHOTO_TAG_NAME" ,   "Tag Name" ) ;

// photo-to-tag table
define("_WEBPHOTO_P2T_TABLE" , "Photo Tag Relation Table" ) ;
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
define("_WEBPHOTO_TITLE_MYPHOTO","My Photos");
define("_WEBPHOTO_TITLE_RANDOM","Radom Photos");
define("_WEBPHOTO_TITLE_HELP","Help");
define("_WEBPHOTO_TITLE_CATEGORY_LIST", "Category List");
define("_WEBPHOTO_TITLE_TAG_LIST",  "Tag List");
define("_WEBPHOTO_TITLE_TAGS",  "Tag");
define("_WEBPHOTO_TITLE_USER_LIST", "Submitter List");
define("_WEBPHOTO_TITLE_DATE_LIST", "Photo Date list");
define("_WEBPHOTO_TITLE_PLACE_LIST","Photo Place List");
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
define("_WEBPHOTO_DSC_TAG_DIVID", "divid the comma(,) when set two or more");
define("_WEBPHOTO_DSC_TAG_EDITABLE", "You can edit only the tags which you posted");

//---------------------------------------------------------
// submit form
//---------------------------------------------------------
define("_WEBPHOTO_CAP_ALLOWED_EXTS", "Allowed Extentions");
define("_WEBPHOTO_CAP_PHOTO_SELECT","Select the main image");
define("_WEBPHOTO_CAP_THUMB_SELECT", "Select the thumb image");
define("_WEBPHOTO_DSC_THUMB_SELECT", "create from the main image, when not select");
define("_WEBPHOTO_DSC_SET_DATETIME",  "Set photo datetime");
define("_WEBPHOTO_DSC_SET_TIME_UPDATE", "Set update time");
define("_WEBPHOTO_DSC_PIXCEL_RESIZE", "Resize automatically if bigger than this size");
define("_WEBPHOTO_DSC_PIXCEL_REJECT", "Cannot upload if bigger than this size");
define("_WEBPHOTO_BUTTON_CLEAR", "Clear");
define("_WEBPHOTO_SUBMIT_RESIZED", "Resized, because photo is too big ");

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
This is the viewer of photos in the web site<br /><br />
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
This shows photos from the internet with the slide show<br /><br />
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
define("_WEBPHOTO_RANDOM_MORE","More Photos at random");
define("_WEBPHOTO_USAGE_PHOTO","Popup the big photo, wehen click the thumbnail image");
define("_WEBPHOTO_USAGE_TITLE","Move to the photo page, wehen click the photo title");
define("_WEBPHOTO_DATE_NOT_SET","Not set Photo Date");
define("_WEBPHOTO_PLACE_NOT_SET","Not Set Photo Place");
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
define("_WEBPHOTO_CAP_REDO_FLASH","Cretae Flash Video");
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
define("_WEBPHOTO_USER_UID" , "Uesr ID" ) ;
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
define("_WEBPHOTO_SUBTITLE_MAIL_PHOTO" ,  "Submiting the photos attached to mails" ) ;
define("_WEBPHOTO_TEXT_MAIL_RETRIEVE" ,  "Access mail server" ) ;
define("_WEBPHOTO_TEXT_MAIL_ACCESS_TIME" ,  "In access limitation" ) ;
define("_WEBPHOTO_TEXT_MAIL_RETRY"  ,  "Access 1 minute later" ) ;
define("_WEBPHOTO_TEXT_MAIL_NOT_RETRIEVE" ,  "Cannot retrieve mail.<br />Probably temporary communication failure.<br />Please retry after a while" ) ;
define("_WEBPHOTO_TEXT_MAIL_NO_NEW" ,  "There no new mail" ) ;
define("_WEBPHOTO_TEXT_MAIL_RETRIEVED_FMT" ,  "Retrieved %s mails" ) ;
define("_WEBPHOTO_TEXT_MAIL_NO_VALID" ,  "There no valid mail" ) ;
define("_WEBPHOTO_TEXT_MAIL_SUBMITED_FMT" ,  "Submited %s photos" ) ;
define("_WEBPHOTO_GOTO_INDEX" ,  "Goto the module top page" ) ;

// file
define("_WEBPHOTO_TITLE_FILE" , "Add Photo from File" ) ;
define("_WEBPHOTO_ERR_EMPTY_FILE" , "You must select the file" ) ;
define("_WEBPHOTO_ERR_EMPTY_CAT" , "You must select the category" ) ;
define("_WEBPHOTO_ERR_INVALID_CAT" , "Invalid category" ) ;
define("_WEBPHOTO_ERR_CREATE_PHOTO" , "Cannot create photo" ) ;
define("_WEBPHOTO_ERR_CREATE_THUMB" , "Cannot create thumb image" ) ;

// help
define("_WEBPHOTO_HELP_MUST_LOGIN","Please login, if you want to read more detail");
define("_WEBPHOTO_HELP_NOT_PERM", "You have no permission. Please contact the webmaster");
define("_WEBPHOTO_HELP_MAIL_TITLE", "Mobile Mail");
define("_WEBPHOTO_HELP_MAIL_DSC", "You can post the photo and video by email from the mobile phone");
define("_WEBPHOTO_HELP_MAIL_TEXT_FMT", '
<b>Prepare</b><br />
Register your mail address of mobile phone<br />
<a href="{MODULE_URL}/index.php?fct=mail_register" target="_blank">Register Mail Addrtess</a><br /><br />
<b>Post photo</b><br />
Send mail to the fllowing address with attaching photo file.<br />
<a href="mailto:{MAIL_ADDR}">{MAIL_ADDR}</a> {MAIL_GUEST} <br /><br />
<b>Rotation for photo</b><br />
You can turn the photo right or left, since you enter the end of "Subject" as following<br />
 R@ : turn right <br />
 L@ : turn left <br /><br />
<b>Retrive mail and submit photo</b><br />
click <a href="{MODULE_URL}/index.php?fct=mail_retrieve" target="_blank">Retrive Mail</a>, after few seconds sent mail.<br />
Webphoto retrive the mail which you sent, submit and show the attached photo<br />' );
define("_WEBPHOTO_HELP_MAIL_GUEST", "This is sample. You can look the REAL mail address, if you have the permission.");

define("_WEBPHOTO_HELP_FILE_TITLE", "Post by FTP");
define("_WEBPHOTO_HELP_FILE_DSC", "You can post the big size photo and video, when you upload the file by FTP");
define("_WEBPHOTO_HELP_FILE_TEXT_FMT", '
<b>Post photo</b><br />
(1) Upload the file in FTP server<br />
(2) Click <a href="{MODULE_URL}/index.php?fct=submit_file" target="_blank">Add Photo from File</a><br />
(3) Select the uploaded file and post' );

// mail check
// for Japanese
define("_WEBPHOTO_MAIL_DENY_TITLE_PREG", "" ) ;
define("_WEBPHOTO_MAIL_AD_WORD_1", "" ) ;
define("_WEBPHOTO_MAIL_AD_WORD_2", "" ) ;

// === define end ===
}

?>