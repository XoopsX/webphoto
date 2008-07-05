$Id: readme_jp.txt,v 1.3 2008/07/05 12:54:16 ohwada Exp $

=================================================
Version: 0.20
Date:   2008-07-05
Author: Kenichi OHWADA
URL:    http://linux.ohwada.jp/
Email:  webmaster@ohwada.jp
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
(1) mediaplayer.swf  による再生
http://www.jeroenwijering.com/?item=JW_FLV_Media_Player

3. Pathinfo が使用できない環境にも対応した

4. バグ対策
(1) RSS にて fatal error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=818

(2) spinner40.gif が 404 error
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=818

(3) typo
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=821

(4) <br> が出力する
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=823&forum=13

(5) imagemaneger にて fatal error

5. データベース構造
(1) mime テーブルに mime_ffmpeg 項目を追加した


● アップデート
(1) 解凍すると、html と xoops_trust_path の２つディレクトリがあります。
それぞれ、XOOPS の該当するディレクトリに上書きしてください。
(2)  管理者画面にてモジュール・アップデートを実行する


● 使用上の注意
ffmpeg は バージョンやコンパイル・オプションで動作が異なります。
Flash 動画の生成には、ファイル種別毎に個別の対応が必要になることがあります。
mime テーブルに Flash 動画生成時のコマンド・オプションが設定できます。
デフォルトでは、avi に "-ar 44100" を設定しています。


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
上記に加えて、preload ファイルをリネームする

XOOPS_TRUUST_PATH/modules/webphoto/preload/_constants.php (アンダーバーあり)
 -> constants.php (アンダーバーなし)


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
