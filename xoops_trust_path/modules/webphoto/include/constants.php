<?php
// $Id: constants.php,v 1.34 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2011-05-01 K.OHWADA
// JW Player 5.2
// 2010-09-20 K.OHWADA
// _C_WEBPHOTO_FILE_KIND_WAV
// 2009-10-25 K.OHWADA
// _C_WEBPHOTO_FILE_KIND_JPEG
// 2009-01-04 K.OHWADA
// _C_WEBPHOTO_EDITOR_DEFAULT
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

// v1.00
define("_B_WEBPHOTO_GPERM_HTML",           16368 ) ;

define("_C_WEBPHOTO_GPERM_NAME", "webphoto" ) ;

// v2.12
define("_C_WEBPHOTO_MYSQL_TEXT_LENGTH",       "65000" ) ;	// 64KB
define("_C_WEBPHOTO_MYSQL_MEDIUMTEXT_LENGTH", "16000000" ) ;	// 16MB

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

//define("_C_WEBPHOTO_MAX_ITEM_FILE_ID" , 10 ) ;
define("_C_WEBPHOTO_MAX_ITEM_FILE_ID" , 20 ) ;

define("_C_WEBPHOTO_MAX_ITEM_TEXT"    , 10 ) ;

define("_C_WEBPHOTO_ITEM_KIND_UNDEFINED", 0 ) ;
define("_C_WEBPHOTO_ITEM_KIND_NONE",      1 ) ;
define("_C_WEBPHOTO_ITEM_KIND_GENERAL",  10 ) ;
define("_C_WEBPHOTO_ITEM_KIND_IMAGE",    11 ) ;
define("_C_WEBPHOTO_ITEM_KIND_VIDEO",    12 ) ;
define("_C_WEBPHOTO_ITEM_KIND_AUDIO",    13 ) ;

// v1.90
define("_C_WEBPHOTO_ITEM_KIND_OFFICE",      14 ) ;
define("_C_WEBPHOTO_ITEM_KIND_IMAGE_OTHER", 15 ) ;

// v2.30
define("_C_WEBPHOTO_ITEM_KIND_VIDEO_H264",  16 ) ;
define("_C_WEBPHOTO_ITEM_KIND_IMAGE_CMYK",  17 ) ;

define("_C_WEBPHOTO_ITEM_KIND_EMBED",   100 ) ;
define("_C_WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL", 101 ) ;
define("_C_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE"  , 102 ) ;
define("_C_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED" , 200 ) ;
define("_C_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR"  , 201 ) ;

// v2.11
define("_C_WEBPHOTO_ITEM_KIND_GROUP_UNDEFINED", 0 ) ;
define("_C_WEBPHOTO_ITEM_KIND_GROUP_IMAGE",  1 ) ;
define("_C_WEBPHOTO_ITEM_KIND_GROUP_VIDEO",  2 ) ;
define("_C_WEBPHOTO_ITEM_KIND_GROUP_AUDIO",  3 ) ;
define("_C_WEBPHOTO_ITEM_KIND_GROUP_OFFICE", 4 ) ;
define("_C_WEBPHOTO_ITEM_KIND_GROUP_OTHERS", 9999 ) ;

// v1.80
//define("_C_WEBPHOTO_MIME_KIND_LIST_IMAGE", "10|11" ) ;
//define("_C_WEBPHOTO_MIME_KIND_LIST_VIDEO", "20|21" ) ;
//define("_C_WEBPHOTO_MIME_KIND_LIST_AUDIO", "30|31|32" ) ;

define("_C_WEBPHOTO_MIME_KIND_GENERAL",        0 ) ;
define("_C_WEBPHOTO_MIME_KIND_IMAGE",         10 ) ;
define("_C_WEBPHOTO_MIME_KIND_IMAGE_CONVERT", 11 ) ;

// v2.30
define("_C_WEBPHOTO_MIME_KIND_IMAGE_JPEG",    12 ) ;

define("_C_WEBPHOTO_MIME_KIND_VIDEO",         20 ) ;
define("_C_WEBPHOTO_MIME_KIND_VIDEO_FFMPEG",  21 ) ;

// v2.30
define("_C_WEBPHOTO_MIME_KIND_VIDEO_FLV",     22 ) ;

define("_C_WEBPHOTO_MIME_KIND_AUDIO",         30 ) ;
define("_C_WEBPHOTO_MIME_KIND_AUDIO_MID",     31 ) ;
define("_C_WEBPHOTO_MIME_KIND_AUDIO_WAV",     32 ) ;

// v2.30
define("_C_WEBPHOTO_MIME_KIND_AUDIO_MP3",     33 ) ;
define("_C_WEBPHOTO_MIME_KIND_AUDIO_FFMPEG",  34 ) ;

define("_C_WEBPHOTO_MIME_KIND_OFFICE",        40 ) ;

// v1.90
define("_C_WEBPHOTO_MIME_KIND_OFFICE_DOC",    41 ) ;
define("_C_WEBPHOTO_MIME_KIND_OFFICE_XLS",    42 ) ;
define("_C_WEBPHOTO_MIME_KIND_OFFICE_PPT",    43 ) ;
define("_C_WEBPHOTO_MIME_KIND_OFFICE_PDF",    44 ) ;

// v1.80 v2.30
define("_C_WEBPHOTO_FILE_KIND_NAME_LIST", "none|cont|thumb|middle|flash|docomo|pdf|swf|small|jpeg|mp3|wav|large" ) ;
define("_C_WEBPHOTO_FILE_LIST", "cont|thumb|large|middle|small|flash|docomo|pdf|swf|jpeg|mp3|wav|large" ) ;

define("_C_WEBPHOTO_FILE_KIND_CONT"          , 1 ) ;
define("_C_WEBPHOTO_FILE_KIND_THUMB"         , 2 ) ;
define("_C_WEBPHOTO_FILE_KIND_MIDDLE"        , 3 ) ;
define("_C_WEBPHOTO_FILE_KIND_FLASH"         , 4 ) ;
define("_C_WEBPHOTO_FILE_KIND_DOCOMO"        , 5 ) ;
define("_C_WEBPHOTO_FILE_KIND_VIDEO_FLASH"   , 4 ) ;
define("_C_WEBPHOTO_FILE_KIND_VIDEO_DOCOMO"  , 5 ) ;

// v1.10
define("_C_WEBPHOTO_FILE_KIND_PDF"           , 6 ) ;

// v1.20
define("_C_WEBPHOTO_FILE_KIND_SWF"           , 7 ) ;

// v1.30
define("_C_WEBPHOTO_FILE_KIND_SMALL"         , 8 ) ;

// v1.80
define("_C_WEBPHOTO_FILE_KIND_JPEG"          , 9 ) ;
define("_C_WEBPHOTO_FILE_KIND_MP3"           , 10 ) ;

// v2.30
define("_C_WEBPHOTO_FILE_KIND_WAV"           , 11 ) ;
define("_C_WEBPHOTO_FILE_KIND_LARGE"         , 12 ) ;

define("_C_WEBPHOTO_ITEM_FILE_CONT"          , 'item_file_id_1' ) ;
define("_C_WEBPHOTO_ITEM_FILE_THUMB"         , 'item_file_id_2' ) ;
define("_C_WEBPHOTO_ITEM_FILE_MIDDLE"        , 'item_file_id_3' ) ;
define("_C_WEBPHOTO_ITEM_FILE_VIDEO_FLASH"   , 'item_file_id_4' ) ;
define("_C_WEBPHOTO_ITEM_FILE_VIDEO_DOCOMO"  , 'item_file_id_5' ) ;
define("_C_WEBPHOTO_ITEM_FILE_FLASH"         , 'item_file_id_4' ) ;
define("_C_WEBPHOTO_ITEM_FILE_DOCOMO"        , 'item_file_id_5' ) ;

// v1.10
define("_C_WEBPHOTO_ITEM_FILE_PDF"           , 'item_file_id_6' ) ;

// v1.20
define("_C_WEBPHOTO_ITEM_FILE_SWF"           , 'item_file_id_7' ) ;

// v1.30
define("_C_WEBPHOTO_ITEM_FILE_SMALL"         , 'item_file_id_8' ) ;

// v1.80
define("_C_WEBPHOTO_ITEM_FILE_JPEG"          , 'item_file_id_9' ) ;
define("_C_WEBPHOTO_ITEM_FILE_MP3"           , 'item_file_id_10' ) ;

// v2.30
define("_C_WEBPHOTO_ITEM_FILE_WAV"          , 'item_file_id_11' ) ;
define("_C_WEBPHOTO_ITEM_FILE_LARGE"        , 'item_file_id_12' ) ;

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

// v1.30
define("_C_WEBPHOTO_UPLOADER_PREFIX_ROT" ,  "tmp_rot_" ) ;

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

//define("_C_WEBPHOTO_DATETIME_DEFAULT",  "0000-00-00 00:00:00");
//define("_C_WEBPHOTO_PLAYLIST_TIME_DEFAULT", "604800");

define("_C_WEBPHOTO_DISPLAYTYPE_GENERAL",      "0");
define("_C_WEBPHOTO_DISPLAYTYPE_IMAGE",        "1");
define("_C_WEBPHOTO_DISPLAYTYPE_EMBED",        "10");
define("_C_WEBPHOTO_DISPLAYTYPE_SWFOBJECT",    "20");
define("_C_WEBPHOTO_DISPLAYTYPE_MEDIAPLAYER",  "21");
define("_C_WEBPHOTO_DISPLAYTYPE_IMAGEROTATOR", "22");

// v2.30
define("_C_WEBPHOTO_DISPLAYFILE_DEFAULT",      "0");

define("_C_WEBPHOTO_ONCLICK_PAGE",   "0");
define("_C_WEBPHOTO_ONCLICK_DIRECT", "1");
define("_C_WEBPHOTO_ONCLICK_POPUP" , "2");

//define("_C_WEBPHOTO_SHOWINFO_DEFAULT", "1|2|3|4|5|6|7|8|9|10");

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

//define("_C_WEBPHOTO_PLAYER_HEIGHT_DEFAULT",  "240");
//define("_C_WEBPHOTO_PLAYER_WIDTH_DEFAULT",   "320");

define("_C_WEBPHOTO_PLAYER_HEIGHT_PLAYLIST", "340");
define("_C_WEBPHOTO_PLAYER_WIDTH_PLAYLIST",  "320");
define("_C_WEBPHOTO_PLAYER_DISPLAYHEIGHT_PLAYLIST", "240");
define("_C_WEBPHOTO_PLAYER_DISPLAYWIDTH_PLAYLIST",  "320");

define("_C_WEBPHOTO_EMBED_HEIGHT_DEFAULT", "240");
define("_C_WEBPHOTO_EMBED_WIDTH_DEFAULT",  "320");

// v2.20
define("_C_WEBPHOTO_EMBED_REPLACE_WIDTH",  '{$w-width}' ) ;
define("_C_WEBPHOTO_EMBED_REPLACE_HEIGHT", '{$w-height}' ) ;

define("_C_WEBPHOTO_PLAYLIST_TYPE_NONE",  "0");
define("_C_WEBPHOTO_PLAYLIST_TYPE_IMAGE", "1");
define("_C_WEBPHOTO_PLAYLIST_TYPE_AUDIO", "2");
define("_C_WEBPHOTO_PLAYLIST_TYPE_VIDEO", "3");
define("_C_WEBPHOTO_PLAYLIST_TYPE_FLASH", "4");

define("_C_WEBPHOTO_FLASHVAR_LINK_TYPE_NONE", "0");
define("_C_WEBPHOTO_FLASHVAR_LINK_TYPE_SITE", "1");
define("_C_WEBPHOTO_FLASHVAR_LINK_TYPE_PAGE", "2");
define("_C_WEBPHOTO_FLASHVAR_LINK_TYPE_FILE", "3");

define("_C_WEBPHOTO_FLASHVAR_ROTATETIME_DEFAULT",   "5");
define("_C_WEBPHOTO_FLASHVAR_LINKTARGET_DEFAULT",   "_self");
define("_C_WEBPHOTO_FLASHVAR_OVERSTRETCH_DEFAULT",  "false");
define("_C_WEBPHOTO_FLASHVAR_TRANSITION_DEFAULT",   "random");

// v2.40
define("_C_WEBPHOTO_FLASHVAR_AUTOSTART_DEFAULT", "false");
define("_C_WEBPHOTO_FLASHVAR_BUFFERLENGTH_DEFAULT", "1");
define("_C_WEBPHOTO_FLASHVAR_VOLUME_DEFAULT",       "90");

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

// v1.30
define("_C_WEBPHOTO_UPLOAD_FIELD_SMALL",    "file_small");

// v2.30
define("_C_WEBPHOTO_UPLOAD_FIELD_JPEG",     "file_jpeg");

// v0.70
define("_C_WEBPHOTO_EMBED_NAME_GENERAL",  "general" ) ;
define("_C_WEBPHOTO_INFO_SEPARATOR" , "|" ) ;

// v1.80 v2.30
//define("_C_WEBPHOTO_CODEINFO_SHOW_LIST", "page|cont|flash|wav|mp3|pdf|swf|jpeg|thumb|middle|site|play|embed|js");
//define("_C_WEBPHOTO_CODEINFO_FILE_LIST", "cont|thumb|middle|flash|docomo|pdf|swf|jpeg|mp3" ) ;
//define("_C_WEBPHOTO_CODEINFO_PAGE_LIST", "page|site|play|embed|js");

//define("_C_WEBPHOTO_CODEINFO_DEFAULT", "1|2|3|4|6|7|8|9|10|101|102|103|104|105");

define("_C_WEBPHOTO_CODEINFO_CONT",   "1");
define("_C_WEBPHOTO_CODEINFO_THUMB",  "2");
define("_C_WEBPHOTO_CODEINFO_MIDDLE", "3");
define("_C_WEBPHOTO_CODEINFO_FLASH",  "4");
define("_C_WEBPHOTO_CODEINFO_DOCOMO", "5");

// v1.20
define("_C_WEBPHOTO_CODEINFO_PDF",    "6");
define("_C_WEBPHOTO_CODEINFO_SWF",    "7");

// v1.30
define("_C_WEBPHOTO_CODEINFO_SMALL",  "8");

// v1.80
define("_C_WEBPHOTO_CODEINFO_JPEG",   "9");
define("_C_WEBPHOTO_CODEINFO_MP3",    "10");

// v2.30
define("_C_WEBPHOTO_CODEINFO_WAV",    "11");
define("_C_WEBPHOTO_CODEINFO_LARGE",  "12");

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
define("_C_WEBPHOTO_VIDEO_THUMB_PLURAL_MAX",  "5" ) ;

// v0.90
define("_C_WEBPHOTO_OPT_PERM_READ_ALL"     , 0 ) ;
define("_C_WEBPHOTO_OPT_PERM_READ_NO_ITEM" , 1 ) ;
define("_C_WEBPHOTO_OPT_PERM_READ_NO_CAT"  , 2 ) ;

// v1.00
define("_C_WEBPHOTO_EDITOR_DEFAULT" , "xoopsdhtml" ) ;
//define("_C_WEBPHOTO_EMBED_TYPE_DEFAULT" , "youtube" ) ;

// v1.50
//define("_C_WEBPHOTO_ROTATE_DEFAULT" , "rot0" ) ;

// v1.60
define("_C_WEBPHOTO_MAX_PHOTO_FILE" , 5 ) ;

// v1.70
define("_C_WEBPHOTO_CAT_CHILD_NON" ,    0 ) ;
define("_C_WEBPHOTO_CAT_CHILD_EMPTY" ,  1 ) ;
define("_C_WEBPHOTO_CAT_CHILD_ALWAYS" , 2 ) ;

// v1.90
define("_C_WEBPHOTO_DETAIL_ONCLICK_DEFAULT",  0 ) ;
define("_C_WEBPHOTO_DETAIL_ONCLICK_IMAGE",    9991 ) ;
define("_C_WEBPHOTO_DETAIL_ONCLICK_LIGHTBOX", 9992 ) ;

// v2.30
define("_C_WEBPHOTO_DETAIL_ONCLICK_DOWNLOAD", 9981 ) ;

// v2.00
define("_C_WEBPHOTO_PERM_LEVEL_PUBLIC", 0 ) ;
define("_C_WEBPHOTO_PERM_LEVEL_GROUP", 1 ) ;

// v2.10
define("_C_WEBPHOTO_UID_DEFAULT", -1 ) ;

// v2.31
define("_C_WEBPHOTO_URI_PARAM_NAME",  "p" ) ;
define("_C_WEBPHOTO_URI_PATH_FIRST",  0 ) ;
define("_C_WEBPHOTO_URI_PATH_SECOND", 1 ) ;

// v2.40
// JW Player 5.2
define("_C_WEBPHOTO_FLASHVAR_SHUFFLE_DEFAULT",   "0");
define("_C_WEBPHOTO_FLASHVAR_MUTE_DEFAULT",      "0");
define("_C_WEBPHOTO_FLASHVAR_SMOOTHING_DEFAULT", "1");
define("_C_WEBPHOTO_FLASHVAR_DOCK_DEFAULT",      "1");
define("_C_WEBPHOTO_FLASHVAR_ICONS_DEFAULT",     "1");
define("_C_WEBPHOTO_FLASHVAR_STRETCHING_DEFAULT",   "uniform");
define("_C_WEBPHOTO_FLASHVAR_CONTROLBAR_IDLEHIDE_DEFAULT", "0");
define("_C_WEBPHOTO_FLASHVAR_CONTROLBAR_POSITION_DEFAULT", "bottom");
define("_C_WEBPHOTO_FLASHVAR_DISPLAY_SHOWMUTE_DEFAULT", "0");
define("_C_WEBPHOTO_FLASHVAR_PLAYLIST_SIZE_DEFAULT", "180");
define("_C_WEBPHOTO_FLASHVAR_PLAYLIST_POSITION_DEFAULT",   "none");
define("_C_WEBPHOTO_FLASHVAR_LOGO_HIDE_DEFAULT",    "1");
define("_C_WEBPHOTO_FLASHVAR_LOGO_MARGIN_DEFAULT",  "8");
define("_C_WEBPHOTO_FLASHVAR_LOGO_TIMEOUT_DEFAULT", "3");
define("_C_WEBPHOTO_FLASHVAR_LOGO_OVER_DEFAULT",    "1");
define("_C_WEBPHOTO_FLASHVAR_LOGO_OUT_DEFAULT",     "0.5");
define("_C_WEBPHOTO_FLASHVAR_LOGO_POSITION_DEFAULT", "bottom-left");
define("_C_WEBPHOTO_FLASHVAR_LOGO_LINKTARGET_DEFAULT", "_blank");
define("_C_WEBPHOTO_FLASHVAR_PLAYER_REPEAT_DEFAULT", "none");
define("_C_WEBPHOTO_FLASHVAR_PLAYER_REPEAT_ALWAYS",  "always");

// v2.60
define("_C_WEBPHOTO_TIMELINE_SCALE_NONE",   99 ) ;

// v1.80
//define("_C_WEBPHOTO_COMMUNITY_USE" ,    0 ) ;

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

// v2.40
define("_C_WEBPHOTO_IS_PLAYLIST",  100 ) ;

// v1.90
define("_C_WEBPHOTO_NAME_DEBUG_SQL" ,   "debug_sql" ) ;
define("_C_WEBPHOTO_NAME_DEBUG_ERROR" , "debug_error" ) ;

// for Japanese
define("_C_WEBPHOTO_JPAPANESE" , "japanese|japaneseutf|ja_utf8" ) ;

// === define end ===
}

?>