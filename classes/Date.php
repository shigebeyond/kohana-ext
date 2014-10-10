<?php defined('SYSPATH') OR die('No direct script access.');

class Date extends Krishna_Date 
{
	public static $datestamp_format = 'Y-m-d';
	
	/**
	 * 获得当前时间
	 * @param bool $timed 是否时间格式， 否则是日期格式
	 * @return string
	 */
	public static function now($timed = TRUE)
	{
		return date($timed ? static::$timestamp_format : static::$datestamp_format);
	}
	
	/**
	 * 计算到期时间
	 * 
	 * @param string $delay 延时
	 * @param Model $item 业务对象
	 * @param string $time_unit 时间单位, 如minutes/days
	 * @return NULL|string
	 */
	public static function calculate_deadline($delay, $item, $time_unit = 'days')
	{
		if (empty($delay))
		{
			return NULL;
		}
		
		if (!is_numeric($delay)) // 非数字则公式
		{
			$delay = Script::evaluate($delay, array('item' => $item)); // 计算公式
		}
		
		return Date::adjust_datetime(time(), "+$delay $time_unit");
	}
	
	/**
	 * adjust a date value by a specified number of units (days, weeks or months).
	 * @param string $date
	 * @param string $adjustment
	 * @param string $units
	 * @return string
	 */
 	/* public static function adjustDate ($date, $adjustment, $units='days')
	{
		$dateobj =& RDCsingleton::getInstance('date_class');

		switch (strtolower($units)) {
			case 'days':
				$out_date = $dateobj->addDays($date, $adjustment);
				break;

			case 'weeks':
				$out_date = $dateobj->addWeeks($date, $adjustment);
				break;

			case 'months':
				$out_date = $dateobj->addMonths($date, $adjustment);
				break;

			default:
				throw new Krishna_Exception('');
				break;
		} // switch

		return $out_date;

	} // adjust_date  */

	/**
	 * adjust a date/time value by a specified amount.
	 * @param string $datetime
	 * @param string $adjustment
	 * @return string
	 */
	public static function adjust_datetime ($datetime, $adjustment)
	{
		if (is_string($datetime)) {
			// remove any internal dashes and colons
			$time = str_replace('-:', '', $datetime);
			// convert time into a unix timestamp
			$time1 = mktime(substr($time,0,2), substr($time,2,2), 0, 2, 2, 2005);
		} else {
			$time1 = $datetime;
		} // if

		// make the adjustment
		$new1 = strtotime($adjustment, $time1);
		// convert unix timstamp into display format
		$new2 = date('Y-m-d H:i:s', $new1);

		return $new2;

	} // adjust_datetime
	
	/**
	 * adjust a time value by a specified amount.
	 * @param string $time
	 * @param string $adjustment
	 * @return string
	 */
	public static function adjust_time ($time, $adjustment)
	{
		// remove any internal colons
		$time = str_replace(':', '', $time);
		// convert time into a unix timestamp
		$time1 = mktime(substr($time,0,2), substr($time,2,2), 0, 2, 2, 2005);
		// make the adjustment
		$new1 = strtotime($adjustment, $time1);
		// convert unix timstamp into display format
		$new2 = date('H:i:s', $new1);

		return $new2;

	} // adjust_time
	
}
