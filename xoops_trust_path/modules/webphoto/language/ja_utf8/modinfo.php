<?php
// $Id: modinfo.php,v 1.1 2008/06/22 05:25:42 ohwada Exp $

//=========================================================
// webphoto module
// UFT-8 for Japanese
// 2008-04-02 K.OHWADA
//=========================================================

$constpref = strtoupper( '_MI_' . $GLOBALS['MY_DIRNAME']. '_' ) ;

// === define begin ===
if( !defined($constpref."LANG_LOADED") ) 
{

define($constpref."LANG_LOADED" , 1 ) ;

//=========================================================
// same as myalbum
//=========================================================

// The name of this module
define($constpref."NAME","WEB 写真集");

// A brief description of this module
define($constpref."DESC","検索・投稿・ランクその他の機能を持つ画像セクションを生成");

// Names of blocks for this module (Not all module has blocks)
define($constpref."BNAME_RECENT","最近の画像");
define($constpref."BNAME_HITS","人気画像");
define($constpref."BNAME_RANDOM","ピックアップ画像");
define($constpref."BNAME_RECENT_P","最近の画像(画像付)");
define($constpref."BNAME_HITS_P","人気画像(画像付)");

// Config Items
define( $constpref."CFG_PHOTOSPATH" , "画像ファイルの保存先ディレクトリ" ) ;
define( $constpref."CFG_DESCPHOTOSPATH" , "XOOPSインストール先からのパスを指定（最初の'/'は必要、最後の'/'は不要）<br />Unixではこのディレクトリへの書込属性をONにして下さい" ) ;
define( $constpref."CFG_THUMBSPATH" , "サムネイルファイルの保存先ディレクトリ" ) ;
define( $constpref."CFG_DESCTHUMBSPATH" , "「画像ファイルの保存先ディレクトリ」と同じです" ) ;
// define( $constpref."CFG_USEIMAGICK" , "画像処理にImageMagickを使う" ) ;
// define( $constpref."CFG_DESCIMAGICK" , "使わない場合は、メイン画像の調整は機能せず、サムネイルの生成にGDを使います。<br />可能であればImageMagickの使用が最善です" ) ;
define( $constpref."CFG_IMAGINGPIPE" , "画像処理を行わせるパッケージ選択" ) ;
define( $constpref."CFG_DESCIMAGINGPIPE" , "ほとんどのPHP環境で標準的に利用可能なのはGDですが機能的に劣ります<br />可能であればImageMagickかNetPBMの使用をお勧めします" ) ;
define( $constpref."CFG_FORCEGD2" , "強制GD2モード" ) ;
define( $constpref."CFG_DESCFORCEGD2" , "強制的にGD2モードで動作させます<br />一部のPHPでは強制GD2モードでサムネイル作成に失敗します<br />画像処理パッケージとしてGDを選択した時のみ意味を持ちます" ) ;
define( $constpref."CFG_IMAGICKPATH" , "ImageMagickの実行パス" ) ;
define( $constpref."CFG_DESCIMAGICKPATH" , "convertの存在するディレクトリをフルパスで指定しますが、空白でうまく行くことが多いでしょう。<br />画像処理パッケージとしてImageMagickを選択した時のみ意味を持ちます" ) ;
define( $constpref."CFG_NETPBMPATH" , "NetPBMの実行パス" ) ;
define( $constpref."CFG_DESCNETPBMPATH" , "pnmscale等の存在するディレクトリをフルパスで指定しますが、空白でうまく行くことが多いでしょう。<br />画像処理パッケージとしてNetPBMを選択した時のみ意味を持ちます" ) ;
define( $constpref."CFG_POPULAR" , "'POP'アイコンがつくために必要なヒット数" ) ;
define( $constpref."CFG_NEWDAYS" , "'new'や'update'アイコンが表示される日数" ) ;
define( $constpref."CFG_NEWPHOTOS" , "トップページで新規画像として表示する数" ) ;

//define( $constpref."CFG_DEFAULTORDER" , "カテゴリ表示でのデフォルト表示順" ) ;

define( $constpref."CFG_PERPAGE" , "1ページに表示される画像数" ) ;
define( $constpref."CFG_DESCPERPAGE" , "選択可能な数字を | で区切って下さい<br />例: 10|20|50|100" ) ;
define( $constpref."CFG_ALLOWNOIMAGE" , "画像のない投稿を許可する" ) ;
define( $constpref."CFG_MAKETHUMB" , "サムネイルを作成する" ) ;
define( $constpref."CFG_DESCMAKETHUMB" , "「生成しない」から「生成する」に変更した時には、「サムネイルの再構築」が必要です。" ) ;

//define( $constpref."CFG_THUMBWIDTH" , "サムネイル画像の幅" ) ;
//define( $constpref."CFG_DESCTHUMBWIDTH" , "生成されるサムネイル画像の高さは、幅から自動計算されます" ) ;
//define( $constpref."CFG_THUMBSIZE" , "サムネイル画像サイズ(pixel)" ) ;

define( $constpref."CFG_THUMBRULE" , "サムネイル生成法則" ) ;
define( $constpref."CFG_WIDTH" , "最大画像幅" ) ;
define( $constpref."CFG_DESCWIDTH" , "画像アップロード時に自動調整されるメイン画像の最大幅。<br />GDモードでTrueColorを扱えない時には単なるサイズ制限" ) ;
define( $constpref."CFG_HEIGHT" , "最大画像高" ) ;
define( $constpref."CFG_DESCHEIGHT" , "最大幅と同じ意味です" ) ;
define( $constpref."CFG_FSIZE" , "最大ファイルサイズ" ) ;
define( $constpref."CFG_DESCFSIZE" , "アップロード時のファイルサイズ制限(byte)" ) ;

//define( $constpref."CFG_MIDDLEPIXEL" , "シングルビューでの最大画像サイズ" ) ;
//define( $constpref."CFG_DESCMIDDLEPIXEL" , "幅x高さ で指定します。<br />（例 480x480）" ) ;

define( $constpref."CFG_ADDPOSTS" , "写真を投稿した時にカウントアップされる投稿数" ) ;
define( $constpref."CFG_DESCADDPOSTS" , "常識的には0か1です。負の値は0と見なされます" ) ;
define( $constpref."CFG_CATONSUBMENU" , "サブメニューへのトップカテゴリーの登録" ) ;
define( $constpref."CFG_NAMEORUNAME" , "投稿者名の表示" ) ;
define( $constpref."CFG_DESCNAMEORUNAME" , "ログイン名かハンドル名か選択して下さい" ) ;
define( $constpref."CFG_VIEWCATTYPE" , "一覧表示の表示タイプ" ) ;

//define( $constpref."CFG_COLSOFTABLEVIEW" , "テーブル表示時のカラム数" ) ;
define( $constpref."CFG_COLSOFTABLE" , "テーブル表示時のカラム数" ) ;

//define( $constpref."CFG_ALLOWEDEXTS" , "アップロード許可するファイル拡張子" ) ;
//define( $constpref."CFG_DESCALLOWEDEXTS" , "ファイルの拡張子を、jpg|jpeg|gif|png のように、'|' で区切って入力して下さい。<br />すべて小文字で指定し、ピリオドや空白は入れないで下さい。<br />意味の判っている方以外は、phpやphtmlなどを追加しないで下さい" ) ;
//define( $constpref."CFG_ALLOWEDMIME" , "アップロード許可するMIMEタイプ" ) ;
//define( $constpref."CFG_DESCALLOWEDMIME" , "MIMEタイプを、image/gif|image/jpeg|image/png のように、'|' で区切って入力して下さい。<br />MIMEタイプによるチェックを行わない時には、ここを空欄にします" ) ;

define( $constpref."CFG_USESITEIMG" , "イメージマネージャ統合での[siteimg]タグ" ) ;
define( $constpref."CFG_DESCUSESITEIMG" , "イメージマネージャ統合で、[img]タグの代わりに[siteimg]タグを挿入するようになります。<br />利用モジュール側で[siteimg]タグが有効に機能するようになっている必要があります" ) ;

define( $constpref."OPT_USENAME" , "ハンドル名" ) ;
define( $constpref."OPT_USEUNAME" , "ログイン名" ) ;

define( $constpref."OPT_CALCFROMWIDTH" , "指定数値を幅として、高さを自動計算" ) ;
define( $constpref."OPT_CALCFROMHEIGHT" , "指定数値を高さとして、幅を自動計算" ) ;
define( $constpref."OPT_CALCWHINSIDEBOX" , "幅か高さの大きい方が指定数値になるよう自動計算" ) ;

define( $constpref."OPT_VIEWLIST" , "説明文付リスト表示" ) ;
define( $constpref."OPT_VIEWTABLE" , "テーブル表示" ) ;

// Sub menu titles
//define($constpref."TEXT_SMNAME1","投稿");
//define($constpref."TEXT_SMNAME2","高人気");
//define($constpref."TEXT_SMNAME3","トップランク");
//define($constpref."TEXT_SMNAME4","自分の投稿");

// Names of admin menu items
//define($constpref."ADMENU0","投稿された画像の承認");
//define($constpref."ADMENU1","画像管理");
//define($constpref."ADMENU2","カテゴリ管理");
//define($constpref."ADMENU_GPERM","各グループの権限");
//define($constpref."ADMENU3","動作チェッカー");
//define($constpref."ADMENU4","画像一括登録");
//define($constpref."ADMENU5","サムネイルの再構築");
//define($constpref."ADMENU_IMPORT","画像インポート");
//define($constpref."ADMENU_EXPORT","画像エクスポート");
//define($constpref."ADMENU_MYBLOCKSADMIN","ブロック・アクセス権限");
//define($constpref."ADMENU_MYTPLSADMIN","テンプレート管理");


// Text for notifications
define($constpref."GLOBAL_NOTIFY", "モジュール全体");
define($constpref."GLOBAL_NOTIFYDSC", "モジュール全体における通知オプション");
define($constpref."CATEGORY_NOTIFY", "カテゴリー");
define($constpref."CATEGORY_NOTIFYDSC", "選択中のカテゴリーに対する通知オプション");
define($constpref."PHOTO_NOTIFY", "写真");
define($constpref."PHOTO_NOTIFYDSC", "表示中の写真に対する通知オプション");

define($constpref."GLOBAL_NEWPHOTO_NOTIFY", "新規写真登録");
define($constpref."GLOBAL_NEWPHOTO_NOTIFYCAP", "新規に写真が登録された時に通知する");
define($constpref."GLOBAL_NEWPHOTO_NOTIFYDSC", "新規に写真が登録された時に通知する");
define($constpref."GLOBAL_NEWPHOTO_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE}: 新たに写真が登録されました");

define($constpref."CATEGORY_NEWPHOTO_NOTIFY", "カテゴリ毎の新写真登録");
define($constpref."CATEGORY_NEWPHOTO_NOTIFYCAP", "このカテゴリに新たに写真が登録された時に通知する");
define($constpref."CATEGORY_NEWPHOTO_NOTIFYDSC", "このカテゴリに新たに写真が登録された時に通知する");
define($constpref."CATEGORY_NEWPHOTO_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE}: 新たに写真が登録されました");


//=========================================================
// add for webphoto
//=========================================================

// Config Items
define($constpref."CFG_SORT" , "デフォルトの表示順" ) ;
define($constpref."OPT_SORT_IDA","レコード番号昇順");
define($constpref."OPT_SORT_IDD","レコード番号降順");
define($constpref."OPT_SORT_HITSA","ヒット数 (低→高)");
define($constpref."OPT_SORT_HITSD","ヒット数 (高→低)");
define($constpref."OPT_SORT_TITLEA","タイトル (A → Z)");
define($constpref."OPT_SORT_TITLED","タイトル (Z → A)");
define($constpref."OPT_SORT_DATEA","更新日時 (旧→新)");
define($constpref."OPT_SORT_DATED","更新日時 (新→旧)");
define($constpref."OPT_SORT_RATINGA","評価 (低→高)");
define($constpref."OPT_SORT_RATINGD","評価 (高→低)");
define($constpref."OPT_SORT_RANDOM","ランダム");

define($constpref."CFG_GICONSPATH" , "Google アイコンファイルの保存先ディレクトリ" ) ;
define($constpref."CFG_TMPPATH" ,   "一時ファイルの保存先ディレクトリ" ) ;
define($constpref."CFG_MIDDLE_WIDTH" ,  "シングルビューでの画像の幅" ) ;
define($constpref."CFG_MIDDLE_HEIGHT" , "シングルビューでの画像の高さ" ) ;
define($constpref."CFG_THUMB_WIDTH" ,  "サムネイル画像の幅" ) ;
define($constpref."CFG_THUMB_HEIGHT" , "サムネイル画像の高さ" ) ;

define($constpref."CFG_APIKEY","Google API Key");
define($constpref."CFG_APIKEY_DSC", "Google Maps を利用する場合は <br /> <a href=\"http://www.google.com/apis/maps/signup.html\" target=\"_blank\">Sign Up for the Google Maps API</a> <br /> にて <br /> API key を取得してください<br /><br />パラメータの詳細は下記をご覧ください<br /><a href=\"http://www.google.com/apis/maps/documentation/reference.html\" target=\"_blank\">Google Maps API Reference</a>");
define($constpref."CFG_LATITUDE", "緯度");
define($constpref."CFG_LONGITUDE","経度");
define($constpref."CFG_ZOOM","ズーム");

define($constpref."CFG_USE_POPBOX","PopBox を使用する");

define($constpref."CFG_INDEX_DESC", "トップページに表示する説明文");
define($constpref."CFG_INDEX_DESC_DEFAULT", "ここには説明文を表示します。<br />説明文は「一般設定」にて編集できます。<br />");

// Sub menu titles
define($constpref."SMNAME_SUBMIT","投稿");
define($constpref."SMNAME_POPULAR","高人気");
define($constpref."SMNAME_HIGHRATE","トップランク");
define($constpref."SMNAME_MYPHOTO","自分の投稿");

// Names of admin menu items
define($constpref."ADMENU_ADMISSION","投稿された画像の承認");
define($constpref."ADMENU_PHOTOMANAGER","画像管理");
define($constpref."ADMENU_CATMANAGER","カテゴリ管理");
define($constpref."ADMENU_CHECKCONFIGS","動作チェッカー");
define($constpref."ADMENU_BATCH","画像一括登録");
define($constpref."ADMENU_REDOTHUMB","サムネイルの再構築");
define($constpref."ADMENU_GROUPPERM","各グループの権限");
define($constpref."ADMENU_IMPORT","画像インポート");
define($constpref."ADMENU_EXPORT","画像エクスポート");

define($constpref."ADMENU_GICONMANAGER","Googleアイコン管理");
define($constpref."ADMENU_MIMETYPES","MIMEタイプ管理");
define($constpref."ADMENU_IMPORT_MYALBUM","Myalbum からの一括インポート");
define($constpref."ADMENU_CHECKTABLES","テーブル動作チェック");
define($constpref."ADMENU_PHOTO_TABLE_MANAGE","写真テーブル管理");
define($constpref."ADMENU_CAT_TABLE_MANAGE","カテゴリテーブル管理");
define($constpref."ADMENU_VOTE_TABLE_MANAGE","投票テーブル管理");
define($constpref."ADMENU_GICON_TABLE_MANAGE","Googleアイコンテーブル管理");
define($constpref."ADMENU_MIME_TABLE_MANAGE","MIMEテーブル管理");
define($constpref."ADMENU_TAG_TABLE_MANAGE","タグテーブル管理");
define($constpref."ADMENU_P2T_TABLE_MANAGE","写真タグ関連テーブル管理");
define($constpref."ADMENU_SYNO_TABLE_MANAGE","類似語テーブル管理");

}
// === define begin ===

?>