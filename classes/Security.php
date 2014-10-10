<?php defined('SYSPATH') OR die('No direct script access.');

class Security extends Krishna_Security 
{
	/************************* token生成与检查 ***************************/
	// 过期时间
	const EXPIRED_PERIOD = 3600;
	
	/**
	 * Gets a new token and appends the creation time to the end.
	 * 
	 * @param bool $new
	 * @return string
	 */
	public static function token($new = FALSE)
	{
		$token = arr::get(static::generate_token(), 'token');
	
		return $token.'-'.time();
	}
	
	/**
	 * Checks if a token is valid. Used in conjunction with the Validation
	 * library.
	 *
	 * @access  public
	 * @param   string   token to validate
	 * @return  boolean
	 */
	public static function check($token)
	{
		return static::validate_token($token);
	}
	
	/**
	 * Generates a new token and saves it to the session.
	 *
	 * @access  protected
	 * @return  array
	 */
	protected static function generate_token()
	{
		$tokens = Session::instance()->get('tokens', array());
	
		// Remove expired tokens
		$tokens = static::clean_tokens($tokens);
	
		// Only store 5 tokens at a time.
		if (count($tokens) >= 5)
		{
			$tokens = array_values(array_slice($tokens, 0, 5, TRUE));
		}
	
		$token = array
		(
				'ts' => time(),
				'token' => sha1(uniqid(rand(), TRUE)),
		);
	
		$tokens[] = $token;
		Session::instance()->set('tokens', $tokens);
	
		return $token;
	}
	
	/**
	 * Removes expired tokens from the session.
	 *
	 * @access  protected
	 * @param   array      tokens currently in session
	 * @return  array
	 */
	protected static function clean_tokens($tokens)
	{
		$time = time();
	
		foreach (array_keys($tokens) as $key)
		{
			if ($tokens[$key]['ts'] > $time + 86400)
			{
				unset($tokens[$key]);
			}
		}
	
		return $tokens;
	}
	
	/**
	 * Validates a token against tokens stored in session. If the difference
	 * between the time generated and current time is 0 or greater than 30
	 * seconds it fails. If a matching token isn't found it session it also
	 * fails.
	 *
	 * @access  protected
	 * @param   string     token to validate
	 * @return  boolean
	 */
	protected static function validate_token($token)
	{
		$split_token = explode('-', $token);
		$token = $split_token[0];
		$time = abs($split_token[1]);
	
		$diff = (time() - $time);
	
		if ($diff AND ($diff <= static::EXPIRED_PERIOD))
		{
			$tokens = Session::instance()->get('tokens', array());
				
			if ( ! is_array($tokens))
				return FALSE;
				
			foreach (array_keys($tokens) as $key)
			{
				if ($tokens[$key]['token'] == $token)
				{
					return TRUE;
				}
			}
		}
	
		return FALSE;
	}
	
	/************************* 其他 ***************************/
	/**
	 * 清理xss
	 *
	 * @param string $str
	 * @return string
	 */
	public function xss_clean($str)
	{
		// strip_tags() 函数剥去 HTML、XML 以及 PHP 的标签。
		// htmlspecialchars() 函数把一些预定义的字符转换为 HTML 实体
		$str = trim(htmlentities(strip_tags($str, ",")));
	
		if (get_magic_quotes_gpc())
		{
			// addslashes() 函数在指定的预定义字符前添加反斜杠。
			$str = stripslashes($str);
		}
	
		//$input_data = mysql_real_escape_string($input_data);
		return $str;
	}
	
}
