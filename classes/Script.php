<?php defined ( 'SYSPATH' ) or die ( 'No direct access allowed.' );

/**
 * 脚本执行与表达式求值
 * 
 * @Package  
 * @category script
 * @author shijianhang
 * @date Oct 24, 2013 5:08:44 PM 
 *
 */
class Script 
{
	/**
	 * 脚本执行
	 * 
	 * @param string $script
	 * @param array $context
	 * @throws Script_Exception
	 */
	public static function execute($script, array $context = array())
	{
		if (empty($script)) 
		{
			return;
		}
		
		//构建上下文: 将上下文的变量分解成为独立变量
		//extract($context, EXTR_OVERWRITE);
		foreach ($context as $key => $value)
		{
			$$key = $value;
		}
		
		try {
			//处理输出
			ob_start();
			
			//执行脚本
			eval( $script );
			
			ob_end_flush();
		}
		catch (Exception $e)
		{
			throw new Script_Exception('Fail to execute script <br/><p>:script</p>', array(':script' => $script), 0, $e);
		}
	}
	
	/**
	 * 校验脚本
	 *    TODO: 校验规则
	 *    
	 * @param string $script
	 * @return boolean
	 */
	public static function valid_script($script)
	{
		return TRUE;
	}
	
	/**
	 * 表达式求值
	 * 
	 * @param string $expr
	 * @param array $context
	 * @throws Script_Exception
	 * @return unknown
	 */
	public static function evaluate($expr, array $context = array())
	{
		if (empty($expr))
		{
			return TRUE;
		}
		
		//构建上下文
		foreach ($context as $key => $value)
		{
			$$key = $value;
		}
		
		try {
			//声明变量来记录表达式的结果
			$script = '$result = ' . $expr . ';';
			
			//执行脚本
			eval( $script );
			
			//返回结果
			return $result;
		}
		catch (Exception $e)
		{
			throw new Script_Exception('Fail to evaluate expression :script', array(':script' => $script), 0, $e);
		}
	}
	
	/**
	 * 校验表达式
	 *    TODO: 校验规则, 如没有分号
	 * 
	 * @param string $expr
	 * @return boolean
	 */
	public static function valid_expr($expr)
	{
		return TRUE;
	}
}

/**
 * 脚本执行的异常
 * @Package package_name 
 * @category 
 * @author shijianhang
 * @date Oct 24, 2013 5:18:18 PM 
 *
 */
class Script_Exception extends Krishna_Exception {}