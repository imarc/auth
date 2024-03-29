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
	 * Custom functionality to check permission on the implementing instance.
	 *
	 * If the auth instance cannot determine authorization (returns NULL) then subsequent methods will
	 * be tried by the manager.
	 *
	 * @access public
	 * @param Manager $manager The auth manager containing the manged entity and permissions
	 * @param string $permission The permission we're checking
	 * @return boolean|null TRUE: has permission, FALSE: does not have permission, NULL: indeterminate
	 */
	public function can(Manager $manager, string $permission): ?bool;
}
