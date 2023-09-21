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
interface ServiceInterface
{
	/**
	 * Entry point for the service.
	 *
	 * If the service cannot determine authorization (returns NULL) then subsequent methods will
	 * be tried by the manager.
	 *
	 * @param Manager $auth The auth manager
	 * @param string $permission The permission we're checking
	 * @param mixed $context The context to check against, usually an object or string
	 * @return boolean|null TRUE: has permission, FALSE: does not have permission, NULL: indeterminate
	 */
	public function __invoke(Manager $manager, string $permission): ?bool;
}
