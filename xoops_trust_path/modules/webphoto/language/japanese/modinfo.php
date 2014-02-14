<?php
// $Id: modinfo.php,v 1.26 2011/12/28 16:16:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-10-01 K.OHWADA
// 写真 -> 写真・動画・メディア
// 2010-04-04 K.OHWADA
// remove echo
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

$constpref = strtoupper( '_MI_' . $MY_DIRNAME. '_' ) ;

// === define begin ===
if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || !defined($constpref."LANG_LOADED") ) 
{

define($constpref."LANG_LOADED" , 1 ) ;

//=========================================================
// same as myalbum
//=========================================================

// The name of this module
define($constpref."NAME","WEB 写真・動画・メディア集");

// A brief description of this module
define($constpref."DESC","写真や動画やその他のメディアを管理するアルバム・モジュールです");

// Names of blocks for this module (Not all module has blocks)
define($constpref."BNAME_RECENT","最近の写真・動画・メディア");
define($constpref."BNAME_HITS","人気の写真・動画・メディア");
define($constpref."BNAME_RANDOM","ピックアップの写真・動画・メディア");
define($constpref."BNAME_RECENT_P","最近の写真・動画・メディア(サムネイル付)");
define($constpref."BNAME_HITS_P","人気の写真・動画・メディア(サムネイル付)");

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
define( $constpref."CFG_NEWPHOTOS" , "トップページで新規として表示する数" ) ;
define( $constpref."CFG_PERPAGE" , "1ページに表示される写真・動画・メディアの数" ) ;
define( $constpref."CFG_DESCPERPAGE" , "選択可能な数字を | で区切って下さい<br />例: 10|20|50|100" ) ;
define( $constpref."CFG_ALLOWNOIMAGE" , "写真・動画・メディアのない投稿を許可する" ) ;
define( $constpref."CFG_MAKETHUMB" , "サムネイルを作成する" ) ;
define( $constpref."CFG_DESCMAKETHUMB" , "「生成しない」から「生成する」に変更した時には、「サムネイルの再構築」が必要です。" ) ;

// v2.30
//define( $constpref."CFG_WIDTH" , "最大画像幅" ) ;
//define( $constpref."CFG_DESCWIDTH" , "画像アップロード時に自動調整されるメイン画像の最大幅。<br />GDモードでTrueColorを扱えない時には単なるサイズ制限" ) ;
//define( $constpref."CFG_HEIGHT" , "最大画像高" ) ;
//define( $constpref."CFG_DESCHEIGHT" , "最大幅と同じ意味です" ) ;
define( $constpref."CFG_WIDTH" , "ポップアップでの画像の幅" ) ;
define( $constpref."CFG_DESCWIDTH" , "ポップアップしたときの画像の大きさです。<br /><br />従来のバージョンではアップロード可能な画像の大きさの制限でした。<br />この制限はなくなりました。<br />2.30にて仕様が変わりました。" ) ;
define( $constpref."CFG_HEIGHT" , "ポップアップでの画像の幅さ" ) ;
define( $constpref."CFG_DESCHEIGHT" , "画像の幅と同じ意味です" ) ;

define( $constpref."CFG_FSIZE" , "最大ファイルサイズ" ) ;
define( $constpref."CFG_DESCFSIZE" , "アップロード時のファイルサイズ制限(byte)" ) ;
define( $constpref."CFG_ADDPOSTS" , "写真・動画・メディアを投稿した時にカウントアップされる投稿数" ) ;
define( $constpref."CFG_DESCADDPOSTS" , "常識的には0か1です。負の値は0と見なされます" ) ;
define( $constpref."CFG_CATONSUBMENU" , "サブメニューへのトップカテゴリーの登録" ) ;
define( $constpref."CFG_NAMEORUNAME" , "投稿者名の表示" ) ;
define( $constpref."CFG_DESCNAMEORUNAME" , "ログイン名かハンドル名か選択して下さい" ) ;
define( $constpref."CFG_VIEWTYPE" , "一覧表示の表示タイプ" ) ;
define( $constpref."CFG_COLSOFTABLE" , "テーブル表示時のカラム数" ) ;
define( $constpref."CFG_USESITEIMG" , "イメージマネージャ統合での[siteimg]タグ" ) ;
define( $constpref."CFG_DESCUSESITEIMG" , "イメージマネージャ統合で、[img]タグの代わりに[siteimg]タグを挿入するようになります。<br />利用モジュール側で[siteimg]タグが有効に機能するようになっている必要があります" ) ;
define( $constpref."OPT_USENAME" , "ハンドル名" ) ;
define( $constpref."OPT_USEUNAME" , "ログイン名" ) ;
define( $constpref."OPT_VIEWLIST" , "説明文付リスト表示" ) ;
define( $constpref."OPT_VIEWTABLE" , "テーブル表示" ) ;

// Text for notifications
define($constpref."GLOBAL_NOTIFY", "モジュール全体");
define($constpref."GLOBAL_NOTIFYDSC", "モジュール全体における通知オプション");
define($constpref."CATEGORY_NOTIFY", "カテゴリー");
define($constpref."CATEGORY_NOTIFYDSC", "選択中のカテゴリーに対する通知オプション");
define($constpref."PHOTO_NOTIFY", "写真・動画・メディア");
define($constpref."PHOTO_NOTIFYDSC", "表示中の写真・動画・メディアに対する通知オプション");

define($constpref."GLOBAL_NEWPHOTO_NOTIFY", "写真・動画・メディアの新規登録");
define($constpref."GLOBAL_NEWPHOTO_NOTIFYCAP", "写真・動画・メディアが新規に登録された時に通知する");
define($constpref."GLOBAL_NEWPHOTO_NOTIFYDSC", "写真・動画・メディアが新規に登録された時に通知する");
define($constpref."GLOBAL_NEWPHOTO_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE}: 新たに写真・動画・メディアが登録されました");

define($constpref."CATEGORY_NEWPHOTO_NOTIFY", "カテゴリ毎の写真・動画・メディアの新規登録");
define($constpref."CATEGORY_NEWPHOTO_NOTIFYCAP", "このカテゴリに新たに写真・動画・メディアが登録された時に通知する");
define($constpref."CATEGORY_NEWPHOTO_NOTIFYDSC", "このカテゴリに新たに写真・動画・メディアが登録された時に通知する");
define($constpref."CATEGORY_NEWPHOTO_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE}: 新たに写真・動画・メディアが登録されました");


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
define($constpref."ADMENU_PHOTOMANAGER","メディア管理");
define($constpref."ADMENU_CATMANAGER","カテゴリ管理");
define($constpref."ADMENU_CHECKCONFIGS","動作チェッカー");
define($constpref."ADMENU_BATCH","メディアの一括登録");
define($constpref."ADMENU_GROUPPERM","各グループの権限");
define($constpref."ADMENU_IMPORT","メディアのインポート");
define($constpref."ADMENU_EXPORT","メディアのエクスポート");

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

//---------------------------------------------------------
// v0.20
//---------------------------------------------------------
define($constpref."CFG_USE_FFMPEG"  , "ffmpeg を使用する" ) ;
define($constpref."CFG_FFMPEGPATH"  , "ffmpeg の実行パス" ) ;
define($constpref."CFG_DESCFFMPEGPATH" , "ffmpeg の存在するディレクトリをフルパスで指定します、空白でうまく行くことが多いでしょう。<br />「ffmpeg を使用する」の「はい」を選択した時のみ意味を持ちます" ) ;
define($constpref."CFG_USE_PATHINFO","pathinfo を使用する");

//---------------------------------------------------------
// v0.30
//---------------------------------------------------------
define($constpref."CFG_MAIL_HOST"  , "メール サーバー ホスト名" ) ;
define($constpref."CFG_MAIL_USER"  , "メール ユーザーID" ) ;
define($constpref."CFG_MAIL_PASS"  , "メール パスワード" ) ;
define($constpref."CFG_MAIL_ADDR"  , "投稿先 メールアドレス" ) ;
define($constpref."CFG_MAIL_CHARSET"  , "メールの文字コード" ) ;
define($constpref."CFG_MAIL_CHARSET_DSC" , "'|' で区切って入力して下さい。<br />文字コードによるチェックを行わない時には、ここを空欄にします" ) ;
define($constpref."CFG_MAIL_CHARSET_LIST","ISO-2022-JP|JIS|Shift_JIS|EUC-JP|UTF-8");
define($constpref."CFG_FILE_DIR"  , "FTP ファイルの保存先ディレクトリ" ) ;
define($constpref."CFG_FILE_DIR_DSC" , "フルパスを指定（最後の'/'は不要）<br />ドキュメント・ルート以外に設定することをお勧めします" ) ;
define($constpref."CFG_FILE_SIZE"  , "FTP 最大ファイル容量 (byte)" ) ;
define($constpref."CFG_FILE_DESC"  , "FTP ヘルプ説明文");
define($constpref."CFG_FILE_DESC_DSC"  , "「ファイル投稿」の権限がある場合に、ヘルプに表示されます");
define($constpref."CFG_FILE_DESC_TEXT"  , "
<b>FTP サーバー</b><br />
FTP サーバー ホスト名: xxx<br />
FTP ユーザーID: xxx<br />
FTP パスワード: xxx<br />" ) ;

define($constpref."ADMENU_MAILLOG_MANAGER","メールログ管理");
define($constpref."ADMENU_MAILLOG_TABLE_MANAGE","メールログ・テーブル管理");
define($constpref."ADMENU_USER_TABLE_MANAGE","ユーザ補助テーブル管理");

//---------------------------------------------------------
// v0.40
//---------------------------------------------------------
define($constpref."CFG_BIN_PASS" , "コマンドのパスワード" ) ;
define($constpref."CFG_COM_DIRNAME",  "コメント統合するd3forumのdirname");
define($constpref."CFG_COM_FORUM_ID", "コメント統合するフォーラムの番号");
define($constpref."CFG_COM_VIEW",     "コメント統合の表示方法");

define($constpref."ADMENU_UPDATE", "アップデート");
define($constpref."ADMENU_ITEM_TABLE_MANAGE", "アイテム・テーブル管理");
define($constpref."ADMENU_FILE_TABLE_MANAGE", "ファイル・テーブル管理");

//---------------------------------------------------------
// v0.50
//---------------------------------------------------------
define( $constpref."CFG_UPLOADSPATH" , "アップロード・ファイルの保存先ディレクトリ" ) ;
define( $constpref."CFG_UPLOADSPATH_DSC" , "XOOPSインストール先からのパスを指定（最初の'/'は必要、最後の'/'は不要）<br />Unixではこのディレクトリへの書込属性をONにして下さい" ) ;
define( $constpref."CFG_MEDIASPATH" , "メディア・ファイルのディレクトリ" ) ;
define( $constpref."CFG_MEDIASPATH_DSC" , "プレイリストの元になるメディア・ファイルのあるディレクトリ <br />XOOPSインストール先からのパスを指定（最初の'/'は必要、最後の'/'は不要）" ) ;
define($constpref."CFG_LOGO_WIDTH" ,  "プレイヤー・ロゴ画像の幅と高さ" ) ;
define($constpref."CFG_USE_CALLBACK", "コールバック・ログを使用する");
define($constpref."CFG_USE_CALLBACK_DSC", "コールバックを使用して Flash Player のイベントを記録する");

define($constpref."ADMENU_ITEM_MANAGER", "アイテム管理");
define($constpref."ADMENU_PLAYER_MANAGER", "プレイヤー管理");
define($constpref."ADMENU_FLASHVAR_MANAGER", "フラッシュ変数管理");
define($constpref."ADMENU_PLAYER_TABLE_MANAGE", "プレイヤー・テーブル管理");
define($constpref."ADMENU_FLASHVAR_TABLE_MANAGE", "フラッシュ変数・テーブル管理");

//---------------------------------------------------------
// v0.60
//---------------------------------------------------------
define($constpref."CFG_WORKDIR" ,   "作業用のディレクトリ" ) ;
define($constpref."CFG_WORKDIR_DSC" , "フルパスを指定（最後の'/'は不要）<br />ドキュメント・ルート以外に設定することをお勧めします");
define($constpref."CFG_CAT_WIDTH" ,   "カテゴリ画像の幅と高さ" ) ;
define($constpref."CFG_CSUB_WIDTH" ,  "サブカテゴリに表示する画像の幅と高さ" ) ;
define($constpref."CFG_GICON_WIDTH" ,  "GoogleMap アイコン画像の幅と高さ" ) ;
define($constpref."CFG_JPEG_QUALITY" ,  "JPEG 品質" ) ;
define($constpref."CFG_JPEG_QUALITY_DSC" ,  "1 - 100 <br />画像処理パッケージとしてGDを選択した時のみ意味を持ちます" ) ;

//---------------------------------------------------------
// v0.80
//---------------------------------------------------------
define($constpref."BNAME_CATLIST"  , "カテゴリ一覧" ) ;
define($constpref."BNAME_TAGCLOUD" , "タグ一覧" ) ;

//---------------------------------------------------------
// v0.90
//---------------------------------------------------------
define($constpref."CFG_PERM_CAT_READ"      , "カテゴリの閲覧権限" ) ;
define($constpref."CFG_PERM_CAT_READ_DSC"  , "カテゴリ・テーブルの設定と合わせて有効になる" ) ;
define($constpref."CFG_PERM_ITEM_READ"     , "アイテムの閲覧権限" ) ;
define($constpref."CFG_PERM_ITEM_READ_DSC" , "アイテム・テーブルの設定と合わせて有効になる" ) ;
define($constpref."OPT_PERM_READ_ALL"     , "全て表示する" ) ;
define($constpref."OPT_PERM_READ_NO_ITEM" , "アイテムを非表示にする" ) ;
define($constpref."OPT_PERM_READ_NO_CAT"  , "カテゴリとアイテムを非表示にする" ) ;

//---------------------------------------------------------
// v1.10
//---------------------------------------------------------
define($constpref."CFG_USE_XPDF"  , "xpdf を使用する" ) ;
define($constpref."CFG_XPDFPATH"  , "xpdf の実行パス" ) ;
define($constpref."CFG_XPDFPATH_DSC" , "pdftoppm などの存在するディレクトリをフルパスで指定します、空白でうまく行くことが多いでしょう。<br />「xpdf を使用する」の「はい」を選択した時のみ意味を持ちます" ) ;

//---------------------------------------------------------
// v1.21
//---------------------------------------------------------
define($constpref."ADMENU_RSS_MANAGER", "RSS 管理");

//---------------------------------------------------------
// v1.30
//---------------------------------------------------------
define($constpref."CFG_SMALL_WIDTH" ,  "タイムラインでの画像の幅" ) ;
define($constpref."CFG_SMALL_HEIGHT" , "タイムラインでの画像の高さ" ) ;
define($constpref."CFG_TIMELINE_DIRNAME", "timeline モジュールのディレクトリ名" ) ;
define($constpref."CFG_TIMELINE_DIRNAME_DSC", "タイムライン機能を使用するときに指定する" ) ;
define($constpref."CFG_TIMELINE_SCALE", "タイムラインの時間幅") ;
define($constpref."CFG_TIMELINE_SCALE_DSC", "約 600px の横幅に表示する時間" ) ;
define($constpref."OPT_TIMELINE_SCALE_WEEK",   "１週間") ;
define($constpref."OPT_TIMELINE_SCALE_MONTH",  "１ヶ月") ;
define($constpref."OPT_TIMELINE_SCALE_YEAR",   "１年") ;
define($constpref."OPT_TIMELINE_SCALE_DECADE", "１０年") ;

//---------------------------------------------------------
// v1.40
//---------------------------------------------------------
// timeline
define($constpref."CFG_TIMELINE_LATEST", "タイムラインの新しい方から表示する写真・動画・メディアの数");
define($constpref."CFG_TIMELINE_RANDOM", "タイムラインのランダムに表示する写真・動画・メディアの数");
define($constpref."BNAME_TIMELINE" , "タイムライン" ) ;

// map, tag
define($constpref."CFG_GMAP_PHOTOS", "マップに表示する写真・動画・メディアの数");
define($constpref."CFG_TAGS", "タグクラウドに表示するタグの数");

//---------------------------------------------------------
// v1.70
//---------------------------------------------------------
define($constpref."CFG_ITEM_SUMMARY", "写真・動画・メディアの説明の最大の文字数");
define($constpref."CFG_ITEM_SUMMARY_DSC", "一覧に表示する写真・動画・メディアの説明文の最大の文字数を指定する<br />-1 は制限なし");
define($constpref."CFG_CAT_SUMMARY", "カテゴリの説明の最大の文字数");
define($constpref."CFG_CAT_SUMMARY_DSC", "カテゴリ一覧に表示する説明文の最大の文字数を指定する<br />-1 は制限なし");
define($constpref."CFG_CAT_CHILD", "下位カテゴリの写真・動画・メディアの表示");
define($constpref."CFG_CAT_CHILD_DSC", "カテゴリ表示のときに下位カテゴリの写真・動画・メディアを表示するか否かを指定する");
define($constpref."OPT_CAT_CHILD_NON", "カテゴリの写真・動画・メディアのみを表示する。常に下位カテゴリの写真・動画・メディアを表示しない");
define($constpref."OPT_CAT_CHILD_EMPTY", "カテゴリ写真・動画・メディアがゼロのときに、下位カテゴリの写真・動画・メディアを表示する");
define($constpref."OPT_CAT_CHILD_ALWAYS", "常に下位カテゴリの写真・動画・メディアを表示する");

//---------------------------------------------------------
// v1.80
//---------------------------------------------------------
define($constpref."CFG_USE_LAME"  , "lame を使用する" ) ;
define($constpref."CFG_LAMEPATH"  , "lame の実行パス" ) ;
define($constpref."CFG_LAMEPATH_DSC" , "lame の存在するディレクトリをフルパスで指定します、空白でうまく行くことが多いでしょう。<br />「lame を使用する」の「はい」を選択した時のみ意味を持ちます" ) ;

define($constpref."CFG_USE_TIMIDITY"  , "timidity を使用する" ) ;
define($constpref."CFG_TIMIDITYPATH"  , "timidity の実行パス" ) ;
define($constpref."CFG_TIMIDITYPATH_DSC" , "timidity の存在するディレクトリをフルパスで指定します、空白でうまく行くことが多いでしょう。<br />「timidity を使用する」の「はい」を選択した時のみ意味を持ちます" ) ;

define($constpref."SMNAME_SEARCH","検索");

//---------------------------------------------------------
// v2.00
//---------------------------------------------------------
// config
define($constpref."CFG_GROUPID_ADMIN"  , "管理者 グループID" ) ;
define($constpref."CFG_GROUPID_ADMIN_DSC" , "このモジュールの管理者のユーザグループID。<br />モジュールインストール時に設定される。<br />むやみに変更しないこと" ) ;
define($constpref."CFG_GROUPID_USER"  , "利用者 グループID" ) ;
define($constpref."CFG_GROUPID_USER_DSC" , "このモジュールの利用者のユーザグループID。<br />モジュールインストール時に設定される。<br />むやみに変更しないこと" ) ;

// admin menu
define($constpref."ADMENU_INVITE", "友人を招待する");

// notifications
define($constpref."GLOBAL_WAITING_NOTIFY", "承認待ちの写真・動画・メディアの投稿");
define($constpref."GLOBAL_WAITING_NOTIFYCAP", "承認待ちの写真・動画・メディアが投稿された時に通知する (管理者)");
define($constpref."GLOBAL_WAITING_NOTIFYDSC", "承認待ちの写真・動画・メディアが投稿された時に通知する");
define($constpref."GLOBAL_WAITING_NOTIFYSBJ", "[{X_SITENAME}] {X_MODULE}: 承認待ちの写真・動画・メディア真が投稿されました");

//---------------------------------------------------------
// v2.10
//---------------------------------------------------------
define($constpref."CFG_USE_LIGHTBOX","LightBox を使用する");

//---------------------------------------------------------
// v2.11
//---------------------------------------------------------
define($constpref."ADMENU_REDOTHUMBS","サムネイルの再構築");

//---------------------------------------------------------
// v2.20
//---------------------------------------------------------
define($constpref."CFG_EMBED_WIDTH", "動画サイトの表示の幅");
define($constpref."CFG_EMBED_HEIGHT","動画サイトの表示の高さ");

//---------------------------------------------------------
// v2.40
//---------------------------------------------------------
define($constpref."CFG_PEAR_PATH", 'PEAR ライブラリのパス');
define($constpref."CFG_PEAR_PATH_DSC", 'Net_POP3 のある PEAR ライブラリの絶対パスを指定する<br />指定しないときは modules/webphoto/PEAR が使用される');

//---------------------------------------------------------
// v2.60
//---------------------------------------------------------
define($constpref."OPT_TIMELINE_SCALE_HOUR",       "１時間") ;
define($constpref."OPT_TIMELINE_SCALE_DAY",        "１日") ;
define($constpref."OPT_TIMELINE_SCALE_CENTURY",    "１世紀") ;
define($constpref."OPT_TIMELINE_SCALE_MILLENNIUM", "１０世紀") ;

}
// === define begin ===

?>