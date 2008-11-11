$Id: readme_jp.txt,v 1.14 2008/11/11 07:04:08 ohwada Exp $

=================================================
Version: 0.60
Date:   2008-11-10
Author: Kenichi OHWADA
URL:    http://linux.ohwada.jp/
Email:  webmaster@ohwada.jp
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
XOOPS_TRUUST_PATH/modules/webphoto/preload/_constants.php (アンダーバーあり)
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

