<?php defined('SYSPATH') or die('No direct script access.');

/**
 * 修复分页插件的bug
 *    环境：路由为(<controller>(/<action>(/<id>)))
 *    bug：方法url()不能返回原始的url
 *    原因：属性_route_params中没有包含controller与action信息
 *    解决：将controller与action放入属性_route_params中
 *    
 * @Package  
 * @category pagination
 * @author shijianhang
 * @date Oct 23, 2013 11:03:21 PM 
 *
 */
class Pagination extends Krishna_Pagination 
{
	/**
	 * Creates a new Pagination object.
	 *
	 * @param   array  configuration
	 * @return  void
	 */
	public function __construct(array $config = array(), Request $request = NULL)
	{
		// Overwrite system defaults with application defaults
		$this->config = $this->config_group() + $this->config; //合并配置项
	
		// Assing Request
		if ($request === NULL)
		{
			$request = Request::current();
		}
	
		$this->_request = $request;
	
		// Assign default Route
		$this->_route = $request->route();
	
		// Assign default route params
		// 将controller与action放入属性_route_params中
		$this->_route_params = array_merge($request->param(), array('controller' => $request->controller(), 'action' => $request->action()));
	
		// Pagination setup
		$this->setup($config);
	}
}