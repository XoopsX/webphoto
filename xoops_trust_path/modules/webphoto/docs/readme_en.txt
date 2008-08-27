$Id: readme_en.txt,v 1.7 2008/08/27 04:09:52 ohwada Exp $

=================================================
Version: 0.40
Date:   2008-08-26
Author: Kenichi OHWADA
URL:    http://linux2.ohwada.net/
Email:  webmaster@ohwada.net
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Supported mobile phone: 2nd version
1.1 Post from the mobile phone
(1) Supported GPS
this module sets GoogleMap, 
when there is GPS information in the image or this message body. 
(2) Supported i-phone

1.2 View for the mobile phone
(1) Show "Send URL to the mobile phone"
(2) Show QR code with URL
(3) Creat and show the small image (480Å~480) for the mobile phone

1.3 Command for retrieveing mails
The user sends email, 
and then the server processes to post the image automatically.
refer "Notice for usage"

2. Enabled "Type of view" in "Preferences"
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=845&forum=13

3. Bug fix
(1) cannot preview description in submit form
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=841

(2) fatal error in "Rebuild Thumbnails"
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=843

(3) fatal error in "Edit Photo"
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=844&forum=13

(4) fatal error in "Image Manager"

(5) conflict with other D3 module

4. Database structure
abolished photo table and added following tables.
(1) item table: the table for each item which replaces photo table
(2) file table: the table for each photo/video file which replaces photo table


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp
(3) Webphoto is chaneged database structure .
please execute "Update" in webphoto's admin cp


* Notice for usage *
1. Supported GPS
(1) In DoCoMo phone, the GPS information can be embedded in Exif of the photo.
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

(2) In DoCoMo phone, the GPS information can be inserted in massage body
http://www.docomo.co.jp/gps.cgi?lat=%2B35.00.35.600&lon=%2B135.41.35.600&geo=wgs84&x-acc=3

2. Command for retrieveing mails
(1) works by the command line mode
-----
php -q -f /XOOPS_ROOT_PATH/modules/webphoto/bin/retrieve.php -pass=xxx
-----
xxx is password.
password is shown in "Command Password" in "Preferences"

(2) sets in crontab
the command is executed every 1 hour in the following sample
----
12 * * * * php -q -f /XOOPS_ROOT_PATH/.../retrieve.php -pass=xxx
----


* Notice *
Although there are no big problem, but I think that there are any small problem. 
Even if some problems come out, only those who can do somehow personally need to use. 
Welcome a bug report, a bug solution, and your hack, etc.


* Special Thanks *
Used "QR code class library" in the following site.
- http://www.swetake.com/qr/
Special thanks to authors.


=================================================
Version: 0.30
Date:   2008-08-10
Author: Kenichi OHWADA
URL:    http://linux2.ohwada.net/
Email:  webmaster@ohwada.net
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Supported mobile phone
1.1 Post from the mobile phone
(1) the user can post the photo and video by email from the mobile phone
(2) firstly, the user register the email address of mobile phone
(3) show the explanation to the user in "Help".

1.2 View for the mobile phone
(1) prepared about 240Å~320 pixel web page. i.php
(2) the operation depends on the model of the mobile phone.
refer "Notice for usage"

1.3 Mail log management
(1) this module preserves the received emails in "Path to temporary" .
(2) this module permits to post only email from the registered email address.
(3) this module manages emails from the unregistered e-mail address as "reject mails"
(4) the admin can post "reject mails"

2. Post by FTP
(1) the user can post the big size photo and video, when the user upload the file by FTP
(2) show the explanation to the user in "Help".
(3) refer "Notice for usage"

3. Added the cache of blocks
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=824

4. Changed Exif datetime
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=828

5. Bug fix
(1) cannot uninstall the module
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=832

(2) cannot preview in submit
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=834&forum=13

(3) cannot delete photo
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=838&forum=13

(4) cannot select category in block
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=840&forum=13

6. Database structure
(1) added user table which save user's email address
(2) added maillog table which save the log of posting by email


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp
(3) Webphoto is chaneged to specify by the full path in "Path to temporary" .
please confirm "Check Configuration" and "Preferences"
(4) After updating, the admin has no permission for "Post by Mail" and "Post by FTP" .
Please set permission in "Global Permissions" as occasion demands .


* Notice for usage *
1. Mobile phone
1.1 Model dependent
I tested in Japanese DoCoMo imodo simulator and the actual phone N903i.
By the case of N903i.
The phone can show the photo which the same phone posted.
But the phone show broken photo which bigger than.
The phone can show the video (i motion)  which the same phone posted.
But the phone cannot show other format video.
I am happy when you teach me the other model's information .

1.2 Path to temporary
this module preserves the received emails in this derectory.
It is not desirably that preserve emails in the accessible area by the WEB browsers such as the document route, because the email has personal information.
Recommend to set to this out of the document route.

2. Post by FTP
Because http protocol has a time limit and file size limit, 
the user cannot upload the large file.
This limitation is eased to use FTP.
On the other hand, with the FTP, the user can access XOOPS files.
Please operate in the pal who can trust.
Or, if the admin can add two or more FTP users,
operate by the setting which the user cannot access XOOPS files.


* Notice *
Although there are no big problem, but I think that there are any small problem. 
Even if some problems come out, only those who can do somehow personally need to use. 
Welcome a bug report, a bug solution, and your hack, etc.


* Special Thanks *
Referred mailbbs module about mobile phone . 
- http://xoops.hypweb.net/modules/mailbbs/
Special thanks to authors.


=================================================
Version: 0.20
Date:   2008-07-09
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

3. MIME type
(1) added 3g2, 3gp, asf, flv
(2) removed asx, because meta file

4. Getting Exif in the following.
(1) "Add Photo" and "Edit Photo" in user mode
(2) "Import" from malbum and imagemanger in the admin cp
(3) "Batch Register" in the admin cp
(4) "Rebuild Thumbnails" in the admin cp

5. Supported the server which can not be used Pathinfo

6. Avoid for conflict to use "xoops_module_header"

7. Bug fix
(1) fatal error in RSS
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=818

(2) 404 error with spinner40.gif 
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=818

(3) typo
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=821

(4) display <br>
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=823&forum=13

(5) fatal error in imagemaneger

8. Database structure
(1) added mime_ffmpeg column in mime table


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


* Notice for usage *
1. ffmpeg
"ffmpeg" is operated depends on the version and the compilation option.
Sometimes you have to set options, when create Flash video.
You can set "ffmpeg" command option for creating Flash video in mime table.
In default, set "-ar 44100" in all video types.

2. Avoid for conflict to use "xoops_module_header"
Sometime dont work popup photo in block.
Is is one of the cause that other module or other block conflict to use the template variable xoops_module_header.
webphoto prepared two ways of avoiding this.

2.1 The way to prepare the special template variable
(1) please add the special template variable to the theme template file

XOOPS_ROOT_PATH/themes/YOUR_THEME/theme.html
-----
<{$xoops_module_header}>
<{* add the following *}>
<{$xoops_webphoto_header}>
-----

(2) rename preload file
XOOPS_TRUUST_PATH/modules/webphoto/preload/_constants.php (with undebar)
 -> constants.php (without undebar)

(3) change _C_WEBPHOTO_PRELOAD_XOOPS_MODULE_HEADER in valid
remove // at the head.
-----
//define("_C_WEBPHOTO_PRELOAD_XOOPS_MODULE_HEADER", "xoops_webphoto_header" )
-----

(4) admin CP -> Preferences Main -> General Settings
set "Yes" in "Check templates for modifications ?"

(5) after confirm to work popup photo in block,
set "No" in "Check templates for modifications ?"


2.2 The way to describe style_sheet and javascript in boby part in the block
it is the HTML validation error that describe style_sheet in boby part.
however, it seems that the WEB browser operate well

(1) rename preload file
XOOPS_TRUUST_PATH/modules/webphoto/preload/_constants.php (with undebar)
 -> constants.php (without undebar)

(2) change _C_WEBPHOTO_PRELOAD_BLOCK_POPBOX_JS in valid
remove // at the head.
-----
//define("_C_WEBPHOTO_PRELOAD_BLOCK_POPBOX_JS", "1" )
-----


* Notice *
Although there are no big problem, but I think that there are any small problem. 
Even if some problems come out, only those who can do somehow personally need to use. 
Welcome a bug report, a bug solution, and your hack, etc.


* Special Thanks *
Referred informations in the internet about ffmpeg . 
Specifically, the following page was useful about getting duration time .
- http://blog.ishiro.com/?p=182
Special thanks to authors.


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
in addition to the above

(1) rename preload file.
XOOPS_TRUUST_PATH/modules/webphoto/preload/_constants.php (with undebar)
 -> constants.php (without undebar)

(2) change _C_WEBPHOTO_PRELOAD_XOOPS_2018 in valid
remove // at the head.
-----
//define("_C_WEBPHOTO_PRELOAD_XOOPS_2018", "1" )
-----


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


* Special Thanks *
Referred myalbum module about general specification . 
- http://xoops.peak.ne.jp/md/mydownloads/singlefile.php?lid=61&cid=1
Referred gnavi module about google icon . 
- http://xoops.iko-ze.net/modules/d3downloads/index.php?page=singlefile&cid=1&lid=5
Referred wf-downloads module about MIME management . 
- http://smartfactory.ca/modules/wfdownloads/singlefile.php?cid=16&lid=49
Special thanks to authors.

