<?php defined('SYSPATH') OR die('No direct script access.');

class Arr extends Krishna_Arr 
{
	/**
	 * 将model数组转换为option列表
	 *
	 * @param array $items
	 * @param string $name_field
	 * @param string $value_field
	 * @return array
	 */
	public static function as_list(/* array */ $items, $name_field = 'name', $value_field = 'id')
	{
		$result = array();
	
		foreach ($items as $item)
		{
			$result[$item->$value_field] = $item->$name_field;
		}
	
		return $result;
	}
	
	/**
	 * 获得model数组的字段值数组
	 * 
	 * @param array $items
	 * @param string $field
	 * @param bool $unique 是否唯一
	 * @return array
	 */
	public static function pluck_field($items, $field, $unique = FALSE)
	{
		$values = array();
	
		foreach ($items as $item)
		{
			if (isset($item->$field))
			{
				// Found a value in this item
				$values[] = $item->$field;
			}
		}
	
		if ($unique) 
		{
			return array_unique($values);
		}
		
		return $values;
	}
	
	/**
	 * 获得model数组的方法值数组
	 * 
	 * @param array $items
	 * @param string $method
	 * @param bool $unique 是否唯一
	 * @return array
	 */
	public static function pluck_method(array $items, $method, $unique = FALSE)
	{
		$values = array();
	
		foreach ($items as $item)
		{
			// Found a value in this item
			$values[] = $item->$method();
		}
	
		if ($unique) 
		{
			return array_unique($values);
		}
		
		return $values;
	}
}
