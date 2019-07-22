<?php

namespace Auth;

/**
 * An interface for objects which provide customized authorization logic
 *
 * @copyright Copyright (c) 2019, Imarc LLC
 * @author Matthew J. Sahagian [mjs] <matthew.sahagian@gmail.com>
 *
 * @license MIT
 *
 * @package Auth
 */
interface AuthInterface
{
	/**
	 * Custom functionality to check permission on the implementing instance
	 *
	 * @access public
	 * @param Manager $manager The auth manager containing the manged entity and permissions
	 * @param string $permission The permission we're checking
	 * @return boolean TRUE if permission is granted, FALSE otherwise
	 */
	public function can(Manager $manager, string $permission): bool;
}
