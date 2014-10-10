<?php defined('SYSPATH') OR die('No direct script access.');

class Form extends Krishna_Form 
{
	// TODO: 支持date等复杂的控件,请参照cl4的控件体系 CL4_Form
	
	public static function open($action = NULL, array $attributes = NULL)
	{
		// 表单头
		$result = parent::open($action, $attributes);
		
		// token的隐藏域
		if (Request::current()->action() !== 'index') 
		{
			$result .= Form::hidden(Security::$token_name, Security::token(TRUE));
		}
		
		return $result;
	}
	
	/**
	 * 生成下拉框: 支持 枚举作为选项列表+在开头插入空的选项
	 * 
	 * @param   string  $name       input name
	 * @param   array   $options    available options
	 * @param   mixed   $selected   selected option string, or an array of selected options
	 * @param   array   $attributes html attributes
	 * @return  string
	 * @uses    HTML::attributes
	 */
	public static function select($name, array $options = NULL, $selected = NULL, array $attributes = NULL)
	{
		if ($options === NULL) 
		{
			$options = array();
		}
		
		// 在开头插入空的选项
		$options = Arr::unshift($options, NULL, '请选择');
		
		return parent::select($name, $options, $selected, $attributes);
	}
}
