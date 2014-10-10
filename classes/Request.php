<?php defined('SYSPATH') OR die('No direct script access.');

class Request extends Krishna_Request 
{
	/**
	 * 带query string的uri
	 * @return string
	 */
	public function uri_with_query()
	{
		return $this->uri().URL::query($this->query());
	}
	
	public function get_post_max_size()
	{
		return ini_get('post_max_size');
	}
}
