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
 * sfDateTimeException is thrown when an error occurs while manipulating dates.
 *
 * @package		sfDateTimePlugin
 * @subpackage	exception
 * @author		Stephen Riesenberg <sjohnr@gmail.com>
 * @version		SVN: $Id$
 */
class sfDateTimeException extends \Exception
{
	/**
	 * Class constructor.
	 *
	 * @param	string	the error message
	 * @param	int		the error code
	 */
	public function __construct($message = null, $code = 0)
	{
		// jme- removed this function. it doesnt exist
		// - in sfExceptions.php you can find
		//   - name    = get_class($exception);
		// $this->setName('sfDateTimeException');
		parent::__construct($message, $code);
	}
}