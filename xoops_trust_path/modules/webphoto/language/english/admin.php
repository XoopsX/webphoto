<?php
// $Id: admin.php,v 1.1 2008/06/21 12:22:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

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


// Photo Manager
//define( "_AM_WEBPHOTO_H3_FMT_PHOTOMANAGER" , "Photo Manager (%s)" ) ;

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

//define( "_AM_WEBPHOTO_PHOTOSTABLE" , "Photos table" ) ;
//define( "_AM_WEBPHOTO_DESCRIPTIONTABLE" , "Descriptions table" ) ;
//define( "_AM_WEBPHOTO_CATEGORIESTABLE" , "Categories table" ) ;
//define( "_AM_WEBPHOTO_VOTEDATATABLE" , "Votedata table" ) ;

define("_AM_WEBPHOTO_COMMENTSTABLE" , "Comments table" ) ;
define("_AM_WEBPHOTO_NUMBEROFPHOTOS" , "Number of Photos" ) ;
define("_AM_WEBPHOTO_NUMBEROFDESCRIPTIONS" , "Number of Descriptions" ) ;
define("_AM_WEBPHOTO_NUMBEROFCATEGORIES" , "Number of Categories" ) ;
define("_AM_WEBPHOTO_NUMBEROFVOTEDATA" , "Number of Votedata" ) ;
define("_AM_WEBPHOTO_NUMBEROFCOMMENTS" , "Number of Comments" ) ;

define( "_AM_WEBPHOTO_H4_CONFIG" , "Config Check" ) ;
define( "_AM_WEBPHOTO_PIPEFORIMAGES" , "Pipe for images" ) ;

//define( "_AM_WEBPHOTO_DIRECTORYFORPHOTOS" , "Directory for Photos" ) ;
//define( "_AM_WEBPHOTO_DIRECTORYFORTHUMBS" , "Directory for Thumbnails" ) ;

define( "_AM_WEBPHOTO_ERR_LASTCHAR" , "Error: The last charactor should not be '/'" ) ;
define( "_AM_WEBPHOTO_ERR_FIRSTCHAR" , "Error: The first charactor should be '/'" ) ;
define( "_AM_WEBPHOTO_ERR_PERMISSION" , "Error: You first have to create and chmod 777 this directory by ftp or shell." ) ;
define( "_AM_WEBPHOTO_ERR_NOTDIRECTORY" , "Error: This is not a directory." ) ;
define( "_AM_WEBPHOTO_ERR_READORWRITE" , "Error: This directory is not writable nor readable. You should change the permission of the directory to 777." ) ;
define( "_AM_WEBPHOTO_ERR_SAMEDIR" , "Error: Photos Path should not be the same as Thumbs Path" ) ;
define( "_AM_WEBPHOTO_LNK_CHECKGD2" , "Check that 'GD2'is working correctly under your GD bundled with PHP" ) ;
define( "_AM_WEBPHOTO_CHECKGD2" , "If the page linked to from here doesn't display correctly, you should not use your GD in truecolor mode." ) ;
define( "_AM_WEBPHOTO_GD2SUCCESS" , "Success!<br />Perhaps, you can use GD2 (truecolor) in this environment." ) ;

define( "_AM_WEBPHOTO_H4_PHOTOLINK" , "Photos & Thumbs Link Check" ) ;
define( "_AM_WEBPHOTO_NOWCHECKING" , "Now, checking ." ) ;
define( "_AM_WEBPHOTO_FMT_PHOTONOTREADABLE" , "a main photo (%s) is not readable." ) ;
define( "_AM_WEBPHOTO_FMT_THUMBNOTREADABLE" , "a thumbnail (%s) is not readable." ) ;
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

define("_AM_WEBPHOTO_ADMISSION" , "Admit Photos" ) ;
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

define("_AM_WEBPHOTO_NOSUBMITTED","No New Submitted Photos.");
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
define("_AM_WEBPHOTO_CATDEL_WARNING","WARNING: Are you sure you want to delete this Category and ALL its Photos and Comments?");

define("_AM_WEBPHOTO_NEWCATADDED","New Category Added Successfully!");
define("_AM_WEBPHOTO_ERROREXIST","ERROR: The Photo you provided is already in the database!");
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
define("_AM_WEBPHOTO_DIRECTORYFOR_PHOTOS" , "Directory for Photos" ) ;
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
define("_AM_WEBPHOTO_DSC_CAT_IMGPATH" , "Path from the directory installed XOOPS.<br />(The first character must be '/'.)" ) ;
define("_AM_WEBPHOTO_OPT_CAT_PERM_POST_ALL" , "All Groups" ) ;

//---------------------------------------------------------
// import
//---------------------------------------------------------
define("_AM_WEBPHOTO_FMT_IMPORTFROM_WEBPHOTO" , 'Importing from "%s" as module type of webphoto' ) ;
define("_AM_WEBPHOTO_IMPORT_COMMENT_NO" , "Do not copy comments" ) ;
define("_AM_WEBPHOTO_IMPORT_COMMENT_YES" , "Copy comments") ;

// === define end ===
}

?>