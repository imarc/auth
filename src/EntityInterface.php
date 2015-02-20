<?php

namespace iMarc\Auth;

/**
 * Make objects able to authorize access to themselves
 *
 * @copyright Copyright (c) 2015, iMarc LLC
 * @author Matthew J. Sahagian [mjs] <msahagian@dotink.org>
 *
 * @license MIT
 *
 * @package Auth
 */
interface EntityInterface
{
	/**
	 * Get entity specific permissions
	 *
	 * This should return an array where the key is the target name (usually a class) and the
	 * value is another array containing the permissions allowed to this role explicitly.  Note
	 * that these permissions should be assumed to be all of the allowed permissions, they will
	 * replace others not append to them.
	 *
	 * @access public
	 * @return array The specific permissions for the entity, $target => [$permission, ...]
	 */
	public function getPermissions();


	/**
	 * Get the roles which the entity has
	 *
	 * @access public
	 * @return array The roles which the entity has
	 */
	public function getRoles();
}
