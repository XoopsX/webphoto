<?php
// $Id: element.php,v 1.1 2008/06/21 12:22:28 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_element
//=========================================================
class webphoto_lib_element extends webphoto_lib_error
{
	var $_alternate_class = '';
	var $_line_count      = 0;
	var $_row             = array();

	var $_cached_token = null;
	var $_token_errors = null;
	var $_token_error_flag  = false;

// set parameter
	var $_FORM_NAME    = 'form';
	var $_TITLE_HEADER = 'title';
	var $_KEYWORD_MIN = 5;

// local constant
	var $_TABLE_SELECT_WIDTH = '200px';
	var $_TD_SELECT_WIDTH    = '100px';

	var $_DIV_STYLE = 'background-color: #dde1de; border: 1px solid #808080; margin: 5px; padding: 10px 10px 5px 10px; width: 95%; text-align: center; ';
	var $_DIV_ERROR_STYLE = 'background-color: #ffffe0; color: #ff0000; border: #808080 1px dotted; margin:  3px; padding: 3px;';
	var $_SPAN_STYLE       = 'font-size: 120%; font-weight: bold; color: #000000; ';
	var $_SPAN_TITLE_STYLE = 'font-size: 120%; font-weight: bold; color: #000000; ';

	var $_SELECTED = 'selected="selected"';
	var $_CHECKED  = 'checked="checked"';

	var $_TIME_FORMAT = 'Y/m/d H:i';

	var $_SIZE_PERPAGE = 10;

	var $_C_YES = 1;
	var $_C_NO  = 0;

	var $_THIS_URL;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webphoto_lib_element()
{
	$this->webphoto_lib_error();

	$this->_THIS_URL = xoops_getenv('PHP_SELF');
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) {
		$instance = new webphoto_lib_element();
	}
	return $instance;
}

//---------------------------------------------------------
// header
//---------------------------------------------------------
function build_html_header( $title=null )
{
	if ( empty($title) ) {
		$title = $this->_TITLE_HEADER;	// todo
	}

	$text  = '<html><head>'."\n";
	$text .= '<meta http-equiv="Content-Type" content="text/html; charset='. _CHARSET .'" />'."\n";
	$text .= '<title>'. $this->sanitize( $title ) .'</title>'."\n";
	$text .= '</head><body>'."\n";
	return $text;
}

function build_html_footer( $close=null )
{
	if ( empty($close) ) {
		$close = _CLOSE;
	}

	$text  = '<hr />'."\n";
	$text .= '<div style="text-align:center;">';
	$text .= '<input value="'. $close .'" type="button" onclick="javascript:window.close();" />';
	$text .= '</div>'."\n";
	$text .= '</body></html>'."\n";
	return $text;
}

//---------------------------------------------------------
// form box
//---------------------------------------------------------
function build_form_box_with_style( $param, $hidden_array )
{
	$title = isset($param['title']) ? $param['title'] : null;
	$desc  = isset($param['desc'])  ? $param['desc']  : null;

	$val  = $this->build_form_box( $param, $hidden_array );
	$text = $this->build_form_style( $title, $desc, $val );
	return $text;
}

function build_form_box( $param, $hidden_array )
{
	$form_name    = isset($param['form_name'])    ? $param['form_name']    : null;
	$action       = isset($param['action'])       ? $param['action']       : null;
	$submit_name  = isset($param['submit_name'])  ? $param['submit_name']  : null;
	$submit_value = isset($param['submit_value']) ? $param['submit_value'] : null;

	if ( empty($form_name) ) {
		$form_name = $this->build_form_name_rand();
	}

	if ( empty($action) ) {
		$action = $this->_THIS_URL;
	}

	if ( empty($submit_name) ) {
		$submit_name = 'submit';
	}

	if ( empty($submit_value) ) {
		$submit_value = _SUBMIT;
	}

	$text  = $this->build_form_tag(  $form_name, $action );
	$text .= $this->build_html_token()."\n";

	if( is_array($hidden_array) && count($hidden_array) ) {
		foreach ($hidden_array as $k => $v) {
			$text .= $this->build_input_hidden($k, $v);
		}
	}

	$text .= $this->build_input_submit( $submit_name, $submit_value );

	$text .= $this->build_form_end();
	return $text;
}

function build_form_style( $title, $desc, $value, $style_div='', $style_span='' )
{
	if ( empty($style_div) ) {
		$style_div = $this->_DIV_STYLE;
	}

	if ( empty($style_span) ) {
		$style_span = $this->_SPAN_TITLE_STYLE;
	}

	$text  = '<div style="'. $style_div .'">'."\n";

	if ($title) {
		$text .= '<span style="'. $style_span .'">';
		$text .= $title;
		$text .= "</span><br /><br />\n";
	}

	if ($desc) {
		$text .= $desc."<br /><br />\n";
	}

	$text .= $value;
	$text .= "</div><br />\n";
	return $text;
}

function build_form_confirm( $hiddens, $action, $msg, $submit='', $cancel='', $addToken=true )
{
	$submit = ($submit != '') ? trim($submit) : _SUBMIT;
	$cancel = ($cancel != '') ? trim($cancel) : _CANCEL;

	$text  = '<div class="confirmMsg">'."\n";
	$text .= '<h4>'.$msg.'</h4>'."\n";

	$text .= $this->build_form_tag( 'confirmMsg', $action );

	foreach ( $hiddens as $name => $value ) 
	{
		if (is_array($value)) {
			foreach ($value as $caption => $newvalue) 
			{
				$text .= $this->build_input_radio( $name, $this->sanitize($newvalue) );
				$text .= $caption;
			}
			$text .= "<br />\n";

		} else {
			$text .= $this->build_input_hidden( $name, $this->sanitize($value) );
		}
	}

	if ( $addToken ) {
		$text .= $this->build_html_token()."\n";
	}

// button
	$text .= $this->build_input_submit('confirm_submit', $submit);
	$text .= ' ';
	$text .= $this->build_input_button_cancel( 'confirm_cancel', $cancel );

	$text .= $this->build_form_end();
	$text .= "</div>\n";

	return $text;
}

//---------------------------------------------------------
// form
//---------------------------------------------------------
function build_form_begin( $name=null, $action=null, $method='post', $extra=null )
{
	if ( empty($name) ) {
		$name = $this->_FORM_NAME;
	}
	if ( empty($action) ) {
		$action = $this->_THIS_URL;
	}
	$text  = $this->build_form_tag( $name, $action, $method, $extra );
	$text .= $this->build_html_token()."\n";
	return $text;
}

function build_form_end()
{
	$text  = "</form>\n";
	return $text;
}

function build_js_checkall()
{
	$name     = $this->_FORM_NAME . '_checkall';
	$checkall = "xoopsCheckAll('". $this->_FORM_NAME ."', '". $name ."')";
	$extra    = ' onclick="'.$checkall.'" ';
	$text = '<input type="checkbox" name="'. $name .'" id="'.$name.'" '. $extra .' />'."\n";
	return $text;
}

function build_js_checkbox( $value )
{
	$name = $this->_FORM_NAME . '_id[]';
	$text = '<input type="checkbox" name="'. $name .'" id="'. $name .'" value="'. $value .'"  />'."\n";
	return $text;
}

function substite_empty( $str )
{
	if ( empty($str) ) {
		$str = '---';
	}
	return $str;
}

function build_form_dhtml( $name, $value, $rows=5, $cols=50, $hiddentext='xoopsHiddenText' )
{
	$ele  = new XoopsFormDhtmlTextArea( '', $name, $value, $rows, $cols, $hiddentext );
	$text = $ele->render();
	return $text;
}

function build_form_select( $name, $value, $options, $size=5 )
{
	$text = '<select id="'. $name.'" name="'. $name.'" size="'. $size .'">'."\n";
	foreach ( $options as $k => $v )
	{
		$selected = $this->build_form_selected( $k, $value );
		$text .= '<option value="'. $k .'" '. $selected .' >';
		$text .= $v;
		$text .= '</option >'."\n";
	}
	$text .= '</select>'."\n";
	return $text;
}

function build_form_selected( $val1, $val2 )
{
	if ( $val1 == $val2 ) {
		return $this->_SELECTED;
	}
	return '';
}

function build_form_radio_yesno( $name, $value )
{
	$options = array(
		_YES => $this->_C_YES,
		_NO  => $this->_C_NO,
	);
	return $this->build_form_radio( $name, $value, $options );
}

function build_form_radio( $name, $value, $options, $del='')
{
	$text = '';
	foreach ( $options as $k => $v )
	{
		$text .= $this->build_input_radio( $name, $v, $this->build_form_checked( $v, $value ) );
		$text .= ' ';
		$text .= $k;
		$text .= ' ';
		$text .= $del;
	}

	return $text;
}

function build_form_checkbox( $name, $value, $options, $del='' )
{
	$text = '';

	foreach ($options as $k => $v)
	{
		$checked = $this->build_form_checked( $v, $value);

		$text .= $this->build_input_checkbox( $name, $v, $checked );
		$text .= ' ';
		$text .= $k;
		$text .= ' ';
		$text .= $del;
	}

	return $text;
}

function build_input_checkbox_yes( $name, $value )
{
	$checked = $this->build_form_checked(  $value, $this->_C_YES );
	$text    = $this->build_input_checkbox( $name, $this->_C_YES, $checked );
	return $text;
}

function build_form_checked( $val1, $val2 )
{
	if ( $val1 == $val2 ) {
		return $this->_CHECKED;
	}
	return '';
}

function build_form_file( $name, $size=50, $extra=null )
{
	$text  = $this->build_input_hidden( 'xoops_upload_file[]', $name );
	$text .= $this->build_input_file(   $name,  $size, $extra );
	return $text;
}

function build_form_name_rand()
{
	$name = $this->_FORM_NAME.'_'.rand();
	return $name;
}

//---------------------------------------------------------
// table form
//---------------------------------------------------------
function build_table_begin()
{
	$text = '<table class="outer" width="100%" cellpadding="4" cellspacing="1">'."\n";
	return $text;
}

function build_table_end()
{
	$text  = "</table>\n";
	return $text;
}

function build_line_title( $title )
{
	$text  = '<tr align="center">';
	$text .= '<th colspan="2">'. $title .'</th>';
	$text .= '</tr>'."\n";
	return $text;
}

function build_line_ele( $title, $ele, $flag_sanitaize=false )
{
	if ( $flag_sanitaize ) {
		$ele = $this->sanitize( $ele );
	}

	$text  = '<tr><td class="head">'. $title .'</td>';
	$text .= '<td class="odd">'. $ele .'</td></tr>'."\n";
	return $text;
}

function set_row( $row )
{
	if ( is_array($row) ) {
		$this->_row = $row;
	}
}

function get_row()
{
	return $this->_row;
}

function get_row_by_key( $name, $default=null, $flag_sanitaize=true )
{
	if ( isset( $this->_row[$name] ) ) {
		$ret = $this->_row[$name];
		if ( $flag_sanitaize ) {
			$ret = $this->sanitize( $ret );
		}
		return $ret;
	}
	return $default;
}

function build_row_hidden( $name )
{
	$value = $this->get_row_by_key( $name );
	$text  = $this->build_input_hidden( $name, $value );
	return $text;
}

function build_row_label( $title, $name, $flag_sanitaize=false )
{
	$value = $this->get_row_by_key( $name );
	$value = $this->substite_empty( $value );
	$text  = $this->build_line_ele( $title, $value );
	return $text;
}

function build_row_text( $title, $name, $size=50 )
{
	$value = $this->get_row_by_key( $name );
	$ele   = $this->build_input_text( $name, $value, $size );
	$text  = $this->build_line_ele( $title, $ele );
	return $text;
}

function build_row_url( $title, $name, $size=50 )
{
	$value = $this->get_row_by_key( $name );
	if ( empty($value) ) {
		$value = 'http://';
	}
	$ele   = $this->build_input_text( $name, $value, $size );
	$text  = $this->build_line_ele( $title, $ele );
	return $text;
}

function build_row_text_id( $title, $name, $id, $size=50 )
{
	$value = $this->get_row_by_key( $name );
	$ele   = $this->build_input_text_id( $id, $name, $value, $size );
	$text  = $this->build_line_ele( $title, $ele );
	return $text;
}

function build_row_textarea( $title, $name, $rows=5, $cols=50 )
{
	$value = $this->get_row_by_key( $name );
	$ele   = $this->build_textarea( $name, $value, $rows, $cols );
	$text  = $this->build_line_ele( $title, $ele );
	return $text;
}

function build_row_dhtml( $title, $name, $rows=5, $cols=50 )
{
	$value = $this->get_row_by_key( $name );
	$ele   = $this->build_form_dhtml( $name, $value, $rows, $cols );
	$text  = $this->build_line_ele( $title, $ele );
	return $text;
}

function build_row_radio_yesno( $title, $name )
{
	$value = $this->get_row_by_key( $name );
	$ele   = $this->build_form_radio_yesno( $name, $value );
	$text  = $this->build_line_ele( $title, $ele );
	return $text;
}

function build_line_add()
{
	return $this->build_line_submit( 'add', _ADD );
}

function build_line_submit( $name='submit', $value=_SUBMIT )
{
	$text  = '<tr><td class="head"></td>';
	$text .= '<td class="head">';
	$text .= $this->build_input_submit( $name, $value );
	$text .= "</td></tr>\n";
	return $text;
}

function build_line_edit()
{
	$text  = '<tr><td class="head"></td>';
	$text .= '<td class="head">';
	$text .= $this->build_input_submit( 'edit',   _EDIT );
	$text .= $this->build_input_submit( 'delete', _DELETE );
	$text .= "</td></tr>\n";
	return $text;
}

function get_alternate_class()
{
	if ( $this->_line_count % 2 != 0) {
		$class = 'odd';
	} else {
		$class = 'even';
	}
	$this->_alternate_class = $class;
	$this->_line_count ++;
	return $class;
}

//---------------------------------------------------------
// element
//---------------------------------------------------------
function build_form_tag( $name, $action='', $method='post', $extra=null )
{
	$text = '<form name="'. $name .'" action="'. $action .'" method="'. $method .'" '. $extra. ' >'."\n";
	return $text;
}

function build_form_upload( $name, $action='', $method='post', $extra=null )
{
	$text = '<form name="'. $name .'" action="'. $action .'" method="'. $method .'" enctype="multipart/form-data" '. $extra .' >'."\n";
	return $text;
}

function build_input_hidden( $name, $value, $flag_sanitaize=false )
{
	if ( $flag_sanitaize ) {
		$value = $this->sanitize( $value );
	}

	$text = '<input type="hidden" id="'. $name .'" name="'. $name .'"  value="'. $value .'" />'."\n";
	return $text;
}

function build_input_text( $name, $value, $size=50 )
{
	return $this->build_input_text_id( $name, $name, $value, $size );
}

function build_input_text_id( $id, $name, $value, $size=50 )
{
	$text = '<input tyep="text" id="'. $id .'"  name="'. $name .'" value="'. $value .'" size="'. $size .'" />';
	return $text;
}

function build_input_submit( $name, $value, $extra=null )
{
	$text = '<input type="submit" id="'. $name .'" name="'. $name .'" value="'. $value .'" '. $extra .' />'."\n";
	return $text;
}

function build_input_reset( $name, $value )
{
	$text = '<input type="reset" id="'. $name .'" name="'. $name .'" value="'. $value .'" />'."\n";
	return $text;
}

function build_input_button( $name, $value, $extra=null )
{
	$text = '<input type="button" id="'. $name .'" name="'. $name .'" value="'. $value .'" '. $extra .' />'."\n";
	return $text;
}

function build_input_file( $name, $size=50, $extra=null )
{
	$text = '<input type="file" id="'. $name .'" name="'. $name .'" size="'. $size .'" '. $extra .' />'."\n";
	return $text;
}

function build_input_checkbox( $name, $value, $checked='', $extra='' )
{
	$text = '<input type="checkbox" name="'.$name.'" id="'.$name.'" value="'.$value.'" '.$checked.' '.$extra.' />'."\n";
	return $text;
}

function build_input_radio( $name, $value, $checked='', $extra='' )
{
	$text = '<input type="radio" name="'. $name .'" value="'. $value .'" '. $checked.' '.$extra.' />'."\n";
	return $text;
}

function build_textarea( $name, $value, $rows=5, $cols=80 )
{
	$text  = $this->build_textarea_tag( $name, $rows, $cols );
	$text .= $value;
	$text .= '</textarea>';
	return $text;
}

function build_textarea_tag( $name, $rows=5, $cols=80 )
{
	$text = '<textarea id="'. $name .'" name="'. $name .'" rows="'. $rows .'" cols="'. $cols .'">';
	return $text;
}

function build_span_tag( $styel=null )
{
	if ( empty($style) ) {
		$style = $this->_SPAN_STYLE;
	}
	$text = '<span style="'. $style .'">';
	return $text;
}

function build_span_end()
{
	$text = "</span>\n";
	return $text;
}

function build_div_tag( $styel=null )
{
	if ( empty($style) ) {
		$style = $this->_DIV_STYLE;
	}
	$text = '<div style="'. $style .'">';
	return $text;
}

function build_div_end()
{
	$text = "</div>\n";
	return $text;
}

function build_div_box( $str, $style=null )
{
	$text  = $this->build_div_tag( $style );
	$text .= $str;
	$text .= $this->build_div_end();
	return $text;
}

function build_input_button_cancel( $name, $value=null )
{
	if ( empty($value) ) {
		$value = _CANCEL;
	}
	$extra = ' onclick="javascript:history.go(-1);" ';
	return $this->build_input_button( $name, $value, $extra );
}

//---------------------------------------------------------
// keyword
//---------------------------------------------------------
function parse_keywords( $keywords, $andor='AND' )
{
	$keyword_array = array();
	$ignore_array  = array();

	if ( $keywords == '' ) {
		$arr = array( $keyword_array, $ignore_array );
		return $arr;
	}

	if ( $andor == 'exact' ) {
		$keyword_array = array( $keywords );

	} else {
		$temp_arr = preg_split( '/[\s,]+/', $keywords );

		foreach ($temp_arr as $q) 
		{
			$q = trim($q);
			if ( strlen($q) >= $this->_KEYWORD_MIN ) {
				$keyword_array[] = $q;
			} else {
				$ignore_array[] = $q;
			}
		}
	}

	$arr = array( $keyword_array, $ignore_array );
	return $arr;
}

//---------------------------------------------------------
// token
//---------------------------------------------------------
function get_token_name()
{
	return 'XOOPS_G_TICKET';
}

function get_token()
{
	global $xoopsGTicket;
	if ( is_object($xoopsGTicket) ) {
		return $xoopsGTicket->issue();
	}
	return null;
}

function build_html_token()
{
// get same token on one page, becuase max ticket is 10
	if ( $this->_cached_token ) {
		return $this->_cached_token;
	}

	global $xoopsGTicket;
	$text = '';
	if ( is_object($xoopsGTicket) ) {
		$text = $xoopsGTicket->getTicketHtml()."\n";
		$this->_cached_token = $text;
	}
	return $text;
}

function check_token( $allow_repost=false )
{
	global $xoopsGTicket;
	if ( is_object($xoopsGTicket) ) {
		if ( ! $xoopsGTicket->check( true , '',  $allow_repost ) ) {
			$this->_token_error_flag  = true;
			$this->_token_errors = $xoopsGTicket->getErrors();
			return false;
		}
	}
	$this->_token_error_flag = false;
	return true;
}

function get_token_errors()
{
	return $this->_token_errors;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_time_start_name( $val )
{
	$this->_TIME_START_NAME = $val;
}

function set_form_name( $val )
{
	$this->_FORM_NAME = $val;
}

function set_title_header( $val )
{
	$this->_TITLE_HEADER = $val;
}

function set_keyword_min( $val )
{
	$this->_KEYWORD_MIN = intval($val);
}

// --- class end ---
}

?>