<?php defined('SYSPATH') OR die('No direct script access.');

class File extends Krishna_File 
{
	/**
	 * 修正文件名
	 * @param string $filename
	 * @return string
	 */
	public static function trim_file_name($filename) 
	{
		// Remove path information and dots around the filename, to prevent uploading
		// into different directories or replacing hidden system files.
		// Also remove control characters and spaces (\x00..\x20) around the filename:
		$filename = trim(basename(stripslashes($filename)), ".\x00..\x20");
		
		// Use a timestamp for empty filenames:
		if (!$filename) 
		{
			$filename = str_replace('.', '-', microtime(true));
		}
		
		return $filename;
	}
	
	/**
	 * 获得唯一的文件名
	 * @param string $path 文件路径
	 * @return string
	 */
	public static function get_unique_filename($path)
	{
		$fi = pathinfo($path);
		$dirname = $fi['dirname'];// 目录名
		$basename = $fi['basename']; // 文件名
		
		while(is_file($dirname.'/'.$basename))
		{
			$basename = static::upcount_name($basename);
		}
	
		return $basename;
	}
	
	/**
	 * 递增文件名， 如 test.txt / test(1).txt / test(2).txt
	 * @param string $basename 文件名
	 * @return string
	 */
	public static function upcount_name($basename) 
	{
		return preg_replace_callback(
				'/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/',
				array('File', 'upcount_name_callback'),
				$basename,
				1
		);
	}
	
	/**
	 * 递增文件名称的回调： index++
	 * @param string $matches
	 * @return string
	 */
	public static function upcount_name_callback($matches)
	{
		$index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
		$ext = isset($matches[2]) ? $matches[2] : '';
		return ' ('.$index.')'.$ext;
	}
	
}
