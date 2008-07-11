# $Id: mysql.sql,v 1.5 2008/07/11 20:28:02 ohwada Exp $

# =========================================================
# webphoto module
# 2008-04-02 K.OHWADA
# =========================================================

# change log
# 2008-07-01 K.OHWADA
# added mime_ffmpeg

#
# Table structure for table `photo`
#

CREATE TABLE photo (
  photo_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  photo_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
  photo_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
  photo_cat_id  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  photo_gicon_id INT(11) UNSIGNED NOT NULL DEFAULT '0',
  photo_uid     INT(11) UNSIGNED NOT NULL DEFAULT '0',
  photo_datetime  DATETIME NOT NULL,
  photo_title VARCHAR(255) NOT NULL DEFAULT '',
  photo_place     VARCHAR(255) NOT NULL DEFAULT '',
  photo_equipment VARCHAR(255) NOT NULL DEFAULT '',
  photo_file_url     VARCHAR(255) NOT NULL DEFAULT '',
  photo_file_path    VARCHAR(255) NOT NULL DEFAULT '',
  photo_file_name    VARCHAR(255) NOT NULL DEFAULT '',
  photo_file_ext     VARCHAR(10)  NOT NULL DEFAULT '',
  photo_file_mime    VARCHAR(255) NOT NULL DEFAULT '',
  photo_file_medium  VARCHAR(255) NOT NULL DEFAULT '',
  photo_file_size    INT(5) NOT NULL DEFAULT '0',
  photo_cont_url     VARCHAR(255) NOT NULL DEFAULT '',
  photo_cont_path    VARCHAR(255) NOT NULL DEFAULT '',
  photo_cont_name    VARCHAR(255) NOT NULL DEFAULT '',
  photo_cont_ext     VARCHAR(10) NOT NULL DEFAULT '',
  photo_cont_mime    VARCHAR(255) NOT NULL DEFAULT '',
  photo_cont_medium  VARCHAR(255) NOT NULL DEFAULT '',
  photo_cont_size     INT(5) NOT NULL DEFAULT '0',
  photo_cont_width    INT(5) NOT NULL DEFAULT '0',
  photo_cont_height   INT(5) NOT NULL DEFAULT '0',
  photo_cont_duration INT(5) NOT NULL DEFAULT '0',
  photo_cont_exif     TEXT NOT NULL,
  photo_middle_width  INT(5) NOT NULL DEFAULT '0',
  photo_middle_height INT(5) NOT NULL DEFAULT '0',
  photo_thumb_url     VARCHAR(255) NOT NULL DEFAULT '',
  photo_thumb_path    VARCHAR(255) NOT NULL DEFAULT '',
  photo_thumb_name    VARCHAR(255) NOT NULL DEFAULT '',
  photo_thumb_ext     VARCHAR(10) NOT NULL DEFAULT '',
  photo_thumb_mime    VARCHAR(255) NOT NULL DEFAULT '',
  photo_thumb_medium  VARCHAR(255) NOT NULL DEFAULT '',
  photo_thumb_size    INT(5) NOT NULL DEFAULT '0',
  photo_thumb_width   INT(5) NOT NULL DEFAULT '0',
  photo_thumb_height  INT(5) NOT NULL DEFAULT '0',
  photo_gmap_latitude  DOUBLE(10,8) NOT NULL DEFAULT '0',
  photo_gmap_longitude DOUBLE(11,8) NOT NULL DEFAULT '0',
  photo_gmap_zoom      TINYINT(2) NOT NULL DEFAULT '0',
  photo_gmap_type      TINYINT(2) NOT NULL DEFAULT '0',
  photo_status TINYINT(2) NOT NULL DEFAULT '0',
  photo_hits   INT(11) UNSIGNED NOT NULL DEFAULT '0',
  photo_rating DOUBLE(6,4) NOT NULL DEFAULT '0.0000',
  photo_votes    INT(11) UNSIGNED NOT NULL DEFAULT '0',
  photo_comments INT(11) UNSIGNED NOT NULL DEFAULT '0',
  photo_perm_read VARCHAR(255) NOT NULL DEFAULT '',
  photo_text1  VARCHAR(255) NOT NULL DEFAULT '',
  photo_text2  VARCHAR(255) NOT NULL DEFAULT '',
  photo_text3  VARCHAR(255) NOT NULL DEFAULT '',
  photo_text4  VARCHAR(255) NOT NULL DEFAULT '',
  photo_text5  VARCHAR(255) NOT NULL DEFAULT '',
  photo_text6  VARCHAR(255) NOT NULL DEFAULT '',
  photo_text7  VARCHAR(255) NOT NULL DEFAULT '',
  photo_text8  VARCHAR(255) NOT NULL DEFAULT '',
  photo_text9  VARCHAR(255) NOT NULL DEFAULT '',
  photo_text10 VARCHAR(255) NOT NULL DEFAULT '',
  photo_description TEXT NOT NULL,
  photo_search TEXT NOT NULL,
  PRIMARY KEY (photo_id),
  KEY (photo_time_update),
  KEY (photo_cat_id),
  KEY (photo_gicon_id),
  KEY (photo_uid),
  KEY (photo_status),
  KEY (photo_hits),
  KEY (photo_rating),
  KEY (photo_datetime),
  KEY (photo_title(40)),
  KEY (photo_place(40)),
  KEY (photo_equipment(40)),
  KEY (photo_search(40)),
  KEY (photo_gmap_latitude, photo_gmap_longitude, photo_gmap_zoom)
) TYPE=MyISAM;

#
# Table structure for table `cat`
#

CREATE TABLE cat (
  cat_id INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  cat_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
  cat_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
  cat_gicon_id INT(5) UNSIGNED NOT NULL DEFAULT '0',
  cat_forum_id INT(5) UNSIGNED NOT NULL DEFAULT '0',
  cat_pid      INT(5) UNSIGNED NOT NULL DEFAULT '0',
  cat_title    VARCHAR(255) NOT NULL DEFAULT '',
  cat_img_path VARCHAR(255) NOT NULL DEFAULT '',
  cat_weight INT(5) UNSIGNED NOT NULL DEFAULT 0,
  cat_depth  INT(5) UNSIGNED NOT NULL DEFAULT 0,
  cat_allowed_ext VARCHAR(255) NOT NULL DEFAULT 'jpg|jpeg|gif|png',
  cat_img_mode    TINYINT(2) NOT NULL DEFAULT '0',
  cat_orig_width  INT(10) UNSIGNED NOT NULL DEFAULT '0',
  cat_orig_height INT(10) UNSIGNED NOT NULL DEFAULT '0',
  cat_main_width  INT(10) UNSIGNED NOT NULL DEFAULT '0',
  cat_main_height INT(10) UNSIGNED NOT NULL DEFAULT '0',
  cat_sub_width   INT(10) UNSIGNED NOT NULL DEFAULT '0',
  cat_sub_height  INT(10) UNSIGNED NOT NULL DEFAULT '0',
  cat_item_type   TINYINT(2) NOT NULL DEFAULT '0',
  cat_gmap_mode      TINYINT(2) NOT NULL DEFAULT '0',
  cat_gmap_latitude  DOUBLE(10,8) NOT NULL DEFAULT '0',
  cat_gmap_longitude DOUBLE(11,8) NOT NULL DEFAULT '0',
  cat_gmap_zoom      TINYINT(2) NOT NULL DEFAULT '0',
  cat_gmap_type      TINYINT(2) NOT NULL DEFAULT '0',
  cat_perm_read VARCHAR(255) NOT NULL DEFAULT '',
  cat_perm_post VARCHAR(255) NOT NULL DEFAULT '',
  cat_text1  VARCHAR(255) NOT NULL DEFAULT '',
  cat_text2  VARCHAR(255) NOT NULL DEFAULT '',
  cat_text3  VARCHAR(255) NOT NULL DEFAULT '',
  cat_text4  VARCHAR(255) NOT NULL DEFAULT '',
  cat_text5  VARCHAR(255) NOT NULL DEFAULT '', 
  cat_description TEXT,
  PRIMARY KEY (cat_id),
  KEY (cat_pid),
  KEY (cat_gicon_id),
  KEY (cat_forum_id),
  KEY (cat_weight),
  KEY (cat_depth),
  KEY (cat_img_mode),
  KEY (cat_item_type),
  KEY (cat_title(40)),
  KEY (cat_gmap_latitude, cat_gmap_longitude, cat_gmap_zoom)
) TYPE=MyISAM;

#
# Table structure for table `vote`
#

CREATE TABLE vote (
  vote_id  INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  vote_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
  vote_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
  vote_photo_id INT(11) UNSIGNED NOT NULL DEFAULT '0',
  vote_uid      INT(11) UNSIGNED NOT NULL DEFAULT '0',
  vote_rating TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  vote_hostname VARCHAR(60) NOT NULL DEFAULT '',
  PRIMARY KEY (vote_id),
  KEY (vote_photo_id),
  KEY (vote_uid),
  KEY (vote_hostname)
) TYPE=MyISAM;

#
# Table structure for table `gicon`
#

CREATE TABLE gicon (
  gicon_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  gicon_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
  gicon_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
  gicon_title VARCHAR(255) NOT NULL default '',
  gicon_image_path  VARCHAR(255) NOT NULL default '',
  gicon_image_name  VARCHAR(255) NOT NULL default '',
  gicon_image_ext   VARCHAR(10)  NOT NULL default '',
  gicon_shadow_path VARCHAR(255) NOT NULL default '',
  gicon_shadow_name VARCHAR(255) NOT NULL default '',
  gicon_shadow_ext  VARCHAR(10)  NOT NULL default '',
  gicon_image_width   INT(4) NOT NULL default '0',
  gicon_image_height  INT(4) NOT NULL default '0',
  gicon_shadow_width  INT(4) NOT NULL default '0',
  gicon_shadow_height INT(4) NOT NULL default '0',
  gicon_anchor_x INT(4) NOT NULL default '0',
  gicon_anchor_y INT(4) NOT NULL default '0',
  gicon_info_x   INT(4) NOT NULL default '0',
  gicon_info_y   INT(4) NOT NULL default '0',
  PRIMARY KEY (gicon_id)
) TYPE=MyISAM;

#
# Table structure for table `mime`
#

CREATE TABLE mime (
  mime_id int(11) NOT NULL auto_increment,
  mime_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
  mime_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
  mime_ext    VARCHAR(10) NOT NULL default '',
  mime_medium VARCHAR(255) NOT NULL default '',
  mime_type   VARCHAR(255) NOT NULL default '',
  mime_name   VARCHAR(255) NOT NULL default '',
  mime_perms  VARCHAR(255) NOT NULL default '',
  mime_ffmpeg VARCHAR(255) NOT NULL default '',
  PRIMARY KEY mime_id (mime_id),
  KEY (mime_ext)
) TYPE=MyISAM;

#
# Table structure for table `tag`
#

CREATE TABLE tag (
 tag_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
 tag_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
 tag_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
 tag_name VARCHAR(255) NOT NULL DEFAULT '',
 PRIMARY KEY (tag_id),
 KEY (tag_name(40))
) TYPE=MyISAM;

#
# Table structure for table `p2t`
#

CREATE TABLE p2t (
 p2t_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
 p2t_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
 p2t_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
 p2t_photo_id INT(10) UNSIGNED DEFAULT NULL,
 p2t_tag_id   INT(10) UNSIGNED DEFAULT NULL,
 p2t_uid      INT(10) UNSIGNED DEFAULT NULL,
 PRIMARY KEY (p2t_id),
 KEY (p2t_photo_id),
 KEY (p2t_tag_id),
 KEY (p2t_uid)
) TYPE=MyISAM;

#
# Table structure for table `syno`
#

CREATE TABLE syno (
 syno_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
 syno_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
 syno_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
 syno_weight INT(5) UNSIGNED NOT NULL DEFAULT 0,
 syno_key   VARCHAR(255) NOT NULL default '',
 syno_value VARCHAR(255) NOT NULL default '',
 PRIMARY KEY (syno_id)
) TYPE=MyISAM;

#
# gicon table
#
INSERT INTO gicon VALUES (1, 0, 0, 'aqua 18x28', '/modules/{DIRNAME}/images/markers/icon_1828_aqua.png', '', 'png', '', '', '', 18, 28, 0, 0, 9, 28, 9, 3);
INSERT INTO gicon VALUES (2, 0, 0, 'blue 18x28', '/modules/{DIRNAME}/images/markers/icon_1828_blue.png', '', 'png', '', '', '', 18, 28, 0, 0, 9, 28, 9, 3);
INSERT INTO gicon VALUES (3, 0, 0, 'gray 18x28', '/modules/{DIRNAME}/images/markers/icon_1828_gray.png', '', 'png', '', '', '', 18, 28, 0, 0, 9, 28, 9, 3);
INSERT INTO gicon VALUES (4, 0, 0, 'green 18x28', '/modules/{DIRNAME}/images/markers/icon_1828_green.png', '', 'png', '', '', '', 18, 28, 0, 0, 9, 28, 9, 3);
INSERT INTO gicon VALUES (5, 0, 0, 'maroon 18x28', '/modules/{DIRNAME}/images/markers/icon_1828_maroon.png', '', 'png', '', '', '', 18, 28, 0, 0, 9, 28, 9, 3);
INSERT INTO gicon VALUES (6, 0, 0, 'pink 18x28', '/modules/{DIRNAME}/images/markers/icon_1828_pink.png', '', 'png', '', '', '', 18, 28, 0, 0, 9, 28, 9, 3);
INSERT INTO gicon VALUES (7, 0, 0, 'purple 18x28', '/modules/{DIRNAME}/images/markers/icon_1828_purple.png', '', 'png', '', '', '', 18, 28, 0, 0, 9, 28, 9, 3);
INSERT INTO gicon VALUES (8, 0, 0, 'red 18x28', '/modules/{DIRNAME}/images/markers/icon_1828_red.png', '', 'png', '', '', '', 18, 28, 0, 0, 9, 28, 9, 3);
INSERT INTO gicon VALUES (9, 0, 0, 'white 18x28', '/modules/{DIRNAME}/images/markers/icon_1828_white.png', '', 'png', '', '', '', 18, 28, 0, 0, 9, 28, 9, 3);
INSERT INTO gicon VALUES (10, 0, 0, 'yellow 18x28', '/modules/{DIRNAME}/images/markers/icon_1828_yellow.png', '', 'png', '', '', '', 18, 28, 0, 0, 9, 28, 9, 3);

#
# MIME Media Types
# http://www.iana.org/assignments/media-types/index.html
# http://technet.microsoft.com/en-us/library/bb742440.aspx
#
# MS IE 6 use ' image/x-png image/pjpeg '
#

INSERT INTO mime VALUES (1, 0, 0, '3g2', 'video', 'video/3gpp2', 'Third Generation Partnership Project 2 File Format', '&1&', '-ar 44100');
INSERT INTO mime VALUES (2, 0, 0, '3gp', 'video', 'video/3gpp', 'Third Generation Partnership Project File Format', '&1&', '-ar 44100');
INSERT INTO mime VALUES (3, 0, 0, 'asf', 'video', 'video/x-ms-asf', 'Advanced Systems Format', '&1&', '-ar 44100');
INSERT INTO mime VALUES (4, 0, 0, 'avi', 'video', 'video/x-msvideo', 'Audio Video Interleave File', '&1&', '-ar 44100');
INSERT INTO mime VALUES (5, 0, 0, 'bmp','image', 'image/bmp', 'Windows OS/2 Bitmap Graphics', '&1&', '');
INSERT INTO mime VALUES (6, 0, 0, 'doc', '', 'application/msword', 'Word Document', '&1&', '');
INSERT INTO mime VALUES (7, 0, 0, 'flv', 'video', 'video/x-flv application/octet-stream', 'Flash Video', '&1&', '-ar 44100');
INSERT INTO mime VALUES (8, 0, 0, 'gif', 'image', 'image/gif', 'Graphic Interchange Format', '&1&2&', '');
INSERT INTO mime VALUES (9, 0, 0, 'jpg','image', 'image/jpeg image/pjpeg', 'JPEG/JIFF Image', '&1&2&', '');
INSERT INTO mime VALUES (10, 0, 0, 'jpeg','image', 'image/jpeg image/pjpeg', 'JPEG/JIFF Image', '&1&2&', '');
INSERT INTO mime VALUES (11, 0, 0, 'mid', 'audio','audio/mid', 'Musical Instrument Digital Interface MIDI-sequention Sound', '&1&', '');
INSERT INTO mime VALUES (12, 0, 0, 'mov','video', 'video/quicktime', 'QuickTime Video Clip', '&1&', '-ar 44100');
INSERT INTO mime VALUES (13, 0, 0, 'mp3','audio', 'audio/mpeg', 'MPEG Audio Stream, Layer III', '&1&', '');
INSERT INTO mime VALUES (14, 0, 0, 'mpeg','video', 'video/mpeg', 'MPEG Movie', '&1&', '-ar 44100');
INSERT INTO mime VALUES (15, 0, 0, 'mpg','video', 'video/mpeg', 'MPEG 1 System Stream', '&1&', '-ar 44100');
INSERT INTO mime VALUES (16, 0, 0, 'pdf','', 'application/pdf', 'Acrobat Portable Document Format', '&1&', '');
INSERT INTO mime VALUES (17, 0, 0, 'png', 'image', 'image/png image/x-png', 'Portable (Public) Network Graphic', '&1&2&', '');
INSERT INTO mime VALUES (18, 0, 0, 'ppt', '', 'application/vnd.ms-powerpoint', 'MS Power Point', '&1&', '');
INSERT INTO mime VALUES (19, 0, 0, 'ram', 'audio', 'audio/x-pn-realaudio', 'RealMedia Metafile', '&1&', '');
INSERT INTO mime VALUES (20, 0, 0, 'rar','', 'application/x-rar-compressed', 'WinRAR Compressed Archive', '&1&', '');
INSERT INTO mime VALUES (21, 0, 0, 'swf','', 'application/x-shockwave-flash', 'Macromedia Flash Format File', '&1&', '');
INSERT INTO mime VALUES (22, 0, 0, 'txt','', 'text/plain', 'Text File', '&1&', '');
INSERT INTO mime VALUES (23, 0, 0, 'wav', 'audio', 'audio/x-wav', 'Waveform Audio', '&1&', '');
INSERT INTO mime VALUES (24, 0, 0, 'wmv', 'video', 'video/x-ms-wmv', 'Windows Media File', '&1&', '-ar 44100');
INSERT INTO mime VALUES (25, 0, 0, 'xls', '', 'application/vnd.ms-excel', 'MS Excel','&1&', '');
INSERT INTO mime VALUES (26, 0, 0, 'zip', '', 'application/zip', 'Compressed Archive File', '&1&', '');

