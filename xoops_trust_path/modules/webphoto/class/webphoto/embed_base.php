<?php
// $Id: embed_base.php,v 1.1 2008/10/30 00:25:51 ohwada Exp $

//=========================================================
// webphoto module
// 2008-10-01 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_embed_base
//=========================================================
class webphoto_embed_base
{
	var $_param     = null ;
	var $_url_head  = null;
	var $_url_tail  = null;
	var $_sample    = null;

	var $_TYPE = null ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_embed_base( $type )
{
	$this->_TYPE = $type ;
}

//---------------------------------------------------------
// interface
//---------------------------------------------------------
function embed( $src, $width, $height, $backcolor='', $frontcolor='', $border='' )
{
	return null;
}

function link( $src )
{
	return null;
}

function thumb( $src )
{
	return null;
}

function desc()
{
	return null;
}

function lang_desc()
{
	return null;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_param( $val )
{
	$this->_param = $val;
}

function get_param( $name )
{
	if ( isset( $this->_param[ $name ] ) ) {
		return  $this->_param[ $name ] ;
	}
	return false ;
}

function set_url( $head, $tail='' )
{
	$this->_url_head = $head;
	if ( $tail ) {
		$this->_url_tail = $tail;
	}
}

function set_sample( $sample )
{
	$this->_sample = $sample;
}

//---------------------------------------------------------
// build
//---------------------------------------------------------
function build_object_begin( $width, $height, $extra=null )
{
	$str  = '<object width="'.$width.'" height="'.$height.' '.$extra.' ">'."\n";
	return $str;
}

function build_object_end()
{
	$str  = "</object>\n";
	return $str;
}

function build_param( $name, $value )
{
	$str = '<param name="'.$name.'" value="'.$value.'" />'."\n";
	return $str;
}

function build_embed_flash( $src, $width, $height, $extra=null )
{
	$str = '<embed src="'.$src.'" width="'.$width.'" height="'.$height.'" '.$extra.' type="application/x-shockwave-flash" />'."\n";
	return $str;
}

function build_link( $src )
{
	$str = $this->_url_head . $src . $this->_url_tail;
	return $str;
}

function build_desc()
{
	return $this->build_desc_span( $this->_url_head, $this->_sample, $this->_url_tail );
}

function build_desc_span( $head, $sample, $tail=null )
{
	$str = $head.'<span style="color: #FF0000;">'.$sample.'</span>'.$tail;
	return $str;
}

function build_lang_desc( $str )
{
	$cont_name = strtoupper( '_WEBPHOTO_EXTERNEL_'.$this->_TYPE );
	if( defined( $cont_name ) ) {
		$str = constant( $cont_name );
	}
	return $str;
}

// --- class end ---
}
?>