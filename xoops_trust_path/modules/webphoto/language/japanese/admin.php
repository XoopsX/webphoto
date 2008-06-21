<?php
// $Id: admin.php,v 1.1 2008/06/21 12:22:15 ohwada Exp $

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

define( "_AM_WEBPHOTO_ERR_LASTCHAR" , "エラー: 最後の文字は'/'でなければなりません" ) ;
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
define( "_AM_WEBPHOTO_FMT_PHOTONOTREADABLE" , "メイン画像 (%s) が読めません." ) ;
define( "_AM_WEBPHOTO_FMT_THUMBNOTREADABLE" , "サムネイル画像 (%s) が読めません." ) ;
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
define("_AM_WEBPHOTO_DSC_CAT_IMGPATH" , "XOOPSインストール先からのパスを指定<br />（最初の'/'は必要）" ) ;
define("_AM_WEBPHOTO_OPT_CAT_PERM_POST_ALL" , "全てのグループ" ) ;

//---------------------------------------------------------
// import
//---------------------------------------------------------
define("_AM_WEBPHOTO_FMT_IMPORTFROM_WEBPHOTO" , 'webphoto モジュール: 「%s」 からの取り込み（カテゴリー単位）' ) ;
define("_AM_WEBPHOTO_IMPORT_COMMENT_NO" , "コメントをコピーしない" ) ;
define("_AM_WEBPHOTO_IMPORT_COMMENT_YES" , "コメントをコピーする" ) ;

// === define end ===
}

?>