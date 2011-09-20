<?php

/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
* (c) Juan Jaramillo <juan.jaramillo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/




namespace Altumo\Utils\sfDate;


/**
 *
 * sfDate class.
 *
 * A class for representing a date/time value as an object.
 *
 * This class allows for chainable calculations using the sfTime utility class.
 *
 * @package	sfDateTimePlugin
 * @author	Stephen Riesenberg <sjohnr@gmail.com>
 * @author	Jerome Etienne <jerome.etienne@gmail.com>
 * @version	SVN: $Id$
 */
class sfDate
{
	/**
	 * The timestamp for this sfDate instance.
	 */
	private $ts = null;
	
	/**
	 * The original timestamp for this sfDate instance.
	 */
	private $init = null;
	
	/**
	 * Retrieves a new instance of this class.
	 *
	 * NOTE: This is not the singleton pattern. Instead, it is for chainability ease-of-use.
	 *
	 * <b>Example:</b>
	 * <code>
	 *   echo sfDate::getInstance()->getFirstDayOfWeek()->addDay()->format('%Y-%m-%d');
	 * </code>
	 *
	 * @param	mixed	timestamp, string, or sfDate object
	 * @return	sfDate
	 */
	public static function getInstance($value = null)
	{
		return new sfDate($value);
	}
	
	/**
	 * Construct an sfDate object.
	 *
	 * @param	mixed	timestamp, string, or sfDate object
	 */
	public function __construct($value = null)
	{
		$this->set($value);
	}
	
	/**
	 * Clone this object
	 *
	 * @return sfDate a copy of this object
	*/
	public function copy()
	{
		// TODO should that be a clone $this ?
		// - what about the $this->init ?
		// - jme- this is a modification
		return new sfDate($this);
	}
	
	/**
	 * Format the date according to the <code>date</code> function.
	 *
	 * @return	string
	 */
	public function format($format)
	{
		return date($format, $this->ts);
	}
		
	/**
	 * Formats the date according to the <code>format_date</code> helper of the Date helper group.
	 *
	 * @return	string
	 */
	public function date($format = 'd')
	{
		sfLoader::loadHelpers('Date');
		
		return format_date($this->ts, $format);
	}
	
	/**
	 * Formats the date according to the <code>format_datetime</code> helper of the Date helper group.
	 *
	 * @return	string
	 */
	public function datetime($format = 'F')
	{
		sfLoader::loadHelpers('Date');
		
		return format_datetime($this->ts, $format);
	}
	
	/**
	 * Format the date as a datetime value.
	 *
	 * @return	string
	 */
	public function dump()
	{
		// jme- do something to get it configurable via .yml somewhere
		return date('Y-m-d H:i:s', $this->ts);
	}

/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
//		all the sfDate::format_*() function
/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * return a string representation of the sfDate for human (only hours)
	 * 
	 * - the format may be configured in sfConfig app_sfDateTimePlugin_format_hour_human
	 * - if not present, it default to 'H:i:s'
	 * 
	 * - jme- this is a modification
	 * 
	 * @return	string
	 */
	public function format_hour()
	{
		// get the format from sfConfig if any, else default to "H:i:s"
		$format	= sfConfig::get('app_sfDateTimePlugin_format_hour_human', "H:i:s");
		// build the string and return it
		return date($format, $this->ts);
	}
	
	/**
	 * return a string representation of the sfDate for human
	 * 
	 * - the format may be configured in sfConfig app_sfDateTimePlugin_format_human
	 * - if not present, it default to '%Y-%m-%d'
	 * 
	 * - jme- this is a modification
	 * 
	 * @return	string
	 */
	public function format_human()
	{
		// get the format from sfConfig if any, else default to "%Y-%m-%d"
		$format	= sfConfig::get('app_sfDateTimePlugin_format_human', "%Y-%m-%d");
		// build the string and return it
		return $this->format_internal($format);
	}

	/**
	 * return a string representation of the sfDate for database
	 * 
	 * - the format may be configured in sfConfig app_sfDateTimePlugin_format_database
	 * - if not present, it default to '%Y-%m-%d'
	 * 
	 * - jme- this is a modification
	 * 
	 * @return	string
	 */
	public function format_database()
	{
		// get the format from sfConfig if any, else default to "%Y-%m-%d"
		$format	= sfConfig::get('app_sfDateTimePlugin_format_database', "%Y-%m-%d");
		// build the string and return it
		return $this->format_internal($format);
	}

	/**
	 * return a string representation of the sfDate for system
	 * 
	 * - the format may be configured in sfConfig app_sfDateTimePlugin_format_database
	 * - if not present, it default to '%Y-%m-%d'
	 * 
	 * @return	string
	 */
	public function format_system()
	{
		// get the format from sfConfig if any, else default to "%Y-%m-%d"
		$format	= sfConfig::get('app_sfDateTimePlugin_format_system', "%Y-%m-%d");
		// build the string and return it
		return $this->format_internal($format);
	}
	
	/**
	 * Internal formating of this object to a string according to $format_str
	*/
	private function format_internal($format_str)
	{
		// build the string and return it
		return strftime($format_str, $this->ts);
	}

/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
//		bunch of alias
/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * Alias of ->format_system()
	 */
	public function to_system()	{ return $this->format_system();	}
	/**
	 * Alias of ->format_database()
	 */
	public function to_database()	{ return $this->format_database();	}
	/**
	 * Alias of ->format_human()
	 */
	public function to_human()	{ return $this->format_human();		}
	
	public function o2s()		{ return $this->to_system();		}
	public function o2d()		{ return $this->to_database();		}
	public function o2h()		{ return $this->to_human();		}
	

	public static function s2o($from) { return sfDate::from_system($from);		}
	public static function d2o($from) { return sfDate::from_database($from);	}
	public static function h2o($from) { return sfDate::from_human($from);		}
	
	public static function s2h($from) { return empty($from)? $from : sfDate::from_system($from)->to_human();	}
	public static function s2d($from) { return empty($from)? $from : sfDate::from_system($from)->to_database();	}
	public static function d2s($from) { return empty($from)? $from : sfDate::from_database($from)->to_system();	}
	public static function d2h($from) { return empty($from)? $from : sfDate::from_database($from)->to_human();	}
	public static function h2s($from) { return empty($from)? $from : sfDate::from_human($from)->to_system();	}
	public static function h2d($from) { return empty($from)? $from : sfDate::from_human($from)->to_database();	}
	

/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
//		all the sfDate::from_*() function
/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * return a sfDate from a string formated for "system"
	 * 
	 * - the format may be configured in sfConfig app_sfDateTimePlugin_format_system
	 * - if not present, it default to '%Y-%m-%d'
	 * 
	 * @return	sfDate
	 */
	private static function from_internal($date_str, $format)
	{
		// log to debug
		//ezDbg::err("myformat=".$format." and date_str=".$date_str);
		// parse $date_str according to $format
		$arr	= strptime($date_str, $format);
		ezDbg::assert($arr !== false);
		// convert it to a UNIX timestamp
		$ts	= mktime($arr['tm_hour'], $arr['tm_min'], $arr['tm_sec'], $arr['tm_mon']+1
						, $arr['tm_mday'], $arr['tm_year'] + 1900);
		// return a sfDate
		return new sfDate($ts);
	}	

	/**
	 * return a sfDate from a string formated for "system"
	 * 
	 * - the format may be configured in sfConfig app_sfDateTimePlugin_format_system
	 * - if not present, it default to '%Y-%m-%d'
	 * 
	 * @return	sfDate
	 */
	public static function from_system($date_str)
	{
		// get the format from sfConfig if any, else default to "%Y-%m-%d"
		$format	= sfConfig::get('app_sfDateTimePlugin_format_system', "%Y-%m-%d");
		// return a sfDate
		return self::from_internal($date_str, $format);
	}
	
	/**
	 * return a sfDate from a string formated for "database"
	 * 
	 * - the format may be configured in sfConfig app_sfDateTimePlugin_format_database
	 * - if not present, it default to '%Y-%m-%d'
	 * 
	 * @return	sfDate
	 */
	public static function from_database($date_str)
	{
		// get the format from sfConfig if any, else default to "%Y-%m-%d"
		$format	= sfConfig::get('app_sfDateTimePlugin_format_database', "%Y-%m-%d");
		// return a sfDate
		return self::from_internal($date_str, $format);
	}

	/**
	 * return a sfDate from a string formated for "human"
	 * 
	 * - the format may be configured in sfConfig app_sfDateTimePlugin_format_human
	 * - if not present, it default to '%Y-%m-%d'
	 * 
	 * @return	sfDate
	 */
	public static function from_human($date_str)
	{
		// get the format from sfConfig if any, else default to "%Y-%m-%d"
		$format	= sfConfig::get('app_sfDateTimePlugin_format_human', "%Y-%m-%d");
		// return a sfDate
		return self::from_internal($date_str, $format);
	}

	/**
	 * Retrieves the given unit of time from the timestamp.
	 *
	 * @param	int	unit of time (accepts sfTime constants).
	 * @return	int	the unit of time
	 *
	 * @throws	sfDateTimeException
	 */
	public function retrieve($unit = sfTime::DAY)
	{
		switch ($unit)
		{
			case sfTime::SECOND:
				return date('s', $this->ts);
			case sfTime::MINUTE:
				return date('i', $this->ts);
			case sfTime::HOUR:
				return date('H', $this->ts);
			case sfTime::DAY:
				return date('d', $this->ts);
			case sfTime::WEEK:
				return date('W', $this->ts);
			case sfTime::MONTH:
				return date('m', $this->ts);
			case sfTime::QUARTER:
				return ceil(date('m', $this->ts) / 3);
			case sfTime::YEAR:
				return date('Y', $this->ts);
			case sfTime::DECADE:
				return ceil((date('Y', $this->ts) % 100) / 10);
			case sfTime::CENTURY:
				return ceil(date('Y', $this->ts) / 100);
			case sfTime::MILLENIUM:
				return ceil(date('Y', $this->ts) / 1000);
			default:
				throw new sfDateTimeException(sprintf('The unit of time provided is not valid: %s', $unit));
		}
	}
	
	/**
	 * Retrieve the timestamp value of this sfDate instance.
	 *
	 * @return	timestamp
	 */
	public function get()
	{
		return $this->ts;
	}
	
	/**
	 * Sets the timestamp value of this sfDate instance.
	 *
	 * This function accepts several froms of a date value:
	 * - timestamp
	 * - string, parsed with <code>strtotime</code>
	 * - sfDate object
	 *
	 * @return	sfDate	the modified object, for chainability
	 */
	public function set($value = null)
	{
		// get the timestamp from $value
		$ts		= sfDateTimeToolkit::getTS($value);
		
		// copy the timestamp to the 
		$this->ts	= $ts;
		if ($this->init === null)	$this->init = $ts;

		// return the object itself		
		return $this;
	}

	/**
	 * Resets the timestamp value of this sfDate instance to its original value.
	 *
	 * @return	sfDate	the reset object, for chainability
	 */
	public function reset()
	{
		$this->ts = $this->init;
		
		return $this;
	}
	
	/**
	 * Compares two date values.
	 *
	 * @param	mixed	timestamp, string, or sfDate object
	 * @return	int		-1, 0, or 1
	 */
	public function cmp($value)
	{
		$ts = sfDateTimeToolkit::getTS($value);
		
		if ($this->ts < $ts)
		{
			// less than
			return -1;
		}
		else if ($this->ts > $ts)
		{
			// greater than
			return 1;
		}

		// equal to
		return 0;
	}
	
	/**
	 * Gets the difference of two date values in a sfTime unit
	 *
	 * @param	mixed	timestamp, string, or sfDate object
	 * @param	int	the unit to diff by (default to sfTime::SECOND)
	 * @return	int	the difference in the unit
	 * @throws	sfDateTimeException
	 */
	public function diff($other, $unit = sfTime::SECOND)
	{
		// jme- modification to get a $unit
		$other_ts	= sfDateTimeToolkit::getTS($other);
		$diff_ts	= $this->ts - $other_ts;

		// determine which unit of time to add by
		switch ($unit)
		{
			case sfTime::SECOND:
				$factor	= 1;
				break;
			case sfTime::MINUTE:
				$factor	= 1*60;
				break;
			case sfTime::HOUR:
				$factor	= 1*60*60;
				break;
			case sfTime::DAY:
				$factor	= 1*60*60*24;
				break;
			case sfTime::WEEK:
				$factor	= 1*60*60*24*7;
				break;
			// jme- not doing the rest due to some special cases
			// - TODO to do later
			// - e.g. how many days in a MONTH ? 28, 29, 30, 31 ?
			//   - rought approximation ok ? NO! as it may lead to confusion
			case sfTime::MONTH:
			case sfTime::QUARTER:
			case sfTime::YEAR:
			case sfTime::DECADE:
			case sfTime::CENTURY:
			case sfTime::MILLENIUM:
			default:
				throw new sfDateTimeException(sprintf('The unit of time provided is not valid: %s', $unit));
		}

		// compute and return the result
		return (int)(($diff_ts + ($factor/2)) / $factor);
	}

	/**
	 * Gets the difference of two date values in sfTime::SECOND
	 *
	 * @param	mixed	timestamp, string, or sfDate object
	 * @return	int	the difference in the unit
	 * @throws	sfDateTimeException
	 */
	public function diffSecond($other)
	{
		return $this->diff($other, sfTime::SECOND);
	}	
	/**
	 * Gets the difference of two date values in sfTime::MINUTE
	 *
	 * @param	mixed	timestamp, string, or sfDate object
	 * @return	int	the difference in the unit
	 * @throws	sfDateTimeException
	 */
	public function diffMinute($other)
	{
		return $this->diff($other, sfTime::MINUTE);
	}
	/**
	 * Gets the difference of two date values in sfTime::HOUR
	 *
	 * @param	mixed	timestamp, string, or sfDate object
	 * @return	int	the difference in the unit
	 * @throws	sfDateTimeException
	 */
	public function diffHour($other)
	{
		return $this->diff($other, sfTime::HOUR);
	}
	/**
	 * Gets the difference of two date values in sfTime::DAY
	 *
	 * @param	$other	mixed	timestamp, string, or sfDate object
	 * @return	int	the difference in the unit
	 * @throws	sfDateTimeException
	 */
	public function diffDay($other)
	{
		return $this->diff($other, sfTime::DAY);
	}
	/**
	 * Gets the difference of two date values in sfTime::WEEK
	 *
	 * @param	$other	mixed	timestamp, string, or sfDate object
	 * @return	int		the difference in the unit
	 * @throws	sfDateTimeException
	 */
	public function diffWeek($other)
	{
		return $this->diff($other, sfTime::WEEK);
	}
	
	/********************************************************************************/
	/********************************************************************************/
	/*		isHollyday() functions						*/
	/********************************************************************************/
	/********************************************************************************/
	/**
	 * @return	boolean 	true if the current day is Holiday
	*/
	public function isHoliday($culture)
	{
		if( $culture == "fr" )	return isHolidayFr();
		throw new sfDateTimeException("the culture ".$culture." is not implemented. sorry");
	}
	
	/**
	 * @return	boolean		true if date is Holiday in culture 'fr'
	*/
	public function isHolidayFr()
	{
		// add fixed holydays
		$holidays	= array(
			"Nouvel an"		=> array(	'month'	=> 1	, 'day'	=> 1	),
			"Armistice 39-45"	=> array(	'month'	=> 5	, 'day'	=> 8	),
			"Toussaint"		=> array(	'month'	=> 11	, 'day'	=> 1	),
			"Armistice 14-18"	=> array(	'month'	=> 11	, 'day'	=> 11	),
			"Assomption"		=> array(	'month'	=> 8	, 'day'	=> 15	),
			"Fete du travail"	=> array(	'month'	=> 5	, 'day'	=> 1	),
			"Fete nationnale"	=> array(	'month'	=> 7	, 'day'	=> 14	),
			"Noel"			=> array(	'month'	=> 12	, 'day'	=> 25	),
		);
		
		// add mobile holidays too

		// add easter_day
		$easter_monday_date	= sfDate::getInstance(easter_date($this->getYear()))->addDay(1);
		$holidays['Lundi de Paques']	= array( 'month'	=> $easter_monday_date->getMonth(),
							 'day'		=> $easter_monday_date->getDay()
							);
		$ascension_date	= sfDate::getInstance(easter_date($this->getYear()))->addDay(39);
		$holidays['Ascenscion']		= array( 'month'	=> $ascension_date->getMonth(),
							 'day'		=> $ascension_date->getDay()
							);
		$pentecote_date	= sfDate::getInstance(easter_date($this->getYear()))->addDay(49);
		$holidays['Pentecote']		= array( 'month'	=> $pentecote_date->getMonth(),
							 'day'		=> $pentecote_date->getDay()
							);
		
		// test if the current date is in Holidays
		$cur_month	= $this->retrieve(sfTime::MONTH);
		$cur_day	= $this->retrieve(sfTime::DAY);
		foreach($holidays as $key => $val){
			// if not the proper month, goto the next
			if( $cur_month	!= $val['month'] )	continue;
			// if not the proper day, goto the next
			if( $cur_day	!= $val['day'] )	continue;
			// return true;
			return true;
		}
		// if all the tests passed
		return false;
	}

	/********************************************************************************/
	/********************************************************************************/
	/*		all the getUNIT() functions					*/
	/********************************************************************************/
	/********************************************************************************/

	/**
	 * - only a shortcut on sfDate::retrieve
	 * @return integer return the seconds
	*/
	public function getSecond(){	return $this->retrieve(sfTime::SECOND);		}
	/**
	 * - only a shortcut on sfDate::retrieve
	 * @return integer return the minutes
	*/
	public function getMinute(){	return $this->retrieve(sfTime::MINUTE);		}
	/**
	 * - only a shortcut on sfDate::retrieve
	 * @return integer return the minutes
	*/
	public function getHour(){	return $this->retrieve(sfTime::HOUR);		}
	/**
	 * - only a shortcut on sfDate::retrieve
	 * @return integer return the days
	*/
	public function getDay(){	return $this->retrieve(sfTime::DAY);		}
	/**
	 * - only a shortcut on sfDate::retrieve
	 * @return integer return the month
	*/
	public function getMonth(){	return $this->retrieve(sfTime::MONTH);		}
	/**
	 * - only a shortcut on sfDate::retrieve
	 * @return integer return the year
	*/
	public function getYear(){	return $this->retrieve(sfTime::YEAR);		}
	/**
	 * - only a shortcut on sfDate::retrieve
	 * @return integer return the decade
	*/
	public function getDecade(){	return $this->retrieve(sfTime::YEAR);		}
	/**
	 * - only a shortcut on sfDate::retrieve
	 * @return integer return the century
	*/
	public function getCentury(){	return $this->retrieve(sfTime::CENTURY);	}
	/**
	 * - only a shortcut on sfDate::retrieve
	 * @return integer return the millenium
	*/
	public function getMillenium(){	return $this->retrieve(sfTime::MILLENIUM);	}

	/**
	 * Returns the timestamp for first day of the week for the given date.
	 *
	 * @param	timestamp
	 * @return	timestamp
	 */
	public function dayOfWeek()
	{
		return date('w', $this->ts);
	}

	/**
	 * Call any function available in the sfTime library, but without the ts parameter.
	 *
	 * <b>Example:</b>
	 * <code>
	 *   $ts = sfTime::firstDayOfMonth(sfTime::addMonth(time(), 5));
	 *   // equivalent
	 *   $dt = new sfDate();
	 *   $ts = $dt->addMonth(5)->firstDayOfMonth()->get();
	 * </code>
	 *
	 * @return	sfDate	the modified object, for chainability
	 */
	public function __call($method, $arguments)
	{
		$callable = array('sfTime', $method);
		
		if (!is_callable($callable))
		{
			throw new sfDateTimeException(sprintf('Call to undefined function: %s::%s', 'sfDate', $method));
		}
		
		array_unshift($arguments, $this->ts);
		
		$this->ts = call_user_func_array($callable, $arguments);
		
		return $this;
	}
}