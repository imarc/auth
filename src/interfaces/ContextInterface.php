<?php

namespace Auth;

/**
 * Enable objects to provide a custom context name
 *
 * @copyright Copyright (c) 2019, Imarc LLC
 * @author Matthew J. Sahagian [mjs] <matthew.sahagian@gmail.com>
 *
 * @license MIT
 *
 * @package Auth
 */
interface ContextInterface
{
	/**
	 * Get context name for the object.
	 *
	 * @access public
	 * @return string The context name for the object
	 */
	public function getAuthContext(): string;
}
