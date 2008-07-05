<?php
// $Id: highlight.php,v 1.2 2008/07/05 17:12:15 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-07-01 K.OHWADA
// changed webphoto_highlighter_by_style()
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_highlight
// same as happy_linux_highlight
//=========================================================

//---------------------------------------------------------
// original: keyhighlighter
// http://www.phpclasses.org/browse/package/1792.html
//
// porting from smartsection <http://smartfactory.ca/>
// http://smartfactory.ca/modules/newbb/viewtopic.php?topic_id=1211
//---------------------------------------------------------

class webphoto_lib_highlight
{
// keyword
	var $_pattern_array;
	var $_replace_callback = 'webphoto_highlighter_by_style';

// background-color: light yellow
	var $_style = 'font-weight: bolder; background-color: #ffff80; ';
	var $_class = 'webphoto_lib_highlight';

	var $_flag_trim            = true;
	var $_flag_sanitize        = true;
	var $_flag_remove_not_word = false;

// same language match contorl code
// ex) BIG-5 GB2312 餐 C05C B2CD 遊 B943 904A 
	var $_flag_remove_control_code = false;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_highlight()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_highlight();
	}
	return $instance;
}

//---------------------------------------------------------
// function
//---------------------------------------------------------
function build_highlight_keywords( $str, $keywords, $flag_singlewords=true )
{
	if ($keywords) {
		$keywords = $this->_sanitize_keyword($keywords);

		$arr = array ();
		if ($flag_singlewords) {
			$keyword_array = explode (' ', $keywords);
			foreach ($keyword_array as $keyword) {
				$arr[] = '/(?' . '>' . preg_quote($keyword) . '+)/si';
			}
		} else {
			$arr[] = '/(?' . '>' . preg_quote($keywords) . '+)/si';
		}

		$this->_pattern_array = $arr;
		$str = $this->_replace_content($str);
	}

	return $str;
}

function build_highlight_keyword_array( $str, $keyword_array )
{
	$ret = $str;

	if ( is_array($keyword_array) && count($keyword_array) ) {
		$arr = array();

		foreach ($keyword_array as $k)
		{
			$keyword = $this->_sanitize_keyword($k);

// not empty
			if ( $keyword ) {
				$arr[] = '/(?' . '>' . preg_quote($keyword, '/') . ')/si';
			}
		}

		if ( count($arr) ) {
			$this->_pattern_array =& $arr;
			$ret = $this->_replace_content( $str );
		}
	}

	return $ret;
}

function _sanitize_keyword($str)
{
	if ( $this->_flag_trim ) {
		$str = trim($str);
	}

	if ( $this->_flag_remove_control_code ) {
		$str = preg_replace('/[\x00-\x1F|\x7F]/', '', $str);
	}

	if ( $this->_flag_remove_not_word ) {
		$str = preg_replace ('/[^\w ]/si', '', $str);
	}

	if ( $this->_flag_sanitize ) {
		$str = htmlspecialchars($str, ENT_QUOTES);
	}

	return $str;
}

function _replace_content($str)
{
	$str = '>' . $str . '<';
	$str = preg_replace_callback ("/(\>(((?" . ">[^><]+)|(?R))*)\<)/is", array (&$this, '_replace_with_callback'), $str);
	$str = substr ($str, 1, -1);
	return $str;
}

function _replace_with_callback( $matches ) 
{
	$replacement = '<span class="'. $this->_class .'">\\0</span>';
	$result = false;

	if ( is_array($matches) && isset($matches[0]) ) {
		$result = $matches[0];

		foreach ($this->_pattern_array as $pattern) 
		{
			if ( !is_null ($this->_replace_callback) ) {
				$result = preg_replace_callback ($pattern, $this->_replace_callback, $result);
			} else {
				$result = preg_replace ($pattern, $replacement, $result);
			}
		}
	}

	return $result;
}

//---------------------------------------------------------
// set parameter
//---------------------------------------------------------
function set_replace_callback($val)
{
	$this->_replace_callback = $val;
}

function set_flag_sanitize($val)
{
	$this->_flag_sanitize = (bool)$val;
}

function set_flag_trim($val)
{
	$this->_flag_trim = (bool)$val;
}

function set_flag_remove_control_code($val)
{
	$this->_flag_remove_control_code = (bool)$val;
}

function set_flag_remove_not_word($val)
{
	$this->_flag_remove_not_word = (bool)$val;
}

function set_style($val)
{
	$this->_style = $val;
}

function set_class($val)
{
	$this->_class = $val;
}

function get_style()
{
	return $this->_style;
}

function get_class()
{
	return $this->_class;
}

// --- class end ---
}

//=========================================================
// function
//=========================================================
//---------------------------------------------------------
// porting from smartsection <http://smartfactory.ca/>
//---------------------------------------------------------
function webphoto_highlighter($matches) 
{
// background-color: light yellow
	$STYLE = 'font-weight: bolder; background-color: #ffff80; ';
	$ret   = false;
	if ( is_array($matches) && isset($matches[0]) ) {
		$ret = '<span style="'.$STYLE.'">' . $matches[0] . '</span>';
	}
	return $ret;
}

function webphoto_highlighter_by_style($matches) 
{
	$highlight =& webphoto_lib_highlight::getInstance();
	$style = $highlight->get_style();
	$ret   = false;
	if ( is_array($matches) && isset($matches[0]) ) {
		$ret = '<span style="'.$style.'">' . $matches[0] . '</span>';
	}
	return $ret;
}

function webphoto_highlighter_by_class($matches) 
{
	$highlight =& webphoto_lib_highlight::getInstance();
	$class = $highlight->get_class();
	$ret   = false;
	if ( is_array($matches) && isset($matches[0]) ){
		$ret = '<span class="'.$class.'">' . $matches[0] . '</span>';
	}
	return $ret;
}

?>