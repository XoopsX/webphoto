<?php
// $Id: admin.php,v 1.24 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// Photo -> Photo Video Media
//---------------------------------------------------------

// === define begin ===
if( !defined("_AM_WEBPHOTO_LANG_LOADED") ) 
{

define("_AM_WEBPHOTO_LANG_LOADED" , 1 ) ;

//=========================================================
// base on myalbum
//=========================================================

// menu
define('_AM_WEBPHOTO_MYMENU_TPLSADMIN','Templates');
define('_AM_WEBPHOTO_MYMENU_BLOCKSADMIN','Blocks/Permissions');

//define('_AM_WEBPHOTO_MYMENU_MYPREFERENCES','Preferences');

// add for webphoto
define("_AM_WEBPHOTO_MYMENU_GOTO_MODULE" , "Goto Module" ) ;


// Index (Categories)
//define( "_AM_WEBPHOTO_H3_FMT_CATEGORIES" , "Categories Manager (%s)" ) ;
//define( "_AM_WEBPHOTO_CAT_TH_TITLE" , "Name" ) ;

define( "_AM_WEBPHOTO_CAT_TH_PHOTOS" , "Images" ) ;
define( "_AM_WEBPHOTO_CAT_TH_OPERATION" , "Operation" ) ;
define( "_AM_WEBPHOTO_CAT_TH_IMAGE" , "Banner" ) ;
define( "_AM_WEBPHOTO_CAT_TH_PARENT" , "Parent" ) ;

//define( "_AM_WEBPHOTO_CAT_TH_IMGURL" , "URL of Banner" ) ;

define( "_AM_WEBPHOTO_CAT_MENU_NEW" , "Creating a category" ) ;
define( "_AM_WEBPHOTO_CAT_MENU_EDIT" , "Editing a category" ) ;
define( "_AM_WEBPHOTO_CAT_INSERTED" , "A category is added" ) ;
define( "_AM_WEBPHOTO_CAT_UPDATED" , "The category is modified" ) ;
define( "_AM_WEBPHOTO_CAT_BTN_BATCH" , "Apply" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_MAKETOPCAT" , "Create a new category on top" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_ADDPHOTOS" , "Add a image into this category" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_EDIT" , "Edit this category" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_MAKESUBCAT" , "Create a new category under this category" ) ;
define( "_AM_WEBPHOTO_CAT_FMT_NEEDADMISSION" , "%s images are needed to be admitted" ) ;
define( "_AM_WEBPHOTO_CAT_FMT_CATDELCONFIRM" , "%s will be deleted with its sub-categories, images, comments. OK?" ) ;


// Admission
//define( "_AM_WEBPHOTO_H3_FMT_ADMISSION" , "Admitting images (%s)" ) ;
//define( "_AM_WEBPHOTO_TH_SUBMITTER" , "Submitter" ) ;
//define( "_AM_WEBPHOTO_TH_TITLE" , "Title" ) ;
//define( "_AM_WEBPHOTO_TH_DESCRIPTION" , "Description" ) ;
//define( "_AM_WEBPHOTO_TH_CATEGORIES" , "Category" ) ;
//define( "_AM_WEBPHOTO_TH_DATE" , "Last update" ) ;


// Photo Video Media Manager
//define( "_AM_WEBPHOTO_H3_FMT_PHOTOMANAGER" , "Photo Video Media Manager (%s)" ) ;

define( "_AM_WEBPHOTO_TH_BATCHUPDATE" , "Update checked photos collectively" ) ;
define( "_AM_WEBPHOTO_OPT_NOCHANGE" , "- NO CHANGE -" ) ;
define( "_AM_WEBPHOTO_JS_UPDATECONFIRM" , "The checked items will be updated. OK?" ) ;


// Module Checker
//define( "_AM_WEBPHOTO_H3_FMT_MODULECHECKER" , "myAlbum-P checker (%s)" ) ;

define( "_AM_WEBPHOTO_H4_ENVIRONMENT" , "Environment Check" ) ;
define( "_AM_WEBPHOTO_PHPDIRECTIVE" , "PHP directive" ) ;
define( "_AM_WEBPHOTO_BOTHOK" , "both ok" ) ;
define( "_AM_WEBPHOTO_NEEDON" , "need on" ) ;

define( "_AM_WEBPHOTO_H4_TABLE" , "Table Check" ) ;

//define( "_AM_WEBPHOTO_PHOTOSTABLE" , "Photo Video Media table" ) ;
//define( "_AM_WEBPHOTO_DESCRIPTIONTABLE" , "Descriptions table" ) ;
//define( "_AM_WEBPHOTO_CATEGORIESTABLE" , "Categories table" ) ;
//define( "_AM_WEBPHOTO_VOTEDATATABLE" , "Votedata table" ) ;

define("_AM_WEBPHOTO_COMMENTSTABLE" , "Comments table" ) ;
define("_AM_WEBPHOTO_NUMBEROFPHOTOS" , "Number of Photo Video Media" ) ;
define("_AM_WEBPHOTO_NUMBEROFDESCRIPTIONS" , "Number of Descriptions" ) ;
define("_AM_WEBPHOTO_NUMBEROFCATEGORIES" , "Number of Categories" ) ;
define("_AM_WEBPHOTO_NUMBEROFVOTEDATA" , "Number of Votedata" ) ;
define("_AM_WEBPHOTO_NUMBEROFCOMMENTS" , "Number of Comments" ) ;

define( "_AM_WEBPHOTO_H4_CONFIG" , "Config Check" ) ;
define( "_AM_WEBPHOTO_PIPEFORIMAGES" , "Pipe for images" ) ;

//define( "_AM_WEBPHOTO_DIRECTORYFORPHOTOS" , "Directory for Photo Video Media" ) ;
//define( "_AM_WEBPHOTO_DIRECTORYFORTHUMBS" , "Directory for Thumbnails" ) ;

define( "_AM_WEBPHOTO_ERR_LASTCHAR" , "Error: The last charactor should not be '/'" ) ;
define( "_AM_WEBPHOTO_ERR_FIRSTCHAR" , "Error: The first charactor should be '/'" ) ;
define( "_AM_WEBPHOTO_ERR_PERMISSION" , "Error: You first have to create and chmod 777 this directory by ftp or shell." ) ;
define( "_AM_WEBPHOTO_ERR_NOTDIRECTORY" , "Error: This is not a directory." ) ;
define( "_AM_WEBPHOTO_ERR_READORWRITE" , "Error: This directory is not writable nor readable. You should change the permission of the directory to 777." ) ;
define( "_AM_WEBPHOTO_ERR_SAMEDIR" , "Error: Photo Video Media Path should not be the same as Thumbs Path" ) ;
define( "_AM_WEBPHOTO_LNK_CHECKGD2" , "Check that 'GD2'is working correctly under your GD bundled with PHP" ) ;
define( "_AM_WEBPHOTO_CHECKGD2" , "If the page linked to from here doesn't display correctly, you should not use your GD in truecolor mode." ) ;
define( "_AM_WEBPHOTO_GD2SUCCESS" , "Success!<br />Perhaps, you can use GD2 (truecolor) in this environment." ) ;

define( "_AM_WEBPHOTO_H4_PHOTOLINK" , "Photo Video Media & Thumbs Link Check" ) ;
define( "_AM_WEBPHOTO_NOWCHECKING" , "Now, checking ." ) ;

//define( "_AM_WEBPHOTO_FMT_PHOTONOTREADABLE" , "a main photo (%s) is not readable." ) ;
//define( "_AM_WEBPHOTO_FMT_THUMBNOTREADABLE" , "a thumbnail (%s) is not readable." ) ;

define( "_AM_WEBPHOTO_FMT_NUMBEROFDEADPHOTOS" , "%s dead photo files have been found." ) ;
define( "_AM_WEBPHOTO_FMT_NUMBEROFDEADTHUMBS" , "%s thumbnails should be rebuilt." ) ;
define( "_AM_WEBPHOTO_FMT_NUMBEROFREMOVEDTMPS" , "%s garbage files have been removed." ) ;
define( "_AM_WEBPHOTO_LINK_REDOTHUMBS" , "rebuild thumbnails" ) ;
define( "_AM_WEBPHOTO_LINK_TABLEMAINTENANCE" , "maintain tables" ) ;


// Redo Thumbnail
//define( "_AM_WEBPHOTO_H3_FMT_RECORDMAINTENANCE" , "myAlbum-P photo maintenance (%s)" ) ;

define( "_AM_WEBPHOTO_FMT_CHECKING" , "checking %s ..." ) ;
define( "_AM_WEBPHOTO_FORM_RECORDMAINTENANCE" , "maintenance of photos like remaking thumbnails etc." ) ;

define( "_AM_WEBPHOTO_FAILEDREADING" , "failed reading." ) ;
define( "_AM_WEBPHOTO_CREATEDTHUMBS" , "created a thumbnail." ) ;
define( "_AM_WEBPHOTO_BIGTHUMBS" , "failed making a thumnail. copied." ) ;
define( "_AM_WEBPHOTO_SKIPPED" , "skipped." ) ;
define( "_AM_WEBPHOTO_SIZEREPAIRED" , "(repaired size fields of the record.)" ) ;
define( "_AM_WEBPHOTO_RECREMOVED" , "this record has been removed." ) ;
define( "_AM_WEBPHOTO_PHOTONOTEXISTS" , "main photo does not exist." ) ;
define( "_AM_WEBPHOTO_PHOTORESIZED" , "main photo was resized." ) ;

define( "_AM_WEBPHOTO_TEXT_RECORDFORSTARTING" , "record's number starting with" ) ;
define( "_AM_WEBPHOTO_TEXT_NUMBERATATIME" , "number of records processed at a time" ) ;
define( "_AM_WEBPHOTO_LABEL_DESCNUMBERATATIME" , "Too large a number may lead to server time out." ) ;

define( "_AM_WEBPHOTO_RADIO_FORCEREDO" , "force recreating even if a thumbnail exists" ) ;
define( "_AM_WEBPHOTO_RADIO_REMOVEREC" , "remove records that don't link to a main photo" ) ;
define( "_AM_WEBPHOTO_RADIO_RESIZE" , "resize bigger photos than the pixels specified in current preferences" ) ;

define( "_AM_WEBPHOTO_FINISHED" , "finished" ) ;
define( "_AM_WEBPHOTO_LINK_RESTART" , "restart" ) ;
define( "_AM_WEBPHOTO_SUBMIT_NEXT" , "next" ) ;


// Batch Register
//define( "_AM_WEBPHOTO_H3_FMT_BATCHREGISTER" , "myAlbum-P batch register (%s)" ) ;


// GroupPerm Global
//define( "_AM_WEBPHOTO_GROUPPERM_GLOBAL" , "Global Permissions" ) ;

define( "_AM_WEBPHOTO_GROUPPERM_GLOBALDESC" , "Configure group's priviledges for this module" ) ;
define( "_AM_WEBPHOTO_GPERMUPDATED" , "Permissions have been changed successfully" ) ;


// Import
define( "_AM_WEBPHOTO_H3_FMT_IMPORTTO" , 'Importing images from another module to %s' ) ;
define( "_AM_WEBPHOTO_FMT_IMPORTFROMMYALBUMP" , 'Importing from "%s" as module type of myAlbum-P' ) ;
define( "_AM_WEBPHOTO_FMT_IMPORTFROMIMAGEMANAGER" , 'Importing from image manager in XOOPS' ) ;

//define( "_AM_WEBPHOTO_CB_IMPORTRECURSIVELY" , 'Importing sub-categories recursively' ) ;
//define( "_AM_WEBPHOTO_RADIO_IMPORTCOPY" , 'Copy images (comments will not be copied)' ) ;
//define( "_AM_WEBPHOTO_RADIO_IMPORTMOVE" , 'Move images (comments will be copied)' ) ;

define( "_AM_WEBPHOTO_IMPORTCONFIRM" , 'Confirm import. OK?' ) ;
define( "_AM_WEBPHOTO_FMT_IMPORTSUCCESS" , 'You have imported %s images' ) ;


// Export
define( "_AM_WEBPHOTO_H3_FMT_EXPORTTO" , 'Exporting images from %s to another module' ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTTOIMAGEMANAGER" , 'Exporting to image manager in XOOPS' ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTIMSRCCAT" , 'Source' ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTIMDSTCAT" , 'Destination' ) ;
define( "_AM_WEBPHOTO_CB_EXPORTRECURSIVELY" , 'with images in its sub-category' ) ;
define( "_AM_WEBPHOTO_CB_EXPORTTHUMB" , 'Export thumbnails instead of main images' ) ;
define( "_AM_WEBPHOTO_EXPORTCONFIRM" , 'Confirm export. OK?' ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTSUCCESS" , 'You have exported %s images' ) ;


//---------------------------------------------------------
// move from main.php
//---------------------------------------------------------
define("_AM_WEBPHOTO_BTN_SELECTALL" , "Select All" ) ;
define("_AM_WEBPHOTO_BTN_SELECTNONE" , "Select None" ) ;
define("_AM_WEBPHOTO_BTN_SELECTRVS" , "Select Reverse" ) ;
define("_AM_WEBPHOTO_FMT_PHOTONUM" , "%s every page" ) ;

define("_AM_WEBPHOTO_ADMISSION" , "Admit Photo Video Media" ) ;
define("_AM_WEBPHOTO_ADMITTING" , "Admitted photo(s)" ) ;
define("_AM_WEBPHOTO_LABEL_ADMIT" , "Admit the photos you checked" ) ;
define("_AM_WEBPHOTO_BUTTON_ADMIT" , "Admit" ) ;
define("_AM_WEBPHOTO_BUTTON_EXTRACT" , "extract" ) ;

define("_AM_WEBPHOTO_LABEL_REMOVE" , "Remove the photos checked" ) ;
define("_AM_WEBPHOTO_JS_REMOVECONFIRM" , "Remove OK?" ) ;
define("_AM_WEBPHOTO_LABEL_MOVE" , "Change category of the checked photos" ) ;
define("_AM_WEBPHOTO_BUTTON_MOVE" , "Move" ) ;
define("_AM_WEBPHOTO_BUTTON_UPDATE" , "Modify" ) ;
define("_AM_WEBPHOTO_DEADLINKMAINPHOTO" , "The main image don't exist" ) ;

define("_AM_WEBPHOTO_NOSUBMITTED","No New Submitted Photo Video Media.");
define("_AM_WEBPHOTO_ADDMAIN","Add a MAIN Category");
define("_AM_WEBPHOTO_IMGURL","Image URL (OPTIONAL Image height will be resized to 50): ");
define("_AM_WEBPHOTO_ADD","Add");
define("_AM_WEBPHOTO_ADDSUB","Add a SUB-Category");
define("_AM_WEBPHOTO_IN","in");
define("_AM_WEBPHOTO_MODCAT","Modify Category");

define("_AM_WEBPHOTO_MODREQDELETED","Modification Request Deleted.");
define("_AM_WEBPHOTO_IMGURLMAIN","Image URL (OPTIONAL and Only valid for main categories. Image height will be resized to 50): ");
define("_AM_WEBPHOTO_PARENT","Parent Category:");
define("_AM_WEBPHOTO_SAVE","Save Changes");
define("_AM_WEBPHOTO_CATDELETED","Category Deleted.");
define("_AM_WEBPHOTO_CATDEL_WARNING","WARNING: Are you sure you want to delete this Category and ALL its Photo Video Media and Comments?");

define("_AM_WEBPHOTO_NEWCATADDED","New Category Added Successfully!");
define("_AM_WEBPHOTO_ERROREXIST","ERROR: The Photo Video Media you provided is already in the database!");
define("_AM_WEBPHOTO_ERRORTITLE","ERROR: You need to enter a TITLE!");
define("_AM_WEBPHOTO_ERRORDESC","ERROR: You need to enter a DESCRIPTION!");
define("_AM_WEBPHOTO_WEAPPROVED","We approved your link submission to the photo database.");
define("_AM_WEBPHOTO_THANKSSUBMIT","Thank you for your submission!");
define("_AM_WEBPHOTO_CONFUPDATED","Configuration Updated Successfully!");

define("_AM_WEBPHOTO_PHOTOBATCHUPLOAD","Register photos uploaded to the server already");
define("_AM_WEBPHOTO_PHOTOPATH","Path");
define("_AM_WEBPHOTO_TEXT_DIRECTORY","Directory");
define("_AM_WEBPHOTO_DESC_PHOTOPATH","Type the full path of the directory including photos to be registered");
define("_AM_WEBPHOTO_MES_INVALIDDIRECTORY","Invalid directory is specified.");
define("_AM_WEBPHOTO_MES_BATCHDONE","%s photo(s) have been registered.");
define("_AM_WEBPHOTO_MES_BATCHNONE","No photo was detected in the directory.");


//---------------------------------------------------------
// move from myalbum_constants.php
//---------------------------------------------------------
// Global Group Permission
define( "_AM_WEBPHOTO_GPERM_INSERTABLE" , "Post (need approval)" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPERINSERT" , "Super Post" ) ;
define( "_AM_WEBPHOTO_GPERM_EDITABLE" , "Edit (need approval)" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPEREDIT" , "Super Edit" ) ;
define( "_AM_WEBPHOTO_GPERM_DELETABLE" , "Delete (need approval)" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPERDELETE" , "Super Delete" ) ;
define( "_AM_WEBPHOTO_GPERM_TOUCHOTHERS" , "Touch photos posted by others" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPERTOUCHOTHERS" , "Super Touch others" ) ;
define( "_AM_WEBPHOTO_GPERM_RATEVIEW" , "View Rate" ) ;
define( "_AM_WEBPHOTO_GPERM_RATEVOTE" , "Vote" ) ;
define( "_AM_WEBPHOTO_GPERM_TELLAFRIEND" , "Tell a friend" ) ;

// add for webphoto
define( "_AM_WEBPHOTO_GPERM_TAGEDIT" , "Edit Tag" ) ;

// v0.30
define( "_AM_WEBPHOTO_GPERM_MAIL" , "Post by Mail" ) ;
define( "_AM_WEBPHOTO_GPERM_FILE" , "Post by FTP" ) ;

//=========================================================
// add for webphoto
//=========================================================

//---------------------------------------------------------
// google icon
// modify from gnavi
//---------------------------------------------------------

// list
define("_AM_WEBPHOTO_GICON_ADD" , "Add New Google Icon" ) ;
define("_AM_WEBPHOTO_GICON_LIST_IMAGE" , 'Icon' ) ;
define("_AM_WEBPHOTO_GICON_LIST_SHADOW" , 'Shadow' ) ;
define("_AM_WEBPHOTO_GICON_ANCHOR" , 'Anchor Point' ) ;
define("_AM_WEBPHOTO_GICON_WINANC" , 'Window Anchor' ) ;
define("_AM_WEBPHOTO_GICON_LIST_EDIT" , 'Edit Icon' ) ;

// form
define("_AM_WEBPHOTO_GICON_MENU_NEW" ,  "Add Icon" ) ;
define("_AM_WEBPHOTO_GICON_MENU_EDIT" , "Edit Icon" ) ;
define("_AM_WEBPHOTO_GICON_IMAGE_SEL" ,  "Select Icon Image" ) ;
define("_AM_WEBPHOTO_GICON_SHADOW_SEL" , "Select Icon Shadow" ) ;
define("_AM_WEBPHOTO_GICON_SHADOW_DEL" , 'Delete Icon Shadow' ) ;
define("_AM_WEBPHOTO_GICON_DELCONFIRM" , "Confirm delete icon %s ?" ) ;


//---------------------------------------------------------
// mime type
// modify from wfdownloads
//---------------------------------------------------------

// Mimetype Form
define("_AM_WEBPHOTO_MIME_CREATEF", "Create Mimetype");
define("_AM_WEBPHOTO_MIME_MODIFYF", "Modify Mimetype");
define("_AM_WEBPHOTO_MIME_NOMIMEINFO", "No mimetypes selected.");
define("_AM_WEBPHOTO_MIME_INFOTEXT", "<ul><li>New mimetypes can be created, edit or deleted easily via this form.</li>
	<li>View displayed mimetypes for Admin and User uploads.</li>
	<li>Change mimetype upload status.</li></ul>
	");

// Mimetype Database
define("_AM_WEBPHOTO_MIME_DELETETHIS", "Delete Selected Mimetype?");
define("_AM_WEBPHOTO_MIME_MIMEDELETED", "Mimetype %s has been deleted");
define("_AM_WEBPHOTO_MIME_CREATED", "Mimetype Information Created");
define("_AM_WEBPHOTO_MIME_MODIFIED", "Mimetype Information Modified");

//image admin icon 
define("_AM_WEBPHOTO_MIME_ICO_EDIT","Edit This Item");
define("_AM_WEBPHOTO_MIME_ICO_DELETE","Delete This Item");
define("_AM_WEBPHOTO_MIME_ICO_ONLINE","Online");
define("_AM_WEBPHOTO_MIME_ICO_OFFLINE","Offline");

// find mine type
//define("_AM_WEBPHOTO_MIME_FINDMIMETYPE", "Find New Mimetype:");
//define("_AM_WEBPHOTO_MIME_FINDIT", "Get Extension!");

// added for webphoto
define("_AM_WEBPHOTO_MIME_PERMS", "Allowed Groups");
define("_AM_WEBPHOTO_MIME_ALLOWED", "Allowed Mimetype");
define("_AM_WEBPHOTO_MIME_NOT_ENTER_EXT", "Not enter extention");

//---------------------------------------------------------
// check config
//---------------------------------------------------------
define("_AM_WEBPHOTO_DIRECTORYFOR_PHOTOS" , "Directory for Photo Video Media" ) ;
define("_AM_WEBPHOTO_DIRECTORYFOR_THUMBS" , "Directory for Thumbnails" ) ;
define("_AM_WEBPHOTO_DIRECTORYFOR_GICONS" , "Directory for Google Icons" ) ;
define("_AM_WEBPHOTO_DIRECTORYFOR_TMP" ,    "Directory for Tempolary" ) ;

//---------------------------------------------------------
// checktable
//---------------------------------------------------------
define("_AM_WEBPHOTO_NUMBEROFRECORED", "Number of recoeds");

//---------------------------------------------------------
// manage
//---------------------------------------------------------
define("_AM_WEBPHOTO_MANAGE_DESC","<b>Caution</b><br />The management of this table only<br />Do not change related tables");
define("_AM_WEBPHOTO_ERR_NO_RECORD", "There are no record");

//---------------------------------------------------------
// cat manager
//---------------------------------------------------------
//define("_AM_WEBPHOTO_DSC_CAT_IMGPATH" , "Please upload the desired category image file<br />and set Path from the directory installed XOOPS.<br />(The first character must be '/'.)<br />Show folder icon if not set" ) ;
//define("_AM_WEBPHOTO_OPT_CAT_PERM_POST_ALL" , "All Groups" ) ;

//---------------------------------------------------------
// import
//---------------------------------------------------------
define("_AM_WEBPHOTO_FMT_IMPORTFROM_WEBPHOTO" , 'Importing from "%s" as module type of webphoto' ) ;
define("_AM_WEBPHOTO_IMPORT_COMMENT_NO" , "Do not copy comments" ) ;
define("_AM_WEBPHOTO_IMPORT_COMMENT_YES" , "Copy comments") ;

//---------------------------------------------------------
// v0.20
//---------------------------------------------------------
define("_AM_WEBPHOTO_PATHINFO_LINK" , "Check that 'Pathinfo' is working correctly in your server" ) ;
define("_AM_WEBPHOTO_PATHINFO_DSC" , "If the page linked to from here doesn't display correctly, you should not use 'Pathinfo' " ) ;
define("_AM_WEBPHOTO_PATHINFO_SUCCESS" , "Success!<br />Perhaps, you can use 'Pathinfo' in your server" ) ;
define("_AM_WEBPHOTO_CAP_REDO_EXIF" , "Get Exif" ) ;
define("_AM_WEBPHOTO_RADIO_REDO_EXIF_TRY" , "Get when not set" ) ;
define("_AM_WEBPHOTO_RADIO_REDO_EXIF_ALWAYS" , "Get always" ) ;

//---------------------------------------------------------
// v0.30
//---------------------------------------------------------
// checkconfigs
define("_AM_WEBPHOTO_DIRECTORYFOR_FILE" , "Directory for FTP File" ) ;
define("_AM_WEBPHOTO_WARN_GEUST_CAN_READ" ,  "Anoymous user can read file in this directory" ) ;
define("_AM_WEBPHOTO_WARN_RECOMMEND_PATH" ,  "Recommend to set it except under the document root" ) ;
define("_AM_WEBPHOTO_MULTIBYTE_LINK" , "Check that 'Charset Convert' is working correctly in your server)" ) ;
define("_AM_WEBPHOTO_MULTIBYTE_DSC" , "If the page linked to from here doesn't display correctly, you should not use 'Charset Convert' " ) ;
define("_AM_WEBPHOTO_MULTIBYTE_SUCCESS" , "Can you read this sentence correctly, wihout character garble ? " ) ;

// maillog manager
define("_AM_WEBPHOTO_SHOW_LIST" ,  "Show list" ) ;
define("_AM_WEBPHOTO_MAILLOG_STATUS_REJECT" ,  "Rejected mail" ) ;
define("_AM_WEBPHOTO_MAILLOG_STATUS_PARTIAL" , "Mail which reject some of attached files" ) ;
define("_AM_WEBPHOTO_MAILLOG_STATUS_SUBMIT" ,  "Submitted mail" ) ;
define("_AM_WEBPHOTO_BUTTON_SUBMIT_MAIL" ,  "SUBMIT MAIL" ) ;
define("_AM_WEBPHOTO_ERR_MAILLOG_NO_ATTACH" ,  "You must select the attached files" ) ;

// mimetype
define("_AM_WEBPHOTO_MIME_ADD_NEW" ,  "Add New MIME Type" ) ;

//---------------------------------------------------------
// v0.40
//---------------------------------------------------------
// index
define("_AM_WEBPHOTO_MUST_UPDATE" , "You MUST update" ) ;
define("_AM_WEBPHOTO_TITLE_BIN" , "Command Management" ) ;
define("_AM_WEBPHOTO_TEST_BIN" ,  "Test Execute" ) ;

// redothumbs
define("_AM_WEBPHOTO_ERR_GET_IMAGE_SIZE", "cannot get image size" ) ;

// checktables
define("_AM_WEBPHOTO_FMT_NOT_READABLE" , "%s (%s) is not readable." ) ;

//---------------------------------------------------------
// v0.50
//---------------------------------------------------------
// config check
define("_AM_WEBPHOTO_DIRECTORYFOR_UPLOADS" , "Directory for Upload Files" ) ;
define("_AM_WEBPHOTO_DIRECTORYFOR_MEDIAS" , "Directory for Media Files" ) ;

// item manager
define("_AM_WEBPHOTO_ITEM_SELECT","Select Item");
define("_AM_WEBPHOTO_ITEM_ADD","Add Item");
define("_AM_WEBPHOTO_ITEM_LISTING","View Item");
define("_AM_WEBPHOTO_VOTE_DELETED","Vote data deleted.");
define("_AM_WEBPHOTO_VOTE_STATS","Vote Statistics");
define("_AM_WEBPHOTO_VOTE_ENTRY","Entry Votes");
define("_AM_WEBPHOTO_VOTE_USER","Registered User Votes");
define("_AM_WEBPHOTO_VOTE_GUEST","Anonymous User Votes");
define("_AM_WEBPHOTO_VOTE_TOTAL","total votes");
define("_AM_WEBPHOTO_VOTE_USERAVG","User Average Rating");
define("_AM_WEBPHOTO_VOTE_USERVOTES","User Total Votes");
define("_AM_WEBPHOTO_LOG_VIEW","View Log File");
define("_AM_WEBPHOTO_LOG_EMPT","Empty Log File");
define("_AM_WEBPHOTO_PLAYLIST_PATH","Playlist Path");
define("_AM_WEBPHOTO_PLAYLIST_REFRESH","Refresh Playlist Cache");
define("_AM_WEBPHOTO_STATUS_CHANGE","Status Change");
define("_AM_WEBPHOTO_STATUS_OFFLINE","Off Line");
define("_AM_WEBPHOTO_STATUS_ONLINE","On Line");
define("_AM_WEBPHOTO_STATUS_AUTO","Auto Publish");

// item form
define("_AM_WEBPHOTO_TIME_NOW","Current Time");

// playlist form
define("_AM_WEBPHOTO_PLAYLIST_ADD", "Add Playlist" ) ;
define("_AM_WEBPHOTO_PLAYLIST_TYPE", "Playlist Type" ) ;
define("_AM_WEBPHOTO_PLAYLIST_FEED_DSC","Enter the web feed URL.");
define("_AM_WEBPHOTO_PLAYLIST_DIR_DSC","Select the directory name");

// player manager
define("_AM_WEBPHOTO_PLAYER_MANAGER","Player Manager");
define("_AM_WEBPHOTO_PLAYER_ADD","Add New Player");
define("_AM_WEBPHOTO_PLAYER_MOD","Modify Player");
define("_AM_WEBPHOTO_PLAYER_CLONE","Clone Player");
define("_AM_WEBPHOTO_PLAYER_ADDED","New Player Added");
define("_AM_WEBPHOTO_PLAYER_DELETED","Player deleted");
define("_AM_WEBPHOTO_PLAYER_MODIFIED","Player Modified");
define("_AM_WEBPHOTO_PLAYER_PREVIEW","Preview");
define("_AM_WEBPHOTO_PLAYER_PREVIEW_DSC","Save your changes first!");
define("_AM_WEBPHOTO_PLAYER_PREVIEW_LINK","Preview Source");
define("_AM_WEBPHOTO_PLAYER_NO_ITEM","Thers are no item to play");
define("_AM_WEBPHOTO_PLAYER_WARNING","WARNING: Are you sure you want to delete this Player? <br />Manually edit all entries using this player before deleting it.");
define("_AM_WEBPHOTO_PLAYER_ERR_EXIST","ERROR: The same title player you provided is already in the database!");
define("_AM_WEBPHOTO_BUTTON_CLONE","Clone");

//---------------------------------------------------------
// v0.60
//---------------------------------------------------------
// cat form
define("_AM_WEBPHOTO_CAP_CAT_SELECT","Select the category image");
define("_AM_WEBPHOTO_DSC_CAT_PATH" , "Set Path from the directory installed XOOPS.<br />(The first character must be '/'.)" ) ;
define("_AM_WEBPHOTO_DSC_CAT_FOLDER" , "Show folder icon if not set" ) ;

//---------------------------------------------------------
// v0.70
//---------------------------------------------------------
define("_AM_WEBPHOTO_RECOMMEND_OFF" , "recommend off" ) ;

//---------------------------------------------------------
// v0.80
//---------------------------------------------------------
define("_AM_WEBPHOTO_TITLE_WAITING" , "List of Wating to approve" ) ;
define("_AM_WEBPHOTO_TITLE_OFFLINE" , "List of Offline" ) ;
define("_AM_WEBPHOTO_TITLE_EXPIRED" , "List of Expired" ) ;

//---------------------------------------------------------
// v0.81
//---------------------------------------------------------
// checkconfigs
define("_AM_WEBPHOTO_QR_CHECK_LINK" , "Check that 'QR Code' is working correctly" ) ;
define("_AM_WEBPHOTO_QR_CHECK_DSC" , "If the page linked to from here doesn't display correctly, you should not use 'QR Code' " ) ;
define("_AM_WEBPHOTO_QR_CHECK_SUCCESS" , "Can you see 'QR Code' correctly" ) ;
define("_AM_WEBPHOTO_QR_CHECK_SHOW" , "Show Debug Info" ) ;
define("_AM_WEBPHOTO_QR_CHECK_INFO" , "Debug Info" ) ;

//---------------------------------------------------------
// v0.90
//---------------------------------------------------------
// cat form
define("_AM_WEBPHOTO_CAT_PARENT_CAP" , "Permission of Parent Category" ) ;
define("_AM_WEBPHOTO_CAT_PARENT_FMT" , "Succeed to the permission of parent category ( %s )" ) ;
define("_AM_WEBPHOTO_CAT_CHILD_CAP"  , "Child Categories" ) ;
define("_AM_WEBPHOTO_CAT_CHILD_NUM"  , "Number of child categories" ) ;
define("_AM_WEBPHOTO_CAT_CHILD_PERM" , "Change permission of child categories" ) ;

//---------------------------------------------------------
// v1.00
//---------------------------------------------------------
// groupperm
define("_AM_WEBPHOTO_GPERM_HTML" , "Use HTML" ) ;

//---------------------------------------------------------
// v1.21
//---------------------------------------------------------
define("_AM_WEBPHOTO_RSS_DEBUG" , "RSS Debug View" ) ;
define("_AM_WEBPHOTO_RSS_CLEAR" , "RSS Cache Clear" ) ;
define("_AM_WEBPHOTO_RSS_CLEARED" , "Cleared" ) ;

//---------------------------------------------------------
// v1.40
//---------------------------------------------------------
define("_AM_WEBPHOTO_TIMELINE_MODULE" , "Timeline Module" ) ;
define("_AM_WEBPHOTO_MODULE_NOT_INSTALL" , "Module is not installed" ) ;

//---------------------------------------------------------
// v1.50
//---------------------------------------------------------
define("_AM_WEBPHOTO_FILE_CHECK" , "File Valid Check" ) ;
define("_AM_WEBPHOTO_FILE_CHECK_DSC" , "checks there are necessary files with file size" ) ;

//---------------------------------------------------------
// v1.72
//---------------------------------------------------------
define( "_AM_WEBPHOTO_MYSQL_CONFIG" , "MySQL Config" ) ;
define( "_AM_WEBPHOTO_MULTIBYTE_CONFIG" , "Multibyte Config" ) ;

//---------------------------------------------------------
// v2.00
//---------------------------------------------------------
// invite
define("_AM_WEBPHOTO_INVITE_EMAIL", "Email address of the inviting person" ) ;
define("_AM_WEBPHOTO_INVITE_NAME",   "Your Name" ) ;
define("_AM_WEBPHOTO_INVITE_MESSAGE", "Message" ) ;
define("_AM_WEBPHOTO_INVITE_SUBMIT", "Invite" ) ;
define("_AM_WEBPHOTO_INVITE_EXAMPLE", "Exsample: Hello, I am John. I send an invitation at the site which was talked about. <br />Jane, too, attempt to register by all means. " ) ;
define("_AM_WEBPHOTO_INVITE_SUBJECT", "You get the invitation email. from %s to %s " ) ;
define("_AM_WEBPHOTO_INVITE_ERR_NO_NAME", "No Name" ) ;

// gperm
define("_AM_WEBPHOTO_GROUP_MOD_ADMIN" , "Admin group of this module" ) ;
define("_AM_WEBPHOTO_GROUP_MOD_USER"  , "User group of this module" ) ;
define("_AM_WEBPHOTO_GROUP_MOD_CATEGORY"  , "Category group of this module" ) ;
define("_AM_WEBPHOTO_GPERM_MODULE_ADMIN" , "Module Admin" ) ;
define("_AM_WEBPHOTO_GPERM_MODULE_READ"  , "Module Access" ) ;

// item manage
define("_AM_WEBPHOTO_BUTTON_REFUSE", "Refuse");
//define("_AM_WEBPHOTO_MAIL_SUBMIT_APPROVE", "Approved your photo");
//define("_AM_WEBPHOTO_MAIL_SUBMIT_REFUSE", "Refused your photo");
define("_AM_WEBPHOTO_ERR_NO_SELECT" , "Error: Not select item" ) ;

// user list
define('_AM_WEBPHOTO_USER_UID', "UID");
define('_AM_WEBPHOTO_USER_UNAME', "User Name");
define('_AM_WEBPHOTO_USER_NAME', "Real Name");
define('_AM_WEBPHOTO_USER_POSTS', "Comments/Posts");
define('_AM_WEBPHOTO_USER_LEVEL', "Level");
define('_AM_WEBPHOTO_USER_REGDATE', "Member Since");
define('_AM_WEBPHOTO_USER_LASTLOGIN', "Last Login");
define('_AM_WEBPHOTO_USER_CONTROL', "Control");
define('_AM_WEBPHOTO_USER_TOTAL', "Total of member");
define('_AM_WEBPHOTO_USER_ASSIGN', "Assign a member");
define('_AM_WEBPHOTO_USER_USER', "User");

//---------------------------------------------------------
// v2.40
//---------------------------------------------------------
define('_AM_WEBPHOTO_PLEASE_IMPORT_MYALBUM', "Please execute the batch import from Myalbum.");

// === define end ===
}

?>