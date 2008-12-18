<?php
// $Id: main.php,v 1.18 2008/12/18 13:23:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

// === define begin ===
if( !defined("_MB_WEBPHOTO_LANG_LOADED") ) 
{

define("_MB_WEBPHOTO_LANG_LOADED" , 1 ) ;

//=========================================================
// base on myalbum
//=========================================================

define("_WEBPHOTO_CATEGORY","カテゴリ");
define("_WEBPHOTO_SUBMITTER","投稿者");
define("_WEBPHOTO_NOMATCH_PHOTO","画像がありません");

define("_WEBPHOTO_ICON_NEW","新着");
define("_WEBPHOTO_ICON_UPDATE","更新");
define("_WEBPHOTO_ICON_POPULAR","高ヒット");
define("_WEBPHOTO_ICON_LASTUPDATE","前回更新");
define("_WEBPHOTO_ICON_HITS","ヒット数");
define("_WEBPHOTO_ICON_COMMENTS","コメント数");

define("_WEBPHOTO_SORT_IDA","レコード番号昇順");
define("_WEBPHOTO_SORT_IDD","レコード番号降順");
define("_WEBPHOTO_SORT_HITSA","ヒット数 (低→高)");
define("_WEBPHOTO_SORT_HITSD","ヒット数 (高→低)");
define("_WEBPHOTO_SORT_TITLEA","タイトル (A → Z)");
define("_WEBPHOTO_SORT_TITLED","タイトル (Z → A)");
define("_WEBPHOTO_SORT_DATEA","更新日時 (旧→新)");
define("_WEBPHOTO_SORT_DATED","更新日時 (新→旧)");
define("_WEBPHOTO_SORT_RATINGA","評価 (低→高)");
define("_WEBPHOTO_SORT_RATINGD","評価 (高→低)");
define("_WEBPHOTO_SORT_RANDOM","ランダム");

define("_WEBPHOTO_SORT_SORTBY","並び替え:");
define("_WEBPHOTO_SORT_TITLE","タイトル");
define("_WEBPHOTO_SORT_DATE","更新日時");
define("_WEBPHOTO_SORT_HITS","ヒット数");
define("_WEBPHOTO_SORT_RATING","評価");
define("_WEBPHOTO_SORT_S_CURSORTEDBY","現在の並び順: %s");

define("_WEBPHOTO_NAVI_PREVIOUS","前");
define("_WEBPHOTO_NAVI_NEXT","次");
define("_WEBPHOTO_S_NAVINFO" , "%s 番 - %s 番を表示 (全 %s 枚)" ) ;
define("_WEBPHOTO_S_THEREARE","データベースにある画像は <b>%s</b> 枚です");
define("_WEBPHOTO_S_MOREPHOTOS","%s さんの画像をもっと");
define("_WEBPHOTO_ONEVOTE","投票数 1");
define("_WEBPHOTO_S_NUMVOTES","投票数 %s");
define("_WEBPHOTO_ONEPOST","コメント数");
define("_WEBPHOTO_S_NUMPOSTS","コメント数 %s");
define("_WEBPHOTO_VOTETHIS","投票する");
define("_WEBPHOTO_TELLAFRIEND","友人に知らせる");
define("_WEBPHOTO_SUBJECT4TAF","面白い写真を見つけました");


//---------------------------------------------------------
// submit
//---------------------------------------------------------
// only "Y/m/d" , "d M Y" , "M d Y" can be interpreted
define("_WEBPHOTO_DTFMT_YMDHI" , "Y-m-d H:i" ) ;

define("_WEBPHOTO_TITLE_ADDPHOTO","画像を追加する");
define("_WEBPHOTO_TITLE_PHOTOUPLOAD","画像アップロード");
define("_WEBPHOTO_CAP_MAXPIXEL","画像サイズ上限");
define("_WEBPHOTO_CAP_MAXSIZE","ファイルサイズ上限 (byte)");
define("_WEBPHOTO_CAP_VALIDPHOTO","承認");
define("_WEBPHOTO_DSC_TITLE_BLANK","タイトル部を空欄にした場合、ファイル名をタイトルとします");

define("_WEBPHOTO_RADIO_ROTATETITLE" , "画像回転" ) ;
define("_WEBPHOTO_RADIO_ROTATE0" , "回転しない" ) ;
define("_WEBPHOTO_RADIO_ROTATE90" , "右に90度回転" ) ;
define("_WEBPHOTO_RADIO_ROTATE180" , "180度回転" ) ;
define("_WEBPHOTO_RADIO_ROTATE270" , "左に90度回転" ) ;

define("_WEBPHOTO_SUBMIT_RECEIVED","画像を登録しました。ご投稿有難うございます。");
define("_WEBPHOTO_SUBMIT_ALLPENDING","すべての投稿画像は確認のため仮登録となります。");

define("_WEBPHOTO_ERR_MUSTREGFIRST","申し訳ありませんがアクセス権限がありません。<br />登録するか、ログイン後にお願いします。");
define("_WEBPHOTO_ERR_MUSTADDCATFIRST","追加するためにはカテゴリが必要です。<br />まずカテゴリを作成して下さい。");
define("_WEBPHOTO_ERR_NOIMAGESPECIFIED","画像未選択：アップロードすべき画像ファイルを選択して下さい。");
define("_WEBPHOTO_ERR_FILE","画像アップロードに失敗：画像ファイルが見つからないか容量制限を越えてます。");
define("_WEBPHOTO_ERR_FILEREAD","画像読込失敗：なんらかの理由でアップロードされた画像ファイルを読み出せません。");
define("_WEBPHOTO_ERR_TITLE","タイトルが必要です");


//---------------------------------------------------------
// edit
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_EDIT","この画像を編集する");
define("_WEBPHOTO_TITLE_PHOTODEL","画像を削除する");
define("_WEBPHOTO_CONFIRM_PHOTODEL","画像削除?");
define("_WEBPHOTO_DBUPDATED","データベース更新に成功!");
define("_WEBPHOTO_DELETED","削除しました!");


//---------------------------------------------------------
// rate
//---------------------------------------------------------
define("_WEBPHOTO_RATE_VOTEONCE","同一画像への投票は一度だけにお願いします。");
define("_WEBPHOTO_RATE_RATINGSCALE","評価は 1 から 10 までです： 1 が最低、 10 が最高");
define("_WEBPHOTO_RATE_BEOBJECTIVE","客観的な評価をお願いします。点数が1か10のみだと順位付けの意味がありません");
define("_WEBPHOTO_RATE_DONOTVOTE","自分が登録した画像は投票できません。");
define("_WEBPHOTO_RATE_IT","投票する!");
define("_WEBPHOTO_RATE_VOTEAPPRE","投票を受け付けました");
define("_WEBPHOTO_RATE_S_THANKURATE","当サイト %s へのご投票、ありがとうございました");

define("_WEBPHOTO_ERR_NORATING","評価が選択されてません。");
define("_WEBPHOTO_ERR_CANTVOTEOWN","自分の投稿画像には投票できません。<br />投票には全て目を通します");
define("_WEBPHOTO_ERR_VOTEONCE","選択画像への投票は一度だけにお願いします。<br />投票にはすべて目を通します。");


//---------------------------------------------------------
// movo to admin.php
//---------------------------------------------------------
// New in myAlbum-P

// only "Y/m/d" , "d M Y" , "M d Y" can be interpreted
//define( "_WEBPHOTO_DTFMT_YMDHI" , "Y/m/d H:i" ) ;

//define( "_WEBPHOTO_NEXT_BUTTON" , "次へ" ) ;
//define( "_WEBPHOTO_REDOLOOPDONE" , "終了" ) ;

//define( "_WEBPHOTO_BTN_SELECTALL" , "全選択" ) ;
//define( "_WEBPHOTO_BTN_SELECTNONE" , "選択解除" ) ;
//define( "_WEBPHOTO_BTN_SELECTRVS" , "選択反転" ) ;
//define( "_WEBPHOTO_FMT_PHOTONUM" , "%s 枚" ) ;

//define( "_WEBPHOTO_AM_ADMISSION" , "画像の承認" ) ;
//define( "_WEBPHOTO_AM_ADMITTING" , "画像を承認しました" ) ;
//define( "_WEBPHOTO_AM_LABEL_ADMIT" , "チェックした画像を承認する" ) ;
//define( "_WEBPHOTO_AM_BUTTON_ADMIT" , "承認" ) ;
//define( "_WEBPHOTO_AM_BUTTON_EXTRACT" , "抽出" ) ;

//define( "_WEBPHOTO_AM_PHOTOMANAGER" , "画像の管理" ) ;
//define( "_WEBPHOTO_AM_PHOTONAVINFO" , "%s 番〜 %s 番を表示 (全 %s 枚)" ) ;
//define( "_WEBPHOTO_AM_LABEL_REMOVE" , "チェックした画像を削除する" ) ;
//define( "_WEBPHOTO_AM_BUTTON_REMOVE" , "削除" ) ;
//define( "_WEBPHOTO_AM_JS_REMOVECONFIRM" , "削除してよろしいですか" ) ;
//define( "_WEBPHOTO_AM_LABEL_MOVE" , "チェックした画像を移動する" ) ;
//define( "_WEBPHOTO_AM_BUTTON_MOVE" , "移動" ) ;
//define( "_WEBPHOTO_AM_BUTTON_UPDATE" , "変更" ) ;
//define( "_WEBPHOTO_AM_DEADLINKMAINPHOTO" , "メイン画像が存在しません" ) ;


//---------------------------------------------------------
// not use
//---------------------------------------------------------
// New MyAlbum 1.0.1 (and 1.2.0)
//define("_WEBPHOTO_MOREPHOTOS","%s さんの画像をもっと!");
//define("_WEBPHOTO_REDOTHUMBS","サムネイルの再構築(<a href='redothumbs.php'>再スタート</a>)");
//define("_WEBPHOTO_REDOTHUMBS2","サムネイルの再構築");
//define("_WEBPHOTO_REDOTHUMBSINFO","大きな数値を入力するとサーバータイムアウトの原因になります。");
//define("_WEBPHOTO_REDOTHUMBSNUMBER","一度に処理するサムネールの数");
//define("_WEBPHOTO_REDOING","再構築しました: ");
//define("_WEBPHOTO_BACK","戻る");
//define("_WEBPHOTO_ADDPHOTO","画像を追加");


//---------------------------------------------------------
// movo to admin.php
//---------------------------------------------------------
// New MyAlbum 1.0.0
//define("_WEBPHOTO_PHOTOBATCHUPLOAD","サーバにアップロード済ファイルの一括登録");
//define("_WEBPHOTO_PHOTOUPLOAD","画像アップロード");
//define("_WEBPHOTO_PHOTOEDITUPLOAD","画像の編集・再アップロード");
//define("_WEBPHOTO_MAXPIXEL","サイズ上限");
//define("_WEBPHOTO_MAXSIZE","サイズ上限(byte)");
//define("_WEBPHOTO_PHOTOCAT","カテゴリ");
//define("_WEBPHOTO_PHOTOTITLE","タイトル");
//define("_WEBPHOTO_PHOTOPATH","Path:");
//define("_WEBPHOTO_TEXT_DIRECTORY","ディレクトリ");
//define("_WEBPHOTO_DESC_PHOTOPATH","画像の含まれるディレクトリを絶対パスで指定して下さい");
//define("_WEBPHOTO_MES_INVALIDDIRECTORY","指定されたディレクトリから画像を読み出せません");
//define("_WEBPHOTO_MES_BATCHDONE","%s 枚の画像を登録しました");
//define("_WEBPHOTO_MES_BATCHNONE","指定されたディレクトリに画像ファイルがみつかりませんでした");
//define("_WEBPHOTO_PHOTODESC","説明");
//define("_WEBPHOTO_SELECTFILE","画像選択");
//define("_WEBPHOTO_NOIMAGESPECIFIED","画像未選択：アップロードすべき画像ファイルを選択して下さい。");
//define("_WEBPHOTO_FILEERROR","画像アップロードに失敗：画像ファイルが見つからないか容量制限を越えてます。");
//define("_WEBPHOTO_FILEREADERROR","画像読込失敗：なんらかの理由でアップロードされた画像ファイルを読み出せません。");

//define("_WEBPHOTO_BATCHBLANK","タイトル部を空欄にした場合、ファイル名をタイトルとします");
//define("_WEBPHOTO_DELETEPHOTO","削除?");
//define("_WEBPHOTO_VALIDPHOTO","承認");
//define("_WEBPHOTO_PHOTODEL","画像削除?");
//define("_WEBPHOTO_DELETINGPHOTO","削除しました");
//define("_WEBPHOTO_MOVINGPHOTO","移動しました");

//define("_WEBPHOTO_STORETIMESTAMP","日時を変更しない");

//define("_WEBPHOTO_POSTERC","投稿: ");
//define("_WEBPHOTO_DATEC","日時: ");
//define("_WEBPHOTO_EDITNOTALLOWED","コメントを編集する権限がありません！");
//define("_WEBPHOTO_ANONNOTALLOWED","匿名ユーザは投稿できません。");
//define("_WEBPHOTO_THANKSFORPOST","ご投稿有り難うございます。");
//define("_WEBPHOTO_DELNOTALLOWED","コメントを削除する権限がありません!");
//define("_WEBPHOTO_GOBACK","戻る");
//define("_WEBPHOTO_AREYOUSURE","このコメントとその下部コメントを削除：よろしいですか？");
//define("_WEBPHOTO_COMMENTSDEL","コメント削除完了！");

// End New


//---------------------------------------------------------
// not use
//---------------------------------------------------------
//define("_WEBPHOTO_THANKSFORINFO","ご投稿頂いた画像の公開はできるだけ早く検討します。");
//define("_WEBPHOTO_BACKTOTOP","最初の画像へ戻る");
//define("_WEBPHOTO_THANKSFORHELP","ご協力有難うございます。");
//define("_WEBPHOTO_FORSECURITY","セキュリティの観点からあなたのIPアドレスを一時的に保存します。");

//define("_WEBPHOTO_MATCH","合致");
//define("_WEBPHOTO_ALL","全て");
//define("_WEBPHOTO_ANY","どれでも");
//define("_WEBPHOTO_NAME","名前");
//define("_WEBPHOTO_DESCRIPTION","説明");

//define("_WEBPHOTO_MAIN","アルバムトップ");
//define("_WEBPHOTO_NEW","新着");
//define("_WEBPHOTO_UPDATED","更新");
//define("_WEBPHOTO_POPULAR","高ヒット");
//define("_WEBPHOTO_TOPRATED","高評価");

//define("_WEBPHOTO_POPULARITYLTOM","ヒット数 (低→高)");
//define("_WEBPHOTO_POPULARITYMTOL","ヒット数 (高→低)");
//define("_WEBPHOTO_TITLEATOZ","タイトル (A → Z)");
//define("_WEBPHOTO_TITLEZTOA","タイトル (Z → A)");
//define("_WEBPHOTO_DATEOLD","日時 (旧→新)");
//define("_WEBPHOTO_DATENEW","日時 (新→旧)");
//define("_WEBPHOTO_RATINGLTOH","評価 (低→高)");
//define("_WEBPHOTO_RATINGHTOL","評価 (高→低)");
//define("_WEBPHOTO_LIDASC","レコード番号昇順");
//define("_WEBPHOTO_LIDDESC","レコード番号降順");

//define("_WEBPHOTO_NOSHOTS","サムネイルなし");
//define("_WEBPHOTO_EDITTHISPHOTO","この画像を編集");

//define("_WEBPHOTO_DESCRIPTIONC","説明");
//define("_WEBPHOTO_EMAILC","Email");
//define("_WEBPHOTO_CATEGORYC","カテゴリ");
//define("_WEBPHOTO_SUBCATEGORY","サブカテゴリ");
//define("_WEBPHOTO_LASTUPDATEC","前回更新");

//define("_WEBPHOTO_HITSC","ヒット数");
//define("_WEBPHOTO_RATINGC","評価");
//define("_WEBPHOTO_NUMVOTES","投票数 %s");
//define("_WEBPHOTO_NUMPOSTS","コメント数 %s");
//define("_WEBPHOTO_COMMENTSC","コメント数");
//define("_WEBPHOTO_RATETHISPHOTO","投票する");
//define("_WEBPHOTO_MODIFY","変更");
//define("_WEBPHOTO_VSCOMMENTS","コメントを見る/送る");

//define("_WEBPHOTO_DIRECTCATSEL","カテゴリ選択");
//define("_WEBPHOTO_THEREARE","データベースにある画像は <b>%s</b> 枚です");
//define("_WEBPHOTO_LATESTLIST","最新リスト");

//define("_WEBPHOTO_VOTEAPPRE","投票を受け付けました");
//define("_WEBPHOTO_THANKURATE","当サイト %s へのご投票、ありがとうございました");
//define("_WEBPHOTO_VOTEONCE","同一画像への投票は一度だけにお願いします。");
//define("_WEBPHOTO_RATINGSCALE","評価は 1 から 10 までです： 1 が最低、 10 が最高");
//define("_WEBPHOTO_BEOBJECTIVE","客観的な評価をお願いします。点数が1か10のみだと順位付けの意味がありません");
//define("_WEBPHOTO_DONOTVOTE","自分が登録した画像は投票できません。");
//define("_WEBPHOTO_RATEIT","投票する!");

//define("_WEBPHOTO_RECEIVED","画像を登録しました。ご投稿有難うございます。");
//define("_WEBPHOTO_ALLPENDING","すべての投稿画像は確認のため仮登録となります。");

//define("_WEBPHOTO_RANK","ランク");
//define("_WEBPHOTO_SUBCATEGORY","サブカテゴリ");
//define("_WEBPHOTO_HITS","ヒット");
//define("_WEBPHOTO_RATING","評価");
//define("_WEBPHOTO_VOTE","投票");
//define("_WEBPHOTO_TOP10","%s のトップ10"); // %s はカテゴリのタイトル

//define("_WEBPHOTO_SORTBY","並び替え:");
//define("_WEBPHOTO_TITLE","タイトル");
//define("_WEBPHOTO_DATE","日時");
//define("_WEBPHOTO_POPULARITY","ヒット数");
//define("_WEBPHOTO_CURSORTEDBY","現在の並び順: %s");
//define("_WEBPHOTO_FOUNDIN","見つかったのはここ:");
//define("_WEBPHOTO_PREVIOUS","前");
//define("_WEBPHOTO_NEXT","次");
//define("_WEBPHOTO_NOMATCH","画像がありません");

//define("_WEBPHOTO_CATEGORIES","カテゴリ");
//define("_WEBPHOTO_SUBMIT","投稿");
//define("_WEBPHOTO_CANCEL","キャンセル");

//define("_WEBPHOTO_MUSTREGFIRST","申し訳ありませんがアクセス権限がありません。<br>登録するか、ログイン後にお願いします。");
//define("_WEBPHOTO_MUSTADDCATFIRST","追加するためにはカテゴリが必要です。<br>まずカテゴリを作成して下さい。");
//define("_WEBPHOTO_NORATING","評価が選択されてません。");
//define("_WEBPHOTO_CANTVOTEOWN","自分の投稿画像には投票できません。<br>投票には全て目を通します");
//define("_WEBPHOTO_VOTEONCE2","選択画像への投票は一度だけにお願いします。<br>投票にはすべて目を通します。");


//---------------------------------------------------------
// move to admin.php
//---------------------------------------------------------
//%%%%%%	Module Name 'MyAlbum' (Admin)	  %%%%%
//define("_WEBPHOTO_PHOTOSWAITING","投稿された画像の承認: 承認待画像数");
//define("_WEBPHOTO_PHOTOMANAGER","画像管理");
//define("_WEBPHOTO_CATEDIT","カテゴリの追加・編集");
//define("_WEBPHOTO_GROUPPERM_GLOBAL","各グループの権限");
//define("_WEBPHOTO_CHECKCONFIGS","モジュールの状態チェック");
//define("_WEBPHOTO_BATCHUPLOAD","画像一括登録");
//define("_WEBPHOTO_GENERALSET","一般設定");
//define("_WEBPHOTO_REDOTHUMBS2","Rebuild Thumbnails");

//define("_WEBPHOTO_DELETE","削除");
//define("_WEBPHOTO_NOSUBMITTED","新規の投稿画像はありません。");
//define("_WEBPHOTO_ADDMAIN","トップカテゴリを追加");
//define("_WEBPHOTO_IMGURL","画像のURL (画像の高さはあらかじめ50pixelに): ");
//define("_WEBPHOTO_ADD","追加");
//define("_WEBPHOTO_ADDSUB","サブカテゴリの追加");
//define("_WEBPHOTO_IN","");
//define("_WEBPHOTO_MODCAT","カテゴリ変更");

//define("_WEBPHOTO_MODREQDELETED","変更要請を削除");
//define("_WEBPHOTO_IMGURLMAIN","画像URL (画像の高さはあらかじめ50pixelに): ");
//define("_WEBPHOTO_PARENT","親カテゴリ:");
//define("_WEBPHOTO_SAVE","変更を保存");
//define("_WEBPHOTO_CATDELETED","カテゴリの消去完了");
//define("_WEBPHOTO_CATDEL_WARNING","カテゴリと同時にここに含まれる画像およびコメントが全て削除されますがよろしいですか？");
//define("_WEBPHOTO_YES","はい");
//define("_WEBPHOTO_NO","いいえ");
//define("_WEBPHOTO_NEWCATADDED","新カテゴリ追加に成功!");
//define("_WEBPHOTO_ERROREXIST","エラー: 提供される画像はすでにデータベースに存在します。");
//define("_WEBPHOTO_ERRORTITLE","エラー: タイトルが必要です!");
//define("_WEBPHOTO_ERRORDESC","エラー: 説明が必要です!");
//define("_WEBPHOTO_WEAPPROVED","画像データベースへのリンク要請を承認しました。");
//define("_WEBPHOTO_THANKSSUBMIT","ご投稿有り難うございます。");
//define("_WEBPHOTO_CONFUPDATED","設定を更新しました。");


//---------------------------------------------------------
// move from myalbum_constants.php
//---------------------------------------------------------
// Caption
define( "_WEBPHOTO_CAPTION_TOTAL" , "Total:" ) ;
define( "_WEBPHOTO_CAPTION_GUESTNAME" , "ゲスト" ) ;
define( "_WEBPHOTO_CAPTION_REFRESH" , "更新" ) ;
define( "_WEBPHOTO_CAPTION_IMAGEXYT" , "サイズ" ) ;
define( "_WEBPHOTO_CAPTION_CATEGORY" , "カテゴリ" ) ;


//=========================================================
// add for webphoto
//=========================================================

//---------------------------------------------------------
// database table items
//---------------------------------------------------------

// photo table
define("_WEBPHOTO_PHOTO_TABLE" , "写真テーブル" ) ;
define("_WEBPHOTO_PHOTO_ID" , "写真ID" ) ;
define("_WEBPHOTO_PHOTO_TIME_CREATE" , "作成日時" ) ;
define("_WEBPHOTO_PHOTO_TIME_UPDATE" , "更新日時" ) ;
define("_WEBPHOTO_PHOTO_CAT_ID" ,  "カテゴリ番号" ) ;
define("_WEBPHOTO_PHOTO_GICON_ID" , "GoogleMap アイコン番号" ) ;
define("_WEBPHOTO_PHOTO_UID" ,   "ユーザ番号" ) ;
define("_WEBPHOTO_PHOTO_DATETIME" ,  "撮影日時" ) ;
define("_WEBPHOTO_PHOTO_TITLE" , "写真タイトル" ) ;
define("_WEBPHOTO_PHOTO_PLACE" , "撮影場所" ) ;
define("_WEBPHOTO_PHOTO_EQUIPMENT" , "撮影機材" ) ;
define("_WEBPHOTO_PHOTO_FILE_URL" ,  "ファイル URL" ) ;
define("_WEBPHOTO_PHOTO_FILE_PATH" , "ファイル パス" ) ;
define("_WEBPHOTO_PHOTO_FILE_NAME" , "ファイル 名" ) ;
define("_WEBPHOTO_PHOTO_FILE_EXT" ,  "ファイル 拡張子" ) ;
define("_WEBPHOTO_PHOTO_FILE_MIME" ,  "ファイル MIMEタイプ" ) ;
define("_WEBPHOTO_PHOTO_FILE_MEDIUM" ,  "ファイル メディアタイプ" ) ;
define("_WEBPHOTO_PHOTO_FILE_SIZE" , "ファイル サイズ" ) ;
define("_WEBPHOTO_PHOTO_CONT_URL" ,    "写真 URL" ) ;
define("_WEBPHOTO_PHOTO_CONT_PATH" ,   "写真 パス" ) ;
define("_WEBPHOTO_PHOTO_CONT_NAME" ,   "写真 ファイル名" ) ;
define("_WEBPHOTO_PHOTO_CONT_EXT" ,    "写真 拡張子" ) ;
define("_WEBPHOTO_PHOTO_CONT_MIME" ,   "写真 MIMEタイプ" ) ;
define("_WEBPHOTO_PHOTO_CONT_MEDIUM" , "写真 メディアタイプ" ) ;
define("_WEBPHOTO_PHOTO_CONT_SIZE" ,   "写真 ファイルサイズ" ) ;
define("_WEBPHOTO_PHOTO_CONT_WIDTH" ,  "写真 画像横幅" ) ;
define("_WEBPHOTO_PHOTO_CONT_HEIGHT" , "写真 画像高さ" ) ;
define("_WEBPHOTO_PHOTO_CONT_DURATION" , "ビデオ再生時間" ) ;
define("_WEBPHOTO_PHOTO_CONT_EXIF" , "Exif 情報" ) ;
define("_WEBPHOTO_PHOTO_MIDDLE_WIDTH" ,  "ミドル 画像横幅" ) ;
define("_WEBPHOTO_PHOTO_MIDDLE_HEIGHT" , "ミドル 画像高さ" ) ;
define("_WEBPHOTO_PHOTO_THUMB_URL" ,    "サムネイル URL" ) ;
define("_WEBPHOTO_PHOTO_THUMB_PATH" ,   "サムネイル パス" ) ;
define("_WEBPHOTO_PHOTO_THUMB_NAME" ,   "サムネイル ファイル名" ) ;
define("_WEBPHOTO_PHOTO_THUMB_EXT" ,    "サムネイル 拡張子" ) ;
define("_WEBPHOTO_PHOTO_THUMB_MIME" ,   "サムネイル MIMEタイプ" ) ;
define("_WEBPHOTO_PHOTO_THUMB_MEDIUM" , "サムネイル メディアタイプ" ) ;
define("_WEBPHOTO_PHOTO_THUMB_SIZE" ,   "サムネイル ファイルサイズ" ) ;
define("_WEBPHOTO_PHOTO_THUMB_WIDTH" ,  "サムネイル 画像横幅" ) ;
define("_WEBPHOTO_PHOTO_THUMB_HEIGHT" , "サムネイル 画像高さ" ) ;
define("_WEBPHOTO_PHOTO_GMAP_LATITUDE" ,  "GoogleMap 緯度" ) ;
define("_WEBPHOTO_PHOTO_GMAP_LONGITUDE" , "GoogleMap 経度" ) ;
define("_WEBPHOTO_PHOTO_GMAP_ZOOM" ,      "GoogleMap ズーム" ) ;
define("_WEBPHOTO_PHOTO_GMAP_TYPE" ,      "GoogleMap タイプ" ) ;
define("_WEBPHOTO_PHOTO_PERM_READ" , "閲覧権限" ) ;
define("_WEBPHOTO_PHOTO_STATUS" ,   "状態" ) ;
define("_WEBPHOTO_PHOTO_HITS" ,     "ヒット数" ) ;
define("_WEBPHOTO_PHOTO_RATING" ,   "評価" ) ;
define("_WEBPHOTO_PHOTO_VOTES" ,    "投票数" ) ;
define("_WEBPHOTO_PHOTO_COMMENTS" , "コメント数" ) ;
define("_WEBPHOTO_PHOTO_TEXT1" ,  "text1" ) ;
define("_WEBPHOTO_PHOTO_TEXT2" ,  "text2" ) ;
define("_WEBPHOTO_PHOTO_TEXT3" ,  "text3" ) ;
define("_WEBPHOTO_PHOTO_TEXT4" ,  "text4" ) ;
define("_WEBPHOTO_PHOTO_TEXT5" ,  "text5" ) ;
define("_WEBPHOTO_PHOTO_TEXT6" ,  "text6" ) ;
define("_WEBPHOTO_PHOTO_TEXT7" ,  "text7" ) ;
define("_WEBPHOTO_PHOTO_TEXT8" ,  "text8" ) ;
define("_WEBPHOTO_PHOTO_TEXT9" ,  "text9" ) ;
define("_WEBPHOTO_PHOTO_TEXT10" , "text10" ) ;
define("_WEBPHOTO_PHOTO_DESCRIPTION" ,  "写真説明文" ) ;
define("_WEBPHOTO_PHOTO_SEARCH" ,  "検索文" ) ;

// category table
define("_WEBPHOTO_CAT_TABLE" , "カテゴリテーブル" ) ;
define("_WEBPHOTO_CAT_ID" ,          "カテゴリID" ) ;
define("_WEBPHOTO_CAT_TIME_CREATE" , "作成日時" ) ;
define("_WEBPHOTO_CAT_TIME_UPDATE" , "更新日時" ) ;
define("_WEBPHOTO_CAT_GICON_ID" ,  "GoogleMap アイコン番号" ) ;
define("_WEBPHOTO_CAT_FORUM_ID" ,  "フォーラム番号" ) ;
define("_WEBPHOTO_CAT_PID" ,    "親番号" ) ;
define("_WEBPHOTO_CAT_TITLE" ,  "カテゴリ名" ) ;
define("_WEBPHOTO_CAT_IMG_PATH" , "カテゴリ画像の相対パス" ) ;
define("_WEBPHOTO_CAT_IMG_MODE" , "画像の表示モード" ) ;
define("_WEBPHOTO_CAT_ORIG_WIDTH" ,  "画像の原寸の横幅" ) ;
define("_WEBPHOTO_CAT_ORIG_HEIGHT" , "画像の原寸の高さ" ) ;
define("_WEBPHOTO_CAT_MAIN_WIDTH" ,  "メインカテゴリ表示の画像の横幅" ) ;
define("_WEBPHOTO_CAT_MAIN_HEIGHT" , "メインカテゴリ表示の画像の高さ" ) ;
define("_WEBPHOTO_CAT_SUB_WIDTH" ,   "サブカテゴリ表示の画像の横幅" ) ;
define("_WEBPHOTO_CAT_SUB_HEIGHT" ,  "サブカテゴリ表示の画像の高さ" ) ;
define("_WEBPHOTO_CAT_WEIGHT" , "表示順" ) ;
define("_WEBPHOTO_CAT_DEPTH" ,  "深さ" ) ;
define("_WEBPHOTO_CAT_ALLOWED_EXT" , "許可された拡張子" ) ;
define("_WEBPHOTO_CAT_ITEM_TYPE" ,      "記事のタイプ" ) ;
define("_WEBPHOTO_CAT_GMAP_MODE" ,      "GoogleMap 表示モード" ) ;
define("_WEBPHOTO_CAT_GMAP_LATITUDE" ,  "GoogleMap 緯度" ) ;
define("_WEBPHOTO_CAT_GMAP_LONGITUDE" , "GoogleMap 経度" ) ;
define("_WEBPHOTO_CAT_GMAP_ZOOM" ,      "GoogleMap ズーム" ) ;
define("_WEBPHOTO_CAT_GMAP_TYPE" ,      "GoogleMap タイプ" ) ;
define("_WEBPHOTO_CAT_PERM_READ" , "閲覧権限" ) ;
define("_WEBPHOTO_CAT_PERM_POST" , "投稿権限" ) ;
define("_WEBPHOTO_CAT_TEXT1" ,  "text1" ) ;
define("_WEBPHOTO_CAT_TEXT2" ,  "text2" ) ;
define("_WEBPHOTO_CAT_TEXT3" ,  "text3" ) ;
define("_WEBPHOTO_CAT_TEXT4" ,  "text4" ) ;
define("_WEBPHOTO_CAT_TEXT5" ,  "text5" ) ;
define("_WEBPHOTO_CAT_DESCRIPTION" ,  "カテゴリ説明文" ) ;

// vote table
define("_WEBPHOTO_VOTE_TABLE" , "投票テーブル" ) ;
define("_WEBPHOTO_VOTE_ID" ,          "投票ID" ) ;
define("_WEBPHOTO_VOTE_TIME_CREATE" , "作成日時" ) ;
define("_WEBPHOTO_VOTE_TIME_UPDATE" , "更新日時" ) ;
define("_WEBPHOTO_VOTE_PHOTO_ID" , "写真番号" ) ;
define("_WEBPHOTO_VOTE_UID" ,      "ユーザ番号" ) ;
define("_WEBPHOTO_VOTE_RATING" ,   "評価" ) ;
define("_WEBPHOTO_VOTE_HOSTNAME" , "IPアドレス" ) ;

// google icon table
define("_WEBPHOTO_GICON_TABLE" , "Googleアイコンテーブル" ) ;
define("_WEBPHOTO_GICON_ID" ,          "アイコンID" ) ;
define("_WEBPHOTO_GICON_TIME_CREATE" , "作成日時" ) ;
define("_WEBPHOTO_GICON_TIME_UPDATE" , "更新日時" ) ;
define("_WEBPHOTO_GICON_TITLE" ,     "アイコン名" ) ;
define("_WEBPHOTO_GICON_IMAGE_PATH" ,  "本体 パス" ) ;
define("_WEBPHOTO_GICON_IMAGE_NAME" ,  "本体 ファイル名" ) ;
define("_WEBPHOTO_GICON_IMAGE_EXT" ,   "本体 拡張子" ) ;
define("_WEBPHOTO_GICON_SHADOW_PATH" , "シャドー パス" ) ;
define("_WEBPHOTO_GICON_SHADOW_NAME" , "シャドー ファイル名" ) ;
define("_WEBPHOTO_GICON_SHADOW_EXT" ,  "シャドー 拡張子" ) ;
define("_WEBPHOTO_GICON_IMAGE_WIDTH" ,  "本体 画像横幅" ) ;
define("_WEBPHOTO_GICON_IMAGE_HEIGHT" , "本体 画像高さ" ) ;
define("_WEBPHOTO_GICON_SHADOW_WIDTH" ,  "シャドー 画像横幅" ) ;
define("_WEBPHOTO_GICON_SHADOW_HEIGHT" , "シャドー 画像高さ" ) ;
define("_WEBPHOTO_GICON_ANCHOR_X" , "アンカー Xサイズ" ) ;
define("_WEBPHOTO_GICON_ANCHOR_Y" , "アンカー Yサイズ" ) ;
define("_WEBPHOTO_GICON_INFO_X" , "WindowInfo Xサイズ" ) ;
define("_WEBPHOTO_GICON_INFO_Y" , "WindowInfo Yサイズ" ) ;

// mime type table
define("_WEBPHOTO_MIME_TABLE" , "MIMEタイプテーブル" ) ;
define("_WEBPHOTO_MIME_ID" ,          "MIME ID" ) ;
define("_WEBPHOTO_MIME_TIME_CREATE" , "作成日時" ) ;
define("_WEBPHOTO_MIME_TIME_UPDATE" , "更新日時" ) ;
define("_WEBPHOTO_MIME_EXT" ,   "拡張子" ) ;
define("_WEBPHOTO_MIME_MEDIUM" ,  "メディアタイプ" ) ;
define("_WEBPHOTO_MIME_TYPE" ,  "MIMEタイプ" ) ;
define("_WEBPHOTO_MIME_NAME" ,  "MIME名称" ) ;
define("_WEBPHOTO_MIME_PERMS" , "パーミッション" ) ;

// added in v0.20
define("_WEBPHOTO_MIME_FFMPEG" , "ffmpeg オプション" ) ;

// tag table
define("_WEBPHOTO_TAG_TABLE" , "タグテーブル" ) ;
define("_WEBPHOTO_TAG_ID" ,          "タグID" ) ;
define("_WEBPHOTO_TAG_TIME_CREATE" , "作成日時" ) ;
define("_WEBPHOTO_TAG_TIME_UPDATE" , "更新日時" ) ;
define("_WEBPHOTO_TAG_NAME" ,   "タグ名" ) ;

// photo-to-tag table
define("_WEBPHOTO_P2T_TABLE" , "写真タグ関連テーブル" ) ;
define("_WEBPHOTO_P2T_ID" ,          "写真タグ関連ID" ) ;
define("_WEBPHOTO_P2T_TIME_CREATE" , "作成日時" ) ;
define("_WEBPHOTO_P2T_TIME_UPDATE" , "更新日時" ) ;
define("_WEBPHOTO_P2T_PHOTO_ID" , "写真番号" ) ;
define("_WEBPHOTO_P2T_TAG_ID" ,   "タグ番号" ) ;
define("_WEBPHOTO_P2T_UID" ,      "ユーザ番号" ) ;

// synonym table
define("_WEBPHOTO_SYNO_TABLE" , "類似語テーブル" ) ;
define("_WEBPHOTO_SYNO_ID" ,          "類似語ID" ) ;
define("_WEBPHOTO_SYNO_TIME_CREATE" , "作成日時" ) ;
define("_WEBPHOTO_SYNO_TIME_UPDATE" , "更新日時" ) ;
define("_WEBPHOTO_SYNO_WEIGHT" , "並び順" ) ;
define("_WEBPHOTO_SYNO_KEY" , "キー" ) ;
define("_WEBPHOTO_SYNO_VALUE" , "類似語" ) ;


//---------------------------------------------------------
// title
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_LATEST","新着");
define("_WEBPHOTO_TITLE_SUBMIT","投稿");
define("_WEBPHOTO_TITLE_POPULAR","高人気");
define("_WEBPHOTO_TITLE_HIGHRATE","トップランク");
define("_WEBPHOTO_TITLE_MYPHOTO","自分の投稿");
define("_WEBPHOTO_TITLE_RANDOM","ランダム写真");
define("_WEBPHOTO_TITLE_HELP","ヘルプ");
define("_WEBPHOTO_TITLE_CATEGORY_LIST", "カテゴリ 一覧");
define("_WEBPHOTO_TITLE_TAG_LIST",  "タグ 一覧");
define("_WEBPHOTO_TITLE_TAGS",  "タグ");
define("_WEBPHOTO_TITLE_USER_LIST", "投稿者 一覧");
define("_WEBPHOTO_TITLE_DATE_LIST", "撮影日時 一覧");
define("_WEBPHOTO_TITLE_PLACE_LIST","撮影場所 一覧");
define("_WEBPHOTO_TITLE_RSS","RSS");

define("_WEBPHOTO_VIEWTYPE_LIST", "リスト形式");
define("_WEBPHOTO_VIEWTYPE_TABLE", "テーブル形式");

define("_WEBPHOTO_CATLIST_ON",   "カテゴリを表示する");
define("_WEBPHOTO_CATLIST_OFF",  "カテゴリを表示しない");
define("_WEBPHOTO_TAGCLOUD_ON",  "タグクラウドを表示する");
define("_WEBPHOTO_TAGCLOUD_OFF", "タグクラウドを表示しない");
define("_WEBPHOTO_GMAP_ON",  "Googleマップを表示する");
define("_WEBPHOTO_GMAP_OFF", "Googleマップを表示しない");

define("_WEBPHOTO_NO_TAG","タグが設定されていない");

//---------------------------------------------------------
// google maps
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_GET_LOCATION", "緯度・経度の設定");
define("_WEBPHOTO_GMAP_DESC", "Googleマップのマーカーをクリックすると、サムネイル画像が表示されます");
define("_WEBPHOTO_GMAP_ICON", "GoogleMap アイコン");
define("_WEBPHOTO_GMAP_LATITUDE", "GoogleMap 緯度");
define("_WEBPHOTO_GMAP_LONGITUDE","GoogleMap 経度");
define("_WEBPHOTO_GMAP_ZOOM","GoogleMap ズーム");
define("_WEBPHOTO_GMAP_ADDRESS",  "住所");
define("_WEBPHOTO_GMAP_GET_LOCATION", "緯度・経度を取得する");
define("_WEBPHOTO_GMAP_SEARCH_LIST",  "検索結果の一覧");
define("_WEBPHOTO_GMAP_CURRENT_LOCATION",  "現在の位置");
define("_WEBPHOTO_GMAP_CURRENT_ADDRESS",  "現在の住所");
define("_WEBPHOTO_GMAP_NO_MATCH_PLACE",  "該当する場所がない");
define("_WEBPHOTO_GMAP_NOT_COMPATIBLE", "貴方のブラウザでは GoogleMaps を表示できません");
define("_WEBPHOTO_JS_INVALID", "貴方のブラウザでは JavaScript が使用できません");
define("_WEBPHOTO_IFRAME_NOT_SUPPORT","貴方のブラウザでは iframe が使用できない");

//---------------------------------------------------------
// search
//---------------------------------------------------------
define("_WEBPHOTO_SR_SEARCH","検索");

//---------------------------------------------------------
// popbox
//---------------------------------------------------------
define("_WEBPHOTO_POPBOX_REVERT", "クリックすると、元の小さい写真になる");

//---------------------------------------------------------
// tag
//---------------------------------------------------------
define("_WEBPHOTO_TAGS","タグ");
define("_WEBPHOTO_EDIT_TAG","タグを編集する");
define("_WEBPHOTO_DSC_TAG_DIVID", "複数個 設定する場合は カンマ , で区切る");
define("_WEBPHOTO_DSC_TAG_EDITABLE", "自分が登録したタグのみ編集できます");

//---------------------------------------------------------
// submit form
//---------------------------------------------------------
define("_WEBPHOTO_CAP_ALLOWED_EXTS", "許可されている拡張子");
define("_WEBPHOTO_CAP_PHOTO_SELECT","メイン画像の選択");
define("_WEBPHOTO_CAP_THUMB_SELECT", "サムネイル画像の選択");
define("_WEBPHOTO_DSC_THUMB_SELECT", "指定しないときは、メイン画像より自動生成される");
define("_WEBPHOTO_DSC_SET_DATETIME",   "撮影日時を設定する");

//define("_WEBPHOTO_DSC_SET_TIME_UPDATE", "更新日時を変更する");

define("_WEBPHOTO_DSC_PIXCEL_RESIZE", "これ以上大きい画像はリサイズします");
define("_WEBPHOTO_DSC_PIXCEL_REJECT", "これ以上大きい画像はアップロードできません");
define("_WEBPHOTO_BUTTON_CLEAR", "リセット");
define("_WEBPHOTO_SUBMIT_RESIZED", "画像が大きいので、リサイズした");

// PHP upload error
// http://www.php.net/manual/en/features.file-upload.errors.php
define("_WEBPHOTO_PHP_UPLOAD_ERR_OK", "エラーはなく、ファイルアップロードは成功しています");
define("_WEBPHOTO_PHP_UPLOAD_ERR_INI_SIZE", "アップロードされたファイルは、upload_max_filesize の値を超えています");
define("_WEBPHOTO_PHP_UPLOAD_ERR_FORM_SIZE", "アップロードされたファイルは、%s を超えています");
define("_WEBPHOTO_PHP_UPLOAD_ERR_PARTIAL", "アップロードされたファイルは一部のみしかアップロードされていません");
define("_WEBPHOTO_PHP_UPLOAD_ERR_NO_FILE", "ファイルはアップロードされませんでした");
define("_WEBPHOTO_PHP_UPLOAD_ERR_NO_TMP_DIR", "テンポラリフォルダがありません");
define("_WEBPHOTO_PHP_UPLOAD_ERR_CANT_WRITE", "ディスクへの書き込みに失敗しました");
define("_WEBPHOTO_PHP_UPLOAD_ERR_EXTENSION", "ファイルのアップロードが拡張モジュールによって停止されました");

// upload error
define("_WEBPHOTO_UPLOADER_ERR_NOT_FOUND", "アップロード・ファイルが見つからない");
define("_WEBPHOTO_UPLOADER_ERR_INVALID_FILE_SIZE", "ファイル・サイズが設定されていない");
define("_WEBPHOTO_UPLOADER_ERR_EMPTY_FILE_NAME", "ファイル名が設定されていない");
define("_WEBPHOTO_UPLOADER_ERR_NO_FILE", "ファイルはアップロードされてない");
define("_WEBPHOTO_UPLOADER_ERR_NOT_SET_DIR", "アップロード・ディレクトリが設定されていない");
define("_WEBPHOTO_UPLOADER_ERR_NOT_ALLOWED_EXT", "許可されていない拡張子です");
define("_WEBPHOTO_UPLOADER_ERR_PHP_OCCURED", "アップローダーでエラーが発生した ");
define("_WEBPHOTO_UPLOADER_ERR_NOT_OPEN_DIR", "アップロード・ディレクトリがオープンできない ");
define("_WEBPHOTO_UPLOADER_ERR_NO_PERM_DIR", "アップロード・ディレクトリのアクセス権限がない ");
define("_WEBPHOTO_UPLOADER_ERR_NOT_ALLOWED_MIME", "許可されていないMIMEタイプです ");
define("_WEBPHOTO_UPLOADER_ERR_LARGE_FILE_SIZE", "ファイル・サイズが大きすぎる ");
define("_WEBPHOTO_UPLOADER_ERR_LARGE_WIDTH", "画像横幅が大きすぎる ");
define("_WEBPHOTO_UPLOADER_ERR_LARGE_HEIGHT", "画像高さが大きすぎる ");
define("_WEBPHOTO_UPLOADER_ERR_UPLOAD", "アップロードに失敗した ");

//---------------------------------------------------------
// help
//---------------------------------------------------------
define("_WEBPHOTO_HELP_DSC", "貴方のパソコンで動作するアプリケーショーンの説明です");

define("_WEBPHOTO_HELP_PICLENS_TITLE", "PicLens");
define("_WEBPHOTO_HELP_PICLENS_DSC", '
Piclens は Cooliris 社が提供する FireFox のアドオンです<br />
WEBサイトの写真を閲覧するビューワーです<br /><br />
<b>参考記事</b><br />
<a href="http://www.forest.impress.co.jp/article/2007/09/13/piclens.html" target="_blank">
画像共有・画像検索サイト専用のビューワーを追加するFirefox拡張「PicLens」
</a><br /><br />
<b>設定方法</b><br />
(1) FireFox をダウンロードする<br />
<a href="http://www.mozilla-japan.org/products/firefox/" target="_blank">
http://www.mozilla-japan.org/products/firefox/
</a><br /><br />
(2) Piclens アドオン をダウンロードする<br />
<a href="http://www.piclens.com/" target="_blank">
http://www.piclens.com/
</a><br /><br />
(3) FireFox で webphoto を見る<br />
http://このサイト/modules/webphoto/ <br /><br />
(4) Firefox の右上の青いマークがクリックする<br />
マークが黒いときは、piclens は使用できない<br />' );

define("_WEBPHOTO_HELP_MEDIARSSSLIDESHOW_TITLE", "Media RSS スライドショー");
define("_WEBPHOTO_HELP_MEDIARSSSLIDESHOW_DSC", '
Media RSS スライドショー は Google ガジェットです<br />
インターネットからの写真をスライドショーで表示します<br /><br />
<b>設定方法</b><br />
(1) Google デスクトップ をダウンロードする<br />
<a href="http://desktop.google.co.jp/" target="_blank">
http://desktop.google.co.jp/
</a><br /><br />
(2) 「Media RSS スライドショー」のガジェットをダウンロードする<br />
<a href="http://desktop.google.com/plugins/i/mediarssslideshow.html" target="_blank">
http://desktop.google.com/plugins/i/mediarssslideshow.html
</a><br /><br />
(3) ガジェットのオプションにて、「MediaRSS の URL」を下記に変更する<br />' );

//---------------------------------------------------------
// others
//---------------------------------------------------------
define("_WEBPHOTO_RANDOM_MORE","ランダム写真をもっと見る");
define("_WEBPHOTO_USAGE_PHOTO","写真をクリックすると、大きな写真がポップアップします");
define("_WEBPHOTO_USAGE_TITLE","タイトルをクリックすると、その写真のページが開きます");
define("_WEBPHOTO_DATE_NOT_SET","撮影日時 設定なし");
define("_WEBPHOTO_PLACE_NOT_SET","撮影場所 設定なし");
define("_WEBPHOTO_GOTO_ADMIN", "管理者画面へ");

//---------------------------------------------------------
// search for Japanese
//---------------------------------------------------------
define("_WEBPHOTO_SR_CANDICATE","検索の候補");
define("_WEBPHOTO_SR_ZENKAKU","全角");
define("_WEBPHOTO_SR_HANKAKU","半角");

define("_WEBPHOTO_JA_KUTEN",   "。");
define("_WEBPHOTO_JA_DOKUTEN", "、");
define("_WEBPHOTO_JA_PERIOD",  "．");
define("_WEBPHOTO_JA_COMMA",   "，");

//---------------------------------------------------------
// v0.20
//---------------------------------------------------------
define("_WEBPHOTO_TITLE_VIDEO_THUMB_SEL", "動画のサムネイルを選択する");
define("_WEBPHOTO_TITLE_VIDEO_REDO","アップロード済みの動画より Flash動画とサムネイルを生成する");
define("_WEBPHOTO_CAP_REDO_THUMB","サムネイルを生成する");
define("_WEBPHOTO_CAP_REDO_FLASH","Flash 動画を生成する");
define("_WEBPHOTO_ERR_VIDEO_FLASH", "Flash 動画を生成できなかった");
define("_WEBPHOTO_ERR_VIDEO_THUMB", "動画のサムネイルが生成できなかったので、アイコンで代用した");
define("_WEBPHOTO_BUTTON_SELECT", "選択");

define("_WEBPHOTO_DSC_DOWNLOAD_PLAY","ダウンロードして再生する");
define("_WEBPHOTO_ICON_VIDEO", "動画");
define("_WEBPHOTO_HOUR", "時間");
define("_WEBPHOTO_MINUTE", "分");
define("_WEBPHOTO_SECOND", "秒");

//---------------------------------------------------------
// v0.30
//---------------------------------------------------------
// user table
define("_WEBPHOTO_USER_TABLE" , "ユーザ補助テーブル" ) ;
define("_WEBPHOTO_USER_ID" ,          "ユーザ補助ID" ) ;
define("_WEBPHOTO_USER_TIME_CREATE" , "作成日時" ) ;
define("_WEBPHOTO_USER_TIME_UPDATE" , "更新日時" ) ;
define("_WEBPHOTO_USER_UID" , "ユーザ番号" ) ;
define("_WEBPHOTO_USER_CAT_ID" , "カテゴリ番号" ) ;
define("_WEBPHOTO_USER_EMAIL" , "メールアドレス" ) ;
define("_WEBPHOTO_USER_TEXT1" ,  "text1" ) ;
define("_WEBPHOTO_USER_TEXT2" ,  "text2" ) ;
define("_WEBPHOTO_USER_TEXT3" ,  "text3" ) ;
define("_WEBPHOTO_USER_TEXT4" ,  "text4" ) ;
define("_WEBPHOTO_USER_TEXT5" ,  "text5" ) ;

// maillog
define("_WEBPHOTO_MAILLOG_TABLE" , "メールログ・テーブル" ) ;
define("_WEBPHOTO_MAILLOG_ID" ,          "メールログID" ) ;
define("_WEBPHOTO_MAILLOG_TIME_CREATE" , "作成日時" ) ;
define("_WEBPHOTO_MAILLOG_TIME_UPDATE" , "更新日時" ) ;
define("_WEBPHOTO_MAILLOG_PHOTO_IDS" , "写真番号" ) ;
define("_WEBPHOTO_MAILLOG_STATUS" , "状態" ) ;
define("_WEBPHOTO_MAILLOG_FROM" , "送信者メールアドレス" ) ;
define("_WEBPHOTO_MAILLOG_SUBJECT" , "題名" ) ;
define("_WEBPHOTO_MAILLOG_BODY" ,  "本文" ) ;
define("_WEBPHOTO_MAILLOG_FILE" ,  "ファイル名" ) ;
define("_WEBPHOTO_MAILLOG_ATTACH" ,  "添付ファイル" ) ;
define("_WEBPHOTO_MAILLOG_COMMENT" ,  "コメント" ) ;

// mail register
define("_WEBPHOTO_TITLE_MAIL_REGISTER" ,  "メルアド登録" ) ;
define("_WEBPHOTO_MAIL_HELP" ,  "使い方はヘルプをご覧ください" ) ;
define("_WEBPHOTO_CAT_USER" ,  "ユーザ名" ) ;
define("_WEBPHOTO_BUTTON_REGISTER" ,  "登録" ) ;
define("_WEBPHOTO_NOMATCH_USER","該当するユーザがいない");
define("_WEBPHOTO_ERR_MAIL_EMPTY","メールアドレスが必要です");
define("_WEBPHOTO_ERR_MAIL_ILLEGAL","メールアドレスの形式がおかしい");

// mail retrieve
define("_WEBPHOTO_TITLE_MAIL_RETRIEVE" ,  "メール受信" ) ;
define("_WEBPHOTO_DSC_MAIL_RETRIEVE" ,  "メールサーバーからメールを受信する" ) ;
define("_WEBPHOTO_BUTTON_RETRIEVE" ,  "メール受信" ) ;
define("_WEBPHOTO_SUBTITLE_MAIL_ACCESS" ,  "メールサーバーにアクセスする" ) ;
define("_WEBPHOTO_SUBTITLE_MAIL_PARSE" ,  "受信したメールを解読します" ) ;
define("_WEBPHOTO_SUBTITLE_MAIL_PHOTO" ,  "メールに添付された写真を登録します" ) ;
define("_WEBPHOTO_TEXT_MAIL_ACCESS_TIME" ,  "アクセス制限中です" ) ;
define("_WEBPHOTO_TEXT_MAIL_RETRY"  ,  "１分後にアクセスしてください" ) ;
define("_WEBPHOTO_TEXT_MAIL_NOT_RETRIEVE" ,  "メールを受信できなかった。<br />一時的な通信障害と思われます。<br />しばらく時間をあけてから、試みてください。" ) ;
define("_WEBPHOTO_TEXT_MAIL_NO_NEW" ,  "新着メールはありません" ) ;
define("_WEBPHOTO_TEXT_MAIL_RETRIEVED_FMT" ,  "%s 件のメールを受信しました" ) ;
define("_WEBPHOTO_TEXT_MAIL_NO_VALID" ,  "有効なメールはありません" ) ;
define("_WEBPHOTO_TEXT_MAIL_SUBMITED_FMT" ,  "%s 件の写真を登録しました" ) ;
define("_WEBPHOTO_GOTO_INDEX" ,  "モジュールのトップページへ" ) ;

// i.php
define("_WEBPHOTO_TITLE_MAIL_POST" ,  "メールから投稿する" ) ;

// file
define("_WEBPHOTO_TITLE_SUBMIT_FILE" , "ファイルからの画像追加" ) ;
define("_WEBPHOTO_CAP_FILE_SELECT", "ファイルの選択");
define("_WEBPHOTO_ERR_EMPTY_FILE" , "ファイルを指定してください" ) ;
define("_WEBPHOTO_ERR_EMPTY_CAT" , "カテゴリを指定してください" ) ;
define("_WEBPHOTO_ERR_INVALID_CAT" , "無効なカテゴリです" ) ;
define("_WEBPHOTO_ERR_CREATE_PHOTO" , "画像を登録できなかった" ) ;
define("_WEBPHOTO_ERR_CREATE_THUMB" , "サムネイルを登録できなかった" ) ;

// help
define("_WEBPHOTO_HELP_MUST_LOGIN","詳しい説明を読むには、ログインしてください");
define("_WEBPHOTO_HELP_NOT_PERM", "貴方には許可されていません。管理者までお問い合わせください。");

define("_WEBPHOTO_HELP_MOBILE_TITLE", "携帯電話");
define("_WEBPHOTO_HELP_MOBILE_DSC", "携帯電話にて、写真や動画を表示することができます<br/>240×320 程度の画面サイズです");
define("_WEBPHOTO_HELP_MOBILE_TEXT_FMT", '
<b>アクセスURL</b><br />
<a href="{MODULE_URL}/i.php" target="_blank">{MODULE_URL}/i.php</a>');

define("_WEBPHOTO_HELP_MAIL_TITLE", "携帯メールによる投稿");
define("_WEBPHOTO_HELP_MAIL_DSC", "携帯電話からメールを送信して、写真や動画を投稿することができます");
define("_WEBPHOTO_HELP_MAIL_GUEST", "これは見本です。権限がないと、正しいメールアドレスは表示されません");

define("_WEBPHOTO_HELP_FILE_TITLE", "FTP による投稿");
define("_WEBPHOTO_HELP_FILE_DSC", "FTP によりファイルをアップロードすることで、ファイル容量の大きな写真や動画を投稿することができます");
define("_WEBPHOTO_HELP_FILE_TEXT_FMT", '
<b>投稿方法</b><br />
(1) 指定された FTP サーバーにファイルをアップロードする<br />
(2) <a href="{MODULE_URL}/index.php?fct=submit_file" target="_blank">「ファイルからの画像追加」</a> をクリックする<br />
(3) アップロードしたファイルを指定して投稿する' );

// mail check
// for Japanese
define("_WEBPHOTO_MAIL_DENY_TITLE_PREG", "/((未|末)\s?承\s?(諾|認)\s?広\s?告|相互リンク|18禁|サイトのご紹介)/i" ) ;
define("_WEBPHOTO_MAIL_AD_WORD_1", "会員登録は無料  充実した出品アイテムなら MSN オークション" ) ;
define("_WEBPHOTO_MAIL_AD_WORD_2", "友達と24時間ホットライン「MSN メッセンジャー」、今すぐダウンロード！" ) ;

//---------------------------------------------------------
// v0.40
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_TABLE" , "アイテム・テーブル" ) ;
define("_WEBPHOTO_ITEM_ID" , "アイテムID" ) ;
define("_WEBPHOTO_ITEM_TIME_CREATE" , "作成日時" ) ;
define("_WEBPHOTO_ITEM_TIME_UPDATE" , "更新日時" ) ;
define("_WEBPHOTO_ITEM_CAT_ID" ,  "カテゴリ番号" ) ;
define("_WEBPHOTO_ITEM_GICON_ID" , "GoogleMap アイコン番号" ) ;
define("_WEBPHOTO_ITEM_UID" ,   "ユーザ番号" ) ;
define("_WEBPHOTO_ITEM_KIND" , "種別" ) ;
define("_WEBPHOTO_ITEM_EXT" ,  "拡張子" ) ;
define("_WEBPHOTO_ITEM_DATETIME" ,  "撮影日時" ) ;
define("_WEBPHOTO_ITEM_TITLE" , "写真タイトル" ) ;
define("_WEBPHOTO_ITEM_PLACE" , "撮影場所" ) ;
define("_WEBPHOTO_ITEM_EQUIPMENT" , "撮影機材" ) ;
define("_WEBPHOTO_ITEM_GMAP_LATITUDE" ,  "GoogleMap 緯度" ) ;
define("_WEBPHOTO_ITEM_GMAP_LONGITUDE" , "GoogleMap 経度" ) ;
define("_WEBPHOTO_ITEM_GMAP_ZOOM" ,      "GoogleMap ズーム" ) ;
define("_WEBPHOTO_ITEM_GMAP_TYPE" ,      "GoogleMap タイプ" ) ;
define("_WEBPHOTO_ITEM_PERM_READ" , "閲覧権限" ) ;
define("_WEBPHOTO_ITEM_STATUS" ,   "状態" ) ;
define("_WEBPHOTO_ITEM_HITS" ,     "ヒット数" ) ;
define("_WEBPHOTO_ITEM_RATING" ,   "評価" ) ;
define("_WEBPHOTO_ITEM_VOTES" ,    "投票数" ) ;
define("_WEBPHOTO_ITEM_DESCRIPTION" ,  "写真説明文" ) ;
define("_WEBPHOTO_ITEM_EXIF" , "Exif 情報" ) ;
define("_WEBPHOTO_ITEM_SEARCH" ,  "検索文" ) ;
define("_WEBPHOTO_ITEM_COMMENTS" , "コメント数" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_1" ,  "ファイル番号：コンテンツ" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_2" ,  "ファイル番号：サムネイル" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_3" ,  "ファイル番号：ミドル" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_4" ,  "ファイル番号：Flash ビデオ" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_5" ,  "ファイル番号：ドコモ ビデオ" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_6" ,  "file6" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_7" ,  "file7" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_8" ,  "file8" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_9" ,  "file9" ) ;
define("_WEBPHOTO_ITEM_FILE_ID_10" , "file10" ) ;
define("_WEBPHOTO_ITEM_TEXT_1" ,  "text1" ) ;
define("_WEBPHOTO_ITEM_TEXT_2" ,  "text2" ) ;
define("_WEBPHOTO_ITEM_TEXT_3" ,  "text3" ) ;
define("_WEBPHOTO_ITEM_TEXT_4" ,  "text4" ) ;
define("_WEBPHOTO_ITEM_TEXT_5" ,  "text5" ) ;
define("_WEBPHOTO_ITEM_TEXT_6" ,  "text6" ) ;
define("_WEBPHOTO_ITEM_TEXT_7" ,  "text7" ) ;
define("_WEBPHOTO_ITEM_TEXT_8" ,  "text8" ) ;
define("_WEBPHOTO_ITEM_TEXT_9" ,  "text9" ) ;
define("_WEBPHOTO_ITEM_TEXT_10" , "text10" ) ;

// file table
define("_WEBPHOTO_FILE_TABLE" , "ファイル・テーブル" ) ;
define("_WEBPHOTO_FILE_ID" , "ファイルID" ) ;
define("_WEBPHOTO_FILE_TIME_CREATE" , "作成日時" ) ;
define("_WEBPHOTO_FILE_TIME_UPDATE" , "更新日時" ) ;
define("_WEBPHOTO_FILE_ITEM_ID" ,  "アイテム番号" ) ;
define("_WEBPHOTO_FILE_KIND" , "種別" ) ;
define("_WEBPHOTO_FILE_URL" ,    "URL" ) ;
define("_WEBPHOTO_FILE_PATH" ,   "パス" ) ;
define("_WEBPHOTO_FILE_NAME" ,   "ファイル名" ) ;
define("_WEBPHOTO_FILE_EXT" ,    "拡張子" ) ;
define("_WEBPHOTO_FILE_MIME" ,   "MIMEタイプ" ) ;
define("_WEBPHOTO_FILE_MEDIUM" , "メディアタイプ" ) ;
define("_WEBPHOTO_FILE_SIZE" ,   "ファイルサイズ" ) ;
define("_WEBPHOTO_FILE_WIDTH" ,  "画像横幅" ) ;
define("_WEBPHOTO_FILE_HEIGHT" , "画像高さ" ) ;
define("_WEBPHOTO_FILE_DURATION" , "ビデオ再生時間" ) ;

// file kind ( for admin checktables )
define("_WEBPHOTO_FILE_KIND_1" ,  "コンテンツ" ) ;
define("_WEBPHOTO_FILE_KIND_2" ,  "サムネイル" ) ;
define("_WEBPHOTO_FILE_KIND_3" ,  "ミドル" ) ;
define("_WEBPHOTO_FILE_KIND_4" ,  "Flash ビデオ" ) ;
define("_WEBPHOTO_FILE_KIND_5" ,  "ドコモ ビデオ" ) ;
define("_WEBPHOTO_FILE_KIND_6" ,  "file6" ) ;
define("_WEBPHOTO_FILE_KIND_7" ,  "file7" ) ;
define("_WEBPHOTO_FILE_KIND_8" ,  "file8" ) ;
define("_WEBPHOTO_FILE_KIND_9" ,  "file9" ) ;
define("_WEBPHOTO_FILE_KIND_10" , "file10" ) ;

// index
define("_WEBPHOTO_MOBILE_MAILTO" , "携帯電話にURLを送信する" ) ;

// i.php
define("_WEBPHOTO_TITLE_MAIL_JUDGE" ,  "携帯電話機の機種を判定する" ) ;
define("_WEBPHOTO_MAIL_MODEL", "機種" ) ;
define("_WEBPHOTO_MAIL_BROWSER", "WEBブラウザ" ) ;
define("_WEBPHOTO_MAIL_NOT_JUDGE", "機種が判定できない" ) ;
define("_WEBPHOTO_MAIL_TO_WEBMASTER", "サイト管理者に連絡する" ) ;

// help
define("_WEBPHOTO_HELP_MAIL_POST_FMT", '
<b>準備</b><br />
携帯電話のメールアドレスを登録してください<br />
<a href="{MODULE_URL}/index.php?fct=mail_register" target="_blank">「メールアドレス登録」</a><br /><br />
<b>投稿方法</b><br />
下記のメールアドレスにメールを送信する<br />
<a href="mailto:{MAIL_ADDR}">{MAIL_ADDR}</a> {MAIL_GUEST} <br /><br />
<b>写真の回転</b><br />
題名 (Subject) の末尾に下記のように記入することで、写真が回転します。<br />
 R@ : 右回転 <br />
 L@ : 左回転 <br /><br />' );
define("_WEBPHOTO_HELP_MAIL_SUBTITLE_RETRIEVE", "<b>メールの受信と、写真の登録</b><br />" );
define("_WEBPHOTO_HELP_MAIL_RETRIEVE_FMT", '
メールを送信してから、数秒後に <a href="{MODULE_URL}/i.php?op=post" target="_blank">「メールから投稿する」</a> をクリックしてください。<br />' );
define("_WEBPHOTO_HELP_MAIL_RETRIEVE_TEXT", "あなたが送信したメールを取り込んで、写真や動画を掲載します。<br />" );
define("_WEBPHOTO_HELP_MAIL_RETRIEVE_AUTO_FMT", '
メールを送信すると、 %s 秒後に自動的に登録されます。<br />
登録されない場合は、<a href="{MODULE_URL}/i.php?op=post" target="_blank">「メールから投稿する」</a> をクリックしてください。<br />' );


//---------------------------------------------------------
// v0.50
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_TIME_PUBLISH" , "発行日時" ) ;
define("_WEBPHOTO_ITEM_TIME_EXPIRE"   , "終了日時" ) ;
define("_WEBPHOTO_ITEM_PLAYER_ID" ,    "プレイヤー番号" ) ;
define("_WEBPHOTO_ITEM_FLASHVAR_ID" ,  "フラッシュ変数番号" ) ;
define("_WEBPHOTO_ITEM_DURATION" , "ビデオ再生時間" ) ;
define("_WEBPHOTO_ITEM_DISPLAYTYPE", "表示形式");
define("_WEBPHOTO_ITEM_ONCLICK","サムネイルをクリックしたときの動作");
define("_WEBPHOTO_ITEM_SITEURL", "サイトURL");
define("_WEBPHOTO_ITEM_ARTIST", "アーティスト");
define("_WEBPHOTO_ITEM_ALBUM", "アルバム");
define("_WEBPHOTO_ITEM_LABEL", "レーベル");
define("_WEBPHOTO_ITEM_VIEWS", "閲覧数");
define("_WEBPHOTO_ITEM_PERM_DOWN" , "ダウンロード権限" ) ;
define("_WEBPHOTO_ITEM_EMBED_TYPE" ,  "プラグインのタイプ" ) ;
define("_WEBPHOTO_ITEM_EMBED_SRC" ,   "プラグインのURLパラメータ" ) ;
define("_WEBPHOTO_ITEM_EXTERNAL_URL" , "外部リンクのURL" ) ;
define("_WEBPHOTO_ITEM_EXTERNAL_THUMB" , "外部リンクのサムネイルURL" ) ;
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE",  "プレイリストのタイプ" ) ;
define("_WEBPHOTO_ITEM_PLAYLIST_FEED",  "プレイリストのFeed URL" ) ;
define("_WEBPHOTO_ITEM_PLAYLIST_DIR",   "プレイリストのディレクトリ" ) ;
define("_WEBPHOTO_ITEM_PLAYLIST_CACHE", "プレイリストのキャッシュ名" ) ;
define("_WEBPHOTO_ITEM_PLAYLIST_TIME",  "プレイリストのキャッシュ時間" ) ;
define("_WEBPHOTO_ITEM_CHAIN", "チェーン");
define("_WEBPHOTO_ITEM_SHOWINFO", "表示項目");

// player table
define("_WEBPHOTO_PLAYER_TABLE","プレイヤー・テーブル");
define("_WEBPHOTO_PLAYER_ID","プレイヤー ID");
define("_WEBPHOTO_PLAYER_TIME_CREATE" , "作成日時" ) ;
define("_WEBPHOTO_PLAYER_TIME_UPDATE" , "更新日時" ) ;
define("_WEBPHOTO_PLAYER_TITLE","プレイヤー名称 ");
define("_WEBPHOTO_PLAYER_STYLE","スタイル・オプション");
define("_WEBPHOTO_PLAYER_WIDTH","プレイヤー幅");
define("_WEBPHOTO_PLAYER_HEIGHT","プレイヤー高さ");
define("_WEBPHOTO_PLAYER_DISPLAYWIDTH","スクリーン幅");
define("_WEBPHOTO_PLAYER_DISPLAYHEIGHT","スクリーン高さ");
define("_WEBPHOTO_PLAYER_SCREENCOLOR","プレイヤー背景色");
define("_WEBPHOTO_PLAYER_BACKCOLOR","背景色");
define("_WEBPHOTO_PLAYER_FRONTCOLOR","テキスト色");
define("_WEBPHOTO_PLAYER_LIGHTCOLOR","ハイライト色");

// FlashVar table
define("_WEBPHOTO_FLASHVAR_TABLE","Flash変数テーブル");
define("_WEBPHOTO_FLASHVAR_ID","Flash変数 ID");
define("_WEBPHOTO_FLASHVAR_TIME_CREATE" , "作成日時" ) ;
define("_WEBPHOTO_FLASHVAR_TIME_UPDATE" , "更新日時" ) ;
define("_WEBPHOTO_FLASHVAR_ITEM_ID","アイテム ID");
define("_WEBPHOTO_FLASHVAR_WIDTH","プレイヤー幅");
define("_WEBPHOTO_FLASHVAR_HEIGHT","プレイヤー高さ");
define("_WEBPHOTO_FLASHVAR_DISPLAYWIDTH","スクリーン幅");
define("_WEBPHOTO_FLASHVAR_DISPLAYHEIGHT","スクリーン高さ");
define("_WEBPHOTO_FLASHVAR_IMAGE_SHOW","画像の表示");
define("_WEBPHOTO_FLASHVAR_SEARCHBAR","検索バー");
define("_WEBPHOTO_FLASHVAR_SHOWEQ","イコライザー表示");
define("_WEBPHOTO_FLASHVAR_SHOWICONS","プレイボタン表示");
define("_WEBPHOTO_FLASHVAR_SHOWNAVIGATION","コントロール・バー表示");
define("_WEBPHOTO_FLASHVAR_SHOWSTOP","ストップ表示");
define("_WEBPHOTO_FLASHVAR_SHOWDIGITS","経過時間表示");
define("_WEBPHOTO_FLASHVAR_SHOWDOWNLOAD","ダウンロード表示");
define("_WEBPHOTO_FLASHVAR_USEFULLSCREEN","フルスクリーン表示");
define("_WEBPHOTO_FLASHVAR_AUTOSCROLL","スクロールバー");
define("_WEBPHOTO_FLASHVAR_THUMBSINPLAYLIST","サムネイル");
define("_WEBPHOTO_FLASHVAR_AUTOSTART","オートスタート");
define("_WEBPHOTO_FLASHVAR_REPEAT","繰り返し");
define("_WEBPHOTO_FLASHVAR_SHUFFLE","シャフル");
define("_WEBPHOTO_FLASHVAR_SMOOTHING","動画スムーズ");
define("_WEBPHOTO_FLASHVAR_ENABLEJS","JavaScript 有効");
define("_WEBPHOTO_FLASHVAR_LINKFROMDISPLAY","ディスプレイからのリンク");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE","リンク・タイプ");
define("_WEBPHOTO_FLASHVAR_BUFFERLENGTH","バッファ・サイズ");
define("_WEBPHOTO_FLASHVAR_ROTATETIME","ローテイト時間");
define("_WEBPHOTO_FLASHVAR_VOLUME","音量");
define("_WEBPHOTO_FLASHVAR_LINKTARGET","リンク・ターゲット");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH","画像/動画の伸張");
define("_WEBPHOTO_FLASHVAR_TRANSITION","画像の切替え効果");
define("_WEBPHOTO_FLASHVAR_SCREENCOLOR","スクリーン背景色");
define("_WEBPHOTO_FLASHVAR_BACKCOLOR","背景色");
define("_WEBPHOTO_FLASHVAR_FRONTCOLOR","テキスト色");
define("_WEBPHOTO_FLASHVAR_LIGHTCOLOR","ハイライト色");
define("_WEBPHOTO_FLASHVAR_TYPE","拡張子");
define("_WEBPHOTO_FLASHVAR_FILE","メディア・ファイル");
define("_WEBPHOTO_FLASHVAR_IMAGE","プレビュー画像");
define("_WEBPHOTO_FLASHVAR_LOGO","ログ画像");
define("_WEBPHOTO_FLASHVAR_LINK","リンク");
define("_WEBPHOTO_FLASHVAR_AUDIO","オーディオ");
define("_WEBPHOTO_FLASHVAR_CAPTIONS","見出し URL");
define("_WEBPHOTO_FLASHVAR_FALLBACK","フォールバック URL");
define("_WEBPHOTO_FLASHVAR_CALLBACK","コールバック URL");
define("_WEBPHOTO_FLASHVAR_JAVASCRIPTID","JavaScript ID");
define("_WEBPHOTO_FLASHVAR_RECOMMENDATIONS","推奨");
define("_WEBPHOTO_FLASHVAR_STREAMSCRIPT","ストリーミング URL");
define("_WEBPHOTO_FLASHVAR_SEARCHLINK","検索リンク");

// log file
define("_WEBPHOTO_LOGFILE_LINE","行");
define("_WEBPHOTO_LOGFILE_DATE","日時");
define("_WEBPHOTO_LOGFILE_REFERER","Referer");
define("_WEBPHOTO_LOGFILE_IP","IP アドレス");
define("_WEBPHOTO_LOGFILE_STATE","状態");
define("_WEBPHOTO_LOGFILE_ID","ID");
define("_WEBPHOTO_LOGFILE_TITLE","タイトル");
define("_WEBPHOTO_LOGFILE_FILE","ファイル");
define("_WEBPHOTO_LOGFILE_DURATION","再生時間");

// item option
define("_WEBPHOTO_ITEM_KIND_UNDEFINED","未定義");
define("_WEBPHOTO_ITEM_KIND_NONE","メディアなし");
define("_WEBPHOTO_ITEM_KIND_GENERAL","一般");
define("_WEBPHOTO_ITEM_KIND_IMAGE","画像 (jpg,gif,png)");
define("_WEBPHOTO_ITEM_KIND_VIDEO","動画 (wmv,mov,flv...");
define("_WEBPHOTO_ITEM_KIND_AUDIO","オーディオ (mp3...)");
define("_WEBPHOTO_ITEM_KIND_EMBED","プラグイン");
define("_WEBPHOTO_ITEM_KIND_EXTERNAL_GENERAL","外部リンク 一般");
define("_WEBPHOTO_ITEM_KIND_EXTERNAL_IMAGE","外部リンク 画像");
define("_WEBPHOTO_ITEM_KIND_PLAYLIST_FEED","プレイリスト Web Feed");
define("_WEBPHOTO_ITEM_KIND_PLAYLIST_DIR", "プレイリスト メディア・ディレクトリ");

define("_WEBPHOTO_ITEM_DISPLAYTYPE_GENERAL","一般");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_IMAGE","画像 (jpg,gif,png)");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_EMBED","プラグイン");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_SWFOBJECT","FlashPlayer (swf)");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_MEDIAPLAYER","MediaPlayer (jpg,gif,png,flv,mp3)");
define("_WEBPHOTO_ITEM_DISPLAYTYPE_IMAGEROTATOR","ImageRotator (jpg,gif,png)");

define("_WEBPHOTO_ITEM_ONCLICK_PAGE","詳細ページ");
define("_WEBPHOTO_ITEM_ONCLICK_DIRECT","ダイレクトリンク");
define("_WEBPHOTO_ITEM_ONCLICK_POPUP","画像ポップアップ");

define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_DSC","What is the media file type?");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_NONE","なし");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_IMAGE","画像 (jpg,gif,png)");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_AUDIO","オーディオ (mp3)");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_VIDEO","動画 (flv)");
define("_WEBPHOTO_ITEM_PLAYLIST_TYPE_FLASH","フラッシュ (swf)");

define("_WEBPHOTO_ITEM_SHOWINFO_DESCRIPTION","説明");
define("_WEBPHOTO_ITEM_SHOWINFO_LOGOIMAGE","サムネイル");
define("_WEBPHOTO_ITEM_SHOWINFO_CREDITS","クレジット");
define("_WEBPHOTO_ITEM_SHOWINFO_STATISTICS","統計");
define("_WEBPHOTO_ITEM_SHOWINFO_SUBMITTER","投稿者");
define("_WEBPHOTO_ITEM_SHOWINFO_POPUP","ポップアップ");
define("_WEBPHOTO_ITEM_SHOWINFO_TAGS","タグ");
define("_WEBPHOTO_ITEM_SHOWINFO_DOWNLOAD","ダウンロード");
define("_WEBPHOTO_ITEM_SHOWINFO_WEBSITE","WEB サイト");
define("_WEBPHOTO_ITEM_SHOWINFO_WEBFEED","WEB Feed");

define("_WEBPHOTO_ITEM_STATUS_WAITING","承認待ち");
define("_WEBPHOTO_ITEM_STATUS_APPROVED","承認済み");
define("_WEBPHOTO_ITEM_STATUS_UPDATED","オンライン(更新)");
define("_WEBPHOTO_ITEM_STATUS_OFFLINE","オフライン");
define("_WEBPHOTO_ITEM_STATUS_EXPIRED","期限切れ");

// player option
define("_WEBPHOTO_PLAYER_STYLE_MONO","モノクロ");
define("_WEBPHOTO_PLAYER_STYLE_THEME","テーマからの色");
define("_WEBPHOTO_PLAYER_STYLE_PLAYER","カスタム・プレイヤー");
define("_WEBPHOTO_PLAYER_STYLE_PAGE","カスタム・プレイヤー/ページ");

// flashvar desc
define("_WEBPHOTO_FLASHVAR_ID_DSC","[Basics] <br />mediaplayer のとき、RTMP ストリーム ID を設定する<br />ID は 統計コールバックに送信される<br />プレイリストのときは、エントリ毎に設定できる");
define("_WEBPHOTO_FLASHVAR_HEIGHT_DSC","[Basics] ");
define("_WEBPHOTO_FLASHVAR_WIDTH_DSC","[Basics] ");
define("_WEBPHOTO_FLASHVAR_DISPLAYHEIGHT_DSC","[Playlist] [mediaplayer] ");
define("_WEBPHOTO_FLASHVAR_DISPLAYWIDTH_DSC","[Playlist] [mediaplayer] <br />プレイリストを下に表示:<br /> スクリーン幅 = プレイヤー幅<br />プレイリストを横に表示<br />スクリーン幅 &gt; プレイヤー幅 ");
define("_WEBPHOTO_FLASHVAR_DISPLAY_DEFAULT","0 のときは、プレイヤーの設定が使用される");
define("_WEBPHOTO_FLASHVAR_SCREENCOLOR_DSC","[Colors] <br />imagerotator のときは <br />異なる大きさの画像が混在しても、あなたのHTMLページに合うように変更する");
define("_WEBPHOTO_FLASHVAR_BACKCOLOR_DSC","[Colors] <br />コントロールの背景色");
define("_WEBPHOTO_FLASHVAR_FRONTCOLOR_DSC","[Colors] <br />コントロールのテキストとボタンの色");
define("_WEBPHOTO_FLASHVAR_LIGHTCOLOR_DSC","[Colors] <br />コントロールのマウスオーバしたときの色");
define("_WEBPHOTO_FLASHVAR_COLOR_DEFAULT","空のときは、プレイヤーの設定が使用される");
define("_WEBPHOTO_FLASHVAR_IMAGE_SHOW_DSC","[Basics] <br />プレビュー画像を表示する");
define("_WEBPHOTO_FLASHVAR_IMAGE_DSC","[Basics] <br />もし音楽や動画を再生するならば、プレビュー画像の URL を設定する <br />プレイリストのときは、エントリ毎に設定できる");
define("_WEBPHOTO_FLASHVAR_FILE_DSC","[Basics] <br />ファイルかプレイリストの URL を設定する<br />imagerorate のときは、プレイリストのみ設定できる");
define("_WEBPHOTO_FLASHVAR_SEARCHBAR_DSC","[Basics] <br />スクリーンの下に検索バーを表示する <br />「検索リンク」により検索先を設定できる");
define("_WEBPHOTO_FLASHVAR_LOGO_DSC","[Display] <br />スクリーンの右上に表示するロゴ画像を設定する<br />透過 PNG が最適");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_DSC","[Display] <br />画像/動画をスクリーンの大きさに拡張する方法を設定する<br />false (デフォルト) = スクリーンに一致する<br />true = 均一に拡張する<br />fit = 不均一に拡張する<br />none = 元の大きさを保持する");
define("_WEBPHOTO_FLASHVAR_SHOWEQ_DSC","[Display] <br />スクリーンの下に擬似的なイコライザーを表示する <br />MP3 に最適 ");
define("_WEBPHOTO_FLASHVAR_SHOWICONS_DSC","[Display] <br />スクリーンの中央にプレイボタンを表示する");
define("_WEBPHOTO_FLASHVAR_TRANSITION_DSC","[Display] [imagerotator] <br />画像の切替え効果を設定する ");
define("_WEBPHOTO_FLASHVAR_SHOWNAVIGATION_DSC","[Controlbar] <br />コントロール・バーを表示する");
define("_WEBPHOTO_FLASHVAR_SHOWSTOP_DSC","[Controlbar] [mediaplayer] <br />コントロール・バーにストップ・ボタンを表示する");
define("_WEBPHOTO_FLASHVAR_SHOWDIGITS_DSC","[Controlbar] [mediaplayer] <br />コントロール・バーに経過時間/残り時間を表示する ");
define("_WEBPHOTO_FLASHVAR_SHOWDOWNLOAD_DSC","[Controlbar] [mediaplayer] <br />コントロール・バーにリンク・ボタンを表示する<br />「リンク」によりリンク先を設定する");
define("_WEBPHOTO_FLASHVAR_USEFULLSCREEN_DSC","[Controlbar] <br />フルスクリーン・ボタンを表示する");
define("_WEBPHOTO_FLASHVAR_AUTOSCROLL_DSC","[Playlist] [mediaplayer] <br />プレイリストのスクロール・バーを表示する代わりに、マウスオーバーしたときに自動的にスクロールする");
define("_WEBPHOTO_FLASHVAR_THUMBSINPLAYLIST_DSC","[Playlist] [mediaplayer] <br />スクリーンにサムネイル画像を表示する");
define("_WEBPHOTO_FLASHVAR_AUDIO_DSC","[Playback] <br />同期した MP3 を割り当てる<br />mediaplayer の音声説明またはディレクターのコメント、あるいは imagerotator の背景音楽として、使用する<br />mediaplayer とプレイリストのときは、エントリ毎にオーディオを設定できる ");
define("_WEBPHOTO_FLASHVAR_AUTOSTART_DSC","[Playback] <br />mediaplayer にて true のときは、ページをロードしたときに自動的にスタートする<br />imagerotator にて false のときは 自動的な画像切り替えを行わない");
define("_WEBPHOTO_FLASHVAR_BUFFERLENGTH_DSC","[Playback]  [mediaplayer] <br />再生する前に、動画をバッファする秒数を設定する<br />高速な通信環境や短い動画のときは小さい値を設定する<br />低速な通信環境のときは大きい値を設定する ");
define("_WEBPHOTO_FLASHVAR_CAPTIONS_DSC","[Playback] [mediaplayer] <br />「見出し」は TimedText 形式であること <br />プレイリストのときは、エントリ毎に見出しを設定できる ");
define("_WEBPHOTO_FLASHVAR_FALLBACK_DSC","[Playback] [mediaplayer] <br />もし MP4 を再生するならば、フォールバックする FLV の URL を設定すること <br />古いバージョンのフラッシュ・プレイヤーでは自動的に選択される ");
define("_WEBPHOTO_FLASHVAR_REPEAT_DSC","[Playback] <br />true のとき、全てのファイルを繰返し再生する <br />プレイリストのときは 一度だけ再生する ");
define("_WEBPHOTO_FLASHVAR_ROTATETIME_DSC","[Playback] <br />画像を切替るときの秒数を設定する ");
define("_WEBPHOTO_FLASHVAR_SHUFFLE_DSC","[Playback] <br />「はい」のとき、プレイリストをランダムに再生する ");
define("_WEBPHOTO_FLASHVAR_SMOOTHING_DSC","[Playback] [mediaplayer] <br />「いいえ」のとき、画像のスムージングを行わない <br />画質は落ちるが、性能は向上する <br />HD ファイルや遅い PC に最適 ");
define("_WEBPHOTO_FLASHVAR_VOLUME_DSC","[Playback] <br />音楽や動画の音量を設定する");
define("_WEBPHOTO_FLASHVAR_ENABLEJS_DSC","[External] <br />「はい」のとき、JavaScript による対話制御を有効にする <br />オンラインのとき動作する<br />JavaScript による対話制御には、コントロールバーや、メディアファイルのローデングの同期や、トラック情報の返信などを含む ");
define("_WEBPHOTO_FLASHVAR_JAVASCRIPTID_DSC","[External] <br />もしも、複数の mediaplayers/imagerotators と JavaScript による対話制御を行うときは、この項目にそれぞれに対するユニークな ID を設定する ");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_DSC","[External] <br />スクリーンやロゴやリンク・ボタンに割当されるリンクの種別を設定する<br /> 「なし」のときは、何もしない <br />それ以外は、そのリンクを割当てる");
//define("_WEBPHOTO_FLASHVAR_LINK_DSC","[External] <br />外部 URL やダウンロード可能なファイルを設定する<br />このリンクはスクリーンやロゴやリンク・ボタンに割当される<br />プレイリストのときは、XML 形式にてエントリ毎に設定できる");
define("_WEBPHOTO_FLASHVAR_LINKFROMDISPLAY_DSC","[External] <br />「はい」のときは、スクリーンをクリックすると、「リンク」に設定された WEB ページのジャンプする ");
define("_WEBPHOTO_FLASHVAR_LINKTARGET_DSC","[External] <br />リンクしたウィンドウ画面の種類");
define("_WEBPHOTO_FLASHVAR_CALLBACK_DSC","[External] <br />Set this to a serverside script that can process statistics. <br />The player will send it a POST every time an item starts/stops. <br />To send callbacks automatically to Google Analytics, set this to urchin or analytics. ");
define("_WEBPHOTO_FLASHVAR_RECOMMENDATIONS_DSC","[External] [mediaplayer] <br />推奨する項目を XML 形式で設定する <br />動画が停止しているときは、Youtube と同じようにサムネイルが表示される ");
define("_WEBPHOTO_FLASHVAR_SEARCHLINK_DSC","[External] [mediaplayer] <br />検索バーの検索先を設定する <br />デフォルトは「search.longtail.tv」である <br />検索バーを隠すには「検索バー」を使用する ");
define("_WEBPHOTO_FLASHVAR_STREAMSCRIPT_DSC","[External] [mediaplayer] <br />ストリーミング配信に使用するスクリプトの URL を設定する <br />パラメータ・ファイルはスクリプトへ送信される <br />もし LigHTTPD ストリーミング を使用するならば、'lighttpd' と設定する . ");
define("_WEBPHOTO_FLASHVAR_TYPE_DSC","[External] [mediaplayer] <br />mediaplayer は「file」の最後の３文字を拡張子を見なしている <br />データベースの ID や mod_rewrite などでうまく動作しない場合には、正しい拡張子を設定すること <br />もしも、確かでないときは、プレイリストがロードされたと見なす。");

// flashvar option
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_NONE","なし");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_SITE","Webサイト URL");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_PAGE","詳細ページ");
define("_WEBPHOTO_FLASHVAR_LINK_TYPE_FILE","メディア・ファイル");
define("_WEBPHOTO_FLASHVAR_LINKTREGET_SELF","同じ画面 self ");
define("_WEBPHOTO_FLASHVAR_LINKTREGET_BLANK","新しい画面 blank ");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_FALSE","False");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_FIT","Fit");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_TRUE","True");
define("_WEBPHOTO_FLASHVAR_OVERSTRETCH_NONE","None");
define("_WEBPHOTO_FLASHVAR_TRANSITION_OFF","なし");
define("_WEBPHOTO_FLASHVAR_TRANSITION_FADE","フェード Fade");
define("_WEBPHOTO_FLASHVAR_TRANSITION_SLOWFADE","遅いフェード Slow Fade");
define("_WEBPHOTO_FLASHVAR_TRANSITION_BGFADE","背景フェード Background Fade");
define("_WEBPHOTO_FLASHVAR_TRANSITION_CIRCLES","丸 Circles");
define("_WEBPHOTO_FLASHVAR_TRANSITION_BLOCKS","四角 Blokcs");
define("_WEBPHOTO_FLASHVAR_TRANSITION_BUBBLES","泡 Bubbles");
define("_WEBPHOTO_FLASHVAR_TRANSITION_FLASH","光 Flash");
define("_WEBPHOTO_FLASHVAR_TRANSITION_FLUIDS","流体 Fluids");
define("_WEBPHOTO_FLASHVAR_TRANSITION_LINES","線 Lines");
define("_WEBPHOTO_FLASHVAR_TRANSITION_RANDOM","ランダム Random");

// edit form
define("_WEBPHOTO_CAP_DETAIL","詳細設定");
define("_WEBPHOTO_CAP_DETAIL_ONOFF","表示/非表示");
define("_WEBPHOTO_PLAYER","プレイヤー");
define("_WEBPHOTO_EMBED_ADD", "プラグインを追加する" ) ;
define("_WEBPHOTO_EMBED_THUMB","このプラグインはサムネイルを提供する");
define("_WEBPHOTO_ERR_EMBED","プラグインの設定が必要です");
define("_WEBPHOTO_ERR_PLAYLIST","プレイリストの設定が必要です");

// sort
define("_WEBPHOTO_SORT_VOTESA","投票数 (低→高)");
define("_WEBPHOTO_SORT_VOTESD","投票数 (高→低)");
define("_WEBPHOTO_SORT_VIEWSA","閲覧数 (低→高)");
define("_WEBPHOTO_SORT_VIEWSD","閲覧数 (高→低)");

// flashvar form
define("_WEBPHOTO_FLASHVARS_FORM","Flash変数の編集");
define("_WEBPHOTO_FLASHVARS_LIST","Flash変数の一覧(英語)");
define("_WEBPHOTO_FLASHVARS_LOGO_SELECT","ロゴ画像の選択");
define("_WEBPHOTO_FLASHVARS_LOGO_UPLOAD","ロゴ画像のアップロード");
define("_WEBPHOTO_FLASHVARS_LOGO_DSC","[Display] <br />ロゴ画像のディレクトリ ");
define("_WEBPHOTO_BUTTON_COLOR_PICKUP","色選択");
define("_WEBPHOTO_BUTTON_RESTORE","デフォルト値に戻す");

// Playlist Cache 
define("_WEBPHOTO_PLAYLIST_STATUS_REPORT","状態報告");
define("_WEBPHOTO_PLAYLIST_STATUS_FETCHED","WEB Feed は取得された");
define("_WEBPHOTO_PLAYLIST_STATUS_CREATED","プレイリストを生成した");
define("_WEBPHOTO_PLAYLIST_ERR_CACHE","[ERROR] キャッシュ・ファイルの生成に失敗した");
define("_WEBPHOTO_PLAYLIST_ERR_FETCH","WEB Feed の取得に失敗した<br />WEB Feed を確認して、キャッシュの再生成をしてください");
define("_WEBPHOTO_PLAYLIST_ERR_NODIR","メディア・ディレクトリは存在しない");
define("_WEBPHOTO_PLAYLIST_ERR_EMPTYDIR","メディア・ディレクトリは空です");
define("_WEBPHOTO_PLAYLIST_ERR_WRITE","キャッシュ・ファイルに書込みできない");

define("_WEBPHOTO_USER",  "ユーザ" ) ;
define("_WEBPHOTO_OR",  "あるいは" ) ;

//---------------------------------------------------------
// v0.60
//---------------------------------------------------------
// item table
//define("_WEBPHOTO_ITEM_ICON" , "アイコン名" ) ;

define("_WEBPHOTO_ITEM_EXTERNAL_MIDDLE" , "外部リンクのミドルURL" ) ;

// cat table
define("_WEBPHOTO_CAT_IMG_NAME" , "カテゴリ画像名" ) ;

// edit form
define("_WEBPHOTO_CAP_MIDDLE_SELECT", "ミドル画像の選択");

//---------------------------------------------------------
// v0.70
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_CODEINFO", "コード表示項目");
define("_WEBPHOTO_ITEM_PAGE_WIDTH",  "ページ横幅");
define("_WEBPHOTO_ITEM_PAGE_HEIGHT", "ページ高さ");
define("_WEBPHOTO_ITEM_EMBED_TEXT",  "埋込み");

// item option
define("_WEBPHOTO_ITEM_CODEINFO_CONT","メディア");
define("_WEBPHOTO_ITEM_CODEINFO_THUMB","サムネイル画像");
define("_WEBPHOTO_ITEM_CODEINFO_MIDDLE","ミドル画像");
define("_WEBPHOTO_ITEM_CODEINFO_FLASH","フラッシュ動画");
define("_WEBPHOTO_ITEM_CODEINFO_DOCOMO","ドコモ動画");
define("_WEBPHOTO_ITEM_CODEINFO_PAGE","URL");
define("_WEBPHOTO_ITEM_CODEINFO_SITE","サイト");
define("_WEBPHOTO_ITEM_CODEINFO_PLAY","プイリスト");
define("_WEBPHOTO_ITEM_CODEINFO_EMBED","埋込み");
define("_WEBPHOTO_ITEM_CODEINFO_JS","スクリプト");

define("_WEBPHOTO_ITEM_PLAYLIST_TIME_HOUR", "1時間");
define("_WEBPHOTO_ITEM_PLAYLIST_TIME_DAY",  "1日");
define("_WEBPHOTO_ITEM_PLAYLIST_TIME_WEEK", "1週間");
define("_WEBPHOTO_ITEM_PLAYLIST_TIME_MONTH","1ヶ月");

// photo
define("_WEBPHOTO_DOWNLOAD","ダウンロード");

// file_read
define("_WEBPHOTO_NO_FILE", "ファイルが存在しない");

//---------------------------------------------------------
// v0.80
//---------------------------------------------------------
// item table
define("_WEBPHOTO_ITEM_ICON_NAME" ,   "アイコン名" ) ;
define("_WEBPHOTO_ITEM_ICON_WIDTH" ,  "アイコン横幅" ) ;
define("_WEBPHOTO_ITEM_ICON_HEIGHT" , "アイコン高さ" ) ;

// item form
define("_WEBPHOTO_DSC_SET_ITEM_TIME_UPDATE",  "更新日時を変更する");
define("_WEBPHOTO_DSC_SET_ITEM_TIME_PUBLISH", "発行日時を設定する");
define("_WEBPHOTO_DSC_SET_ITEM_TIME_EXPIRE",  "終了日時を設定する");

//---------------------------------------------------------
// v0.81
//---------------------------------------------------------
// vote option
define("_WEBPHOTO_VOTE_RATING_1", "1");
define("_WEBPHOTO_VOTE_RATING_2", "2");
define("_WEBPHOTO_VOTE_RATING_3", "3");
define("_WEBPHOTO_VOTE_RATING_4", "4");
define("_WEBPHOTO_VOTE_RATING_5", "5");
define("_WEBPHOTO_VOTE_RATING_6", "6");
define("_WEBPHOTO_VOTE_RATING_7", "7");
define("_WEBPHOTO_VOTE_RATING_8", "8");
define("_WEBPHOTO_VOTE_RATING_9", "9");
define("_WEBPHOTO_VOTE_RATING_10","10");

//---------------------------------------------------------
// v0.90
//---------------------------------------------------------
// edit form
define("_WEBPHOTO_GROUP_PERM_ALL" , "全てのグループ" ) ;

// === define end ===
}

?>