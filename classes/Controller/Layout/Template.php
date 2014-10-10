<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * 有布局模板的控制器
 *
 * @Package Controller/Layout
 * 
 * @category controller
 * @author shijianhang
 *         @date Oct 23, 2013 11:09:06 PM
 *        
 */
abstract class Controller_Layout_Template extends Controller_ACL
{
	/**
	 * @var boolean 记录用户的请求是否是Ajax
	 */
	protected $_ajax = FALSE;
	
	protected $_title = '';
	
	protected $_page_data = '';
	
	protected $_errors = NULL;
	
	protected $_template = 'template';
	
	protected $_filters = array ('login', 'acl');
	
	/**
	 *
	 * @var bool 是否post请求
	 */
	protected $_posted = NULL;
	
	public function __construct( Request $request, Response $response )
	{
		parent::__construct( $request, $response );
		
		// 判断是否是ajax请求?
		$this->_ajax = ($request->query('ajax') == 'true' OR $request->is_ajax());
	}

	/**
	 * 判断是否是ajax请求。如果是，发送适当的头信息给浏览器
	 *
	 * @see Krishna_Controller::before()
	 */
	public function before()
	{
		parent::before();
		
		if ($this->_ajax)
		{
			$this->request->headers ['Cache-Control'] = 'no-cache, must-revalidate';
			$this->request->headers ['Expires'] = 'Sun, 30 Jul 1989 19:30:00 GMT';
		}
		
		if (!$this->_ajax OR $this->request->query('modal')) 
		{
			// widget
			Media::widget('global', 'forms', 'modal');
		}
	}

	public function after()
	{
		if ($this->_ajax)
		{
			if ($this->request->query('modal') AND !$this->_valid_post()) // 先检查modal后检查post，因为有些请求不需要检查post，一旦检查就报错（如没有token）
			{
				$this->_build_modal_response();
			}
			else
			{
				$this->_build_ajax_response();
			} 
		}
		else
		{
			$this->_build_html_responese();
		}
	}

	/**
	 * 检查是否合法的post请求
	 * 用法:if ($this->_valid_post('upload_photo')) { .
	 * .. }
	 *
	 * @param string $submit Submit value [Optional]
	 * @return boolean Return TRUE if it's valid $_POST
	 */
	protected function _valid_post( $submit = 'submit' )
	{
		if ($this->_posted !== NULL)
		{
			return $this->_posted;
		}
		
		if ($this->request->method() != 'POST') 
		{
			return $this->_posted = FALSE;
		}
		
		if (Request::post_max_size_exceeded())
		{
			Message::error( '上传文件大小不能超过 ' . Request::get_post_max_size() . ' Bytes.' );
			return $this->_posted = FALSE;
		}
		
		if (! $this->_ajax && ! $this->request->post($submit)) 
		{
			Message::error( '表单已被修改，请重新提交.' );
			return $this->_posted = FALSE;
		}
		
		// 检查CSRF与表单重复提交
		if ($this->request->action() !== 'index' AND !Security::check($this->request->post(Security::$token_name))) 
		{
			Message::error( '表单已过期, 请勿再次提交.' );
			return $this->_posted = FALSE;
		} 
		
		return $this->_posted = TRUE;
	}

	protected function _build_html_responese()
	{
		$this->_template = View::factory( $this->_template );
		View::set_global( 'site', Krishna::$config->load( 'site' )->get( 'site' ) );
		View::set_global( 'title', $this->_title );
		$this->_template->content = $this->_page_data;
		View::set_global( 'messages', Message::display() );
		View::set_global( 'errors', Message::merge( $this->_errors ) );
		View::set_global( 'notices', Notice::display());
		
		$string = str_replace( "\t", '', $this->_template );
		// $string = preg_replace("/[\s]+/", ' ', str_replace("\n", '', $string));
		$this->response->body( $string );
	}
	
	protected function _build_modal_response()
	{
		$this->_template = View::factory( 'modal' );
		$this->_template->content = $this->_page_data;
		$string = str_replace( "\t", '', $this->_template );
		// $string = preg_replace("/[\s]+/", ' ', str_replace("\n", '', $string));
		$this->response->body( $string );
	}

	protected function _build_ajax_response()
	{
		// 如果page_data是View，则直接返回，否则返回json串
		if (! ($this->_page_data instanceof View))
		{
			$errors = empty($this->_errors) ? NULL : View::factory('errors/partial', array('errors' => Message::merge( $this->_errors )))->render();;
			$this->_page_data = $this->_build_json( $this->_page_data, Message::display(),  $errors);
		}
		
		$this->response->body( $this->_page_data );
	}

	protected function _build_json( $data = NULL, $message = NULL, $errors = NULL )
	{
		$result = array ();
		
		if ($data !== NULL)
		{
			$result ['data'] = $data;
		}
		
		if ($message !== NULL)
		{
			$result ['message'] = $message;
		}
		
		if ($errors !== NULL)
		{
			$result ['errors'] = $errors;
		}
		
		return json_encode( $result );
	}

	/**
	 * 重写父类方法execute()
	 *
	 * @return Response
	 */
	public function execute()
	{
		try
		{
			$response = parent::execute();
		}
		catch ( HTTP_Exception_401 $e )
		{
			// 用户未的登录
			$this->redirect( '/user/login' );
		}
		catch ( HTTP_Exception_403 $e )
		{
			// 用户没有权限
			Message::error( '您没有权限访问该页面:'.$e->getMessage() );
			$this->redirect( '/' );
		}
		/*
		 * catch (Exception $e) { //TODO: 其他异常 //显示异常 //记录 Krishna_Exception::log($e); }
		 */
		
		return $response;
	}
}
