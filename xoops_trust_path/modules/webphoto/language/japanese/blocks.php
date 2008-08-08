<?php
// $Id: blocks.php,v 1.3 2008/08/08 04:36:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

$constpref = strtoupper( '_BL_' . $GLOBALS['MY_DIRNAME']. '_' ) ;

// === define begin ===
if( !defined($constpref."LANG_LOADED") ) 
{

define($constpref."LANG_LOADED" , 1 ) ;

//=========================================================
// same as myalbum
//=========================================================

define($constpref."BTITLE_TOPNEW","最新の画像");
define($constpref."BTITLE_TOPHIT","ヒット数の多い画像");
define($constpref."BTITLE_RANDOM","ピックアップ画像");
define($constpref."TEXT_DISP","表示数");
define($constpref."TEXT_STRLENGTH","画像名の最大表示文字数");
define($constpref."TEXT_CATLIMITATION","カテゴリ限定");
define($constpref."TEXT_CATLIMITRECURSIVE","サブカテゴリも対象");
define($constpref."TEXT_BLOCK_WIDTH","最大表示サイズ");
define($constpref."TEXT_BLOCK_WIDTH_NOTES","（※ ここを0にした場合、サムネイル画像をそのままのサイズで表示します）");
define($constpref."TEXT_RANDOMCYCLE","画像の切り替え周期（単位は秒）");
define($constpref."TEXT_COLS","画像の列数");

//---------------------------------------------------------
// v0.20
//---------------------------------------------------------
define($constpref."POPBOX_REVERT", "クリックすると、元の小さい写真になる");

//---------------------------------------------------------
// v0.30
//---------------------------------------------------------
define($constpref."TEXT_CACHETIME", "キャッシュ時間");

// === define end ===
}

?>