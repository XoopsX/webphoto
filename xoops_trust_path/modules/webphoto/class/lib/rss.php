<?php
// $Id: rss.php,v 1.2 2008/08/08 04:36:09 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// change log
// 2008-08-01 K.OHWADA
// used webphoto_lib_multibyte
//---------------------------------------------------------

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//---------------------------------------------------------
// http://search.yahoo.com/mrss/
//
// <media:content 
//   url="http://www.foo.com/movie.mov" 
//   fileSize="12216320" 
//   type="video/quicktime"
//   medium="video"
//   isDefault="true" 
//   expression="full" 
//   bitrate="128" 
//   framerate="25"
//   samplingrate="44.1"
//   channels="2"
//   duration="185" 
//   height="200"
//   width="300" 
//   lang="en" />
//
// <media:thumbnail 
//   url="http://www.foo.com/keyframe.jpg"
//   width="75"
//   height="50"
//   time="12:05:01.123" />
//---------------------------------------------------------

//=========================================================
// class webphoto_lib_rss
//=========================================================
class webphoto_lib_rss
{
	var $_DIRNAME;
	var $_MODULE_PATH;
	var $_MODULE_URL;

	var $_MAX_SUMMARY  = 500;

	var $_SITE_AUTHOR_NAME_UID     = 1;
	var $_SITE_AUTHOR_NAME_DEFAULT = 'xoops';
	var $_SITE_LOGO = 'images/logo.gif';
	var $_CHANNEL_GENERATOR = 'XOOPS webphoto';
	var $_CHANNEL_DOCS      = 'http://backend.userland.com/rss/';

	var $_MODULE_ID ;
	var $_MODULE_NAME;

	var $_multibyte_class;

	var $_xoops_sitename;
	var $_xoops_adminmail;
	var $_xoops_slogan;
	var $_xoops_site_author_name;
	var $_is_module_admin;

	var $_template   = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_rss( $dirname )
{
	$this->_multibyte_class =& webphoto_lib_multibyte::getInstance();

	$this->_DIRNAME     = $dirname;
	$this->_MODULE_PATH = XOOPS_ROOT_PATH .'/modules/'. $dirname;
	$this->_MODULE_URL  = XOOPS_URL       .'/modules/'. $dirname;

	$this->_init_xoops_param();
}

function &getInstance( $dirname )
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_rss( $dirname );
	}
	return $instance;
}

//---------------------------------------------------------
// modify from backend.php
//---------------------------------------------------------
function build_rss( $cache_id=null, $cache_time=3600 )
{
	$this->http_output('pass');

	header ('Content-Type:text/xml; charset=utf-8');

	$tpl = new XoopsTpl();

	if ( $cache_time > 0 ) {
		$tpl->xoops_setCaching(2);
		$tpl->xoops_setCacheTime( $cache_time );
	}

	if ( !$tpl->is_cached( $this->_template, $cache_id ) || ($cache_time == 0) ) {
		$this->assign_tpl( $tpl );
	}

	return $tpl->fetch( $this->_template, $cache_id );
}

function set_template( $val )
{
	$this->_template = $val;
}

//---------------------------------------------------------
// template
//---------------------------------------------------------
function assign_tpl( &$tpl )
{
	$channel = $this->build_channel_array();
	$tpl->assign( $this->utf8_array( $channel ) );

	$items = $this->build_items();
	foreach ( $items as $item ) {
		$tpl->append( 'items', $this->utf8_array( $item ) );
	}
}

//---------------------------------------------------------
// channel
//---------------------------------------------------------
function build_channel_array()
{
	$image_url = XOOPS_URL.'/'.$this->_SITE_LOGO ;

	$dimention = getimagesize( XOOPS_ROOT_PATH.'/'.$this->_SITE_LOGO );
	if (empty($dimention[0])) {
		$width = 88;
	} else {
		$width = ($dimention[0] > 144) ? 144 : $dimention[0];
	}
	if (empty($dimention[1])) {
		$height = 31;
	} else {
		$height = ($dimention[1] > 400) ? 400 : $dimention[1];
	}

	$xoops_sitename_xml     = $this->xml_text( $this->_xoops_sitename );
	$xoops_slogan_xml       = $this->xml_text( $this->_xoops_slogan );
	$xoops_module_name_xml  = $this->xml_text( $this->_MODULE_NAME );
	$xoops_site_author_name = $this->_xoops_site_author_name;

	$webmaster     = $this->_xoops_adminmail .' ( '. $xoops_site_author_name .' ) ';
	$webmaster_xml = $this->xml_url( $webmaster ) ;

	$link_xml  = $this->xml_url( XOOPS_URL.'/' ) ;
	$atom_link = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	$lastbuild = date( 'r', time() ) ;

	$arr = array(
		'channel_title'          => $xoops_sitename_xml ,
		'channel_link'           => $link_xml ,
		'channel_atom_link'      => $this->xml_url( $atom_link ),
		'channel_description'    => $xoops_slogan_xml ,
		'channel_lastbuild'      => $this->xml_text( $lastbuild ) ,
		'channel_webmaster'      => $webmaster_xml ,
		'channel_managingeditor' => $webmaster_xml ,
		'channel_category'       => $xoops_module_name_xml ,
		'channel_generator'      => $this->xml_text( $this->_CHANNEL_GENERATOR ),
		'channel_language'       => $this->xml_text( _LANGCODE ),
		'channel_docs'           => $this->xml_url( $this->_CHANNEL_DOCS ) ,
		'image_url'    => $this->xml_url( $image_url ) ,
		'image_title'  => $xoops_sitename_xml ,
		'image_link'   => $link_xml ,
		'image_width'  => intval($width) ,
		'image_height' => intval($height) ,
	);

	return $arr;
}

//---------------------------------------------------------
// items
//---------------------------------------------------------
function build_items()
{
	// dummy
}

// --------------------------------------------------------
// htmlspecialchars
// http://www.w3.org/TR/REC-xml/#dt-markup
// http://www.fxis.co.jp/xmlcafe/tmp/rec-xml.html#dt-markup
//   &  -> &amp;	// without html entity
//   <  -> &lt;
//   >  -> &gt;
//   "  -> &quot;
//   '  -> &apos;
// --------------------------------------------------------
function xml_text($str)
{
	return $this->xml_htmlspecialchars_strict($str);
}

function xml_url($str)
{
	return $this->xml_htmlspecialchars_url($str);
}

function xml_htmlspecialchars($str)
{
	$str = $this->replace_control_code( $str, '' );
	$str = $this->replace_return_code(  $str );
	$str = htmlspecialchars($str);
	$str = preg_replace("/'/", '&apos;', $str);
	return $str;
}

function xml_htmlspecialchars_strict($str)
{
	$str = $this->xml_strip_html_entity_char($str);
	$str = $this->xml_htmlspecialchars($str);
	$str = str_replace('?', '&#063;', $str);
	return $str;
}

function xml_htmlspecialchars_url($str)
{
	$str = preg_replace("/&amp;/sU", '&', $str);
	$str = $this->xml_strip_html_entity_char($str);
	$str = $this->xml_htmlspecialchars($str);
	return $str;
}

function xml_cdata($str, $flag_control=true, $flag_undo=true)
{
	$str = $this->replace_control_code( $str, '');
	$str = $this->xml_undo_html_special_chars($str);

// not sanitize
	$str = $this->xml_convert_cdata($str);

	return $str;
}

function xml_convert_cdata($str)
{
	return preg_replace("/]]>/", ']]&gt;', $str);
}

// --------------------------------------------------------
// strip html entities
//   &abc; -> ' '
// --------------------------------------------------------
function xml_strip_html_entity_char($str)
{
	return preg_replace("/&[0-9a-zA-z]+;/sU", ' ', $str);
}

// --------------------------------------------------------
// undo XOOPS HtmlSpecialChars
//   &lt;   -> <
//   &gt;   -> >
//   &quot; -> "
//   &#039; -> '
//   &amp;  -> &
//   &amp;nbsp; -> &nbsp;
// --------------------------------------------------------
function xml_undo_html_special_chars($str)
{
	$str = preg_replace("/&gt;/i",   '>', $str);
	$str = preg_replace("/&lt;/i",   '<', $str);
	$str = preg_replace("/&quot;/i", '"', $str);
	$str = preg_replace("/&#039;/i", "'", $str);	
	$str = preg_replace("/&amp;nbsp;/i", '&nbsp;', $str);
	return $str;
}

//---------------------------------------------------------
// TAB \x09 \t
// LF  \xOA \n
// CR  \xOD \r
//---------------------------------------------------------
function replace_control_code( $str, $replace=' ' )
{
	$str = preg_replace('/[\x00-\x08]/', $replace, $str);
	$str = preg_replace('/[\x0B-\x0C]/', $replace, $str);
	$str = preg_replace('/[\x0E-\x1F]/', $replace, $str);
	$str = preg_replace('/[\x7F]/',      $replace, $str);
	return $str;
}

function replace_return_code( $str, $replace=' ' )
{
	$str = preg_replace("/\n/", $replace, $str);
	$str = preg_replace("/\r/", $replace, $str);
	return $str;
}

//---------------------------------------------------------
// clear template
//---------------------------------------------------------
function clear_compiled_tpl()
{
	if ( $this->_is_module_admin ) {
		$tpl = new XoopsTpl();
		$tpl->clear_compiled_tpl( $this->_template );
		$tpl->clear_cache( $this->_template );
		echo "template cleared : ". $this->_template ;
	}
}

//---------------------------------------------------------
// multibyte
//---------------------------------------------------------
function http_output( $encoding )
{
	return $this->_multibyte_class->m_mb_http_output( $encoding );
}

function utf8( $str )
{
	return $this->_multibyte_class->convert_to_utf8( $str );
}

function utf8_array( $arr_in )
{
	$arr = array();
	foreach( $arr_in as $k => $v ) {
		$arr[ $k ] = $this->utf8( $v );
	}
	return $arr;
}

//---------------------------------------------------------
// xoops class
//---------------------------------------------------------
function _init_xoops_param()
{
	$xoops_class =& webphoto_xoops_base::getInstance();

	$this->_xoops_language  = $xoops_class->get_config_by_name( 'language' );
	$this->_xoops_sitename  = $xoops_class->get_config_by_name( 'sitename' );
	$this->_xoops_slogan    = $xoops_class->get_config_by_name( 'slogan' );
	$this->_is_module_admin = $xoops_class->get_my_user_is_module_admin();
	$this->_MODULE_ID       = $xoops_class->get_my_module_id();
	$this->_MODULE_NAME     = $xoops_class->get_my_module_name( 'n' );

	$name = $xoops_class->get_user_uname_from_id( $this->_SITE_AUTHOR_NAME_UID );
	if ( empty($name) ) {
		$name = $this->_SITE_AUTHOR_NAME_DEFAULT;
	}

	$this->_xoops_site_author_name = $name;
}

// --- class end ---
}

?>