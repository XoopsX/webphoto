<?php
// $Id: constants.php,v 1.13 2008/12/18 13:23:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-11-29 K.OHWADA
// _C_WEBPHOTO_VODEO_THUMB_PLURAL_MAX
// 2008-10-08 K.OHWADA
// _C_WEBPHOTO_UPLOAD_FIELD_PHOTO
// 2008-10-01 K.OHWADA
// added _C_WEBPHOTO_SWFOBJECT_EXTS
// 2008-08-24 K.OHWADA
// added _C_WEBPHOTO_MAX_ITEM_FILE_ID
// 2008-08-01 K.OHWADA
// added _B_WEBPHOTO_GPERM_MAIL
// 2008-07-01 K.OHWADA
// added _C_WEBPHOTO_VIDEO_THUMB_PREFIX
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

// === define begin ===
if( !defined("_C_WEBPHOTO_LOADED") ) 
{

define("_C_WEBPHOTO_LOADED", 1 ) ;

//=========================================================
// Constant
//=========================================================

// System Constants (Dont Edit)
// group_permission's gperm_itemid is MEDIUMINT 
// max of MEDIUMINT is 8388607
define("_B_WEBPHOTO_GPERM_INSERTABLE",         1 ) ;
define("_B_WEBPHOTO_GPERM_SUPERINSERT",        2 ) ;
define("_B_WEBPHOTO_GPERM_EDITABLE",           4 ) ;
define("_B_WEBPHOTO_GPERM_SUPEREDIT",          8 ) ;
define("_B_WEBPHOTO_GPERM_DELETABLE",         16 ) ;
define("_B_WEBPHOTO_GPERM_SUPERDELETE",       32 ) ;
define("_B_WEBPHOTO_GPERM_TOUCHOTHERS",       64 ) ;
define("_B_WEBPHOTO_GPERM_SUPERTOUCHOTHERS", 128 ) ;
define("_B_WEBPHOTO_GPERM_RATEVIEW",         256 ) ;
define("_B_WEBPHOTO_GPERM_RATEVOTE",         512 ) ;
define("_B_WEBPHOTO_GPERM_TELLAFRIEND",     1024 ) ;
define("_B_WEBPHOTO_GPERM_TAGEDIT",         2048 ) ;

// v0.30
define("_B_WEBPHOTO_GPERM_MAIL",            4096 ) ;
define("_B_WEBPHOTO_GPERM_FILE",            8192 ) ;

define("_C_WEBPHOTO_GPERM_NAME", "webphoto" ) ;

// constants
define("_C_WEBPHOTO_IMAGE_EXTS" , "jpg|jpeg|gif|png" ) ;
define("_C_WEBPHOTO_SWFOBJECT_EXTS", "swf" );
define("_C_WEBPHOTO_MEDIAPLAYER_AUDIO_EXTS", "mp3" );
define("_C_WEBPHOTO_MEDIAPLAYER_VIDEO_EXTS", "flv" );
define("_C_WEBPHOTO_VIDEO_DOCOMO_EXTS" , "3gp" ) ;
define("_C_WEBPHOTO_VIDEO_FLASH_EXT"  , "flv" ) ;
define("_C_WEBPHOTO_VIDEO_DOCOMO_EXT" , "3gp" ) ;

define("_C_WEBPHOTO_CFG_OPT_PERPAGE" , "10|20|50|100" ) ;

//define("_C_WEBPHOTO_CAT_MAIN_WIDTH_DEFAULT" ,  120 ) ;
//define("_C_WEBPHOTO_CAT_MAIN_HEIGHT_DEFAULT" , 120 ) ;
//define("_C_WEBPHOTO_CAT_SUB_WIDTH_DEFAULT" ,   50 ) ;
//define("_C_WEBPHOTO_CAT_SUB_HEIGHT_DEFAULT" ,  50 ) ;

define("_C_WEBPHOTO_MAX_PHOTO_TEXT" , 10 ) ;
define("_C_WEBPHOTO_MAX_CAT_TEXT" ,   5 ) ;
define("_C_WEBPHOTO_MAX_USER_TEXT" ,  5 ) ;
define("_C_WEBPHOTO_MAX_ITEM_FILE_ID" , 10 ) ;
define("_C_WEBPHOTO_MAX_ITEM_TEXT"    , 10 ) ;

define("_C_WEBPHOTO_ITEM_KIND_UNDEFINED", 0 ) ;
define("_C_WEBPHOTO_ITEM_KIND_NONE",      1 ) ;
define("_C_WEBPHOTO_ITEM_KIND_GENERAL",  10 ) ;
define("_C_WEBPHOTO_ITEM_KIND_IMAGE",    11 ) ;
define("_C_WEBPHOTO_ITEM_KIND_VIDEO",    12 ) ;
define("_C_WEBPHOTO_ITEM_KIND_AUDIO",    13 ) ;
define("_C_WEBPHOTO_ITEM_KIND_EMBED",   100 ) ;
define("_C_WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL", 101 ) ;
define("_C_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE"  , 102 ) ;
define("_C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED" , 200 ) ;
define("_C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR"  , 201 ) ;

define("_C_WEBPHOTO_FILE_KIND_CONT"          , 1 ) ;
define("_C_WEBPHOTO_FILE_KIND_THUMB"         , 2 ) ;
define("_C_WEBPHOTO_FILE_KIND_MIDDLE"        , 3 ) ;
define("_C_WEBPHOTO_FILE_KIND_VIDEO_FLASH"   , 4 ) ;
define("_C_WEBPHOTO_FILE_KIND_VIDEO_DOCOMO"  , 5 ) ;

define("_C_WEBPHOTO_ITEM_FILE_CONT"          , 'item_file_id_1' ) ;
define("_C_WEBPHOTO_ITEM_FILE_THUMB"         , 'item_file_id_2' ) ;
define("_C_WEBPHOTO_ITEM_FILE_MIDDLE"        , 'item_file_id_3' ) ;
define("_C_WEBPHOTO_ITEM_FILE_VIDEO_FLASH"   , 'item_file_id_4' ) ;
define("_C_WEBPHOTO_ITEM_FILE_VIDEO_DOCOMO"  , 'item_file_id_5' ) ;

define("_C_WEBPHOTO_PIPEID_GD" ,      0 ) ;
define("_C_WEBPHOTO_PIPEID_IMAGICK" , 1 ) ;
define("_C_WEBPHOTO_PIPEID_NETPBM" ,  2 ) ;

define("_C_WEBPHOTO_IMAGE_READFAULT" , -1 ) ;
define("_C_WEBPHOTO_IMAGE_CREATED" ,    1 ) ;
define("_C_WEBPHOTO_IMAGE_COPIED" ,     2 ) ;
define("_C_WEBPHOTO_IMAGE_SKIPPED" ,    3 ) ;
define("_C_WEBPHOTO_IMAGE_ICON" ,       4 ) ;
define("_C_WEBPHOTO_IMAGE_RESIZE" ,     5 ) ;

define("_C_WEBPHOTO_VIDEO_THUMB_SINGLE"  , 0 ) ;
define("_C_WEBPHOTO_VIDEO_THUMB_PLURAL"  , 1 ) ;

define("_C_WEBPHOTO_VIDEO_FAILED" ,  -1 ) ;
define("_C_WEBPHOTO_VIDEO_CREATED" ,  1 ) ;
define("_C_WEBPHOTO_VIDEO_SKIPPED" ,  2 ) ;

define("_C_WEBPHOTO_UPLOADER_NOT_FOUND"         , 1 ) ;
define("_C_WEBPHOTO_UPLOADER_INVALID_FILE_SIZE" , 2 ) ;
define("_C_WEBPHOTO_UPLOADER_EMPTY_FILE_NAME"   , 3 ) ;
define("_C_WEBPHOTO_UPLOADER_NO_FILE"           , 4 ) ;
define("_C_WEBPHOTO_UPLOADER_NOT_SET_DIR"       , 5 ) ;
define("_C_WEBPHOTO_UPLOADER_NOT_ALLOWED_EXT"   , 6 ) ;
define("_C_WEBPHOTO_UPLOADER_PHP_OCCURED"       , 7 ) ;
define("_C_WEBPHOTO_UPLOADER_NOT_OPEN_DIR"      , 8 ) ;
define("_C_WEBPHOTO_UPLOADER_NO_PERM_DIR"       , 9 ) ;
define("_C_WEBPHOTO_UPLOADER_NOT_ALLOWED_MIME"  , 10 ) ;
define("_C_WEBPHOTO_UPLOADER_LARGE_FILE_SIZE"   , 11 ) ;
define("_C_WEBPHOTO_UPLOADER_LARGE_WIDTH"       , 12 ) ;
define("_C_WEBPHOTO_UPLOADER_LARGE_HEIGHT"      , 13 ) ;
define("_C_WEBPHOTO_UPLOADER_UPLOAD"            , 14 ) ;

define("_C_WEBPHOTO_UPLOADER_PREFIX"      , "tmp_" ) ;
define("_C_WEBPHOTO_UPLOADER_PREFIX_PREV" , "tmp_prev_" ) ;
define("_C_WEBPHOTO_VIDEO_THUMB_PREFIX"   , "tmp_video_" ) ;

define("_C_WEBPHOTO_STATUS_WAITING" ,  0 ) ;
define("_C_WEBPHOTO_STATUS_APPROVED" , 1 ) ;
define("_C_WEBPHOTO_STATUS_UPDATED" ,  2 ) ;
define("_C_WEBPHOTO_STATUS_OFFLINE" ,  -1 ) ;
define("_C_WEBPHOTO_STATUS_EXPIRED" ,  -2 ) ;

define("_C_WEBPHOTO_RETRIEVE_CODE_ACCESS_TIME"  , -1 ) ;
define("_C_WEBPHOTO_RETRIEVE_CODE_NOT_RETRIEVE" , -2 ) ;
define("_C_WEBPHOTO_RETRIEVE_CODE_NO_NEW"       , -3 ) ;

define("_C_WEBPHOTO_MAILLOG_STATUS_REJECT" ,  0 ) ;
define("_C_WEBPHOTO_MAILLOG_STATUS_PARTIAL" , 1 ) ;
define("_C_WEBPHOTO_MAILLOG_STATUS_SUBMIT" ,  2 ) ;

define("_C_WEBPHOTO_MSG_LEVEL_NON" ,    0 ) ;
define("_C_WEBPHOTO_MSG_LEVEL_ALL" ,    1 ) ;
define("_C_WEBPHOTO_MSG_LEVEL_USER" ,   2 ) ;
define("_C_WEBPHOTO_MSG_LEVEL_ADMIN" ,  3 ) ;

define("_C_WEBPHOTO_NO" ,  0 ) ;
define("_C_WEBPHOTO_YES" , 1 ) ;

define("_C_WEBPHOTO_DATETIME_STR_NOT_SET" , "0000" ) ;

define("_C_WEBPHOTO_PLACE_STR_NOT_SET"   , "----" ) ;
define("_C_WEBPHOTO_PLACE_VALUE_NOT_SET" , '' ) ;

define("_C_WEBPHOTO_TAG_SEPARATOR" , "," ) ;

define("_C_WEBPHOTO_PERM_ALLOW_ALL" , "*" ) ;
define("_C_WEBPHOTO_PERM_DENOY_ALL" , "x" ) ;
define("_C_WEBPHOTO_PERM_SEPARATOR" , "&" ) ;

define("_C_WEBPHOTO_GMAP_ZOOM" , "12" ) ;

// v0.50
define("_C_WEBPHOTO_FLASH_VERSION", "9");

define("_C_WEBPHOTO_DATETIME_DEFAULT",  "0000-00-00 00:00:00");
define("_C_WEBPHOTO_PLAYLIST_TIME_DEFAULT", "604800");

define("_C_WEBPHOTO_DISPLAYTYPE_GENERAL",      "0");
define("_C_WEBPHOTO_DISPLAYTYPE_IMAGE",        "1");
define("_C_WEBPHOTO_DISPLAYTYPE_EMBED",        "10");
define("_C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT",    "20");
define("_C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER",  "21");
define("_C_WEBPHOTO_DISPLAYTYPE_IMAGEROTATOR", "22");

define("_C_WEBPHOTO_ONCLICK_PAGE",   "0");
define("_C_WEBPHOTO_ONCLICK_DIRECT", "1");
define("_C_WEBPHOTO_ONCLICK_POPUP" , "2");

define("_C_WEBPHOTO_SHOWINFO_DEFAULT", "1|2|3|4|5|6|7|8|9|10");
define("_C_WEBPHOTO_SHOWINFO_DESCRIPTION", "1");
define("_C_WEBPHOTO_SHOWINFO_LOGOIMAGE",   "2");
define("_C_WEBPHOTO_SHOWINFO_CREDITS",     "3");
define("_C_WEBPHOTO_SHOWINFO_STATISTICS",  "4");
define("_C_WEBPHOTO_SHOWINFO_SUBMITTER",   "5");
define("_C_WEBPHOTO_SHOWINFO_POPUP",       "6");
define("_C_WEBPHOTO_SHOWINFO_TAGS",        "7");
define("_C_WEBPHOTO_SHOWINFO_DOWNLOAD",    "8");
define("_C_WEBPHOTO_SHOWINFO_WEBSITE",     "9");
define("_C_WEBPHOTO_SHOWINFO_WEBFEED",    "10");

define("_C_WEBPHOTO_PLAYER_ID_NONE",     "0");
define("_C_WEBPHOTO_PLAYER_ID_DEFAULT",  "1");
define("_C_WEBPHOTO_PLAYER_ID_PLAYLIST", "2");

define("_C_WEBPHOTO_PLAYER_HEIGHT_DEFAULT",  "240");
define("_C_WEBPHOTO_PLAYER_WIDTH_DEFAULT",   "320");
define("_C_WEBPHOTO_PLAYER_HEIGHT_PLAYLIST", "340");
define("_C_WEBPHOTO_PLAYER_WIDTH_PLAYLIST",  "320");
define("_C_WEBPHOTO_PLAYER_DISPLAYHEIGHT_PLAYLIST", "240");
define("_C_WEBPHOTO_PLAYER_DISPLAYWIDTH_PLAYLIST",  "320");

define("_C_WEBPHOTO_EMBED_HEIGHT_DEFAULT", "240");
define("_C_WEBPHOTO_EMBED_WIDTH_DEFAULT",  "320");

define("_C_WEBPHOTO_PLAYLIST_TYPE_NONE",  "0");
define("_C_WEBPHOTO_PLAYLIST_TYPE_IMAGE", "1");
define("_C_WEBPHOTO_PLAYLIST_TYPE_AUDIO", "2");
define("_C_WEBPHOTO_PLAYLIST_TYPE_VIDEO", "3");
define("_C_WEBPHOTO_PLAYLIST_TYPE_FLASH", "4");

define("_C_WEBPHOTO_FLASHVAR_LINK_TYPE_NONE", "0");
define("_C_WEBPHOTO_FLASHVAR_LINK_TYPE_SITE", "1");
define("_C_WEBPHOTO_FLASHVAR_LINK_TYPE_PAGE", "2");
define("_C_WEBPHOTO_FLASHVAR_LINK_TYPE_FILE", "3");

define("_C_WEBPHOTO_FLASHVAR_AUTOSTART_DEFAULT"   , "2");
define("_C_WEBPHOTO_FLASHVAR_BUFFERLENGTH_DEFAULT", "3");
define("_C_WEBPHOTO_FLASHVAR_ROTATETIME_DEFAULT",   "5");
define("_C_WEBPHOTO_FLASHVAR_VOLUME_DEFAULT",       "80");
define("_C_WEBPHOTO_FLASHVAR_LINKTARGET_DEFAULT",   "_self");
define("_C_WEBPHOTO_FLASHVAR_OVERSTRETCH_DEFAULT",  "false");
define("_C_WEBPHOTO_FLASHVAR_TRANSITION_DEFAULT",   "random");

define("_C_WEBPHOTO_PLAYER_STYLE_MONO",   "0");
define("_C_WEBPHOTO_PLAYER_STYLE_THEME",  "1");
define("_C_WEBPHOTO_PLAYER_STYLE_PLAYER", "2");
define("_C_WEBPHOTO_PLAYER_STYLE_PAGE",   "3");

define("_C_WEBPHOTO_PLAYERLOGO_SIZE" , "30000" ) ;	// 30 KB

define("_C_WEBPHOTO_RET_ERROR",      -1 ) ;
define("_C_WEBPHOTO_RET_SUCCESS",     1 ) ;
define("_C_WEBPHOTO_RET_VIDEO_FORM",  2 ) ;

// v0.60
define("_C_WEBPHOTO_UPLOAD_FIELD_PHOTO",    "file_photo");
define("_C_WEBPHOTO_UPLOAD_FIELD_THUMB",    "file_thumb");
define("_C_WEBPHOTO_UPLOAD_FIELD_MIDDLE",   "file_middle");
define("_C_WEBPHOTO_UPLOAD_FIELD_CATEGORY", "file_category");
define("_C_WEBPHOTO_UPLOAD_FIELD_GICON",    "file_gicon");
define("_C_WEBPHOTO_UPLOAD_FIELD_GSHADOW",  "file_gshadow");
define("_C_WEBPHOTO_UPLOAD_FIELD_PLOGO",    "file_plogo");

// v0.70
define("_C_WEBPHOTO_EMBED_NAME_GENERAL",  "general" ) ;
define("_C_WEBPHOTO_INFO_SEPARATOR" , "|" ) ;

define("_C_WEBPHOTO_CODEINFO_DEFAULT", "1|2|3|4|101|102|103|104|105");
define("_C_WEBPHOTO_CODEINFO_CONT",   "1");
define("_C_WEBPHOTO_CODEINFO_THUMB",  "2");
define("_C_WEBPHOTO_CODEINFO_MIDDLE", "3");
define("_C_WEBPHOTO_CODEINFO_FLASH",  "4");
define("_C_WEBPHOTO_CODEINFO_DOCOMO", "5");
define("_C_WEBPHOTO_CODEINFO_PAGE",   "101");
define("_C_WEBPHOTO_CODEINFO_SITE",   "102");
define("_C_WEBPHOTO_CODEINFO_PLAY",   "103");
define("_C_WEBPHOTO_CODEINFO_EMBED",  "104");
define("_C_WEBPHOTO_CODEINFO_JS",     "105");

define("_C_WEBPHOTO_PLAYLIST_TIME_HOUR",  "3600");
define("_C_WEBPHOTO_PLAYLIST_TIME_DAY",   "86400");
define("_C_WEBPHOTO_PLAYLIST_TIME_WEEK",  "604800");
define("_C_WEBPHOTO_PLAYLIST_TIME_MONTH", "2592000");

define("_C_WEBPHOTO_SMALL_WIDTH",  "60");
define("_C_WEBPHOTO_SMALL_HEIGHT", "40");
define("_C_WEBPHOTO_SMALL_CURRENT_WIDTH",  "80");
define("_C_WEBPHOTO_SMALL_CURRENT_HEIGHT", "80");

// v0.80
define("_C_WEBPHOTO_VODEO_THUMB_PLURAL_MAX",  "5" ) ;

// v0.90
define("_C_WEBPHOTO_OPT_PERM_READ_ALL"     , 0 ) ;
define("_C_WEBPHOTO_OPT_PERM_READ_NO_ITEM" , 1 ) ;
define("_C_WEBPHOTO_OPT_PERM_READ_NO_CAT"  , 2 ) ;

// error code
define("_C_WEBPHOTO_ERR_NO_PERM",         -101 ) ;
define("_C_WEBPHOTO_ERR_NO_RECORD",       -102 ) ;
define("_C_WEBPHOTO_ERR_TOKEN",           -103 ) ;
define("_C_WEBPHOTO_ERR_DB",              -104 ) ;
define("_C_WEBPHOTO_ERR_UPLOAD",          -105 ) ;
define("_C_WEBPHOTO_ERR_FILE",            -106 ) ;
define("_C_WEBPHOTO_ERR_FILEREAD",        -107 ) ;
define("_C_WEBPHOTO_ERR_NO_SPECIFIED",    -108 ) ;
define("_C_WEBPHOTO_ERR_NO_IMAGE",        -109 ) ;
define("_C_WEBPHOTO_ERR_NO_TITLE",        -110 ) ;
define("_C_WEBPHOTO_ERR_CHECK_DIR",       -111 ) ;
define("_C_WEBPHOTO_ERR_NOT_ALLOWED_EXT", -112 ) ;

// v0.30
define("_C_WEBPHOTO_ERR_EMPTY_FILE",      -113 ) ;
define("_C_WEBPHOTO_ERR_EMPTY_CAT",       -114 ) ;
define("_C_WEBPHOTO_ERR_INVALID_CAT",     -115 ) ;
define("_C_WEBPHOTO_ERR_NO_CAT_RECORD",   -116 ) ;
define("_C_WEBPHOTO_ERR_EXT",             -117 ) ;
define("_C_WEBPHOTO_ERR_FILE_SIZE",       -118 ) ;
define("_C_WEBPHOTO_ERR_CREATE_PHOTO",    -119 ) ;
define("_C_WEBPHOTO_ERR_CREATE_THUMB",    -120 ) ;

// v0.40
define("_C_WEBPHOTO_ERR_GET_IMAGE_SIZE",  -121 ) ;

// v0.50
define("_C_WEBPHOTO_ERR_EMBED",       -122 ) ;
define("_C_WEBPHOTO_ERR_PLAYLIST",    -123 ) ;
define("_C_WEBPHOTO_ERR_NO_FALSHVAR", -124 ) ;

// v0.81
define("_C_WEBPHOTO_ERR_VOTE_OWN",   -125 ) ;
define("_C_WEBPHOTO_ERR_VOTE_ONCE",  -126 ) ;
define("_C_WEBPHOTO_ERR_NO_RATING",  -127 ) ;

// for Japanese
define("_C_WEBPHOTO_JPAPANESE" , "japanese|japaneseutf|ja_utf8" ) ;

// === define end ===
}

?>