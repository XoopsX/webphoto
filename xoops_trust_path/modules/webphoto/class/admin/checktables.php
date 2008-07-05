<?php
// $Id: checktables.php,v 1.2 2008/07/05 12:54:16 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// added $admin_link
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_admin_checktables
//=========================================================
class webphoto_admin_checktables extends webphoto_base_this
{
	var $_vote_handler;
	var $_gicon_handler;
	var $_mime_handler;
	var $_tag_handler;
	var $_p2t_handler;
	var $_syno_handler;
	var $_xoops_comments_handler;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_admin_checktables( $dirname, $trust_dirname )
{
	$this->webphoto_base_this( $dirname, $trust_dirname );

	$this->_vote_handler   =& webphoto_vote_handler::getInstance( $dirname );
	$this->_gicon_handler  =& webphoto_gicon_handler::getInstance( $dirname );
	$this->_mime_handler   =& webphoto_mime_handler::getInstance( $dirname );
	$this->_tag_handler    =& webphoto_tag_handler::getInstance( $dirname );
	$this->_p2t_handler    =& webphoto_p2t_handler::getInstance( $dirname );
	$this->_syno_handler   =& webphoto_syno_handler::getInstance( $dirname );
	$this->_xoops_comments_handler =& webphoto_xoops_comments_handler::getInstance();
}

function &getInstance( $dirname, $trust_dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_admin_checktables( $dirname, $trust_dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function main()
{
	xoops_cp_header();

	echo $this->build_admin_menu();
	echo $this->build_admin_title( 'CHECKTABLES' );

	$this->_print_check();

	xoops_cp_footer();
}

//---------------------------------------------------------
// check
//---------------------------------------------------------
function _print_check()
{
	$cfg_makethumb   = $this->_config_class->get_by_name('makethumb');

//
// TABLE CHECK
//
	echo "<h4>"._AM_WEBPHOTO_H4_TABLE."</h4>\n" ;

	echo _WEBPHOTO_PHOTO_TABLE.": ";
	echo $this->_photo_handler->get_table();
	echo " &nbsp; " ;

	echo _AM_WEBPHOTO_NUMBEROFPHOTOS.": ";
	echo $this->_photo_handler->get_count_all();
	echo "<br /><br />\n" ;

	echo _WEBPHOTO_CAT_TABLE.": ";
	echo $this->_cat_handler->get_table();
	echo " &nbsp; " ;

	echo _AM_WEBPHOTO_NUMBEROFCATEGORIES.": ";
	echo $this->_cat_handler->get_count_all();
	echo "<br /><br />\n" ;

	echo _WEBPHOTO_VOTE_TABLE.": ";
	echo $this->_vote_handler->get_table();
	echo " &nbsp; " ;

	echo _AM_WEBPHOTO_NUMBEROFVOTEDATA.": ";
	echo $this->_vote_handler->get_count_all();
	echo "<br /><br />\n" ;

	echo _WEBPHOTO_GICON_TABLE.": ";
	echo $this->_gicon_handler->get_table();
	echo " &nbsp; " ;

	echo _AM_WEBPHOTO_NUMBEROFRECORED.": ";
	echo $this->_gicon_handler->get_count_all();
	echo "<br /><br />\n" ;

	echo _WEBPHOTO_MIME_TABLE.": ";
	echo $this->_mime_handler->get_table();
	echo " &nbsp; " ;

	echo _AM_WEBPHOTO_NUMBEROFRECORED.": ";
	echo $this->_mime_handler->get_count_all();
	echo "<br /><br />\n" ;

	echo _WEBPHOTO_TAG_TABLE.": ";
	echo $this->_tag_handler->get_table();
	echo " &nbsp; " ;

	echo _AM_WEBPHOTO_NUMBEROFRECORED.": ";
	echo $this->_tag_handler->get_count_all();
	echo "<br /><br />\n" ;

	echo _WEBPHOTO_P2T_TABLE.": ";
	echo $this->_p2t_handler->get_table();
	echo " &nbsp; " ;

	echo _AM_WEBPHOTO_NUMBEROFRECORED.": ";
	echo $this->_p2t_handler->get_count_all();
	echo "<br /><br />\n" ;

	echo _WEBPHOTO_SYNO_TABLE.": ";
	echo $this->_syno_handler->get_table();
	echo " &nbsp; " ;

	echo _AM_WEBPHOTO_NUMBEROFRECORED.": ";
	echo $this->_syno_handler->get_count_all();
	echo "<br /><br />\n" ;

	echo _AM_WEBPHOTO_COMMENTSTABLE.": ";
	echo $this->_xoops_comments_handler->get_table();
	echo " &nbsp; " ;

	echo _AM_WEBPHOTO_NUMBEROFCOMMENTS.": ";
	echo $this->_xoops_comments_handler->get_count_by_modid( $this->_MODULE_ID );
	echo "<br /><br />\n" ;

//
// CONSISTEMCY CHECK
//
	echo "<h4>"._AM_WEBPHOTO_H4_PHOTOLINK."</h4>\n" ;

	$dead_photos = 0 ;
	$dead_thumbs = 0 ;
	echo _AM_WEBPHOTO_NOWCHECKING ;

	$rows = $this->_photo_handler->get_rows_all_asc();
	foreach ( $rows as $row )
	{
		$id         = $row['photo_id'];
		$ext        = $row['photo_file_ext'];
		$file_path  = $row['photo_file_path'];
		$file_ext   = $row['photo_file_ext'];
		$photo_path = $row['photo_cont_path'];
		$photo_ext  = $row['photo_cont_ext'];
		$thumb_path = $row['photo_thumb_path'];
		$thumb_ext  = $row['photo_thumb_ext'];

		$file_full_path  = XOOPS_ROOT_PATH  . $file_path ;
		$photo_full_path = XOOPS_ROOT_PATH . $photo_path ;
		$thumb_full_path = XOOPS_ROOT_PATH . $thumb_path ;

		$admin_url  = $this->_MODULE_URL .'/admin/index.php?fct=photo_table_manage&amp;op=form&amp;id='.$id;
		$admin_link = '<a href="'. $admin_url .'" target="_blank">'. sprintf("%04d", $id) .'</a> : '."\n";

		echo ". " ;

		if ( $file_path && ! is_readable( $file_full_path ) ) {
			echo "<br />\n";
			echo $admin_link;
			printf( _AM_WEBPHOTO_FMT_PHOTONOTREADABLE , $file_full_path ) ;
			echo "<br />\n";
			$dead_photos ++ ;

		} elseif ( $photo_path && ! is_readable( $photo_full_path ) ) {
			echo "<br />\n";
			echo $admin_link;
			printf( _AM_WEBPHOTO_FMT_PHOTONOTREADABLE , $photo_full_path ) ;
			echo "<br />\n";
			$dead_photos ++ ;
		}

		if ( $thumb_path && ! is_readable( $thumb_full_path ) ) {
			echo "<br />\n";
			echo $admin_link;
			printf( _AM_WEBPHOTO_FMT_THUMBNOTREADABLE , $thumb_full_path ) ;
			echo "<br />\n";
			$dead_thumbs ++ ;
		}
	}

// show result
	if( $dead_photos == 0 ) {
		if( ! $cfg_makethumb || $dead_thumbs == 0 ) {
			$this->_print_green( 'ok' );
		} else {
			$msg = sprintf( _AM_WEBPHOTO_FMT_NUMBEROFDEADTHUMBS , $dead_thumbs ) ;
			echo "<br />\n";
			$this->_print_red( $msg );
			echo "<br />\n";
			echo $this->_build_form_redo_thumbs();
		}

	} else {
		$msg = sprintf( _AM_WEBPHOTO_FMT_NUMBEROFDEADPHOTOS , $dead_photos ) ;
		echo "<br />\n";
		$this->_print_red( $msg );
		echo "<br />\n";
		echo $this->_build_form_remove_rec();
	}

}

function _build_form_redo_thumbs()
{
	$text  = '<form action="'. $this->_ADMIN_INDEX_PHP .'" method="post">'."\n";
	$text .= '<input type="hidden" name="fct" value="redothumbs" />'."\n";
	$text .= '<input type="submit" value="'. _AM_WEBPHOTO_LINK_REDOTHUMBS .'" />'."\n";
	$text .= "</form>\n" ;
	return $text;
}

function _build_form_remove_rec()
{
	$text  = '<form action="'. $this->_ADMIN_INDEX_PHP .'" method="post">'."\n";
	$text .= '<input type="hidden" name="fct" value="redothumbs" />'."\n";
	$text .= '<input type="hidden" name="removerec" value="1" />'."\n";
	$text .= '<input type="submit" value="'. _AM_WEBPHOTO_LINK_TABLEMAINTENANCE .'" />'."\n";
	$text .= "</form>\n" ;
	return $text;
}

function _print_on_off( $val, $flag_red=false )
{
	if ( $val ) {
		$this->_print_green('on');
	} elseif ( $flag_red ) { 
		$this->_print_red('off');
	} else { 
		$this->_print_green('off');
	}
}

function _print_red( $str )
{
	echo '<font color="#FF0000"><b>'. $str .'</b></font>'."<br />\n" ;
}

function _print_green( $str )
{
	echo '<font color="#00FF00"><b>'. $str .'</b></font>'."<br />\n" ;
}

// --- class end ---
}

?>