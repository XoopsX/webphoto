<?php
// $Id: fckeditor.php,v 1.1 2009/01/06 09:42:30 ohwada Exp $

//=========================================================
// webphoto module
// 2009-01-04 K.OHWADA
//=========================================================

if ( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_editor_fckeditor
//=========================================================
class webphoto_editor_fckeditor extends webphoto_editor_base
{
	var $_js_base = 'common/fckeditor' ;
	var $_js_file = 'fckeditor.js' ;
	var $_width   = '100%' ;
	var $_height  = '500' ;
	var $_toolbar = 'Default' ;
	var $_value   = '' ;

function webphoto_editor_fckeditor()
{
	$this->webphoto_editor_base();
}

function exists()
{
	$file = XOOPS_ROOT_PATH.'/'.$this->_js_base.'/'.$this->_js_file;
	return file_exists( $file );
}

function build_js()
{
	$base = XOOPS_URL.'/'.$this->_js_base.'/' ;
	$file = $base . $this->_js_file ;

	$str  = '
<script type="text/javascript" src="'. $file .'"></script>
<script type="text/javascript">
<!--
function fckeditor_exec( instanceName ) {
  var oFCKeditor = new FCKeditor( instanceName , "'. $this->_width .'" , "'. $this->_height .'" , "'. $this->_toolbar .'" , "'. $this->_value .'" );
  oFCKeditor.BasePath = "'. $base .'";
  oFCKeditor.ReplaceTextarea();
}
// -->
</script>
' ;

	return $str;
}

function build_textarea( $id, $name, $value, $rows, $cols )
{
	$str  = '<textarea id="'. $id .'" name="'. $name .'">'. $value .'</textarea>';
	$str .= '<script>fckeditor_exec("'. $id .'");</script>' ;
	return $str;
}

// --- class end ---
}
?>