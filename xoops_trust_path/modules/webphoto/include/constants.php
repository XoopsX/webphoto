<?php
// $Id: constants.php,v 1.4 2008/08/08 04:36:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
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
define("_C_WEBPHOTO_CFG_OPT_PERPAGE" , "10|20|50|100" ) ;

define("_C_WEBPHOTO_CAT_MAIN_WIDTH_DEFAULT" ,  120 ) ;
define("_C_WEBPHOTO_CAT_MAIN_HEIGHT_DEFAULT" , 120 ) ;
define("_C_WEBPHOTO_CAT_SUB_WIDTH_DEFAULT" ,   50 ) ;
define("_C_WEBPHOTO_CAT_SUB_HEIGHT_DEFAULT" ,  50 ) ;

define("_C_WEBPHOTO_MAX_PHOTO_TEXT" , 10 ) ;
define("_C_WEBPHOTO_MAX_CAT_TEXT" ,   5 ) ;

// v0.30
define("_C_WEBPHOTO_MAX_USER_TEXT" ,  5 ) ;

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

// for Japanese
define("_C_WEBPHOTO_JPAPANESE" , "japanese|japaneseutf|ja_utf8" ) ;

// === define end ===
}

?>