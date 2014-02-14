<?php
// $Id: xoopsdhtml.php,v 1.2 2010/02/07 12:20:02 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2010-02-01 K.OHWADA
// set_display_html()
//---------------------------------------------------------

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_editor_xoopsdhtml
//=========================================================
class webphoto_editor_xoopsdhtml extends webphoto_editor_base
{
	var $_caption    = '';
	var $_hiddentext = 'xoopsHiddenText' ;

function webphoto_editor_xoopsdhtml()
{
	$this->webphoto_editor_base();

	$this->set_allow_in_not_has_html( true );
	$this->set_show_display_options(  true );
	$this->set_display_html(   1 ) ;
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
	$ele  = new XoopsFormDhtmlTextArea( 
		$this->_caption, $name, $value, $rows, $cols, $this->_hiddentext );
	return $ele->render();
}

// --- class end ---
}
?>