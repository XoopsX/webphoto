$Id: readme_en.txt,v 1.67 2012/10/05 12:56:30 ohwada Exp $

=================================================
Version: 2.61
Date:   2012-10-05
Author: Kenichi OHWADA
URL:    http://linux.ohwada.jp/
Email:  webmaster@ohwada.jp
=================================================

This is the album module which manages photos and videos.

* Changes *
1. bug fix
(1) NOT add new category
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1280&forum=13


=================================================
Version: 2.60
Date:   2011-12-25
=================================================

This is the album module which manages photos and videos.

* Changes *
1. timeline
(1) supoort before 1970 (unixtime) 
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1186&forum=13

(2) add century and day to unit
(3) show timeline in category
(4) When move to a large time from a category, the information on a category is succeeded. 

2. map
(1) When move to a large map from a category, the information on a category is succeeded. 


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp
(3) Execute "File Valid Check" of "Update" in webphoto's admin control, 
  and cofirm whether or not necessary files are set.


=================================================
Version: 2.51
Date:   2011-11-11
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Migrating to MySQL 5.5
(1) TYPE=MyISAM -> ENGINE=MyISAM

2. bug fix
(1) Fatal error in Command Management
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1205&forum=13

(2) Fatal error in visit


=================================================
Version: 2.50
Date:   2011-11-03
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Migrating to PHP 5.3
Deprecated features in PHP 5.3.x
http://www.php.net/manual/en/migration53.deprecated.php
(1) ereg
(2) Assigning the return value of new by reference is now deprecated.

2. Update PopBix.js to v2.7a
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1189&forum=13

3. Global Permissions on install
(1) to Registered Users, allow Post (need approval) and othes. 
(2) to Anonymous Users, allow Module Access. 

4. Tag management
in Item Management, the admin can edit all tags. 

* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp
(3) Execute "File Valid Check" of "Update" in webphoto's admin control, 
  and cofirm whether or not necessary files are set.


=================================================
Version: 2.41
Date:   2011-06-05
=================================================

This is the album module which manages photos and videos.

* Changes *
1. bug fix
(1) WRONG url in gmap when not use pathinfo
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1178&post_id=4302

(2) Fatal Error in import from webphoto


=================================================
Version: 2.41
Date:   2011-05-16
=================================================

This is the album module which manages photos and videos.

* Changes *
1. bug fix
(1) can NOT install module
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1177&post_id=4297

(2) can NOT "mail register"


=================================================
Version: 2.40
Date:   2011-05-10
=================================================

This is the album module which manages photos and videos.

* Changes *
1. file download
(1) download file by an original file name. 
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1167&forum=13

(2) image file show two mode, download and the image display.
(3) add 'thumb' to the thumbnail file, 
because the thumbnail file has same name as media file

2. Flash Player
(1) version up to "JW Player 5.6"
(2) modify greatly "Edit of Flash Player's options"

3. Mail recive
(1) support Gmail for POP server
(2) support the platform dependent Japansese character
(3) support the file name with space. 
(3) Title is made from filename when there was no Subject. 
(4) extend to 5 mail address.
(5) use PEAR library

4. bugfix
(1) play the first one music in the playlist
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1152&forum=13

(2) Fatal Error in commnad test excute
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1154&forum=13

(3) Fatal Error in import from myalbum-p
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1157&forum=13

(4) Fatal Error in expire list of "Item Manager"
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1158&forum=13

(5) Fatal Error in download
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1167&forum=13

(6) Fatal Error in video edit of "Item Manager"

(7) NOT set offline in "Item Manager"

(8) link with the page that doesn't exist in playlist

5. Database structure
(1) flashvar table : support "JW Player 5.6"

6. PEAR library
enclose the following
at May, 2011
(1) Net_Socket 1.0.10 
(2) Net_POP3 1.3.8
(3) Mail_mime 1.8.1
(4) Mail_mimeDecode 1.5.5 


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp
(3) Execute "File Valid Check" of "Update" in webphoto's admin control, 
  and cofirm whether or not necessary files are set.


=================================================
Version: 2.32
Date:   2010-11-17
=================================================

This is the album module which manages photos and videos.

* Changes *
1. bugfix
(1) In batch registration, NOT create the thumbnail
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1146

(2) In batch registration, set wrong data

(3) In imagemanager, NOT submit the image.


=================================================
Version: 2.31
Date:   2010-11-03
=================================================

This is the album module which manages photos and videos.

* Changes *
1. bugfix
(1) NOT create new category
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1139

(2) NOT show RSS
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1125


=================================================
Version: 2.30
Date:   2010-10-10
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Media file
(1) added image : svg jpeg(cmyk)
(2) added video : mp4 mpe m1v
(3) added audio : wma aiff au

2. Image file
2.1 Media file
In old version, when a uploaded image is larger than the set size,
Webphoto saved a resized image, and not saved an original image.
Since this version, Webphoto saves an original image.

2.2 Thumbnail image
(1) Webphoto converts from the media files into the JPEG image,
and generates the thumbnail image. 
In old version, Webphoto not saved the JPEG image.
Since this version, Webphoto saves the JPEG image.

(2) In old version, Webphoto generated the thumbnail image 
from the GIF image and the PNG image with the same format. 
Since this version, Webphoto convertes to the JPEG image,
and generates the thumbnail image.

3. Media player
(1) Vesion up
- JW Player 5.2
- JW Image Rotator 3.18
(2) Media player can play H.264/AAC format.

4. Video site
4.1 show preview when submit video.
4.2 support souece id sm*** and nm*** for niconico video.

5. Language file
changed 'Photo' to 'Photo Video Media'

6. Bug fix
(1) NOT show download file.
(2) NOT show submit form, when users are too much.

7. Database structure
(1) item table: add field item_displayfile etc


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp
(3) Execute "File Valid Check" of "Update" in webphoto's admin control, 
  and cofirm whether or not necessary files are set.


* Notice *
In this version, I changed many files, for "Image file".
Although there are no big problem, 
but I think that there are any small problem. 
Welcome a bug report, a bug solution, and your hack, etc.


=================================================
Version: 2.20
Date:   2010-06-17
=================================================

This is the album module which manages photos and videos.

* Changes *
1. suport windows
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1107&forum=13

The following command can be operated in the windows OS. 
imagemagick ffmpeg lame timidity xpdf java

2. main.in option
(1) added submit_detail_div_onoff
in submit, "show detail" is turned "ON" by default. 
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1113&forum=13

(2) added upload_allowed_mimes
NOT check mime type on uploading
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1114&forum=13

3. Video site
3.1 embed plugin
(1) added the following plugins
in submit, the title and the thumbnail image can be acquired
- ustream.tv
- nicovideo.jp
- ameba.jp

(2) in the following plugins, the title and the thumbnail image can be acquired
- youtube.com
- myspace.com
- dailymotion.com

3.2 Preferences
added "Screen width of video site" and "Screen height"

3.3 thumbnail image
show thumbnail image in the following
- GoogleMap
- whatsnew module
- happy_search module

4. support MS Office 2007
added extensions ( docx xlsx pptx ) whitch you can upload file

5. Bug fix
(1) wrong thumbnail image in happy_search module
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1116&forum=13


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp
(3) Execute "File Valid Check" of "Update" in webphoto's admin control, 
  and cofirm whether or not necessary files are set.


* Special Thanks *
Special thanks to the plugins of the video site. 
- http://www.how-to.tv/


=================================================
Version: 2.13
Date:   2010-05-12
=================================================

This is the album module which manages photos and videos.

* Changes *
1. able to replace the template file of mail by preload 

2. bug fix
(1) Fatal error in bin/retrieve.php
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1090&forum=13

(2) Fatal error in mail_register
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1097&forum=13

(3) NOT show google map in item manager
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1099&forum=13

(4) Wrong total in category
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1101&forum=13

(5) Fatal error in mail manager
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1103&forum=13

(6) NOT continue to select the list or the table
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1105&forum=13

(7) Fatal error in weblinks module

(8) NOT effective of category order and read permission in the sub menu


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp
(3) Execute "File Valid Check" of "Update" in webphoto's admin control, 
  and cofirm whether or not necessary files are set.


=================================================
Version: 2.12
Date:   2010-04-04
=================================================

This is the album module which manages photos and videos.

* Changes *
1. support CentOS5
(1) changed from pdftoppm to pdftops creating the thumbnail of PDF.

1. bug fix
(1) NOT set cateogry read permission
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1079&forum=13

(2) NOT show embed video in other site
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1087&forum=13

(3) Notice when altsys call language file

(4) DB error when submit
item_content : Incorrect string value
item_exif : Data too long

* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp
(3) Execute "File Valid Check" of "Update" in webphoto's admin control, 
  and cofirm whether or not necessary files are set.


=================================================
Version: 2.11
Date:   2010-02-17
=================================================

This is the album module which manages photos and videos.

* Changes *
1. added excution time and memory usage in admin page.

2. bug fix
(1) typo in Japanese language file
(2) wrong "Module Admin" in "group permission"


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp
(3) Execute "File Valid Check" of "Update" in webphoto's admin control, 
  and cofirm whether or not necessary files are set.


=================================================
Version: 2.10
Date:   2010-02-06
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Customize of view 
The admin can customize easily to show or hidden the map, the timeline and etc
the map, the timeline and etc are made in component part.
The admin can set  to show or hidden them with main.ini.

(1) added the map in the tag and user page
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1004&forum=13

2. Scrolling view of the description of photo
The submitter can choice the full text view or the scrolling view.
The scrolling view is effective when the description is long.
The submitter can set in every item.

3. Action when click in detail page
The admin can choice the action when a guest click the photo in detail page
The admin can set every item.

(1) general view
show the uploaded file in new window.
this is default.

(2) photo in new window ( conventional )
show the full-scale photo in new window.
it is valid when the uploaded file is a picture image.

(3) popup ( new )
show the full-scale photo in popup style by lightbox2.
it is valid when the uploaded file is a picture image.
- http://www.lokeshdhakar.com/projects/lightbox2/

(4) PDF ( added in v1.90 )
show the PDF file in new window.
It is valid when the PDF file is generated from the uploaded file.

4. Submit form
(1) added plain (simple textbox) in editor type of description
(2) removed link from thumbnail in preview

5. Admin page
(1) show version of webphoto in "Check Configuration"

6. bug fix
(1) Fatal error in weblink module
(2) wrong link to user group in group permission
(3) wrong URL in the approval waiting email when in bulk submit
(4) the user can not delete the main photo in edit
(5) error in rate

7. Database structure
(1) item table: add field item_description_scroll
item_perm_level


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp
(3) Execute "File Valid Check" of "Update" in webphoto's admin control, 
  and cofirm whether or not necessary files are set.
(4) Execute "Update" in webphoto's admin control
  set the action when click in detail page for the items which already exist


* Notice *
In this version, I changed many files, for "Customize of view".
Although there are no big problem, but I think that there are any small problem. 
Welcome a bug report, a bug solution, and your hack, etc.


* Special Thanks *
Special thanks to the author of lightbox2 
- http://www.lokeshdhakar.com/projects/lightbox2/


=================================================
Version: 2.00
Date:   2009-12-24
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Global Permissions
(1) added a link to the user group management to the group name.
(2) added access and management to the setting item.

2. notification of wating approval
(1) when the user submit the photo which needs approval, notify the wating approval to the admin with PM or email.
(2) when the admin approve it, send the approved email to the submitter.
(3) when the adinm refuse it,  send the refused email to the submitter.

3. separated "File Valid Check" from "Check Configuration"

4. The community feature
This feature is experimental.
It make the area of the member limitation like the community of SNS for each of the modules and the categories.

5. bug fix
(1) Fatal error in "Rebuild Thumbnails" 
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1054

(2) Fatal error in "Notifications"
(3) not show "Max file size" in the submit form.
(4) not upload in the category form, when the image size is big 

6. Database structure
(1) item table: add field item_perm_level
(2) cat  table: add field cat_group_id


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


=================================================
Version: 1.90
Date:   2009-11-29
=================================================

This is the album module which manages photos and videos.

* Changes *
1. added main.ini
In old version, the internal status were set as PHP constant. 
In this version, there are able to be set as variable in each module

It is read in following the order. 
The last set value becomes effective. 
(1) XOOPS_TRUST_PATH /include/main.ini
(2) XOOPS_TRUST_PATH /preload/main.ini (if exists)
(3) XOOPS_ROOT_PATH  /preload/main.ini (if exists)

2. added Pictures, Videos, Musics, Offices in menu

3. in list and detail page, the group icon are displayed in the item which the guest can not see.

4. in detail pgae, the admin can choice the file which is displayed on click the content image
(1) uploaded file
(2) converted PDF

5. in detail pgae, the screen size of the video in Flash Player is automatically adjusted. 

The screen size is decided in following the order. 
(1) value of item table (if set)
(2) value of flashvar table (if set)
(3) the screen size is automatically adjusted to fit value of Flash Player,
if value of file table (flash video) is set. (new)
(4) value of Flash Player

6. in submit page, the spin icon is displayed while uploading the file. 

7. in submit page, the admin can change the default of editor
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1046

8. The admin can change the plugin type.
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1043

9. bug fix
(1) typo Radom -> Random
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=461&forum=11

(2) Fatal error: upload ai type
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1043

10. Database structure
(1) item  table: add field item_detail_onclick item_weight


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


* Notice *
In this version, I changed many files, because added main.ini.
Although there are no big problem, but I think that there are any small problem. 
Welcome a bug report, a bug solution, and your hack, etc.


=================================================
Version: 1.80
Date:   2009-11-01
=================================================

This is the album module which manages photos and videos.

* Changes *
1. media file
(1) added media files
ai, eps, pct, psd, tif, wmf 

(2) processing of image files
convert to JPEG and create thumbnail, when submit image files. 
object: ai, bmp, eps, pct, psd, tif, wmf which are can not be show by WEB browser
requirement: imagemagick is necessary

(3) processing of audeo files
convert to MP3 and play in mediaplayer.swf, when submit image files. 
object: wav, mid
requirement: for wav, lame is necessary
             for mid, lame and timidity are necessary

2. bug fix
(1) Fatal error in imagemanager.php
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1032&forum=13

(2) same image overlaps in timelime
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1033&forum=13

(3) gap at bottom of map
http://linux2.ohwada.net/modules/newbb/viewtopic.php?&topic_id=463&forum=11

(4) player id is not correctly selected 

3. Database structure
(1) mime table: add field mime_kind, mime_option

* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


=================================================
Version: 1.73
Date:   2009-09-25
=================================================

This is the album module which manages photos and videos.

* Changes *
1. New Feature
(1) add "submitter" in RSS
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1020&forum=13

(2) add Google Map in mobile mode
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1026&forum=13

2. Bug Fix
(1) change the method of getting location information from EXIF
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=996&forum=13

(2) Incorrect string when save EXIF in DB
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1014&forum=13

(3) limit the number of markers displayed in map when edit
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1023&forum=13

(4) not show reply comment when guest in XCL

3. Database structure
(1) item table : item_exif TEXT -> BLOB


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


=================================================
Version: 1.72
Date:   2009-08-08
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Charactor Code
(1) The admin can choice mbstring or iconv for character code fucntion

(2) Check in both of mbstring and iconv function
in "Check that 'Charset Convert' is working correctly in your server"
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=989&forum=13

(3) Show MySQL Config in admin cp
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=1014&post_id=3766#forumpost3766

2. Changed the background of the white map marker into transparent
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=9&topic_id=988

3. Bug fix
(1) show "Submitter List" when click "My Photos"
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1001&forum=13

(2) not show the description in edit form 
when editor field of item table is empty


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


* Notice for usage *
1. Charactor Code Function
In generaly webphoto uses "iconv" if iconv exists.
if you want to use "mbstring" when both of mbstring and iconv exist
you can choice with preload file.

rename preload file.
XOOPS_TRUST_PATH/modules/webphoto/preload/_multibyte.php (with undebar)
 -> multibyte.php (without undebar)


=================================================
Version: 1.71
Date:   2009-06-01
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Category
(1) bug fix: the user can select the category which he has no permission to post
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=983

(2) added to check the category's permission in submit process

(3) bug fix: not show cat_id in menu

2. Bug fix
(1) not show tags in edit form
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=986&forum=13


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


* Special Thanks *
Referred the following about option tag and disabled
Select, Option, Disabled And The JavaScript Solution
- http://www.lattimore.id.au/2005/07/01/select-option-disabled-and-the-javascript-solution/
Special thanks to author.


=================================================
Version: 1.70
Date:   2009-05-23
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Category
(1) Show category's description in category list
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=972

(2) Unrelated category's post permission from the parent category's
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=983

(3) Added "Show photos of subcategoies" in "Preference"
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=983

(4) Show parent category and chiledren categories at the form in "category management"

(5) Show error for zombie, photo which belongs to no category, in "item management"
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=859

2. Changed photo's description from full text to summary in photo list


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


=================================================
Version: 1.60
Date:   2009-05-17
Author: Kenichi OHWADA
URL:    http://linux2.ohwada.net/
Email:  webmaster@ohwada.net
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Added uploading plural photos
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=960

2. Added "Select Editor" in "Add Photo from File" and "Batch Register"
3. Added Submitter in "Photo Upload" form of "Item Management"

4. French for v1.51

5. Bug fix
(1) typo
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=980

(2) 403 error in bin/retrieve.php
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=981

(3) Parse error in "Vote this"
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=981


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


=================================================
Version: 1.51
Date:   2009-04-27
=================================================

This is the album module which manages photos and videos.

* Changes *
1. bug fix
(1) Warning: chmod()
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=5&topic_id=959

(2) Fatal error in "category management"
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=961


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


=================================================
Version: 1.50
Date:   2009-04-19
=================================================

This is the album module which manages photos and videos.

* Changes *
1. customize of the submit form
(1) change the form to template
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=924

(2) no show of the plugin form
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=938

(3) no show of eternal url
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=951

2. change the bread crumb to template
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=939

3. in "Check Configuration" added to check there are necessary files
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=939

4. bug fix
(1) fatal error in weblinks
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=5&topic_id=952

(2) not show the photo description
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=955

(3) fatal error in "item manager"


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


=================================================
Version: 1.40
Date:   2009-04-10
=================================================

This is the album module which manages photos and videos.

* Changes *
1. timeline
(1) add timeline block
(2) add timeline in date list
(3) add timeline in menu of help
(4) get image by network and create small image, when external media.

2. map
(1) add map in place list

3. preferrence
add the following
(1) number of photos in timeline
(2) number of photos in map
(3) number of tags in tag cloud


4. bug fix
(1) packaging mistake
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=932&forum=13
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=948&forum=13

(2) typo in English
http://linux2.ohwada.net/modules/newbb/viewtopic.php?forum=11&topic_id=449

(3) NOT clear "file id" in item table, when delete image.


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


=================================================
Version: 1.30
Date:   2009-03-20
=================================================

This is the album module which manages photos and videos.

* Changes *
1. the user can insert the non-image file like pdf in content by imagemanager
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=936

2. rotate the image in preview
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=936

3. support timeline
(1) show timeline using timeline module
(2) add the small image for timeline

4. bug fix
(1) cannot import from myalbum-P 
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=932

(2) flash player becomes default in the user edit
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=936


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp
(3) and execute "Update" in webphoto's admin cp
  for creating the small images.


* Special Thanks *
Special thanks to MIT Simile Project.
- http://code.google.com/p/simile-widgets/wiki/Timeline


=================================================
Version: 1.21
Date:   2009-03-07
Author: Kenichi OHWADA
URL:    http://linux2.ohwada.net/
Email:  webmaster@ohwada.net
=================================================

This is the album module which manages photos and videos.

* Changes *
1. highlight the search keyword in detail page
2. valid "page width" and "page height" in showing flash
3. support Georss in RSS output
4. added RSS Manager
5. added French lang pack

6. Bug fix
(1) Fatal error in help
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=921

(2) Fatal error in imagemanager
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=923

(3) not valid "Use pathinfo" in RSS output
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=927


=================================================
Version: 1.20
Date:   2009-02-01
Author: Kenichi OHWADA
URL:    http://linux2.ohwada.net/
Email:  webmaster@ohwada.net
=================================================

This is the album module which manages photos and videos.

* Changes *
1. added GoogleMap in category
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=912

2. added GoogleMap in block
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=913&forum=13

3. Added Webphoto to the album select of Weblinks.
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=5&topic_id=911

4. Edit form
(1) you can edit "content"
(2) show the following files
- Flash video (flv)
- Docomo video (3gp)
- PDF (pdf)
- Flash (swf)

5. Word file (doc)
(1) require jodconverter, xpdf, imagemagick
- http://www.artofsolving.com/opensource/jodconverter
- http://www.foolabs.com/xpdf/
- http://www.imagemagick.org/script/index.php
(2) create PDF file from Word
(3) create the thumbnail from Word
(4) extract the text content from Word

6. Excel file (xls)
(1) require jodconverter, xpdf, imagemagick
(2) create PDF file from Excel
(3) create the thumbnail from Excel
(4) extract the text content from Excel

7. PowerPoint file (ppt)
(1) require jodconverter, xpdf, imagemagick
(2) create PDF file from PowerPoint
(2) create Flash file (swf) from PowerPoint
(3) create the thumbnail from PowerPoint
(4) extract the text content from PowerPoint


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
  Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp.


* Notice for usage *
1. jodconverter
(1) rename preload file.
XOOPS_TRUST_PATH/modules/webphoto/preload/_jodconverter.php (with undebar)
 -> jodconverter.php (without undebar)

(2) set path according to your enviroment
-----
define("_C_WEBPHOTO_JAVA_PATH", "/usr/bin/" ) ;
define("_C_WEBPHOTO_JODCONVERTER_JAR", "/usr/local/java/jodconverter-2.2.1/lib/jodconverter-cli-2.2.1.jar" ) ;
-----


* Notice *
In this version, I changed program structure substantially.
Although there are no big problem, but I think that there are any small problem. 
Even if some problems come out, only those who can do somehow personally need to use. 
Welcome a bug report, a bug solution, and your hack, etc.


=================================================
Version: 1.10
Date:   2009-01-25
=================================================

This is the album module which manages photos and videos.

* Changes *
1. View
(1) limit 'exif' by 500 bytes.

2. Search
(1) highlight keyword in the description when module search.
(2) it is same when search in happy_search.

3. Thumbnail with Icon
(1) add the icon which shows file kind in the thumbnail,
when create the thumbnail from the media as the video.
(2) enable this when the admin select "ImageMagick" in "Package treating images"

4. plugin
add plugins supported file kind
- audio
- html
- pdf
- txt
- video

5. Text file (txt)
(1) extract the text content from the text file, 
and show it in 'text content' field.
(2) limit 'text content' by 500 bytes.
(3) search in 'text content'

6. PDF file (pdf)
(1) require 'xpdf'
http://www.foolabs.com/xpdf/
(2) create the thumbnail from PDF file
(3) extract the text content from the PDF file, 
and show it in 'text content' field.

7. Bug fix
(1) fetal error in edit photo
http://linux.ohwada.jp/modules/newbb/viewtopic.php?viewmode=flat&topic_id=909&forum=13

(2) fatal error in category management
http://linux.ohwada.jp/modules/newbb/viewtopic.php?viewmode=flat&topic_id=910&forum=13

8. Database structure
add field of table
(1) item table : item_content etc

9. Program structure
(1) added 'edit' directory under 'class' directory.


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
  Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp.
  the fields are added to the table automatically. 


* Notice for usage *
1. Text file 
the character encoding is detected automatically.
but it does not sometimes work out.
you should enter the text in 'text content', if character garbage

2. PDF file
In xpdf, all texts (character string) are not sometimes extracted.
I do not know the difference when do or not extracte .
please teach me.


* Notice *
In this version, I changed program structure substantially.
Although there are no big problem, but I think that there are any small problem. 
Even if some problems come out, only those who can do somehow personally need to use. 
Welcome a bug report, a bug solution, and your hack, etc.


=================================================
Version: 1.00
Date:   2009-01-04
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Support FCKeditor
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=899&forum=13

(1) User can choice the editor each item.

2. Bug fix
(1) fatal error in mobile
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=902&forum=13

(2) fatal error in help
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=904&forum=13

(3) fatal error in block
http://linux.ohwada.jp/modules/newbb/viewtopic.php?viewmode=flat&topic_id=905&forum=13

3. Database structure
3.1 add field of table
(1) item table : item_editor etc


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
  Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp.
  the fields are added to the table automatically. 


* Notice for usage *
1. FCKeditor
(1) Set XOOPS_ROOT_PATH /common/fckeditor 
http://xoops.peak.ne.jp/md/mydownloads/singlefile.php?lid=93

(2) check "Use HTML" in "Global Permissions"

(3) It is difficult slightly to change a once chosen editor.
You MUST rewrite "editor" column in "item table management".
Moreover, you change "description" "HTML tags" "smiley icon" "XOOPS codes" "image" "linebreak" columns to fit the editor.


* Notice *
Although there are no big problem, but I think that there are any small problem. 
Even if some problems come out, only those who can do somehow personally need to use. 
Welcome a bug report, a bug solution, and your hack, etc.


=================================================
Version: 0.90
Date:   2008-12-20
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Read Permission
1.1 Added read permission each category
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=893&forum=13

(1) accedes to the permission of the parent category,
when adding a new category. 

(2) changes the permission of the child categories,
when changing a category

1.2 Added read permission each item

2. Bug Fix
(1) In case of the module dupilication, the category list show the other module's data.
(2) Not set permission to download, when submitting in imagemanager
(3) Not show google marker in the neighborhood in submit form


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
  Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp.


* Notice for usage *
1. Read Permission  of category
The permission of the category acts independently with the parent category.
In general make the permission of the category the same as the parent category.

For example, 
when there is category B under category A,
you set the permisson of category A that the guest can not read 
and the permisson of category B that the guest can read,
the guest can read the items which belongs to category B.

2. Server load
The server load will be increased much,
when you use the read permisson of the category and the read permisson of the item.

I recommend to divide into more than one module
and to set permisson for each of the modules,
when there are many numbers of the categories and numbers of the items

The server load will be increased a bit ,
when you use the post premisson of the category and The download permission of the item.


* Notice *
Although there are no big problem, but I think that there are any small problem. 
Even if some problems come out, only those who can do somehow personally need to use. 
Welcome a bug report, a bug solution, and your hack, etc.


=================================================
Version: 0.81
Date:   2008-12-10
=================================================

This is the album module which manages photos and videos.

* Changes *
1. rating
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=868&forum=13
the admin can customize the rating from 1 to 10 by the sentences such as "Excellent"

2. bug fix
(1) fatal error in bin/retrieve.php
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=882&forum=13

(2) not show QR code
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=882&forum=13

(3) problem of module clone
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=887&forum=13

(4) fatal error in i.php
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=889&forum=13

(5) fatal error in RSS
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=890&forum=13

(6) 404 error of magplus.cur
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=890&forum=13


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
  Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp
  the fields are added to the table automatically. 


=================================================
Version: 0.80
Date:   2008-11-30
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Creation of Icon
In old version, the icon was copied each item.
In this version, the icon is used just as in images/exts directory. 

2. File Management System
changed to use the file location in relative path from full path .
http://linux.ohwada.jp/modules/newbb/viewtopic.php?vtopic_id=869&forum=13

3. Publish Time and Expire Time
3.1 the admin can set publish time and expire time each item
3.2 Automatic Publish and Expire
(1) When becoming at the publish time, the item becomes online mode automatically.
(2) When becoming at the expire time, the item becomes offline mode automatically.
3.3 added the offline list and the expired list in the item management.

4. Added Block
(1) Category List Block
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=428&forum=11

(2) Tag Cloud Block
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=835&forum=13

5. Bug Fix
(1) Wrong popup image when external image.
(2) Not show when external swf.
(3) Wrong time when when $the admin's time zone is different from the server.

6. Database structure
6.1 add field of table
(1) item table : item_icon_width


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
  Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp
  the fields are added to the table automatically. 


* Notice *
Although there are no big problem, but I think that there are any small problem. 
Even if some problems come out, only those who can do somehow personally need to use. 
Welcome a bug report, a bug solution, and your hack, etc.


* Special Thanks *
Referred webshow about Publish Time and Expire Time.
- http://wikiwebshow.com/
Special thanks to authors.


=================================================
Version: 0.70
Date:   2008-11-20
=================================================

This is the album module which manages photos and videos.

* Changes *

1. added codebox
1.1 display items
(1) download media
(2) download flash video
(3) thumbnail image URL
(4) middle image URL
(5) page URL
(6) website URL
(7) embed with object form
(8) embed with JavaScript

1.2 download permission
the user can set download permission in each item
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=418&forum=11

2. video site
2.1 submit form
(1) added page width and height
(2) added embed

2.2 plugin
(1) added pandora.tv
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=873&forum=13

(2) added general
the admin can submit HTML embed

(3) added webphoto 
the user quotes the video which is posted in other webphoto.

(4) added width and height to youtube

3. navigation in detail page
changed figure to thumbnail in list

4. Bug fix
(1) fatal error when submit external URL
(2) fatal error when submit embed plugin
(3) cannot create thumb when submit playlist:

5. Database structure
5.1 add field of table
(1) item table : item_codeinfo


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
  Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp
  the fields are added to the table automatically. 


* Special Thanks *
Referred webshow about codebox.
- http://wikiwebshow.com/
Special thanks to authors.


=================================================
Version: 0.60
Date:   2008-11-10
=================================================

This is the album module which manages photos and videos.

* Changes *

1. Image Upload
1.1 Tumbnamil Image
(1) added to shrink image when upload
(2) added to delete image

1.2 Middle Image
(1) addeed to upload image
(2) added to shrink image when upload
(3) added to delete image
(4) added to exteranal URL

1.3 Category Image
(1) addeed to upload image
(2) added to shrink image when upload
(3) added to select image from uploaded

1.4 GoogleMap Icon Image
(1) added to shrink image when upload

1.5 Player Logo Image
(1) added to shrink image when upload

1.6 added JPEG Quality
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=869&forum=13

2. Support JPEx
show the top menu twice in admin's page.
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=872&forum=13

3. Bug fix
(1) Fatal error in preview
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=872&forum=13

(2) Fatal error in "Mail Retrieve"
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=872&forum=13

(3) not set Google Map form Exif data

(4) not create video thumb in "Add Photo from File"

(5) Fatal error to remove image in "Rebuild Thumbnails"

(6) Fatal error to remove image in "Item Table Management"

4. Database structure
4.1 add field of table
(1) item table: item_external_middle
(2) cat  table: cat_img_name

4.2 change item of config table (preferrence)
look "Notice for usage"


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
  Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


* Notice for usage *
1. The directoris for work
Webphoto remove tmpdir in "preferrence",
add workdir instead.

In old version, the admin can set the directory for tmploraly (tmpdir)
In this version, the admin can set the root directory (workdir) ,
Webphoto fix the directories belong the root directory.

Dirctory same as old version
- tmp (tmploaly files)

Dirctory for files which have separated form tmp.
- mail (mails)
- log  (logs)

[Caution]
If you changed the value of tmpdir from the default value,
please move files manually to match with new dirctory.


* Notice *
Although there are no big problem, but I think that there are any small problem. 
Even if some problems come out, only those who can do somehow personally need to use. 
Welcome a bug report, a bug solution, and your hack, etc.


=================================================
Version: 0.50
Date:   2008-10-30
=================================================

This is the album module which manages photos and videos.

* Changes *

1. External Media
In old version, the media file must be uploaded.
In this version, the user can set URL of the media file.

2. Video sites
(1) support video sites with the plug-in.
(2) The following sites are prepared.
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

3. Display Type
In old version, the display type of media file extension is fixed
In this version, the admin can select the display type for media file.

3.1 action when click thumbnail
(1) jump the detail Page
(2) open the media file
(3) popup the large image

3.2 display in detail page
(1) show thumbnail, open the media file when click thumb
(1) show thumbnail, show the large image when click thumb
(3) show plugin for video site
(4) play in swfobject.swf
(5) play in mediaplayer.swf
(6) play in imagerotator.swf

3.3 flash player variables
look 9

4. popbox.js
http://www.c6software.com/Products/PopBox/Default.aspx

in image type (jpg,gif,png), show the large image when click thumb
default (since v0.10):

5. swfobject.swf
http://blog.deconcept.com/swfobject/

in swf type, play in this player.
default (in this version)

6. mediaplayer.swf
http://www.jeroenwijering.com/?item=JW_FLV_Media_Player

in following type, play in this player.
(1) in flv type, or converted from posted video
    default (since v0.2)
(2) in mp3 type, default (since v0.42)
(3) in image type (jpg,gif,png), the admin can set

7. add imagerotator.swf 
http://www.jeroenwijering.com/?item=JW_Image_Rotator

use for playlist

8. Playlist
http://code.jeroenwijering.com/trac/wiki/Playlists3

8.1 in mediaplayer.swf and imagerotator.swf, the admin can play the playlist 

8.2 There are two ways in the setting of the playlist.
(1) specify the URL of the playlist.
(2) specify the directory of media files in own site.
    and webphoto generates the playlist from the media files automaticllay.

(3) As the sample, prepare the following media files.
(3-1) photo (jpg) medias/sample_photo/
(3-2) music (mp3) medias/sample_music/

9. Flash player variables 
http://code.jeroenwijering.com/trac/wiki/Flashvars3

(1) the admin can set variables for every media file 
in swfobject.swf, mediaplayer.swf, imagerotator.swf
(2) Only the size and the color are valid in swfobject.swf
(3) the admin can set more than one pattern about the size and the color of the flash player in "player management".
the admin can select one of the patterns for every media file.
(4) the admin can set other variables for for every media file.
(5) the default values are used, when not set variables.

10. Added color_picker.js
http://www.softcomplex.com/products/tigra_color_picker/

use to set color in flash player.

11. Callback of flash player
logging when play in mediaplayer.swf
the admin can enable in preferrence

12. Bug fix
(1) notice in "user info"
(2) SQL syntax error in "rate"
(3) not copy thumbnail in "import from myalbum"
(4) notice when item has only title in "Rebuild Thumbnails"

13. Database structure
13.1 add tables
(1) player   table: store part of flash player variables
(2) flashvar table: store all of flash player variables

13.2 add field of table
(3) item table: add 23 fields, item_player_id etc

13.3 change item of config table (preferrence)
look "Notice for usage"


* Update *
(1) Recommend to backup the database before update.
(2) When you unzip the zip file, there are two directories html and xoops_trust_path.
  Please copy and overwrite in the directory which XOOPS correspond
(3) Execute the module update in the admin cp
(4) Please execute "Update" in webphoto's admin cp.
  It set values in displaytype, onclick, duration which added in item table.


* Notice for usage *
1. The directoris for upload
Webphoto remove photospath, thumbspath, giconspath in "preferrence",
add uploadspath instead.

In old version, the admin can change the directory for upload in photos, thumbs, gicons.
In this version, the admin can change the root directory (uploadspath) only,
Webphoto fix the directories belong the root directory.

Dirctory same as old version
- photos (photos and vidos)
- thumbs (thumbnails)
- gicons (GoogleMaps icons)

Dirctory for files which have separated form photos.
Webphoto use the old files in photos just as it is.
and create the new files in following dirctories.
- middles (middle size thumbnails)
- flashs  (flash videos created automaticaly)
- qrs     (QR codes)

Dirctory added in this version
- playlists (playlist cache)
- logos     (player logo images)

[Caution]
If you changed the value of photos, thumbs, gicons from the default value,
please move files manually to match with new dirctory.


* Notice *
Although there are no big problem, but I think that there are any small problem. 
Even if some problems come out, only those who can do somehow personally need to use. 
Welcome a bug report, a bug solution, and your hack, etc.


* Special Thanks *
Referred webshow about Youtube plugin and Flash player variables.
- http://wikiwebshow.com/
Special thanks to authors.


=================================================
Version: 0.42
Date:   2008-10-13
=================================================

This is the album module which manages photos and videos.

* Changes *
1. play MP3 in Flash player
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=422&forum=11

2. add Portugues of Brazil lang pack
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=429&forum=11

1. Bug fix
(1) not show orignal file in tempalate manager of altsys module
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=858&forum=13

(2) save 12:52 at photo datetime when 12:00:52
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=860&forum=13

(3) error in mysql 5

(4) fatal error when apporve the post

(5) not set xoops_group

* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


=================================================
Version: 0.41
Date:   2008-09-13
=================================================

This is the album module which manages photos and videos.

* Changes *
1. Bug fix
(1) date in block
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=854

(2) cannot install
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=855

(3) not use image manager if there are no photo
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=857

(4) fatal error in category manager

(5) not select category when open submit form from category


* Update *
(1) When you unzip the zip file, there are two directories html and xoops_trust_path.
Please copy and overwrite in the directory which XOOPS correspond
(2) Execute the module update in the admin cp


=================================================
Version: 0.40
Date:   2008-09-01
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

3. Supported d3forum comment integration
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=850&forum=13

4. Bug fix
(1) cannot preview description in submit form
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=841

(2) fatal error in "Rebuild Thumbnails"
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=13&topic_id=843

(3) fatal error in "Edit Photo"
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=844&forum=13

(4) not show alt of icon image in "Edit Photo"
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=851&forum=13

(5) fatal error in "Image Manager"

(6) conflict with other D3 module

5. Database structure
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

3. d3forum comment integration
3.1 Preferences
set the following at "Preferences" in webphoto module
"Comment-integration: dirname of d3forum"
"Comment-integration: forum ID"
"View of Comment-integration"

3.2 template
change template file
  XOOPS_TRUST_PATH/modules/webphoto/templates/main_photo.html
remove asterrisk (*)
-----
<{* d3forum_comment dirname=$cfg_comment_dirname forum_id=$cfg_comment_forum_id class="WebphotoD3commentContent" mytrustdirname="webphoto" id=$photo.photo_id subject=$photo.title_s subject_escaped=1 view=$cfg_comment_view posts_num=10 *}>

 |

<{d3forum_comment dirname=$cfg_comment_dirname forum_id=$cfg_comment_forum_id class="WebphotoD3commentContent" mytrustdirname="webphoto" id=$photo.photo_id subject=$photo.title_s subject_escaped=1 view=$cfg_comment_view posts_num=10}>
-----

for xoops 2.0.18
template file is
  XOOPS_ROOT_PATH/modules/webphoto/templates/webphoto_main_photo.html

3.2 d3forum module
fill the following at "Format for comment-integration" in d3forum module
-----
webphoto::WebphotoD3commentContent::webphoto
-----
First "webphoto" is the directory name in XOOPS_ROOT_PATH 
(you can change with the module duplication)
Last "webphoto" is the directory name in XOOPS_TRUST_PATH 
(you can NOT change)


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

