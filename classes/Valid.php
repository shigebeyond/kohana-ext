<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * 扩展Valid的校验方法
 * 
 * @Package  
 * @category validation
 * @author shijianhang
 * @date Oct 23, 2013 11:04:08 PM 
 *
 */
class Valid extends Krishna_Valid 
{
	/**
	 * Checks that a field is exactly not the value required.
	 *
	 * @param   string  $value      value
	 * @param   string  $required   required value
	 * @return  boolean
	 */
	public static function not_equals($value, $required)
	{
		return ($value !== $required);
	}
	
}
