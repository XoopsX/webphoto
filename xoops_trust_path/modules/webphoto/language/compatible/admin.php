<?php
// $Id: admin.php,v 1.10 2011/05/10 02:56:39 ohwada Exp $

//=========================================================
// webphoto module
// 2009-03-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// v2.40
//---------------------------------------------------------
if( !defined("_AM_WEBPHOTO_PLEASE_IMPORT_MYALBUM") ) 
{
define('_AM_WEBPHOTO_PLEASE_IMPORT_MYALBUM', "Please execute the batch import from Myalbum.");
}

//---------------------------------------------------------
// v2.00
//---------------------------------------------------------
if( !defined("_AM_WEBPHOTO_INVITE_EMAIL") ) 
{
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

}

//---------------------------------------------------------
// v1.72
//---------------------------------------------------------
if( !defined("_AM_WEBPHOTO_MYSQL_CONFIG") ) 
{
define( "_AM_WEBPHOTO_MYSQL_CONFIG" , "MySQL Config" ) ;
define( "_AM_WEBPHOTO_MULTIBYTE_CONFIG" , "Multibyte Config" ) ;
}

//---------------------------------------------------------
// v1.50
//---------------------------------------------------------
if( !defined("_AM_WEBPHOTO_FILE_CHECK") ) 
{

define("_AM_WEBPHOTO_FILE_CHECK" , "File Valid Check" ) ;
define("_AM_WEBPHOTO_FILE_CHECK_DSC" , "checks there are necessary files with file size" ) ;

}


//---------------------------------------------------------
// v1.40
//---------------------------------------------------------
if( !defined("_AM_WEBPHOTO_TIMELINE_MODULE") ) 
{

define("_AM_WEBPHOTO_TIMELINE_MODULE" , "Timeline Module" ) ;
define("_AM_WEBPHOTO_MODULE_NOT_INSTALL" , "Module is not installed" ) ;

}

//---------------------------------------------------------
// admin for French
//---------------------------------------------------------
if( !defined("_AM_WEBPHOTO_RSS_DEBUG") ) 
{
//---------------------------------------------------------
// v1.21
//---------------------------------------------------------
define("_AM_WEBPHOTO_RSS_DEBUG" , "RSS Debug View" ) ;
define("_AM_WEBPHOTO_RSS_CLEAR" , "RSS Cache Clear" ) ;
define("_AM_WEBPHOTO_RSS_CLEARED" , "Cleared" ) ;

// === define end ===
}

//---------------------------------------------------------
// admin for Portugues.do.Brasil
//---------------------------------------------------------
// === define begin ===
if( !defined("_AM_WEBPHOTO_DIRECTORYFOR_UPLOADS") ) 
{

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
define("_AM_WEBPHOTO_CAT_PARENT_CAP" , "Pemission of Parent Category" ) ;
define("_AM_WEBPHOTO_CAT_PARENT_FMT" , "Accede permission of parent category ( %s )" ) ;
define("_AM_WEBPHOTO_CAT_CHILD_CAP"  , "Child Categories" ) ;
define("_AM_WEBPHOTO_CAT_CHILD_NUM"  , "Number of child categories" ) ;
define("_AM_WEBPHOTO_CAT_CHILD_PERM" , "Change permission of child categories" ) ;

//---------------------------------------------------------
// v1.00
//---------------------------------------------------------
// groupperm
define( "_AM_WEBPHOTO_GPERM_HTML" , "Use HTML" ) ;

// === define end ===
}

?>