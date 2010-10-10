<?php
// $Id: blocks.php,v 1.10 2010/10/10 11:02:10 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// 写真 -> 写真・動画・メディア
// 2010-04-04 K.OHWADA
// use $mydirname
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

$constpref = strtoupper( '_BL_' . $MY_DIRNAME. '_' ) ;

// === define begin ===
if( !defined($constpref."LANG_LOADED") ) 
{

define($constpref."LANG_LOADED" , 1 ) ;

//=========================================================
// same as myalbum
//=========================================================

define($constpref."BTITLE_TOPNEW","最新の写真・動画・メディア");
define($constpref."BTITLE_TOPHIT","ヒット数の多い写真・動画・メディア");
define($constpref."BTITLE_RANDOM","ピックアップの写真・動画・メディア");
define($constpref."TEXT_DISP","表示数");
define($constpref."TEXT_STRLENGTH","写真・動画・メディアのタイトルの最大表示文字数");
define($constpref."TEXT_CATLIMITATION","カテゴリ限定");

// v2.30
define($constpref."TEXT_CATLIMITRECURSIVE","サブカテゴリも対象<br />カテゴリ限定のとき有効");

define($constpref."TEXT_BLOCK_WIDTH","最大表示サイズ");
define($constpref."TEXT_BLOCK_WIDTH_NOTES","（※ ここを0にした場合、サムネイル画像をそのままのサイズで表示します）");
define($constpref."TEXT_RANDOMCYCLE","画像の切り替え周期（単位は秒）");
define($constpref."TEXT_COLS","写真・動画・メディアの列数");

//---------------------------------------------------------
// v0.20
//---------------------------------------------------------
define($constpref."POPBOX_REVERT", "クリックすると、元の小さい画像になる");

//---------------------------------------------------------
// v0.30
//---------------------------------------------------------
define($constpref."TEXT_CACHETIME", "キャッシュ時間");

//---------------------------------------------------------
// v0.80
//---------------------------------------------------------
define($constpref."TEXT_CATLIST_SUB", "サブカテゴリの表示");
define($constpref."TEXT_CATLIST_MAIN_IMG", "メインカテゴリの写真・動画・メディアの表示");
define($constpref."TEXT_CATLIST_SUB_IMG", "サブカテゴリの写真・動画・メディアの表示");
define($constpref."TEXT_CATLIST_COLS", "横に並べるカテゴリの数");
define($constpref."TEXT_TAGCLOUD_LIMIT", "タグの表示する数");

//---------------------------------------------------------
// v1.20
//---------------------------------------------------------
// google map
define($constpref."GMAP_MODE","GoogleMap モード");
define($constpref."GMAP_MODE_NONE","非表示");
define($constpref."GMAP_MODE_DEFAULT","デフォルト");
define($constpref."GMAP_MODE_SET","下記の設定値");
define($constpref."GMAP_LATITUDE","緯度");
define($constpref."GMAP_LONGITUDE","経度");
define($constpref."GMAP_ZOOM","ズーム");
define($constpref."GMAP_HEIGHT","表示の高さ");
define($constpref."PIXEL", "ピクセル");

//---------------------------------------------------------
// v1.40
//---------------------------------------------------------
define($constpref."TIMELINE_LATEST", "新しい方から表示する写真・動画・メディアの数");
define($constpref."TIMELINE_RANDOM", "ランダムに表示する写真・動画・メディアの数");
define($constpref."TIMELINE_HEIGHT","表示の高さ");
define($constpref."TIMELINE_SCALE", "タイムラインの時間幅") ;
define($constpref."TIMELINE_SCALE_WEEK",   "１週間") ;
define($constpref."TIMELINE_SCALE_MONTH",  "１ヶ月") ;
define($constpref."TIMELINE_SCALE_YEAR",   "１年") ;
define($constpref."TIMELINE_SCALE_DECADE", "１０年") ;

// === define end ===
}

?>