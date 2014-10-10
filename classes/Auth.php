<?php defined('SYSPATH') OR die('No direct access allowed.');

abstract class Auth extends Krishna_Auth 
{ 
	/**
	 * 获得当前登录用户的id
	 * @param string $default
	 * @return int
	 */
	public function get_user_id($default = NULL)
	{
		$user = $this->get_user($default);
		return $user ? $user->id : NULL;
	}
}