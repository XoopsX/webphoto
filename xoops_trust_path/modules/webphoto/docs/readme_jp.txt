$Id: readme_jp.txt,v 1.1 2008/06/21 12:22:17 ohwada Exp $

=================================================
Version: 0.10
Date:   2008-06-08
Author: Kenichi OHWADA
URL:    http://linux.ohwada.jp/
Email:  webmaster@ohwada.jp
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
解凍すると、html と xoops_trust_path の２つディレクトリがあります。
それぞれ、XOOPS の該当するディレクトリに格納ください。


● モジュール複製
1. xoops 2.0.16a JP および XOOPS Cube 2.1.x
ディレクトリをコピーするだけです。

例えば、ディレクトリ hoge にコピーする。
XOOPS_ROOT_PATH/modules/webphoto/* 
 -> XOOPS_ROOT_PATH/modules/hoge/* 

2. xoops 2.0.18
上記に加えて、テンプレートファイルをリネームしてください。

XOOPS_ROOT_PATH/modules/hoge/templates/webphoto_*.html 
 -> XOOPS_ROOT_PATH/modules/hoge/templates/hoge_*.html 


● 注意
フルスクラッチのアルファ版です。
大きな問題はないはずですが、小さな問題はあると思います。
何か問題が出ても、自分でなんとか出来る人のみお使いください。
バグ報告やバグ解決などは歓迎します。
