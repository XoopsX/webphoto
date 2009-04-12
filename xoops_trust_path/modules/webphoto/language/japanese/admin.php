<?php
// $Id: admin.php,v 1.15 2009/04/12 02:49:35 ohwada Exp $

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
define("_AM_WEBPHOTO_MYMENU_TPLSADMIN","テンプレート管理");
define("_AM_WEBPHOTO_MYMENU_BLOCKSADMIN","ブロック管理/アクセス権限");

//define("_AM_WEBPHOTO_MYMENU_MYPREFERENCES","一般設定");

// add for webphoto
define("_AM_WEBPHOTO_MYMENU_GOTO_MODULE" , "モジュールへ" ) ;


// Index (Categories)
//define( "_AM_WEBPHOTO_H3_FMT_CATEGORIES" , "%s カテゴリー管理" ) ;
//define( "_AM_WEBPHOTO_CAT_TH_TITLE" , "カテゴリー名" ) ;

define( "_AM_WEBPHOTO_CAT_TH_PHOTOS" , "画像数" ) ;
define( "_AM_WEBPHOTO_CAT_TH_OPERATION" , "カテゴリ操作" ) ;
define( "_AM_WEBPHOTO_CAT_TH_IMAGE" , "イメージ" ) ;
define( "_AM_WEBPHOTO_CAT_TH_PARENT" , "親カテゴリー" ) ;

//define( "_AM_WEBPHOTO_CAT_TH_IMGURL" , "イメージのURL" ) ;

define( "_AM_WEBPHOTO_CAT_MENU_NEW" , "カテゴリーの新規作成" ) ;
define( "_AM_WEBPHOTO_CAT_MENU_EDIT" , "カテゴリーの編集" ) ;
define( "_AM_WEBPHOTO_CAT_INSERTED" , "カテゴリーを追加しました" ) ;
define( "_AM_WEBPHOTO_CAT_UPDATED" , "カテゴリーを更新しました" ) ;
define( "_AM_WEBPHOTO_CAT_BTN_BATCH" , "変更を反映する" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_MAKETOPCAT" , "トップカテゴリーを追加" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_ADDPHOTOS" , "このカテゴリーに画像を追加" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_EDIT" , "このカテゴリーの編集" ) ;
define( "_AM_WEBPHOTO_CAT_LINK_MAKESUBCAT" , "このカテゴリー下にサブカテゴリー作成" ) ;
define( "_AM_WEBPHOTO_CAT_FMT_NEEDADMISSION" , "未承認画像あり (%s 枚)" ) ;
define( "_AM_WEBPHOTO_CAT_FMT_CATDELCONFIRM" , "カテゴリー %s を削除してよろしいですか？ 配下のサブカテゴリーも含め、画像やコメントがすべて削除されます" ) ;


// Admission
//define( "_AM_WEBPHOTO_H3_FMT_ADMISSION" , "%s 投稿画像の承認" ) ;
//define( "_AM_WEBPHOTO_TH_SUBMITTER" , "投稿者" ) ;
//define( "_AM_WEBPHOTO_TH_TITLE" , "タイトル" ) ;
//define( "_AM_WEBPHOTO_TH_DESCRIPTION" , "説明文" ) ;
//define( "_AM_WEBPHOTO_TH_CATEGORIES" , "カテゴリー" ) ;
//define( "_AM_WEBPHOTO_TH_DATE" , "最終更新日" ) ;


// Photo Manager
//define( "_AM_WEBPHOTO_H3_FMT_PHOTOMANAGER" , "%s 画像管理" ) ;

define( "_AM_WEBPHOTO_TH_BATCHUPDATE" , "チェックした画像をまとめて変更する" ) ;
define( "_AM_WEBPHOTO_OPT_NOCHANGE" , "変更なし" ) ;
define( "_AM_WEBPHOTO_JS_UPDATECONFIRM" , "指定された項目についてのみ、チェックした画像を変更します" ) ;


// Module Checker
//define( "_AM_WEBPHOTO_H3_FMT_MODULECHECKER" , "myAlbum-P 動作チェッカー (%s)" ) ;

define( "_AM_WEBPHOTO_H4_ENVIRONMENT" , "環境チェック" ) ;
define( "_AM_WEBPHOTO_PHPDIRECTIVE" , "PHP設定" ) ;
define( "_AM_WEBPHOTO_BOTHOK" , "両方ok" ) ;
define( "_AM_WEBPHOTO_NEEDON" , "要on" ) ;

define( "_AM_WEBPHOTO_H4_TABLE" , "テーブルチェック" ) ;

//define( "_AM_WEBPHOTO_PHOTOSTABLE" , "メイン画像テーブル" ) ;
//define( "_AM_WEBPHOTO_DESCRIPTIONTABLE" , "テキストテーブル" ) ;
//define( "_AM_WEBPHOTO_CATEGORIESTABLE" , "カテゴリーテーブル" ) ;
//define( "_AM_WEBPHOTO_VOTEDATATABLE" , "投票データテーブル" ) ;

define( "_AM_WEBPHOTO_COMMENTSTABLE" , "コメントテーブル" ) ;
define( "_AM_WEBPHOTO_NUMBEROFPHOTOS" , "画像総数" ) ;
define( "_AM_WEBPHOTO_NUMBEROFDESCRIPTIONS" , "テキスト総数" ) ;
define( "_AM_WEBPHOTO_NUMBEROFCATEGORIES" , "カテゴリー総数" ) ;
define( "_AM_WEBPHOTO_NUMBEROFVOTEDATA" , "投票総数" ) ;
define( "_AM_WEBPHOTO_NUMBEROFCOMMENTS" , "コメント総数" ) ;

define( "_AM_WEBPHOTO_H4_CONFIG" , "設定チェック" ) ;
define( "_AM_WEBPHOTO_PIPEFORIMAGES" , "画像処理プログラム" ) ;

//define( "_AM_WEBPHOTO_DIRECTORYFORPHOTOS" , "メイン画像ディレクトリ" ) ;
//define( "_AM_WEBPHOTO_DIRECTORYFORTHUMBS" , "サムネイルディレクトリ" ) ;

define( "_AM_WEBPHOTO_ERR_LASTCHAR" , "エラー: 最後の文字の'/'は必要ありません" ) ;
define( "_AM_WEBPHOTO_ERR_FIRSTCHAR" , "エラー: 最初の文字は'/'でなければなりません" ) ;
define( "_AM_WEBPHOTO_ERR_PERMISSION" , "エラー: まずこのディレクトリをつくって下さい。その上で、書込可能に設定して下さい。Unixではchmod 777、Windowsでは読み取り専用属性を外します" ) ;
define( "_AM_WEBPHOTO_ERR_NOTDIRECTORY" , "エラー: 指定されたディレクトリがありません." ) ;
define( "_AM_WEBPHOTO_ERR_READORWRITE" , "エラー: 指定されたディレクトリは読み出せないか書き込めないかのいずれかです。その両方を許可する設定にして下さい。Unixではchmod 777、Windowsでは読み取り専用属性を外します" ) ;
define( "_AM_WEBPHOTO_ERR_SAMEDIR" , "エラー: メイン画像用ディレクトリとサムネイル用ディレクトリが一緒です。（その設定は不可能です）" ) ;
define( "_AM_WEBPHOTO_LNK_CHECKGD2" , "GD2(truecolor)モードが動くかどうかのチェック" ) ;
define( "_AM_WEBPHOTO_CHECKGD2" , "（このリンク先が正常に表示されなければ、GD2モードでは動かないものと諦めてください）" ) ;
define( "_AM_WEBPHOTO_GD2SUCCESS" , "成功しました!<br />おそらく、このサーバのPHPでは、GD2(true color)モードで画像を生成可能です。" ) ;

define( "_AM_WEBPHOTO_H4_PHOTOLINK" , "メイン画像とサムネイルのリンクチェック" ) ;
define( "_AM_WEBPHOTO_NOWCHECKING" , "チェック中 ." ) ;

//define( "_AM_WEBPHOTO_FMT_PHOTONOTREADABLE" , "メイン画像 (%s) が読めません." ) ;
//define( "_AM_WEBPHOTO_FMT_THUMBNOTREADABLE" , "サムネイル画像 (%s) が読めません." ) ;

define( "_AM_WEBPHOTO_FMT_NUMBEROFDEADPHOTOS" , "画像のないレコードが %s 個ありました。" ) ;
define( "_AM_WEBPHOTO_FMT_NUMBEROFDEADTHUMBS" , "サムネイルが %s 個未作成です" ) ;
define( "_AM_WEBPHOTO_FMT_NUMBEROFREMOVEDTMPS" , "テンポラリを %s 個削除しました" ) ;
define( "_AM_WEBPHOTO_LINK_REDOTHUMBS" , "サムネイル再構築" ) ;
define( "_AM_WEBPHOTO_LINK_TABLEMAINTENANCE" , "テーブルメンテナンス" ) ;


// Redo Thumbnail
//define( "_AM_WEBPHOTO_H3_FMT_RECORDMAINTENANCE" , "myAlbum-P 写真メンテナンス (%s)" ) ;

define( "_AM_WEBPHOTO_FMT_CHECKING" , "%s をチェック中 ... " ) ;
define( "_AM_WEBPHOTO_FORM_RECORDMAINTENANCE" , "サムネイルの再構築など、写真データの各種メンテナンス" ) ;

define( "_AM_WEBPHOTO_FAILEDREADING" , "写真ファイルの読み込み失敗" ) ;
define( "_AM_WEBPHOTO_CREATEDTHUMBS" , "サムネイル作成完了" ) ;
define( "_AM_WEBPHOTO_BIGTHUMBS" , "サムネイルを作成できないので、コピーしました" ) ;
define( "_AM_WEBPHOTO_SKIPPED" , "スキップします" ) ;
define( "_AM_WEBPHOTO_SIZEREPAIRED" , "(登録されていたピクセル数を修正しました)" ) ;
define( "_AM_WEBPHOTO_RECREMOVED" , "このレコードは削除されました" ) ;
define( "_AM_WEBPHOTO_PHOTONOTEXISTS" , "画像がありません" ) ;
define( "_AM_WEBPHOTO_PHOTORESIZED" , "サイズ調整しました" ) ;

define( "_AM_WEBPHOTO_TEXT_RECORDFORSTARTING" , "処理を開始するレコード番号" ) ;
define( "_AM_WEBPHOTO_TEXT_NUMBERATATIME" , "一度に処理する写真数" ) ;
define( "_AM_WEBPHOTO_LABEL_DESCNUMBERATATIME" , "この数を大きくしすぎるとサーバのタイムアウトを招きます" ) ;

define( "_AM_WEBPHOTO_RADIO_FORCEREDO" , "サムネイルがあっても常に作成し直す" ) ;
define( "_AM_WEBPHOTO_RADIO_REMOVEREC" , "写真がないレコードを削除する" ) ;
define( "_AM_WEBPHOTO_RADIO_RESIZE" , "今のピクセル数設定よりも大きな画像はサイズを切りつめる" ) ;

define( "_AM_WEBPHOTO_FINISHED" , "完了" ) ;
define( "_AM_WEBPHOTO_LINK_RESTART" , "再スタート" ) ;
define( "_AM_WEBPHOTO_SUBMIT_NEXT" , "次へ" ) ;


// Batch Register
//define( "_AM_WEBPHOTO_H3_FMT_BATCHREGISTER" , "myAlbum-P 画像一括登録 (%s)" ) ;


// GroupPerm Global
//define( "_AM_WEBPHOTO_GROUPPERM_GLOBAL" , "各グループの権限設定" ) ;

define( "_AM_WEBPHOTO_GROUPPERM_GLOBALDESC" , "グループ個々について、権限を設定します" ) ;
define( "_AM_WEBPHOTO_GPERMUPDATED" , "権限設定を変更しました" ) ;


// Import
define( "_AM_WEBPHOTO_H3_FMT_IMPORTTO" , '%s への画像インポート' ) ;
define( "_AM_WEBPHOTO_FMT_IMPORTFROMMYALBUMP" , 'myAblum-Pモジュール: 「%s」 からの取り込み（カテゴリー単位）' ) ;
define( "_AM_WEBPHOTO_FMT_IMPORTFROMIMAGEMANAGER" , 'イメージ・マネージャからの取り込み（カテゴリー単位）' ) ;

//define( "_AM_WEBPHOTO_CB_IMPORTRECURSIVELY" , 'サブカテゴリーもインポートする' ) ;
//define( "_AM_WEBPHOTO_RADIO_IMPORTCOPY" , '画像のコピー（コメントは引き継がれません）' ) ;
//define( "_AM_WEBPHOTO_RADIO_IMPORTMOVE" , '画像の移動（コメントを引き継ぎます）' ) ;

define( "_AM_WEBPHOTO_IMPORTCONFIRM" , 'インポートします。よろしいですか？' ) ;
define( "_AM_WEBPHOTO_FMT_IMPORTSUCCESS" , '%s 枚の画像をインポートしました' ) ;


// Export
define( "_AM_WEBPHOTO_H3_FMT_EXPORTTO" , '%s から他モジュール等への画像エクスポート' ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTTOIMAGEMANAGER" , 'イメージ・マネージャへの書き出し（カテゴリー単位）' ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTIMSRCCAT" , 'コピー元カテゴリー' ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTIMDSTCAT" , 'コピー先カテゴリー' ) ;
define( "_AM_WEBPHOTO_CB_EXPORTRECURSIVELY" , 'サブカテゴリーもエクスポートする' ) ;
define( "_AM_WEBPHOTO_CB_EXPORTTHUMB" , 'サムネイル画像の方をエクスポートする' ) ;
define( "_AM_WEBPHOTO_EXPORTCONFIRM" , 'エクスポートします。よろしいですか？' ) ;
define( "_AM_WEBPHOTO_FMT_EXPORTSUCCESS" , '%s 枚の画像をエクスポートしました' ) ;


//---------------------------------------------------------
// move from main.php
//---------------------------------------------------------
define( "_AM_WEBPHOTO_BTN_SELECTALL" , "全選択" ) ;
define( "_AM_WEBPHOTO_BTN_SELECTNONE" , "選択解除" ) ;
define( "_AM_WEBPHOTO_BTN_SELECTRVS" , "選択反転" ) ;
define( "_AM_WEBPHOTO_FMT_PHOTONUM" , "%s 枚" ) ;

define( "_AM_WEBPHOTO_ADMISSION" , "画像の承認" ) ;
define( "_AM_WEBPHOTO_ADMITTING" , "画像を承認しました" ) ;
define( "_AM_WEBPHOTO_LABEL_ADMIT" , "チェックした画像を承認する" ) ;
define( "_AM_WEBPHOTO_BUTTON_ADMIT" , "承認" ) ;
define( "_AM_WEBPHOTO_BUTTON_EXTRACT" , "抽出" ) ;

define( "_AM_WEBPHOTO_LABEL_REMOVE" , "チェックした画像を削除する" ) ;
define( "_AM_WEBPHOTO_JS_REMOVECONFIRM" , "削除してよろしいですか" ) ;
define( "_AM_WEBPHOTO_LABEL_MOVE" , "チェックした画像を移動する" ) ;
define( "_AM_WEBPHOTO_BUTTON_MOVE" , "移動" ) ;
define( "_AM_WEBPHOTO_BUTTON_UPDATE" , "変更" ) ;
define( "_AM_WEBPHOTO_DEADLINKMAINPHOTO" , "メイン画像が存在しません" ) ;

define("_AM_WEBPHOTO_NOSUBMITTED","新規の投稿画像はありません。");
define("_AM_WEBPHOTO_ADDMAIN","トップカテゴリを追加");
define("_AM_WEBPHOTO_IMGURL","画像のURL (画像の高さはあらかじめ50pixelに): ");
define("_AM_WEBPHOTO_ADD","追加");
define("_AM_WEBPHOTO_ADDSUB","サブカテゴリの追加");
define("_AM_WEBPHOTO_IN","");
define("_AM_WEBPHOTO_MODCAT","カテゴリ変更");

define("_AM_WEBPHOTO_MODREQDELETED","変更要請を削除");
define("_AM_WEBPHOTO_IMGURLMAIN","画像URL (画像の高さはあらかじめ50pixelに): ");
define("_AM_WEBPHOTO_PARENT","親カテゴリ:");
define("_AM_WEBPHOTO_SAVE","変更を保存");
define("_AM_WEBPHOTO_CATDELETED","カテゴリの消去完了");
define("_AM_WEBPHOTO_CATDEL_WARNING","カテゴリと同時にここに含まれる画像およびコメントが全て削除されますがよろしいですか？");

define("_AM_WEBPHOTO_NEWCATADDED","新カテゴリ追加に成功!");
define("_AM_WEBPHOTO_ERROREXIST","エラー: 提供される画像はすでにデータベースに存在します。");
define("_AM_WEBPHOTO_ERRORTITLE","エラー: タイトルが必要です!");
define("_AM_WEBPHOTO_ERRORDESC","エラー: 説明が必要です!");
define("_AM_WEBPHOTO_WEAPPROVED","画像データベースへのリンク要請を承認しました。");
define("_AM_WEBPHOTO_THANKSSUBMIT","ご投稿有り難うございます。");
define("_AM_WEBPHOTO_CONFUPDATED","設定を更新しました。");

define("_AM_WEBPHOTO_PHOTOBATCHUPLOAD","サーバにアップロード済ファイルの一括登録");
define("_AM_WEBPHOTO_PHOTOPATH","Path:");
define("_AM_WEBPHOTO_TEXT_DIRECTORY","ディレクトリ");
define("_AM_WEBPHOTO_DESC_PHOTOPATH","画像の含まれるディレクトリを絶対パスで指定して下さい");
define("_AM_WEBPHOTO_MES_INVALIDDIRECTORY","指定されたディレクトリから画像を読み出せません");
define("_AM_WEBPHOTO_MES_BATCHDONE","%s 枚の画像を登録しました");
define("_AM_WEBPHOTO_MES_BATCHNONE","指定されたディレクトリに画像ファイルがみつかりませんでした");


//---------------------------------------------------------
// move from myalbum_constants.php
//---------------------------------------------------------
// Global Group Permission
define( "_AM_WEBPHOTO_GPERM_INSERTABLE" , "投稿可（要承認）" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPERINSERT" , "投稿可（承認不要）" ) ;
define( "_AM_WEBPHOTO_GPERM_EDITABLE" , "編集可（要承認）" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPEREDIT" , "編集可（承認不要）" ) ;
define( "_AM_WEBPHOTO_GPERM_DELETABLE" , "削除可（要承認）" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPERDELETE" , "削除可（承認不要）" ) ;
define( "_AM_WEBPHOTO_GPERM_TOUCHOTHERS" , "他ユーザのイメージを編集・削除可（要承認）" ) ;
define( "_AM_WEBPHOTO_GPERM_SUPERTOUCHOTHERS" , "他ユーザのイメージを編集・削除可（承認不要）" ) ;
define( "_AM_WEBPHOTO_GPERM_RATEVIEW" , "投票閲覧可" ) ;
define( "_AM_WEBPHOTO_GPERM_RATEVOTE" , "投票可" ) ;
define( "_AM_WEBPHOTO_GPERM_TELLAFRIEND" , "友人に知らせる" ) ;

// add for webphoto
define( "_AM_WEBPHOTO_GPERM_TAGEDIT" , "タグ編集可（承認不要）" ) ;

// v0.30
define( "_AM_WEBPHOTO_GPERM_MAIL" , "メール投稿可（承認不要）" ) ;
define( "_AM_WEBPHOTO_GPERM_FILE" , "ファイル投稿可（承認不要）" ) ;

//=========================================================
// add for webphoto
//=========================================================

//---------------------------------------------------------
// google icon
// modify from gnavi
//---------------------------------------------------------

// list
define( "_AM_WEBPHOTO_GICON_ADD" , "アイコンを新規追加" ) ;
define( "_AM_WEBPHOTO_GICON_LIST_IMAGE" , 'アイコン' ) ;
define( "_AM_WEBPHOTO_GICON_LIST_SHADOW" , 'シャドー' ) ;
define( "_AM_WEBPHOTO_GICON_ANCHOR" , 'アンカーポイント' ) ;
define( "_AM_WEBPHOTO_GICON_WINANC" , 'ウィンドウアンカー' ) ;
define( "_AM_WEBPHOTO_GICON_LIST_EDIT" , 'アイコンの編集' ) ;

// form
define( "_AM_WEBPHOTO_GICON_MENU_NEW" ,  "アイコンの新規作成" ) ;
define( "_AM_WEBPHOTO_GICON_MENU_EDIT" , "アイコンの編集" ) ;
define( "_AM_WEBPHOTO_GICON_IMAGE_SEL" ,  "アイコン画像の選択" ) ;
define( "_AM_WEBPHOTO_GICON_SHADOW_SEL" , "アイコンシャドーの選択" ) ;
define( "_AM_WEBPHOTO_GICON_SHADOW_DEL" , 'アイコンシャドーを削除' ) ;
define( "_AM_WEBPHOTO_GICON_DELCONFIRM" , "アイコン %s を削除してよろしいですか？ " ) ;


//---------------------------------------------------------
// mime type
// modify from wfdownloads
//---------------------------------------------------------

// Mimetype Form
define("_AM_WEBPHOTO_MIME_CREATEF", "MIMEタイプ 作成");
define("_AM_WEBPHOTO_MIME_MODIFYF", "MIMEタイプ 編集");
define("_AM_WEBPHOTO_MIME_NOMIMEINFO", "MIMEタイプが選択されていません。");
define("_AM_WEBPHOTO_MIME_INFOTEXT", "<ul><li>新しいMIMEタイプを作成することができ、このフォームから簡単に編集及び削除することができます。 </li>
	<li>管理者及びユーザがアップロードできるMIMEタイプを確認できます。</li>
	<li>アップロードされているMIMEタイプを変更する事が出来ます。</li></ul>
	");

// Mimetype Database
define("_AM_WEBPHOTO_MIME_DELETETHIS", "選択されたMIMEタイプを削除します。よろしいですか？");
define("_AM_WEBPHOTO_MIME_MIMEDELETED", "MIMEタイプ %s は削除されました。");
define("_AM_WEBPHOTO_MIME_CREATED", "MIMEタイプを作成しました。");
define("_AM_WEBPHOTO_MIME_MODIFIED", "MIMEタイプを更新しました。");

//image admin icon 
define("_AM_WEBPHOTO_MIME_ICO_EDIT","このアイテムを編集");
define("_AM_WEBPHOTO_MIME_ICO_DELETE","このアイテムを削除");
define("_AM_WEBPHOTO_MIME_ICO_ONLINE","オンライン");
define("_AM_WEBPHOTO_MIME_ICO_OFFLINE","オフライン");

// find mine type
//define("_AM_WEBPHOTO_MIME_FINDMIMETYPE", "Find New Mimetype:");
//define("_AM_WEBPHOTO_MIME_FINDIT", "Get Extension!");

// added for webphoto
define("_AM_WEBPHOTO_MIME_PERMS", "許可されているグループ");
define("_AM_WEBPHOTO_MIME_ALLOWED", "許可されているMIMEタイプ");
define("_AM_WEBPHOTO_MIME_NOT_ENTER_EXT", "拡張子が入力されていない");

//---------------------------------------------------------
// check config
//---------------------------------------------------------
define("_AM_WEBPHOTO_DIRECTORYFOR_PHOTOS" , "画像 ディレクトリ" ) ;
define("_AM_WEBPHOTO_DIRECTORYFOR_THUMBS" , "サムネイル ディレクトリ" ) ;
define("_AM_WEBPHOTO_DIRECTORYFOR_GICONS" , "Google アイコン ディレクトリ" ) ;
define("_AM_WEBPHOTO_DIRECTORYFOR_TMP" ,    "一時ファイル ディレクトリ" ) ;

//---------------------------------------------------------
// check table
//---------------------------------------------------------
define("_AM_WEBPHOTO_NUMBEROFRECORED", "レコード数");

//---------------------------------------------------------
// manage
//---------------------------------------------------------
define("_AM_WEBPHOTO_MANAGE_DESC","<b>注意</b><br />テーブル単体の管理です<br />関連するテーブルは変更されません");
define("_AM_WEBPHOTO_ERR_NO_RECORD", "データが存在しない");

//---------------------------------------------------------
// cat manager
//---------------------------------------------------------
//define("_AM_WEBPHOTO_DSC_CAT_IMGPATH" , "画像ファイルをアップロードしてください<br />XOOPSインストール先からのパスを指定します（最初の'/'は必要）<br />設定しないときは、フォルダーアイコンが表示されます" ) ;
//define("_AM_WEBPHOTO_OPT_CAT_PERM_POST_ALL" , "全てのグループ" ) ;

//---------------------------------------------------------
// import
//---------------------------------------------------------
define("_AM_WEBPHOTO_FMT_IMPORTFROM_WEBPHOTO" , 'webphoto モジュール: 「%s」 からの取り込み（カテゴリー単位）' ) ;
define("_AM_WEBPHOTO_IMPORT_COMMENT_NO" , "コメントをコピーしない" ) ;
define("_AM_WEBPHOTO_IMPORT_COMMENT_YES" , "コメントをコピーする" ) ;

//---------------------------------------------------------
// v0.20
//---------------------------------------------------------
define("_AM_WEBPHOTO_PATHINFO_LINK" , "Pathinfo が動くかどうかのチェック" ) ;
define("_AM_WEBPHOTO_PATHINFO_DSC" , "このリンク先が正常に表示されなければ、Pathinfo が動かないものと諦めてください<br/>「一般設定」にて pathinfo を使用しないモードに変更できます" ) ;
define("_AM_WEBPHOTO_PATHINFO_SUCCESS" , "成功しました!<br />おそらく、このサーバでは、Pathinfo が使用できます" ) ;
define("_AM_WEBPHOTO_CAP_REDO_EXIF" , "Exif の取得" ) ;
define("_AM_WEBPHOTO_RADIO_REDO_EXIF_TRY" , "設定されていないときに取得" ) ;
define("_AM_WEBPHOTO_RADIO_REDO_EXIF_ALWAYS" , "常に取得する" ) ;

//---------------------------------------------------------
// v0.30
//---------------------------------------------------------
// checkconfigs
define("_AM_WEBPHOTO_DIRECTORYFOR_FILE" ,    "FTP ファイル ディレクトリ" ) ;
define("_AM_WEBPHOTO_WARN_GEUST_CAN_READ" ,  "このディレクトリはゲストも読むことが出来ます" ) ;
define("_AM_WEBPHOTO_WARN_RECOMMEND_PATH" ,  "ドキュメント・ルート以外に設定することをお勧めします" ) ;
define("_AM_WEBPHOTO_MULTIBYTE_LINK" , "文字コード変換が動くかどうかのチェック" ) ;
define("_AM_WEBPHOTO_MULTIBYTE_DSC" , "（このリンク先が正常に表示されなければ、文字コード変換が動かないようです）" ) ;
define("_AM_WEBPHOTO_MULTIBYTE_SUCCESS" , "この文が文字化けせずに表示されていますか？" ) ;

// maillog manager
define("_AM_WEBPHOTO_SHOW_LIST" ,  "一覧表示" ) ;
define("_AM_WEBPHOTO_MAILLOG_STATUS_REJECT" ,  "拒否されたメール" ) ;
define("_AM_WEBPHOTO_MAILLOG_STATUS_PARTIAL" , "一部の添付ファイルが拒否されたメール" ) ;
define("_AM_WEBPHOTO_MAILLOG_STATUS_SUBMIT" ,  "投稿されたメール" ) ;
define("_AM_WEBPHOTO_BUTTON_SUBMIT_MAIL" ,  "このメールを投稿する" ) ;
define("_AM_WEBPHOTO_ERR_MAILLOG_NO_ATTACH" ,  "添付ファイルが選択されていない" ) ;

// mimetype
define("_AM_WEBPHOTO_MIME_ADD_NEW" ,  "MIME タイプを追加する" ) ;

//---------------------------------------------------------
// v0.40
//---------------------------------------------------------
// index
define("_AM_WEBPHOTO_MUST_UPDATE" , "アップデートが必要です" ) ;
define("_AM_WEBPHOTO_TITLE_BIN" , "コマンドの管理" ) ;
define("_AM_WEBPHOTO_TEST_BIN" , "コマンドのテスト実行" ) ;

// redothumbs
define("_AM_WEBPHOTO_ERR_GET_IMAGE_SIZE", "image size が取得できない" ) ;

// checktables
define("_AM_WEBPHOTO_FMT_NOT_READABLE" , "%s (%s) が読めません." ) ;

//---------------------------------------------------------
// v0.50
//---------------------------------------------------------
// config check
define("_AM_WEBPHOTO_DIRECTORYFOR_UPLOADS" , "アップロード・ディレクトリ" ) ;
define("_AM_WEBPHOTO_DIRECTORYFOR_MEDIAS" , "メディア・ディレクトリ" ) ;

// item manager
define("_AM_WEBPHOTO_ITEM_SELECT","アイテムの選択");
define("_AM_WEBPHOTO_ITEM_ADD","アイテムの追加");
define("_AM_WEBPHOTO_ITEM_LISTING","アイテムの閲覧");
define("_AM_WEBPHOTO_VOTE_DELETED","投票データは削除された");
define("_AM_WEBPHOTO_VOTE_STATS","投票の統計");
define("_AM_WEBPHOTO_VOTE_ENTRY","全体の投票");
define("_AM_WEBPHOTO_VOTE_USER","登録ユーザの投票");
define("_AM_WEBPHOTO_VOTE_GUEST","ゲストの投票");
define("_AM_WEBPHOTO_VOTE_TOTAL","投票数");
define("_AM_WEBPHOTO_VOTE_USERAVG","ユーザの平均評価");
define("_AM_WEBPHOTO_VOTE_USERVOTES","ユーザの全投票数");
define("_AM_WEBPHOTO_LOG_VIEW","ログファイルの閲覧");
define("_AM_WEBPHOTO_LOG_EMPT","ログファイルを空にする");
define("_AM_WEBPHOTO_PLAYLIST_PATH","プレイリストのパス");
define("_AM_WEBPHOTO_PLAYLIST_REFRESH","プレイリストのキャッシュの再生成");
define("_AM_WEBPHOTO_STATUS_CHANGE","ステータスの変更");
define("_AM_WEBPHOTO_STATUS_OFFLINE","オンライン");
define("_AM_WEBPHOTO_STATUS_ONLINE","オフライン");
define("_AM_WEBPHOTO_STATUS_AUTO","自動発行");

// item form
define("_AM_WEBPHOTO_TIME_NOW","現在日時");

// playlist form
define("_AM_WEBPHOTO_PLAYLIST_ADD", "プレイリストを追加する" ) ;
define("_AM_WEBPHOTO_PLAYLIST_TYPE", "プレイリストのタイプ" ) ;
define("_AM_WEBPHOTO_PLAYLIST_FEED_DSC","WEB Feed URL を入力する");
define("_AM_WEBPHOTO_PLAYLIST_DIR_DSC","ディレクトリ名を選択する ");

// player manager
define("_AM_WEBPHOTO_PLAYER_MANAGER","プレイヤー管理");
define("_AM_WEBPHOTO_PLAYER_ADD","プレイヤーの追加");
define("_AM_WEBPHOTO_PLAYER_MOD","プレイヤーの変更");
define("_AM_WEBPHOTO_PLAYER_CLONE","プレイヤーの複製");
define("_AM_WEBPHOTO_PLAYER_ADDED","プレイヤーを追加した");
define("_AM_WEBPHOTO_PLAYER_DELETED","プレイヤーを削除した");
define("_AM_WEBPHOTO_PLAYER_MODIFIED","プレイヤーを変更した");
define("_AM_WEBPHOTO_PLAYER_PREVIEW","プレビュー");
define("_AM_WEBPHOTO_PLAYER_PREVIEW_DSC","最初に変更を保存してください！");
define("_AM_WEBPHOTO_PLAYER_PREVIEW_LINK","プレビューのソース");
define("_AM_WEBPHOTO_PLAYER_NO_ITEM","再生するアイテムがありません");
define("_AM_WEBPHOTO_PLAYER_WARNING","[警告] プレイヤーを削除してもいいですか？ <br />削除する前に、このプイヤーを使用している全てのアイテムを手動で変更してください。");
define("_AM_WEBPHOTO_PLAYER_ERR_EXIST","[エラー] 同じ名前のプレイヤーが存在しています！");
define("_AM_WEBPHOTO_BUTTON_CLONE","複製");

//---------------------------------------------------------
// v0.60
//---------------------------------------------------------
// cat form
define("_AM_WEBPHOTO_CAP_CAT_SELECT","カテゴリ画像の選択");
define("_AM_WEBPHOTO_DSC_CAT_PATH" , "XOOPSインストール先からのパスを指定します（最初の'/'は必要）" ) ;
define("_AM_WEBPHOTO_DSC_CAT_FOLDER" , "設定しないときは、フォルダーアイコンが表示されます" ) ;

//---------------------------------------------------------
// v0.70
//---------------------------------------------------------
define("_AM_WEBPHOTO_RECOMMEND_OFF" , "推奨 off" ) ;

//---------------------------------------------------------
// v0.80
//---------------------------------------------------------
define("_AM_WEBPHOTO_TITLE_WAITING" , "承認待ち一覧" ) ;
define("_AM_WEBPHOTO_TITLE_OFFLINE" , "オフライン一覧" ) ;
define("_AM_WEBPHOTO_TITLE_EXPIRED" , "期限切れ一覧" ) ;

//---------------------------------------------------------
// v0.81
//---------------------------------------------------------
// checkconfigs
define("_AM_WEBPHOTO_QR_CHECK_LINK" , "QRコードが表示できるかのチェック" ) ;
define("_AM_WEBPHOTO_QR_CHECK_DSC" , "（このリンク先が正常に表示されなければ、QRコードが表示できません）" ) ;
define("_AM_WEBPHOTO_QR_CHECK_SUCCESS" , "QRコードが表示されていますか？" ) ;
define("_AM_WEBPHOTO_QR_CHECK_SHOW" , "デバック情報を見る" ) ;
define("_AM_WEBPHOTO_QR_CHECK_INFO" , "デバック情報" ) ;

//---------------------------------------------------------
// v0.90
//---------------------------------------------------------
// cat form
define("_AM_WEBPHOTO_CAT_PARENT_CAP" , "親カテゴリの権限" ) ;
define("_AM_WEBPHOTO_CAT_PARENT_FMT" , "親カテゴリ ( %s ) の権限を継承する" ) ;
define("_AM_WEBPHOTO_CAT_CHILD_CAP"  , "下位のカテゴリ" ) ;
define("_AM_WEBPHOTO_CAT_CHILD_NUM"  , "下位のカテゴリの数" ) ;
define("_AM_WEBPHOTO_CAT_CHILD_PERM" , "下位のカテゴリの権限を変更する" ) ;

//---------------------------------------------------------
// v1.00
//---------------------------------------------------------
// groupperm
define( "_AM_WEBPHOTO_GPERM_HTML" , "HTML投稿可" ) ;

//---------------------------------------------------------
// v1.21
//---------------------------------------------------------
define( "_AM_WEBPHOTO_RSS_DEBUG" , "RSS デバック表示" ) ;
define( "_AM_WEBPHOTO_RSS_CLEAR" , "RSS キャッシュ・クリア" ) ;
define( "_AM_WEBPHOTO_RSS_CLEARED" , "クリアした" ) ;

//---------------------------------------------------------
// v1.40
//---------------------------------------------------------
define( "_AM_WEBPHOTO_TIMELINE_MODULE" , "タイムライン・モジュール" ) ;
define( "_AM_WEBPHOTO_MODULE_NOT_INSTALL" , "モジュールはインストールされていない" ) ;

// === define end ===
}

?>