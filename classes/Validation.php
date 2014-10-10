<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * 有缓存结果的校验器
 *    主要是针对这样的情况：两次调用Validation::check()之间发生了一些改变校验结果的事情
 *    如在保存上传文件前进行一次Upload::not_empty()的校验，在保存之后再进行一次Upload::not_empty()的校验，则两次结果可能是不一致的，因为在保存上传文件时，$file['tmp_name']被移动到新的位置，因此可能出现第一次校验成功，第二次校验失败
 *    
 * @Package  
 * @category validation
 * @author shijianhang
 * @date Oct 23, 2013 11:04:38 PM 
 *
 */
class Validation extends Krishna_Validation 
{
	/**
	 * 校验的标记数：0 未验证 1 验证成功 -1 验证失败
	 * @var int
	 */
	protected $_valid = 0;
	
	/**
	 * 执行所有的校验规则
	 *    跟父类方法的区别：缓存校验结果，第一次调用时执行校验规则，并缓存结果，第二次直接从缓存中取得结果
	 *
	 * @return  boolean
	 */
	public function check()
	{
		if ($this->_valid == 0) 
		{
			$result = parent::check();
			$this->_valid = $result ? 1 : -1;
		}
		return $this->_valid > 0;
	}
	
	/**
	 * 合并校验器
	 *    主要针对这样的情况：对同一个数据进行了多次的校验，每次校验都是独立互不影响（如每次校验都是针对同一个数据的不同属性来校验的），但是最后需要汇总校验结果，这样就需要一个合并的校验器
	 *    如对上传多个文件的情况，每个文件的对应一个对象属性，每个对象属性逻辑也不一样，这样就要求对每个上传文件单独处理（包含校验处理），这样会导致有的文件上传成功有的上传失败，最后合并全部文件的上传结果
	 * @param Validation $v1
	 * @param Validation $v2
	 * @return NULL|Validation
	 */
	public static function merge($v1, $v2)
	{
		//如果参数为空
		if ($v2 == NULL)
		{
			return $v1;
		}
		
		if ($v1 == NULL) 
		{
			return $v2;
		}
		
		//如果数据不一致，则放弃合并
		if ($v1->_data !== $v2->_data) 
		{
			return NULL;
		}
		
		//合并数据
		$result = Validation::factory($v1->_data);
		
		//合并rule
		$result->_rules = array_merge($v1->_rules, $v2->_rules);
		
		//合并label
		$result->_labels = array_merge($v1->_labels, $v2->_labels);
		
		//合并errors
		$result->_errors = array_merge($v1->_errors, $v2->_errors);
		
		//合并_valid
		if ($v1->_valid == 0 ||  $v2->_valid == 0) 
		{
			$result->_valid = 0;
		}
		else if ($v1->_valid < 0 ||  $v2->_valid < 0) 
		{
			$result->_valid = -1;
		}
		else 
		{
			$result->_valid = 1;
		}
		
		return $result;
	}
}
