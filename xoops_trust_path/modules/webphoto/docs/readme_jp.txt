$Id: readme_jp.txt,v 1.69 2012/10/05 12:56:30 ohwada Exp $

=================================================
Version: 2.61
Date:   2011-12-25
Author: Kenichi OHWADA
URL:    http://linux.ohwada.jp/
Email:  webmaster@ohwada.jp
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. バグ対策
(1) 新規カテゴリー登録できない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1280&forum=13


=================================================
Version: 2.60
Date:   2011-12-25
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. タイムライン
(1) 1970年 (unixtime) 以前に対応した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1186&forum=13

(2) 時間スケールに１世紀と１日を追加した
(3) カテゴリにタイムラインを表示した
(4) カテゴリから大きなタイムラインに移動した時に、カテゴリの情報を引き継いだ。

2. 地図
(1) カテゴリから大きな地図に移動した時に、カテゴリの情報を引き継いだ。


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
(3) Webphoto の管理者画面にて、
  「アップデート」の「ファイルの妥当性の検査」を実行し、
  必要なファイルが設置されているかを確認する


=================================================
Version: 2.51
Date:   2011-11-11
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. MySQL 5.5 対応
(1) TYPE=MyISAM -> ENGINE=MyISAM

2. バグ対策
(1) コマンドのテスト実行にて Fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1205&forum=13

(2) visit にて Fatal error


=================================================
Version: 2.50
Date:   2011-11-03
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. PHP 5.3 対応
PHP 5.3.x で推奨されない機能 を修正した
http://www.php.net/manual/ja/migration53.deprecated.php
(1) ereg
(2) new の返り値を参照で代入すること

2. PopBix.js を v2.7a に更新した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1189&forum=13

3. インストール時の各グループの権限
(1) 登録ユーザに対して、投稿可（要承認） などを許可した。
(2) ゲストに対して、モジュール・アクセスを許可した。

4. タグ管理
アイテム管理にて、管理者は全てのタグを編集できるようにした。

● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
(3) Webphoto の管理者画面にて、
  「アップデート」の「ファイルの妥当性の検査」を実行し、
  必要なファイルが設置されているかを確認する


=================================================
Version: 2.42
Date:   2011-06-05
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. バグ対策
(1) pathinfoを使用しないとき、gmapのURLが正しくない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1178&post_id=4302

(2) webphotoからインポートにて Fatal エラー


=================================================
Version: 2.41
Date:   2011-05-16
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. バグ対策
(1) インストールできない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1177&post_id=4297

(2) webphotoからの一括インポートにて fatal error


=================================================
Version: 2.40
Date:   2011-05-10
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. ファイルのダウンロード
(1) オリジナルのファイル名でダウンロードする
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1167&forum=13

(2) 画像ファイルはダウンロードと画像表示の２つを表示する
(3) メディアとサムネイル画像が同じファイル名になるので、サムネイル画像には thumb を追加した

2. Flash プレイヤー
(1) 「JW Player 5.6」にバージョンアップした
(2) 「Flash Player のオプションの編集」を大幅に修正した

3. メール受信
(1) POPサーバーを Gmail に対応した
(2) 機種依存文字に対応した
(3) スペースのあるファイル名に対応した
(3) Subject がない時はファイル名をタイトルにした
(4) メールアドレスを５個に拡張した
(5) PEAR ライブラリを使用した

4. バグ対策
(1) プレイリストにて最初の１曲しか再生しない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1152&forum=13

(2) コマンドのテスト実行にて fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1154&forum=13

(3) myalbum-pからの一括インポートに失敗します。
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1157&forum=13

(4) 「アイテム管理」にて、期限切れ一覧が表示されない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1158&forum=13

(5) ダウンロードができない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1167&forum=13

(6) 「アイテム管理」にて、動画のときに「編集」ボタンを押すと、fatal error

(7) 「アイテム管理」にて、状態がオフラインにならない

(8) プレイリストにて、存在しないページにリンクしている

5. データベースの構造
(1) flashvar table : JW Player 5.6 に対応した

6. PEAR ライブラリ
下記を同封しています
2011年5月 時点
(1) Net_Socket 1.0.10 
(2) Net_POP3 1.3.8
(3) Mail_mime 1.8.1
(4) Mail_mimeDecode 1.5.5 


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
(3) Webphoto の管理者画面にて、
  「アップデート」の「ファイルの妥当性の検査」を実行し、
  必要なファイルが設置されているかを確認する


=================================================
Version: 2.32
Date:   2010-11-17
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. バグ対策
(1) 一括登録にてサムネイル画像が生成されない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1146

(2) 一括登録にて前の情報が残り誤った情報が登録される

(3) imagemanager にて登録できない


=================================================
Version: 2.31
Date:   2010-11-03
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. バグ対策
(1) カテゴリが登録できない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1139

(2) RSSが表示されない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1125


=================================================
Version: 2.30
Date:   2010-10-10
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. メディアファイル
(1) 画像 svg jpeg(cmyk) を追加した
(2) 動画 mp4 mpe m1v を追加した
(3) オーディオ wma aiff au を追加した

2. 画像ファイルの扱い
2.1 メディア・ファイル
従来は、設定されたサイズよりも大きい画像は、
リザイズされたファイルが保存され、オリジナルのファイルは保存されなかった。
今回のバージョンから、オリジナルのファイルを保存するようにした。

2.2 サムネイル画像
(1) メディア・ファイルからJPEG画像に変換してサムネイル画像を生成している。
従来は、JPEG画像は保存していなかった。
今回のバージョンから、保存するようにした。
(2) 従来は、GIF画像、PNG画像は、同じ形式でサムネイル画像を生成していた。
今回のバージョンから、JPEG画像に変換してから生成するようにした。

3. 再生プレイヤー
(1) バージョンアップした
- JW Player 5.2
- JW Image Rotator 3.18
(2) H.264/AAC 形式の動画が再生できる

4. 動画サイト
4.1 投稿時のプレビューを表示した
4.2 ニコニコ動画のソースIDを sm*** と nm*** に対応した

5. 言語ファイル
「写真」を「写真・動画・メディア」に変更した

6. バグ対策
(1) ダウンロード・ファイルが表示されない
(2) ユーザ数が多いとき、登録フォームが表示されない

7. データベース構造
(1) item テーブル: 項目追加 item_displayfile etc


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
(3) Webphoto の管理者画面にて、
  「アップデート」の「ファイルの妥当性の検査」を実行し、
  必要なファイルが設置されているかを確認する


● 注意
画像ファイルの扱いを変更したことに伴い、多くのファイルを変更しました。
大きな問題はないはずですが、小さな問題はあると思います。
バグ報告やバグ解決を歓迎します。


=================================================
Version: 2.20
Date:   2010-06-17
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. windows	対応
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1107&forum=13

下記のコマンドが windows 環境でも動作するようにした
imagemagick ffmpeg lame timidity xpdf java

2. main.in オプション
(1) submit_detail_div_onoff を追加した
新規登録にて デフォルトで詳細設定を「表示」にする
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1113&forum=13

(2) upload_allowed_mimes を追加した
アップロードにて MIME タイプのチェックを行わない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1114&forum=13

3. 動画サイト
3.1 プラグイン
(1) 下記の動画サイト・プラグインを追加した
新規登録時にタイトルやサムネイル画像を取得する
- ustream.tv ユーストリーム
- nicovideo.jp ニコニコ動画
- ameba.jp アメーバ

(2) 下記の動画サイト・プラグインにて、タイトルやサムネイル画像を取得するようにした。
- youtube.com ユーチューブ
- myspace.com マイスペース
- dailymotion.com デイリーモーション

3.2 一般設定
一般設定に「動画サイトの表示の幅」と「高さ」を追加した

3.3 サムネイル表示
下記に動画サイトのサムネイル表示を追加した
- GoogleMap
- whatsnew モジュール
- happy_search モジュール

4. MS Office 2007 対応
アップロード可能なファイルに 拡張子 docx xlsx pptx を追加した

5. バグ修正
(1) happy_search の検索結果の画像がおかしい
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1116&forum=13


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
(3) Webphoto の管理者画面にて、
  「アップデート」の「ファイルの妥当性の検査」を実行し、
  必要なファイルが設置されているかを確認する


● 謝辞
動画サイトのプラグインを提供いただきました。
- http://www.how-to.tv/


=================================================
Version: 2.13
Date:   2010-05-12
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. メールのテンプレート・ファイル を preload で置換できる

2. バグ修正
(1) bin/retrieve.php にて Fatal エラー
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1090&forum=13

(2) メール登録 にて Fatal エラー
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1097&forum=13

(3) アイテム管理にて google map が表示されない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1099&forum=13

(4) カテゴリ表示にて total 数が正しくない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1101&forum=13

(5) mime管理にて Fatal エラー
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1103&forum=13

(6) リスト表示・テーブル表示の切替えが継続しない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1105&forum=13

(7) weblinks モジュール にて Fatal エラー

(8) サブメニューのカテゴリ表示にて、並び順と閲覧制限が効かない


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
(3) Webphoto の管理者画面にて、
  「アップデート」の「ファイルの妥当性の検査」を実行し、
  必要なファイルが設置されているかを確認する


=================================================
Version: 2.12
Date:   2010-04-04
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. CentOS5 対応
(1) PDFのサムネイル生成を pdftoppm から pdftops に変更した

2. バグ修正
(1) カテゴリーの閲覧権限が設定できない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1079&forum=13

(2) 他のサイトでの動画の埋込み再生ができない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1087&forum=13

(3) altsys から言語ファイル読込みで Notice

(4) 登録時のDBエラー
item_content : Incorrect string value
item_exif : Data too long


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
(3) Webphoto の管理者画面にて、
  「アップデート」の「ファイルの妥当性の検査」を実行し、
  必要なファイルが設置されているかを確認する


=================================================
Version: 2.11
Date:   2010-02-17
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. 管理画面にも実行時間とメモリ使用量を表示した

2. バグ修正
(1) typo 日本語言語ファイル
(2) 「各グループの権限」にてモジュール管理の表示がおかしい


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
(3) Webphoto の管理者画面にて、
  「アップデート」の「ファイルの妥当性の検査」を実行し、
  必要なファイルが設置されているかを確認する


=================================================
Version: 2.10
Date:   2010-02-06
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. 表示方法のカスタマイズ
地図やタイムラインなどの表示/非表示のカスタマイズを簡単にした
地図やタイムラインなどをコンポーネント部品化した。
main.ini により表示/非表示を設定できるようにした。

(1) タグと投稿者のページに地図を表示した。
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1004&forum=13

2. 説明文のスクロール表示
投稿者は、説明文が長いときに、スクロール表示にすることが選択できる
アイテム毎の設定が可能

3. 詳細画面のクリック時の動作
管理者は、詳細画面 (photo) にて、写真をクリックしたときの動作が選択できる
アイテム毎の設定が可能

(1) 一般的な表示
別画面にアップロードしたファイルを表示する
これがデフォルトの設定

(2) 別画面での写真表示 (従来のもの) 
別画面に原寸大の写真をその大きさの画面サイズで表示する
アップロードしたファイルが静止画像のときに有効

(3) ポップアップ表示 (新設)
lightbox2 にて原寸大の写真をポップアップ表示する
アップロードしたファイルが静止画像のときに有効
- http://www.lokeshdhakar.com/projects/lightbox2/

(4) PDF表示 (v1.90で追加)
別画面にPDFファイルを表示する
アップロードしたファイルから PDF が生成されたときに有効

4. 登録画面
(1) 説明文のエディタ形式に plain (普通のtextbox) を追加した
(2) プレビュー時のサムネイルからリンクを削除した

5. 管理者画面
(1) 動作チェッカーに webphoto のバージョンを表示した

6. バグ修正
(1) weblink モジュールとの連携にて Fatal error
(2) 各グループの権限にてユーザグループのリンク先がおかしい
(3) 一括登録のとき承認待ちメール内のURLが正しくない
(4) 編集画面にて メイン画像が削除できない
(5) 投票にて error

7. データベース構造
(1) item テーブル: 項目追加 item_description_scroll


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
(3) Webphoto の管理者画面にて、
  「アップデート」の「ファイルの妥当性の検査」を実行し、
  必要なファイルが設置されているかを確認する
(4) Webphoto の管理者画面にて、「アップデート」を実行する。
  すでにあるアイテムについて、詳細画面のクリック時の動作を設定します。


● 注意
表示方法のカスタマイズに伴い、多くのファイルを変更しました。
大きな問題はないはずですが、小さな問題はあると思います。
バグ報告やバグ解決を歓迎します。


● 謝辞
lightbox2 の作者に感謝します
- http://www.lokeshdhakar.com/projects/lightbox2/


=================================================
Version: 2.00
Date:   2009-12-24
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. 各グループの権限
(1) グループ名にユーザグループ管理へのリンクを追加した。
(2) 設定項目にアクセスと管理を追加した。

2. 承認待ちの通知
(1) 承認が必要な写真が投稿されたとき、管理者に対して、承認待ちがPMもしくはメールで通知される
(2) 承認すると、投稿者に対して、承認した旨のメールが送信される
(3) 拒否すると、投稿者に対して、拒否した旨のメールが送信される

3. 「動作チェッカー」から「ファイルの妥当性の検査」を独立した

4. コミュニティ機能
この機能は実験的なものです。
モジュール単位やカテゴリ単位に、SNSのコミュニティのような会員限定のエリアを構築する

5. バグ修正
(1) サムネイル再構築にて Fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1054

(2) イベント通知機能にて Fatal error
(3) 投稿画面にて「ファイルサイズ上限」が表示されない
(4) カテゴリ登録にて、画像が大きいとアップロード出来ない

6. データベース構造
(1) item テーブル: 項目追加 item_perm_level
(2) cat  テーブル: 項目追加 cat_group_id


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


=================================================
Version: 1.90
Date:   2009-11-29
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. main.ini 新設
従来は PHP 定数として設定していた内部状態を、モジュール単位に変数で設定できるようにした。

下記の順番に読み込まれる。最後の設定された値が有効になる。
(1) XOOPS_TRUST_PATH /include/main.ini
(2) XOOPS_TRUST_PATH /preload/main.ini (存在すれば)
(3) XOOPS_ROOT_PATH  /preload/main.ini (存在すれば)

2. メニューに、静止画、動画、音楽、オフィスを追加した

3. 一覧表示と詳細画面にて、ゲストに対して閲覧制限をしている記事に、グループアイコンを表示した

4. 詳細画面にて、コンテンツ画像をクリックしたときに、表示するファイルを選択可能した
(1) アップロードしたファイル
(2) 変換されたPDF

5. 詳細画面にて、Flash Player に表示する動画の画面サイズを自動的に調整する

画面サイズは下記の順番で決定される
(1) item テーブルの値 (設定されていれば)
(2) flashvar テーブルの値 (設定されていれば)
(3) file テーブル (フラッシュ動画) の値があれば、Flash Player の値に収まるように自動調整 (新設)
(4) Flash Player の値

6. 登録画面にて、ファイルアップロード中はスピンアイコンを表示する

7. 登録画面にて、デフォルトのエディタを変更する
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1046

8. 管理者はプラグイン・タイプを変更可能にした
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1043

9. バグ修正
(1) typo Radom -> Random
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=461&forum=11

(2) Fatal error: ai 形式をアップロードしたとき
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1043

10. データベース構造
(1) item テーブル: 項目追加 item_detail_onclick item_weight


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


● 注意
main.ini の新設に伴い、多くのファイルを変更しました。
大きな問題はないはずですが、小さな問題はあると思います。
バグ報告やバグ解決を歓迎します。


=================================================
Version: 1.80
Date:   2009-11-01
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. メディアファイル
(1) メディアファイルを追加した
ai, eps, pct, psd, tif, wmf 

(2) 画像ファイルの処理
画像ファイルを登録するときに、JPEG に変換し、サムネイルを生成する
対象: ai, bmp, eps, pct, psd, tif, wmf など WEBブラウザで表示できないもの
要件: imagemagick が必要

(3) 音楽ファイルの処理
音楽ファイルを登録するときに、MP3 に変換し、mediaplayer.swf プレイヤで再生する
対象: wav, mid
要件: wav は lame が必要
      mid は lame と timidity が必要

2. バグ対策
(1) Fatal error in imagemanager.php
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1032&forum=13

(2) timeline の画像がダブッて表示される
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1033&forum=13

(3) 地図の下に隙間が出来る
http://linux2.ohwada.net/modules/newbb/viewtopic.php?&topic_id=463&forum=11

(4) player id が正しく選択されない

3. データベース構造
(1) mime テーブル: 項目追加 mime_kind mime_option


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


=================================================
Version: 1.73
Date:   2009-09-26
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. 機能追加
(1) RSSに投稿者名を表示した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1020&forum=13

(2) 携帯電話に Google Map を表示した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1026&forum=13

2. バグ修正
(1) EXIFから位置情報を抽出する方法を変更した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=996&forum=13

(2) EXIFをDBに保存するときに、不正な文字のエラーになる
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1014&forum=13

(3) 編集時に表示される近辺のマーカーの数を制限した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1023&forum=13

(4) XCL にて、ゲストのとき、コメント reply が表示されない

3. データベース構造
(1) item テーブル: item_exif TEXT -> BLOB


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


=================================================
Version: 1.72
Date:   2009-08-08
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. 文字コード
(1) 文字コード関数にて mbstring と iconv を選択可能にした

(2) 管理者画面の「文字コード変換が動くかどうか」を mbstring と iconv の両方で動作確認する
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=989&forum=13

(3) 管理者画面にて MySQLの文字コード設定を表示した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1014&post_id=3766#forumpost3766

2. 白い地図マーカーの背景色を透過にした
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=9&topic_id=988

3. バグ修正
(1) 「自分の投稿」をクリックしたとき投稿者一覧が表示される
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1001&forum=13

(2) item テーブルの editor 項目が空のとき、編集フォームに説明文が表示されない


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


● 使用上の注意
1. 文字コード処理
通常は、iconv が存在すれば、iconv を使います。
iconv と mbstring の両方が存在するときに、mbstring を使いたいときは、
preload ファイルにより設定します。

preload ファイルをリネームする
XOOPS_TRUST_PATH/modules/webphoto/preload/_multibyte.php (アンダーバーあり)
 -> multibyte.php (アンダーバーなし)


=================================================
Version: 1.71
Date:   2009-06-01
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. カテゴリ
(1) 投稿権限のないカテゴリが選択できるバグを修正
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=983

(2) 登録処理にて指定されたカテゴリの投稿権限をチェックした

(3) メニューに cat_id が表示されないバグを修正

2. バグ修正
(1) 編集画面にタグが表示されない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=986&forum=13


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


● 謝辞
option タグの disabled 属性に関して、下記を参考にしました。
Select, Option, Disabled And The JavaScript Solution
- http://www.lattimore.id.au/2005/07/01/select-option-disabled-and-the-javascript-solution/
作者の方に、感謝します。


=================================================
Version: 1.70
Date:   2009-05-23
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. カテゴリ
(1) カテゴリ一覧にカテゴリ説明文を表示した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=972

(2) カテゴリの投稿権限を親カテゴリから独立した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=983

(3)「一般設定」に下位カテゴリの画像を表示する/しないを追加した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=983

(4)「カテゴリ管理」の登録フォームに親カテゴリと下位カテゴリを表示した

(5)「アイテム管理」にゾンビ(存在しないカテゴリに属する画像）をエラー表示した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=859

2. 一覧表示のときは、写真の説明文を全文から要約に変更した


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


=================================================
Version: 1.60
Date:   2009-05-17
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. 複数枚のアップロードを追加した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=960

2. 「ファイルからの画像追加」と「画像一括登録」に「エディタを選択する」を追加した
3. 「アイテム管理」の登録フォームに投稿者を追加した

4. フランス語 1.51対応

5. バグ対策
(1) typo
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=980

(2) bin/retrieve.php にて 403 エラー
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=981

(3) 「投票する」にて Parse error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=981


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


=================================================
Version: 1.51
Date:   2009-04-27
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. バグ対策
(1) Warning: chmod()
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=5&topic_id=959

(2) カテゴリ管理にて Fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=961


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


=================================================
Version: 1.50
Date:   2009-04-19
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. 投稿フォームのカスタマイズ
(1) テンプレート化
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=924

(2) プラグインの非表示
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=938

(3) 外部リンクのURLの非表示
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=951

2. パンくずリストのテンプレート化
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=939

3. 動作チェッカーにて、必要なファイルが揃ってるかのチェックを追加した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=939

4. バグ対策
(1) weblinks にて fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=5&topic_id=952

(2) 写真説明文が表示されない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=955

(3) アイテム管理にて fatal error


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


=================================================
Version: 1.40
Date:   2009-04-10
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. タイムライン
(1) ブロック表示を追加した
(2) 撮影日一覧にタイムライン表示を追加した
(3) ヘルプのメニューにタイムラインを追加した
(4) 外部メディアのときは、ネット越しい画像を取得し、スモール画像を生成する

2. マップ
(1) 撮影場所一覧にマップ表示を追加した

3. 一般設定
下記を追加した
(1) タイムラインに表示する写真の数
(2) マップに表示する写真の数
(3) タグクラウドに表示するタグの数

4. バグ対策
(1) パッケージ作成ミス
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=932&forum=13
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=948&forum=13

(2) 英語のtypo
http://linux2.ohwada.net/modules/newbb/viewtopic.php?forum=11&topic_id=449

(3) 画像を削除したときに、item テーブルの file id が 0 にならない


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


=================================================
Version: 1.30
Date:   2009-03-20
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. イメージマネージャ統合にて画像以外を貼り付ける
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=936

2. プレビューにて画像の回転を行う
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=936

3. タイムラインに対応した
(1) timeline モジュールを使用してタイムラインを表示する
(2) タイムライン用にスモール画像を追加した

4. バグ対策
(1) myalbum-P からのインポートできないr 
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=932

(2) ユーザ編集画面にて player が default になる
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=936


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
(3) 次に、Webphoto の管理者画面にて「アップデート」を実行する。
  タイムライン用にスモール画像を生成します。


● 謝辞
MIT Simile Project の方々に、感謝します。
- http://code.google.com/p/simile-widgets/wiki/Timeline


=================================================
Version: 1.21
Date:   2009-03-05
Author: Kenichi OHWADA
URL:    http://linux.ohwada.jp/
Email:  webmaster@ohwada.jp
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. 詳細表示にて、検索キーワードをハイライトした
2. flash 表示にて 「ページ横幅」「ページ高さ」を有効にした
3. RSS 出力にて、Georss に対応した
4. RSS 管理を新設した
5. フランス語を追加した

6. バグ対策
(1) へルプにて、Fatal error 
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=921

(2) イメージマネジャーにて、Fatal error 
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=923

(3) RSS出力 にて「pathinfo を使用する」が反映されない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=927


=================================================
Version: 1.20
Date:   2009-02-01
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. カテゴリ毎に GoogleMap を設置する
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=912

2. ブロックに GoogleMap を設置する
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=913&forum=13

3. Weblinks のアルバムの選択に Webphoto を追加した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=5&topic_id=911

4. 登録画面
(1) 「テキスト文」を編集可能にした
(2) 下記のファイルを表示した
- Flash 動画 (flv)
- Docomo 動画 (3gp)
- PDF (pdf)
- Flash (swf)

5. Word (doc)
(1) jodconverter, xpdf, imagemagick が必要です
- http://www.artofsolving.com/opensource/jodconverter
- http://www.foolabs.com/xpdf/
- http://www.imagemagick.org/script/index.php
(2) Word から PDF を生成する
(3) Word からサムネイルを生成する
(4) Word からテキストを抽出し「テキスト文」欄に表示する

6. Excel (xls)
(1) jodconverter, xpdf, imagemagick が必要です
(2) Excel から PDF を生成する
(3) Excel からサムネイルを生成する
(4) Excel からテキストを抽出し「テキスト文」欄に表示する

7. PowerPoint (ppt)
(1) jodconverter, xpdf, imagemagick が必要です
(2) PowerPoint から PDF を生成する
(3) PowerPoint からフラッシュ (swf) を生成する
(4) PowerPoint からサムネイルを生成する
(5) PowerPoint からテキストを抽出し「テキスト文」欄に表示する


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


● 使用上の注意
1. jodconverter
(1) preload ファイルをリネームする
XOOPS_TRUST_PATH/modules/webphoto/preload/_jodconverter.php (アンダーバーあり)
 -> jodconverter.php (アンダーバーなし)

(2) 環境に合わせて、パスを設定する
-----
define("_C_WEBPHOTO_JAVA_PATH", "/usr/bin/" ) ;
define("_C_WEBPHOTO_JODCONVERTER_JAR", "/usr/local/java/jodconverter-2.2.1/lib/jodconverter-cli-2.2.1.jar" ) ;
-----


● 注意
大きな問題はないはずですが、小さな問題はあると思います。
何か問題が出ても、自分でなんとか出来る人のみお使いください。
バグ報告やバグ解決などは歓迎します。


=================================================
Version: 1.10
Date:   2009-01-25
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. 表示
(1) exif を 500バイトで制限する

2. 検索
(1) モジュール内検索のとき、説明文中のキーワードをハイライトする
(2) happy_search から検索しても、同様。

3. アイコン付きサムネイル
(1) 動画などからサムネイルを生成するときに、ファイル種別を示すアイコンを追加する
(2) 「画像処理を行わせるパッケージ選択」にて、「ImmageMagick」を選択したときに有効

4. プラグイン
ファイル種別に対応したプラグインを追加した
- audio
- html
- pdf
- txt
- video

5. テキスト・ファイル (txt)
(1) テキスト・ファイルからテキスト(文字列)を抽出し「テキスト文」欄に表示する
(2) 「テキスト文」を 500バイトで制限する
(3) 「テキスト文」を検索の対象にする

6. PDF ファイル (pdf)
(1) xpdf が必要です
http://www.foolabs.com/xpdf/
(2) PDF からサムネイルを生成する
(3) PDF からテキストを抽出し「テキスト文」欄に表示する

7. バグ対策
(1) 編集にて fetal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?viewmode=flat&topic_id=909&forum=13

(2) カテゴリー管理にて fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?viewmode=flat&topic_id=910&forum=13

8. データベース構造
テーブルの項目追加
(1) item テーブル: item_content など

9. プログラム構造
(1) class ディレクトリの下に edit ディレクトリを追加した


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
  自動的にテーブルに項目が追加されます。


● 使用上の注意
1. テキスト・ファイル
文字コードの自動認識をしていますが、うまくいかないこともあります。
文字化けする場合は「テキスト文」欄に直接 入力してください。

2. PDF ファイル
xpdf ではテキスト(文字列)が全て抽出されないことがあります。
抽出される場合とされない場合の違いが分かりません。
ご存知の方はご一報を。


● 注意
今回のバージョンでは、プログラム構造を大きく変更しました。
大きな問題はないはずですが、小さな問題はあると思います。
何か問題が出ても、自分でなんとか出来る人のみお使いください。
バグ報告やバグ解決などは歓迎します。


=================================================
Version: 1.00
Date:   2009-01-04
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. FCKeditor 対応
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=899&forum=13

(1) 記事ごとに エディタが選択できます。

2. バグ対策
(1) 携帯アクセスにて、fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=902&forum=13

(2) ヘルプにて、fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=904&forum=13

(3) ブロック表示にて、fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?viewmode=flat&topic_id=905&forum=13

3. データベース構造
3.1 テーブルの項目追加
(1) item テーブル: item_editor など


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
  自動的にテーブルに項目が追加されます。


● 使用上の注意
1. FCKeditor
(1) XOOPS_ROOT_PATH /common/fckeditor を設置する
http://xoops.peak.ne.jp/md/mydownloads/singlefile.php?lid=93

(2) 「各グループの権限」にて「HTML投稿可」にする

(3) 一度選択したエディタを変更することは、ちょっと面倒です。
「アイテムテーブル管理」にて「エディタ」欄を書き換える。
さらに、「写真説明文」「HTMLタグ」「スマイリーアイコン」「XOOPSコード」「画像」「改行」欄を
エディタに合うように変更する。


● 注意
大きな問題はないはずですが、小さな問題はあると思います。
何か問題が出ても、自分でなんとか出来る人のみお使いください。
バグ報告やバグ解決などは歓迎します。


=================================================
Version: 0.90
Date:   2008-12-20
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. 閲覧権限
1.1 カテゴリごとの閲覧権限を追加した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=893&forum=13

(1) 新しいカテゴリを追加するときは、親カテゴリの権限を継承する。
(2) カテゴリを変更したときは、下位のカテゴリの権限も変更する。

1.2 アイテムごとの閲覧権限を追加した

2. バグ対策
(1) モジュール複製時に、カテゴリ一覧が他のモジュールのものを表示する
(2) imagemanager からの投稿するとき、ダウンロード権限が設定されない
(3) 投稿・編集画面にて、近傍の Google アイコン が表示されない


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


● 使用上の注意
1. カテゴリの閲覧権限
カテゴリの閲覧権限は、親のカテゴリとは独立に作用します。
通常は、カテゴリの閲覧権限は、親のカテゴリと同じにしてください。

例えば、Ａカテゴリの下にＢカテゴリがあり、
Ａカテゴリはゲスト閲覧不可とし、
Ｂカテゴリは全て閲覧可としたときは、
Ｂカテゴリに属するアイテムはゲストに表示されます。

2. サーバーの負荷
カテゴリの閲覧権限 と アイテムの閲覧権限 は、サーバーの負荷が大きくなります。
カテゴリ数やアイテム数が多いときは、
複数のモジュールに分割して、
モジュール単位に権限設定を行うことをお勧めします。

なお、カテゴリの投稿権限 や アイテムのダウンロード権限 は、
サーバーの負荷にはさほど影響しません。


● 注意
大きな問題はないはずですが、小さな問題はあると思います。
何か問題が出ても、自分でなんとか出来る人のみお使いください。
バグ報告やバグ解決などは歓迎します。


=================================================
Version: 0.81
Date:   2008-12-10
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. 投票機能
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=868&forum=13
１から１０までの評価を「とても素晴らしい」など文章で表現するカスタマイズを可能にした

2. バグ修正
(1) コマンドのメール受信にて fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=882&forum=13

(2) QRコードが出力しない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=882&forum=13

(3) 複数設置の不具合
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=887&forum=13

(4) 携帯表示にて fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=889&forum=13

(5) RSS にて fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=890&forum=13

(6) magplus.cur が 404 エラー
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=890&forum=13


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
  自動的にテーブルに項目が追加されます。


=================================================
Version: 0.80
Date:   2008-11-30
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. アイコンの生成
従来は、アイテム毎にアイコンをコピーしていた。
今回から images/exts ディレクトリにあるアイコンをそのまま使用するように変更した。

2. ファイル管理
ファイルをフルパスから相対パスで指定するように変更した。
http://linux.ohwada.jp/modules/newbb/viewtopic.php?vtopic_id=869&forum=13

3. 発行日時と終了日時
3.1 アイコン毎に発行日時と終了日時を設定できる
3.2 自動管理
(1) 発行日時になると、自動的に表示可能になる
(2) 終了日時になると、自動的に非表示になる
3.3 アイテム管理にオフラインと期限切れの一覧表示を追加した

4. ブロックを追加した
(1) カテゴリ一覧
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=428&forum=11

(2) タグ一覧
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=835&forum=13

5. バグ対策
(1) 外部画像のときにポップアップする画像が正しくない
(2) 外部 swf が表示できない
(3) 管理者のタイムゾーンがサーバーと異なるときに、更新時刻がおかしくなる

6. データベース構造
6.1 テーブルの項目追加
(1) item テーブル: item_icon_width


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
  自動的にテーブルに項目が追加されます。


● 注意
大きな問題はないはずですが、小さな問題はあると思います。
何か問題が出ても、自分でなんとか出来る人のみお使いください。
バグ報告やバグ解決などは歓迎します。


● 謝辞
発行日時と終了日時に関して、webshow を参考にしました。
- http://wikiwebshow.com/
作者の方に、感謝します。


=================================================
Version: 0.70
Date:   2008-11-20
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更

1. コード表示を追加した
1.1 表示項目
(1) メディアのダウンロード
(2) フラッシュ動画のダウンロード
(3) サムネイル画像のURL
(4) ミドル画像のURL
(5) 詳細ページのURL
(6) サイトのURL
(7) object 形式の埋込み
(8) JavaScript 形式の埋込み

1.2 ダウンロードの許可
ダウンロードの許可をアイテム毎に設定する
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=418&forum=11

2. 動画投稿サイト
2.1 登録項目
(1) 表示画面の横幅と高さを追加した
(2) 埋込み形式を追加した

2.2 プラグイン
(1) pandora.tv を追加した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=873&forum=13

(2) general を追加した
埋込み形式を HTML 記法で登録する
管理者だけが設定できる

(3) webphoto を追加した
他の webphoto に登録されている動画を引用する

(4) youtube に横幅と高さを追加した

3. 詳細ぺージのナビ
数字からサムネイルに変更した

4. バグ対策
(1) 外部 URL の登録にて、fatal error
(2) プラグインの登録にて、fatal error
(3) プレイリストの登録にて、サムネイルが生成されない

5. データベース構造
テーブルの項目追加
(1) item テーブル: item_codeinfo


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
  自動的にテーブルに項目が追加されます。


● 謝辞
コード表示に関して、webshow を参考にしました。
- http://wikiwebshow.com/
作者の方に、感謝します。


=================================================
Version: 0.60
Date:   2008-11-10
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更

1. 画像のアップロード処理
1.1 サムネイル画像
(1) アップロード時の画像縮小を追加
(2) 削除を追加

1.2 ミドル画像
(1) アップロードを追加
(2) アップロード時の画像縮小を追加
(3) 削除を追加
(4) 外部URLの指定を追加

1.3 カテゴリ画像
(1) アップロードを追加
(2) アップロード時の画像縮小を追加
(3) アップロード済みファイルからの選択を追加

1.4 Google アイコン画像
(1) アップロード時の画像縮小を追加

1.5 Player ロゴ画像
(1) アップロード時の画像縮小を追加

1.6 JPEG 品質の追加
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=869&forum=13

2. JPEx 対応
管理画面のメニューの重複を対策
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=872&forum=13

3. バグ対策
(1) プレビューにて Fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=872&forum=13

(2) 「メール受信」にて Fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=872&forum=13

(3) Exif から Google Map 登録ができない

(4) 「ファイルからの画像追加」にて 動画のサムネイル生成ができない

(5) 「サムネイル再構築」の削除にて Fatal error

(6) 「アイテム・テーブル管理」の削除にて、Fatal error

4. データベース構造
4.1 テーブルの項目追加
(1) item テーブル: item_external_middle
(2) cat  テーブル: cat_img_name

4.2 一般設定 (config テーブル) の項目変更
「使用上の注意」参照


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


● 使用上の注意
1. 作業用ディレクトリ
一般設定から tmpdir を削除し、
代わりに workdir を追加した。

従来は、一時ディレクトリ (tmpdir) を設定していた。
今回から ルートとなる作業用ディレクトリ (workdir) を設定し、
その下のディレクトリを固定にした。

従来と同じもの
- tmp (一時的なファイル)

従来は tmp に置いていたファイルを分離したもの
- mail (受信メールの保存)
- log  (ログの保存)

[注意]
tmpdir をデフォルト値から変更している場合は、
上記に対応するように、手動でファイルを移動してください。


● 注意
大きな問題はないはずですが、小さな問題はあると思います。
何か問題が出ても、自分でなんとか出来る人のみお使いください。
バグ報告やバグ解決などは歓迎します。


=================================================
Version: 0.50
Date:   2008-10-30
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更

1. 外部メディア
従来はメディア・ファイルをアップロードする必要があった。
今回から、外部メディアの URL を指定することもできる。

2. 動画投稿サイト
(1) プラグインにより動画投稿サイトに対応した
(2) 下記の動画投稿サイトが用意されている
- www.youtube.com
- uncutvideo.aol.com
- www.dailymotion.com
- video.google.com
- www.livevideo.com
- www.metacafe.com
- vids.myspace.com
- video.msn.com
- www.veoh.com
- www.vimeo.com

3. 表現形式
従来は メディア形式 (拡張子) に対して固定的な表現をしていた。
今回から、管理者はメディア・ファイル毎に表現方法を選択することができる。

3.1 サムネイルをクリックしたときの動作
(1) 詳細ページを開く
(2) メディア・ファイルを開く
(3) 大きな画像をポップアップする (画像のとき)

3.2 詳細ページでの表現
(1) サムネイルを表示し、サムネイルをクリックすると、メディア・ファイルを開く
(2) サムネイルを表示し、サムネイルをクリックすると、大きな画像を表示する (画像のとき)
(3) 動画サイトのプラグインを表示する (動画サイトのとき)
(4) swfobject.swf で再生する
(5) mediaplayer.swf で再生する
(6) imagerotator.swf で再生する

3.3 フラッシュ・プレイヤーの変数
後述

4. popbox.js
http://www.c6software.com/Products/PopBox/Default.aspx

jpg,gif,png 形式の画像は、サムネイルをクリックしたときに、大きな写真がポップアップする。
デフォルトの設定 (v0.10 から)

5. swfobject.swf
http://blog.deconcept.com/swfobject/

swf 形式はこのプレイヤーにて再生する (新規)
デフォルトの設定 

6. mediaplayer.swf
http://www.jeroenwijering.com/?item=JW_FLV_Media_Player

下記の形式のファイルをこのプレイヤーにて再生する
(1) flv 形式 あるいは 投稿した動画から flv 形式に変換したもの (v0.2 から)
    デフォルトの設定
(2) mp3 形式 (v0.42 から) デフォルトの設定
(3) jpg,gif,png 形式 (新規)  管理者による設定

7. imagerotator.swf の追加
http://www.jeroenwijering.com/?item=JW_Image_Rotator

プレイリストの再生に使用する

8. プレイリスト
http://code.jeroenwijering.com/trac/wiki/Playlists3

8.1 mediaplayer.swf と imagerotator.swf にて、プレイリストの再生ができる

8.2 プレイリストの設定は２つの方法がある
(1) プレイリストのURLを指定する
(2) メディア・ファイルのある自サイト内のディレクトリを指定して、
      メディア・ファイルからプレイリストを生成する

8.3 サンプルとして、下記のメディア・ファイルを用意した
(1) 写真 (jpg) medias/sample_photo/
(2) 音楽 (mp3) medias/sample_music/

9. フラッシュ・プレイヤーの変数
http://code.jeroenwijering.com/trac/wiki/Flashvars3

(1) swfobject.swf, mediaplayer.swf, imagerotator.swf に対して、
投稿したメディア・ファイル毎に変数の設定ができる。
(2) swfobject.swf に対しては、大きさと色のみ有効。
(3) プレイヤー管理にて、フラッシュ・プレイヤーの大きさと色について、複数のパターンが設定できる。
メディア・ファイル毎に設定したパターンの中から選択する。
(4) その他の変数は、メディア・ファイル毎に設定する。
(5) 変数を設定しないときは、デフォルトの設定値が使用される。

10. color_picker.js の追加
http://www.softcomplex.com/products/tigra_color_picker/

フラッシュ・プレイヤーの色の設定に使用する

11. フラッシュ・プレイヤーのコールバック
mediaplayer.swf にて再生したときのログが収集できる
管理者設定

12. バグ対策
(1) 「ユーザー情報」にて、notice
(2) 「投票」にて、SQL syntax error
(3) 「myalbum からのインポート」にて、サムネイルがコピーされない
(4) 「サムネイルの再構築」にて、タイトルだけのアイテムにて notice

13. データベース構造
13.1 テーブルの追加
(1) player   テーブル: フラッシュ・プレイヤーの一部の変数を格納する
(2) flashvar テーブル: フラッシュ・プレイヤーの全ての変数を格納する

13.2 テーブルの項目追加
(1) item テーブル: item_player_id など 23項目を追加した

13.3 一般設定 (config テーブル) の項目変更
「使用上の注意」参照


● アップデート
(1) アップデートする前に、データベースのバックアップをとることを推奨します。
(2) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
  それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(3) 管理者画面にてモジュール・アップデートを実行する
(4) モジュール・アップデート後は、Webphoto の管理者画面にて「アップデート」を実行してください。
  item テーブルに追加した displaytype, onclick, duration を設定します。


● 使用上の注意
1. アップロード先のディレクトリ
一般設定から photospath, thumbspath, giconspath を削除し、
代わりに uploadspath を追加した。

従来は、アップロード先のディレクトリは、photos, thumbs, gicons 毎に変更可能であった。
今回から ルートとなるディレクトリ (uploadspath) のみを変更可能にして、
その下のディレクトリを固定にした。

従来と同じもの
- photos (画像や動画)
- thumbs (サムネイル)
- gicons (GoogleMapsのアイコン)

従来は photos に置いていたファイルを分離したもの
photos にある従来のファイルはそのまま使用される
新規に作成するファイルから適用される。
- middles (中間サイズのサムネイル)
- flashs  (自動生成したフラッシュ動画)
- qrs     (QRコード)

今回 追加したもの
- playlists (プレイリストのキャッシュ)
- logos     (プレイヤーのロゴ画像)

[注意]
photos, thumbs, gicons をデフォルト値から変更している場合は、
上記に対応するように、手動でファイルを移動してください。


● 注意
大きな問題はないはずですが、小さな問題はあると思います。
何か問題が出ても、自分でなんとか出来る人のみお使いください。
バグ報告やバグ解決などは歓迎します。


● 謝辞
動画投稿サイトやフラッシュ・プレイヤーの変数に関して、webshow を参考にしました。
- http://wikiwebshow.com/
作者の方に、感謝します。


=================================================
Version: 0.42
Date:   2008-10-13
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. MP3 を Flash プレーヤーで再生する
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=422&forum=11

2. ブラジル語 追加
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=429&forum=11

1. バグ対策
(1) 「テンプレート管理」でオリジナルファイルが表示されない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=858&forum=13

(2) 撮影時刻が 12:00:52 のとき 12:52 と記録される
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=860&forum=13

(3) mysql 5 でエラーになる

(4) 承認にて Fatal error

(5) xoops_group が設定されない


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


=================================================
Version: 0.41
Date:   2008-09-13
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. バグ対策
(1) ブロック内の日付とヒット数の表示
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=854

(2) インストールできない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=855

(3) 画像が登録されていないとイメージマネージャーが使えない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=857

(4) カテゴリを削除すると、fatal error

(5) カテゴリからの画像登録にて、カテゴリが選択されない


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する


=================================================
Version: 0.40
Date:   2008-09-01
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. 携帯電話 対応 第２弾
1.1 携帯メールによる投稿
(1) GPS 対応
画像あるいは本文に位置情報があると、GoogleMap を設定する
(2) i-phone 対応

1.2 携帯電話用の表示
(1) 「携帯電話にURLを送信する」を表示した
(2) URL情報をQRコードにて表示した
(3) 携帯電話でも表示できるように中間サイズ(480×480)の画像を作成した

1.3 メール受信のコマンド化
ユーザはメールを送信するだけです。
後は、サーバー側で自動的に投稿処理を行います。
「使用上の注意」参照

2. 「一般設定」の「一覧表示の表示タイプ」を有効にした
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=845&forum=13

3. d3forumコメント統合に対応した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=850&forum=13

4. バグ対策
(1) プレビューにて説明文が表示されない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=841

(2)「サムネイルの再構築」にて fatal error 
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=843

(3)「編集画面」にて fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=844&forum=13

(4)「編集画面」にて アイコン画像の alt が表示されない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=851&forum=13

(5) 「イマージマネジャー」からの登録にて fatal error

(6) 他のD3モジュールと衝突する

5. データベース構造
photo テーブルを廃止して、下記のテーブルを追加した
(1) item テーブル: photo テーブルの代わりとなる記事単位のテーブル
(2) file テーブル: photo テーブルの代わりとなる写真・動画単位のテーブル


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2) 管理者画面にてモジュール・アップデートを実行する
(3) 今回、テーブル構造を大きく変更しました。
モジュール・アップデート後は、Webphoto の管理者画面にて「アップデート」を実行してください。


● 使用上の注意
1. GPS 対応
(1) ドコモでは写真のExifに下記のような位置情報が挿入できます
---
GPSLatitudeRef: N
GPSLatitude.0: 35/1
GPSLatitude.1: 00/1
GPSLatitude.2: 35600/1000
GPSLongitudeRef: E
GPSLongitude.0: 135/1
GPSLongitude.1: 41/1
GPSLongitude.2: 35600/1000
----

(2) ドコモでは本文中に下記のような位置情報が挿入できます
http://www.docomo.co.jp/gps.cgi?lat=%2B35.00.35.600&lon=%2B135.41.35.600&geo=wgs84&x-acc=3

2. メール受信のコマンド化
(1) コマンドラインモードで動作させる
-----
php -q -f /XOOPS_ROOT_PATH/modules/webphoto/bin/retrieve.php -pass=xxx
-----
xxx はパスワード。
「一般設定」の「コマンドのパスワード 」に表示されている

(2) crontab に設定する
下記の例では１時間ごとにコマンドが起動される
----
12 * * * * php -q -f /XOOPS_ROOT_PATH/.../retrieve.php -pass=xxx
----

3. d3forumコメント統合
3.1 一般設定
webphoto の「一般設定」の下記の項目を設定する
「コメント統合するd3forumのdirname」
「コメント統合するフォーラムの番号」
「コメント統合の表示方法」

3.2 テンプレート
テンプレートファイルを変更する
  XOOPS_TRUST_PATH/modules/webphoto/templates/main_photo.html 
アスタリスク(*) を削除する
-----
<{* d3forum_comment dirname=$cfg_comment_dirname forum_id=$cfg_comment_forum_id class="WebphotoD3commentContent" mytrustdirname="webphoto" id=$photo.photo_id subject=$photo.title_s subject_escaped=1 view=$cfg_comment_view posts_num=10 *}>

 ↓

<{d3forum_comment dirname=$cfg_comment_dirname forum_id=$cfg_comment_forum_id class="WebphotoD3commentContent" mytrustdirname="webphoto" id=$photo.photo_id subject=$photo.title_s subject_escaped=1 view=$cfg_comment_view posts_num=10}>
-----

3.3 d3forum モジュール
d3forum モジュールの「コメント統合時の参照方法」に、下記のように記載する
-----
webphoto::WebphotoD3commentContent::webphoto
-----
最初の webphoto は XOOPS_ROOT_PATH 側のディレクトリ名 (モジュール複製により変更可)
最後の webphoto は XOOPS_TRUST_PATH 側のディレクトリ名 (変更不可)


● 注意
大きな問題はないはずですが、小さな問題はあると思います。
何か問題が出ても、自分でなんとか出来る人のみお使いください。
バグ報告やバグ解決などは歓迎します。


● 謝辞
下記にて配布されている「ＱＲコードクラスライブラリ」を使用しました。
- http://www.swetake.com/qr/
作者の方に、感謝します。


=================================================
Version: 0.30
Date:   2008-08-10
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. 携帯電話 対応
1.1 携帯メールによる投稿
(1) 携帯電話からメールを送信して、写真や動画を投稿することができます
(2) 最初に、携帯電話のメールアドレスを登録します
(3) ユーザへの説明は「ヘルプ」に表示します

1.2 携帯電話用の表示
(1) 240×320 程度の画面サイズを用意した。i.php
(2) 携帯電話の機種により、動作が異なります。
「使用上の注意」参照

1.3 メールログ管理
(1) 受信したメールは「一時ファイルの保存先ディレクトリ」に保存されます。
(2) 登録されたメールアドレスからのみ投稿が許可されます。
(3) 未登録のメールアドレスからのメールは「拒否されたメール」として管理されます。
(4) 管理者は「拒否されたメール」を投稿することが出来ます。

2. FTP による投稿
(1) FTP によりファイルをアップロードすることで、ファイル容量の大きな写真や動画を投稿することができます。
(2) ユーザへの説明は「ヘルプ」に表示します
(3) 「使用上の注意」参照

3. ブロックのキャッシュを追加した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=824

4. Exif の撮影日時を変更した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=828

5. バグ対策
(1) モジュールをアンインストールできない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=832

(2) 登録画面でプレビューできない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=834&forum=13

(3) 写真を削除できない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=838&forum=13

(4) ブロックでカテゴリが指定できない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=840&forum=13

6. データベース構造
(1) ユーザ毎のメールアドレスを保存する user テーブル を追加した
(2) メール投稿のログを保存する maillog テーブルを追加した


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2)  管理者画面にてモジュール・アップデートを実行する
(3) 「一時ファイルの保存先ディレクトリ」がフルパスで指定するように変更になりました。
「動作チェッカー」と「一般設定」にて確認してください。
(4) アップデート後は「携帯メールによる投稿」「FTP による投稿」は管理者にも許可されていません。
必要に応じて「各グループの権限」から設定してください。


● 使用上の注意
1. 携帯電話
1.1 携帯電話の機種依存性
ドコモの imodo シミュレータと実機 N903i で確認しています。
N903i の場合では。
携帯電話から投稿した写真は、同じ携帯電話で表示できますが、
大きな画像サイズのものは途中で切れてしまいます。
携帯電話から投稿した動画(iモーション)は、同じ携帯電話で再生できますが、
他の形式のものは再生することが出来ません。
他の機種に関する情報を提供してもらえると、ありがたいです。

1.2 一時ファイルの保存先ディレクトリ
受信したメールはこのディレクトリに保存されます。
メールには個人情報などが含まれますので、ドキュメント・ルートなどWEBブラウザからアクセス可能なエリアに保存するのは好ましくありません。
ドキュメント・ルートの外に設定することをお勧めします

2. FTP による投稿
http プロトコロは時間制限や容量制限があるため、ファイル容量の大きなものはアップロード出来ません。
FTP を併用することで、この制限が緩和されます。
一方、FTP により、ユーザが XOOPS ファイルへのアクセスすることも可能になります。
信頼できる仲間内で運用してください。
あるいは、複数の FTP ユーザが設定できる場合は、
XOOPS ファイルにはアクセスできない設定で運用してください。


● 注意
大きな問題はないはずですが、小さな問題はあると思います。
何か問題が出ても、自分でなんとか出来る人のみお使いください。
バグ報告やバグ解決などは歓迎します。


● 謝辞
携帯電話対応に関して、mailbbs を参考にしました。
- http://xoops.hypweb.net/modules/mailbbs/
作者の方に、感謝します。


=================================================
Version: 0.20
Date:   2008-07-09
=================================================

写真や動画を管理するアルバム・モジュールです。

● 主な変更
1. 動画機能の拡張
(1) ffmpeg が必要です
http://ffmpeg.mplayerhq.hu/

(2) 再生時間を自動取得する
(3) サムネイルを自動生成する
(4) Flash 動画を自動生成する

2. Flash 動画の再生
(1) mediaplayer.swf による再生
http://www.jeroenwijering.com/?item=JW_FLV_Media_Player

3. MIME タイプ
(1) 3g2, 3gp, asf, flv を追加した
(2) asx はメタ形式だったので、削除した

4. 下記の場合に Exif 情報を取得する
(1) ユーザ画面の新規登録と変更
(2) 管理者画面の myalbum と imagemanger からのインポート
(3) 管理者画面の画像一括登録
(4) 管理者画面のサムネイルの再構築

5. Pathinfo が使用できない環境にも対応した

6. xoops_module_header 競合の回避策を用意した

7. バグ対策
(1) RSS にて fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=818

(2) spinner40.gif が 404 error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=818

(3) typo
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=821

(4) <br> が出力する
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=823&forum=13

(5) imagemaneger にて fatal error

8. データベース構造
(1) mime テーブルに mime_ffmpeg 項目を追加した


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2)  管理者画面にてモジュール・アップデートを実行する


● 使用上の注意
1. ffmpeg
ffmpeg は バージョンやコンパイル・オプションで動作が異なります。
Flash 動画の生成には、ファイル種別毎に個別の対応が必要になることがあります。
mime テーブルに Flash 動画生成時のコマンド・オプションが設定できます。
デフォルトでは、全てのビデオに "-ar 44100" を設定しています。

2. xoops_module_header 競合の回避策
ブロックにて写真のポップアップが出来ないことがあります。
原因の１つに、テンプレート変数 xoops_module_header の使用が他のモジュールやブロックと競合していることがあります。
これを回避する方法を２つ用意した。

2.1 専用のテンプレート変数を用意する方法
(1) テーマのテンプレートに専用のテンプレート変数を追加する

XOOPS_ROOT_PATH/themes/貴方のテーマ/theme.html
-----
<{$xoops_module_header}>
<{* 下記を追記する *}>
<{$xoops_webphoto_header}>
-----

(2) preload ファイルをリネームする
XOOPS_TRUUST_PATH/modules/webphoto/preload/_constants.php (アンダーバーあり)
 -> constants.php (アンダーバーなし)

(3) _C_WEBPHOTO_PRELOAD_XOOPS_MODULE_HEADER を有効にする
先頭の // を削除する
-----
//define("_C_WEBPHOTO_PRELOAD_XOOPS_MODULE_HEADER", "xoops_webphoto_header" )
-----

(4) 管理者画面 -> システム設定メイン -> 一般設定 にて
「themes/ ディレクトリからの自動アップデートを有効にする」を「はい」にする

(5) ブロックにて写真のポップアップが確認できたら、
「themes/ ディレクトリからの自動アップデートを有効にする」を「いいえ」にする

2.2 body 部に style_sheet と javascript を記述する方法
body 部に style_sheet を記述するのは、HTML 文法違反ですが、ブラウザの動作には支障ないようです。

(1) preload ファイルをリネームする
XOOPS_TRUUST_PATH/modules/webphoto/preload/_constants.php (アンダーバーあり)
 -> constants.php (アンダーバーなし)

(2) _C_WEBPHOTO_PRELOAD_BLOCK_POPBOX_JS を有効にする
先頭の // を削除する
-----
//define("_C_WEBPHOTO_PRELOAD_BLOCK_POPBOX_JS", "1" )
-----


● 注意
大きな問題はないはずですが、小さな問題はあると思います。
何か問題が出ても、自分でなんとか出来る人のみお使いください。
バグ報告やバグ解決などは歓迎します。


● 謝辞
ffmpeg に関して、WEB にある情報を参考にしました。
特に、再生時間の取得に関しては、下記のページが有益でした。
- http://blog.ishiro.com/?p=182
作者の方々に、感謝します。


=================================================
Version: 0.10
Date:   2008-06-21
=================================================

写真や動画を管理するアルバム・モジュールです。

アルバム・モジュールの定番である myalbum と基本的な仕様と機能は同じです。
実装は全く異なります。


● 主な機能
1. myalbum を継承した機能
myalbum v2.88 の全ての機能

2. インデックス情報の拡張
(1) 撮影日
(2) 撮影場所
(3) 撮影機材
(4) タグ・クラウド
(5) 類似語辞書によるあいまい検索

(6) GoogleMaps 対応
http://code.google.com/intl/ja/apis/maps/

(7) Exif 対応
http://ja.wikipedia.org/wiki/Exchangeable_image_file_format

3. 写真と動画を一元的に扱うための機能
(1) MIMEタイプ管理の簡易化
(2) サムネイル登録の追加

4. リッチ・インターフェイス
(1) popbox.js による 写真のポップアップ
(2) prototype.js による 表示・非表示の切替え
(3) pathinfo を利用した静的風 URL

(4) piclens 対応
http://www.cooliris.com/

(5) Google ガジェット対応
http://desktop.google.com/plugins/i/mediarssslideshow.html

5. RSS
(1) MediaRSS 対応
(2) GeoRSS 対応

6. 実装方式
(1) D3 形式
(2) プリロード 

7. その他
(1) 類推しにくいファイル名の採用

8. データベース構造

□ myalbun を継承した テーブル
8.1 写真テーブル (photo table)
(1) メイン画像のフルURLを格納する項目を追加
(2) サムネイル画像のフルURLを格納する項目を追加
(3) 画像の大きさなどの属性項目の追加
(4) 撮影日 などのインデックス項目を追加
(5) カスタマイズ用のテキスト項目の追加

8.2 カテゴリテーブル (cat table)
(1) 画像の大きさなどの属性項目の追加
(2) カスタマイズ用のテキスト項目の追加

8.3 投票テーブル (vote table)
項目名を変更した。内容には変更なし。

□ 追加したテーブル
8.4 Google アイコンテーブル (gicon table)
Googleマップのアイコンを格納するテーブル

8.5 MIMEタイプテーブル (mime table)
MIMEタイプを格納するテーブル

8.6 タグテーブル (tag table)
タグを格納するテーブル

8.7 写真タグ関連テーブル (p2te table)
写真テーブルとタグテーブルを関連付けするテーブル

8.8 類似語テーブル (syno table)
あいまい検索のための類似語を格納するテーブル


● インストール
1. 共通 ( xoops 2.0.16a JP および XOOPS Cube 2.1.x )
解凍すると、html と xoops_trust_path の２つディレクトリがあります。
それぞれ、XOOPS の該当するディレクトリに格納ください。

イントール時に下記のような Warning が出ますが、
動作には支障ないので、無視してください。
-----
Warning [Xoops]: Smarty error: unable to read resource: "db:_inc_gmap_js.html" in file class/smarty/Smarty.class.php line 1095
-----

2. xoops 2.0.18
上記に加えて
(1) preload ファイルをリネームする
XOOPS_TRUST_PATH/modules/webphoto/preload/_constants.php (アンダーバーあり)
 -> constants.php (アンダーバーなし)

(2) _C_WEBPHOTO_PRELOAD_XOOPS_2018 を有効にする
先頭の // を削除する
-----
//define("_C_WEBPHOTO_PRELOAD_XOOPS_2018", 1 ) ;
-----


● モジュール複製
1. 共通 ( xoops 2.0.16a JP および XOOPS Cube 2.1.x )
ディレクトリをコピーするだけです。

例えば、ディレクトリ hoge にコピーする。
XOOPS_ROOT_PATH/modules/webphoto/* 
 -> XOOPS_ROOT_PATH/modules/hoge/* 

2. xoops 2.0.18
上記に加えて、テンプレートファイルをリネームしてください。

XOOPS_ROOT_PATH/modules/hoge/templates/webphoto_*.html 
 -> XOOPS_ROOT_PATH/modules/hoge/templates/hoge_*.html 


● picles
piclens に対応しています
http://www.cooliris.com/

RSS を複数出力する XOOPS サイトの構成にしている場合は、
webphoto モジュールの出力する RSS が一番最初になるように設定してください

例えば、テーマテンプレートに whatsnew モジュールの RSS を設定している場合は、
下記の順番にする

themes/xxx/theme,html
-----
<{$xoops_module_header}>

<!-- xoops_module_header の下に記述する -->
<link rel="alternate" type="application/rdf+xml" title="RDF" href="<{$xoops_url}>/modules/whatsnew/rdf.php" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="<{$xoops_url}>/modules/whatsnew/rss.php" />
<link rel="alternate" type="application/atom+xml" title="ATOM" href="<{$xoops_url}>/modules/whatsnew/atom.php" />
-----


● 注意
フルスクラッチのアルファ版です。
大きな問題はないはずですが、小さな問題はあると思います。
何か問題が出ても、自分でなんとか出来る人のみお使いください。
バグ報告やバグ解決などは歓迎します。


● 謝辞
全体的な仕様に関して、myalbum を参考にしました。
- http://xoops.peak.ne.jp/md/mydownloads/singlefile.php?lid=61&cid=1
Google アイコンに関して、gnavi を参考にしました。
- http://xoops.iko-ze.net/modules/d3downloads/index.php?page=singlefile&cid=1&lid=5
MIME 管理に関して、wf-downloads を参考にしました。
- http://smartfactory.ca/modules/wfdownloads/singlefile.php?cid=16&lid=49
作者の方々に、感謝します。

