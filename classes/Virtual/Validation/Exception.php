<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * 虛擬的校驗異常
 *
 * @package    Krishna/ORM
 * @author     Krishna Team
 * @copyright  (c) 2008-2009 Krishna Team
 * @license    http://krishnaphp.com/license
 */
class Virtual_Validation_Exception extends Krishna_Exception 
{
	/** 校验器 */
	protected $_validation;
	
	/**
	 * 构造函数
	 * @param string $message
	 * @param number $code
	 * @param Exception $previous
	 */
	public function __construct($message = "", $code = 0, Exception $previous = NULL)
	{
		if ($message instanceof Validation)
		{
			$this->_validation = $message;
			$message = "";
		}
		
		// Pass the message and integer code to the parent
		Exception::__construct($message, (int) $code, $previous);
	
		// Save the unmodified code
		// @link http://bugs.php.net/39615
		$this->code = $code;
	}
	
	public function errors($directory = NULL, $translate = TRUE)
	{
		// 输出校验错误
		if ($this->_validation) 
		{
			return $this->_validation->errors(NULL, $translate);
		}
		
		// 输出普通错误（字符串转数组）
		return (array) $this->getMessage();
	}
}
