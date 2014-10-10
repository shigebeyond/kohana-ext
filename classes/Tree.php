<?php defined ( 'SYSPATH' ) or die ( 'No direct access allowed.' );

/**
 * 树的操作
 * 
 * @Package  
 * @category tree
 * @author shijianhang
 * @date Oct 24, 2013 5:08:44 PM 
 *
 */
class Tree 
{
	
	/** 树节点列表 */
	protected $_items;
	
	/** 键字段 */
	protected $_key_field;
	
	/** 值字段 */
	protected $_value_field;
	
	/** 孩子字段 */
	protected $_children_field;
	
	/**
	 * 构造函数
	 * 
	 * @param array $items
	 * @param string $key_field
	 * @param string $value_field
	 * @param string $children_field
	 */
	public function __construct(array $items, $key_field = 'key', $value_field = 'value', $children_field = 'children')
	{
		$this->_items = $items;
		$this->_key_field = $key_field;
		$this->_value_field = $value_field;
		$this->_children_field = $children_field;
	}
	
	/**
	 * 查询出列表
	 *
	 * @param string $indent 	 缩进用的字符
	 * @return array
	 */
	public function find_list($indent = NULL)
	{
		$list = array();
	
		// 遍历树
		foreach ($this->_items as $item)
		{
			// 从根节点开始生成列表
			$this->generate_list($list, $item, 1, $indent);
		}
	
		return $list;
	}
	
	/**
	 * 生成列表(递归)
	 *   
	 * @param array 	$list
	 * @param ORM_Node  $item		当前节点
	 * @param int 	 	$level		当前节点的层级
	 * @param string 	$indent 	缩进用的字符
	 */
	protected function generate_list(array &$list, array $item, $level, $indent = NULL)
	{
		// 添加列表项
		$list[$item[$this->_key_field]] = str_repeat($indent, $level - 1).$item[$this->_value_field];
	
		// 递归生成其孩子的列表
		if (!empty($item[$this->_children_field])) 
		{
			foreach ($item[$this->_children_field] as $child)
			{
				static::generate_list($list, $child, $level + 1, $indent);
			}
		}
	}

}