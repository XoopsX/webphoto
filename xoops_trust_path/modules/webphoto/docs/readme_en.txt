$Id: readme_en.txt,v 1.3 2008/07/05 12:54:16 ohwada Exp $

=================================================
Version: 0.20
Date:   2008-07-05
Author: Kenichi OHWADA
URL:    http://linux2.ohwada.net/
Email:  webmaster@ohwada.net
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Extention of video feature
(1) require ffmpeg
http://ffmpeg.mplayerhq.hu/

(2) get duratio time automatically
(3) create thumbnail from video automatically
(4) create Flash video automatically

2. Flash video player
(1) using mediaplayer.swf
http://www.jeroenwijering.com/?item=JW_FLV_Media_Player

3. Supported the server which can not be used Pathinfo

4. Bug fix
(1) fatal error in RSS
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=818

(2) 404 error with spinner40.gif 
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=818

(3) typo
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=821

(4) display <br>
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=823&forum=13

(5) fatal error in imagemaneger

5. Database structure
(1) added mime_ffmpeg column in mime table


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


* Notice for usage *
"ffmpeg" is operated depends on the version and the compilation option.
Sometimes you have to set options, when create Flash video.
You can set "ffmpeg" command option for creating Flash video in mime table.
In default, set "-ar 44100" in avi.


=================================================
Version: 0.10
Date:   2008-06-21
=================================================

This is the album module which manages photos and videos.

The basic specification and the feature are same as myalbum module.
The implementing is different completely with myalbum

* Feature *
1. Feature which was succeeded from myalbum
all feature based on myalbum v2.88

2. Extension of the index information
(1) Photo Date
(2) Photo Place
(3) Photo Equipment
(4) Tag Cloud
(5) The ambiguous search using the synonym dictionary

(6) suport GoogleMaps
http://code.google.com/intl/en/apis/maps/

(7) support Exif
http://en.wikipedia.org/wiki/Exchangeable_image_file_format

3. Feature to manage photos and videos uniformly
(1) Simplification of MIME type management
(2) Addition of thumbnail image registration

4. Rich Interface
(1) Popup photo using popbox.js
(2) Switch of show or hide using prototype.js
(3) Static URL using pathinfo

(4) support Piclens
http://www.cooliris.com/

(5) support Google desktop gadget
http://desktop.google.com/plugins/i/mediarssslideshow.html

5. RSS
(1) Support MediaRSS
(2) Support GeoRSS

6. Implement
(1) D3 Style
(2) Preload 

7. Others
(1) Adopt the file name whitch is not easy to analogize

8. Database structure

# Tables which was succeeded from myalbum
8.1 photo ( photo table )
(1) added field which store full URL of photo image
(2) added field which store full URL of thumbnail image
(3) added field which store image size and mime type
(4) added field which store photo date and place
(5) added field for customize

8.2 category ( cat table )
(1) added field which store image size
(2) added field for customize

8.3 vote ( vote table )
changed field name. not chaneg feature.

# New Tables
8.4 Google Icon (gicon table)
this table store GoogleMaps Icons

8.5 MIME type (mime table)
this table store MIME Type

8.6 tag (tag table)
this table store tags

8.7 photo-tag (p2te table)
this table relate photo table and tag table.

8.8 synonym (syno table)
this table store synonym for ambiguous search 


* Install *
1. common ( xoops 2.0.16a JP and XOOPS Cube 2.1.x )
When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy in the directory which XOOPS correspond

When you install, the xoops output warning like the following.
Please ignore, because xoops and webphoto work well.
-----
Warning [Xoops]: Smarty error: unable to read resource: "db:_inc_gmap_js.html" in file class/smarty/Smarty.class.php line 1095
-----

2. xoops 2.0.18
in addition to the above, rename preload file.

XOOPS_TRUUST_PATH/modules/webphoto/preload/_constants.php (with undebar)
 -> constants.php (without undebar)


* Module Duplication *
1. common ( xoops 2.0.16a JP and XOOPS Cube 2.1.x )
copy directory only

for exsample, copy to 'hoge' directory
XOOPS_ROOT_PATH/modules/webphoto/* 
 -> XOOPS_ROOT_PATH/modules/hoge/* 

2. xoops 2.0.18
in addition to the above, rename template files.

XOOPS_ROOT_PATH/modules/hoge/templates/webphoto_*.html 
 -> XOOPS_ROOT_PATH/modules/hoge/templates/hoge_*.html 


* Piclens *
this module support piclens
http://www.cooliris.com/

When your xoops site which outputs more than one RSS,
you set outputs first the RSS of webphoto module.
For example, when you set the RSS of whatsnew module in the theme template,
you should describe the following.

themes/xxx/theme,html
-----
<{$xoops_module_header}>

<!-- described under xoops_module_header -->
<link rel="alternate" type="application/rdf+xml" title="RDF" href="<{$xoops_url}>/modules/whatsnew/rdf.php" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="<{$xoops_url}>/modules/whatsnew/rss.php" />
<link rel="alternate" type="application/atom+xml" title="ATOM" href="<{$xoops_url}>/modules/whatsnew/atom.php" />
-----


* Notice *
This is alpha version of full scratch.
Although there are no big problem, but I think that there are any small problem. 
Even if some problems come out, only those who can do somehow personally need to use. 
Welcome a bug report, a bug solution, and your hack, etc.

