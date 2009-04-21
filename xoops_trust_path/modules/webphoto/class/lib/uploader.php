<?php
// $Id: uploader.php,v 1.5 2009/04/21 15:14:54 ohwada Exp $

//=========================================================
// webphoto module
// 2008-04-02 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webphoto_lib_uploader
// base on myalbum's myuploader.php
//=========================================================

//---------------------------------------------------------
// change log
// 2009-04-21 K.OHWADA
//   chmod_file()
// 2008-11-16
//   clear error
// 2008-11-08
//   getInstance() setUploadDir() etc
// 2008-04-02
// add function
//   setAllowedMimeTypes()
//   setAllowedExtensions()
//   setErrorCodes()
//---------------------------------------------------------

// myuploader.php,v 1.12+
// Security & Bug fixed version of class XoopsMediaUploader by GIJOE

/*!
Example

  include_once 'myuploader.php';
  $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');
  $maxfilesize = 50000;
  $maxfilewidth = 120;
  $maxfileheight = 120;
  $uploader = new XoopsMediaUploader('/home/xoops/uploads', $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight, $allowed_exts);
  if ($uploader->fetchMedia($_POST['uploade_file_name'])) {
    if (!$uploader->upload()) {
       echo $uploader->getErrors();
    } else {
       echo '<h4>File uploaded successfully!</h4>'
       echo 'Saved as: ' . $uploader->getSavedFileName() . '<br />';
       echo 'Full path: ' . $uploader->getSavedDestination();
    }
  } else {
    echo $uploader->getErrors();
  }

*/


class webphoto_lib_uploader
{
	var $mediaName;
	var $mediaType;
	var $mediaSize;
	var $mediaTmpName;
	var $mediaError;

	var $uploadDir = '';

	var $allowedMimeTypes  = array();
	var $allowedExtensions = array();

	var $maxFileSize = 0;
	var $maxWidth;
	var $maxHeight;

	var $targetFileName;

	var $prefix;

	var $errors = array();

	var $savedDestination;

	var $savedFileName;

// 2008-04-02
	var $mediaWidth  = 0;
	var $mediaHeight = 0;

// 2008-04-02
	var $errorCodes = array();

// 2008-04-02
	var $errorMsgs = array(
		1  => 'Uploaded File not found',
		2  => 'Invalid File Size',
		3  => 'Filename Is Empty',
		4  => 'No file uploaded',
		5  => 'Upload directory not set',
		6  => 'Extension not allowed',
		7  => 'Error occurred: Error #', // mediaError
		8  => 'Failed opening directory: ', // uploadDir
		9  => 'Failed opening directory with write permission: ', // uploadDir
		10 => 'MIME type not allowed: ', // mediaType
		11 => 'File size too large: ', // mediaSize
		12 => 'File width must be smaller than ', // maxWidth
		13 => 'File height must be smaller than ', // maxHeight
		14 => 'Failed uploading file: ', // mediaName
	);

// 2009-04-21
	var $_ini_safe_mode ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
	/**
	 * Constructor
	 * 
	 * @param   string  $uploadDir
	 * @param   array   $allowedMimeTypes
	 * @param   int	 $maxFileSize
	 * @param   int	 $maxWidth
	 * @param   int	 $maxHeight
	 * @param   int	 $cmodvalue
	 * @param   array   $allowedExtensions
	 **/
 	function webphoto_lib_uploader()
	{
		$this->_ini_safe_mode = ini_get('safe_mode');
	}

// added for webphoto
	function &getInstance()
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new webphoto_lib_uploader();
		}
		return $instance;
	}

//	function MyXoopsMediaUploader($uploadDir, $allowedMimeTypes, $maxFileSize, $maxWidth=null, $maxHeight=null, $allowedExtensions=null )
//	{
//		if (is_array($allowedMimeTypes)) {
//			$this->allowedMimeTypes =& $allowedMimeTypes;
//		}
//		$this->uploadDir = $uploadDir;
//		$this->maxFileSize = intval($maxFileSize);
//		if(isset($maxWidth)) {
//			$this->maxWidth = intval($maxWidth);
//		}
//		if(isset($maxHeight)) {
//			$this->maxHeight = intval($maxHeight);
//		}
//		if( isset( $allowedExtensions ) && is_array( $allowedExtensions ) ) {
//			$this->allowedExtensions =& $allowedExtensions ;
//		}
//	}

//---------------------------------------------------------
// functions
//---------------------------------------------------------
// added for webphoto
	function setUploadDir( $uploadDir )
	{
		$this->uploadDir = $uploadDir;
	}

// added for webphoto
	function setMaxFileSize( $maxFileSize )
	{
		$this->maxFileSize = intval($maxFileSize);
	}

// added for webphoto
	function setMaxWidth( $maxWidth )
	{
		$this->maxWidth = intval($maxWidth);
	}

// added for webphoto
	function setMaxHeight( $maxHeight )
	{
		$this->maxHeight = intval($maxHeight);
	}

// added for webphoto
	function setAllowedMimeTypes( $allowedMimeTypes )
	{
		if( isset( $allowedMimeTypes ) && is_array( $allowedMimeTypes ) ) {
			$this->allowedMimeTypes =& $allowedMimeTypes ;
		}
	}

// added for webphoto
	function setAllowedExtensions( $allowedExtensions )
	{
		if( isset( $allowedExtensions ) && is_array( $allowedExtensions ) ) {
			$this->allowedExtensions =& $allowedExtensions ;
		}
	}

	/**
	 * Fetch the uploaded file
	 * 
	 * @param   string  $media_name Name of the file field
	 * @param   int	 $index	  Index of the file (if more than one uploaded under that name)
	 * @return  bool
	 **/
	function fetchMedia($media_name, $index = null)
	{
// clear error
		$this->errors     = array();
		$this->errorCodes = array();

		if (!isset($_FILES[$media_name])) {

// 2008-04-02
//			$this->setErrors( 'File not found');
			$this->setErrorCodes(1);

			return false;

		} elseif (is_array($_FILES[$media_name]['name']) && isset($index)) {
			$index = intval($index);
			$this->mediaName = $_FILES[$media_name]['name'][$index];
			$this->mediaType = $_FILES[$media_name]['type'][$index];
			$this->mediaSize = $_FILES[$media_name]['size'][$index];
			$this->mediaTmpName = $_FILES[$media_name]['tmp_name'][$index];
			$this->mediaError = !empty($_FILES[$media_name]['error'][$index]) ? $_FILES[$media_name]['errir'][$index] : 0;

		} else {
			$media_name =& $_FILES[$media_name];
			$this->mediaName = $media_name['name'];
			$this->mediaType = $media_name['type'];
			$this->mediaSize = $media_name['size'];
			$this->mediaTmpName = $media_name['tmp_name'];
			$this->mediaError = !empty($media_name['error']) ? $media_name['error'] : 0;
		}

		if (intval($this->mediaSize) < 0) {

// 2008-04-02
//			$this->setErrors('Invalid File Size');
			$this->setErrorCodes(2);

			return false;
		}
		if ($this->mediaName == '') {

// 2008-04-02
//			$this->setErrors('Filename Is Empty');
			$this->setErrorCodes(3);

			return false;
		}

// 2008-04-02
// brought these sentences up, because not report media error  
		if ($this->mediaError > 0) {

// 2008-04-02
//			$this->setErrors('Error occurred: Error #'.$this->mediaError);
			$this->setErrorCodes(7, $this->mediaError);

			return false;
		}

		if ($this->mediaTmpName == 'none' || !is_uploaded_file($this->mediaTmpName) || $this->mediaSize == 0 ) {

// 2008-04-02
//			$this->setErrors('No file uploaded');
			$this->setErrorCodes(4);

			return false;
		}
		return true;
	}

	/**
	 * Set the target filename
	 * 
	 * @param   string  $value
	 **/
	function setTargetFileName($value){
		$this->targetFileName = strval(trim($value));
	}

	/**
	 * Set the prefix
	 * 
	 * @param   string  $value
	 **/
	function setPrefix($value){
		$this->prefix = strval(trim($value));
	}

	/**
	 * Get the uploaded filename
	 * 
	 * @return  string 
	 **/
	function getMediaName()
	{
		return $this->mediaName;
	}

	/**
	 * Get the type of the uploaded file
	 * 
	 * @return  string 
	 **/
	function getMediaType()
	{
		return $this->mediaType;
	}

	/**
	 * Get the size of the uploaded file
	 * 
	 * @return  int 
	 **/
	function getMediaSize()
	{
		return $this->mediaSize;
	}

	/**
	 * Get the temporary name that the uploaded file was stored under
	 * 
	 * @return  string 
	 **/
	function getMediaTmpName()
	{
		return $this->mediaTmpName;
	}

	/**
	 * Get the saved filename
	 * 
	 * @return  string 
	 **/
	function getSavedFileName(){
		return $this->savedFileName;
	}

	/**
	 * Get the destination the file is saved to
	 * 
	 * @return  string
	 **/
	function getSavedDestination(){
		return $this->savedDestination;
	}

// 2008-04-02
	function getMediaError() {
		return $this->mediaError;
	}

// 2008-04-02
	function getUploadDir() {
		return $this->uploadDir;
	}

// 2008-04-02
	function getMaxWidth() {
		return $this->maxWidth;
	}

// 2008-04-02
	function getMaxHeight() {
		return $this->maxHeight;
	}

// 2008-04-02
	function getMediaWidth() {
		return $this->mediaWidth;
	}

// 2008-04-02
	function getMediaHeight() {
		return $this->mediaHeight;
	}

	/**
	 * Check the file and copy it to the destination
	 * 
	 * @return  bool
	 **/
	function upload($chmod = 0644)
	{
		if ($this->uploadDir == '') {

// 2008-04-02
//			$this->setErrors('Upload directory not set');
			$this->setErrorCodes(5);

			return false;
		}
		if (!is_dir($this->uploadDir)) {

// 2008-04-02
//			$this->setErrors('Failed opening directory: '.$this->uploadDir);
			$this->setErrorCodes(8, $this->uploadDir);

			return false;
		}
		if (!is_writeable($this->uploadDir)) {

// 2008-04-02
//			$this->setErrors('Failed opening directory with write permission: '.$this->uploadDir);
			$this->setErrorCodes(9, $this->uploadDir);

			return false;
		}
		if (!$this->checkMimeType()) {

// 2008-04-02
//			$this->setErrors('MIME type not allowed: '.$this->mediaType);
			$this->setErrorCodes(10, $this->mediaType);

			return false;
		}
		if (!$this->checkExtension()) {

// 2008-04-02
//			$this->setErrors('Extension not allowed');
			$this->setErrorCodes(6);

			return false;
		}
		if (!$this->checkMaxFileSize()) {

// 2008-04-02
//			$this->setErrors('File size too large: '.$this->mediaSize);
			$this->setErrorCodes(11, $this->mediaSize);

		}
		if (!$this->checkMaxWidth()) {

// 2008-04-02
//			$this->setErrors(sprintf('File width must be smaller than %u', $this->maxWidth));
			$this->setErrorCodes(12, $this->maxWidth);

		}
		if (!$this->checkMaxHeight()) {

// 2008-04-02
//			$this->setErrors(sprintf('File height must be smaller than %u', $this->maxHeight));
			$this->setErrorCodes(13, $this->maxHeight);

		}
		if (count($this->errors) > 0) {
			return false;
		}
		if (!$this->_copyFile($chmod)) {

// 2008-04-02
//			$this->setErrors('Failed uploading file: '.$this->mediaName);
			$this->setErrorCodes(14, $this->mediaName);

			return false;
		}
		return true;
	}

	/**
	 * Copy the file to its destination
	 * 
	 * @return  bool 
	 **/
	function _copyFile($chmod)
	{
		$matched = array();
		if (!preg_match("/\.([a-zA-Z0-9]+)$/", $this->mediaName, $matched)) {
			return false;
		}
		if (isset($this->targetFileName)) {
			$this->savedFileName = $this->targetFileName;
		} elseif (isset($this->prefix)) {
			$this->savedFileName = uniqid($this->prefix).'.'.strtolower($matched[1]);
		} else {
			$this->savedFileName = strtolower($this->mediaName);
		}
		$this->savedDestination = $this->uploadDir.'/'.$this->savedFileName;
		if (!move_uploaded_file($this->mediaTmpName, $this->savedDestination)) {
			return false;
		}
		$this->chmod_file( $this->savedDestination, $chmod );
		return true;
	}

// 2009-04-21
	function chmod_file( $file, $mode )
	{
		if ( ! $this->_ini_safe_mode ) {
			chmod( $file, $mode );
		}
	}

	/**
	 * Is the file the right size?
	 * 
	 * @return  bool 
	 **/
	function checkMaxFileSize()
	{
		if ($this->mediaSize > $this->maxFileSize) {
			return false;
		}
		return true;
	}

	/**
	 * Is the picture the right width?
	 * 
	 * @return  bool 
	 **/
	function checkMaxWidth()
	{
		if (!isset($this->maxWidth)) {
			return true;
		}
		if (false !== $dimension = getimagesize($this->mediaTmpName)) {

// 2008-04-02
			$this->mediaWidth = $dimension[0];

			if ($this->mediaWidth > $this->maxWidth) {
				return false;
			}
		} else {
			trigger_error(sprintf('Failed fetching image size of %s, skipping max width check..', $this->mediaTmpName), E_USER_WARNING);
		}
		return true;
	}

	/**
	 * Is the picture the right height?
	 * 
	 * @return  bool 
	 **/
	function checkMaxHeight()
	{
		if (!isset($this->maxHeight)) {
			return true;
		}
		if (false !== $dimension = getimagesize($this->mediaTmpName)) {
			$dimension = @getimagesize($this->mediaTmpName);

// 2008-04-02
			$this->mediaHeight = $dimension[1];

			if ($this->mediaHeight > $this->maxHeight) {
				return false;
			}
		} else {
			trigger_error(sprintf('Failed fetching image size of %s, skipping max height check..', $this->mediaTmpName), E_USER_WARNING);
		}
		return true;
	}

	/**
	 * Is the file the right Mime type 
	 * 
	 * (is there a right type of mime? ;-)
	 * 
	 * @return  bool
	 **/
	function checkMimeType()
	{
		if (count($this->allowedMimeTypes) > 0 && !in_array($this->mediaType, $this->allowedMimeTypes)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Is the file the right extension
	 * 
	 * @return  bool
	 **/
	function checkExtension()
	{
		$ext = substr( strrchr( $this->mediaName , '.' ) , 1 ) ;
		if( ! empty( $this->allowedExtensions ) && ! in_array( strtolower( $ext ) , $this->allowedExtensions ) ) {
			return false;
		} else {
			return true;
		}
	}

// 2008-04-02
	function setErrorCodes($code, $msg=null)
	{
		$this->setErrors( $this->errorMsgs[$code] . $msg );
		$this->errorCodes[] = $code;
	}

// 2008-04-02
	function getErrorCodes()
	{
		return $this->errorCodes;
	}

	/**
	 * Add an error
	 * 
	 * @param   string  $error
	 **/
	function setErrors($error)
	{
		$this->errors[] = trim($error);
	}

	/**
	 * Get generated errors
	 * 
	 * @param	bool	$ashtml Format using HTML?
	 * 
	 * @return	array|string	Array of array messages OR HTML string
	 */
	function &getErrors($ashtml = true)
	{
		if (!$ashtml) {
			return $this->errors;
		} else {
			$ret = '';
			if (count($this->errors) > 0) {
				$ret = '<h4>Errors Returned While Uploading</h4>';
				foreach ($this->errors as $error) {
					$ret .= $error.'<br />';
				}
			}
			return $ret;
		}
	}

// --- class end ---
}

?>