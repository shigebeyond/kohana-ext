<?php defined('SYSPATH') or die('No direct access allowed.');

class Curl
{
	/**
	 * 封装curl的调用接口，post的请求方式
	 * 
	 * @param string $url	url to request
	 * @param string $data	past data to post to $url
	 * @param int $timeout 
	 * @param array $headers_only	additional headers to send in the request
	 * @param bool $headers_only	flag to return only the headers
	 * @param array $curl_options	additional curl options to instantiate curl with
	 * @return json
	 */
	public static function post($url, $data = '', $timeout = 5, array $headers = array(), $headers_only = false, array $curl_options = array()) 
	{
		$json = self::http ( TRUE, $url, $data, TRUE, $timeout, $headers, $headers_only, $curl_options );
		return json_decode($json, TRUE);
	}
	
	
	/**
	 * 封装curl的调用接口，get的请求方式
	 * 
	 * @param string $url	url to request
	 * @param string $data	past data to post to $url
	 * @param int $timeout 
	 * @param array $headers_only	additional headers to send in the request
	 * @param bool $headers_only	flag to return only the headers
	 * @param array $curl_options	additional curl options to instantiate curl with
	 * @return json
	 */
	public static function get($url, $data = array(), $timeout = 5, array $headers = array(), $headers_only = false, array $curl_options = array()) 
	{
		$json = self::http ( FALSE, $url, $data, TRUE, $timeout, $headers, $headers_only, $curl_options );
		return json_decode($json, TRUE);

	}
	
	/**
	 * 封装curl的调用接口，适应各类请求，如get/post/https
	 * 
	 * @param bool post whether post request
	 * @param string $url	url to request
	 * @param string $data	past data to post to $url
	 * @param bool $ssl whether https request
	 * @param int $timeout 
	 * @param array $headers_only	additional headers to send in the request
	 * @param bool $headers_only	flag to return only the headers
	 * @param array $curl_options	additional curl options to instantiate curl with
	 */
	 public static function http($post, $url, $data = NULL, $ssl = FALSE, $timeout = 5, array $headers = array(), $headers_only = false, array $curl_options = array()) 
	 {
		if($url == "" || $timeout <= 0)
		{
			return false;
		}
		
		//构建get的url
		if(!$post AND !empty($data)) 
		{
			$url = $url . '?' . http_build_query($data);
		}
	
		//构建curl
		$con = curl_init($url);
		curl_setopt($con, CURLOPT_NOBODY, $headers_only);
		curl_setopt($con, CURLOPT_RETURNTRANSFER,TRUE);
		curl_setopt($con, CURLOPT_TIMEOUT, (int)$timeout);
		
		//设置post数据
		if ($post) 
		{
			curl_setopt($con, CURLOPT_POSTFIELDS, $data);
			curl_setopt($con, CURLOPT_POST, TRUE);
		}
		
		//设置https
		if ($ssl) 
		{
			curl_setopt($con, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
			curl_setopt($con, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
		}
		
		//Set any additional headers
		if(!empty($headers))
		{
			curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
		}
		
		//Set additional curl options
		if(!empty($curl_options))
		{
			foreach ($curl_options as $key => $value)
			{
				curl_setopt($con, $key, $value);
			}
		}
	
		return curl_exec($con);}


}