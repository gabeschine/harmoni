<?php

require_once(dirname(__FILE__)."/ImageMagickProcessor.class.php");
//require_once(dirname(__FILE__)."/GDProcessor.class.php");

/**
 * Class for resizing of images
 * 
 * @package harmoni.image_processor
 * @version $Id: ImageProcessor.class.php,v 1.2 2004/11/06 02:18:13 adamfranco Exp $
 * @date $Date: 2004/11/06 02:18:13 $
 * @copyright 2004 Middlebury College
 */

class ImageProcessor {

	/**
	 * Constructor
	 * 
	 * @param string $thumbnailFormat The mime type of the format you which thumbnails
	 *			to be generated in. image/jpeg or image/png are recommended
	 * @param boolean $useGD If true, the GD image libraries will be used
	 *			to generate images of the formats that they support.
	 * @param array $gdFormats An array of mime-types to handle with the GD libraries.
	 * @param boolean $useImageMagick If true, the ImageMagick application will be
	 *			called via the shell to resize images that it supports.
	 * @param string $ImageMagickPath The path to the ImageMagick functions if
	 *			ImageMagick is availible.
	 * @param string $ImageMagickTempDir Directory in which to place images while
	 *			processing.
	 * @param array $ImageMagickFormats An array of mime-types to handle with ImageMagick.
	 * @return obj
	 * @access public
	 * @date 10/22/04
	 */
	function ImageProcessor ($thumbnailFormat, $useGD, $gdFormats, 
		$useImageMagick = FALSE, $ImageMagickPath = "/usr/bin", 
		$ImageMagickTempDir = "/tmp", $ImageMagickFormats = array()) 
	{
		ArgumentValidator::validate($thumbnailFormat, new StringValidatorRule);
		ArgumentValidator::validate($useGD, new BooleanValidatorRule);
		ArgumentValidator::validate($gdFormats, new ArrayValidatorRuleWithRule(new StringValidatorRule));
		ArgumentValidator::validate($useImageMagick, new BooleanValidatorRule);
		ArgumentValidator::validate($ImageMagickPath, new StringValidatorRule);
		ArgumentValidator::validate($ImageMagickTempDir, new StringValidatorRule);
		ArgumentValidator::validate($ImageMagickFormats, new ArrayValidatorRuleWithRule(new StringValidatorRule));
		
		$this->_thumbnailFormat = $thumbnailFormat;
		$this->_useGD = $useGD;
		$this->_gdFormats = $gdFormats;
		$this->_useImageMagick = $useImageMagick;
		$this->_ImageMagickPath = $ImageMagickPath;
		$this->_ImageMagickTempDir = $ImageMagickTempDir;
		$this->_ImageMagickFormats = $ImageMagickFormats;
		
		if ($this->_useGD)
			$this->_gdProcessor =& new GDProcessor($thumbnailFormat);
		if ($this->_useImageMagick)
			$this->_ImageMagickProcessor =& new ImageMagickProcessor(
						$thumbnailFormat, $this->_ImageMagickPath, 
						$this->_ImageMagickTempDir);
	}
	
	/**
	 * Return true if the image format is supported.
	 * 
	 * @param string $format The mime type of the format in question.
	 * @return boolean
	 * @access public
	 * @date 10/22/04
	 */
	function isFormatSupported ( $format ) {
		ArgumentValidator::validate($format, new StringValidatorRule);
		
		if (in_array($format, $this->_gdFormats) 
			|| in_array($format, $this->_ImageMagickFormats)
			|| ($this->_useImageMagick
				&& $this->_ImageMagickProcessor->isSupported($format)))
		{
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Generate a thumbnail from the image format/data passed
	 * 
	 * @param string $format The format of the source image
	 * @param string $data	The data of the source image
	 * @return string The thumbnail data of $thumbnailFormat mime type.
	 * @access public
	 * @date 10/22/04
	 */
	function generateThumbnailData ($format, $data) {
		if (in_array($format, $this->_gdFormats))
			return $this->_gdProcessor->generateThumbnailData($format, $data);
		if (in_array($format, $this->_ImageMagickFormats))
			return $this->_ImageMagickProcessor->generateThumbnailData($format, $data);
		if ($this->_useImageMagick
			&& $this->_ImageMagickProcessor->isSupported($format))
			return $this->_ImageMagickProcessor->generateThumbnailData($format, $data);
		
		throwError(new Error("Unsupported Format, '$format'.", "ImageProcessor", true));
	}
	
	/**
	 * Return the format that thumbnails will be generated in.
	 * 
	 * @return string
	 * @access public
	 * @date 10/22/04
	 */
	function getThumbnailFormat () {
		return $this->_thumbnailFormat;
	}
	
	/**
	 * Get the format that a resized image will be returned in
	 * 
	 * @param string $format The format of the source image
	 * @return string The mime type.
	 * @access public
	 * @date 10/22/04
	 */
	function getResizedFormat ($format) {
		if (in_array($format, $this->_gdFormats))
			return $this->_gdProcessor->getResizedFormat($format);
		if (in_array($format, $this->_ImageMagickFormats))
			return $this->_ImageMagickProcessor->getResizedFormat($format);
		if ($this->_useImageMagick
			&& $this->_ImageMagickProcessor->isSupported($format))
			return $this->_ImageMagickProcessor->getResizedFormat($format);
		
		throwError(new Error("Unsupported Format, '$format'.", "ImageProcessor", true));
	}
	
	/**
	 * Generate a resized image from the image format/data passed
	 * 
	 * @param string $format The format of the source image
	 * @param string $data	The data of the source image
	 * @return string The thumbnail data of a mime type that can be obtained with
	 *		getResizedFormat.
	 * @access public
	 * @date 10/22/04
	 */
	function getResizedData ($format, $size, $data) {
		if (in_array($format, $this->_gdFormats))
			return $this->_gdProcessor->getResizedData($format, $size, $data);
		if (in_array($format, $this->_ImageMagickFormats))
			return $this->_ImageMagickProcessor->getResizedData($format, $size, $data);
		if ($this->_useImageMagick
			&& $this->_ImageMagickProcessor->isSupported($format))
			return $this->_ImageMagickProcessor->getResizedData($format, $size, $data);
		
		throwError(new Error("Unsupported Format, '$format'.", "ImageProcessor", true));
	}
	
	/**
	 * Get the format that a resized image will be returned in
	 * 
	 * @param string $format The format of the source image
	 * @return string The mime type.
	 * @access public
	 * @date 10/22/04
	 */
	function getWebsafeFormat ($format) {
		if (in_array($format, $this->_gdFormats))
			return $this->_gdProcessor->getWebsafeFormat($format);
		if (in_array($format, $this->_ImageMagickFormats))
			return $this->_ImageMagickProcessor->getWebsafeFormat($format);
		if ($this->_useImageMagick
			&& $this->_ImageMagickProcessor->isSupported($format))
			return $this->_ImageMagickProcessor->getWebsafeFormat($format);
		
		throwError(new Error("Unsupported Format, '$format'.", "ImageProcessor", true));
	}
	
	/**
	 * Generate a resized image from the image format/data passed
	 * 
	 * @param string $format The format of the source image
	 * @param string $data	The data of the source image
	 * @return string The thumbnail data of a mime type that can be obtained with
	 *		getResizedFormat.
	 * @access public
	 * @date 10/22/04
	 */
	function getWebsafeData ($format, $size, $data) {
		if (in_array($format, $this->_gdFormats))
			return $this->_gdProcessor->getWebsafeData($format, $size, $data);
		if (in_array($format, $this->_ImageMagickFormats))
			return $this->_ImageMagickProcessor->getWebsafeData($format, $size, $data);
		if ($this->_useImageMagick
			&& $this->_ImageMagickProcessor->isSupported($format))
			return $this->_ImageMagickProcessor->getWebsafeData($format, $size, $data);
		
		throwError(new Error("Unsupported Format, '$format'.", "ImageProcessor", true));
	}	
		
	/**
	 * Start the service
	 * 
	 * @return void
	 * @access public
	 * @date 6/28/04
	 */
	function start () {
		
	}
	
	/**
	 * Stop the service
	 * 
	 * @return void
	 * @access public
	 * @date 6/28/04
	 */
	function stop () {
		
	}
	
}

?>