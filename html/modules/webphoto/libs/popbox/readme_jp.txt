$Id: readme_jp.txt,v 1.2 2011/11/04 03:51:30 ohwada Exp $

=================================================
PopBox.js の設置について
2011-11-03 K.OHWADA
=================================================

■ ファイル配置

libs/popbox/
- PopBox.js
- Styles.css

libs/popbox/images/
- magminus.cur
- magplus.cur

images/ (※ IE用)
- magminus.cur
- magplus.cur

images/popbox/
- magminus.gif
- magplus.gif
- spinner40.gif

※ IE用
Styles.css にて url("images/magminus.cur") と指定している。
仕様上は css ファイルからの相対位置になるが。
IE では html ファイルからの相対位置になる。


■ 来歴

v2.50 (2011-11)
PopBox.js v2.7a (July 3, 2009) に更新
webphoto／「最新の画像」クリックでＩＥではフリーズ
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1189&forum=13

v0.81 (2008-12)
webphoto 0.80でRSSが出力されません
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=890&forum=13

v0.20 (2008-07)
RSSを取得できません
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=818&forum=13

v0.10 (2008-06) 
PopBox.js v2.5 (December 18, 2007) を設置
