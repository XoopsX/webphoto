<?php
// $Id: plain.php,v 1.1 2010/02/07 12:22:11 ohwada Exp $

//=========================================================
// webphoto module
// 2010-02-01 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_editor_plain
//=========================================================
class webphoto_editor_plain extends webphoto_editor_base
{

function webphoto_editor_plain()
{
	$this->webphoto_editor_base();

	$this->set_display_smiley( 1 ) ;
	$this->set_display_xcode(  1 ) ;
	$this->set_display_image(  1 ) ;
	$this->set_display_br(     1 ) ;
}

function exists()
{
	return true ;
}

function build_textarea( $id, $name, $value, $rows, $cols )
{
	$str  = '<textarea id="'.$id.'" name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'" >';
	$str .= $this->sanitize($value);
	$str .= '</textarea>';
	return $str;
}

// --- class end ---
}
?>