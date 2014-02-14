# $Id: mysql.sql,v 1.28 2011/12/28 16:16:15 ohwada Exp $

# =========================================================
# webphoto module
# 2008-04-02 K.OHWADA
# =========================================================

# =========================================================
# change log
# 2011-12-25 K.OHWADA
# cat_timeline_mode
# 2011-11-11 K.OHWADA
# TYPE=MyISAM -> ENGINE=MyISAM
# 2011-05-16 K.OHWADA
# SQL syntax error
# 2011-05-01 K.OHWADA
# JW Player 5.6
# flashvar_dock
# 2010-10-01 K.OHWADA
# item_displayfile etc
# svg wav etc
# 2010-06-06 K.OHWADA
# docx 
# 2010-01-10 K.OHWADA
# item_description_scroll 
# 2009-12-06 K.OHWADA
# item_perm_level cat_group_id
# 2009-11-11 K.OHWADA
# item_detail_onclick
# mime_kind of doc, xls, ppt, pdf
# 2009-10-25 K.OHWADA
# mime_kind
# 2009-08-22 K.OHWADA
# item_exif TEXT -> BLOB
# 2009-05-05 K.OHWADA
# video/avi
# 2009-01-10 K.OHWADA
# item_content
# 2009-01-04 K.OHWADA
# item_editor
# 2008-11-29 K.OHWADA
# item_icon_width
# 2008-11-16 K.OHWADA
# item_codeinfo 
# 2008-11-08 K.OHWADA
# item_external_middle
# cat_img_name
# 2008-10-01 K.OHWADA
# item_external_type etc
# 2008-09-09 K.OHWADA
# BUG: redeclare photo table
# 2008-08-24 K.OHWADA
# added item table, file table
# 2008-08-01 K.OHWADA
# added user table, maillog table
# 2008-07-01 K.OHWADA
# added mime_ffmpeg
# =========================================================

#
# Table structure for table `item`
#

CREATE TABLE item (
  item_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  item_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
  item_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
  item_cat_id    INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_gicon_id  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_uid      INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_kind     TINYINT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_ext       VARCHAR(255) NOT NULL DEFAULT '',
  item_datetime  DATETIME NOT NULL,
  item_title     VARCHAR(255) NOT NULL DEFAULT '',
  item_place     VARCHAR(255) NOT NULL DEFAULT '',
  item_equipment VARCHAR(255) NOT NULL DEFAULT '',
  item_gmap_latitude  DOUBLE(10,8) NOT NULL DEFAULT '0',
  item_gmap_longitude DOUBLE(11,8) NOT NULL DEFAULT '0',
  item_gmap_zoom      TINYINT(2) NOT NULL DEFAULT '0',
  item_gmap_type      TINYINT(2) NOT NULL DEFAULT '0',
  item_status TINYINT(2) NOT NULL DEFAULT '0',
  item_hits   INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_rating DOUBLE(6,4) NOT NULL DEFAULT '0.0000',
  item_votes    INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_comments INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_perm_read VARCHAR(255) NOT NULL DEFAULT '',
  item_file_id_1  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_file_id_2  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_file_id_3  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_file_id_4  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_file_id_5  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_file_id_6  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_file_id_7  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_file_id_8  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_file_id_9  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_file_id_10 INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_text_1  VARCHAR(255) NOT NULL DEFAULT '',
  item_text_2  VARCHAR(255) NOT NULL DEFAULT '',
  item_text_3  VARCHAR(255) NOT NULL DEFAULT '',
  item_text_4  VARCHAR(255) NOT NULL DEFAULT '',
  item_text_5  VARCHAR(255) NOT NULL DEFAULT '',
  item_text_6  VARCHAR(255) NOT NULL DEFAULT '',
  item_text_7  VARCHAR(255) NOT NULL DEFAULT '',
  item_text_8  VARCHAR(255) NOT NULL DEFAULT '',
  item_text_9  VARCHAR(255) NOT NULL DEFAULT '',
  item_text_10 VARCHAR(255) NOT NULL DEFAULT '',
  item_description TEXT NOT NULL,
  item_exif   BLOB NOT NULL,
  item_search TEXT NOT NULL,
  item_time_publish INT(10) UNSIGNED NOT NULL DEFAULT '0',
  item_time_expire  INT(10) UNSIGNED NOT NULL DEFAULT '0',
  item_player_id   INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_flashvar_id INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_duration    INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_displaytype INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_onclick     INT(11) UNSIGNED NOT NULL DEFAULT '0',  
  item_views INT(11) NOT NULL DEFAULT '0',
  item_chain INT(11) NOT NULL DEFAULT '0',
  item_siteurl VARCHAR(255) NOT NULL DEFAULT '',
  item_artist  VARCHAR(255) NOT NULL DEFAULT '',
  item_album   VARCHAR(255) NOT NULL DEFAULT '',
  item_label   VARCHAR(255) NOT NULL DEFAULT '',
  item_perm_down VARCHAR(255) NOT NULL DEFAULT '',
  item_external_url   VARCHAR(255) NOT NULL DEFAULT '',
  item_external_thumb VARCHAR(255) NOT NULL DEFAULT '',
  item_embed_type     VARCHAR(255) NOT NULL DEFAULT '',
  item_embed_src      VARCHAR(255) NOT NULL DEFAULT '',
  item_playlist_feed  VARCHAR(255) NOT NULL DEFAULT '',
  item_playlist_dir   VARCHAR(255) NOT NULL DEFAULT '',
  item_playlist_cache VARCHAR(255) NOT NULL DEFAULT '',
  item_playlist_type  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_playlist_time  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  item_showinfo  VARCHAR(255) NOT NULL DEFAULT '',  
  item_external_middle VARCHAR(255) NOT NULL DEFAULT '',
  item_icon_name VARCHAR(255) NOT NULL DEFAULT '',
  item_codeinfo  VARCHAR(255) NOT NULL DEFAULT '',
  item_page_width  INT(11) NOT NULL DEFAULT '0',
  item_page_height INT(11) NOT NULL DEFAULT '0',
  item_embed_text  TEXT NOT NULL,
  item_icon_width  INT(11) NOT NULL DEFAULT '0',
  item_icon_height INT(11) NOT NULL DEFAULT '0',
  item_editor  VARCHAR(255) NOT NULL DEFAULT '',
  item_description_html   TINYINT(2) NOT NULL DEFAULT '0',
  item_description_smiley TINYINT(2) NOT NULL DEFAULT '0',
  item_description_xcode  TINYINT(2) NOT NULL DEFAULT '0',
  item_description_image  TINYINT(2) NOT NULL DEFAULT '0',
  item_description_br     TINYINT(2) NOT NULL DEFAULT '0',
  item_width  INT(11) NOT NULL DEFAULT '0',
  item_height INT(11) NOT NULL DEFAULT '0',
  item_content TEXT NOT NULL,
  item_detail_onclick INT(11) UNSIGNED NOT NULL DEFAULT '0',  
  item_weight         INT(11) UNSIGNED NOT NULL DEFAULT '0',  
  item_perm_level TINYINT(2) NOT NULL DEFAULT '0',  
  item_description_scroll INT(11) UNSIGNED NOT NULL DEFAULT '0',  
  item_displayfile INT(11) UNSIGNED NOT NULL DEFAULT '0', 
  item_file_id_11  INT(11) UNSIGNED NOT NULL DEFAULT '0', 
  item_file_id_12  INT(11) UNSIGNED NOT NULL DEFAULT '0', 
  item_file_id_13  INT(11) UNSIGNED NOT NULL DEFAULT '0', 
  item_file_id_14  INT(11) UNSIGNED NOT NULL DEFAULT '0', 
  item_file_id_15  INT(11) UNSIGNED NOT NULL DEFAULT '0', 
  item_file_id_16  INT(11) UNSIGNED NOT NULL DEFAULT '0', 
  item_file_id_17  INT(11) UNSIGNED NOT NULL DEFAULT '0', 
  item_file_id_18  INT(11) UNSIGNED NOT NULL DEFAULT '0', 
  item_file_id_19  INT(11) UNSIGNED NOT NULL DEFAULT '0', 
  item_file_id_20  INT(11) UNSIGNED NOT NULL DEFAULT '0', 
  PRIMARY KEY (item_id),
  KEY (item_time_update),
  KEY (item_cat_id),
  KEY (item_gicon_id),
  KEY (item_player_id),
  KEY (item_flashvar_id), 
  KEY (item_uid),
  KEY (item_file_id_1),
  KEY (item_file_id_2),
  KEY (item_file_id_3),
  KEY (item_file_id_4),
  KEY (item_file_id_5),
  KEY (item_file_id_6),
  KEY (item_file_id_7),
  KEY (item_file_id_8),
  KEY (item_file_id_9),
  KEY (item_file_id_10),
  KEY (item_status),
  KEY (item_hits),
  KEY (item_views),
  KEY (item_rating),
  KEY (item_votes),
  KEY (item_datetime),
  KEY (item_weight),
  KEY (item_title(40)),
  KEY (item_place(40)),
  KEY (item_equipment(40)),
  KEY (item_search(40)),
  KEY (item_gmap_latitude, item_gmap_longitude, item_gmap_zoom)
) ENGINE=MyISAM;

#
# Table structure for table `file`
#

CREATE TABLE file (
  file_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  file_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
  file_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
  file_item_id INT(11) UNSIGNED NOT NULL DEFAULT '0',
  file_kind    TINYINT(11) UNSIGNED NOT NULL DEFAULT '0',
  file_url     VARCHAR(255) NOT NULL DEFAULT '',
  file_path    VARCHAR(255) NOT NULL DEFAULT '',
  file_name    VARCHAR(255) NOT NULL DEFAULT '',
  file_ext     VARCHAR(10) NOT NULL DEFAULT '',
  file_mime    VARCHAR(255) NOT NULL DEFAULT '',
  file_medium  VARCHAR(255) NOT NULL DEFAULT '',
  file_size     INT(5) NOT NULL DEFAULT '0',
  file_width    INT(5) NOT NULL DEFAULT '0',
  file_height   INT(5) NOT NULL DEFAULT '0',
  file_duration INT(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (file_id),
  KEY (file_time_update),
  KEY (file_item_id),
  KEY (file_kind)
) ENGINE=MyISAM;

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
) ENGINE=MyISAM;

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
  cat_img_name VARCHAR(255) NOT NULL DEFAULT '',
  cat_group_id INT(5) UNSIGNED NOT NULL DEFAULT '0',
  cat_timeline_mode  TINYINT(2) NOT NULL DEFAULT '0',
  cat_timeline_scale INT(5) NOT NULL DEFAULT '0',
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
) ENGINE=MyISAM;

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
) ENGINE=MyISAM;

#
# Table structure for table `gicon`
#

CREATE TABLE gicon (
  gicon_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  gicon_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
  gicon_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
  gicon_title VARCHAR(255) NOT NULL DEFAULT '',
  gicon_image_path  VARCHAR(255) NOT NULL DEFAULT '',
  gicon_image_name  VARCHAR(255) NOT NULL DEFAULT '',
  gicon_image_ext   VARCHAR(10)  NOT NULL DEFAULT '',
  gicon_shadow_path VARCHAR(255) NOT NULL DEFAULT '',
  gicon_shadow_name VARCHAR(255) NOT NULL DEFAULT '',
  gicon_shadow_ext  VARCHAR(10)  NOT NULL DEFAULT '',
  gicon_image_width   INT(4) NOT NULL DEFAULT '0',
  gicon_image_height  INT(4) NOT NULL DEFAULT '0',
  gicon_shadow_width  INT(4) NOT NULL DEFAULT '0',
  gicon_shadow_height INT(4) NOT NULL DEFAULT '0',
  gicon_anchor_x INT(4) NOT NULL DEFAULT '0',
  gicon_anchor_y INT(4) NOT NULL DEFAULT '0',
  gicon_info_x   INT(4) NOT NULL DEFAULT '0',
  gicon_info_y   INT(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (gicon_id)
) ENGINE=MyISAM;

#
# Table structure for table `mime`
#

CREATE TABLE mime (
  mime_id INT(11) NOT NULL AUTO_INCREMENT,
  mime_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
  mime_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
  mime_ext    VARCHAR(10) NOT NULL DEFAULT '',
  mime_medium VARCHAR(255) NOT NULL DEFAULT '',
  mime_type   VARCHAR(255) NOT NULL DEFAULT '',
  mime_name   VARCHAR(255) NOT NULL DEFAULT '',
  mime_perms  VARCHAR(255) NOT NULL DEFAULT '',
  mime_ffmpeg VARCHAR(255) NOT NULL DEFAULT '',
  mime_kind   INT(4) NOT NULL DEFAULT '0',
  mime_option VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY mime_id (mime_id),
  KEY (mime_ext)
) ENGINE=MyISAM;

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
) ENGINE=MyISAM;

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
) ENGINE=MyISAM;

#
# Table structure for table `syno`
#

CREATE TABLE syno (
 syno_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
 syno_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
 syno_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
 syno_weight INT(5) UNSIGNED NOT NULL DEFAULT 0,
 syno_key   VARCHAR(255) NOT NULL DEFAULT '',
 syno_value VARCHAR(255) NOT NULL DEFAULT '',
 PRIMARY KEY (syno_id)
) ENGINE=MyISAM;

#
# Table structure for table `user`
#

CREATE TABLE user (
 user_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
 user_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
 user_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
 user_uid INT(5) UNSIGNED NOT NULL DEFAULT 0,
 user_cat_id INT(5) UNSIGNED NOT NULL DEFAULT 0,
 user_email VARCHAR(255) NOT NULL DEFAULT '',
 user_text1 VARCHAR(255) NOT NULL DEFAULT '',
 user_text2 VARCHAR(255) NOT NULL DEFAULT '',
 user_text3 VARCHAR(255) NOT NULL DEFAULT '',
 user_text4 VARCHAR(255) NOT NULL DEFAULT '',
 user_text5 VARCHAR(255) NOT NULL DEFAULT '', 
 PRIMARY KEY (user_id),
 KEY (user_uid),
 KEY (user_cat_id),
 KEY (user_email(40))
) ENGINE=MyISAM;

#
# Table structure for table `maillog`
#

CREATE TABLE maillog (
 maillog_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
 maillog_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
 maillog_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
 maillog_photo_ids   VARCHAR(255) NOT NULL DEFAULT '',
 maillog_status  TINYINT(2) NOT NULL DEFAULT '0',
 maillog_from    VARCHAR(255) NOT NULL DEFAULT '',
 maillog_subject VARCHAR(255) NOT NULL DEFAULT '',
 maillog_body    VARCHAR(255) NOT NULL DEFAULT '',
 maillog_file    VARCHAR(255) NOT NULL DEFAULT '',
 maillog_attach  TEXT NOT NULL,
 maillog_comment TEXT NOT NULL,
 PRIMARY KEY (maillog_id),
 KEY (maillog_status),
 KEY (maillog_from(40))
) ENGINE=MyISAM;

#
## Table structure for table `player`
#

CREATE TABLE player (
  player_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  player_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
  player_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
  player_title VARCHAR(24) NOT NULL DEFAULT '',
  player_style  TINYINT(2) NOT NULL DEFAULT '0', 
  player_width  INT(4) NOT NULL DEFAULT '0',
  player_height INT(4) NOT NULL DEFAULT '0',
  player_displaywidth  INT(4) NOT NULL DEFAULT '0',
  player_displayheight INT(4) NOT NULL DEFAULT '0',
  player_screencolor VARCHAR(7) NOT NULL DEFAULT '',
  player_backcolor   VARCHAR(7) NOT NULL DEFAULT '',
  player_frontcolor  VARCHAR(7) NOT NULL DEFAULT '',
  player_lightcolor  VARCHAR(7) NOT NULL DEFAULT '',
  PRIMARY KEY  (player_id),
  KEY (player_title(24))
) ENGINE=MyISAM;

#
## Table structure for table `flashvar`
#

CREATE TABLE flashvar (
  flashvar_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  flashvar_time_create INT(10) UNSIGNED NOT NULL DEFAULT '0',
  flashvar_time_update INT(10) UNSIGNED NOT NULL DEFAULT '0',
  flashvar_item_id INT(11) UNSIGNED NOT NULL DEFAULT '0',
  flashvar_width  INT(4) NOT NULL DEFAULT '0',
  flashvar_height INT(4) NOT NULL DEFAULT '0',
  flashvar_displaywidth  INT(4) NOT NULL DEFAULT '0',
  flashvar_displayheight INT(4) NOT NULL DEFAULT '0',
  flashvar_image_show TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_searchbar  TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_showeq     TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_showicons  TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_shownavigation TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_showstop   TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_showdigits TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_showdownload  TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_usefullscreen TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_autoscroll    TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_thumbsinplaylist TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_autostart  TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_repeat  TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_shuffle TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_smoothing  TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_enablejs   TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_linkfromdisplay TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_link_type       TINYINT(2) NOT NULL DEFAULT '0',
  flashvar_bufferlength INT(3)  NOT NULL DEFAULT '0',
  flashvar_rotatetime   INT(3)  NOT NULL DEFAULT '0',
  flashvar_volume       INT(3)  NOT NULL DEFAULT '0',
  flashvar_screencolor VARCHAR(7) NOT NULL DEFAULT '',
  flashvar_backcolor   VARCHAR(7) NOT NULL DEFAULT '',
  flashvar_frontcolor  VARCHAR(7) NOT NULL DEFAULT '',
  flashvar_lightcolor  VARCHAR(7) NOT NULL DEFAULT '',
  flashvar_linktarget  VARCHAR(24) NOT NULL DEFAULT '',
  flashvar_overstretch VARCHAR(6)  NOT NULL DEFAULT '',
  flashvar_transition  VARCHAR(24) NOT NULL DEFAULT '',
  flashvar_type        VARCHAR(5)  NOT NULL DEFAULT '',
  flashvar_file      VARCHAR(255) NOT NULL DEFAULT '',
  flashvar_image     VARCHAR(255) NOT NULL DEFAULT '',
  flashvar_logo      VARCHAR(255) NOT NULL DEFAULT '',
  flashvar_link      VARCHAR(255) NOT NULL DEFAULT '',
  flashvar_audio     VARCHAR(255) NOT NULL DEFAULT '',
  flashvar_captions  VARCHAR(255) NOT NULL DEFAULT '',
  flashvar_fallback  VARCHAR(255) NOT NULL DEFAULT '',
  flashvar_callback  VARCHAR(255) NOT NULL DEFAULT '',
  flashvar_javascriptid     VARCHAR(255) NOT NULL DEFAULT '',
  flashvar_recommendations  VARCHAR(255) NOT NULL DEFAULT '',
  flashvar_streamscript     VARCHAR(255) NOT NULL DEFAULT '',
  flashvar_searchlink  VARCHAR(255) NOT NULL DEFAULT '',
  flashvar_dock  TINYINT(2)  NOT NULL DEFAULT '0',
  flashvar_icons TINYINT(2)  NOT NULL DEFAULT '0',
  flashvar_mute  TINYINT(2)  NOT NULL DEFAULT '0',
  flashvar_controlbar_idlehide TINYINT(2)  NOT NULL DEFAULT '0',
  flashvar_display_showmute    TINYINT(2)  NOT NULL DEFAULT '0',
  flashvar_logo_hide TINYINT(2)  NOT NULL DEFAULT '0',
  flashvar_duration INT(3)  NOT NULL DEFAULT '0',
  flashvar_start    INT(3)  NOT NULL DEFAULT '0',
  flashvar_item     INT(3)  NOT NULL DEFAULT '0',
  flashvar_playlist_size INT(3)  NOT NULL DEFAULT '0',
  flashvar_logo_margin   INT(3)  NOT NULL DEFAULT '0',
  flashvar_logo_timeout  INT(3)  NOT NULL DEFAULT '0',
  flashvar_logo_over     FLOAT(5,4)  NOT NULL DEFAULT '0',
  flashvar_logo_out      FLOAT(5,4)  NOT NULL DEFAULT '0',
  flashvar_playlistfile  VARCHAR(255) NOT NULL DEFAULT '', 
  flashvar_mediaid       VARCHAR(255) NOT NULL DEFAULT '', 
  flashvar_provider      VARCHAR(255) NOT NULL DEFAULT '', 
  flashvar_streamer      VARCHAR(255) NOT NULL DEFAULT '', 
  flashvar_netstreambasepath VARCHAR(255) NOT NULL DEFAULT '', 
  flashvar_skin          VARCHAR(255) NOT NULL DEFAULT '', 
  flashvar_player_repeat VARCHAR(255) NOT NULL DEFAULT '', 
  flashvar_playerready   VARCHAR(255) NOT NULL DEFAULT '', 
  flashvar_plugins       VARCHAR(255) NOT NULL DEFAULT '',
  flashvar_stretching    VARCHAR(255) NOT NULL DEFAULT '',
  flashvar_controlbar_position VARCHAR(255) NOT NULL DEFAULT '', 
  flashvar_playlist_position   VARCHAR(255) NOT NULL DEFAULT '', 
  flashvar_logo_file       VARCHAR(255) NOT NULL DEFAULT '', 
  flashvar_logo_link       VARCHAR(255) NOT NULL DEFAULT '', 
  flashvar_logo_linktarget VARCHAR(255) NOT NULL DEFAULT '', 
  flashvar_logo_position   VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (flashvar_id),
  KEY (flashvar_item_id)
) ENGINE=MyISAM;

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

INSERT INTO mime VALUES (1, 0, 0, '3g2', 'video', 'video/3gpp2', 'Third Generation Partnership Project 2 File Format', '&1&', '', 21, 'ffmpeg:-ar 44100;');
INSERT INTO mime VALUES (2, 0, 0, '3gp', 'video', 'video/3gpp', 'Third Generation Partnership Project File Format', '&1&', '', 21, 'ffmpeg:-ar 44100;');
INSERT INTO mime VALUES (3, 0, 0, 'asf', 'video', 'video/x-ms-asf', 'Advanced Systems Format', '&1&', '', 21, 'ffmpeg:-ar 44100;');
INSERT INTO mime VALUES (4, 0, 0, 'avi', 'video', 'video/x-msvideo video/avi', 'Audio Video Interleave File', '&1&', '', 21, 'ffmpeg:-ar 44100;');
INSERT INTO mime VALUES (5, 0, 0, 'bmp', 'image', 'image/bmp', 'Windows OS/2 Bitmap Graphics', '&1&', '', 11, '');
INSERT INTO mime VALUES (6, 0, 0, 'doc', '', 'application/msword', 'Word Document', '&1&', '', 41, '');
INSERT INTO mime VALUES (7, 0, 0, 'flv', 'video', 'video/x-flv application/octet-stream', 'Flash Video', '&1&', '-ar 44100', 20, 'ffmpeg:-ar 44100;');
INSERT INTO mime VALUES (8, 0, 0, 'gif', 'image', 'image/gif', 'Graphic Interchange Format', '&1&2&', '', 10, '');
INSERT INTO mime VALUES (9, 0, 0, 'jpg', 'image', 'image/jpeg image/pjpeg', 'JPEG/JIFF Image', '&1&2&', '', 12, '');
INSERT INTO mime VALUES (10, 0, 0, 'jpeg', 'image', 'image/jpeg image/pjpeg', 'JPEG/JIFF Image', '&1&2&', '', 12, '');
INSERT INTO mime VALUES (11, 0, 0, 'mid', 'audio', 'audio/mid', 'Musical Instrument Digital Interface MIDI-sequention Sound', '&1&', '', 31, '');
INSERT INTO mime VALUES (12, 0, 0, 'mov', 'video', 'video/quicktime', 'QuickTime Video Clip', '&1&', '', 21, 'ffmpeg:-ar 44100;');
INSERT INTO mime VALUES (13, 0, 0, 'mp3', 'audio', 'audio/mpeg audio/mp3', 'MPEG Audio Stream, Layer III', '&1&', '', 30, '');
INSERT INTO mime VALUES (14, 0, 0, 'mpeg', 'video', 'video/mpeg', 'MPEG Movie',           '&1&', '', 21, 'ffmpeg:-ar 44100;');
INSERT INTO mime VALUES (15, 0, 0, 'mpg',  'video', 'video/mpeg', 'MPEG 1 System Stream', '&1&', '', 21, 'ffmpeg:-ar 44100;');
INSERT INTO mime VALUES (16, 0, 0, 'pdf', '', 'application/pdf', 'Acrobat Portable Document Format', '&1&', '', 44, '');
INSERT INTO mime VALUES (17, 0, 0, 'png', 'image', 'image/png image/x-png', 'Portable (Public) Network Graphic', '&1&2&', '', 10, '');
INSERT INTO mime VALUES (18, 0, 0, 'ppt', '', 'application/vnd.ms-powerpoint', 'MS Power Point', '&1&', '', 43, '');
INSERT INTO mime VALUES (19, 0, 0, 'ram', 'audio', 'audio/x-pn-realaudio', 'RealMedia Metafile', '&1&', '', 30, '');
INSERT INTO mime VALUES (20, 0, 0, 'rar', '', 'application/x-rar-compressed', 'WinRAR Compressed Archive', '&1&', '', 0, '');
INSERT INTO mime VALUES (21, 0, 0, 'swf', '', 'application/x-shockwave-flash', 'Macromedia Flash Format File', '&1&', '', 0, '');
INSERT INTO mime VALUES (22, 0, 0, 'txt', '', 'text/plain', 'Text File', '&1&', '', 0, '');
INSERT INTO mime VALUES (23, 0, 0, 'wav', 'audio', 'audio/wav audio/x-wav', 'Waveform Audio', '&1&', '', 32, '');
INSERT INTO mime VALUES (24, 0, 0, 'wmv', 'video', 'video/x-ms-wmv', 'Windows Media File', '&1&', '', 21, 'ffmpeg:-ar 44100;');
INSERT INTO mime VALUES (25, 0, 0, 'xls', '', 'application/vnd.ms-excel', 'MS Excel', '&1&', '', 42, '');
INSERT INTO mime VALUES (26, 0, 0, 'zip', '', 'application/zip', 'Compressed Archive File', '&1&', '', 0, '');
INSERT INTO mime VALUES (27, 0, 0, 'ai', '', 'application/postscript', 'Adobe Illustrator', '&1&', '', 11, '');
INSERT INTO mime VALUES (28, 0, 0, 'eps', '', 'application/postscript', 'Encapsulated PostScript', '&1&', '', 11, '');
INSERT INTO mime VALUES (29, 0, 0, 'pct', '', 'image/x-pict', 'Apple Macintosh QuickDraw/PICT', '&1&', '', 11, '');
INSERT INTO mime VALUES (30, 0, 0, 'psd', '', 'image/x-photshop', 'Adobe Photoshop', '&1&', '', 11, 'convert:--flatten');
INSERT INTO mime VALUES (31, 0, 0, 'tif', '', 'image/tiff', 'Tag Image File Format', '&1&', '', 11, '');
INSERT INTO mime VALUES (32, 0, 0, 'wmf', '', 'image/wmf application/octet-stream', 'Windows Meta File', '&1&', '', 11, '');

# http://technet.microsoft.com/ja-jp/library/ee309278%28office.12%29.aspx
INSERT INTO mime VALUES (33, 0, 0, 'docx', '', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'MS Word 2007', '&1&', '', 41, '');
INSERT INTO mime VALUES (34, 0, 0, 'pptx', '', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'MS Power Point 2007', '&1&', '', 43, '');
INSERT INTO mime VALUES (35, 0, 0, 'xltx', '', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'MS Excel 2007', '&1&', '', 42, '');

# v2.30
INSERT INTO mime VALUES (36, 0, 0, 'svg', '', 'image/svg+xml', 'Scalable Vector Graphics', '&1&', '', 11, 'convert:-size 1200;');
INSERT INTO mime VALUES (37, 0, 0, 'wma', '', 'audio/x-ms-wma', 'Windows Media Audio', '&1&', '', 34, '');
INSERT INTO mime VALUES (38, 0, 0, 'aif', '', 'audio/aiff', 'Audio Interchange File Format', '&1&', '', 34, '');
INSERT INTO mime VALUES (39, 0, 0, 'aifc', '', 'audio/aiff', 'Audio Interchange File Format', '&1&', '', 34, '');
INSERT INTO mime VALUES (40, 0, 0, 'aiff', '', 'audio/aiff', 'Audio Interchange File Format', '&1&', '', 34, '');
INSERT INTO mime VALUES (41, 0, 0, 'au', '', 'audio/basic', 'Audio UNIX', '&1&', '', 34, '');
INSERT INTO mime VALUES (42, 0, 0, 'snd', '', 'audio/basic', 'Sound UNIX', '&1&', '', 34, '');
INSERT INTO mime VALUES (43, 0, 0, 'ivf', '', 'application/octet-stream', 'Indeo Video Files', '&1&', '', 34, '');
INSERT INTO mime VALUES (44, 0, 0, 'midi', '', 'audio/mid', 'Musical Instrument Digital Interface MIDI-sequention Sound', '&1&', '', 31, '');
INSERT INTO mime VALUES (45, 0, 0, 'rmi', '', 'audio/mid', 'Musical Instrument Digital Interface MIDI-sequention Sound', '&1&', '', 31, '');
INSERT INTO mime VALUES (46, 0, 0, 'mpa', '', 'video/x-mpg', 'MPEG-1 Audio Layer-III', '&1&', '', 34, 'ffmpeg:-ar 44100');
INSERT INTO mime VALUES (47, 0, 0, 'm1v', '', 'video/mpeg video/x-mpeg', 'MPEG-1 Audio Layer-III', '&1&', '', 21, 'ffmpeg:-ar 44100');
INSERT INTO mime VALUES (48, 0, 0, 'mpe', '', 'video/mpeg video/x-mpeg', 'MPEG-1 Audio Layer-III', '&1&', '', 21, 'ffmpeg:-ar 44100');
INSERT INTO mime VALUES (49, 0, 0, 'mp4', '', 'video/mp4 video/mpeg4', 'MPEG-4', '&1&', '', 21, 'ffmpeg:-ar 44100');

#
# player table
#

INSERT INTO player VALUES (1, 0, 0, 'default', 0, 260, 320, 0, 0, '', '', '', '');
INSERT INTO player VALUES (2, 0, 0, 'playlist default', 0, 260, 320, 260, 220, '', '', '', '');
