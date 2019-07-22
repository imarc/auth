<?php

namespace Auth;

/**
 * An interface for objects which need auth management
 *
 * @copyright Copyright (c) 2019, Imarc LLC
 * @author Matthew J. Sahagian [mjs] <matthew.sahagian@gmail.com>
 *
 * @license MIT
 *
 * @package Auth
 */
interface ManagedInterface
{
	/**
	 * Provide the auth manager to the object
	 *
	 * @access public
	 * @param Manager $manager The auth manager containing the manged entity and permissions
	 * @return object The object instance for method chaining
	 */
	public function setAuthManager(Manager $manager): object;
}
