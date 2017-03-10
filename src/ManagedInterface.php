<?php

namespace iMarc\Auth;

/**
 * An interface for objects which need auth management
 *
 * @copyright Copyright (c) 2015, iMarc LLC
 * @author Matthew J. Sahagian [mjs] <msahagian@dotink.org>
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
	 * @return Object The object instance for method chaining
	 */
	public function setAuthManager(Manager $manager);
}
