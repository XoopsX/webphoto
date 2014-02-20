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
	private $isXCL22;

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
	
	$this->isXCL22 = (defined('LEGACY_BASE_VERSION') && version_compare(LEGACY_BASE_VERSION, '2.2.0.0', '>='));
}

function display_options($has_html)
{
	if ($this->isXCL22) {
		$arr = array(
			'html'   => $has_html? 1 : 0,
			'smiley' => $has_html? 0 : 1,
			'xcode'  => $has_html? 0 : 1,
			'image'  => $has_html? 0 : 1,
			'br'     => $has_html? 0 : 1
		);
		return $arr;
	} else {
		return parent::display_options($has_html);
	}
}

function exists()
{
	return true ;
}

function build_textarea( $id, $name, $value, $rows, $cols, $item_row )
{
	if ($this->isXCL22) {
		$params = array();
		$params['class'] = $params['editor'] =  $item_row['item_description_html']? 'html' : 'bbcode';
		$params['name'] = trim($name);
		$params['cols'] = $cols ? intval($cols) : 50;
		$params['rows'] = $rows ? intval($rows) : 5;
		$params['value'] = $value ? $value : null;
		$params['id'] = $id ? trim($id) : 'legacy_xoopsform_' . $params['name'];
		
		$html = "";
		switch($params['editor']){
			case 'html':
				XCube_DelegateUtils::call("Site.TextareaEditor.HTML.Show", new XCube_Ref($html), $params);
				break;
			default:
				XCube_DelegateUtils::call("Site.TextareaEditor.BBCode.Show", new XCube_Ref($html), $params);
				break;
		}
		return $html;
		
	} else {
		$ele  = new XoopsFormDhtmlTextArea( 
			$this->_caption, $name, $value, $rows, $cols, $this->_hiddentext );
		return $ele->render();
	}
}

// --- class end ---
}
?>